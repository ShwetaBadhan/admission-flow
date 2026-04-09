<?php

namespace App\Http\Controllers;

use App\Models\CommissionPayment;
use App\Models\CommissionRule;
use App\Models\AdmissionRequest;
use App\Models\Consultant;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SlabRule; // ✅ Add this

class CommissionPaymentController extends Controller
{
    /**
     * Generate commission payment for accepted admission
     * ✅ NOW WITH AUTO SLAB BONUS DETECTION
     */
    public function generatePayment(AdmissionRequest $admission)
    {
        // Validation checks
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
        $courseName = $admission->course->name ?? $admission->course_name ?? null;

        if (!$courseName) {
            return back()->with('error', 'Course name not found for this admission.');
        }

        // 🔹 Find base commission rule
        $commissionRule = $this->findMatchingCommissionRule(
            $consultant->id,
            $admission->college_id,
            $courseName
        );

        if (!$commissionRule || $commissionRule->status !== 'active') {
            return back()->with('error', 'No active commission rule found.');
        }

        // 🔹 Calculate BASE commission only
        $tuitionFee = $admission->tuition_fee ?? $admission->course_fee ?? $admission->course->fee ?? 0;
        $baseCommission = $this->calculateBaseCommission($commissionRule, $tuitionFee);

        // 🔹 🔥 AUTO SLAB BONUS LOGIC 🔥
        $slabBonus = 0;
        $appliedSlabs = [];
        $applicableSlabs = $this->getApplicableSlabRules($consultant->id, $admission->college_id, $courseName);

        foreach ($applicableSlabs as $slabRule) {
            // Count admissions based on slab scope
            $admissionCount = $this->getConsultantAdmissionCount(
                $consultant->id,
                $slabRule->scope === 'per_college' ? $admission->college_id : null
            );

            // ✅ Check if threshold is met (including CURRENT admission)
            if ($admissionCount >= $slabRule->threshold) {
                $bonus = $slabRule->calculateBonus($baseCommission, $tuitionFee);
                $slabBonus += $bonus;
                
                $appliedSlabs[] = [
                    'slab_rule_id' => $slabRule->id,
                    'threshold' => $slabRule->threshold,
                    'bonus_type' => $slabRule->bonus_type,
                    'bonus_value' => $slabRule->bonus_value,
                    'bonus_amount' => $bonus,
                    'admission_count' => $admissionCount,
                    'applied_at' => now()
                ];

                // 🎯 RETROACTIVE: Apply bonus to previous unpaid admissions
                if ($slabRule->retroactive) {
                    $this->applySlabRetroactively($slabRule, $consultant->id, $admission->college_id, $baseCommission, $tuitionFee);
                }
            }
        }

        $finalAmount = $baseCommission + $slabBonus;

        // 🔹 Create payment record with slab details
        $payment = CommissionPayment::create([
            'consultant_id' => $consultant->id,
            'admission_request_id' => $admission->id,
            'commission_rule_id' => $commissionRule->id,
            'lead_id' => $lead->id,
            'payment_type' => $commissionRule->commission_type,
            'commission_value' => $commissionRule->commission_value,
            'calculated_amount' => $finalAmount,
            'base_amount' => $baseCommission,           // ✅ Store base separately
            'slab_bonus_amount' => $slabBonus,          // ✅ Store bonus separately
            'slab_details' => json_encode($appliedSlabs), // ✅ Store which slabs applied
            'currency' => $commissionRule->currency,
            'status' => 'pending',
            'created_by' => Auth::id(),
            'notes' => 'Auto-generated' . ($slabBonus > 0 ? ' | 🎯 Slab bonus: +' . number_format($slabBonus, 2) : '')
        ]);

        // 🔹 Log communication for transparency
        if ($lead) {
            $bonusNote = $slabBonus > 0 ? " + ₹{$slabBonus} slab bonus (threshold met)" : "";
            $lead->communications()->create([
                'type' => 'system',
                'content' => "Commission #{$payment->id}: ₹{$baseCommission}{$bonusNote} | Total: ₹{$finalAmount}",
                'created_by' => Auth::id(),
                'status' => 'completed'
            ]);
        }

        $bonusMsg = $slabBonus > 0 ? " (+₹" . number_format($slabBonus, 2) . " 🎯 slab bonus)" : "";
        return back()->with('success', 'Commission generated: ₹' . number_format($finalAmount, 2) . $bonusMsg);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Calculate ONLY base commission (without slab)
     */
    private function calculateBaseCommission($rule, $tuitionFee)
    {
        return $rule->commission_type === 'percentage' 
            ? ($tuitionFee * $rule->commission_value) / 100
            : $rule->commission_value;
    }

    /**
     * Get all active SlabRules applicable to this context
     */
    private function getApplicableSlabRules($consultantId, $collegeId, $courseName)
    {
        return SlabRule::with(['consultant', 'college'])
            ->where('status', 'active')
            ->get()
            ->filter(function($rule) use ($consultantId, $collegeId, $courseName) {
                // Consultant match (null = all consultants)
                if ($rule->consultant_id && $rule->consultant_id !== $consultantId) {
                    return false;
                }
                // College match (null = all colleges)
                if ($rule->college_id && $rule->college_id !== $collegeId) {
                    return false;
                }
                // Course match (null = all courses)
                if ($rule->course_name && $rule->course_name !== $courseName) {
                    return false;
                }
                return true;
            })
            ->sortBy('threshold'); // Apply lower thresholds first
    }

    /**
     * Count accepted admissions for slab calculation
     */
    private function getConsultantAdmissionCount($consultantId, $collegeId = null)
    {
        $query = AdmissionRequest::where('status', 'accepted')
            ->whereHas('lead', function($q) use ($consultantId) {
                $q->where('consultant_id', $consultantId);
            });

        if ($collegeId) {
            $query->where('college_id', $collegeId);
        }

        return $query->count();
    }

    /**
     * 🔥 Apply slab bonus retroactively to previous unpaid payments
     */
    private function applySlabRetroactively($slabRule, $consultantId, $collegeId, $baseCommission, $tuitionFee)
    {
        // Get previous payments that haven't had this slab applied yet
        $previousPayments = CommissionPayment::where('consultant_id', $consultantId)
            ->where('status', '!=', 'paid') // Skip already paid to avoid accounting issues
            ->whereDoesntHave('admissionRequest', function($q) use ($collegeId, $slabRule) {
                // Only update payments within the slab's scope
                if ($slabRule->scope === 'per_college' && $collegeId) {
                    $q->where('college_id', $collegeId);
                }
            })
            ->get();

        if ($previousPayments->isEmpty()) {
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($previousPayments as $payment) {
                // Get existing slab details or initialize empty array
                $existingSlabs = json_decode($payment->slab_details ?? '[]', true);
                
                // Check if this slab rule was already applied
                $alreadyApplied = collect($existingSlabs)->contains('slab_rule_id', $slabRule->id);
                if ($alreadyApplied) {
                    continue; // Skip if already applied
                }

                // Calculate bonus for this payment
                $admission = $payment->admissionRequest;
                $fee = $admission->tuition_fee ?? $admission->course_fee ?? $admission->course->fee ?? 0;
                $base = $this->calculateBaseCommission($payment->commissionRule ?? $slabRule, $fee);
                $bonus = $slabRule->calculateBonus($base, $fee);

                // Add new slab to details
                $existingSlabs[] = [
                    'slab_rule_id' => $slabRule->id,
                    'threshold' => $slabRule->threshold,
                    'bonus_type' => $slabRule->bonus_type,
                    'bonus_value' => $slabRule->bonus_value,
                    'bonus_amount' => $bonus,
                    'applied_at' => now(),
                    'applied_retroactively' => true
                ];

                // Update payment
                $payment->update([
                    'calculated_amount' => $payment->base_amount + array_sum(array_column($existingSlabs, 'bonus_amount')),
                    'slab_bonus_amount' => array_sum(array_column($existingSlabs, 'bonus_amount')),
                    'slab_details' => json_encode($existingSlabs),
                    'notes' => ($payment->notes ?? '') . ' | 🔄 Slab #' . $slabRule->id . ' applied retroactively'
                ]);

                // Log audit trail
                if ($admission->lead) {
                    $admission->lead->communications()->create([
                        'type' => 'system',
                        'content' => "Commission #{$payment->id} updated: +₹{$bonus} slab bonus applied retroactively (threshold #{$slabRule->threshold} met)",
                        'created_by' => Auth::id(),
                        'status' => 'completed'
                    ]);
                }
            }
            DB::commit();
            Log::info("Retroactive slab applied: Rule #{$slabRule->id} to {$previousPayments->count()} payments");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Retroactive slab application failed: ' . $e->getMessage());
            // Don't break main flow - just log error
        }
    }

    /**
     * Find matching commission rule (existing method - keep as is)
     */
    private function findMatchingCommissionRule($consultantId, $collegeId, $courseName)
    {
        $rule = CommissionRule::where('consultant_id', $consultantId)
            ->where('college_id', $collegeId)
            ->where('course_name', $courseName)
            ->where('status', 'active')
            ->first();

        if (!$rule) {
            $rule = CommissionRule::where('consultant_id', $consultantId)
                ->whereNull('college_id')
                ->where('course_name', $courseName)
                ->where('status', 'active')
                ->first();
        }

        return $rule;
    }
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
public function generateForConsultant(Request $request)
{
    $validated = $request->validate([
        'consultant_id' => 'required|exists:consultants,id',
        'college_id' => 'nullable|exists:colleges,id',
    ]);

    $consultant = Consultant::findOrFail($validated['consultant_id']);
    
    // Find accepted admissions without existing payments
    $query = AdmissionRequest::where('status', 'accepted')
        ->whereHas('lead', function($q) use ($validated) {
            $q->where('consultant_id', $validated['consultant_id']);
        })
        ->whereDoesntHave('commissionPayments'); // Only admissions without payments

    // Optional: Filter by college
    if ($validated['college_id']) {
        $query->where('college_id', $validated['college_id']);
    }

    $admissions = $query->get();
    
    if ($admissions->isEmpty()) {
        return back()->with('info', 'No pending admissions found for this consultant.');
    }

    $generated = 0;
    $failed = 0;
    $errors = [];

    DB::beginTransaction();
    try {
        foreach ($admissions as $admission) {
            try {
                // Re-use existing generatePayment logic
                $this->generatePayment($admission);
                $generated++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Admission #{$admission->id}: " . $e->getMessage();
                Log::error('Payment generation failed for admission ' . $admission->id . ': ' . $e->getMessage());
            }
        }
        DB::commit();
        
        $message = "✅ Generated {$generated} payments for {$consultant->name}.";
        if ($failed > 0) {
            $message .= " ⚠️ {$failed} failed.";
        }
        
        return back()->with('success', $message);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Bulk generation failed: ' . $e->getMessage());
    }
}
    /**
     * Generate commission payment for accepted admission
     */
    // public function generatePayment(AdmissionRequest $admission)
    // {
    //     if ($admission->status !== 'accepted') {
    //         return back()->with('error', 'Commission can only be generated for accepted admissions.');
    //     }

    //     $existingPayment = CommissionPayment::where('admission_request_id', $admission->id)->first();
    //     if ($existingPayment) {
    //         return back()->with('error', 'Commission payment already exists for this admission.');
    //     }

    //     $lead = $admission->lead;
    //     if (!$lead || !$lead->consultant) {
    //         return back()->with('error', 'No consultant assigned to this lead.');
    //     }

    //     $consultant = $lead->consultant;

    //     // ✅ Get course NAME from admission request (since CommissionRule uses course_name string)
    //     $courseName = $admission->course->name ?? $admission->course_name ?? null;

    //     if (!$courseName) {
    //         return back()->with('error', 'Course name not found for this admission.');
    //     }

    //     // ✅ Pass course NAME (not ID) to matching method
    //     $commissionRule = $this->findMatchingCommissionRule(
    //         $consultant->id,
    //         $admission->college_id,
    //         $courseName  // ✅ String comparison
    //     );

    //     if (!$commissionRule) {
    //         return back()->with('error', "No commission rule found for consultant '{$consultant->name}', course '{$courseName}'" .
    //             ($admission->college_id ? ", college ID {$admission->college_id}" : ""));
    //     }

    //     if ($commissionRule->status !== 'active') {
    //         return back()->with('error', 'Commission rule is not active.');
    //     }

    //     $calculatedAmount = $this->calculateCommission($commissionRule, $admission);

    //     $payment = CommissionPayment::create([
    //         'consultant_id' => $consultant->id,
    //         'admission_request_id' => $admission->id,
    //         'commission_rule_id' => $commissionRule->id,
    //         'lead_id' => $lead->id,
    //         'payment_type' => $commissionRule->commission_type,
    //         'commission_value' => $commissionRule->commission_value,
    //         'calculated_amount' => $calculatedAmount,
    //         'currency' => $commissionRule->currency,
    //         'status' => 'pending',
    //         'created_by' => Auth::id(),
    //         'notes' => 'Auto-generated for accepted admission #' . $admission->id
    //     ]);

    //     if ($lead) {
    //         $lead->communications()->create([
    //             'type' => 'note',
    //             'content' => "Commission payment #{$payment->id} generated: {$payment->calculated_amount} {$payment->currency}",
    //             'created_by' => Auth::id(),
    //             'status' => 'completed'
    //         ]);
    //     }

    //     return back()->with('success', 'Commission payment generated successfully! Amount: ' .
    //         number_format($calculatedAmount, 2) . ' ' . $commissionRule->currency);
    // }

    /**
     * Find matching commission rule (FIXED for course_name as string)
     */
    // private function findMatchingCommissionRule($consultantId, $collegeId, $courseName)
    // {
    //     // First try to find rule with specific college + exact course name match
    //     $rule = CommissionRule::where('consultant_id', $consultantId)
    //         ->where('college_id', $collegeId)
    //         ->where('course_name', $courseName)  // ✅ Compare string directly
    //         ->where('status', 'active')
    //         ->first();

    //     // If not found, try rule without college (general course rule)
    //     if (!$rule) {
    //         $rule = CommissionRule::where('consultant_id', $consultantId)
    //             ->whereNull('college_id')
    //             ->where('course_name', $courseName)  // ✅ Compare string directly
    //             ->where('status', 'active')
    //             ->first();
    //     }

    //     return $rule;
    // }

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
 $colleges = College::where('status', 1)->orderBy('name')->get(); // ✅ Add this
    return response()->json([
        'data' => $payments->items(),
        'current_page' => $payments->currentPage(),
        'last_page' => $payments->lastPage(),
        'total' => $payments->total(),
        'colleges',
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