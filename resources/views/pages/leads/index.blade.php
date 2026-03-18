@extends('layout.master')
<!-- Session Messages -->
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
                    <h4 class="mb-1">Leads<span class="badge badge-soft-primary ms-2"></span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Leads</li>
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
                                                aria-expanded="true" aria-controls="collapseTwo">Lead Name</a>
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
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-06.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Elizabeth Morgan
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-40.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Katherine Brooks
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-05.jpg"
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
                                                aria-controls="collapseThree">Company Name</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="collapseThree"
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
                                                <ul>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            NovaWave LLC
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            BlueSky Industries
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Silver Hawk
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Summit Peak
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#status" aria-expanded="false"
                                                aria-controls="status">Lead Status</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="status"
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
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Closed
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Not Closed
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Contacted
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            Lost
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#date2" aria-expanded="false"
                                                aria-controls="date2">Created Date</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="date2"
                                            data-bs-parent="#accordionExample">
                                            <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                <div class="input-group w-auto input-group-flat">
                                                    <input type="text" class="form-control" data-provider="flatpickr"
                                                        data-date-format="d M, Y">
                                                    <span class="input-group-text">
                                                        <i class="ti ti-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="filter-set-content">
                                        <div class="filter-set-content-head">
                                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#owner" aria-expanded="false" aria-controls="owner">Lead
                                                Owner</a>
                                        </div>
                                        <div class="filter-set-contents accordion-collapse collapse" id="owner"
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
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-17.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Robert Johnson
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-16.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Isabella Cooper
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-14.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>John Smith
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-22.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Sophia Parker
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-25.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Emma Reynolds
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-24.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Liam Carter
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-39.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Noah Mitchell
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-31.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Mason Hayes
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-21.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Ron Thompson
                                                        </label>
                                                    </li>
                                                    <li class="mb-1">
                                                        <label class="dropdown-item px-2 d-flex align-items-center">
                                                            <input class="form-check-input m-0 me-1" type="checkbox">
                                                            <span class="avatar avatar-xs rounded-circle me-2"><img
                                                                    src="assets/img/users/user-10.jpg"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Laura Bennett
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="javascript:void(0);" class="btn btn-outline-light w-100">Reset</a>
                                    <a href="javascript:void(0);" class="btn btn-primary w-100">Filter</a>
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
                        <a href="leads-list.html" class="btn btn-sm p-1 border-0 fs-14"><i
                                class="ti ti-list-tree"></i></a>
                        <a href="leads.html" class="flex-shrink-0 btn btn-sm p-1 border-0 ms-1 fs-14 active"><i
                                class="ti ti-grid-dots"></i></a>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add Lead</a>
                </div>
            </div>
            <!-- table header -->

            <!-- Leads Kanban -->
            <div class="">
                <div class="kanban-list-items p-2 rounded border">

                    @foreach ($statuses as $statusKey => $statusInfo)
                        @php
                            $statusLeads = $leadsByStatus[$statusKey] ?? collect();
                        @endphp

                        <!-- Status Header -->
                        <div class="card mb-3 border-0 shadow">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="d-flex align-items-center mb-1">
                                            <i class="ti ti-circle-filled fs-10 text-{{ $statusInfo['color'] }} me-1"></i>
                                            {{ $statusInfo['label'] }}
                                        </h6>
                                        <span class="fw-medium">{{ $statusLeads->count() }} Leads</span>
                                    </div>

                                </div>
                            </div>

                            <!-- Lead Cards -->
                            <div class="kanban-drag-wrap">
                                <div class="container">
                                    <div class="row">
                                        @foreach ($statusLeads->take(3) as $lead)
                                            <div class="col-md-4 mb-3">
                                                <div class="card kanban-card border mb-0 mt-3 shadow">
                                                    <div class="card-body">

                                                        <!-- Card Header -->
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <a href="{{ route('leads.index', $lead) }}"
                                                                        class="avatar rounded-circle bg-soft-{{ $statusInfo['color'] }} flex-shrink-0 me-2">
                                                                        <span
                                                                            class="avatar-title text-{{ $statusInfo['color'] }}">
                                                                            {{ strtoupper(substr($lead->full_name, 0, 2)) }}
                                                                        </span>
                                                                    </a>
                                                                    <h6 class="fw-medium fs-14 mb-0">
                                                                        <a href="{{ route('lead-details',$lead->id) }}">
                                                                            {{ $lead->full_name }}
                                                                        </a>
                                                                    </h6>
                                                                </div>
                                                            </div>

                                                            <!-- Card Actions -->
                                                            <div class="d-flex align-items-center">
                                                                <div class="dropdown table-action">
                                                                    <a href="#"
                                                                        class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                                        data-bs-toggle="dropdown">
                                                                        <i class="ti ti-dots-vertical"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <!-- Edit Button - Opens offcanvas with lead ID -->
                                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                                            data-bs-toggle="offcanvas"
                                                                            data-bs-target="#offcanvas_edit_{{ $lead->id }}">
                                                                            <i
                                                                                class="ti ti-square-rounded-plus-filled me-1"></i>
                                                                            Edit
                                                                        </a>
                                                                        <!-- Delete Button -->
                                                                        <a class="dropdown-item delete-btn" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#delete_lead{{ $lead->id }}">
                                                                            <i class="ti ti-trash"></i> Delete
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Lead Details -->
                                                        <div class="d-flex flex-column">
                                                            <p class="text-default d-inline-flex align-items-center mb-2">
                                                                <i class="ti ti-mail text-dark me-1"></i>
                                                                <a href="mailto:{{ $lead->email }}" class="text-reset">
                                                                    {{ $lead->email ?? 'N/A' }}
                                                                </a>
                                                            </p>
                                                            <p class="text-default d-inline-flex align-items-center mb-2">
                                                                <i class="ti ti-phone text-dark me-1"></i>
                                                                {{ $lead->mobile ?? 'N/A' }}
                                                            </p>
                                                            <p class="text-default d-inline-flex align-items-center">
                                                                <i class="ti ti-map-pin-pin text-dark me-1"></i>
                                                                {{ $lead->city?->name }}{{ $lead->city && $lead->state ? ', ' : '' }}{{ $lead->state?->name }}
                                                            </p>
                                                        </div>

                                                        <!-- Card Footer -->
                                                        <div
                                                            class="d-flex align-items-center justify-content-between border-top pt-3">
                                                            <span
                                                                class="avatar avatar-xs border rounded-circle d-flex align-items-center justify-content-center p-1">
                                                                @if ($lead->course)
                                                                    <img src="{{ asset('assets/img/icons/company-icon-01.svg') }}"
                                                                        alt="Img">
                                                                @else
                                                                    <i class="ti ti-school text-muted"></i>
                                                                @endif
                                                            </span>
                                                            <div class="icons-social d-flex align-items-center gap-1">
                                                                <a href="tel:{{ $lead->mobile }}"
                                                                    class="d-flex align-items-center justify-content-center me-1">
                                                                    <i class="ti ti-phone-check"></i>
                                                                </a>
                                                                <a href="mailto:{{ $lead->email }}"
                                                                    class="d-flex align-items-center justify-content-center me-1">
                                                                    <i class="ti ti-message-circle-2"></i>
                                                                </a>
                                                                <a href="{{ route('leads.update', $lead) }}"
                                                                    class="d-flex align-items-center justify-content-center">
                                                                    <i class="ti ti-color-swatch"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ✅ Edit Offcanvas - Unique ID per lead -->
                                            <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1"
                                                id="offcanvas_edit_{{ $lead->id }}">
                                                <div class="offcanvas-header border-bottom">
                                                    <h5 class="mb-0">Edit Lead</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="offcanvas"></button>
                                                </div>
                                                <div class="offcanvas-body">
                                                    <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="row">
                                                            <!-- Full Name -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Lead Name<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="full_name"
                                                                        class="form-control"
                                                                        value="{{ old('full_name', $lead->full_name) }}"
                                                                        required>
                                                                </div>
                                                            </div>

                                                            <!-- Mobile & Email -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Phone<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="mobile"
                                                                        class="form-control"
                                                                        value="{{ old('mobile', $lead->mobile) }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Email</label>
                                                                    <input type="email" name="email"
                                                                        class="form-control"
                                                                        value="{{ old('email', $lead->email) }}">
                                                                </div>
                                                            </div>

                                                            <!-- State & City -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">State</label>
                                                                    <select name="state_id" class="form-select">
                                                                        <option value="">Select State</option>
                                                                        @foreach ($states as $state)
                                                                            <option value="{{ $state->id }}"
                                                                                {{ old('state_id', $lead->state_id) == $state->id ? 'selected' : '' }}>
                                                                                {{ $state->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">City</label>
                                                                    <select name="city_id" class="form-select">
                                                                        <option value="">Select City</option>
                                                                        @foreach ($cities as $city)
                                                                            <option value="{{ $city->id }}"
                                                                                {{ old('city_id', $lead->city_id) == $city->id ? 'selected' : '' }}>
                                                                                {{ $city->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Qualification -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Qualification</label>
                                                                    <select name="qualification_id" class="select">
                                                                        <option value="">Select</option>
                                                                        @foreach ($qualifications as $qual)
                                                                            <option value="{{ $qual->id }}"
                                                                                {{ old('qualification_id', $lead->qualification_id) == $qual->id ? 'selected' : '' }}>
                                                                                {{ $qual->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Course -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Course</label>
                                                                    <select name="interested_course_id" class="select">
                                                                        <option value="">Select Course</option>
                                                                        @foreach ($courses as $course)
                                                                            <option value="{{ $course->id }}"
                                                                                {{ old('interested_course_id', $lead->interested_course_id) == $course->id ? 'selected' : '' }}>
                                                                                {{ $course->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Intake -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Preferred Intake</label>
                                                                    <select name="preferred_intake_id" class="select">
                                                                        <option value="">Select</option>
                                                                        @foreach ($intakes as $intake)
                                                                            <option value="{{ $intake->id }}"
                                                                                {{ old('preferred_intake_id', $lead->preferred_intake_id) == $intake->id ? 'selected' : '' }}>
                                                                                {{ $intake->name ?? $intake->year }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Lead Source -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Lead Source</label>
                                                                    <select name="lead_source_id" class="select">
                                                                        <option value="">Select</option>
                                                                        @foreach ($leadsources as $source)
                                                                            <option value="{{ $source->id }}"
                                                                                {{ old('lead_source_id', $lead->lead_source_id) == $source->id ? 'selected' : '' }}>
                                                                                {{ $source->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Priority -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Priority</label>
                                                                    <!-- Priority in Edit Form - Correct Pre-fill -->
                                                                    <select name="priority_id" class="select">
                                                                        <option value="">Select Priority</option>
                                                                        @foreach ($priorities as $priority)
                                                                            @php
                                                                                // Get the current priority ID from lead or old input
                                                                                $currentPriorityId = old(
                                                                                    'priority_id',
                                                                                    $lead->priority_id,
                                                                                );
                                                                            @endphp
                                                                            <option value="{{ $priority->id }}"
                                                                                {{ $currentPriorityId == $priority->id ? 'selected' : '' }}>
                                                                                {{ $priority->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Notes -->
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Description</label>
                                                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $lead->notes) }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Buttons -->
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <button type="button" class="btn btn-light me-2"
                                                                data-bs-dismiss="offcanvas">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- ✅ /Edit Offcanvas -->
                                            <!-- delete modal -->
                                            <div class="modal fade" id="delete_lead{{ $lead->id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
                                                    <div class="modal-content rounded-0">
                                                        <div class="modal-body p-4 text-center position-relative">
                                                            <form action="{{ route('leads.destroy', $lead->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="mb-3 position-relative z-1">
                                                                    <span
                                                                        class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i
                                                                            class="ti ti-trash fs-24"></i></span>
                                                                </div>
                                                                <h5 class="mb-1">Delete Confirmation</h5>
                                                                <p class="mb-3">Are you sure you want to remove leads you
                                                                    selected.</p>
                                                                <div class="d-flex justify-content-center">
                                                                    <a href="#"
                                                                        class="btn btn-light position-relative z-1 me-2 w-100"
                                                                        data-bs-dismiss="modal">Cancel</a>
                                                                    <button type="submit"
                                                                        class="btn btn-primary position-relative z-1 w-100"
                                                                        data-bs-dismiss="modal">Yes, Delete</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- delete modal -->
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <!-- /Leads Kanban -->
        </div>
        <!-- End Content -->



    </div>

    <!-- ========================
           End Page Content
          ========================= -->


    <!-- Add lead-->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="mb-0">Add New Lead</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('leads.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Lead Name<span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}"
                                required>
                            @error('full_name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}"
                                required>
                            @error('mobile')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">State</label>
                            <select name="state_id" id="add_state" class="form-select">
                                <option value="" disabled selected>Select State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <select name="city_id" id="add_city" class="form-select">
                                <option value="" disabled selected>Select City</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Qualification</label>
                            <select name="qualification_id" class="select">
                                <option value="" disabled selected>Select Qualification</option>
                                @foreach ($qualifications as $qualification)
                                    <option value="{{ $qualification->id }}"
                                        {{ old('qualification_id') == $qualification->id ? 'selected' : '' }}>
                                        {{ $qualification->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Course</label>
                            <select name="interested_course_id" class="select">
                                <option value="" disabled selected>Select Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('interested_course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Preferred Intake</label>
                            <select name="preferred_intake_id" class="select">
                                <option value="" disabled selected>Select Intake</option>
                                @foreach ($intakes as $intake)
                                    <option value="{{ $intake->id }}"
                                        {{ old('preferred_intake_id') == $intake->id ? 'selected' : '' }}>
                                        {{ $intake->name ?? $intake->year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lead Source</label>
                            <select name="lead_source_id" class="select">
                                <option value="" disabled selected>Select Lead Source</option>
                                @foreach ($leadsources as $leadsource)
                                    <option value="{{ $leadsource->id }}"
                                        {{ old('lead_source_id') == $leadsource->id ? 'selected' : '' }}>
                                        {{ $leadsource->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority_id" class="select">
                                <option value="" disabled selected>Select Priority</option>
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority->id }}"
                                        {{ old('priority_id') == $priority->id ? 'selected' : '' }}>
                                        {{ $priority->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Description">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <button type="button" data-bs-dismiss="offcanvas" class="btn btn-light me-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create New</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add lead -->
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Helper: Load Cities via AJAX ---
            function loadCities(stateId, citySelect, selectedCityId = null) {
                citySelect.innerHTML = '<option value="">Select City</option>';

                if (!stateId) return;

                fetch(`/api/cities/${stateId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(cities => {
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            if (selectedCityId && city.id == selectedCityId) {
                                option.selected = true;
                            }
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading cities:', error));
            }

            // --- Add Modal: State Change ---
            const addState = document.getElementById('add_state');
            const addCity = document.getElementById('add_city');
            if (addState && addCity) {
                addState.addEventListener('change', function() {
                    loadCities(this.value, addCity);
                });
                if (addState.value) {
                    loadCities(addState.value, addCity);
                }
            }

            // --- Edit Modal: Populate with data from button attributes ---
            const editModal = document.getElementById('edit_consultant');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    // Set form action
                    const consultantId = button.getAttribute('data-id');
                    document.getElementById('editForm').action = `/consultants/${consultantId}`;

                    // Populate fields
                    document.getElementById('edit-name').value = button.getAttribute('data-name');
                    document.getElementById('edit-email').value = button.getAttribute('data-email');
                    document.getElementById('edit-phone').value = button.getAttribute('data-phone');
                    document.getElementById('edit-address').value = button.getAttribute('data-address');

                    // Populate state and trigger city load
                    const editState = document.getElementById('edit_state');
                    const editCity = document.getElementById('edit_city');
                    const stateId = button.getAttribute('data-state-id');
                    const cityId = button.getAttribute('data-city-id');

                    editState.value = stateId;
                    if (stateId) {
                        loadCities(stateId, editCity, cityId);
                    }

                    // Populate status
                    const status = button.getAttribute('data-status');
                    document.getElementById('edit-active').checked = (status == 1);
                    document.getElementById('edit-inactive').checked = (status == 0);
                });

                // Handle state change in edit modal
                const editStateEl = document.getElementById('edit_state');
                const editCityEl = document.getElementById('edit_city');
                if (editStateEl) {
                    editStateEl.addEventListener('change', function() {
                        loadCities(this.value, editCityEl);
                    });
                }
            }




        });
    </script>
@endpush
