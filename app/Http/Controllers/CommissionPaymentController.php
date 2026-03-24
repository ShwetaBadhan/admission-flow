<?php

namespace App\Http\Controllers;

use App\Models\CommissionPayment;
use App\Models\CommissionRule;
use App\Models\AdmissionRequest;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = CommissionPayment::with([
            'consultant',
            'admissionRequest.lead',
            'commissionRule',
            'createdBy'
        ])->latest();

        // Filters
        if ($request->filled('consultant_id')) {
            $query->where('consultant_id', $request->consultant_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date . ' 23:59:59'
            ]);
        }

        $payments = $query->paginate(15);
        $consultants = Consultant::where('status', 1)->orderBy('name')->get();
        $statuses = ['pending', 'paid', 'cancelled'];

        // Summary stats
        $totalPending = CommissionPayment::where('status', 'pending')->sum('calculated_amount');
        $totalPaid = CommissionPayment::where('status', 'paid')->sum('calculated_amount');

        return view('pages.commission-payments.index', compact(
            'payments',
            'consultants',
            'statuses',
            'totalPending',
            'totalPaid'
        ));
    }

    public function show(CommissionPayment $payment)
    {
        $payment->load([
            'consultant',
            'admissionRequest.lead',
            'admissionRequest.college',
            'admissionRequest.course',
            'commissionRule',
            'createdBy'
        ]);

        return view('pages.commission-payments.show', compact('payment'));
    }

    /**
     * Generate commission payment for accepted admission
     */
    public function generatePayment(AdmissionRequest $admission)
    {
        if ($admission->status !== 'accepted') {
            return back()->with('error', 'Commission can only be generated for accepted admissions.');
        }

        $existingPayment = CommissionPayment::where('admission_request_id', $admission->id)->first();
        if ($existingPayment) {
            return back()->with('error', 'Commission payment already exists for this admission.');
        }

        $lead = $admission->lead;
        if (!$lead || !$lead->consultant) {
            return back()->with('error', 'No consultant assigned to this lead.');
        }

        $consultant = $lead->consultant;

        // ✅ Get course NAME from admission request (since CommissionRule uses course_name string)
        $courseName = $admission->course->name ?? $admission->course_name ?? null;

        if (!$courseName) {
            return back()->with('error', 'Course name not found for this admission.');
        }

        // ✅ Pass course NAME (not ID) to matching method
        $commissionRule = $this->findMatchingCommissionRule(
            $consultant->id,
            $admission->college_id,
            $courseName  // ✅ String comparison
        );

        if (!$commissionRule) {
            return back()->with('error', "No commission rule found for consultant '{$consultant->name}', course '{$courseName}'" .
                ($admission->college_id ? ", college ID {$admission->college_id}" : ""));
        }

        if ($commissionRule->status !== 'active') {
            return back()->with('error', 'Commission rule is not active.');
        }

        $calculatedAmount = $this->calculateCommission($commissionRule, $admission);

        $payment = CommissionPayment::create([
            'consultant_id' => $consultant->id,
            'admission_request_id' => $admission->id,
            'commission_rule_id' => $commissionRule->id,
            'lead_id' => $lead->id,
            'payment_type' => $commissionRule->commission_type,
            'commission_value' => $commissionRule->commission_value,
            'calculated_amount' => $calculatedAmount,
            'currency' => $commissionRule->currency,
            'status' => 'pending',
            'created_by' => Auth::id(),
            'notes' => 'Auto-generated for accepted admission #' . $admission->id
        ]);

        if ($lead) {
            $lead->communications()->create([
                'type' => 'note',
                'content' => "Commission payment #{$payment->id} generated: {$payment->calculated_amount} {$payment->currency}",
                'created_by' => Auth::id(),
                'status' => 'completed'
            ]);
        }

        return back()->with('success', 'Commission payment generated successfully! Amount: ' .
            number_format($calculatedAmount, 2) . ' ' . $commissionRule->currency);
    }

    /**
     * Find matching commission rule (FIXED for course_name as string)
     */
    private function findMatchingCommissionRule($consultantId, $collegeId, $courseName)
    {
        // First try to find rule with specific college + exact course name match
        $rule = CommissionRule::where('consultant_id', $consultantId)
            ->where('college_id', $collegeId)
            ->where('course_name', $courseName)  // ✅ Compare string directly
            ->where('status', 'active')
            ->first();

        // If not found, try rule without college (general course rule)
        if (!$rule) {
            $rule = CommissionRule::where('consultant_id', $consultantId)
                ->whereNull('college_id')
                ->where('course_name', $courseName)  // ✅ Compare string directly
                ->where('status', 'active')
                ->first();
        }

        return $rule;
    }

    /**
     * Calculate commission amount
     */
    private function calculateCommission($rule, $admission)
    {
        // ✅ Fallback: try multiple possible field names for tuition fee
        $tuitionFee = $admission->tuition_fee
            ?? $admission->course_fee
            ?? $admission->course->fee ?? 0;

        if ($rule->commission_type === 'percentage') {
            return ($tuitionFee * $rule->commission_value) / 100;
        }

        return $rule->commission_value; // Fixed amount
    }

    /**
 * API: List commission payments (JSON)
 */
