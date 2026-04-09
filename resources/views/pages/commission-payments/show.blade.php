@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content pb-0">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4">
            <div>
                <h4 class="mb-1">Payment Details #{{ $payment->id }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('commission-payments') }}">Payments</a></li>
                        <li class="breadcrumb-item active">#{{ $payment->id }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('commission-payments') }}" class="btn btn-light">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="row">
            <!-- 💰 PAYMENT BREAKDOWN CARD (LEFT) -->
            <div class="col-lg-8">
                
                <!-- Commission Breakdown with Slab Details -->
                <div class="card border-0 rounded-0 shadow-sm mb-4">
                    <div class="card-header bg-light d-flex align-items-center justify-content-between">
                        <h6 class="mb-0"><i class="ti ti-calculator me-2"></i>Commission Breakdown</h6>
                        @if($payment->slab_applied)
                            <span class="badge bg-success"><i class="ti ti-confetti me-1"></i>Slab Bonus Applied</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <!-- Base Commission -->
                                    <tr class="border-bottom">
                                        <td class="py-3">
                                            <span class="text-muted">Base Commission</span>
                                            <br><small class="text-muted">{{ ucfirst($payment->payment_type) }}: {{ $payment->commission_value }}{{ $payment->payment_type === 'percentage' ? '%' : '' }}</small>
                                        </td>
                                        <td class="text-end py-3 fw-medium">
                                            ₹{{ number_format($payment->base_amount ?? $payment->calculated_amount, 2) }}
                                        </td>
                                    </tr>
                                    
                                    <!-- Slab Bonus Section (if applied) -->
                                    @if(($payment->slab_bonus_amount ?? 0) > 0)
                                        <tr class="bg-success bg-opacity-10">
                                            <td class="py-3">
                                                <span class="text-success fw-medium">🎯 Slab Bonus</span>
                                                <br><small class="text-muted">Volume threshold met</small>
                                            </td>
                                            <td class="text-end py-3 text-success fw-bold">
                                                +₹{{ number_format($payment->slab_bonus_amount, 2) }}
                                            </td>
                                        </tr>
                                        
                                        <!-- Slab Details from JSON -->
                                        @php 
                                            $slabs = json_decode($payment->slab_details ?? '[]', true); 
                                        @endphp
                                        @if(!empty($slabs))
                                            @foreach($slabs as $index => $slab)
                                            <tr class="border-bottom">
                                                <td class="py-2 ps-4">
                                                    <small class="text-muted">
                                                        <i class="ti ti-arrow-right me-1"></i>
                                                        Threshold {{ $slab['threshold'] }} admissions
                                                        @if(isset($slab['bonus_type']))
                                                            <br><span class="badge bg-info bg-opacity-75">{{ str_replace('_', ' ', $slab['bonus_type']) }}</span>
                                                        @endif
                                                    </small>
                                                </td>
                                                <td class="text-end py-2">
                                                    <small class="text-success fw-medium">
                                                        +₹{{ number_format($slab['bonus_amount'] ?? 0, 2) }}
                                                    </small>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    
                                    <!-- TOTAL -->
                                    <tr class="border-top fw-bold fs-5">
                                        <td class="py-3">Total Commission</td>
                                        <td class="text-end py-3 text-primary">
                                            ₹{{ number_format($payment->calculated_amount, 2) }} {{ $payment->currency }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Slab Info Note -->
                        @if($payment->slab_applied)
                        <div class="alert alert-success mt-3 mb-0">
                            <small>
                                <i class="ti ti-info-circle me-1"></i>
                                <strong>Slab Bonus Applied:</strong> This payment includes volume-based bonus because the consultant met the admission threshold. 
                                @if($payment->slab_details)
                                    @php $slabs = json_decode($payment->slab_details, true); @endphp
                                    Bonus applied for threshold(s): {{ collect($slabs)->pluck('threshold')->join(', ') }} admissions.
                                @endif
                            </small>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Meta Info -->
                <div class="card border-0 rounded-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-info-circle me-2"></i>Payment Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small d-block">Status</label>
                                <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }} fs-6">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block">Created</label>
                                <p class="mb-0">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            @if($payment->status === 'paid')
                                @if($payment->payment_reference)
                                <div class="col-md-6">
                                    <label class="text-muted small d-block">Payment Reference</label>
                                    <p class="mb-0 fw-medium">{{ $payment->payment_reference }}</p>
                                </div>
                                @endif
                                @if($payment->payment_date)
                                <div class="col-md-6">
                                    <label class="text-muted small d-block">Paid On</label>
                                    <p class="mb-0">{{ $payment->payment_date->format('d M Y') }}</p>
                                </div>
                                @endif
                            @endif
                            @if($payment->notes)
                            <div class="col-12">
                                <label class="text-muted small d-block">Notes</label>
                                <p class="mb-0">{{ $payment->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions (if pending) -->
                @if($payment->status === 'pending')
                <div class="card border-0 rounded-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Actions</h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success mark-paid-btn" 
                                data-payment-id="{{ $payment->id }}" 
                                data-amount="{{ number_format($payment->calculated_amount, 2) }} {{ $payment->currency }}">
                                <i class="ti ti-check me-1"></i> Mark as Paid
                            </button>
                            <button type="button" class="btn btn-outline-danger cancel-btn" 
                                data-payment-id="{{ $payment->id }}">
                                <i class="ti ti-x me-1"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- 👥 RELATED INFO (RIGHT SIDEBAR) -->
            <div class="col-lg-4">
                
                <!-- Consultant Card -->
                <div class="card border-0 rounded-0 shadow-sm mb-3">
                    <div class="card-body text-center">
                        <span class="avatar avatar-lg bg-primary text-white mb-2">
                            {{ substr($payment->consultant->name ?? 'C', 0, 1) }}
                        </span>
                        <h6 class="mb-1">{{ $payment->consultant->name ?? 'N/A' }}</h6>
                        <p class="text-muted small mb-0">{{ $payment->consultant->email ?? '' }}</p>
                        @if($payment->consultant->mobile)
                        <p class="text-muted small">{{ $payment->consultant->mobile }}</p>
                        @endif
                    </div>
                </div>

                <!-- Lead Card -->
                <div class="card border-0 rounded-0 shadow-sm mb-3">
                    <div class="card-header bg-light py-2">
                        <small class="text-muted">Lead Details</small>
                    </div>
                    <div class="card-body">
                        <p class="mb-1 fw-medium">{{ $payment->admissionRequest->lead->full_name ?? 'N/A' }}</p>
                        <p class="mb-1 text-muted small">{{ $payment->admissionRequest->lead->email ?? '' }}</p>
                        <p class="mb-0 text-muted small">{{ $payment->admissionRequest->lead->mobile ?? '' }}</p>
                    </div>
                </div>

                <!-- Admission Card -->
                <div class="card border-0 rounded-0 shadow-sm">
                    <div class="card-header bg-light py-2">
                        <small class="text-muted">Admission Request</small>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong class="d-block text-muted small">Course</strong>
                            <span class="fw-medium">{{ $payment->admissionRequest->course->name ?? $payment->admissionRequest->course_name ?? 'N/A' }}</span>
                        </p>
                        <p class="mb-2">
                            <strong class="d-block text-muted small">College</strong>
                            <span class="fw-medium">{{ $payment->admissionRequest->college->name ?? 'N/A' }}</span>
                        </p>
                        <p class="mb-0">
                            <strong class="d-block text-muted small">Status</strong>
                            <span class="badge bg-{{ $payment->admissionRequest->status === 'accepted' ? 'success' : 'secondary' }}">
                                {{ ucfirst($payment->admissionRequest->status) }}
                            </span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentId = '{{ $payment->id }}';
    
    // Mark Paid with Swal
    document.querySelector('.mark-paid-btn')?.addEventListener('click', function() {
        const amount = this.dataset.amount;
        
        Swal.fire({
            title: 'Mark as Paid?',
            html: `
                <div class="text-start">
                    <p class="mb-2"><strong>Payment #:</strong> ${paymentId}</p>
                    <p class="mb-3"><strong>Amount:</strong> ${amount}</p>
                    <input type="text" id="paymentRef" class="form-control mb-2" placeholder="Transaction/Reference ID *">
                    <input type="date" id="paymentDate" class="form-control" value="${new Date().toISOString().split('T')[0]}">
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm Paid',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const ref = document.getElementById('paymentRef').value.trim();
                const date = document.getElementById('paymentDate').value;
                if (!ref) {
                    Swal.showValidationMessage('Payment reference is required');
                    return false;
                }
                return { ref, date };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/commission-payments/${paymentId}/mark-paid`;
                form.innerHTML = `@csrf
                    <input type="hidden" name="payment_reference" value="${result.value.ref}">
                    <input type="hidden" name="payment_date" value="${result.value.date}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
    
    // Cancel with Swal
    document.querySelector('.cancel-btn')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Cancel Payment?',
            text: `Are you sure you want to cancel payment #${paymentId}? This cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel',
            cancelButtonText: 'No, Keep',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/commission-payments/${paymentId}/cancel`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush