@extends('layout.master')

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Success!', text: @json(session('success')), timer: 4000, timerProgressBar: true, showConfirmButton: false });
    });
</script>
@endif

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'error', title: 'Error!', html: @json($errors->all()), timer: 6000, timerProgressBar: true, showConfirmButton: true });
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
                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#generateForConsultant">
                    <i class="ti ti-user-plus me-1"></i> Generate for Consultant
                </a>
                <form action="{{ route('commission-payments.bulk-generate') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Generate payments for all accepted admissions without payments?')">
                        <i class="ti ti-cash me-1"></i> Bulk Generate
                    </button>
                </form>
            </div>
        </div>

        <!-- Summary Cards (Simple) -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 bg-soft-warning shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Pending Payments</h6>
                        <h3 class="mb-0">₹{{ number_format($totalPending, 2) }}</h3>
                        <small class="text-muted">{{ $payments->where('status', 'pending')->count() }} payments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-soft-success shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Paid Payments</h6>
                        <h3 class="mb-0">₹{{ number_format($totalPaid, 2) }}</h3>
                        <small class="text-muted">{{ $payments->where('status', 'paid')->count() }} payments</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 rounded-0 mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('commission-payments') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Consultant</label>
                        <select name="consultant_id" class="form-select">
                            <option value="">All Consultants</option>
                            @foreach ($consultants as $consultant)
                                <option value="{{ $consultant->id }}" {{ request('consultant_id') == $consultant->id ? 'selected' : '' }}>
                                    {{ $consultant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">From</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">To</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="ti ti-filter me-1"></i> Filter</button>
                        <a href="{{ route('commission-payments') }}" class="btn btn-outline-secondary"><i class="ti ti-x"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payments Table (SIMPLE - Total Amount Only) -->
        <div class="card border-0 rounded-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Consultant</th>
                                <th>Lead</th>
                                <th>Admission #</th>
                                <th>Type</th>
                                <th class="text-end">Total Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr>
                                    <td><strong>#{{ $payment->id }}</strong></td>
                                    <td>{{ $payment->consultant->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->admissionRequest->lead->full_name ?? 'N/A' }}</td>
                                    <td>#{{ $payment->admission_request_id }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->payment_type === 'percentage' ? 'info' : 'primary' }} bg-opacity-75">
                                            {{ $payment->payment_type === 'percentage' ? '%' : '₹' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <!-- ✅ ONLY TOTAL AMOUNT SHOWN HERE -->
                                        <strong class="text-primary">₹{{ number_format($payment->calculated_amount, 2) }}</strong>
                                        <small class="text-muted d-block">{{ $payment->currency }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'cancelled' ? 'danger' : 'warning') }} bg-opacity-75">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('commission-payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="ti ti-receipt-off fs-1 d-block mb-2"></i>
                                        No commission payments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $payments->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Generate for Consultant Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="generateForConsultant">
    <div class="offcanvas-header border-bottom">
        <h5 class="mb-0"><i class="ti ti-user-plus me-2"></i>Generate for Consultant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <form action="{{ route('commission-payments.generate-for-consultant') }}" method="POST">
        @csrf
        <div class="offcanvas-body">
            <div class="mb-3">
                <label class="form-label">Select Consultant <span class="text-danger">*</span></label>
                <select name="consultant_id" class="form-select" required>
                    <option value="">Choose Consultant</option>
                    @foreach ($consultants as $consultant)
                        <option value="{{ $consultant->id }}">{{ $consultant->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">College (Optional)</label>
                <select name="college_id" class="form-select">
                    <option value="">All Colleges</option>
                    @foreach ($colleges ?? [] as $college)
                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="alert alert-info">
                <small><i class="ti ti-info-circle me-1"></i>Generates payments for accepted admissions without existing payments.</small>
            </div>
        </div>
        <div class="offcanvas-footer border-top p-3">
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="ti ti-cash me-1"></i> Generate</button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple action buttons - no complex Swal for index
    document.querySelectorAll('.mark-paid-btn, .cancel-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'info',
                title: 'Action Available',
                text: 'Please use the View button to manage this payment.',
                confirmButtonText: 'OK'
            });
        });
    });
});
</script>
@endpush