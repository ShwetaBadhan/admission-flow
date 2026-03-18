@extends('layout.master')
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
                    <h4 class="mb-1">Calls Reason<span class="badge badge-soft-primary ms-2">232</span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Calls Reason</li>
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

            <div class="card border-0 rounded-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#add_calls"><i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Call
                        Reason</a>
                </div>
                <div class="card-body">
                    <!-- Contact Stage List -->
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="no-sort">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th>Title</th>
                                    <th>Created at</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        <div class="form-check form-check-md"><input class="form-check-input"
                                                type="checkbox"></div>
                                    </td>
                                    <td><span class="title-name">Busy</span></td>
                                    <td>25 Feb 2025, 01:22 PM</td>
                                    <td><span class="badge badge-pill badge-status bg-success">Active</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">No Answer</span></td>
                                    <td>03 Apr 2025, 09:45 AM</td>
                                    <td><span class="badge badge-pill badge-status bg-success">Active</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">Wrong Number</span></td>
                                    <td>14 Apr 2025, 11:11 AM</td>
                                    <td><span class="badge badge-pill badge-status bg-success">Active</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">Unavailable</span></td>
                                    <td>12 May 2025, 01:09 AM</td>
                                    <td><span class="badge badge-pill badge-status bg-success">Active</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">Technical Issue</span></td>
                                    <td>28 May 2025, 07:08 AM</td>
                                    <td><span class="badge badge-pill badge-status bg-success">Active</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">Bug Report</span></td>
                                    <td>01 July 2025, 02:15 AM</td>
                                    <td><span class="badge badge-pill badge-status bg-danger">Inactive</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">Customer Call</span></td>
                                    <td>20 Jul 2025, 10:25 AM</td>
                                    <td><span class="badge badge-pill badge-status bg-danger">Inactive</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">Management Call</span></td>
                                    <td>16 Sep 2025, 02:10 PM</td>
                                    <td><span class="badge badge-pill badge-status bg-success">Active</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">Support Call</span></td>
                                    <td>10 Oct 2025, 10:15 AM</td>
                                    <td><span class="badge badge-pill badge-status bg-danger">Inactive</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><input class="form-check-input" type="checkbox"></td>
                                    <td><span class="title-name">Sales Call</span></td>
                                    <td>01 Nov 2025, 01:32 PM</td>
                                    <td><span class="badge badge-pill badge-status bg-success">Active</span></td>
                                    <td>
                                        <div class="dropdown table-action">
                                            <a href="#"
                                                class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit_calls">
                                                    <i class="ti ti-edit text-blue"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#delete_calls">
                                                    <i class="ti ti-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

        </div>
        <!-- End Content -->



    </div>

    <!-- ========================
       End Page Content
      ========================= -->

    <!-- Add New Source -->
    <div class="modal fade" id="add_calls" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Call Reason</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="calls.html">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Status</label>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <input type="radio" class="status-radio" id="edit-active" name="status">
                                    <label for="edit-active">Active</label>
                                </div>
                                <div>
                                    <input type="radio" class="status-radio" id="edit-inactive" name="status">
                                    <label for="edit-inactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex align-items-center justify-content-end m-0">
                            <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create New</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Add New Source -->

    <!-- Edit Source -->
    <div class="modal fade" id="edit_calls" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Call Reason</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="calls.html">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="Busy">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Status</label>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <input type="radio" class="status-radio" id="edit-active2" name="status"
                                        checked="">
                                    <label for="edit-active2">Active</label>
                                </div>
                                <div>
                                    <input type="radio" class="status-radio" id="edit-inactive2" name="status">
                                    <label for="edit-inactive2">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex align-items-center justify-content-end m-0">
                            <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Edit Source -->

    <!-- Delete Source -->
    <div class="modal fade" id="delete_calls" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="avatar avatar-xl bg-danger-light rounded-circle mb-3">
                            <i class="ti ti-trash-x fs-36 text-danger"></i>
                        </div>
                        <h4 class="mb-2">Delete Confirmation</h4>
                        <p class="mb-0">Are you sure you want to remove Call Reason you selected.</p>
                        <div class="d-flex align-items-center justify-content-center mt-4">
                            <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                            <a href="calls.html" class="btn btn-danger">Yes, Delete it</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Source -->
@endsection
