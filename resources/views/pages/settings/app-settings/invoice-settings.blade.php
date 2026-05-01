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

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Settings</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i
                            class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse"
                        id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <div class="card border-0">
                <div class="card-body pb-0 pt-0 px-2">
                    @include('components.settings-header')
                </div>
            </div>

            <!-- start row -->
            <div class="row">
                <div class="col-xl-3 col-lg-12 theiaStickySidebar">
                    @include('components.app-settings-sidebar')
                </div>

                <div class="col-xl-9 col-lg-12">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="border-bottom mb-3 pb-3">
                                <h5 class="mb-0 fs-17">Invoice Settings</h5>
                            </div>

                            {{-- Fixed Form: Proper action, method, CSRF, enctype --}}
                            <form action="{{ route('invoice-settings.update') }}" method="POST"
                                enctype="multipart/form-data" id="invoiceSettingsForm">
                                @csrf
                                @method('PUT')



                                <div class="border-bottom mb-3">
                                    <!-- Invoice Logo -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Invoice Logo</h6>
                                                <p class="fs-13 mb-0">Upload logo of your company to display in invoice</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="profile-upload d-flex align-items-center">

                                                    {{-- 🔹 Image Container (Left Side) --}}
                                                    <div class="profile-upload-img position-relative flex-shrink-0"
                                                        style="width: 120px; height: 120px;">
                                                        <div class="border border-dashed rounded position-relative w-100 h-100 bg-light d-flex align-items-center justify-content-center"
                                                            style="border-radius: 0.375rem;">

                                                            {{-- Preview Image --}}
                                                            <img id="ImgPreview"
                                                                src="{{ $settings->invoice_image && Storage::disk('public')->exists($settings->invoice_image) ? Storage::url($settings->invoice_image) : asset('assets/img/profiles/avatar-02.jpg') }}"
                                                                alt="Invoice Logo" class="w-100 h-100"
                                                                style="object-fit: contain; border-radius: 0.375rem;"
                                                                onerror="this.src='{{ asset('assets/img/profiles/avatar-02.jpg') }}'">

                                                            {{-- Remove Button --}}
                                                            <a href="javascript:void(0);" id="removeImage1"
                                                                class="position-absolute top-0 end-0 btn btn-sm btn-danger rounded-circle m-1 shadow"
                                                                style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; z-index: 20;">
                                                                <i class="ti ti-x fs-6"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    {{-- 🔹 Upload Content (Right Side) --}}
                                                    <div class="profile-upload-content ms-3">

                                                        {{-- ✅ Fixed: Label with position:relative to contain absolute input --}}
                                                        <label
                                                            class="d-inline-flex align-items-center btn btn-primary btn-sm mb-2 position-relative overflow-hidden"
                                                            style="cursor: pointer; width: fit-content;">
                                                            <i class="ti ti-file-broken me-1"></i>Upload File

                                                            {{-- ✅ Fixed: Input constrained to label bounds --}}
                                                            <input type="file" name="invoice_image" id="imag"
                                                                class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                                                style="cursor: pointer; z-index: 10;">
                                                        </label>

                                                        <p class="mb-0 fs-12 text-muted">Recommended: 250px × 100px, Max 2MB
                                                        </p>
                                                        @error('invoice_image')
                                                            <span class="text-danger fs-12">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Hidden input for removal --}}
                                                <input type="hidden" name="remove_invoice_image" id="remove_invoice_image"
                                                    value="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Invoice Prefix -->
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Invoice Prefix</h6>
                                                <p class="fs-13 mb-0">Add prefix to your invoice</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <input type="text" name="invoice_prefix"
                                                    class="form-control @error('invoice_prefix') is-invalid @enderror"
                                                    value="{{ old('invoice_prefix', $settings->invoice_prefix ?? 'INV-') }}">
                                                @error('invoice_prefix')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Invoice Due Days -->
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Invoice Due</h6>
                                                <p class="fs-13 mb-0">Select due date to display in invoice</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center inv-days">
                                                    <div class="me-2">
                                                        <select name="invoice_due_days"
                                                            class="select @error('invoice_due_days') is-invalid @enderror">
                                                            <option value="5"
                                                                {{ old('invoice_due_days', $settings->invoice_due_days ?? 5) == 5 ? 'selected' : '' }}>
                                                                5</option>
                                                            <option value="7"
                                                                {{ old('invoice_due_days', $settings->invoice_due_days ?? 5) == 7 ? 'selected' : '' }}>
                                                                7</option>
                                                            <option value="10"
                                                                {{ old('invoice_due_days', $settings->invoice_due_days ?? 5) == 10 ? 'selected' : '' }}>
                                                                10</option>
                                                            <option value="14"
                                                                {{ old('invoice_due_days', $settings->invoice_due_days ?? 5) == 14 ? 'selected' : '' }}>
                                                                14</option>
                                                            <option value="30"
                                                                {{ old('invoice_due_days', $settings->invoice_due_days ?? 5) == 30 ? 'selected' : '' }}>
                                                                30</option>
                                                        </select>
                                                    </div>
                                                    <p class="fs-13 mb-0">Days</p>
                                                </div>
                                                @error('invoice_due_days')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Invoice Round Off -->
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Invoice Round Off</h6>
                                                <p class="fs-13 mb-0">Value roundoff in invoice</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check form-switch me-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="enable_round_off" role="switch" id="enable_round_off"
                                                            {{ old('enable_round_off', $settings->enable_round_off ?? false) ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="w-100">
                                                        <select name="round_off_type"
                                                            class="select @error('round_off_type') is-invalid @enderror"
                                                            {{ !$settings->enable_round_off ? 'disabled' : '' }}
                                                            id="round_off_type">
                                                            <option value="up"
                                                                {{ old('round_off_type', $settings->round_off_type ?? 'up') == 'up' ? 'selected' : '' }}>
                                                                Roundoff Up</option>
                                                            <option value="down"
                                                                {{ old('round_off_type', $settings->round_off_type ?? 'up') == 'down' ? 'selected' : '' }}>
                                                                Roundoff Down</option>
                                                            <option value="nearest"
                                                                {{ old('round_off_type', $settings->round_off_type ?? 'up') == 'nearest' ? 'selected' : '' }}>
                                                                Round to Nearest</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @error('round_off_type')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Show Company Details -->
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Show Company Details</h6>
                                                <p class="fs-13 mb-0">Show/hide company details in invoice</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="show_company_details" role="switch"
                                                        id="show_company_details"
                                                        {{ old('show_company_details', $settings->show_company_details ?? true) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Invoice Footer Terms -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Invoice Footer Terms</h6>
                                                <p class="fs-13 mb-0">Enter terms that will appear on All Invoices by
                                                    default.</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <textarea name="invoice_terms" class="form-control @error('invoice_terms') is-invalid @enderror" rows="4"
                                                    placeholder="Enter default invoice terms...">{{ old('invoice_terms', $settings->invoice_terms ?? '') }}</textarea>
                                                @error('invoice_terms')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted fs-12">Supports basic HTML tags</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-primary" id="saveBtn">
                                        <span class="spinner-border spinner-border-sm d-none" role="status"
                                            id="saveSpinner"></span>
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div>
    </div>

    {{-- Image Preview & Removal Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image Preview
            const imageInput = document.getElementById('imag');
            const imagePreview = document.getElementById('ImgPreview');
            const removeImageBtn = document.getElementById('removeImage1');
            const removeImageHidden = document.getElementById('remove_invoice_image');
            const defaultImage = '{{ asset('assets/img/profiles/avatar-02.jpg') }}';

            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.match('image.*')) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            imagePreview.src = event.target.result;
                            imagePreview.style.display = 'block';
                            removeImageHidden.value = '0'; // Reset removal flag
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Remove Image
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    imagePreview.src = defaultImage;
                    if (imageInput) imageInput.value = '';
                    removeImageHidden.value = '1'; // Mark for removal
                });
            }

            // Round Off Toggle Enable/Disable
            const roundOffSwitch = document.getElementById('enable_round_off');
            const roundOffSelect = document.getElementById('round_off_type');

            if (roundOffSwitch && roundOffSelect) {
                roundOffSwitch.addEventListener('change', function() {
                    roundOffSelect.disabled = !this.checked;
                });
            }

            // Form Submit Loading State
            const form = document.getElementById('invoiceSettingsForm');
            const saveBtn = document.getElementById('saveBtn');
            const saveSpinner = document.getElementById('saveSpinner');

            if (form) {
                form.addEventListener('submit', function() {
                    saveBtn.disabled = true;
                    saveSpinner.classList.remove('d-none');
                });
            }
        });
    </script>
@endsection
