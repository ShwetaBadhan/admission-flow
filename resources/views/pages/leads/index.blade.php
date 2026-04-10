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
                        <div class="dropdown-menu dropdown-menu-end">
                            <ul>
                                <li>
                                    <a href="{{ route('leads.export.pdf', request()->query()) }}" class="dropdown-item"><i
                                            class="ti ti-file-type-pdf me-1"></i>Export as
                                        PDF</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('leads.export.excel', request()->query()) }}"
                                        class="dropdown-item"><i class="ti ti-file-type-xls me-1"></i>Export as
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
                    <!-- Filter Form Wrapper -->
                    <form id="leads-filter-form" action="{{ route('leads.index') }}" method="GET">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside"><i
                                    class="ti ti-filter me-2"></i>Filter<i class="ti ti-chevron-down ms-2"></i></a>
                            <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                                <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                    <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filter</h6>
                                    <button type="button" class="btn-close close-filter-btn"
                                        data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                                </div>
                                <div class="filter-set-view p-3">
                                    <div class="accordion" id="accordionExample">

                                        <!-- Lead Name Filter -->
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                    aria-expanded="true" aria-controls="collapseTwo">Lead Name</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse show"
                                                id="collapseTwo" data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <div class="mb-2">
                                                        <div class="input-icon-start input-icon position-relative">
                                                            <span class="input-icon-addon fs-12"><i
                                                                    class="ti ti-search"></i></span>
                                                            <input type="text" class="form-control form-control-md"
                                                                placeholder="Search" name="lead_name"
                                                                value="{{ request('lead_name') }}">
                                                        </div>
                                                    </div>
                                                    <ul class="mb-0">
                                                        @foreach ($leads as $lead)
                                                            <li class="mb-1">
                                                                <label class="dropdown-item px-2 d-flex align-items-center">
                                                                    <input
                                                                        class="form-check-input m-0 me-1 lead-name-checkbox"
                                                                        type="checkbox" name="lead_ids[]"
                                                                        value="{{ $lead->id }}"
                                                                        {{ in_array($lead->id, request('lead_ids', [])) ? 'checked' : '' }}>
                                                                    <span class="avatar avatar-xs rounded-circle me-2">
                                                                        <img src="{{ asset('assets/img/users/user-06.jpg') }}"
                                                                            class="flex-shrink-0 rounded-circle"
                                                                            alt="img">
                                                                    </span>
                                                                    {{ $lead->full_name }}
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Lead Status Filter -->
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#status" aria-expanded="false"
                                                    aria-controls="status">Lead Status</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="status"
                                                data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    @foreach ($statuses as $key => $status)
                                                        <div class="mb-1">
                                                            <label class="dropdown-item px-2 d-flex align-items-center">
                                                                <input class="form-check-input m-0 me-1" type="checkbox"
                                                                    name="statuses[]" value="{{ $key }}"
                                                                    {{ in_array($key, request('statuses', [])) ? 'checked' : '' }}>
                                                                {{ $status['label'] }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Created Date Filter -->
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
                                                        <input type="text" class="form-control" name="created_date"
                                                            value="{{ request('created_date') }}"
                                                            data-provider="flatpickr" data-date-format="d M, Y">
                                                        <span class="input-group-text"><i
                                                                class="ti ti-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Lead Owner (Consultant) Filter -->
                                        <div class="filter-set-content">
                                            <div class="filter-set-content-head">
                                                <a href="#" class="collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#owner" aria-expanded="false"
                                                    aria-controls="owner">Lead Owner</a>
                                            </div>
                                            <div class="filter-set-contents accordion-collapse collapse" id="owner"
                                                data-bs-parent="#accordionExample">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <div class="mb-2">
                                                        <div class="input-icon-start input-icon position-relative">
                                                            <span class="input-icon-addon fs-12"><i
                                                                    class="ti ti-search"></i></span>
                                                            <input type="text" class="form-control form-control-md"
                                                                placeholder="Search" id="consultant-search">
                                                        </div>
                                                    </div>
                                                    <ul class="mb-0" id="consultant-list">
                                                        @foreach ($consultants as $consultant)
                                                            <li class="mb-1 consultant-item">
                                                                <label
                                                                    class="dropdown-item px-2 d-flex align-items-center">
                                                                    <input class="form-check-input m-0 me-1"
                                                                        type="checkbox" name="consultant_ids[]"
                                                                        value="{{ $consultant->id }}"
                                                                        {{ in_array($consultant->id, request('consultant_ids', [])) ? 'checked' : '' }}>
                                                                    <span class="avatar avatar-xs rounded-circle me-2">
                                                                        <img src="{{ asset('assets/img/users/user-17.jpg') }}"
                                                                            class="flex-shrink-0 rounded-circle"
                                                                            alt="img">
                                                                    </span>
                                                                    {{ $consultant->name }}
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Filter Actions -->
                                    <div class="d-flex align-items-center gap-2 mt-3">
                                        <button type="button" class="btn btn-outline-light w-100"
                                            id="reset-filters">Reset</button>
                                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Global Search Input (outside form, handled via JS) -->
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search leads..." id="global-search"
                            value="{{ request('search') }}">
                    </div>
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">

                    @can('create-leads')
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add Lead</a>

                        <!-- ✅ Bulk Import Button -->
                        <a href="{{ route('leads.bulk.import') }}" class="btn btn-outline-primary">
                            <i class="ti ti-file-upload me-1"></i>Bulk Import
                        </a>
                    @endcan
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
                                        @foreach ($statusLeads as $lead)
                                            <div class="col-md-4 mb-3">
                                                <div class="card kanban-card border mb-0 mt-3 shadow">
                                                    <div class="card-body">

                                                        <!-- Card Header -->
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <div class="d-flex align-items-center mb-3">
                                                                    @can('view-lead-details')
                                                                        <a href="{{ route('leads.index', $lead) }}"
                                                                            class="avatar rounded-circle bg-soft-{{ $statusInfo['color'] }} flex-shrink-0 me-2">
                                                                            <span
                                                                                class="avatar-title text-{{ $statusInfo['color'] }}">
                                                                                {{ strtoupper(substr($lead->full_name, 0, 2)) }}
                                                                            </span>
                                                                        </a>
                                                                    @endcan

                                                                    <h6 class="fw-medium fs-14 mb-0">
                                                                        <a href="{{ route('lead-details', $lead->id) }}">
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

                                                                        @can('edit-leads')
                                                                            <!-- Edit Button - Opens offcanvas with lead ID -->
                                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                                data-bs-toggle="offcanvas"
                                                                                data-bs-target="#offcanvas_edit_{{ $lead->id }}">
                                                                                <i
                                                                                    class="ti ti-square-rounded-plus-filled me-1"></i>
                                                                                Edit
                                                                            </a>
                                                                        @endcan

                                                                        @can('delete-leads')
                                                                            <!-- Delete Button -->
                                                                            <a class="dropdown-item delete-btn" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#delete_lead{{ $lead->id }}">
                                                                                <i class="ti ti-trash"></i> Delete
                                                                            </a>
                                                                        @endcan

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
                                                            <!-- Lead Source (Edit) - with data-name attribute -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Lead Source</label>
                                                                    <select name="lead_source_id"
                                                                        id="lead_source_edit_{{ $lead->id }}"
                                                                        class="select">
                                                                        <option value="">Select</option>
                                                                        @foreach ($leadsources as $source)
                                                                            <option value="{{ $source->id }}"
                                                                                data-name="{{ strtolower($source->name) }}"
                                                                                {{ old('lead_source_id', $lead->lead_source_id) == $source->id ? 'selected' : '' }}>
                                                                                {{ $source->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Consultant Field (Conditional - Edit) -->
                                                            <div class="col-md-6"
                                                                id="consultant_field_edit_{{ $lead->id }}"
                                                                style="display: {{ $lead->leadSource && stripos($lead->leadSource->name, 'consultant') !== false ? 'block' : 'none' }};">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Assign to Consultant</label>
                                                                    <select name="consultant_id"
                                                                        id="consultant_select_edit_{{ $lead->id }}"
                                                                        class="select">
                                                                        <option value="">Select Consultant</option>
                                                                        @foreach ($consultants as $consultant)
                                                                            <option value="{{ $consultant->id }}"
                                                                                {{ old('consultant_id', $lead->consultant_id) == $consultant->id ? 'selected' : '' }}>
                                                                                {{ $consultant->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Priority -->
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Priority</label>
                                                                    <select name="priority_id" class="select">
                                                                        <option value="">Select Priority</option>
                                                                        @foreach ($priorities as $priority)
                                                                            @php
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

                    <!-- Lead Source -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lead Source</label>
                            <!-- In your Lead Source dropdown, add data attribute -->
                            <select name="lead_source_id" id="lead_source" class="select">
                                <option value="" disabled selected>Select Lead Source</option>
                                @foreach ($leadsources as $leadsource)
                                    <option value="{{ $leadsource->id }}"
                                        data-name="{{ strtolower($leadsource->name) }}"
                                        {{ old('lead_source_id') == $leadsource->id ? 'selected' : '' }}>
                                        {{ $leadsource->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- ✅ Consultant Dropdown (Shows only when Lead Source = Consultant) -->
                    <div class="col-md-6" id="consultant_field" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Assign to Consultant <span class="text-danger">*</span></label>
                            <select name="consultant_id" id="consultant_select" class="select">
                                <option value="">Select Consultant</option>
                                @foreach ($consultants as $consultant)
                                    <option value="{{ $consultant->id }}"
                                        {{ old('consultant_id') == $consultant->id ? 'selected' : '' }}>
                                        {{ $consultant->name }} ({{ $consultant->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('consultant_id')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
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

            console.log('🚀 Page loaded, initializing consultant toggle...');

            // === Helper: Check if element is Select2-enhanced ===
            function isSelect2(element) {
                return $(element).data('select2') !== undefined;
            }

            // === Helper: Get selected option's data-name or text ===
            function getSourceName(selectEl) {
                const selected = selectEl.options[selectEl.selectedIndex];
                return selected.getAttribute('data-name') || selected.text.toLowerCase();
            }

            // === 1. Toggle Consultant Field (Add Form) ===
            const leadSourceSelect = document.getElementById('lead_source');
            const consultantField = document.getElementById('consultant_field');
            const consultantSelect = document.getElementById('consultant_select');

            function toggleConsultantField() {
                if (!leadSourceSelect || !consultantField || !consultantSelect) {
                    console.warn('Missing elements for consultant toggle');
                    return;
                }

                const sourceName = getSourceName(leadSourceSelect);
                console.log('📋 Lead source selected:', sourceName);

                if (sourceName.includes('consultant')) {
                    console.log('✅ Showing consultant field');
                    consultantField.style.display = 'block';
                    consultantSelect.setAttribute('required', 'required');
                } else {
                    console.log('❌ Hiding consultant field');
                    consultantField.style.display = 'none';
                    consultantSelect.removeAttribute('required');
                    consultantSelect.value = '';
                }
            }

            if (leadSourceSelect) {
                // Initial check
                toggleConsultantField();

                // Handle Select2 or native change
                if (typeof jQuery !== 'undefined' && $(leadSourceSelect).hasClass('select2-hidden-accessible')) {
                    console.log('🔄 Using Select2 event listener');
                    $(leadSourceSelect).on('change.select2', function() {
                        toggleConsultantField();
                    });
                } else {
                    console.log('🔄 Using native change listener');
                    leadSourceSelect.addEventListener('change', toggleConsultantField);
                }
            }

            // === 2. Toggle Consultant Field for Edit Forms ===
            document.querySelectorAll('[id^="lead_source_edit_"]').forEach(function(sourceSelect) {
                const consultantFieldEdit = sourceSelect.closest('.row')?.querySelector(
                    '[id^="consultant_field_edit_"]');
                const consultantSelectEdit = sourceSelect.closest('.row')?.querySelector(
                    '[id^="consultant_select_edit_"]');

                if (consultantFieldEdit && consultantSelectEdit) {
                    // Initial check
                    toggleConsultantFieldForEdit(sourceSelect, consultantFieldEdit, consultantSelectEdit);

                    // Handle Select2 or native
                    if (typeof jQuery !== 'undefined' && $(sourceSelect).hasClass(
                            'select2-hidden-accessible')) {
                        $(sourceSelect).on('change.select2', function() {
                            toggleConsultantFieldForEdit(this, consultantFieldEdit,
                                consultantSelectEdit);
                        });
                    } else {
                        sourceSelect.addEventListener('change', function() {
                            toggleConsultantFieldForEdit(this, consultantFieldEdit,
                                consultantSelectEdit);
                        });
                    }
                }
            });

            function toggleConsultantFieldForEdit(sourceSelect, fieldEl, selectEl) {
                const sourceName = getSourceName(sourceSelect);

                if (sourceName.includes('consultant')) {
                    fieldEl.style.display = 'block';
                    selectEl.setAttribute('required', 'required');
                } else {
                    fieldEl.style.display = 'none';
                    selectEl.removeAttribute('required');
                    selectEl.value = '';
                }
            }

            // === 3. Load Cities via AJAX ===
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

            // === 4. Add Form: State/City Listener ===
            const addState = document.getElementById('add_state');
            const addCity = document.getElementById('add_city');
            if (addState && addCity) {
                if (typeof jQuery !== 'undefined' && $(addState).hasClass('select2-hidden-accessible')) {
                    $(addState).on('change.select2', function() {
                        loadCities(this.value, addCity);
                    });
                } else {
                    addState.addEventListener('change', function() {
                        loadCities(this.value, addCity);
                    });
                }
                if (addState.value) {
                    loadCities(addState.value, addCity);
                }
            }

            // === 5. Edit Forms: State/City Listeners ===
            document.querySelectorAll('select[name="state_id"]').forEach(function(stateSelect) {
                if (stateSelect.id === 'add_state') return;

                const citySelect = stateSelect.closest('.row')?.querySelector('select[name="city_id"]');
                if (citySelect) {
                    if (typeof jQuery !== 'undefined' && $(stateSelect).hasClass(
                            'select2-hidden-accessible')) {
                        $(stateSelect).on('change.select2', function() {
                            loadCities(this.value, citySelect);
                        });
                    } else {
                        stateSelect.addEventListener('change', function() {
                            loadCities(this.value, citySelect);
                        });
                    }
                    if (stateSelect.value) {
                        loadCities(stateSelect.value, citySelect, citySelect.value);
                    }
                }
            });

        });
        // === Global Search with Debounce ===
        const globalSearch = document.getElementById('global-search');
        let searchTimeout;

        if (globalSearch) {
            globalSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const url = new URL(window.location);
                    if (this.value.trim()) {
                        url.searchParams.set('search', this.value.trim());
                    } else {
                        url.searchParams.delete('search');
                    }
                    // Remove page param to reset pagination
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                }, 500);
            });
        }

        // === Reset Filters ===
        const resetBtn = document.getElementById('reset-filters');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                // Clear all filter inputs
                document.getElementById('leads-filter-form')?.reset();
                // Redirect to clean URL
                window.location.href = "{{ route('leads.index') }}";
            });
        }

        // === Consultant Search within Filter ===
        const consultantSearch = document.getElementById('consultant-search');
        const consultantItems = document.querySelectorAll('.consultant-item');

        if (consultantSearch && consultantItems.length > 0) {
            consultantSearch.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                consultantItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(term) ? 'block' : 'none';
                });
            });
        }

        // === Lead Name Search within Filter ===
        const leadNameSearch = document.querySelector('#collapseTwo input[type="text"]');
        const leadNameItems = document.querySelectorAll('#collapseTwo ul li');

        if (leadNameSearch && leadNameItems.length > 0) {
            leadNameSearch.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                leadNameItems.forEach(item => {
                    if (item.querySelector('a')) return; // Skip "Load More"
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(term) ? 'block' : 'none';
                });
            });
        }

        // === Preserve Scroll Position on Filter Submit ===
        const filterForm = document.getElementById('leads-filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', function() {
                sessionStorage.setItem('leadsScrollPos', window.scrollY);
            });
        }

        // Restore scroll position on page load
        const scrollPos = sessionStorage.getItem('leadsScrollPos');
        if (scrollPos) {
            window.scrollTo(0, parseInt(scrollPos));
            sessionStorage.removeItem('leadsScrollPos');
        }
    </script>
@endpush
