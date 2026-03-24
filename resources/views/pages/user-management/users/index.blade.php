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
                    <h4 class="mb-1">Manage Users <span class="badge badge-soft-primary ms-2">{{ $users->total() }}</span>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Users</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    @can('create-users')
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas_add">
                            <i class="ti ti-square-rounded-plus-filled me-1"></i>Add User
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
                    <!-- Contact List -->
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap datatable" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="no-sort">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th class="no-sort"></th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-md">
                                                <input class="form-check-input" type="checkbox" value="{{ $user->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="set-star rating-select">
                                                <i class="ti ti-star-filled fs-16"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="d-flex align-items-center fs-14 fw-medium mb-0">
                                                <a href="javascript:void(0);" class="avatar avatar-rounded me-2">
                                                    <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : url('assets/img/profiles/avatar-19.jpg') }}"
                                                        alt="User Image">
                                                </a>
                                                <a href="javascript:void(0);" class="d-flex flex-column">
                                                    {{ $user->name }}
                                                    <span class="text-body fs-13 mt-1 d-inline-block fw-normal">
                                                        {{ $user->getRoleNames()->first() ?? 'No Role' }}
                                                    </span>
                                                </a>
                                            </h6>
                                        </td>
                                        <td>{{ $user->phone ?? 'N/A' }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach ($user->getRoleNames() as $role)
                                                <span class="badge badge-soft-primary">{{ $role }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y, h:i a') }}</td>
                                        <td>
                                            @if ($user->status)
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
                                                    @can('edit-users')
                                                        <a class="dropdown-item edit-btn" href="javascript:void(0);"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvas_edit{{ $user->id }}">
                                                            <i class="ti ti-edit text-blue"></i> Edit
                                                        </a>
                                                    @endcan
                                                    @can('delete-users')
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#delete_user{{ $user->id }}">
                                                            <i class="ti ti-trash"></i> Delete
                                                        </a>
                                                    @endcan

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Edit User Offcanvas -->
                                    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1"
                                        id="offcanvas_edit{{ $user->id }}">
                                        <div class="offcanvas-header border-bottom">
                                            <h5 class="fw-semibold">Edit User</h5>
                                            <button type="button"
                                                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                                                data-bs-dismiss="offcanvas" aria-label="Close">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div
                                                                    class="avatar avatar-xxl border border-dashed me-3 flex-shrink-0">
                                                                    <div
                                                                        class="position-relative d-flex align-items-center">
                                                                        <i class="ti ti-photo text-dark fs-16"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-inline-flex flex-column align-items-start">
                                                                    <div
                                                                        class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                                                        <i class="ti ti-file-broken me-1"></i>Upload file
                                                                        <input type="file"
                                                                            class="form-control image-sign"
                                                                            name="profile_photo">
                                                                    </div>
                                                                    <span>JPG, GIF or PNG. Max size of 800K</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">User Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $user->name }}" name="name" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="email" class="form-control"
                                                                    value="{{ $user->email }}" name="email" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Role <span
                                                                        class="text-danger">*</span></label>
                                                                <select class="select" name="role" required>
                                                                    <option value="">Select Role</option>
                                                                    @foreach ($roles as $role)
                                                                        <option value="{{ $role->name }}"
                                                                            {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                                                            {{ $role->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Phone <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $user->phone }}" name="phone">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Password (Leave blank to keep
                                                                    current)</label>
                                                                <div class="input-group input-group-flat pass-group">
                                                                    <input type="password" class="form-control pass-input"
                                                                        name="password">
                                                                    <span class="input-group-text toggle-password">
                                                                        <i class="ti ti-eye-off"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Repeat Password</label>
                                                                <div class="input-group input-group-flat pass-group">
                                                                    <input type="password" class="form-control pass-input"
                                                                        name="password_confirmation">
                                                                    <span class="input-group-text toggle-password">
                                                                        <i class="ti ti-eye-off"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Status</label>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-2">
                                                                        <input type="radio" class="status-radio"
                                                                            id="edit-active-{{ $user->id }}"
                                                                            name="status" value="1"
                                                                            {{ $user->status == 1 ? 'checked' : '' }}>
                                                                        <label
                                                                            for="edit-active-{{ $user->id }}">Active</label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="radio" class="status-radio"
                                                                            id="edit-inactive-{{ $user->id }}"
                                                                            name="status" value="0"
                                                                            {{ $user->status == 0 ? 'checked' : '' }}>
                                                                        <label
                                                                            for="edit-inactive-{{ $user->id }}">Inactive</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <a href="#" class="btn btn-light me-2"
                                                        data-bs-dismiss="offcanvas">Cancel</a>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /Edit User -->
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="delete_user{{ $user->id }}">
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
                                                    <p class="mb-3">Are you sure you want to delete this user?</p>
                                                    <form action="{{ route('users.destroy', $user->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="d-flex justify-content-center">
                                                            <a href="#"
                                                                class="btn btn-light position-relative z-1 me-2 w-100"
                                                                data-bs-dismiss="modal">Cancel</a>
                                                            <button type="submit"
                                                                class="btn btn-danger position-relative z-1 w-100">
                                                                Yes, Delete
                                                            </button>
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
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
            <!-- card end -->
        </div>
    </div>

    <!-- Add User Offcanvas -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Add New User</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-xxl border border-dashed me-3 flex-shrink-0">
                                    <div class="position-relative d-flex align-items-center">
                                        <i class="ti ti-photo text-dark fs-16"></i>
                                    </div>
                                </div>
                                <div class="d-inline-flex flex-column align-items-start">
                                    <div class="drag-upload-btn btn btn-sm btn-primary position-relative mb-2">
                                        <i class="ti ti-file-broken me-1"></i>Upload file
                                        <input type="file" class="form-control image-sign" name="profile_photo">
                                    </div>
                                    <span>JPG, GIF or PNG. Max size of 800K</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">User Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-flat pass-group">
                                    <input type="password" class="form-control pass-input" name="password" required>
                                    <span class="input-group-text toggle-password">
                                        <i class="ti ti-eye-off"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Repeat Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-flat pass-group">
                                    <input type="password" class="form-control pass-input" name="password_confirmation"
                                        required>
                                    <span class="input-group-text toggle-password">
                                        <i class="ti ti-eye-off"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
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
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="offcanvas">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add User -->
@endsection
