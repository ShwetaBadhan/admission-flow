@extends('layout.master')
@section('content')
 {{-- ✅ SweetAlert Notifications --}}
@if(session('success'))
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

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: @json(implode('<br>', $errors->all())),
        confirmButtonText: 'Okay',
        customClass: { popup: 'swal-wide' }
    });
});
</script>
@endif

<!-- ========================
    Start Page Content
========================= -->
         
<div class="page-wrapper">
    <!-- Start Content -->
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
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
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
                @include('components.website-settings-sidebar')
            </div>

            <div class="col-xl-9 col-lg-12">
                <!-- Custom Fields -->
                <div class="card">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h4 class="fs-17 mb-0">Language</h4>
                            <div class="d-flex align-items-center gap-2">
                                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_lang">
                                    <i class="ti ti-square-rounded-plus-filled me-1"></i>Add Language
                                </a>
                            </div>
                        </div>

                        <!-- Contact List -->
                        <div class="table-responsive custom-table mb-4">
                            <table class="table table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Language</th>
                                        <th>Code</th>
                                        <th>RTL</th>
                                        <th>Default</th>
                                        <th>Status</th>
                                        <th>Platforms</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($languages as $language)
                                    <tr id="language-row-{{ $language->id }}">
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($language->flag)
                                                    <img src="{{ Storage::url($language->flag) }}" alt="{{ $language->name }}" height="16" class="rounded">
                                                @else
                                                    <span class="avatar avatar-xs bg-light text-dark">{{ substr($language->name, 0, 1) }}</span>
                                                @endif
                                                <span>{{ $language->name }}</span>
                                                @if($language->is_default)
                                                    <span class="badge bg-primary badge-sm">Default</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td><code>{{ $language->code }}</code></td>
                                        <td>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input toggle-rtl ms-auto" type="checkbox" 
                                                       data-id="{{ $language->id }}" 
                                                       {{ $language->is_rtl ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            @if(!$language->is_default)
                                            <button class="btn btn-sm btn-outline-primary set-default" data-id="{{ $language->id }}">
                                                Set Default
                                            </button>
                                            @else
                                            <span class="badge bg-success">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input toggle-status ms-auto" type="checkbox" 
                                                       data-id="{{ $language->id }}" 
                                                       {{ $language->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input toggle-platform" type="checkbox" 
                                                           data-id="{{ $language->id }}" 
                                                           data-platform="web"
                                                           {{ $language->web_enabled ? 'checked' : '' }}>
                                                    <label class="form-check-label small">Web</label>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input toggle-platform" type="checkbox" 
                                                           data-id="{{ $language->id }}" 
                                                           data-platform="app"
                                                           {{ $language->app_enabled ? 'checked' : '' }}>
                                                    <label class="form-check-label small">App</label>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input toggle-platform" type="checkbox" 
                                                           data-id="{{ $language->id }}" 
                                                           data-platform="admin"
                                                           {{ $language->admin_enabled ? 'checked' : '' }}>
                                                    <label class="form-check-label small">Admin</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-flex align-items-center gap-2">
                                            <button class="btn btn-xs btn-icon btn-outline-light edit-language" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#edit_lang"
                                                    data-id="{{ $language->id }}"
                                                    data-name="{{ $language->name }}"
                                                    data-code="{{ $language->code }}"
                                                    data-flag="{{ $language->flag }}"
                                                    data-rtl="{{ $language->is_rtl ? '1' : '0' }}"
                                                    data-default="{{ $language->is_default ? '1' : '0' }}"
                                                    data-active="{{ $language->is_active ? '1' : '0' }}"
                                                    data-web="{{ $language->web_enabled ? '1' : '0' }}"
                                                    data-app="{{ $language->app_enabled ? '1' : '0' }}"
                                                    data-admin="{{ $language->admin_enabled ? '1' : '0' }}">
                                                <i class="ti ti-edit text-blue"></i>
                                            </button>
                                            <button class="btn btn-xs btn-icon btn-outline-light delete-language" 
                                                    data-id="{{ $language->id }}"
                                                    data-name="{{ $language->name }}">
                                                <i class="ti ti-trash text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No languages found. Add your first language!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /Contact List -->
                    </div>
                </div>
                <!-- /Custom Fields -->
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- End Content -->
</div>
<!-- ========================
    End Page Content
========================= -->

<!-- Add Language Modal -->
<div class="modal fade" id="add_lang" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('language-settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Language</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Language Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., English" required value="{{ old('name') }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Language Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="e.g., en" required value="{{ old('code') }}" maxlength="10">
                        <small class="text-muted">ISO 639-1 code (2 letters)</small>
                        @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Flag Image</label>
                        <input type="file" name="flag" class="form-control" accept="image/svg+xml,image/png,image/jpeg,image/jpg">
                        <small class="text-muted">SVG, PNG, JPG (max 2MB)</small>
                        @error('flag') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Settings</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_rtl" id="add_rtl" value="1" {{ old('is_rtl') ? 'checked' : '' }}>
                                <label class="form-check-label" for="add_rtl">Right-to-Left (RTL)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" id="add_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="add_default">Set as Default Language</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="add_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="add_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Enable On Platforms</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="web_enabled" id="add_web" value="1" {{ old('web_enabled', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="add_web">Web</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="app_enabled" id="add_app" value="1" {{ old('app_enabled', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="add_app">Mobile App</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="admin_enabled" id="add_admin" value="1" {{ old('admin_enabled', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="add_admin">Admin Panel</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save Language</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Add Language Modal -->

<!-- Edit Language Modal -->
<div class="modal fade" id="edit_lang" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="edit-language-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Language</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_language_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Language Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" placeholder="e.g., English" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Language Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="edit_code" class="form-control" placeholder="e.g., en" required maxlength="10">
                        <small class="text-muted">ISO 639-1 code (2 letters)</small>
                        @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Flag Image</label>
                        <input type="file" name="flag" class="form-control" accept="image/svg+xml,image/png,image/jpeg,image/jpg">
                        <small class="text-muted">SVG, PNG, JPG (max 2MB)</small>
                        <div id="edit_flag_preview" class="mt-2"></div>
                        @error('flag') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Settings</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_rtl" id="edit_rtl" value="1">
                                <label class="form-check-label" for="edit_rtl">Right-to-Left (RTL)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" id="edit_default" value="1">
                                <label class="form-check-label" for="edit_default">Set as Default Language</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_active" value="1">
                                <label class="form-check-label" for="edit_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Enable On Platforms</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="web_enabled" id="edit_web" value="1">
                                <label class="form-check-label" for="edit_web">Web</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="app_enabled" id="edit_app" value="1">
                                <label class="form-check-label" for="edit_app">Mobile App</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="admin_enabled" id="edit_admin" value="1">
                                <label class="form-check-label" for="edit_admin">Admin Panel</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Update Language</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Language Modal -->

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete_lang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form id="delete-language-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle">
                            <i class="ti ti-trash fs-24"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Delete Confirmation</h5>
                    <p class="mb-3">Are you sure you want to delete <strong id="delete_language_name"></strong>? This action cannot be undone.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-sm btn-light w-100" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-danger w-100">Yes, Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Delete Confirmation Modal -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== EDIT LANGUAGE MODAL =====
    document.querySelectorAll('.edit-language').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const form = document.getElementById('edit-language-form');
            const action = "{{ route('language-settings.update', ':id') }}".replace(':id', id);
            
            form.action = action;
            document.getElementById('edit_language_id').value = id;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_code').value = this.dataset.code;
            document.getElementById('edit_rtl').checked = this.dataset.rtl === '1';
            document.getElementById('edit_default').checked = this.dataset.default === '1';
            document.getElementById('edit_active').checked = this.dataset.active === '1';
            document.getElementById('edit_web').checked = this.dataset.web === '1';
            document.getElementById('edit_app').checked = this.dataset.app === '1';
            document.getElementById('edit_admin').checked = this.dataset.admin === '1';
            
            // Preview existing flag
            const flagPreview = document.getElementById('edit_flag_preview');
            if (this.dataset.flag) {
                flagPreview.innerHTML = `<img src="{{ Storage::url('') }}${this.dataset.flag}" alt="Current flag" height="40" class="rounded">`;
            } else {
                flagPreview.innerHTML = '';
            }
        });
    });

    // ===== DELETE LANGUAGE MODAL =====
    document.querySelectorAll('.delete-language').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const form = document.getElementById('delete-language-form');
            const action = "{{ route('language-settings.destroy', ':id') }}".replace(':id', id);
            
            form.action = action;
            document.getElementById('delete_language_name').textContent = name;
        });
    });

    // ===== AJAX TOGGLES =====
    
    // Toggle Status
    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const checked = this.checked;
            
            fetch(`/language-settings/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    this.checked = !checked;
                    Swal.fire('Error', 'Failed to update status', 'error');
                }
            })
            .catch(error => {
                this.checked = !checked;
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong', 'error');
            });
        });
    });

    // Toggle RTL
    document.querySelectorAll('.toggle-rtl').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const checked = this.checked;
            
            fetch(`/language-settings/${id}/toggle-rtl`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    this.checked = !checked;
                    Swal.fire('Error', 'Failed to update RTL setting', 'error');
                }
            })
            .catch(error => {
                this.checked = !checked;
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong', 'error');
            });
        });
    });

    // Set as Default
    document.querySelectorAll('.set-default').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            
            if (!confirm('Are you sure you want to set this as the default language?')) {
                return;
            }
            
            fetch(`/language-settings/${id}/set-default`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    Swal.fire('Error', data.message || 'Failed to set default language', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong', 'error');
            });
        });
    });

    // Toggle Platform
    document.querySelectorAll('.toggle-platform').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const platform = this.dataset.platform;
            const checked = this.checked;
            
            fetch(`/language-settings/${id}/toggle-platform/${platform}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    this.checked = !checked;
                    Swal.fire('Error', data.message || 'Failed to update platform setting', 'error');
                }
            })
            .catch(error => {
                this.checked = !checked;
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong', 'error');
            });
        });
    });
});
</script>
@endpush