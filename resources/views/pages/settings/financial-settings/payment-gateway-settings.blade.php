@extends('layout.master')
@section('content')

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
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"><i class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                @include('components.settings-header')
            </div>
        </div>
        
        <!-- start row -->
        <div class="row">
            <div class="col-xl-3 col-lg-12 theiaStickySidebar">
                @include('components.financial-settings-sidebar')
            </div>

            <div class="col-xl-9 col-lg-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h4 class="fs-17 mb-0">Payment Gateways</h4>
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                                <i class="ti ti-plus me-1"></i>Add Gateway
                            </a>
                        </div>

                        {{-- Alert Messages --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                
                                @forelse($paymentMethods as $method)
                                    @php
                                        // Decrypt keys for display (only in forms, never in JS)
                                        $decryptedApiKey = $method->api_key ? Crypt::decryptString($method->api_key) : null;
                                        $decryptedSecretKey = $method->secret_key ? Crypt::decryptString($method->secret_key) : null;
                                    @endphp
                                    
                                    <!-- Payment Method Card -->
                                    <div class="border rounded shadow p-3 mb-3">
                                        <div class="row gy-3">
                                            <div class="col-sm-5">
                                                <div class="d-flex align-items-center">
                                                    <span>
                                                        <img src="{{ $method->logo ? Storage::url($method->logo) : asset('assets/img/payments/payment-' . ($loop->iteration % 10 + 1) . '.svg') }}" 
                                                             alt="{{ $method->name }}" 
                                                             style="width: 40px; height: 40px; object-fit: contain;">
                                                    </span>
                                                    <div class="ms-2">
                                                        <h6 class="fs-14 fw-medium mb-1">{{ $method->name }}</h6>
                                                        <span class="badge {{ $method->is_connected ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                                            {{ $method->is_connected ? 'Connected' : 'Not Connected' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="d-flex align-items-center">
                                                        <a href="javascript:void(0);"
                                                           data-bs-toggle="collapse"
                                                           data-bs-target="#desc-{{ $method->id }}"
                                                           class="text-default me-3 border-end pe-3 fs-16">
                                                            <i class="ti ti-info-circle-filled"></i>
                                                        </a>
                                                        <a href="#" 
                                                           class="btn btn-light"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#editPaymentModal"
                                                           data-id="{{ $method->id }}"
                                                           data-name="{{ $method->name }}"
                                                           data-slug="{{ $method->slug }}"
                                                           data-email="{{ $method->email }}"
                                                           data-api-key="{{ $decryptedApiKey }}"
                                                           data-secret-key="{{ $decryptedSecretKey }}"
                                                           data-logo="{{ $method->logo ? Storage::url($method->logo) : '' }}"
                                                           data-is-active="{{ $method->is_active ? '1' : '0' }}">
                                                            <i class="ti ti-tool me-1"></i>View Integration
                                                        </a>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        {{-- Toggle Active Switch --}}
                                                        <form action="{{ route('payment-gateway-settings.toggle', $method->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="form-check form-switch p-0 mb-0">
                                                                <input class="form-check-input ms-0" 
                                                                       type="checkbox" 
                                                                       role="switch" 
                                                                       name="is_active"
                                                                       {{ $method->is_active ? 'checked' : '' }}
                                                                       onchange="this.form.submit()">
                                                            </div>
                                                        </form>
                                                        
                                                        {{-- Delete Button --}}
                                                        <form action="{{ route('payment-gateway-settings.destroy', $method->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete {{ $method->name }}?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-icon btn-outline-danger btn-sm">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse pt-3 mt-3 border-top" id="desc-{{ $method->id }}">
                                            <p class="mb-0 fs-13 text-muted">{{ $method->description ?? 'Configure your ' . $method->name . ' payment gateway credentials.' }}</p>
                                        </div>
                                    </div>
                                    <!-- /Payment Method Card -->
                                    
                                @empty
                                    <div class="text-center py-5">
                                        <i class="ti ti-wallet fs-1 text-muted"></i>
                                        <p class="mt-3 text-muted">No payment gateways configured yet.</p>
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                                            <i class="ti ti-plus me-1"></i>Add First Gateway
                                        </a>
                                    </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
</div>

<!-- ============ ADD PAYMENT MODAL ============ -->
<div class="modal fade" id="addPaymentModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('payment-gateway-settings.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Gateway</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Gateway Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="e.g., PayPal Pro">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug') }}" required placeholder="e.g., paypal-pro" 
                               pattern="[a-z0-9\-]+" title="Use lowercase letters, numbers, and hyphens only">
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Unique identifier (lowercase, no spaces)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Recommended: 250x100px PNG/SVG</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merchant Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="merchant@example.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">API Key <span class="text-danger">*</span></label>
                        <input type="password" name="api_key" class="form-control @error('api_key') is-invalid @enderror" 
                               value="{{ old('api_key') }}" required>
                        @error('api_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Secret Key <span class="text-danger">*</span></label>
                        <input type="password" name="secret_key" class="form-control @error('secret_key') is-invalid @enderror" 
                               value="{{ old('secret_key') }}" required>
                        @error('secret_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Gateway</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ EDIT PAYMENT MODAL (Dynamic) ============ -->
<div class="modal fade" id="editPaymentModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editPaymentForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_method_id">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Edit Payment Gateway</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Gateway Name</label>
                        <input type="text" class="form-control" id="edit_name" disabled>
                        <small class="text-muted">Name cannot be changed after creation</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" id="edit_slug" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Logo</label>
                        <div class="d-flex align-items-center gap-3">
                            <img id="edit_logo_preview" src="" alt="Logo" style="width: 60px; height: 60px; object-fit: contain;" class="border rounded">
                            <div>
                                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*" id="edit_logo_input">
                                @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Upload new to replace</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merchant Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               id="edit_email" placeholder="merchant@example.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">API Key</label>
                        <input type="password" name="api_key" class="form-control @error('api_key') is-invalid @enderror" 
                               id="edit_api_key" placeholder="•••••••• (leave blank to keep current)">
                        @error('api_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Leave blank to keep existing key</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Secret Key</label>
                        <input type="password" name="secret_key" class="form-control @error('secret_key') is-invalid @enderror" 
                               id="edit_secret_key" placeholder="•••••••• (leave blank to keep current)">
                        @error('secret_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Leave blank to keep existing key</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Gateway</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection
@push('scripts')
	{{-- Minimal JS for Dynamic Modal Population (No Loops, No Complex Logic) --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editPaymentModal');
    if (!editModal) return;
    
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        
        // Populate form fields from data attributes
        document.getElementById('edit_method_id').value = button.dataset.id;
        document.getElementById('editModalTitle').textContent = 'Edit ' + button.dataset.name;
        document.getElementById('edit_name').value = button.dataset.name;
        document.getElementById('edit_slug').value = button.dataset.slug;
        document.getElementById('edit_email').value = button.dataset.email || '';
        document.getElementById('edit_api_key').value = ''; // Never pre-fill decrypted keys
        document.getElementById('edit_secret_key').value = '';
        
        // Logo preview
        const logoPreview = document.getElementById('edit_logo_preview');
        if (button.dataset.logo) {
            logoPreview.src = button.dataset.logo;
            logoPreview.style.display = 'block';
        } else {
            logoPreview.style.display = 'none';
        }
        
        // Update form action
        const form = document.getElementById('editPaymentForm');
        form.action = "{{ route('payment-gateway-settings.update', ':id') }}".replace(':id', button.dataset.id);
    });
});
</script>
@endpush