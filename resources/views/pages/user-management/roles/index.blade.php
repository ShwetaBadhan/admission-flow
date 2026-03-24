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
                    <h4 class="mb-1">Roles & Permissions <span
                            class="badge badge-soft-primary ms-2">{{ $roles->total() }}</span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Roles & Permissions</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    @can('create-roles')
                        <a href="{{ route('roles.create') }}" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#add_role">
                            <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Role
                        </a>
                    @endcan

                </div>
            </div>
            <!-- End Page Header -->

            <!-- card start -->
            <div class="card border-0 rounded-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search" id="searchInput">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap datatable" id="rolesTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="no-sort">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th>Role Name</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-md">
                                                <input class="form-check-input row-check" type="checkbox"
                                                    value="{{ $role->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="mb-0">{{ $role->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($role->status)
                                                <span class="badge badge-pill badge-status bg-success">Active</span>
                                            @else
                                                <span class="badge badge-pill badge-status bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $role->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="dropdown table-action">
                                                <a href="#"
                                                    class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @can('edit-roles')
                                                        <a class="dropdown-item edit-btn" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit_role{{ $role->id }}">
                                                            <i class="ti ti-edit text-blue"></i> Edit
                                                        </a>
                                                    @endcan

                                                    {{-- @can('assign-permissions') --}}
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#permissionsModal_{{ $role->id }}">
                                                            <i class="ti ti-shield-check text-purple"></i> Assign Permissions
                                                        </a>
                                                    {{-- @endcan --}}

                                                    @can('delete-roles')
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#delete_role{{ $role->id }}"><i
                                                                class="ti ti-trash"></i>
                                                            Delete</a>
                                                    @endcan


                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Role Modal -->
                                    <div class="modal fade" id="edit_role{{ $role->id }}" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Role</h5>
                                                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-2">
                                                            <label class="form-label">Role Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="name"
                                                                value="{{ $role->name }}"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                required>
                                                            @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label">Status</label>
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-2">
                                                                    <input type="radio" class="status-radio"
                                                                        id="edit-active-{{ $role->id }}" name="status"
                                                                        value="1"
                                                                        {{ $role->status == 1 ? 'checked' : '' }}>
                                                                    <label
                                                                        for="edit-active-{{ $role->id }}">Active</label>
                                                                </div>
                                                                <div>
                                                                    <input type="radio" class="status-radio"
                                                                        id="edit-inactive-{{ $role->id }}"
                                                                        name="status" value="0"
                                                                        {{ $role->status == 0 ? 'checked' : '' }}>
                                                                    <label
                                                                        for="edit-inactive-{{ $role->id }}">Inactive</label>
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

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="delete_role{{ $role->id }}">
                                        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
                                            <div class="modal-content rounded-0">
                                                <div class="modal-body p-4 text-center position-relative">
                                                    <div class="mb-3 position-relative z-1">
                                                        <span
                                                            class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle">
                                                            <i class="ti ti-trash fs-24"></i>
                                                        </span>
                                                    </div>
                                                    <h5 class="mb-1">Delete Confirmation</h5>
                                                    <p class="mb-3">Are you sure you want to delete this role?</p>

                                                    <form id="delete-form-{{ $role->id }}"
                                                        action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="d-flex justify-content-center">
                                                            <a href="#"
                                                                class="btn btn-light position-relative z-1 me-2 w-100"
                                                                data-bs-dismiss="modal">Cancel</a>
                                                            <button type="button"
                                                                class="btn btn-danger position-relative z-1 w-100"
                                                                onclick="document.getElementById('delete-form-{{ $role->id }}').submit();">
                                                                Yes, Delete
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Permissions Modal (UPDATED WITH MULTI-SELECT) -->
                                    <div class="modal fade" id="permissionsModal_{{ $role->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content">
                                                <form action="{{ route('roles.permissions.assign', $role->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Manage Permissions for:
                                                            <strong>{{ $role->name }}</strong>
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Permissions <span
                                                                        class="text-danger">*</span></label>
                                                                <!-- Multi Select Start -->
                                                                <select class="select2 form-control select2-multiple"
                                                                    name="permissions[]" data-toggle="select2"
                                                                    multiple="multiple" data-placeholder="Choose ...">
                                                                    @php
                                                                        $allPermissions = \App\Models\Permission::orderBy(
                                                                            'name',
                                                                        )->get();
                                                                        $rolePermissionNames = $role->permissions
                                                                            ->pluck('name')
                                                                            ->toArray();
                                                                    @endphp
                                                                    @forelse($allPermissions as $permission)
                                                                        <option value="{{ $permission->name }}"
                                                                            {{ in_array($permission->name, $rolePermissionNames) ? 'selected' : '' }}>
                                                                            {{ $permission->name }}
                                                                        </option>
                                                                    @empty
                                                                        <option disabled>No permissions available</option>
                                                                    @endforelse
                                                                </select>
                                                                <!-- Multi Select End -->
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="ti ti-device-floppy me-1"></i>Save Permissions
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
            <!-- card end -->
        </div>
        <!-- End Content -->
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="add_role" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Role</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
