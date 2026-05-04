@extends('layout.master')
{{-- Session Messages --}}
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
    <!-- ========================
                                   Start Page Content
                                  ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Colleges<span class="badge badge-soft-primary ms-2">{{ count($colleges) }}</span>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Colleges</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow"
                            data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Export</a>
                        <div class="dropdown-menu  dropdown-menu-end">
                            <ul>
                                <li>
                                    <a href="{{ route('export.pdf', request()->query()) }}" class="dropdown-item">
                                        <i class="ti ti-file-type-pdf me-1"></i>Export as PDF
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('export.excel', request()->query()) }}" class="dropdown-item">
                                        <i class="ti ti-file-type-xls me-1"></i>Export as Excel
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i
                            class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse"
                        id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- table header -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <!-- Replace the entire filter dropdown with this -->
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"><i class="ti ti-filter me-2"></i>Filter<i
                                class="ti ti-chevron-down ms-2"></i></a>
                        <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                            <form method="GET" action="{{ route('colleges.index') }}" id="filter-form">
                                <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                    <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filter</h6>
                                    <button type="button" class="btn-close close-filter-btn"
                                        data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                                </div>
                                <div class="filter-set-view p-3">
                                    <div class="accordion" id="accordionExample">

                                        <!-- Status Filter -->
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" data-bs-toggle="collapse" data-bs-target="#Status"
                                                    aria-expanded="true" aria-controls="Status">Status</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse show" id="Status"
                                                data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <ul>
                                                        <li>
                                                            <label class="dropdown-item px-2 d-flex align-items-center">
                                                                <input class="form-check-input m-0 me-1 filter-checkbox"
                                                                    type="radio" name="status" value="active"
                                                                    {{ request('status') == 'active' ? 'checked' : '' }}>
                                                                Active
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="dropdown-item px-2 d-flex align-items-center">
                                                                <input class="form-check-input m-0 me-1 filter-checkbox"
                                                                    type="radio" name="status" value="inactive"
                                                                    {{ request('status') == 'inactive' ? 'checked' : '' }}>
                                                                Inactive
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Location Filter -->
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseFive" aria-expanded="false"
                                                    aria-controls="collapseFive">Location</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="collapseFive"
                                                data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <div class="mb-2">
                                                        <select class="form-select form-select-sm" name="state_id"
                                                            id="filter_state_id">
                                                            <option value="">All States</option>
                                                            @foreach ($states as $state)
                                                                <option value="{{ $state->id }}"
                                                                    {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <select class="form-select form-select-sm" name="city_id"
                                                            id="filter_city_id">
                                                            <option value="">All Cities</option>
                                                            @if (request('state_id'))
                                                                @foreach (\App\Models\City::where('state_id', request('state_id'))->get() as $city)
                                                                    <option value="{{ $city->id }}"
                                                                        {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                                                        {{ $city->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Course Filter -->
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseCourses" aria-expanded="false"
                                                    aria-controls="collapseCourses">Courses</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse"
                                                id="collapseCourses" data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <select class="form-select form-select-sm" name="course_id">
                                                        <option value="">All Courses</option>
                                                        @foreach ($courses as $course)
                                                            <option value="{{ $course->id }}"
                                                                {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                                                {{ $course->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Filter Buttons -->
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <a href="{{ route('colleges.index') }}"
                                            class="btn btn-outline-light w-100">Reset</a>
                                        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">

                    @can('create-colleges')
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add
                            College</a>
                    @endcan

                </div>
            </div>
            <!-- table header -->

            <!-- College Grid -->
            <div class="row">
                @foreach ($colleges as $college)
                    <div class="col-xxl-3 col-xl-4 col-md-6 mb-3">
                        <div class="card border shadow h-100">
                            <div class="card-body">

                                <!-- Header: Logo + Name + Actions -->
                                <div
                                    class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 mb-3 border-bottom pb-3">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('colleges.index', $college->id) }}"
                                            class="avatar border rounded-circle flex-shrink-0 me-2">
                                            @if ($college->college_image)
                                                <img src="{{ Storage::url($college->college_image) }}"
                                                    class="w-40 h-40 object-fit-cover" alt="{{ $college->name }}">
                                            @else
                                                <img src="{{ asset('assets/img/icons/company-icon-01.svg') }}"
                                                    class="w-auto h-auto" alt="default">
                                            @endif
                                        </a>
                                        <div>
                                            <h6 class="fs-14 mb-0">
                                                <a href="{{ route('colleges.index', $college->id) }}"
                                                    class="fw-medium text-decoration-none">
                                                    {{ $college->name }}
                                                </a>
                                            </h6>
                                            <div class="set-star text-default small">
                                                @if ($college->status === 'active')
                                                    <span class="badge badge-pill badge-status bg-success">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-pill badge-status bg-danger">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dropdown Actions -->
                                    <div class="dropdown table-action">
                                        <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @can('edit-colleges')
                                                <a class="dropdown-item edit-college" href="javascript:void(0)"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvas_edit_{{ $college->id }}">
                                                    <i class="ti ti-edit text-blue me-1"></i> Edit
                                                </a>
                                            @endcan
                                            @can('delete-colleges')
                                                <a class="dropdown-item delete-college" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_contact{{ $college->id }}">
                                                    <i class="ti ti-trash text-danger me-1"></i> Delete
                                                </a>
                                            @endcan


                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <div class="d-block">
                                    <div class="d-flex flex-column mb-0">
                                        @if ($college->email)
                                            <p class="text-default d-inline-flex align-items-center mb-2 small">
                                                <i class="ti ti-mail text-dark me-1"></i>
                                                <a href="mailto:{{ $college->email }}"
                                                    class="text-decoration-none">{{ Str::limit($college->email, 25) }}</a>
                                            </p>
                                        @endif

                                        @if ($college->phone)
                                            <p class="text-default d-inline-flex align-items-center mb-2 small">
                                                <i class="ti ti-phone text-dark me-1"></i>{{ $college->phone }}
                                            </p>
                                        @endif
                                        @if ($college->fees_range)
                                            <p class="text-default d-inline-flex align-items-center mb-2 small">
                                                <i class="ti ti-cash text-dark me-1"></i>{{ $college->fees_range }}
                                            </p>
                                        @endif
                                        @if ($college->website)
                                            <p class="text-default d-inline-flex align-items-center mb-2 small">
                                                <i class="ti ti-world text-dark me-1"></i>
                                                <a href="mailto:{{ $college->website }}"
                                                    class="text-decoration-none">{{ Str::limit($college->website, 25) }}</a>
                                            </p>
                                        @endif
                                        <p class="text-default d-inline-flex align-items-center small">
                                            <i class="ti ti-map-pin text-dark me-1"></i>
                                            {{ $college->city->name ?? 'N/A' }}, {{ $college->state->name ?? 'N/A' }}
                                        </p>


                                    </div>

                                    <!-- Courses as Badges -->
                                    <div class="d-flex align-items-center flex-wrap gap-1 mt-2">
                                        @php
                                            // Get selected course IDs (auto-casted to array via $casts)
                                            $selectedCourseIds = $college->course_ids ?? [];

                                            // Fetch course names if IDs exist
                                            $courseNames = [];
                                            if (!empty($selectedCourseIds)) {
                                                $courseNames = \App\Models\Course::whereIn('id', $selectedCourseIds)
                                                    ->pluck('name')
                                                    ->toArray();
                                            }
                                        @endphp

                                        @foreach (array_slice($courseNames, 0, 3) as $courseName)
                                            @if ($courseName)
                                                <span class="badge badge-tag badge-soft-primary me-1">
                                                    {{ Str::limit($courseName, 12) }}
                                                </span>
                                            @endif
                                        @endforeach

                                        @if (count($courseNames) > 3)
                                            <span class="badge badge-tag badge-soft-secondary">
                                                +{{ count($courseNames) - 3 }} more
                                            </span>
                                        @endif

                                        @if (empty($courseNames))
                                            <span class="badge badge-tag badge-soft-secondary">No courses</span>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Edit Offcanvas - Inside Loop -->
                    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1"
                        id="offcanvas_edit_{{ $college->id }}">
                        <div class="offcanvas-header border-bottom">
                            <h5 class="mb-0">Edit College</h5>
                            <button type="button"
                                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form action="{{ route('colleges.update', $college->id) }}" method="POST" enctype="multipart/form-data" class="ajax-form" id="edit-college-form-{{ $college->id }}">
                                @csrf
                                @method('PUT')

                                <div class="accordion accordion-bordered" id="main_accordion_edit_{{ $college->id }}">

                                    <!-- Basic Info -->
                                    <div class="accordion-item rounded mb-3">
                                        <div class="accordion-header">
                                            <a href="#" class="accordion-button accordion-custom-button rounded"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#basic_edit_{{ $college->id }}">
                                                <span class="avatar avatar-md rounded me-1"><i
                                                        class="ti ti-user-plus"></i></span> Basic Info
                                            </a>
                                        </div>
                                        <div class="accordion-collapse collapse show" id="basic_edit_{{ $college->id }}"
                                            data-bs-parent="#main_accordion_edit_{{ $college->id }}">
                                            <div class="accordion-body border-top">
                                                <div class="row">
                                                    <!-- Image Upload -->
                                                    <div class="col-md-12">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div
                                                                class="avatar avatar-xxl border border-dashed me-3 flex-shrink-0">
                                                                <div class="position-relative d-flex align-items-center">
                                                                    @if ($college->college_image)
                                                                        <img src="{{ Storage::url($college->college_image) }}"
                                                                            class="rounded"
                                                                            style="width:100%; height:100%; object-fit:cover;"
                                                                            alt="{{ $college->name }}">
                                                                    @else
                                                                        <i class="ti ti-photo text-dark fs-16"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="d-inline-flex flex-column align-items-start">
                                                                <div
                                                                    class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                                                    <i class="ti ti-file-broken me-1"></i>Upload file
                                                                    <input type="file" class="form-control image-sign"
                                                                        name="college_image" accept="image/*">
                                                                </div>
                                                                <span>JPG, GIF or PNG. Max size of 800K</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- College Name -->
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">College Name<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="name"
                                                                value="{{ old('name', $college->name) }}" required>
                                                        </div>
                                                    </div>

                                                    <!-- Email -->
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Email <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="email" class="form-control" name="email"
                                                                value="{{ old('email', $college->email) }}" required>
                                                        </div>
                                                    </div>

                                                    <!-- Phone & Website -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Phone</label>
                                                            <input type="text" class="form-control" name="phone"
                                                                value="{{ old('phone', $college->phone) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Website</label>
                                                            <input type="url" class="form-control" name="website"
                                                                value="{{ old('website', $college->website) }}">
                                                        </div>
                                                    </div>

                                                    <!-- Programs -->
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Programs (Comma Separated)</label>
                                                            <select class="form-select" name="course_ids[]"
                                                                id="course_ids_edit_{{ $college->id }}" data-choices
                                                                data-choices-removeItem multiple>
                                                                <option value="">Select Courses</option>
                                                                @if (isset($courses))
                                                                    @foreach ($courses as $course)
                                                                        <option value="{{ $course->id }}"
                                                                            {{ in_array($course->id, $college->course_ids ?? []) ? 'selected' : '' }}>
                                                                            {{ $course->name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Deadline & Fees -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Application Deadline</label>
                                                            <input type="date" class="form-control"
                                                                name="application_deadline"
                                                                value="{{ old('application_deadline', $college->application_deadline?->format('Y-m-d')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Fees Range</label>
                                                            <input type="text" class="form-control" name="fees_range"
                                                                value="{{ old('fees_range', $college->fees_range) }}"
                                                                placeholder="e.g. 50k - 1L">
                                                        </div>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select class="select" name="status">
                                                                <option value="active"
                                                                    {{ old('status', $college->status) == 'active' ? 'selected' : '' }}>
                                                                    Active</option>
                                                                <option value="inactive"
                                                                    {{ old('status', $college->status) == 'inactive' ? 'selected' : '' }}>
                                                                    Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Basic Info -->

                                    <!-- Address Info -->
                                    <div class="accordion-item border-top rounded mb-3">
                                        <div class="accordion-header">
                                            <a href="#" class="accordion-button accordion-custom-button rounded"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#address_edit_{{ $college->id }}">
                                                <span class="avatar avatar-md rounded me-1"><i
                                                        class="ti ti-map-pin-cog"></i></span> Address Info
                                            </a>
                                        </div>
                                        <div class="accordion-collapse collapse" id="address_edit_{{ $college->id }}"
                                            data-bs-parent="#main_accordion_edit_{{ $college->id }}">
                                            <div class="accordion-body border-top">
                                                <div class="row">
                                                    <!-- State -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">State <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-select" name="state_id"
                                                                id="state_id_edit_{{ $college->id }}" required>
                                                                <option value="">Select State</option>
                                                                @foreach ($states as $state)
                                                                    <option value="{{ $state->id }}"
                                                                        {{ old('state_id', $college->state_id) == $state->id ? 'selected' : '' }}>
                                                                        {{ $state->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- City -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">City <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-select" name="city_id"
                                                                id="city_id_edit_{{ $college->id }}" required>
                                                                <option value="">Select City</option>
                                                                @if ($college->city)
                                                                    <option value="{{ $college->city->id }}" selected>
                                                                        {{ $college->city->name }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Address Info -->

                                </div>

                                <!-- Buttons -->
                                <div class="d-flex align-items-center justify-content-end mt-3">
                                    <button type="button" data-bs-dismiss="offcanvas"
                                        class="btn btn-light me-2">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update College</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Edit Offcanvas -->
                    <!-- delete modal -->
                    <div class="modal fade" id="delete_contact{{ $college->id }}">
                        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
                            <div class="modal-content rounded-0">
                                <div class="modal-body p-4 text-center position-relative">
                                    <form action="{{ route('colleges.destroy', $college->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="mb-3 position-relative z-1">
                                            <span
                                                class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i
                                                    class="ti ti-trash fs-24"></i></span>
                                        </div>
                                        <h5 class="mb-1">Delete Confirmation</h5>
                                        <p class="mb-3">Are you sure you want to remove College you selected.</p>

                                        <div class="d-flex justify-content-center">
                                            <a href="#" class="btn btn-light position-relative z-1 me-2 w-100"
                                                data-bs-dismiss="modal">Cancel</a>
                                            <button type="submit"
                                                class="btn btn-primary position-relative z-1 w-100">Yes, Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- delete modal -->
                @endforeach
            </div>
            <!-- /College Grid -->

          

        </div>
        <!-- End Content -->


    </div>

    <!-- ======================== End Page Content ========================= -->

    </div>
    <!-- End Wrapper -->
    <!-- Add College Offcanvas -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="mb-0" id="offcanvas_title">Add New College</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('colleges.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form" id="add-college-form">
                @csrf
                <div class="accordion accordion-bordered" id="main_accordion">
                    <!-- Basic Info -->
                    <div class="accordion-item rounded mb-3">
                        <div class="accordion-header">
                            <a href="#" class="accordion-button accordion-custom-button rounded"
                                data-bs-toggle="collapse" data-bs-target="#basic">
                                <span class="avatar avatar-md rounded me-1"><i class="ti ti-user-plus"></i></span> Basic
                                Info
                            </a>
                        </div>
                        <div class="accordion-collapse collapse show" id="basic" data-bs-parent="#main_accordion">
                            <div class="accordion-body border-top">
                                <div class="row">
                                    <!-- Image Upload -->
                                    <div class="col-md-12">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar avatar-xxl border border-dashed me-3 flex-shrink-0">
                                                <div class="position-relative d-flex align-items-center">
                                                    <img id="image_preview" src=""
                                                        style="display:none; width:100%; height:100%; object-fit:cover;"
                                                        class="rounded">
                                                    <i id="image_icon" class="ti ti-photo text-dark fs-16"></i>
                                                </div>
                                            </div>
                                            <div class="d-inline-flex flex-column align-items-start">
                                                <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                                    <i class="ti ti-file-broken me-1"></i>Upload file
                                                    <input type="file" class="form-control image-sign"
                                                        name="college_image" id="college_image" accept="image/*">
                                                </div>
                                                <span>JPG, GIF or PNG. Max size of 800K</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- College Name -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">College Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                required>
                                        </div>
                                    </div>
                                    <!-- Email -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                required>
                                        </div>
                                    </div>
                                    <!-- Phone & Website -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone <span
                                                    class="text-danger">*</span></label></label>
                                            <input type="text" class="form-control" name="phone" id="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Website <span
                                                    class="text-danger">*</span></label></label>
                                            <input type="url" class="form-control" name="website" id="website">
                                        </div>
                                    </div>
                                    <!-- Courses (Fixed - No $college reference) -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Select Courses <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="course_ids[]" id="course_ids_add"
                                                data-choices data-choices-removeItem multiple>
                                                <option value="">Select Courses</option>
                                                @if (isset($courses))
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="invalid-feedback">Please select at least one course.</div>
                                        </div>
                                    </div>
                                    <!-- Deadline & Fees -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Application Deadline <span
                                                    class="text-danger">*</span></label></label>
                                            <input type="date" class="form-control" name="application_deadline"
                                                id="application_deadline">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Fees Range <span
                                                    class="text-danger">*</span></label></label>
                                            <input type="text" class="form-control" name="fees_range" id="fees_range"
                                                placeholder="e.g. 50k - 1L">
                                        </div>
                                    </div>
                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select class="select" name="status" data-toggle="select">
                                                <option value="" disabled>Select Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Address Info -->
                    <div class="accordion-item border-top rounded mb-3">
                        <div class="accordion-header">
                            <a href="#" class="accordion-button accordion-custom-button rounded"
                                data-bs-toggle="collapse" data-bs-target="#address">
                                <span class="avatar avatar-md rounded me-1"><i class="ti ti-map-pin-cog"></i></span>
                                Address Info
                            </a>
                        </div>
                        <div class="accordion-collapse collapse" id="address" data-bs-parent="#main_accordion">
                            <div class="accordion-body border-top">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">State <span class="text-danger">*</span></label>
                                            <select class="form-select" name="state_id" id="state_id" required>
                                                <option value="">Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">City <span class="text-danger">*</span></label>
                                            <select class="form-select" name="city_id" id="city_id" required>
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end mt-3">
                    <button type="button" data-bs-dismiss="offcanvas" class="btn btn-light me-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save College</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // === 🍞 SweetAlert Toast ===
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false,
        timer: 3000, timerProgressBar: true,
        didOpen: (t) => { t.addEventListener('mouseenter', Swal.stopTimer); t.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    // === 🔄 Get CSRF Token ===
    function getCSRF() {
        return document.querySelector('meta[name="csrf-token"]')?.content 
            || document.querySelector('input[name="_token"]')?.value 
            || '';
    }

    // === 🎯 AJAX Form Handler ===
    function initAjaxForms() {
        document.querySelectorAll('form.ajax-form').forEach(form => {
            if (form.dataset.ajaxDone) return;
            form.dataset.ajaxDone = '1';

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('🔄 AJAX submit:', form.id);

                const btn = form.querySelector('button[type="submit"]');
                const originalBtn = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

                // Clear old errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback, .text-danger.small').forEach(el => el?.remove());

                const formData = new FormData(form);
                const method = form.querySelector('input[name="_method"]')?.value?.toUpperCase() || 'POST';

                try {
                    const response = await fetch(form.action, {
                        method: ['PUT','PATCH','DELETE'].includes(method) ? 'POST' : method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': getCSRF()
                        },
                        body: formData
                    });

                    const contentType = response.headers.get('content-type');
                    let data = {};
                    if (contentType?.includes('application/json')) {
                        data = await response.json();
                    }

                    console.log('📡 Response:', response.status, data);

                    if (response.ok) {
                        // ✅ Success
                        Toast.fire({ icon: 'success', title: data.message || 'Saved successfully!' });
                        setTimeout(() => {
                            const offcanvas = form.closest('.offcanvas');
                            if (offcanvas && typeof bootstrap !== 'undefined') {
                                bootstrap.Offcanvas.getInstance(offcanvas)?.hide();
                            }
                            window.location.reload();
                        }, 1200);
                    } 
                    else if (response.status === 422 && data.errors) {
                        // ❌ Validation errors - show inline
                        Object.entries(data.errors).forEach(([field, msgs]) => {
                            const input = form.querySelector(`[name="${field}"], [name="${field}[]"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const err = document.createElement('small');
                                err.className = 'text-danger small d-block mt-1';
                                err.textContent = msgs[0];
                                // Insert after input or its wrapper
                                const target = input.closest('.mb-3') || input.parentNode;
                                target.appendChild(err);
                            }
                        });
                        // Show first error in Swal
                        const firstMsg = Object.values(data.errors)[0]?.[0] || 'Validation failed';
                        Swal.fire({ icon: 'error', title: 'Validation Error', text: firstMsg, confirmButtonColor: '#0d6efd' });
                    } 
                    else {
                        // ❌ Server error
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong' });
                    }
                } catch (err) {
                    console.error('💥 AJAX Error:', err);
                    Swal.fire({ icon: 'error', title: 'Connection Error', text: err.message });
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalBtn;
                }
            });
        });
    }

    initAjaxForms();

    // === 🖼️ Image Preview (Add Form) ===
    const imageInput = document.getElementById('college_image');
    const imagePreview = document.getElementById('image_preview');
    const imageIcon = document.getElementById('image_icon');
    if (imageInput && imagePreview && imageIcon) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    imageIcon.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // === 🌍 Dynamic City Load (Add Form) ===
    const stateSelect = document.getElementById('state_id');
    const citySelect = document.getElementById('city_id');
    if (stateSelect && citySelect) {
        stateSelect.addEventListener('change', async function() {
            citySelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.disabled = true;
            if (!this.value) {
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = false;
                return;
            }
            try {
                const res = await fetch(`/get-cities/${this.value}`);
                const cities = await res.json();
                citySelect.innerHTML = '<option value="">Select City</option>';
                cities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id; opt.textContent = c.name;
                    citySelect.appendChild(opt);
                });
            } catch(e) { console.error('City load error:', e); }
            citySelect.disabled = false;
        });
    }

    // === 🌍 Dynamic City Load (Edit Forms - Loop) ===
    document.querySelectorAll('[id^="state_id_edit_"]').forEach(stateSel => {
        const id = stateSel.id.replace('state_id_edit_', '');
        const citySel = document.getElementById(`city_id_edit_${id}`);
        if (!citySel) return;
        
        stateSel.addEventListener('change', async function() {
            citySel.innerHTML = '<option value="">Loading...</option>';
            citySel.disabled = true;
            if (!this.value) {
                citySel.innerHTML = '<option value="">Select City</option>';
                citySel.disabled = false;
                return;
            }
            try {
                const res = await fetch(`/get-cities/${this.value}`);
                const cities = await res.json();
                citySel.innerHTML = '<option value="">Select City</option>';
                // Preserve existing selection if any
                const current = citySel.dataset.current || '';
                cities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id; opt.textContent = c.name;
                    if (c.id == current) opt.selected = true;
                    citySel.appendChild(opt);
                });
            } catch(e) { console.error('City load error:', e); }
            citySel.disabled = false;
        });
    });

    // === 🔍 Filter: State/City Dynamic ===
    const filterState = document.getElementById('filter_state_id');
    const filterCity = document.getElementById('filter_city_id');
    if (filterState && filterCity) {
        filterState.addEventListener('change', async function() {
            filterCity.innerHTML = '<option value="">Loading...</option>';
            if (!this.value) {
                filterCity.innerHTML = '<option value="">All Cities</option>';
                return;
            }
            try {
                const res = await fetch(`/get-cities/${this.value}`);
                const cities = await res.json();
                filterCity.innerHTML = '<option value="">All Cities</option>';
                cities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id; opt.textContent = c.name;
                    filterCity.appendChild(opt);
                });
            } catch(e) { console.error('Filter city error:', e); }
        });
    }

    // === 🎨 Close Filter Dropdown on Apply ===
    const applyFilter = document.querySelector('#filter-form button[type="submit"]');
    if (applyFilter) {
        applyFilter.addEventListener('click', function() {
            const dropdown = document.querySelector('.filter-dropdown-menu');
            if (dropdown) {
                const bs = bootstrap.Dropdown.getInstance(dropdown.closest('.dropdown'));
                bs?.hide();
            }
        });
    }

});
</script>

<style>
    .is-invalid { border-color: #dc3545 !important; }
    .text-danger.small.d-block { display: block; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem; }
    .invalid-feedback { display: block; width: 100%; font-size: 0.875em; color: #dc3545; margin-top: 0.25rem; }
</style>
@endpush