<?php

namespace App\Http\Controllers;

use App\Models\ConsultantPaymentRequest;
use App\Models\CommissionPayment;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultantPaymentRequestController extends Controller
{
    /**
     * Helper: Check if user has consultant role & get consultant record
     */
    private function getAuthenticatedConsultant(Request $request)
    {
        $user = $request->user();

        // ✅ Check role (adjust based on your role system)
        // Option A: If using spatie/laravel-permission
        if (method_exists($user, 'hasRole') && !$user->hasRole('consultant')) {
            return null;
        }

        // Option B: If using custom 'role' field in users table
        if (isset($user->role) && $user->role !== 'consultant') {
            return null;
        }

        // Option C: If using 'is_consultant' boolean field
        if (isset($user->is_consultant) && !$user->is_consultant) {
            return null;
        }

        // ✅ Now fetch consultant record (if linked)
        $consultant = $user->consultant ?? Consultant::where('id', $user->id)->first();

        return $consultant;
    }

    /**
     * Consultant: View their own payment requests
     */
    public function index(Request $request)
    {
        $consultant = $this->getAuthenticatedConsultant($request);

        if (!$consultant) {
            return redirect()->back()->with('error', 'Consultant access required.');
        }

        $requests = ConsultantPaymentRequest::where('consultant_id', $consultant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.consultants.payment-requests.index', compact('requests', 'consultant'));
    }

    /**
     * Consultant: Form to create new payment request
     */
    public function create(Request $request)
    {
        $consultant = $this->getAuthenticatedConsultant($request);

        if (!$consultant) {
            return redirect()->back()->with('error', 'Consultant access required.');
        }

        // Get pending commission payments for this consultant
        $pendingPayments = CommissionPayment::where('consultant_id', $consultant->id)
            ->where('status', 'pending')
            ->with(['admissionRequest.lead', 'admissionRequest.college'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPending = $pendingPayments->sum('calculated_amount');

        return view('pages.consultants.payment-requests.create', compact('pendingPayments', 'totalPending', 'consultant'));
    }

    /**
     * Consultant: Store new payment request
     */
    public function store(Request $request)
    {
        $consultant = $this->getAuthenticatedConsultant($request);

        if (!$consultant) {
            return redirect()->back()->with('error', 'Consultant access required.');
        }

        $validated = $request->validate([
            'admission_ids' => 'required|array|min:1',
            'admission_ids.*' => 'exists:commission_payments,id',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verify all selected payments belong to this consultant and are pending
        $selectedPayments = CommissionPayment::whereIn('id', $validated['admission_ids'])
            ->where('consultant_id', $consultant->id)
            ->where('status', 'pending')
            ->get();

        if ($selectedPayments->count() !== count($validated['admission_ids'])) {
            return back()->with('error', 'Invalid payment selection. Some payments may already be processed.');
        }

        $totalAmount = $selectedPayments->sum('calculated_amount');

        DB::beginTransaction();
        try {
            $paymentRequest = ConsultantPaymentRequest::create([
                'consultant_id' => $consultant->id,
                'requested_amount' => $totalAmount,
                'notes' => $validated['notes'],
                'admission_ids' => $validated['admission_ids'],
                'status' => 'pending',
            ]);

            DB::commit();
            return redirect()->route('consultant.payment-requests.index')
                ->with('success', 'Payment request #' . $paymentRequest->id . ' submitted successfully! Amount: ₹' . number_format($totalAmount, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment request creation failed: ' . $e->getMessage());
            return back()->with('error', 'Request submission failed. Please try again.');
        }
    }

    /**
     * Admin: View all payment requests
     */
    public function adminIndex(Request $request)
    {
        // ✅ Admin check (adjust based on your admin auth)
        $user = $request->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole('superadmin') : ($user->role ?? '') === 'admin';

        if (!$isAdmin) {
            abort(403, 'Admin access required.');
        }

        $query = ConsultantPaymentRequest::with(['consultant', 'approver'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('consultant_id')) {
            $query->where('consultant_id', $request->consultant_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('consultant', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $requests = $query->paginate(15);
        $consultants = Consultant::where('status', 1)->orderBy('name')->get();
        $statuses = ['pending', 'approved', 'rejected', 'paid'];

        return view('pages.consultants.payment-requests.index', compact('requests', 'consultants', 'statuses'));
    }

    /**
     * Admin: Approve payment request & mark payments as paid
     */
    public function approve(ConsultantPaymentRequest $request)
    {
        // ✅ Admin check
        $user = auth()->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole('superadmin') : ($user->role ?? '') === 'admin';

        if (!$isAdmin) {
            abort(403, 'Admin access required.');
        }

        if ($request->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        // ✅ ONLY update request status - DO NOT touch commission payments
        $request->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'notes' => ($request->notes ?? '') . "\n\n✅ Approved by " . (Auth::user()->name ?? 'Admin') . " on " . now()->format('d M Y'),
        ]);

        return back()->with('success', '✅ Request #' . $request->id . ' approved. Consultant can now expect payment. Payments remain pending until manually marked.');
    }
    /**
     * Admin: Reject payment request
     */
    public function reject(ConsultantPaymentRequest $request, Request $httpRequest)
    {
        // ✅ Admin check
        $user = auth()->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole('superadmin') : ($user->role ?? '') === 'admin';

        if (!$isAdmin) {
            abort(403, 'Admin access required.');
        }

        if ($request->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $validated = $httpRequest->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $request->update([
            'status' => 'rejected',
            'notes' => ($request->notes ?? '') . "\n\n❌ Rejected: " . $validated['rejection_reason'] . "\nBy: " . (Auth::user()->name ?? 'Admin') . " on " . now()->format('d M Y'),
        ]);

        return back()->with('success', 'Request #' . $request->id . ' rejected.');
    }
    /**
     * Show requested payments list
     */
    /**
     * Admin: View all payment requests from all consultants
     */
    public function requestedPayments(Request $request)
    {
        // ✅ Admin check
        $user = $request->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole('superadmin') : ($user->role ?? '') === 'admin';

        if (!$isAdmin) {
            abort(403, 'Admin access required.');
        }

        // Get all payment requests
        $query = ConsultantPaymentRequest::with(['consultant', 'approver']);

        // Filters
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('consultant_id')) $query->where('consultant_id', $request->consultant_id);
        if ($request->filled('search')) {
            $query->whereHas('consultant', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        $paymentRequests = $query->orderBy('created_at', 'desc')->paginate(20);

        // ✅ Preload ALL commission payments for ALL requests
        $allPaymentIds = $paymentRequests->flatMap(function ($req) {
            return $req->admission_ids ?? [];
        })->unique()->toArray();

        $allPayments = [];
        if (!empty($allPaymentIds)) {
            $paymentsCollection = CommissionPayment::with([
                'admissionRequest.lead',
                'admissionRequest.college'
            ])->whereIn('id', $allPaymentIds)->get();

            foreach ($paymentsCollection as $payment) {
                $allPayments[$payment->id] = [
                    'id' => $payment->id,
                    'admission_request_id' => $payment->admission_request_id,
                    'calculated_amount' => $payment->calculated_amount,
                    'status' => $payment->status,
                    'lead_name' => $payment->admissionRequest->lead->full_name ?? 'N/A',
                    'college_name' => $payment->admissionRequest->college->name ?? 'N/A',
                ];
            }
        }

        // Stats
        $totalRequests = $paymentRequests->total() ?? 0;
        $pendingRequests = ConsultantPaymentRequest::where('status', 'pending')->count() ?? 0;
        $totalPendingAmount = ConsultantPaymentRequest::where('status', 'pending')->sum('requested_amount') ?? 0;
        $totalApprovedAmount = ConsultantPaymentRequest::whereIn('status', ['approved', 'paid'])->sum('requested_amount') ?? 0;
        $totalRejected = ConsultantPaymentRequest::where('status', 'rejected')->count() ?? 0;

        $consultants = Consultant::where('status', 1)->orderBy('name')->get();
        $statuses = ['pending', 'approved', 'rejected', 'paid'];

        return view('pages.consultants.payment-requests.requested-payments', compact(
            'paymentRequests',
            'allPayments', // ✅ Pass preloaded payments
            'totalRequests',
            'pendingRequests',
            'totalPendingAmount',
            'totalApprovedAmount',
            'totalRejected',
            'consultants',
            'statuses'
        ));
    }
    /**
     * Admin: Actually make payment for an approved request (mark commission payments as paid)
     */
    public function makePayment(ConsultantPaymentRequest $request, Request $httpRequest)
    {
        // ✅ Admin check
        $user = auth()->user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole('superadmin') : ($user->role ?? '') === 'admin';

        if (!$isAdmin) {
            abort(403, 'Admin access required.');
        }

        // ✅ Can only make payment for approved requests
        if ($request->status !== 'approved') {
            return back()->with('error', 'Payment can only be made for approved requests.');
        }

        // ✅ Validate payment details
        $validated = $httpRequest->validate([
            'payment_reference' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:bank_transfer,cash,upi,cheque,other',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // ✅ Mark the request as paid
            $request->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_reference' => $validated['payment_reference'],
                'notes' => ($request->notes ?? '') . "\n\n💰 Paid via {$validated['payment_method']} on {$validated['payment_date']}\nRef: {$validated['payment_reference']}" . ($validated['payment_notes'] ? "\nNotes: {$validated['payment_notes']}" : ''),
            ]);

            // ✅ NOW mark individual commission payments as paid
            if (!empty($request->admission_ids) && is_array($request->admission_ids)) {
                CommissionPayment::whereIn('id', $request->admission_ids)
                    ->where('status', 'pending') // Only update pending ones
                    ->update([
                        'status' => 'paid',
                        'payment_date' => $validated['payment_date'],
                        'payment_reference' => $validated['payment_reference'],
                        'paid_by' => Auth::id(),
                    ]);
            }

            DB::commit();
            return back()->with('success', '💰 Payment recorded for Request #' . $request->id . '. ' . count($request->admission_ids) . ' commission payments marked as paid.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment recording failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }
}
