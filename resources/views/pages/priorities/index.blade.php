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
                    <h4 class="mb-1">priorities<span class="badge badge-soft-primary ms-2">{{ $priorities->total() }}</span>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">priorities</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#add_Priority">
                        <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New priorities
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
                        <table class="table table-nowrap datatable" id="prioritiesTable">
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
                                @foreach ($priorities as $priority)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-md">
                                                <input class="form-check-input item-checkbox" type="checkbox"
                                                    value="{{ $priority->id }}">
                                            </div>
                                        </td>
                                        <td><span class="title-name">{{ $priority->name }}</span></td>
                                        <td>{{ $priority->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            @if ($priority->status == 1)
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
                                                        data-bs-target="#edit_Priority{{ $priority->id }}">
                                                        <i class="ti ti-edit text-blue"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item delete-btn" href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delete_Priority{{ $priority->id }}">
                                                        <i class="ti ti-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Priority Modal -->
                                    <div class="modal fade" id="edit_Priority{{ $priority->id }}" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Priority</h5>
                                                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('priorities.update', $priority->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Priority Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="name"
                                                                value="{{ $priority->name }}" class="form-control" required>
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label">Status</label>
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-2">
                                                                    <input type="radio" class="status-radio"
                                                                        id="edit-active-{{ $priority->id }}" name="status"
                                                                        value="1"
                                                                        {{ $priority->status == 1 ? 'checked' : '' }}>
                                                                    <label
                                                                        for="edit-active-{{ $priority->id }}">Active</label>
                                                                </div>
                                                                <div>
                                                                    <input type="radio" class="status-radio"
                                                                        id="edit-inactive-{{ $priority->id }}"
                                                                        name="status" value="0"
                                                                        {{ $priority->status == 0 ? 'checked' : '' }}>
                                                                    <label
                                                                        for="edit-inactive-{{ $priority->id }}">Inactive</label>
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

                                    <!-- Delete Priority Modal -->
                                    <div class="modal fade" id="delete_Priority{{ $priority->id }}" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form action="{{ route('priorities.destroy', $priority->id) }}"
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
                        {{ $priorities->withQueryString()->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Add New Priority Modal -->
    <div class="modal fade" id="add_Priority" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Priority</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('priorities.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Priority Name <span class="text-danger">*</span></label>
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
