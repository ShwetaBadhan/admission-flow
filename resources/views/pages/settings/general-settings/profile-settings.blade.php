@extends('layout.master')
@section('content')
    {{-- ✅ SweetAlert Notifications --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: @json(session('success')),
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: @json(implode('<br>', $errors->all())),
                    confirmButtonText: 'Okay',
                    customClass: {
                        popup: 'swal-wide'
                    }
                });
            });
        </script>
    @endif

    <div class="page-wrapper">
        <div class="content">
            {{-- Page Header --}}
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Settings</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Profile Settings</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex">
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" title="Refresh"
                        data-bs-toggle="tooltip">
                        <i class="ti ti-refresh"></i>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" title="Collapse"
                        id="collapse-header" data-bs-toggle="tooltip">
                        <i class="ti ti-transition-top"></i>
                    </a>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body pb-0 pt-0 px-2">
                    @include('components.settings-header')
                </div>
            </div>

            <div class="row">
                {{-- Sidebar --}}
                <div class="col-xl-3 col-lg-12 theiaStickySidebar">
                    <div class="card mb-3 mb-xl-0">
                        <div class="card-body">
                            <div class="settings-sidebar">
                                <h5 class="mb-3 fs-17">General Settings</h5>
                                <div class="list-group list-group-flush">
                                    @include('components.general-settings-sidebar')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="col-xl-9 col-lg-12">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="border-bottom mb-4 pb-3">
                                <h5 class="mb-0 fs-17">Profile</h5>
                            </div>

                            <form action="{{ route('profile-settings.update') }}" method="POST"
                                enctype="multipart/form-data" id="profileForm">
                                @csrf

                                {{-- Profile Image Upload --}}
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Profile Picture</label>
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <div class="position-relative">
                                            <div class="avatar avatar-xxl border border-dashed rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                style="width: 120px; height: 120px;">
                                                {{-- ✅ FIXED: Use auth()->user()->profile_photo --}}
                                                <img id="profilePreview"
                                                    src="{{ auth()->user()->profile_photo && Storage::disk('public')->exists(auth()->user()->profile_photo) ? Storage::url(auth()->user()->profile_photo) : asset('assets/img/profiles/avatar-02.jpg') }}"
                                                    class="rounded-circle object-fit-cover"
                                                    style="width: 100%; height: 100%;" alt="{{ auth()->user()->name }}"
                                                    onerror="this.src='{{ asset('assets/img/profiles/avatar-02.jpg') }}'">
                                            </div>
                                            <label for="profileImageInput"
                                                class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle shadow"
                                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                                <i class="ti ti-pencil fs-6"></i>
                                                <input type="file" name="profile_image" id="profileImageInput"
                                                    class="d-none" accept="image/*">
                                            </label>
                                        </div>
                                        <div>
                                            <p class="mb-1 text-muted small">JPG, PNG, GIF • Max 2MB</p>
                                            {{-- ✅ FIXED: Check auth()->user()->profile_photo --}}
                                            @if (auth()->user()->profile_photo)
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    id="removeProfileImage">Remove</button>
                                            @endif
                                            @error('profile_image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Personal Information --}}
                                <div class="border-bottom mb-4 pb-4">
                                    <h6 class="mb-3 fw-semibold">Personal Information</h6>
                                    <div class="row g-3">
                                        {{-- First Name --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="first_name"
                                                value="{{ old('first_name', $profile->first_name) }}"
                                                class="form-control @error('first_name') is-invalid @enderror" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Last Name --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="last_name"
                                                value="{{ old('last_name', $profile->last_name) }}"
                                                class="form-control @error('last_name') is-invalid @enderror" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Username --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">Username <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="username"
                                                value="{{ old('username', $profile->username) }}"
                                                class="form-control @error('username') is-invalid @enderror" required>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Phone --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" name="phone"
                                                value="{{ old('phone', $profile->phone) }}"
                                                class="form-control @error('phone') is-invalid @enderror" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email"
                                                value="{{ old('email', auth()->user()->email) }}"
                                                class="form-control @error('email') is-invalid @enderror" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Brand Assets --}}
                                <div class="border-bottom mb-4 pb-4">
                                    <h6 class="mb-3 fw-semibold">Brand & System Assets</h6>
                                    <div class="row g-3">
                                        @php
                                            $assets = [
                                                [
                                                    'key' => 'white_logo',
                                                    'label' => 'White Logo',
                                                    'accept' => 'image/*',
                                                    'max' => '1MB',
                                                ],
                                                [
                                                    'key' => 'black_logo',
                                                    'label' => 'Black Logo',
                                                    'accept' => 'image/*',
                                                    'max' => '1MB',
                                                ],
                                                [
                                                    'key' => 'favicon',
                                                    'label' => 'Favicon',
                                                    'accept' => 'image/x-icon,image/png',
                                                    'max' => '512KB',
                                                ],
                                                [
                                                    'key' => 'cover_image',
                                                    'label' => 'Cover Image',
                                                    'accept' => 'image/*',
                                                    'max' => '5MB',
                                                ],
                                            ];
                                        @endphp
                                        @foreach ($assets as $asset)
                                            <div class="col-md-3">
                                                <div class="card h-100 border-0 bg-light">
                                                    <div class="card-body text-center p-3">
                                                        <div class="position-relative mb-2" style="height: 80px;">
                                                            @if ($profile->{$asset['key']} && Storage::disk('public')->exists($profile->{$asset['key']}))
                                                                <img src="{{ Storage::url($profile->{$asset['key']}) }}"
                                                                    class="rounded object-fit-contain"
                                                                    style="max-height: 80px; max-width: 100%;"
                                                                    alt="{{ $asset['label'] }}">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle p-0"
                                                                    style="width: 20px; height: 20px;"
                                                                    onclick="removeAsset('{{ $asset['key'] }}')"
                                                                    title="Remove">
                                                                    <i class="ti ti-x fs-6"></i>
                                                                </button>
                                                            @else
                                                                <div
                                                                    class="d-flex align-items-center justify-content-center h-100 text-muted small">
                                                                    <i class="ti ti-photo me-1"></i> No image
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <label
                                                            class="form-label small fw-medium mb-1">{{ $asset['label'] }}</label>
                                                        <input type="file" name="{{ $asset['key'] }}"
                                                            id="{{ $asset['key'] }}Input"
                                                            class="form-control form-control-sm @error($asset['key']) is-invalid @enderror"
                                                            accept="{{ $asset['accept'] }}">
                                                        <small class="text-muted d-block mt-1">Max
                                                            {{ $asset['max'] }}</small>
                                                        @error($asset['key'])
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Address Section --}}
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-semibold">Address</h6>
                                    <div class="row g-3">
                                        {{-- Address --}}
                                        <div class="col-12">
                                            <label class="form-label small">Street Address</label>
                                            <input type="text" name="address"
                                                value="{{ old('address', $profile->address) }}"
                                                class="form-control @error('address') is-invalid @enderror">
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Country --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">Country</label>
                                            <input type="text" name="country"
                                                value="{{ old('country', $profile->country) }}"
                                                class="form-control @error('country') is-invalid @enderror">
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- State --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">State / Province</label>
                                            <select name="state_id" id="stateSelect"
                                                class="form-select @error('state_id') is-invalid @enderror">
                                                <option value="">Select State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ old('state_id', $profile->state_id) == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('state_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- City --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">City</label>
                                            <select name="city_id" id="citySelect"
                                                class="form-select @error('city_id') is-invalid @enderror">
                                                <option value="">Select City</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        data-state="{{ $city->state_id }}"
                                                        {{ old('city_id', $profile->city_id) == $city->id ? 'selected' : '' }}>
                                                        {{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('city_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Postal Code --}}
                                        <div class="col-md-4">
                                            <label class="form-label small">Postal Code</label>
                                            <input type="text" name="postal_code"
                                                value="{{ old('postal_code', $profile->postal_code) }}"
                                                class="form-control @error('postal_code') is-invalid @enderror">
                                            @error('postal_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('profile-settings') }}" class="btn btn-sm btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-primary" id="saveBtn">
                                        <span class="spinner-border spinner-border-sm d-none" id="saveSpinner"
                                            role="status"></span>
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Keep your existing modals here (unchanged) --}}
    <div class="modal fade" id="change_password" role="dialog">...</div>
    <div class="modal fade" id="change_phone_number" role="dialog">...</div>
    <div class="modal fade" id="change_email" role="dialog">...</div>
    <div class="modal fade" id="device_management" role="dialog">...</div>
    <div class="modal fade" id="account_activity" role="dialog">...</div>
    <div class="modal fade" id="deactive_account">...</div>
    <div class="modal fade" id="delete_account" role="dialog">...</div>

    {{-- ✅ JavaScript: Previews, Asset Removal, State/City Filter, Form Submit --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips
                if (typeof bootstrap !== 'undefined') {
                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
                }

                // ===== Image Preview Logic =====
                function setupImagePreview(inputId, previewId, defaultSrc) {
                    const input = document.getElementById(inputId);
                    const preview = document.getElementById(previewId);
                    if (!input || !preview) return;

                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            preview.src = URL.createObjectURL(file);
                            preview.classList.remove('d-none');
                        }
                    });
                }

                // Profile image preview
                setupImagePreview('profileImageInput', 'profilePreview',
                    "{{ asset('assets/img/profiles/avatar-02.jpg') }}");

                // Brand asset previews
                document.querySelectorAll('input[type="file"][name]').forEach(input => {
                    const name = input.name;
                    if (['white_logo', 'black_logo', 'favicon', 'cover_image'].includes(name)) {
                        input.addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (file && file.type.startsWith('image/')) {
                                const card = input.closest('.card');
                                const img = card?.querySelector('img');
                                if (img) {
                                    img.src = URL.createObjectURL(file);
                                    img.classList.remove('d-none');
                                    const removeBtn = card?.querySelector(
                                        'button[onclick*="removeAsset"]');
                                    if (removeBtn) removeBtn.classList.remove('d-none');
                                }
                            }
                        });
                    }
                });

                // ===== Remove Profile Image =====
                const removeProfileBtn = document.getElementById('removeProfileImage');
                if (removeProfileBtn) {
                    removeProfileBtn.addEventListener('click', function() {
                        Swal.fire({
                            title: 'Remove Profile Picture?',
                            text: 'This will revert to the default avatar.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, Remove',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('profilePreview').src =
                                    "{{ asset('assets/img/profiles/avatar-02.jpg') }}";
                                document.getElementById('profileImageInput').value = '';
                                removeProfileBtn.remove();
                                Swal.fire('Removed!', '', 'success');
                            }
                        });
                    });
                }

                // ===== Remove Brand Asset =====
                window.removeAsset = function(assetKey) {
                    Swal.fire({
                        title: `Remove ${assetKey.replace('_', ' ')}?`,
                        text: 'The current image will be deleted.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const input = document.getElementById(assetKey + 'Input');
                            const card = input?.closest('.card');
                            const img = card?.querySelector('img');
                            const removeBtn = card?.querySelector(
                                `button[onclick="removeAsset('${assetKey}')"]`);

                            if (img) {
                                img.src = '';
                                img.classList.add('d-none');
                                img.alt = 'No image';
                                img.parentElement.innerHTML =
                                    '<div class="d-flex align-items-center justify-content-center h-100 text-muted small"><i class="ti ti-photo me-1"></i> No image</div>';
                            }
                            if (input) input.value = '';
                            if (removeBtn) removeBtn.classList.add('d-none');

                            Swal.fire('Deleted!', '', 'success');
                        }
                    });
                };

                // ===== State → City Filter =====
                const stateSelect = document.getElementById('stateSelect');
                const citySelect = document.getElementById('citySelect');
                if (stateSelect && citySelect) {
                    const cityOptions = Array.from(citySelect.options);

                    function filterCities() {
                        const selectedState = stateSelect.value;
                        cityOptions.forEach(opt => {
                            if (!opt.value) return;
                            const cityState = opt.getAttribute('data-state');
                            opt.style.display = (!selectedState || cityState === selectedState) ? '' : 'none';
                        });
                        if (citySelect.value && citySelect.options[citySelect.selectedIndex]?.style.display ===
                            'none') {
                            citySelect.value = '';
                        }
                    }
                    stateSelect.addEventListener('change', filterCities);
                    filterCities();
                }

                // ===== Form Submit Loading State =====
                const form = document.getElementById('profileForm');
                const saveBtn = document.getElementById('saveBtn');
                const spinner = document.getElementById('saveSpinner');

                if (form && saveBtn && spinner) {
                    form.addEventListener('submit', function() {
                        saveBtn.disabled = true;
                        spinner.classList.remove('d-none');
                        saveBtn.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Saving...';
                    });
                }
            });
        </script>
    @endpush
@endsection
