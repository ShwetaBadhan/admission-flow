<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
        <h5 class="fw-semibold mb-0">Calls</h5>
        <div class="d-inline-flex align-items-center">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_call"
                class="link-primary fw-medium"><i class="ti ti-circle-plus me-1"></i>Add New</a>
        </div>
    </div>
    <div class="card-body">
        @forelse($lead->communications->where('type', 'call')->take(5) as $call)
            <div class="d-flex align-items-start mb-4 pb-4 border-bottom">
                <!-- Icon -->
                <div class="flex-shrink-0 me-3">
                    <div
                        class="avatar avatar-md bg-soft-secondary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ti ti-phone text-secondary"></i>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-grow-1">
                    <!-- Header -->
                    <div class="d-flex align-items-center flex-wrap mb-2">
                        <h6 class="fw-bold mb-0 me-2">{{ $call->call_status ?? 'Calls' }}</h6>
                        <span class="text-muted me-2">by {{ $call->createdBy->name ?? 'System' }}</span>
                        <span class="text-muted">•</span>
                        <span class="text-muted ms-2">{{ $call->created_at->diffForHumans() }}</span>
                    </div>

                    <!-- Note Content -->
                    <p class="mb-2 text-dark">{{ $call->content }}</p>



                    <!-- ✅ Created Date (Fixed) -->
                    <p class="mb-0 small">
                        <span class="text-muted">Follow-up:</span>
                        <span class="text-primary">{{ $call->created_at->format('M d, Y') }}</span>
                    </p>
                </div>
            </div>
        @empty
            <p class="text-muted text-center py-4">No calls logged yet.</p>
        @endforelse
    </div>
</div>
</div>



<!-- Create Call Log -->
<div class="modal fade" id="create_call" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Call Log</h5>
                <button type="button" class="btn-close custom-btn-close border p-1 me-0 text-dark"
                    data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{ route('leads.add-communication', $lead) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="call">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Call Direction <span class="text-danger">*</span></label>
                                <select class="select" name="direction" required>
                                    <option value="inbound">Inbound</option>
                                    <option value="outbound">Outbound</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact<span class="text-danger"> *</span></label>
                                <select class="select" name="call_status" required>
                                    <option value="">Select Contact</option>
                                    @foreach ($communicationTypes as $type)
                                        <option value="{{ $type }}"
                                            {{ old('call_status') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('call_status')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Follow Up Date <span class="text-danger">*</span></label>
                                <div class="input-group w-auto input-group-flat">
                                    <input type="text" class="form-control" data-provider="flatpickr"
                                        data-date-format="d M, Y" value="23-05-2025">
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Note <span class="text-danger"> *</span></label>
                                <textarea class="form-control" name="content" rows="4"></textarea>
                            </div>
                            <div>
                                <div class="form-check mb-1">
                                    <input type="checkbox" class="form-check-input" id="customCheck1">
                                    <label class="form-check-label" for="customCheck1">Create a follow up task</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-light" data-bs-dismiss="modal">Cancel</a>
                    <button class="btn btn-primary" type="submit">Create New</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Create Call Log -->
