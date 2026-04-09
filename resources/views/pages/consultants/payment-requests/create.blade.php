@extends('layout.master')

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                html: @json($errors->all()),
                timer: 8000,
                timerProgressBar: true,
                showConfirmButton: true
            });
        });
    </script>
@endif

@section('content')
    <div class="page-wrapper">
        <div class="content pb-0">
            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4">
                <div>
                    <h4 class="mb-1">New Payment Request</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('payment-requests.index') }}">My Requests</a></li>
                            <li class="breadcrumb-item active">New Request</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('payment-requests.index') }}" class="btn btn-light">
                    <i class="ti ti-arrow-left me-1"></i> Back to Requests
                </a>
            </div>

            <div class="row">
                <!-- Left: Pending Payments Selection -->
                <div class="col-lg-8">
                    <div class="card border-0 rounded-0 shadow-sm mb-4">
                        <div class="card-header bg-light d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">Select Pending Commissions</h6>
                            <span class="badge bg-primary">{{ $pendingPayments->count() }} Available</span>
                        </div>
                        <div class="card-body">
                            @if ($pendingPayments->isEmpty())
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    You don't have any pending commission payments yet.
                                    Payments are generated when your admissions are accepted.
                                </div>
                                <a href="{{ route('payment-requests.index') }}" class="btn btn-light">
                                    <i class="ti ti-arrow-left me-1"></i> Back to Requests
                                </a>
                            @else
                                <form action="{{ route('payment-requests.store') }}" method="POST" id="paymentRequestForm">
                                    @csrf

                                    <!-- Select All -->
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                            <label class="form-check-label fw-medium" for="selectAll">Select All
                                                ({{ count($pendingPayments) }} payments)</label>
                                        </div>
                                        <div>
                                            <strong class="text-primary" id="selectedTotal">₹0.00</strong>
                                            <small class="text-muted d-block">Selected Amount</small>
                                        </div>
                                    </div>

                                    <!-- Payments List -->
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50"><input type="checkbox"
                                                            class="form-check-input select-item" id="checkAll"></th>
                                                    <th>Payment #</th>
                                                    <th>Admission</th>
                                                    <th>College</th>
                                                    <th>Type</th>
                                                    <th class="text-end">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pendingPayments as $payment)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="form-check-input payment-checkbox"
                                                                name="admission_ids[]" value="{{ $payment->id }}"
                                                                data-amount="{{ $payment->calculated_amount }}"
                                                                id="payment_{{ $payment->id }}">
                                                        </td>
                                                        <td><strong>#{{ $payment->id }}</strong></td>
                                                        <td>
                                                            <small class="d-block text-muted">Admission
                                                                #{{ $payment->admission_request_id }}</small>
                                                            {{ $payment->admissionRequest->lead->full_name ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $payment->admissionRequest->college->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $payment->payment_type === 'percentage' ? 'info' : 'primary' }} bg-opacity-75">
                                                                {{ $payment->payment_type === 'percentage' ? '%' : 'Fixed' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end fw-medium">
                                                            ₹{{ number_format($payment->calculated_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Notes -->
                                    <div class="mb-3 mt-4">
                                        <label class="form-label">Notes (Optional)</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="Add any notes for admin..."></textarea>
                                        <small class="text-muted">Maximum 500 characters</small>
                                    </div>

                                    <!-- Submit -->
                                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                                        <div>
                                            <small class="text-muted">
                                                <i class="ti ti-info-circle me-1"></i>
                                                Select at least 1 payment to submit request
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('payment-requests.index') }}"
                                                class="btn btn-light">Cancel</a>
                                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                                <i class="ti ti-send me-1"></i> Submit Request
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Summary Card -->
                <div class="col-lg-4">
                    <div class="card border-0 rounded-0 shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ti ti-summary me-2"></i>Request Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small">Consultant</label>
                                <p class="mb-0 fw-medium">{{ $consultant->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Selected Payments</label>
                                <p class="mb-0" id="selectedCount">0 of {{ $pendingPayments->count() }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Total Amount</label>
                                <h3 class="mb-0 text-primary" id="summaryTotal">₹0.00</h3>
                            </div>
                            <hr>
                            <div class="alert alert-info mb-0">
                                <small>
                                    <i class="ti ti-info-circle me-1"></i>
                                    <strong>How it works:</strong><br>
                                    1. Select pending commission payments<br>
                                    2. Submit request for approval<br>
                                    3. Admin reviews & approves<br>
                                    4. Payments marked as paid automatically
                                </small>
                            </div>
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
            const checkboxes = document.querySelectorAll('.payment-checkbox');
            const selectAll = document.getElementById('selectAll');
            const checkAll = document.getElementById('checkAll');
            const selectedTotal = document.getElementById('selectedTotal');
            const summaryTotal = document.getElementById('summaryTotal');
            const selectedCount = document.getElementById('selectedCount');
            const submitBtn = document.getElementById('submitBtn');

            function updateTotals() {
                let total = 0;
                let count = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.amount);
                        count++;
                    }
                });

                selectedTotal.textContent = '₹' + total.toFixed(2);
                summaryTotal.textContent = '₹' + total.toFixed(2);
                selectedCount.textContent = count + ' of {{ $pendingPayments->count() }}';
                submitBtn.disabled = count === 0;

                // Update select all checkbox state
                const allChecked = checkboxes.length > 0 && count === checkboxes.length;
                const someChecked = count > 0 && count < checkboxes.length;

                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked;
                checkAll.checked = allChecked;
                checkAll.indeterminate = someChecked;
            }

            // Individual checkbox change
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateTotals);
            });

            // Select All / Check All
            [selectAll, checkAll].forEach(btn => {
                btn?.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateTotals();
                });
            });

            // Form submission confirmation
            document.getElementById('paymentRequestForm')?.addEventListener('submit', function(e) {
                const count = document.querySelectorAll('.payment-checkbox:checked').length;
                const total = selectedTotal.textContent;

                e.preventDefault();

                Swal.fire({
                    title: 'Submit Payment Request?',
                    html: `
                <div class="text-start">
                    <p>You are requesting payment for:</p>
                    <ul class="mb-3">
                        <li><strong>${count}</strong> commission payments</li>
                        <li><strong>Total Amount:</strong> ${total}</li>
                    </ul>
                    <p class="text-muted small">Once submitted, this request will be reviewed by admin.</p>
                </div>
            `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Submit Request',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            // Initialize
            updateTotals();
        });
    </script>
@endpush
