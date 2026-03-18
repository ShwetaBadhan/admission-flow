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
    <div class="page-wrapper">
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Communication Logs<span class="badge badge-soft-primary ms-2">{{ $logs->total() }}</span>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Communication Logs</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#add_Log">
                        <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Communication Logs
                    </a>
                </div>
            </div>
            <!-- End Page Header -->


            <div class="card border-0 rounded-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search" id="searchInput">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap datatable" id="Communication LogsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="no-sort">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th>Title</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-md">
                                                <input class="form-check-input item-checkbox" type="checkbox"
                                                    value="{{ $log->id }}">
                                            </div>
                                        </td>
                                        <td><span class="title-name">{{ $log->name }}</span></td>
                                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            @if ($log->status == 1)
                                                <span class="badge badge-pill badge-status bg-success">Active</span>
                                            @else
                                                <span class="badge badge-pill badge-status bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown table-action">
                                                <a href="#"
                                                    class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit-btn" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#edit_Log{{ $log->id }}">
                                                        <i class="ti ti-edit text-blue"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item delete-btn" href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delete_Log{{ $log->id }}">
                                                        <i class="ti ti-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Log Modal -->
                                    <div class="modal fade" id="edit_Log{{ $log->id }}" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Log</h5>
                                                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('communication-logs.update', $log->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Log Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="name"
                                                                value="{{ $log->name }}" class="form-control" required>
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label">Status</label>
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-2">
                                                                    <input type="radio" class="status-radio"
                                                                        id="edit-active-{{ $log->id }}" name="status"
                                                                        value="1"
                                                                        {{ $log->status == 1 ? 'checked' : '' }}>
                                                                    <label
                                                                        for="edit-active-{{ $log->id }}">Active</label>
                                                                </div>
                                                                <div>
                                                                    <input type="radio" class="status-radio"
                                                                        id="edit-inactive-{{ $log->id }}"
                                                                        name="status" value="0"
                                                                        {{ $log->status == 0 ? 'checked' : '' }}>
                                                                    <label
                                                                        for="edit-inactive-{{ $log->id }}">Inactive</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="d-flex align-items-center justify-content-end m-0">
                                                            <a href="#" class="btn btn-light me-2"
                                                                data-bs-dismiss="modal">Cancel</a>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                Changes</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Log Modal -->
                                    <div class="modal fade" id="delete_Log{{ $log->id }}" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form action="{{ route('communication-logs.destroy', $log->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="text-center">
                                                            <div
                                                                class="avatar avatar-xl bg-danger-light rounded-circle mb-3">
                                                                <i class="ti ti-trash-x fs-36 text-danger"></i>
                                                            </div>
                                                            <h4 class="mb-2">Delete Confirmation</h4>
                                                            <p class="mb-0">Are you sure you want to remove <strong
                                                                    id="deleteName"></strong>?</p>
                                                            <div
                                                                class="d-flex align-items-center justify-content-center mt-4">
                                                                <a href="#" class="btn btn-light me-2"
                                                                    data-bs-dismiss="modal">Cancel</a>
                                                                <button type="submit" class="btn btn-danger">Yes, Delete
                                                                    it</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Add New Log Modal -->
    <div class="modal fade" id="add_Log" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Log</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('communication-logs.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Log Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Status</label>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <input type="radio" class="status-radio" id="add-active" name="status"
                                        value="1" checked>
                                    <label for="add-active">Active</label>
                                </div>
                                <div>
                                    <input type="radio" class="status-radio" id="add-inactive" name="status"
                                        value="0">
                                    <label for="add-inactive">Inactive</label>
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
@endsection
