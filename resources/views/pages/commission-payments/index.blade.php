@extends('layout.master')
<!-- Session Messages -->
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: @json(session('success')),
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: @json($errors->all()),
                timer: 6000,
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
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Commission Payments
                        <span class="badge badge-soft-primary ms-2">{{ $payments->total() }}</span>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Commission Payments</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <form action="{{ route('commission-payments.bulk-generate') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Generate payments for all accepted admissions without payments?')">
                            <i class="ti ti-cash me-1"></i> Bulk Generate
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 bg-soft-warning">
                        <div class="card-body">
                            <h6 class="text-muted">Pending Payments</h6>
                            <h3>${{ number_format($totalPending, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-soft-success">
                        <div class="card-body">
                            <h6 class="text-muted">Paid Payments</h6>
                            <h3>${{ number_format($totalPaid, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card border-0 rounded-0 mb-4">
                <div class="card-body">
                    <form action="{{ route('commission-payments') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <select name="consultant_id" class="form-select">
                                <option value="">All Consultants</option>
                                @foreach ($consultants as $consultant)
                                    <option value="{{ $consultant->id }}"
                                        {{ request('consultant_id') == $consultant->id ? 'selected' : '' }}>
                                        {{ $consultant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="card border-0 rounded-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Consultant</th>
                                    <th>Lead</th>
                                    <th>Admission</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>#{{ $payment->id }}</td>
                                        <td>{{ $payment->consultant->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->admissionRequest->lead->full_name ?? 'N/A' }}</td>
                                        <td>#{{ $payment->admission_request_id }}</td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</span>
                                        </td>
                                        <td>{{ $payment->commission_value }}{{ $payment->payment_type === 'percentage' ? '%' : '' }}
                                        </td>
                                        <td>{{ number_format($payment->calculated_amount, 2) }} {{ $payment->currency }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $payment->created_at->format('d M Y') }}</td>
                                        <!-- Payments Table - Action Column with Swal -->
                                        <td>
                                            <div class="dropdown table-action">
                                                <a href="#" class="action-icon btn btn-xs btn-outline-light"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                        href="{{ route('commission-payments.show', $payment) }}">
                                                        <i class="ti ti-eye me-1"></i> View
                                                    </a>
                                                    @if ($payment->status === 'pending')
                                                        <!-- Mark Paid with Swal Confirmation -->
                                                        <form
                                                            action="{{ route('commission-payments.mark-paid', $payment) }}"
                                                            method="POST" class="d-inline mark-paid-form"
                                                            data-payment-id="{{ $payment->id }}">
                                                            @csrf
                                                            <button type="button" class="dropdown-item mark-paid-btn"
                                                                data-payment-id="{{ $payment->id }}"
                                                                data-amount="{{ number_format($payment->calculated_amount, 2) }} {{ $payment->currency }}">
                                                                <i class="ti ti-check me-1"></i> Mark Paid
                                                            </button>
                                                        </form>
                                                        <!-- Cancel with Swal Confirmation -->
                                                        <form action="{{ route('commission-payments.cancel', $payment) }}"
                                                            method="POST" class="d-inline cancel-form"
                                                            data-payment-id="{{ $payment->id }}">
                                                            @csrf
                                                            <button type="button"
                                                                class="dropdown-item text-danger cancel-btn"
                                                                data-payment-id="{{ $payment->id }}">
                                                                <i class="ti ti-x me-1"></i> Cancel
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $payments->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Mark Paid Confirmation
            document.querySelectorAll('.mark-paid-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const amount = this.dataset.amount;
                    const paymentId = this.dataset.paymentId;

                    Swal.fire({
                        title: 'Mark as Paid?',
                        html: `
                    <p class="text-start mb-2">Confirm payment details:</p>
                    <div class="text-start bg-light p-2 rounded mb-2">
                        <small><strong>Payment #:</strong> ${paymentId}</small><br>
                        <small><strong>Amount:</strong> ${amount}</small>
                    </div>
                    <p class="text-start text-muted small mb-0">You will be redirected to enter payment reference.</p>
                `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Continue',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to a modal/page for payment details
                            // For now, submit with a prompt for reference
                            Swal.fire({
                                title: 'Enter Payment Reference',
                                input: 'text',
                                inputLabel: 'Transaction/Reference ID',
                                inputPlaceholder: 'e.g., TXN-2024-001',
                                showCancelButton: true,
                                confirmButtonText: 'Confirm Paid',
                                inputValidator: (value) => {
                                    if (!value) return 'Reference is required!';
                                }
                            }).then((refResult) => {
                                if (refResult.isConfirmed) {
                                    // Add hidden input for reference and submit
                                    const refInput = document.createElement(
                                    'input');
                                    refInput.type = 'hidden';
                                    refInput.name = 'payment_reference';
                                    refInput.value = refResult.value;

                                    const dateInput = document.createElement(
                                        'input');
                                    dateInput.type = 'hidden';
                                    dateInput.name = 'payment_date';
                                    dateInput.value = new Date().toISOString()
                                        .split('T')[0];

                                    form.appendChild(refInput);
                                    form.appendChild(dateInput);
                                    form.submit();
                                }
                            });
                        }
                    });
                });
            });

            // Cancel Confirmation
            document.querySelectorAll('.cancel-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const paymentId = this.dataset.paymentId;

                    Swal.fire({
                        title: 'Cancel Payment?',
                        text: `Are you sure you want to cancel payment #${paymentId}? This action cannot be undone.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Cancel it',
                        cancelButtonText: 'No, Keep it',
                        confirmButtonColor: '#dc3545',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Bulk Generate Confirmation
            document.querySelector('button[onclick*="bulk-generate"]')?.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Bulk Generate Payments?',
                    text: 'This will create pending commission payments for all accepted admissions without existing payments.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Generate',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
