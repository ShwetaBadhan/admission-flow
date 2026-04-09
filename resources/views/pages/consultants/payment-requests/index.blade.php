@extends('layout.master')

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: @json(session('success')),
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false
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
                    <h4 class="mb-1">My Payment Requests
                        <span class="badge badge-soft-primary ms-2">{{ $requests->total() }}</span>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Payment Requests</li>
                        </ol>
                    </nav>
                </div>
                <!-- ✅ New Request Button -->
                <a href="{{ route('payment-requests.create') }}" class="btn btn-primary">
                    <i class="ti ti-square-rounded-plus me-1"></i> New Payment Request
                </a>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-soft-warning shadow-sm text-center">
                        <div class="card-body">
                            <h2 class="mb-0">{{ $requests->where('status', 'pending')->count() }}</h2>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-soft-info shadow-sm text-center">
                        <div class="card-body">
                            <h2 class="mb-0">{{ $requests->where('status', 'approved')->count() }}</h2>
                            <small class="text-muted">Approved</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-soft-success shadow-sm text-center">
                        <div class="card-body">
                            <h2 class="mb-0">{{ $requests->where('status', 'paid')->count() }}</h2>
                            <small class="text-muted">Paid</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-soft-danger shadow-sm text-center">
                        <div class="card-body">
                            <h2 class="mb-0">{{ $requests->where('status', 'rejected')->count() }}</h2>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requests Table (Simple - No Filters) -->
            <div class="card border-0 rounded-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Request #</th>
                                    <th>Amount</th>
                                    <th>Admissions</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $request)
                                    <tr>
                                        <td><strong>#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                        <td><strong
                                                class="text-primary">₹{{ number_format($request->requested_amount, 2) }}</strong>
                                        </td>
                                        <td><span
                                                class="badge bg-secondary">{{ count($request->admission_ids ?? []) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'rejected' => 'danger',
                                                    'paid' => 'success',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#viewRequest{{ $request->id }}">
                                                <i class="ti ti-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- View Modal (Same as before) -->
                                    <div class="modal fade" id="viewRequest{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content rounded-0">
                                                <div class="modal-header border-bottom">
                                                    <h5 class="modal-title">Request
                                                        #{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <label class="text-muted small">Amount</label>
                                                            <p class="mb-0 fs-5 fw-bold text-primary">
                                                                ₹{{ number_format($request->requested_amount, 2) }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small">Status</label>
                                                            <p class="mb-0">
                                                                <span
                                                                    class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }} fs-6">
                                                                    {{ ucfirst($request->status) }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-muted small">Submitted</label>
                                                            <p class="mb-0">
                                                                {{ $request->created_at->format('d M Y, h:i A') }}</p>
                                                        </div>
                                                    </div>
                                                    @if ($request->notes)
                                                        <div class="mt-3">
                                                            <label class="text-muted small">Notes</label>
                                                            <p class="mb-0 bg-light p-2 rounded">{{ $request->notes }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="ti ti-receipt-off fs-1 d-block mb-2"></i>
                                            No payment requests found.
                                            <br>
                                            <a href="{{ route('payment-requests.create') }}"
                                                class="btn btn-sm btn-primary mt-2">
                                                <i class="ti ti-square-rounded-plus me-1"></i> Create First Request
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $requests->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
