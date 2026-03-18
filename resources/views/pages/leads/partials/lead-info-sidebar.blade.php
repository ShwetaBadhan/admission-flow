<div class="card">
    <div class="card-body p-3">
        <h6 class="mb-3 fw-semibold">Lead Information</h6>
        
        <div class="border-bottom mb-3 pb-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <p class="mb-0">Lead ID</p>
                <p class="mb-0 text-dark">#{{ $lead->id }}</p>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <p class="mb-0">Full Name</p>
                <p class="mb-0 text-dark">{{ $lead->full_name }}</p>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <p class="mb-0">Mobile</p>
                <p class="mb-0 text-dark">
                    <a href="tel:{{ $lead->mobile }}">{{ $lead->mobile }}</a>
                </p>
            </div>
            @if($lead->email)
            <div class="d-flex align-items-center justify-content-between mb-2">
                <p class="mb-0">Email</p>
                <p class="mb-0 text-dark">
                    <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                </p>
            </div>
            @endif
            <div class="d-flex align-items-center justify-content-between mb-2">
                <p class="mb-0">Created</p>
                <p class="mb-0 text-dark">{{ $lead->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <!-- Counsellor Assignment -->
        <h6 class="mb-3 fw-semibold">Assignment</h6>
        <div class="border-bottom mb-3 pb-3">
            <form action="" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="form-label fs-13">Assign Counsellor</label>
                    <select name="consultant_id" class="select form-select-sm">
                        <option value="">Select Counsellor</option>
                        @foreach($consultants as $consultant)
                            <option value="{{ $consultant->id }}" 
                                {{ $lead->consultant_id == $consultant->id ? 'selected' : '' }}>
                                {{ $consultant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="ti ti-user-plus me-1"></i>Assign
                </button>
            </form>
            
            @if($lead->counsellor)
            <div class="mt-2 p-2 bg-soft-primary rounded">
                <small class="text-muted">Assigned to:</small><br>
                <strong>{{ $lead->counsellor->name }}</strong>
                @if($lead->assigned_at)
                <br><small class="text-muted">
                    on {{ $lead->assigned_at->format('d M Y') }}
                </small>
                @endif
            </div>
            @endif
        </div>

        <!-- Current Stage -->
        <h6 class="mb-3 fw-semibold">Current Stage</h6>
        <div class="mb-3">
            <form action="{{ route('leads.update-stage', $lead) }}" method="POST">
                @csrf
                <div class="mb-2">
                    <select name="stage" class="form-select form-select-sm">
                        @foreach($stages as $stage)
                            <option value="{{ $stage }}" 
                                {{ ($lead->currentStage?->stage ?? $lead->status) == $stage ? 'selected' : '' }}>
                                {{ ucfirst($stage) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <textarea name="notes" class="form-control form-control-sm" 
                              placeholder="Stage change notes..." rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-sm btn-success w-100">
                    <i class="ti ti-check me-1"></i>Update Stage
                </button>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="row g-2">
            <div class="col-6">
                <div class="p-2 bg-light rounded text-center">
                    <small class="text-muted d-block">Documents</small>
                    <strong>{{ $lead->documents->count() }}</strong>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2 bg-light rounded text-center">
                    <small class="text-muted d-block">Admissions</small>
                    <strong>{{ $lead->admissionRequests->count() }}</strong>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2 bg-light rounded text-center">
                    <small class="text-muted d-block">Communications</small>
                    <strong>{{ $lead->communications->count() }}</strong>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2 bg-light rounded text-center">
                    <small class="text-muted d-block">Stage Changes</small>
                    <strong>{{ $lead->stages->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>