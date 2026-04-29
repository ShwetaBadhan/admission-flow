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
                            <h4 class="fs-17 mb-0">Currencies</h4>
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_currency">
                                <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Currency
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

                        {{-- Search Form --}}
                        <form method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search currencies..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary"><i class="ti ti-search"></i></button>
                                @if(request('search'))
                                    <a href="{{ route('currency-settings.index') }}" class="btn btn-outline-secondary">Clear</a>
                                @endif
                            </div>
                        </form>

                        <!-- Start Table -->
                        <div class="table-responsive custom-table">
                            <table class="table table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Currency</th>
                                        <th>Code</th>
                                        <th>Symbol</th>
                                        <th>Exchange Rate</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($currencies as $currency)
                                        <tr>
                                            <td>
                                                {{ $currency->name }}
                                                @if($currency->is_default)
                                                    <a href="javascript:void(0);" class="badge badge-tag badge-soft-info ms-2" title="Default Currency">
                                                        <i class="ti ti-star-filled me-1"></i>Default
                                                    </a>
                                                @endif
                                            </td>
                                            <td><code class="fs-13">{{ $currency->code }}</code></td>
                                            <td><span class="fs-16">{{ $currency->symbol }}</span></td>
                                            <td>{{ number_format($currency->exchange_rate, 2) }}</td>
                                            <td>
                                                <span class="badge {{ $currency->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $currency->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown table-action">
                                                    <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        {{-- Set Default --}}
                                                        @if(!$currency->is_default)
                                                            <form action="{{ route('currency-settings.default', $currency->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="ti ti-star text-warning me-1"></i>Set as Default
                                                                </button>
                                                            </form>
                                                        @endif
                                                        
                                                        {{-- Edit Trigger --}}
                                                        <a class="dropdown-item edit-currency-trigger" href="#" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#edit_currency"
                                                           data-id="{{ $currency->id }}"
                                                           data-name="{{ $currency->name }}"
                                                           data-code="{{ $currency->code }}"
                                                           data-symbol="{{ $currency->symbol }}"
                                                           data-rate="{{ $currency->exchange_rate }}"
                                                           data-is-default="{{ $currency->is_default ? '1' : '0' }}"
                                                           data-is-active="{{ $currency->is_active ? '1' : '0' }}">
                                                            <i class="ti ti-edit text-blue me-1"></i>Edit
                                                        </a>
                                                        
                                                        {{-- Delete Trigger --}}
                                                        @if(!$currency->is_default)
                                                            <a class="dropdown-item delete-currency-trigger" href="#"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#delete_currency"
                                                               data-id="{{ $currency->id }}"
                                                               data-name="{{ $currency->name }} ({{ $currency->code }})">
                                                                <i class="ti ti-trash text-danger me-1"></i>Delete
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                No currencies found. <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_currency">Add one now</a>.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table -->

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
</div>

<!-- ============ ADD CURRENCY MODAL ============ -->
<div class="modal fade" id="add_currency" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('currency-settings.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Currency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Currency Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="e.g., US Dollar, Indian Rupee">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Exchange Rate <span class="text-danger">*</span></label>
                        <input type="number" name="exchange_rate" class="form-control @error('exchange_rate') is-invalid @enderror" 
                               value="{{ old('exchange_rate') }}" required min="0.000001" step="0.000001" 
                               placeholder="Rate against base currency (e.g., 86.62)">
                        @error('exchange_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Base currency = 1.000000</small>
                    </div>
                    <div class="d-flex flex-lg-row flex-column align-items-center justify-content-between gap-3 mb-3">
                        <div class="w-100">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   value="{{ old('code') }}" required maxlength="3" pattern="[A-Z]{3}" 
                                   placeholder="USD" style="text-transform: uppercase;">
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">3-letter ISO code</small>
                        </div>
                        <div class="w-100">
                            <label class="form-label">Symbol <span class="text-danger">*</span></label>
                            <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" 
                                   value="{{ old('symbol') }}" required maxlength="10" placeholder="$">
                            @error('symbol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="form-label mb-0">Make as Default</label>
                        <div class="form-check form-switch p-0">
                            <input class="form-check-input ms-auto" type="checkbox" role="switch" 
                                   name="is_default" id="add_is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Currency</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ EDIT CURRENCY MODAL ============ -->
<div class="modal fade" id="edit_currency" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editCurrencyForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_currency_id">
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Currency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Currency Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               id="edit_currency_name" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Exchange Rate <span class="text-danger">*</span></label>
                        <input type="number" name="exchange_rate" class="form-control @error('exchange_rate') is-invalid @enderror" 
                               id="edit_currency_rate" required min="0.000001" step="0.000001">
                        @error('exchange_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="d-flex flex-lg-row flex-column align-items-center justify-content-between gap-3 mb-3">
                        <div class="w-100">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   id="edit_currency_code" required maxlength="3" pattern="[A-Z]{3}" style="text-transform: uppercase;">
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="w-100">
                            <label class="form-label">Symbol <span class="text-danger">*</span></label>
                            <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" 
                                   id="edit_currency_symbol" required maxlength="10">
                            @error('symbol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" id="edit_is_default" value="1">
                            <label class="form-check-label" for="edit_is_default">Set as Default Currency</label>
                        </div>
                        <small class="text-muted">Only one currency can be default at a time</small>
                    </div>
                    <div class="mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ DELETE CONFIRMATION MODAL ============ -->
<div class="modal fade" id="delete_currency" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form method="POST" id="deleteCurrencyForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="delete_currency_id">
                
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle">
                            <i class="ti ti-trash fs-24"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Delete Confirmation</h5>
                    <p class="mb-3">Are you sure you want to delete <strong id="delete_currency_name"></strong>?</p>
                    <small class="text-muted d-block mb-3">This action cannot be undone.</small>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Minimal JS for Dynamic Modal Population --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ===== Edit Currency Modal =====
    const editModal = document.getElementById('edit_currency');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            document.getElementById('edit_currency_id').value = button.dataset.id;
            document.getElementById('edit_currency_name').value = button.dataset.name || '';
            document.getElementById('edit_currency_code').value = button.dataset.code || '';
            document.getElementById('edit_currency_symbol').value = button.dataset.symbol || '';
            document.getElementById('edit_currency_rate').value = button.dataset.rate || '';
            document.getElementById('edit_is_default').checked = button.dataset.isDefault === '1';
            document.getElementById('edit_is_active').checked = button.dataset.isActive === '1';
            
            // Disable default checkbox if already default
            const isDefault = button.dataset.isDefault === '1';
            document.getElementById('edit_is_default').disabled = isDefault;
            
            // Update form action
            const form = document.getElementById('editCurrencyForm');
            form.action = "{{ route('currency-settings.update', ':id') }}".replace(':id', button.dataset.id);
        });
    }
    
    // ===== Delete Currency Modal =====
    const deleteModal = document.getElementById('delete_currency');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            document.getElementById('delete_currency_id').value = button.dataset.id;
            document.getElementById('delete_currency_name').textContent = button.dataset.name || 'this currency';
            
            // Update form action
            const form = document.getElementById('deleteCurrencyForm');
            form.action = "{{ route('currency-settings.destroy', ':id') }}".replace(':id', button.dataset.id);
        });
    }
    
    // ===== Auto-uppercase code input =====
    const codeInputs = document.querySelectorAll('input[name="code"]');
    codeInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
});
</script>

@endsection