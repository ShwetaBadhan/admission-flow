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
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Commission Rules<span
                            class="badge badge-soft-primary ms-2">{{ $commissionRules->total() }}</span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Commission Rules</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    
                    {{-- <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i
                            class="ti ti-refresh"></i></a> --}}
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse"
                        id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->





            <!-- card start -->
            <div class="card border-0 rounded-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    {{-- <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                    </div> --}}
                    @can('create-commission-rules')
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas_add"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add New
                            Commission Rules</a>
                    @endcan

                </div>
                <div class="card-body">

                  

                    <!-- Commission Rules List -->
                    <div class="table-responsive table-nowrap custom-table">
                        <table class="table table-nowrap datatable" id="Commission Rules-list">
                            <thead class="table-light">
                                <tr>
                                    <th class="no-sort">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th class="no-sort"></th>
                                    <th>Consultant</th>
                                    <th>College</th>
                                    <th>Course</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th class="text-end no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commissionRules as $rule)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-md">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>{{ $rule->consultant->name ?? 'N/A' }}</td>
                                        <td>{{ $rule->college->name ?? 'N/A' }}</td>
                                        <td>{{ $rule->course_name }}</td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $rule->commission_type)) }}</span>
                                        </td>
                                        <td>{{ $rule->commission_value }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $rule->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($rule->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown table-action">
                                                <a href="#"
                                                    class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @can('edit-commission-rules')
                                                        <a class="dropdown-item edit-college" href="javascript:void(0)"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvas_edit{{ $rule->id }}">
                                                            <i class="ti ti-edit text-blue me-1"></i> Edit
                                                        </a>
                                                    @endcan

                                                    @can('delete-commission-rules')
                                                        <a class="dropdown-item delete-btn" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#delete_rule{{ $rule->id }}">
                                                            <i class="ti ti-trash"></i> Delete
                                                        </a>
                                                    @endcan


                                                </div>
                                            </div>


                                        </td>
                                    </tr>

                                    <!-- Edit Commission Rule -->
                                    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1"
                                        id="offcanvas_edit{{ $rule->id }}">
                                        <div class="offcanvas-header border-bottom">
                                            <h5 class="mb-0">Edit Commission Rule</h5>
                                            <button type="button"
                                                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                                                data-bs-dismiss="offcanvas" aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <form action="{{ route('commission-rules.update', $rule->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Consultant <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="consultant_id" class="form-select select"
                                                                    required>
                                                                    <option value="">Choose Consultant</option>
                                                                    @foreach ($consultants as $consultant)
                                                                        <option value="{{ $consultant->id }}"
                                                                            {{ $rule->consultant_id == $consultant->id ? 'selected' : '' }}>
                                                                            {{ $consultant->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Course <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="course_name" class="form-select select"
                                                                    required>
                                                                    <option value="">Choose Course</option>
                                                                    @foreach ($courses as $course)
                                                                        <option value="{{ $course->name }}"
                                                                            {{ $rule->course_name == $course->name ? 'selected' : '' }}>
                                                                            {{ $course->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">College (optional)</label>
                                                                <select name="college_id" class="form-select select">
                                                                    <option value="">Choose College</option>
                                                                    @foreach ($colleges as $college)
                                                                        <option value="{{ $college->id }}"
                                                                            {{ $rule->college_id == $college->id ? 'selected' : '' }}>
                                                                            {{ $college->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Commission Type <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="commission_type" class="form-select select"
                                                                    required>
                                                                    <option value="">Choose Type</option>
                                                                    <option value="percentage"
                                                                        {{ $rule->commission_type == 'percentage' ? 'selected' : '' }}>
                                                                        Percentage</option>
                                                                    <option value="fixed_amount"
                                                                        {{ $rule->commission_type == 'fixed_amount' ? 'selected' : '' }}>
                                                                        Fixed Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Commission Value <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" step="0.01"
                                                                    name="commission_value" class="form-control"
                                                                    value="{{ $rule->commission_value }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Currency <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="currency" class="form-select select"
                                                                    required>
                                                                    <option value="">Choose Currency</option>
                                                                    <option value="USD"
                                                                        {{ $rule->currency == 'USD' ? 'selected' : '' }}>
                                                                        USD ($)</option>
                                                                    <option value="EUR"
                                                                        {{ $rule->currency == 'EUR' ? 'selected' : '' }}>
                                                                        EUR (€)</option>
                                                                    <option value="GBP"
                                                                        {{ $rule->currency == 'GBP' ? 'selected' : '' }}>
                                                                        GBP (£)</option>
                                                                    <option value="INR"
                                                                        {{ $rule->currency == 'INR' ? 'selected' : '' }}>
                                                                        INR (₹)</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Status <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="status" class="form-select select"
                                                                    required>
                                                                    <option value="active"
                                                                        {{ $rule->status == 'active' ? 'selected' : '' }}>
                                                                        Active</option>
                                                                    <option value="inactive"
                                                                        {{ $rule->status == 'inactive' ? 'selected' : '' }}>
                                                                        Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Notes</label>
                                                                <textarea name="notes" class="form-control" rows="3">{{ $rule->notes }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <button type="button" data-bs-dismiss="offcanvas"
                                                        class="btn btn-light me-2">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /Edit Commission Rule -->
                                    <!-- delete modal -->
                                    <div class="modal fade" id="delete_rule{{ $rule->id }}">
                                        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
                                            <div class="modal-content rounded-0">
                                                <div class="modal-body p-4 text-center position-relative">
                                                    <form action="{{ route('commission-rules.destroy', $rule->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="mb-3 position-relative z-1">
                                                            <span
                                                                class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i
                                                                    class="ti ti-trash fs-24"></i></span>
                                                        </div>
                                                        <h5 class="mb-1">Delete Confirmation</h5>
                                                        <p class="mb-3">Are you sure you want to remove Rule you
                                                            selected.</p>

                                                        <div class="d-flex justify-content-center">
                                                            <a href="#"
                                                                class="btn btn-light position-relative z-1 me-2 w-100"
                                                                data-bs-dismiss="modal">Cancel</a>
                                                            <button type="submit"
                                                                class="btn btn-primary position-relative z-1 w-100">Yes,
                                                                Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- delete modal -->
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="datatable-length"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="datatable-paginate"></div>
                        </div>
                    </div>
                    <!-- /Commission Rules List -->

                </div>
            </div>
            <!-- card end -->

        </div>
        <!-- End Content -->



    </div>

    <!-- ========================
           End Page Content
          ========================= -->

    <!-- Add Commission Rule -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="mb-0">Add New Commission Rule</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('commission-rules.store') }}" method="POST">
                @csrf
                <div>
                    <div class="row">
                       <!-- Consultant ID Field -->
<div class="col-md-6">
    <div class="mb-3">
        <label class="form-label">Consultant <span class="text-danger">*</span></label>
        
        @if(auth()->user()?->hasRole('consultant') || (auth()->user()?->role ?? '') === 'consultant')
            {{-- Consultant: Auto-select, readonly --}}
            <input type="hidden" name="consultant_id" value="{{ auth()->id() }}">
            <input type="text" class="form-control" value="{{ auth()->user()->name ?? 'You' }}" disabled>
            <small class="text-muted">Rules will be assigned to your account</small>
        @else
            {{-- Admin: Full dropdown --}}
            <select name="consultant_id" class="form-select select" required>
                <option value="">Choose Consultant</option>
                @foreach($consultants as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        @endif
    </div>
</div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Course <span class="text-danger">*</span></label>
                                <select name="course_name" class="form-select select" required>
                                    <option value="">Choose Course</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->name }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">College (optional)</label>
                                <select name="college_id" class="form-select select">
                                    <option value="">Choose College</option>
                                    @foreach ($colleges as $college)
                                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Commission Type <span class="text-danger">*</span></label>
                                <select name="commission_type" class="form-select select" required>
                                    <option value="">Choose Type</option>
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed_amount">Fixed Amount</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Commission Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="commission_value" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Currency <span class="text-danger">*</span></label>
                                <select name="currency" class="form-select select" required>
                                    <option value="">Choose Currency</option>
                                    <option value="USD">USD ($)</option>
                                    <option value="EUR">EUR (€)</option>
                                    <option value="GBP">GBP (£)</option>
                                    <option value="INR">INR (₹)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <button type="button" data-bs-dismiss="offcanvas" class="btn btn-light me-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add Commission Rule -->

    <!-- edit proposal -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_edit">
        <div class="offcanvas-header border-bottom">
            <h5 class="mb-0">Edit Commission Rules</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
            </button>
        </div>
        <div class="offcanvas-body">
            <form>
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="Distribution">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Commission Rules Type <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Choose</option>
                                    <option>Public Relations</option>
                                    <option selected="">Brand</option>
                                    <option>Media</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Deal Value <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="$04,51,000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Currency <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Choose</option>
                                    <option selected="">Dollar</option>
                                    <option>Euro</option>
                                    <option>Pound</option>
                                    <option>Rupee</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Period <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Choose</option>
                                    <option selected="">Days</option>
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Period Value <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="Collins">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Target Audience <span class="text-danger">*</span></label>
                                <input class="input-tags form-control border-0 h-100" data-choices=""
                                    data-choices-limit="infinite" data-choices-removeitem="" type="text"
                                    value="Small Business, Corporate Companies, Urban Apartment">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea rows="3" class="form-control" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Attachment <span class="text-danger">*</span></label>
                                <div
                                    class="file-upload drag-file w-100 d-flex bg-light border shadow align-items-center justify-content-center flex-column">
                                    <span class="upload-img d-block mb-1"><i
                                            class="ti ti-folder-open text-primary fs-16"></i></span>
                                    <p class="mb-0 fs-14 text-dark">Drop your files here or <a href="javascript:void(0);"
                                            class="text-decoration-underline text-primary">browse</a></p>
                                    <input type="file" accept="video/image">
                                    <p class="fs-13 mb-0">Maximum size : 50 MB</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar rounded bg-success fs-24 flex-shrink-0"><i
                                                    class="ti ti-file-spreadsheet"></i></span>
                                            <div>
                                                <h6 class="mb-1 fs-14 fw-medium">Project Specs.xls</h6>
                                                <p class="mb-0">365 KB</p>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);"
                                            class="btn btn-icon btn-xs rounded-circle btn-outline-light flex-shrink-0"><i
                                                class="ti ti-download"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar rounded bg-success fs-24 flex-shrink-0"><img
                                                    src="assets/img/blogs/blog-1.jpg" alt="img"></span>
                                            <div>
                                                <h6 class="mb-1 fs-14 fw-medium">637.jpg</h6>
                                                <p class="mb-0">365 KB</p>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);"
                                            class="btn btn-icon btn-xs rounded-circle btn-outline-light flex-shrink-0"><i
                                                class="ti ti-download"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <button type="button" data-bs-dismiss="offcanvas" class="btn btn-light me-2">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <!-- edit proposal -->

    <!-- delete modal -->
    <div class="modal fade" id="delete_Commission Rules">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4 text-center position-relative">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i
                                class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="mb-1">Delete Confirmation</h5>
                    <p class="mb-3">Are you sure you want to remove Commission Rules you selected.</p>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-light position-relative z-1 me-2 w-100"
                            data-bs-dismiss="modal">Cancel</a>
                        <a href="#" class="btn btn-primary position-relative z-1 w-100"
                            data-bs-dismiss="modal">Yes, Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete modal -->
@endsection
