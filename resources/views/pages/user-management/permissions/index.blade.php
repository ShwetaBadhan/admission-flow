@extends('layout.master')

@section('content')
    <div class="page-wrapper">
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Permissions <span
                            class="badge badge-soft-primary ms-2">{{ $permissions->total() }}</span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">Permissions</li>
                        </ol>
                    </nav>
                </div>
                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#add_permission">
                    <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Permission
                </a>
            </div>

            <!-- Permissions Table -->
            <div class="card border-0 rounded-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="no-sort">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th>Permission Name</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-md">
                                                <input class="form-check-input row-check" type="checkbox"
                                                    value="{{ $permission->id }}">
                                            </div>
                                        </td>
                                        <td><strong>{{ $permission->name }}</strong></td>
                                        <td>
                                            @if ($permission->status)
                                                <span class="badge badge-pill badge-status bg-success">Active</span>
                                            @else
                                                <span class="badge badge-pill badge-status bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $permission->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="dropdown table-action">
                                                <a href="#" class="btn btn-xs btn-icon btn-outline-light"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <!-- Edit -->
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#edit_permission_{{ $permission->id }}">
                                                        <i class="ti ti-edit text-blue"></i> Edit
                                                    </a>
                                                    <!-- Delete -->
                                                    <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delete_permission_{{ $permission->id }}">
                                                        <i class="ti ti-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal (Unique ID per permission) -->
                                    <div class="modal fade" id="edit_permission_{{ $permission->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('permissions.update', $permission->id) }}"
                                                    method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Permission</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Permission Name *</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ $permission->name }}" required>
                                                        </div>
                                                        <div>
                                                            <label class="form-label">Status</label>
                                                            <div class="d-flex gap-3">
                                                                <div class="form-check">
                                                                    <input type="radio" name="status"
                                                                        id="active_{{ $permission->id }}" value="1"
                                                                        class="form-check-input"
                                                                        {{ $permission->status ? 'checked' : '' }}>
                                                                    <label
                                                                        for="active_{{ $permission->id }}">Active</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input type="radio" name="status"
                                                                        id="inactive_{{ $permission->id }}" value="0"
                                                                        class="form-check-input"
                                                                        {{ !$permission->status ? 'checked' : '' }}>
                                                                    <label
                                                                        for="inactive_{{ $permission->id }}">Inactive</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal (Unique ID per permission) -->
                                    <div class="modal fade" id="delete_permission_{{ $permission->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
                                            <div class="modal-content rounded-0">
                                                <div class="modal-body p-4 text-center position-relative">
                                                    <div class="mb-3 position-relative z-1">
                                                        <span
                                                            class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle">
                                                            <i class="ti ti-trash fs-24"></i>
                                                        </span>
                                                    </div>
                                                    <h5>Delete Permission?</h5>
                                                    <p class="text-muted mb-4">This action cannot be undone.</p>
                                                    <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Yes,
                                                                Delete</button>
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
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Add Permission Modal -->
    <div class="modal fade" id="add_permission" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Permission Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input type="radio" name="status" id="add-active" value="1"
                                        class="form-check-input" checked>
                                    <label for="add-active">Active</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="status" id="add-inactive" value="0"
                                        class="form-check-input">
                                    <label for="add-inactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 (if not in master layout) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Minimal SweetAlert for Laravel Sessions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: @json(session('success')),
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: @json(session('error')),
                    timer: 5000,
                    showConfirmButton: true
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: @json($errors->first()),
                    timer: 6000,
                    showConfirmButton: true
                });
            @endif
        });
    </script>
@endsection
