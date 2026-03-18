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
                    <h4 class="mb-1">Colleges<span class="badge badge-soft-primary ms-2">{{ $colleges->total() }}</span>
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
                                    <a href="javascript:void(0);" class="dropdown-item"><i
                                            class="ti ti-file-type-pdf me-1"></i>Export as
                                        PDF</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i
                                            class="ti ti-file-type-xls me-1"></i>Export as
                                        Excel </a>
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
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"><i class="ti ti-filter me-2"></i>Filter<i
                                class="ti ti-chevron-down ms-2"></i></a>
                        <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                            <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filter</h6>
                                <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown-menu"
                                    aria-label="Close"></button>
                            </div>
                            <div class="filter-set-view p-3">
                                <div class="accordion" id="accordionExample">
                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                aria-expanded="true" aria-controls="collapseTwo">Owner</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse show" id="collapseTwo"
                                            data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <div class="mb-2">
                                                    <div class="input-icon-start input-icon position-relative">
                                                        <span class="input-icon-addon fs-12">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control form-control-md"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                                <ul class="mb-0">
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2">
                                                                <img src="assets/img/users/user-06.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Elizabeth Morgan
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2">
                                                                <img src="assets/img/users/user-40.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Katherine Brooks
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2">
                                                                <img src="assets/img/users/user-05.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Sophia Lopez
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-10.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>John Michael
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-15.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Natalie Brooks
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-01.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>William Turner
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-13.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Ava Martinez
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-12.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Nathan Reed
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-03.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Lily Anderson
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-18.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Ryan Coleman
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);"
                                                            class="link-primary text-decoration-underline p-2 d-flex">Load
                                                            More</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapseThree" aria-expanded="false"
                                                aria-controls="collapseThree">Tags</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="collapseThree"
                                            data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <ul>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Collab
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Promotion
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            VIP
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapseFive" aria-expanded="false"
                                                aria-controls="collapseFive">Location</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="collapseFive"
                                            data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <div class="mb-1">
                                                    <div class="input-icon-start input-icon position-relative">
                                                        <span class="input-icon-addon fs-12">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control form-control-md"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                                <ul class="mb-0">
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xss rounded-circle me-1"><img
                                                                    src="assets/img/flags/us.svg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>USA
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xss rounded-circle me-1"><img
                                                                    src="assets/img/flags/ae.svg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>UAE
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xss rounded-circle me-1"><img
                                                                    src="assets/img/flags/de.svg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Germany
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xss rounded-circle me-1"><img
                                                                    src="assets/img/flags/fr.svg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>France
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-expanded="false"
                                                aria-controls="collapseOne">Rating</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="collapseOne"
                                            data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <ul>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="rating">
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <span class="ms-1">5.0</span>
                                                            </span>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="rating">
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <span class="ms-1">4.0</span>
                                                            </span>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="rating">
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <span class="ms-1">3.0</span>
                                                            </span>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="rating">
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <span class="ms-1">2.0</span>
                                                            </span>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="rating">
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <i class="ti ti-star-filled"></i>
                                                                <span class="ms-1">1.0</span>
                                                            </span>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#Status" aria-expanded="false"
                                                aria-controls="Status">Status</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="Status"
                                            data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <ul>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Active
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Inactive
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="javascript:void(0);" class="btn btn-outline-light w-100">Reset</a>
                                    <a href="Colleges-list.html" class="btn btn-primary w-100">Filter</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="d-flex align-items-center shadow p-1 rounded border view-icons bg-white">
                        <a href="Colleges-list.html" class="btn btn-sm p-1 border-0 fs-14"><i
                                class="ti ti-list-tree"></i></a>
                        <a href="Colleges.html" class="flex-shrink-0 btn btn-sm p-1 border-0 ms-1 fs-14 active"><i
                                class="ti ti-grid-dots"></i></a>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add
                        College</a>
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
                                            <a class="dropdown-item edit-college" href="javascript:void(0)"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#offcanvas_edit_{{ $college->id }}">
                                                <i class="ti ti-edit text-blue me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item delete-college" href="#" data-bs-toggle="modal"
                                                data-bs-target="#delete_contact{{ $college->id }}">
                                                <i class="ti ti-trash text-danger me-1"></i> Delete
                                            </a>
                                            <a class="dropdown-item" href="{{ route('colleges.index', $college->id) }}">
                                                <i class="ti ti-eye text-blue-light me-1"></i> Preview
                                            </a>
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

                                <!-- Footer: Social + Avatar -->
                                <div
                                    class="d-flex justify-content-between align-items-center flex-wrap row-gap-2 border-top pt-3 mt-3">
                                    <div class="d-flex align-items-center grid-social-links">
                                        @if ($college->email)
                                            <a href="mailto:{{ $college->email }}"
                                                class="avatar avatar-xs text-dark rounded-circle me-1" title="Email">
                                                <i class="ti ti-mail fs-14"></i>
                                            </a>
                                        @endif
                                        @if ($college->phone)
                                            <a href="tel:{{ $college->phone }}"
                                                class="avatar avatar-xs text-dark rounded-circle me-1" title="Call">
                                                <i class="ti ti-phone-check fs-14"></i>
                                            </a>
                                        @endif
                                        @if ($college->website)
                                            <a href="{{ $college->website }}" target="_blank"
                                                class="avatar avatar-xs text-dark rounded-circle me-1" title="Website">
                                                <i class="ti ti-world fs-14"></i>
                                            </a>
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
                            <form action="{{ route('colleges.update', $college->id) }}" method="POST"
                                enctype="multipart/form-data">
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

            <!-- ✅ Custom Load More Pagination -->
            {{ $colleges->links('vendor.pagination.load-more') }}

        </div>
        <!-- End Content -->


    </div>

    <!-- ========================
                           End Page Content
                          ========================= -->

    </div>
    <!-- End Wrapper -->
    <!-- Add/Edit Offcanvas -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="mb-0" id="offcanvas_title">Add New College</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            {{-- Form Action --}}
            <form action="{{ route('colleges.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Method spoofing for update handled in JS --}}
                <input type="hidden" name="_method" id="method_field" value="POST">
                <input type="hidden" name="id" id="college_id">

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
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="phone" id="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Website</label>
                                            <input type="url" class="form-control" name="website" id="website">
                                        </div>
                                    </div>


                                    <!-- NEW: Select Courses (Multi-Select from DB) -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Select Courses <span
                                                    class="text-danger">*</span></label>
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
                                            <div class="invalid-feedback">Please select at least one course.</div>
                                        </div>
                                    </div>
                                    <!-- /NEW -->

                                    <!-- Deadline & Fees -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Application Deadline</label>
                                            <input type="date" class="form-control" name="application_deadline"
                                                id="application_deadline">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Fees Range</label>
                                            <input type="text" class="form-control" name="fees_range" id="fees_range"
                                                placeholder="e.g. 50k - 1L">
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Status</label>
                                            </div>
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
                    <!-- /Basic Info -->

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
                                                {{-- Populated via AJAX --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Address Info -->
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




            // 3. Image Preview Logic
            const imageInput = document.getElementById('college_image');
            const imagePreview = document.getElementById('image_preview');
            const imageIcon = document.getElementById('image_icon');

            if (imageInput) {
                imageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                            imageIcon.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // 4. Dynamic State/City Dropdown
            const stateSelect = document.getElementById('state_id');
            const citySelect = document.getElementById('city_id');

            if (stateSelect) {
                stateSelect.addEventListener('change', function() {
                    const stateId = this.value;
                    citySelect.innerHTML = '<option value="">Loading...</option>';

                    if (stateId) {
                        fetch(`/get-cities/${stateId}`)
                            .then(response => response.json())
                            .then(data => {
                                citySelect.innerHTML = '<option value="">Select City</option>';
                                data.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city.id;
                                    option.text = city.name;
                                    citySelect.appendChild(option);
                                });
                            });
                    } else {
                        citySelect.innerHTML = '<option value="">Select City</option>';
                    }
                });
            }


        });
    </script>
@endpush
