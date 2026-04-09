@extends('layout.master')

{{-- Session Messages --}}
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
        Swal.fire({ icon: 'error', title: 'Validation Error!', html: @json($errors->all()), timer: 6000, timerProgressBar: true, showConfirmButton: true });
    });
</script>
@endif

@section('content')
<div class="page-wrapper">
    <div class="content pb-0">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Slab Rules <span class="badge badge-soft-primary ms-2">{{ $slabRules->total() }}</span></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Slab Rules</li>
                    </ol>
                </nav>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                {{-- <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Export</a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <ul>
                            <li><a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-pdf me-1"></i>Export as PDF</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-xls me-1"></i>Export as Excel</a></li>
                        </ul>
                    </div>
                </div> --}}
                <button class="btn btn-icon btn-outline-light shadow" onclick="location.reload()"><i class="ti ti-refresh"></i></button>
                <button class="btn btn-icon btn-outline-light shadow" id="collapse-header"><i class="ti ti-transition-top"></i></button>
            </div>
        </div>

         <!-- Card Start -->
        <div class="card border-0 rounded-0">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                {{-- 🔍 WORKING SEARCH FORM --}}
                <form method="GET" action="{{ route('slab-rules.index') }}" id="slabFilterForm" class="d-flex align-items-center gap-2 w-100 flex-wrap">
                    <div class="input-icon input-icon-start position-relative flex-grow-1" style="max-width: 350px;">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search consultant, college, course..." value="{{ request('search') }}">
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                        <!-- ⬆️⬇️ SORT -->
                        <div class="dropdown">
                            <select name="sort" class="select form-select-sm" style="width: auto;">
                                <option value="threshold_asc" {{ request('sort', 'threshold_asc') == 'threshold_asc' ? 'selected' : '' }}>Lowest Threshold</option>
                                <option value="threshold_desc" {{ request('sort') == 'threshold_desc' ? 'selected' : '' }}>Highest Threshold</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            </select>
                        </div>

                        <!-- 📅 DATE RANGE -->
                        <input type="text" name="start_date" class="form-control form-control-sm" placeholder="Start Date" value="{{ request('start_date') }}" data-provider="flatpickr" data-date-format="Y-m-d" style="width: 130px;">
                        <input type="text" name="end_date" class="form-control form-control-sm" placeholder="End Date" value="{{ request('end_date') }}" data-provider="flatpickr" data-date-format="Y-m-d" style="width: 130px;">

                        <!-- 🎛️ FILTER DROPDOWN -->
                        <div class="dropdown">
                            <button type="button" class="btn btn-outline-light shadow px-2 dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="ti ti-filter me-2"></i>Filters
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg p-0" style="min-width: 300px;">
                                <div class="filter-header d-flex align-items-center justify-content-between border-bottom p-3">
                                    <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Advanced Filters</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="dropdown" aria-label="Close"></button>
                                </div>
                                <div class="p-3">
                                    <div class="accordion" id="slabFilterAccordion">
                                        <!-- Threshold -->
                                        <div class="filter-set-content mb-3">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed d-block text-decoration-none" data-bs-toggle="collapse" data-bs-target="#filterThreshold" aria-expanded="false"><i class="ti ti-hash me-1"></i>Threshold</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="filterThreshold" data-bs-parent="#slabFilterAccordion">
                                                <div class="bg-light rounded border p-2 mt-2">
                                                    <label class="d-flex align-items-center mb-1"><input class="form-check-input m-0 me-2" type="checkbox" name="threshold[]" value="5" {{ in_array('5', request('threshold', [])) ? 'checked' : '' }}> 5 Admissions</label>
                                                    <label class="d-flex align-items-center mb-1"><input class="form-check-input m-0 me-2" type="checkbox" name="threshold[]" value="10" {{ in_array('10', request('threshold', [])) ? 'checked' : '' }}> 10 Admissions</label>
                                                    <label class="d-flex align-items-center"><input class="form-check-input m-0 me-2" type="checkbox" name="threshold[]" value="15+" {{ in_array('15+', request('threshold', [])) ? 'checked' : '' }}> 15+ Admissions</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Status -->
                                        <div class="filter-set-content mb-3">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed d-block text-decoration-none" data-bs-toggle="collapse" data-bs-target="#filterStatus" aria-expanded="false"><i class="ti ti-toggle-left me-1"></i>Status</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="filterStatus" data-bs-parent="#slabFilterAccordion">
                                                <div class="bg-light rounded border p-2 mt-2">
                                                    <label class="d-flex align-items-center mb-1"><input class="form-check-input m-0 me-2" type="radio" name="status" value="active" {{ request('status') == 'active' ? 'checked' : '' }}> Active</label>
                                                    <label class="d-flex align-items-center"><input class="form-check-input m-0 me-2" type="radio" name="status" value="inactive" {{ request('status') == 'inactive' ? 'checked' : '' }}> Inactive</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        {{-- <button type="button" onclick="resetFilters()" class="btn btn-outline-light flex-fill">Reset</button> --}}
                                        <button type="submit" class="btn btn-primary flex-fill">Apply Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        <button type="button" onclick="resetFilters()" class="btn btn-outline-light flex-fill">Reset</button>

                        <button type="submit" class="btn btn-primary"><i class="ti ti-search me-1"></i>Apply</button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <!-- Add New Button -->
                <div class="d-flex justify-content-end mb-3">
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_add_slab">
                        <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Slab Rule
                    </a>
                </div>

                <!-- Slab Rules Table -->
                <div class="table-responsive table-nowrap custom-table">
                    <table class="table table-nowrap" id="slab-rules-list">
                        <thead class="table-light">
                            <tr>
                                <th class="no-sort"><div class="form-check form-check-md"><input class="form-check-input" type="checkbox" id="select-all-slabs"></div></th>
                                <th class="no-sort"></th>
                                <th>Consultant</th>
                                <th>College</th>
                                <th>Course</th>
                                <th>Threshold</th>
                                <th>Bonus</th>
                                <th>Scope</th>
                                <th>Retro</th>
                                <th>Status</th>
                                <th class="text-end no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($slabRules as $slab)
                            <tr>
                                <td><div class="form-check form-check-md"><input class="form-check-input" type="checkbox"></div></td>
                                <td></td>
                                <td>{{ $slab->consultant?->name ?? '<span class="text-muted">All</span>' }}</td>
                                <td>{{ $slab->college?->name ?? '<span class="text-muted">All</span>' }}</td>
                                <td>{{ $slab->course_name ?? '<span class="text-muted">All</span>' }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $slab->threshold }} Admissions</span></td>
                                <td>{{ number_format($slab->bonus_value, 2) }}{{ $slab->bonus_type == 'fixed_amount' ? ' ' . $slab->currency : '%' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $slab->scope)) }}</td>
                                <td>{!! $slab->retroactive ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                                <td><span class="badge bg-{{ $slab->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($slab->status) }}</span></td>
                                <td>
                                    <div class="dropdown table-action">
                                        <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_edit_slab{{ $slab->id }}">
                                                <i class="ti ti-edit text-blue me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_slab{{ $slab->id }}">
                                                <i class="ti ti-trash text-danger me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Slab Rule Offcanvas -->
                            <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_edit_slab{{ $slab->id }}">
                                <div class="offcanvas-header border-bottom">
                                    <h5 class="mb-0">Edit Slab Rule</h5>
                                    <button type="button" class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <form action="{{ route('slab-rules.update', $slab) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Consultant <span class="text-muted">(Optional)</span></label>
                                                <select name="consultant_id" class="form-select select">
                                                    <option value="">All Consultants</option>
                                                    @foreach ($consultants as $c) <option value="{{ $c->id }}" {{ $slab->consultant_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option> @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">College <span class="text-muted">(Optional)</span></label>
                                                <select name="college_id" class="form-select select">
                                                    <option value="">All Colleges</option>
                                                    @foreach ($colleges as $col) <option value="{{ $col->id }}" {{ $slab->college_id == $col->id ? 'selected' : '' }}>{{ $col->name }}</option> @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Course <span class="text-muted">(Optional)</span></label>
                                                <select name="course_name" class="form-select select">
                                                    <option value="">All Courses</option>
                                                    @foreach ($courses as $crs) <option value="{{ $crs }}" {{ $slab->course_name == $crs ? 'selected' : '' }}>{{ $crs }}</option> @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Trigger Threshold <span class="text-danger">*</span></label>
                                                <input type="number" name="threshold" class="form-control" value="{{ $slab->threshold }}" min="1" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Bonus Type <span class="text-danger">*</span></label>
                                                <select name="bonus_type" class="form-select select" required>
                                                    <option value="fixed_amount" {{ $slab->bonus_type == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                                                    <option value="percentage_of_commission" {{ $slab->bonus_type == 'percentage_of_commission' ? 'selected' : '' }}>% of Commission</option>
                                                    <option value="percentage_of_fee" {{ $slab->bonus_type == 'percentage_of_fee' ? 'selected' : '' }}>% of Course Fee</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Bonus Value <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="bonus_value" class="form-control" value="{{ $slab->bonus_value }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Scope</label>
                                                <select name="scope" class="form-select select" required>
                                                    <option value="per_college" {{ $slab->scope == 'per_college' ? 'selected' : '' }}>Per College</option>
                                                    <option value="global" {{ $slab->scope == 'global' ? 'selected' : '' }}>Global (All Colleges)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" type="checkbox" name="retroactive" value="1" {{ $slab->retroactive ? 'checked' : '' }}>
                                                    <label class="form-check-label">Apply bonus retroactively when threshold is met</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Currency</label>
                                                <select name="currency" class="form-select select">
                                                    <option value="INR" {{ $slab->currency == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                                                    <option value="USD" {{ $slab->currency == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select select">
                                                    <option value="active" {{ $slab->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $slab->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Notes</label>
                                                <textarea name="notes" class="form-control" rows="3">{{ $slab->notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end mt-4 gap-2">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Slab</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Delete Modal (Keep your existing code here) -->
                            <div class="modal fade" id="delete_slab{{ $slab->id }}">
                               <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
                                    <div class="modal-content rounded-0">
                                        <div class="modal-body p-4 text-center position-relative">
                                            <form action="{{ route('slab-rules.destroy', $slab) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="mb-3 position-relative z-1">
                                                    <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                                                </div>
                                                <h5 class="mb-1">Delete Confirmation</h5>
                                                <p class="mb-3">Are you sure you want to remove this Slab Rule?</p>
                                                <div class="d-flex justify-content-center">
                                                    <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Cancel</a>
                                                    <button type="submit" class="btn btn-primary position-relative z-1 w-100">Yes, Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="10" class="text-center py-4 text-muted">No slab rules configured yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row align-items-center mt-3">
                    <div class="col-md-6"><div class="datatable-length"></div></div>
                    <div class="col-md-6"><div class="datatable-paginate">{{ $slabRules->withQueryString()->links() }}</div></div>
                </div>
            </div>
        </div>
        <!-- Card End -->
    </div>
</div>

<!-- Add Slab Rule Offcanvas -->
<div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add_slab">
    <div class="offcanvas-header border-bottom">
        <h5 class="mb-0">Add New Slab Rule</h5>
        <button type="button" class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('slab-rules.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Consultant <span class="text-muted">(Optional)</span></label>
                    <select name="consultant_id" class="form-select select">
                        <option value="">All Consultants</option>
                        @foreach ($consultants as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">College <span class="text-muted">(Optional)</span></label>
                    <select name="college_id" class="form-select select">
                        <option value="">All Colleges</option>
                        @foreach ($colleges as $col) <option value="{{ $col->id }}">{{ $col->name }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Course <span class="text-muted">(Optional)</span></label>
                    <select name="course_name" class="form-select select">
                        <option value="">All Courses</option>
                        @foreach ($courses as $crs) <option value="{{ $crs }}">{{ $crs }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Trigger Threshold <span class="text-danger">*</span></label>
                    <input type="number" name="threshold" class="form-control" min="1" required placeholder="e.g., 5, 10, 15">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bonus Type <span class="text-danger">*</span></label>
                    <select name="bonus_type" class="form-select select" required>
                        <option value="fixed_amount">Fixed Amount</option>
                        <option value="percentage_of_commission">% of Base Commission</option>
                        <option value="percentage_of_fee">% of Course Fee</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bonus Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="bonus_value" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Scope</label>
                    <select name="scope" class="form-select select" required>
                        <option value="per_college">Per College</option>
                        <option value="global">Global (All Colleges)</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="retroactive" value="1" checked>
                        <label class="form-check-label">Apply bonus retroactively when threshold is met</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Currency</label>
                    <select name="currency" class="form-select select">
                        <option value="INR">INR (₹)</option>
                        <option value="USD">USD ($)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end mt-4 gap-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Slab</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
    <script>
    // 🔁 Reset Filters Function
    function resetFilters() {
        document.getElementById('slabFilterForm').reset();
        // Remove query params by reloading without GET parameters
        window.location.href = "{{ route('slab-rules.index') }}";
    }

    // ✅ Auto-submit on Sort change
    document.querySelector('select[name="sort"]')?.addEventListener('change', function() {
        document.getElementById('slabFilterForm').submit();
    });

    // 📅 Initialize Flatpickr if not auto-loaded
    if(typeof flatpickr !== 'undefined') {
        flatpickr('input[name="start_date"]', { dateFormat: "Y-m-d" });
        flatpickr('input[name="end_date"]', { dateFormat: "Y-m-d" });
    }
</script>
@endpush