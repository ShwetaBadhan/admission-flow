@extends('layout.master')

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Success!', text: @json(session('success')), timer: 4000, showConfirmButton: false });
    });
</script>
@endif

@section('content')
<div class="page-wrapper">
    <div class="content pb-0">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">All Payment Requests 
                    <span class="badge badge-soft-primary ms-2">{{ $totalRequests ?? 0 }}</span>
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('payment-requests.index') }}">Payment Requests</a></li>
                        <li class="breadcrumb-item active">Requested Payments</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex">
                <a href="{{ route('payment-requests.index') }}" class="btn btn-light">
                    <i class="ti ti-list me-1"></i> Requests List
                </a>
                <a href="{{ route('commission-payments') }}" class="btn btn-primary">
                    <i class="ti ti-cash me-1"></i> All Payments
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card border-0 bg-soft-primary shadow-sm text-center">
                    <div class="card-body">
                        <h2 class="mb-0">{{ $totalRequests ?? 0 }}</h2>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 bg-soft-warning shadow-sm text-center">
                    <div class="card-body">
                        <h2 class="mb-0">{{ $pendingRequests ?? 0 }}</h2>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-soft-warning shadow-sm text-center">
                    <div class="card-body">
                        <h2 class="mb-0">₹{{ number_format($totalPendingAmount ?? 0, 0) }}</h2>
                        <small class="text-muted">Pending Amount</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-soft-success shadow-sm text-center">
                    <div class="card-body">
                        <h2 class="mb-0">₹{{ number_format($totalApprovedAmount ?? 0, 0) }}</h2>
                        <small class="text-muted">Approved Amount</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card border-0 bg-soft-danger shadow-sm text-center">
                    <div class="card-body">
                        <h2 class="mb-0">{{ $totalRejected ?? 0 }}</h2>
                        <small class="text-muted">Rejected</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 rounded-0 mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('payment-requests.requested') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Consultant</label>
                        <select name="consultant_id" class="form-select">
                            <option value="">All Consultants</option>
                            @foreach ($consultants ?? [] as $consultant)
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
                            @foreach ($statuses ?? [] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">From</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">To</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                   
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="ti ti-filter me-1"></i> Filter</button>
                        <a href="{{ route('payment-requests.requested') }}" class="btn btn-outline-secondary"><i class="ti ti-reload"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="card border-0 rounded-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Request #</th>
                                <th>Consultant</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payments</th>
                                <th>Status</th>
                                <th>Processed</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentRequests ?? [] as $request)
                                <tr>
                                    <td><strong>#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                    <td>{{ $request->consultant->name ?? 'N/A' }}</td>
                                    <td>{{ $request->created_at->format('d M Y') }}</td>
                                    <td><strong class="text-primary">₹{{ number_format($request->requested_amount, 2) }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ count($request->admission_ids ?? []) }}</span></td>
                                    <td>
                                        @php $colors = ['pending'=>'warning','approved'=>'info','rejected'=>'danger','paid'=>'success']; @endphp
                                        <span class="badge bg-{{ $colors[$request->status] ?? 'secondary' }}">{{ ucfirst($request->status) }}</span>
                                    </td>
                                    <td>{{ $request->approved_at?->format('d M Y') ?: '—' }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary view-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewModal"
                                                data-id="{{ $request->id }}"
                                                data-number="{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}"
                                                data-consultant="{{ $request->consultant->name ?? 'N/A' }}"
                                                data-date="{{ $request->created_at->format('d M Y') }}"
                                                data-amount="{{ number_format($request->requested_amount, 2) }}"
                                                data-status="{{ $request->status }}"
                                                data-processed="{{ $request->approved_at?->format('d M Y') ?: '—' }}"
                                                data-notes="{{ $request->notes ?? '' }}"
                                                data-payments='@json($request->admission_ids ?? [])'>
                                            <i class="ti ti-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-5 text-muted">No payment requests found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(isset($paymentRequests) && $paymentRequests->hasPages())
                    <div class="mt-3">{{ $paymentRequests->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ✅ SINGLE VIEW MODAL (Outside Loop) -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">Request #<span id="modalNumber">---</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Basic Info -->
                <div class="row mb-3">
                    <div class="col-6"><strong>Consultant:</strong> <span id="modalConsultant">---</span></div>
                    <div class="col-6 text-end"><strong>Amount:</strong> ₹<span id="modalAmount">---</span></div>
                    <div class="col-6"><strong>Date:</strong> <span id="modalDate">---</span></div>
                    <div class="col-6 text-end"><strong>Status:</strong> <span class="badge" id="modalStatus">---</span></div>
                    <div class="col-6"><strong>Processed:</strong> <span id="modalProcessed">---</span></div>
                </div>
                
                <!-- Notes -->
                <div id="modalNotesBox" class="alert alert-light border mb-3" style="display:none;">
                    <small><strong>Notes:</strong> <span id="modalNotes"></span></small>
                </div>

                <!-- Payments Table -->
                <h6 class="border-bottom pb-2 mb-2">Included Payments (<span id="paymentsCount">0</span>)</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Payment #</th><th>Admission #</th><th>Lead</th><th class="text-end">Amount</th><th>Status</th></tr></thead>
                        <tbody id="paymentsBody">
                            <tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr><td colspan="3" class="text-end">Total:</td><td class="text-end text-primary" id="paymentsTotal">₹0.00</td><td></td></tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Rejection Reason -->
                <div id="rejectionBox" class="alert alert-danger mt-2" style="display:none;">
                    <strong>Rejection:</strong> <span id="rejectionText"></span>
                </div>
            </div>
            
            <!-- Footer Actions -->
            <div class="modal-footer">
                <div id="modalActions"></div>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ✅ SINGLE PAYMENT MODAL (Outside Loop) -->
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <form id="payForm" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">💰 Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Request #<span id="payModalNumber"></span> - ₹<span id="payModalAmount"></span></p>
                    <div class="mb-3">
                        <label class="form-label">Payment Reference *</label>
                        <input type="text" name="payment_reference" class="form-control" placeholder="TXN-2024-001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Date *</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="payment_notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="ti ti-check me-1"></i> Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ✅ SINGLE REJECT MODAL (Outside Loop) -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">Reject Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Reason for rejection:</p>
                    <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Preloaded payments data
const allPayments = @json($allPayments ?? []);
const statusColors = { pending: 'warning', approved: 'info', rejected: 'danger', paid: 'success' };

document.addEventListener('DOMContentLoaded', function() {
    
    // ✅ View Button Click - Populate Modal
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = {
                id: this.dataset.id,
                number: this.dataset.number,
                consultant: this.dataset.consultant,
                date: this.dataset.date,
                amount: this.dataset.amount,
                status: this.dataset.status,
                processed: this.dataset.processed,
                notes: this.dataset.notes,
                payments: JSON.parse(this.dataset.payments || '[]')
            };

            // Populate fields
            document.getElementById('modalNumber').textContent = data.number;
            document.getElementById('modalConsultant').textContent = data.consultant;
            document.getElementById('modalDate').textContent = data.date;
            document.getElementById('modalAmount').textContent = data.amount;
            document.getElementById('modalProcessed').textContent = data.processed;
            
            // Status badge
            const statusBadge = document.getElementById('modalStatus');
            statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            statusBadge.className = 'badge bg-' + (statusColors[data.status] || 'secondary');
            
            // Notes
            const notesBox = document.getElementById('modalNotesBox');
            if (data.notes && data.notes.trim()) {
                notesBox.style.display = 'block';
                document.getElementById('modalNotes').textContent = data.notes;
            } else {
                notesBox.style.display = 'none';
            }
            
            // Rejection
            const rejectBox = document.getElementById('rejectionBox');
            if (data.status === 'rejected' && data.notes && data.notes.includes('❌ Rejected:')) {
                rejectBox.style.display = 'block';
                document.getElementById('rejectionText').textContent = data.notes.split('❌ Rejected:')[1]?.trim() || '';
            } else {
                rejectBox.style.display = 'none';
            }
            
            // Payments table
            const payments = data.payments.map(id => allPayments[id]).filter(p => p);
            const tbody = document.getElementById('paymentsBody');
            const totalEl = document.getElementById('paymentsTotal');
            document.getElementById('paymentsCount').textContent = payments.length;
            
            if (payments.length > 0) {
                let html = '', total = 0;
                payments.forEach(p => {
                    total += parseFloat(p.calculated_amount || 0);
                    html += `<tr>
                        <td>#${p.id}</td>
                        <td>#${p.admission_request_id}</td>
                        <td><small>${p.lead_name || 'N/A'}</small></td>
                        <td class="text-end">₹${parseFloat(p.calculated_amount || 0).toFixed(2)}</td>
                        <td><span class="badge bg-${p.status === 'paid' ? 'success' : 'warning'}">${p.status}</span></td>
                    </tr>`;
                });
                tbody.innerHTML = html;
                totalEl.textContent = '₹' + total.toFixed(2);
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No payments</td></tr>';
                totalEl.textContent = '₹0.00';
            }
            
            // ✅ Actions with Swal handlers (not inline confirm)
            const actionsDiv = document.getElementById('modalActions');
            let actions = '';
            
            if (data.status === 'pending') {
                // Approve with Swal
                actions += `<button type="button" class="btn btn-info btn-sm approve-btn ml-3" data-id="${data.id}">
                    <i class="ti ti-check me-1"></i> Approve
                </button>`;
                // Reject with Swal
               // Reject: Opens existing #rejectModal
                actions += `<button type="button" id="btnReject" class="btn btn-outline-danger btn-sm m-1" 
                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="ti ti-x me-1"></i> Reject
                </button>`;
            } else if (data.status === 'approved') {
                actions += `<button type="button" class="btn btn-success btn-sm ml-3" data-bs-toggle="modal" data-bs-target="#payModal" data-id="${data.id}" data-amount="${data.amount}">
                    <i class="ti ti-cash me-1"></i> Record Payment
                </button>`;
            } else if (data.status === 'paid') {
                actions += `<span class="badge bg-success ">Paid</span>`;
            }
            actionsDiv.innerHTML = actions;
        });
    });
    
    // ✅ Approve Button - Swal Confirmation
    document.getElementById('viewModal')?.addEventListener('click', function(e) {
        if (e.target.classList.contains('approve-btn')) {
            e.preventDefault();
            const requestId = e.target.dataset.id;
            
            Swal.fire({
                title: 'Approve Payment Request?',
                text: 'Request will be marked as approved. Payments remain pending until manually recorded.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0dcaf0',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm(`/payment-requests/${requestId}/approve`, {});
                }
            });
        }
        
          // Rejection
            const rejectBox = document.getElementById('rejectionBox');
            if (data.status === 'rejected' && data.notes && data.notes.includes('❌ Rejected:')) {
                rejectBox.style.display = 'block';
                document.getElementById('rejectionText').textContent = data.notes.split('❌ Rejected:')[1]?.trim() || '';
            } else {
                rejectBox.style.display = 'none';
            }
    });
    
    // ✅ Payment Modal - Set form action
    document.getElementById('payModal')?.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        const id = btn.dataset.id;
        document.getElementById('payModalNumber').textContent = id.padStart(6, '0');
        document.getElementById('payModalAmount').textContent = btn.dataset.amount || '0.00';
        document.getElementById('payForm').action = `/payment-requests/${id}/make-payment`;
    });
    
    // ✅ Helper: Submit form with hidden inputs
    function submitForm(url, data) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = `@csrf`;
        
        Object.keys(data).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endpush