<div class="card">
    <div class="card-header">
        <h5 class="fw-semibold mb-0">Files</h5>
    </div>
    <div class="card-body">
        <div class="card border mb-3">
            <div class="card-body pb-0">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <h6 class="mb-1">Manage Documents</h6>
                            <p>Send customizable quotes, proposals and contracts to close deals faster.</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="mb-3">
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#new_file">Create Document</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @forelse($lead->documents as $doc)
            <div class="card border shadow-none mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Left Side - Document Info -->
                        <div class="d-flex align-items-center">
                            <!-- Document Icon -->
                            <div class="flex-shrink-0 me-3">
                                <div
                                    class="avatar avatar-md bg-soft-primary rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ti ti-file-text text-primary"></i>
                                </div>
                            </div>

                            <!-- Document Details -->
                            <div>
                                <h6 class="fw-semibold fs-14 mb-1">{{ ucfirst($doc->document_type) }}</h6>
                                <p class="mb-0 text-muted small">{{ $doc->file_name }}</p>
                            </div>
                        </div>

                        <!-- Right Side - Status & Actions -->
                        @if (!$doc->is_verified)
                            <div class="d-flex align-items-center gap-3">
                                @if ($doc->is_verified === null)
                                    <!-- Pending Badge + Verify/Reject Buttons -->
                                    <span
                                        class="badge bg-soft-warning text-warning border d-flex align-items-center gap-1 px-3 py-2">
                                        <i class="ti ti-clock"></i> pending
                                    </span>

                                    <!-- Verify Button -->
                                    <form id="verify-form-{{ $doc->id }}"
                                        action="{{ route('leads.verify-document', [$lead, $doc]) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-link text-success p-0 verify-btn"
                                            data-doc-id="{{ $doc->id }}">
                                            Verify
                                        </button>
                                    </form>

                                    <!-- Reject Button -->
                                    <form id="reject-form-{{ $doc->id }}"
                                        action="{{ route('leads.reject-document', [$lead, $doc]) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0 reject-btn"
                                            data-doc-id="{{ $doc->id }}">
                                            Reject
                                        </button>
                                    </form>
                                @elseif($doc->is_verified === 1 || $doc->is_verified === true)
                                    <!-- Verified Badge -->
                                    <span
                                        class="badge bg-soft-success text-success border d-flex align-items-center gap-1 px-3 py-2">
                                        <i class="ti ti-check"></i> verified
                                    </span>
                                @else
                                    <!-- Rejected Badge -->
                                    <span
                                        class="badge bg-soft-danger text-danger border d-flex align-items-center gap-1 px-3 py-2">
                                        <i class="ti ti-x"></i> rejected
                                    </span>
                                @endif

                                <!-- External Link Icon (Shows for ALL documents) -->
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-muted">
                                    <i class="ti ti-external-link fs-5"></i>
                                </a>
                            </div>
                        @else
                            <!-- Verified Badge -->
                            <span
                                class="badge bg-soft-success text-success border d-flex align-items-center gap-1 px-3 py-2">
                                <i class="ti ti-check"></i> verified
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted text-center py-4">No documents uploaded yet.</p>
        @endforelse
    </div>
</div>
<!-- Add File -->
<div class="modal custom-modal fade custom-modal-two modal-padding" id="new_file" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New File</h5>
                <button type="button" class="btn-close custom-btn-close border p-1 me-0 text-dark"
                    data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{ route('leads.upload-document', $lead) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Document Type <span class="text-danger">*</span></label>
                              <select name="document_type" class="select" required>
    <option value="" disabled selected>Select Document Type</option>
    @foreach ($documentTypes as $documentType)
        @php
            $alreadyUploaded = $lead->documents()
                ->where('document_type', $documentType->id)
                ->where('is_verified', '!=', false) // Adjust logic as needed
                ->exists();
        @endphp
        <option value="{{ $documentType->id }}"
                {{ old('document_type') == $documentType->id ? 'selected' : '' }}
                {{ $alreadyUploaded ? 'disabled' : '' }}>
            {{ $documentType->name }} 
            {{ $alreadyUploaded ? '(Already Uploaded)' : '' }}
        </option>
    @endforeach
</select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">File <span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                        </div>
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
<!-- /Add File -->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verify button click handler
            document.querySelectorAll('.verify-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const docId = this.getAttribute('data-doc-id');
                    const form = document.getElementById(`verify-form-${docId}`);

                    Swal.fire({
                        title: 'Verify Document?',
                        text: 'Are you sure you want to verify this document?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Verify it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Reject button click handler
            document.querySelectorAll('.reject-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const docId = this.getAttribute('data-doc-id');
                    const form = document.getElementById(`reject-form-${docId}`);

                    Swal.fire({
                        title: 'Reject Document?',
                        text: 'Are you sure you want to reject this document?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Reject it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
