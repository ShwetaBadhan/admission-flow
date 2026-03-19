@extends('layout.master')

{{-- Session Messages --}}
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
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: @json($errors->all()),
                timer: 6000,
                showConfirmButton: true
            });
        });
    </script>
@endif

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">
                        <a href="{{ route('consultants.index') }}" class="text-decoration-none text-dark">
                            <i class="ti ti-arrow-left me-2"></i>
                        </a>
                        Consultant Details
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('consultants.index') }}">Consultants</a></li>
                            <li class="breadcrumb-item active">{{ $consultant->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('consultants.index') }}" class="btn btn-light">
                        <i class="ti ti-list me-1"></i>Back to List
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kyc_upload_modal">
                        <i class="ti ti-upload me-1"></i>Upload KYC
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="avatar avatar-lg bg-primary-light rounded-circle">
                                <i class="ti ti-file text-primary fs-24"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Total Docs</h6>
                                <h4 class="mb-0 fw-bold">{{ $stats['total_docs'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="avatar avatar-lg bg-warning-light rounded-circle">
                                <i class="ti ti-clock text-warning fs-24"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Pending</h6>
                                <h4 class="mb-0 fw-bold text-warning">{{ $stats['pending'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="avatar avatar-lg bg-success-light rounded-circle">
                                <i class="ti ti-check text-success fs-24"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Verified</h6>
                                <h4 class="mb-0 fw-bold text-success">{{ $stats['verified'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="avatar avatar-lg bg-danger-light rounded-circle">
                                <i class="ti ti-x text-danger fs-24"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted">Rejected</h6>
                                <h4 class="mb-0 fw-bold text-danger">{{ $stats['rejected'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left: Consultant Info -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar avatar-xxl bg-primary-light rounded-circle mb-3">
                                    <span
                                        class="fs-32 fw-bold text-primary">{{ strtoupper(substr($consultant->name, 0, 1)) }}</span>
                                </div>
                                <h5 class="mb-1">{{ $consultant->name }}</h5>
                                <span
                                    class="badge badge-pill badge-status bg-{{ $consultant->status == 1 ? 'success' : 'danger' }}">
                                    {{ $consultant->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0 fw-medium">{{ $consultant->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Phone</label>
                                <p class="mb-0 fw-medium">{{ $consultant->phone }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Location</label>
                                <p class="mb-0 fw-medium">
                                    {{ $consultant->city?->name ?? 'N/A' }}, {{ $consultant->state?->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="text-muted small">Address</label>
                                <p class="mb-0">{{ $consultant->address ?? 'Not provided' }}</p>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#edit_consultant">
                                    <i class="ti ti-edit me-1"></i>Edit Profile
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: KYC Documents -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="ti ti-id-badge me-2"></i>KYC Documents</h5>
                        </div>
                        <div class="card-body">
                            @if ($consultant->kycDocuments->isEmpty())
                                <div class="text-center py-5">
                                    <div class="avatar avatar-lg bg-light rounded-circle mb-3">
                                        <i class="ti ti-file-off text-muted fs-24"></i>
                                    </div>
                                    <p class="text-muted mb-3">No KYC documents uploaded yet.</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#kyc_upload_modal">
                                        <i class="ti ti-upload me-1"></i>Upload First Document
                                    </button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-nowrap align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Document</th>
                                                <th>Number</th>
                                                <th>File</th>
                                                <th>Uploaded</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($consultant->kycDocuments as $kyc)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ ucfirst(str_replace('_', ' ', $kyc->document_type)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $kyc->document_number ?? '-' }}</td>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $kyc->file_path) }}" target="_blank"
                                                            class="text-primary">
                                                            <i class="ti ti-eye me-1"></i>View
                                                        </a>
                                                    </td>
                                                    <td>{{ $kyc->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>
                                                        @if ($kyc->is_verified === null)
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @elseif($kyc->is_verified)
                                                            <span class="badge bg-success">Verified</span>
                                                        @else
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($kyc->is_verified === null)
                                                            <div class="d-flex gap-1">
                                                                <form
                                                                    action="{{ route('consultants.kyc.verify', ['id' => $consultant->id, 'kyc_id' => $kyc->id]) }}"
                                                                    method="POST" id="verify-form-{{ $kyc->id }}">
                                                                    @csrf
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-success verify-kyc-btn"
                                                                        data-kyc-id="{{ $kyc->id }}" title="Verify">
                                                                        <i class="ti ti-check"></i>
                                                                    </button>
                                                                </form>
                                                                <form
                                                                    action="{{ route('consultants.kyc.reject', ['id' => $consultant->id, 'kyc_id' => $kyc->id]) }}"
                                                                    method="POST" id="reject-form-{{ $kyc->id }}">
                                                                    @csrf
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-danger reject-kyc-btn"
                                                                        data-kyc-id="{{ $kyc->id }}" title="Reject">
                                                                        <i class="ti ti-x"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <span class="text-muted small">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- KYC Upload Modal -->
    <div class="modal fade" id="kyc_upload_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload KYC Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('consultants.kyc.upload', $consultant->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Document Type *</label>
                            <select name="document_type" class="select" required>
                                <option value="">Select Type</option>
                                <option value="aadhaar">Aadhaar Card</option>
                                <option value="pan">PAN Card</option>
                                <option value="gst">GST Certificate</option>
                                <option value="bank">Bank Statement</option>
                                <option value="address">Address Proof</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Document Number</label>
                            <input type="text" name="document_number" class="form-control"
                                placeholder="e.g., ABCDE1234F">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File *</label>
                            <input type="file" name="file" class="form-control"
                                accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Consultant Modal -->
    <div class="modal fade" id="edit_consultant" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Consultant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('consultants.update', $consultant->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $consultant->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $consultant->email }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ $consultant->phone }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">State *</label>
                                <select name="state" class="form-select" required>
                                    <option value="">Select State</option>
                                    @foreach (\App\Models\State::all() as $state)
                                        <option value="{{ $state->id }}"
                                            {{ $consultant->state == $state->id ? 'selected' : '' }}>{{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City *</label>
                                <select name="city" class="form-select" required>
                                    <option value="">Select City</option>
                                    @if ($consultant->state)
                                        @foreach (\App\Models\City::where('state_id', $consultant->state)->get() as $city)
                                            <option value="{{ $city->id }}"
                                                {{ $consultant->city == $city->id ? 'selected' : '' }}>{{ $city->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address *</label>
                                <textarea name="address" class="form-control" rows="3" required>{{ $consultant->address }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Status</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input type="radio" name="status" id="edit-active" value="1"
                                            class="form-check-input" {{ $consultant->status == 1 ? 'checked' : '' }}>
                                        <label for="edit-active" class="form-check-label">Active</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="status" id="edit-inactive" value="0"
                                            class="form-check-input" {{ $consultant->status == 0 ? 'checked' : '' }}>
                                        <label for="edit-inactive" class="form-check-label">Inactive</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ✅ KYC Verify Handler - Unique ID targeting
            document.querySelectorAll('.verify-kyc-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const kycId = this.getAttribute('data-kyc-id');
                    const form = document.getElementById(`verify-form-${kycId}`);

                    Swal.fire({
                        title: 'Verify Document?',
                        text: 'Mark this KYC document as verified?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Verify!',
                        reverseButtons: true
                    }).then(result => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // ✅ KYC Reject Handler
            document.querySelectorAll('.reject-kyc-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const kycId = this.getAttribute('data-kyc-id');
                    const form = document.getElementById(`reject-form-${kycId}`);

                    Swal.fire({
                        title: 'Reject Document?',
                        text: 'Mark this KYC document as rejected?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Reject!',
                        reverseButtons: true
                    }).then(result => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // ✅ City dropdown based on state (Edit modal)
            const stateSelect = document.querySelector('#edit_consultant select[name="state"]');
            const citySelect = document.querySelector('#edit_consultant select[name="city"]');

            if (stateSelect && citySelect) {
                stateSelect.addEventListener('change', function() {
                    citySelect.innerHTML = '<option value="">Loading cities...</option>';
                    if (this.value) {
                        fetch(`/api/cities/${this.value}`)
                            .then(res => res.json())
                            .then(cities => {
                                citySelect.innerHTML = '<option value="">Select City</option>';
                                cities.forEach(city => {
                                    const opt = document.createElement('option');
                                    opt.value = city.id;
                                    opt.textContent = city.name;
                                    citySelect.appendChild(opt);
                                });
                            })
                            .catch(() => citySelect.innerHTML =
                                '<option value="">Error loading cities</option>');
                    } else {
                        citySelect.innerHTML = '<option value="">Select City</option>';
                    }
                });
            }
        });
    </script>
@endpush