public function apiIndex(Request $request)
{
    $query = CommissionPayment::with(['consultant', 'admissionRequest.lead'])
        ->latest();

    // Filters
    if ($request->filled('consultant_id')) {
        $query->where('consultant_id', $request->consultant_id);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date,
            $request->end_date . ' 23:59:59'
        ]);
    }

    $payments = $query->paginate(10);

    return response()->json([
        'data' => $payments->items(),
        'current_page' => $payments->currentPage(),
        'last_page' => $payments->lastPage(),
        'total' => $payments->total(),
        'total_pending' => CommissionPayment::where('status', 'pending')->sum('calculated_amount'),
        'total_paid' => CommissionPayment::where('status', 'paid')->sum('calculated_amount'),
    ]);
}

/**
 * API: Show single payment details
 */
public function apiShow(CommissionPayment $payment)
{
    $payment->load(['consultant', 'admissionRequest.lead', 'commissionRule']);
    
    return response()->json([
        'id' => $payment->id,
        'status' => $payment->status,
        'consultant_name' => $payment->consultant->name ?? 'N/A',
        'lead_name' => $payment->admissionRequest->lead->full_name ?? 'N/A',
        'calculated_amount' => number_format($payment->calculated_amount, 2),
        'currency' => $payment->currency,
        'payment_type' => $payment->payment_type,
        'commission_value' => $payment->commission_value,
        'payment_reference' => $payment->payment_reference,
        'payment_date' => $payment->payment_date?->format('Y-m-d'),
        'notes' => $payment->notes,
        'created_at' => $payment->created_at->format('Y-m-d H:i'),
    ]);
}

/**
 * Mark payment as paid
 */
public function markAsPaid(Request $request, CommissionPayment $payment)
{
    // ✅ ADD THIS: Validate the request
    $validated = $request->validate([
        'payment_date' => 'required|date',
        'payment_reference' => 'required|string|max:255',
        'notes' => 'nullable|string'
    ]);

    $payment->update([
        'status' => 'paid',
        'payment_date' => $validated['payment_date'],
        'payment_reference' => $validated['payment_reference'],
        'paid_by' => Auth::id(),
        'notes' => $validated['notes'] ?? $payment->notes
    ]);

    // Log communication
    if ($payment->admissionRequest && $payment->admissionRequest->lead) {
        $payment->admissionRequest->lead->communications()->create([
            'type' => 'note',
            'content' => "Commission payment #{$payment->id} marked as paid. Ref: {$validated['payment_reference']}",
            'created_by' => Auth::id(),
            'status' => 'completed'
        ]);
    }

    return back()->with('success', 'Payment marked as paid successfully!');
}

/**
 * Cancel payment
 */
public function cancel(CommissionPayment $payment)
{
    $payment->update([
        'status' => 'cancelled',
        'paid_by' => Auth::id()
    ]);

    return back()->with('success', 'Payment cancelled successfully!');
}

/**
 * Bulk generate payments for accepted admissions
 */
public function bulkGenerate(Request $request)
{
    $consultantId = $request->input('consultant_id');

    $query = AdmissionRequest::where('status', 'accepted')
        ->whereDoesntHave('commissionPayments');

    if ($consultantId) {
        $query->whereHas('lead', function($q) use ($consultantId) {
            $q->where('consultant_id', $consultantId);
        });
    }

    $admissions = $query->get();
    $generated = 0;
    $failed = 0;

    DB::beginTransaction();
    try {
        foreach ($admissions as $admission) {
            try {
                $this->generatePayment($admission);
                $generated++;
            } catch (\Exception $e) {
                $failed++;
                Log::error('Payment generation failed for admission ' . $admission->id . ': ' . $e->getMessage());
            }
        }
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Bulk generation failed: ' . $e->getMessage());
    }

    return back()->with('success', "Generated {$generated} payments. {$failed} failed.");
}
}