<!-- Total Leads Card -->
<div class="col-xl-3 col-sm-6 d-flex">
    <div class="card flex-fill">
        <div class="card-body position-relative">
            <p class="fw-medium mb-1">Total Leads</p>
            <h4 class="mb-3">{{ number_format($leadsCurrent) }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="d-inline-flex align-items-center badge rounded-pill border-0 
                    {{ $leadsChange >= 0 ? 'badge-soft-success' : 'badge-soft-danger' }}">
                    {{ $leadsChange >= 0 ? '+' : '' }}{{ $leadsChange }}%
                </span>
                <p class="text-dark mb-0">From Last Week</p>
            </div>
            <div class="custom-card-icon">
                <div class="avatar avatar-rounded avatar-lg bg-primary-gradient-100 position-absolute top-0 end-0">
                    <img src="{{ url('assets/img/icons/user.png') }}" alt="icon" class="img-fluid w-auto h-auto">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Colleges Card -->
<div class="col-xl-3 col-sm-6 d-flex">
    <div class="card flex-fill">
        <div class="card-body position-relative">
            <p class="fw-medium mb-1">Total Colleges</p>
            <h4 class="mb-3">{{ number_format($collegesCurrent) }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="d-inline-flex align-items-center badge rounded-pill border-0 
                    {{ $collegesChange >= 0 ? 'badge-soft-success' : 'badge-soft-danger' }}">
                    {{ $collegesChange >= 0 ? '+' : '' }}{{ $collegesChange }}%
                </span>
                <p class="text-dark mb-0">From Last Week</p>
            </div>
            <div class="custom-card-icon">
                <div class="avatar avatar-rounded avatar-lg bg-info-gradient-100 position-absolute top-0 end-0">
                    <img src="{{ url('assets/img/icons/college.png') }}" alt="icon" class="img-fluid w-auto h-auto">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Colleges Card -->
<div class="col-xl-3 col-sm-6 d-flex">
    <div class="card flex-fill">
        <div class="card-body position-relative">
            <p class="fw-medium mb-1">Total Consultants</p>
            <h4 class="mb-3">{{ number_format($consultantsCurrent) }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="d-inline-flex align-items-center badge rounded-pill border-0 
                    {{ $consultantsChange >= 0 ? 'badge-soft-success' : 'badge-soft-danger' }}">
                    {{ $consultantsChange >= 0 ? '+' : '' }}{{ $consultantsChange }}%
                </span>
                <p class="text-dark mb-0">From Last Week</p>
            </div>
            <div class="custom-card-icon">
                <div class="avatar avatar-rounded avatar-lg bg-info-gradient-100 position-absolute top-0 end-0">
                    <img src="{{ url('assets/img/icons/man-with-tie-profile.png') }}" alt="icon" class="img-fluid w-auto h-auto">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Leads Card -->
<div class="col-xl-3 col-sm-6 d-flex">
    <div class="card flex-fill">
        <div class="card-body position-relative">
            <p class="fw-medium mb-1">Total Courses</p>
            <h4 class="mb-3">{{ number_format($coursesCurrent) }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="d-inline-flex align-items-center badge rounded-pill border-0 
                    {{ $coursesChange >= 0 ? 'badge-soft-success' : 'badge-soft-danger' }}">
                    {{ $coursesChange >= 0 ? '+' : '' }}{{ $coursesChange }}%
                </span>
                <p class="text-dark mb-0">From Last Week</p>
            </div>
            <div class="custom-card-icon">
                <div class="avatar avatar-rounded avatar-lg bg-primary-gradient-100 position-absolute top-0 end-0">
                    <img src="{{ url('assets/img/icons/book.png') }}" alt="icon" class="img-fluid w-auto h-auto">
                </div>
            </div>
        </div>
    </div>
</div>