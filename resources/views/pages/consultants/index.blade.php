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
    <div class="page-wrapper">
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Consultants<span
                            class="badge badge-soft-primary ms-2">{{ $consultants->total() }}</span></h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Consultants</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#add_consultant">
                        <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Consultants
                    </a>
                </div>
            </div>
            <!-- End Page Header -->

            <div class="card border-0 rounded-0">
                <div class="card-body">
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="no-sort">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($consultants as $consultant)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-md">
                                                <input class="form-check-input" type="checkbox">
                                            </div>
                                        </td>
                                        <td><span class="title-name">{{ $consultant->name }}</span></td>
                                        <td>{{ $consultant->email }}</td>
                                        <td>{{ $consultant->phone }}</td>
                                        
                                        <td>
                                            <span
                                                class="badge badge-pill badge-status bg-{{ $consultant->status == 1 ? 'success' : 'danger' }}">
                                                {{ $consultant->status == 1 ? 'Active' : 'Inactive' }}
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
                                                    {{-- Edit Button with data attributes --}}
                                                    <a class="dropdown-item edit-btn" href="javascript:void(0);"
                                                        data-bs-toggle="modal" data-bs-target="#edit_consultant"
                                                        data-id="{{ $consultant->id }}" data-name="{{ $consultant->name }}"
                                                        data-email="{{ $consultant->email }}"
                                                        data-phone="{{ $consultant->phone }}"
                                                        data-state-id="{{ $consultant->state }}"
                                                        data-city-id="{{ $consultant->city }}"
                                                        data-address="{{ $consultant->address }}"
                                                        data-status="{{ $consultant->status }}">
                                                        <i class="ti ti-edit text-blue"></i> Edit
                                                    </a>
                                                    {{-- Delete Button --}}
                                                    <a class="dropdown-item delete-btn" href="javascript:void(0);"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delete_consultant{{ $consultant->id }}">
                                                        <i class="ti ti-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Delete Consultant Modal -->
                                    <div class="modal fade" id="delete_consultant{{ $consultant->id }}" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <div class="avatar avatar-xl bg-danger-light rounded-circle mb-3">
                                                            <i class="ti ti-trash-x fs-36 text-danger"></i>
                                                        </div>
                                                        <h4 class="mb-2">Delete Confirmation</h4>
                                                        <p class="mb-0">Are you sure you want to remove <strong
                                                                id="delete-name"></strong>?</p>
                                                        <form action="{{ route('consultants.destroy', $consultant->id) }}"
                                                            method="POST" class="mt-4">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="d-flex align-items-center justify-content-center">
                                                                <a href="#" class="btn btn-light me-2"
                                                                    data-bs-dismiss="modal">Cancel</a>
                                                                <button type="submit" class="btn btn-danger">Yes, Delete
                                                                    it</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $consultants->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Add New Consultant Modal -->
    <div class="modal fade" id="add_consultant" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Consultant</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('consultants.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Phone No. <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <select name="state" id="add_state" class="form-select" required>
                                        <option value="">Select State</option>
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
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <select name="city" id="add_city" class="form-select" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" required>{{ old('address') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <input type="radio" class="status-radio" id="add-active" name="status"
                                                value="1" {{ old('status', 1) == 1 ? 'checked' : '' }}>
                                            <label for="add-active">Active</label>
                                        </div>
                                        <div>
                                            <input type="radio" class="status-radio" id="add-inactive" name="status"
                                                value="0" {{ old('status') == 0 ? 'checked' : '' }}>
                                            <label for="add-inactive">Inactive</label>
                                        </div>
                                    </div>
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

    <!-- Edit Consultant Modal (SINGLE modal, reused for all consultants) -->
    <div class="modal fade" id="edit_consultant" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Consultant</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit-name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="edit-email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Phone No. <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="edit-phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <select name="state" id="edit_state" class="form-select" required>
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
                                    <select name="city" id="edit_city" class="form-select" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" id="edit-address" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <input type="radio" class="status-radio" id="edit-active" name="status"
                                                value="1">
                                            <label for="edit-active">Active</label>
                                        </div>
                                        <div>
                                            <input type="radio" class="status-radio" id="edit-inactive" name="status"
                                                value="0">
                                            <label for="edit-inactive">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="d-flex align-items-center justify-content-end m-0">
                                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>







                    </div>

                </form>
            </div>
        </div>
    </div>
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
