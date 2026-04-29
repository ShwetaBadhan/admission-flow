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

                        <!-- ===== TAX RATES SECTION ===== -->
                        <div class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h4 class="fs-17 mb-0">Tax Rate</h4>
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_tax">
                                <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Tax Rate
                            </a>
                        </div>

                        <!-- Search for Tax Rates -->
                        <form method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search_rates" class="form-control" 
                                       placeholder="Search tax rates..." 
                                       value="{{ request('search_rates') }}">
                                <button type="submit" class="btn btn-outline-secondary"><i class="ti ti-search"></i></button>
                                @if(request('search_rates'))
                                    <a href="{{ route('tax-rate-settings.index') }}" class="btn btn-outline-secondary">Clear</a>
                                @endif
                            </div>
                        </form>

                        <!-- Tax Rates Table -->
                        <div class="table-responsive custom-table mb-4">
                            <table class="table table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Tax Rate</th>
                                        <th>Created On</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($taxRates as $rate)
                                        <tr>
                                            <td>{{ $rate->name }}</td>
                                            <td>{{ number_format($rate->rate, 2) }}%</td>
                                            <td>{{ $rate->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge {{ $rate->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown table-action">
                                                    <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        {{-- Edit Trigger --}}
                                                        <a class="dropdown-item edit-rate-trigger" href="#" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#edit_tax"
                                                           data-id="{{ $rate->id }}"
                                                           data-name="{{ $rate->name }}"
                                                           data-rate="{{ $rate->rate }}"
                                                           data-is-active="{{ $rate->is_active ? '1' : '0' }}">
                                                            <i class="ti ti-edit text-blue me-1"></i>Edit
                                                        </a>
                                                        {{-- Delete Trigger --}}
                                                        <a class="dropdown-item delete-rate-trigger" href="#"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#delete_tax"
                                                           data-id="{{ $rate->id }}"
                                                           data-name="{{ $rate->name }}">
                                                            <i class="ti ti-trash text-danger me-1"></i>Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No tax rates found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- ===== TAX GROUPS SECTION ===== -->
                        <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h4 class="fs-17 mb-0">Tax Group</h4>
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_tax_group">
                                <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Group
                            </a>
                        </div>

                        <!-- Search for Tax Groups -->
                        <form method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search_groups" class="form-control" 
                                       placeholder="Search tax groups..." 
                                       value="{{ request('search_groups') }}">
                                <button type="submit" class="btn btn-outline-secondary"><i class="ti ti-search"></i></button>
                                @if(request('search_groups'))
                                    <a href="{{ route('tax-rate-settings.index') }}" class="btn btn-outline-secondary">Clear</a>
                                @endif
                            </div>
                        </form>

                        <!-- Tax Groups Table -->
                        <div class="table-responsive custom-table">
                            <table class="table table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Tax Rate</th>
                                        <th>Created On</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($taxGroups as $group)
                                        <tr>
                                            <td>{{ $group->name }}</td>
                                            <td>
                                                {{-- Show individual rates or total --}}
                                               @foreach($group->taxRates as $rate)  
    <span class="badge bg-light text-dark me-1">
        {{ $rate->name }}: {{ $rate->formattedRate }}
    </span>
@endforeach

{{-- Show total if multiple rates --}}
@if($group->taxRates->count() > 1)
    <br><small class="text-muted">Total: {{ $group->formattedTotalRate }}</small>
@endif
                                            </td>
                                            <td>{{ $group->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge {{ $group->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $group->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown table-action">
                                                    <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        {{-- Edit Trigger --}}
                                                        <a class="dropdown-item edit-group-trigger" href="#" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#edit_tax_group"
                                                           data-id="{{ $group->id }}"
                                                           data-name="{{ $group->name }}"
                                                           data-sub-taxes='@json($group->sub_taxes ?? [])'
                                                           data-is-active="{{ $group->is_active ? '1' : '0' }}">
                                                            <i class="ti ti-edit text-blue me-1"></i>Edit
                                                        </a>
                                                        {{-- Delete Trigger --}}
                                                        <a class="dropdown-item delete-group-trigger" href="#"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#delete_tax_group"
                                                           data-id="{{ $group->id }}"
                                                           data-name="{{ $group->name }}">
                                                            <i class="ti ti-trash text-danger me-1"></i>Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No tax groups found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
</div>

<!-- ============ ADD TAX RATE MODAL ============ -->
<div class="modal fade" id="add_tax" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('tax-rate-settings.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Tax Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tax Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="e.g., VAT, GST, CGST">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="rate" class="form-control @error('rate') is-invalid @enderror" 
                                   value="{{ old('rate') }}" required min="0" max="100" step="0.01" placeholder="e.g., 18.50">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Enter value between 0 and 100</small>
                    </div>
                    <div class="mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="add_rate_active" value="1" checked>
                            <label class="form-check-label" for="add_rate_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Tax Rate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ EDIT TAX RATE MODAL ============ -->
<div class="modal fade" id="edit_tax" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editRateForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_rate_id">
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tax Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tax Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               id="edit_rate_name" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="rate" class="form-control @error('rate') is-invalid @enderror" 
                                   id="edit_rate_value" required min="0" max="100" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_rate_active" value="1">
                            <label class="form-check-label" for="edit_rate_active">Active</label>
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

<!-- ============ DELETE TAX RATE MODAL ============ -->
<div class="modal fade" id="delete_tax" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form method="POST" id="deleteRateForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="delete_rate_id">
                
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle">
                            <i class="ti ti-trash fs-24"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Delete Confirmation</h5>
                    <p class="mb-3">Are you sure you want to delete <strong id="delete_rate_name"></strong>?</p>
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

<!-- ============ ADD TAX GROUP MODAL ============ -->
<div class="modal fade" id="add_tax_group" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('tax-groups.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Tax Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="e.g., GST, Sales Tax">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Sub Taxes <span class="text-danger">*</span></label>
                        <select name="sub_taxes[]" class="form-select @error('sub_taxes') is-invalid @enderror" 
                                multiple required size="{{ min($allTaxRates->count(), 5) }}">
                            @forelse($allTaxRates as $rate)
                                <option value="{{ $rate->id }}" {{ in_array($rate->id, old('sub_taxes', [])) ? 'selected' : '' }}>
                                    {{ $rate->name }} ({{ number_format($rate->rate, 2) }}%)
                                </option>
                            @empty
                                <option value="" disabled>No tax rates available. Create one first.</option>
                            @endforelse
                        </select>
                        @error('sub_taxes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple tax rates</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Tax Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ EDIT TAX GROUP MODAL ============ -->
<div class="modal fade" id="edit_tax_group" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editGroupForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_group_id">
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tax Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               id="edit_group_name" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sub Taxes <span class="text-danger">*</span></label>
                        <select name="sub_taxes[]" class="form-select @error('sub_taxes') is-invalid @enderror" 
                                id="edit_group_taxes" multiple required size="{{ min($allTaxRates->count(), 5) }}">
                            @foreach($allTaxRates as $rate)
                                <option value="{{ $rate->id }}">
                                    {{ $rate->name }} ({{ number_format($rate->rate, 2) }}%)
                                </option>
                            @endforeach
                        </select>
                        @error('sub_taxes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple tax rates</small>
                    </div>
                    <div class="mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_group_active" value="1">
                            <label class="form-check-label" for="edit_group_active">Active</label>
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

<!-- ============ DELETE TAX GROUP MODAL ============ -->
<div class="modal fade" id="delete_tax_group" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form method="POST" id="deleteGroupForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="delete_group_id">
                
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle">
                            <i class="ti ti-trash fs-24"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Delete Confirmation</h5>
                    <p class="mb-3">Are you sure you want to delete <strong id="delete_group_name"></strong>?</p>
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
    
    // ===== Edit Tax Rate Modal =====
    const editRateModal = document.getElementById('edit_tax');
    if (editRateModal) {
        editRateModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            document.getElementById('edit_rate_id').value = button.dataset.id;
            document.getElementById('edit_rate_name').value = button.dataset.name || '';
            document.getElementById('edit_rate_value').value = button.dataset.rate || '';
            document.getElementById('edit_rate_active').checked = button.dataset.isActive === '1';
            
            // Update form action
            const form = document.getElementById('editRateForm');
            form.action = "{{ route('tax-rate-settings.update', ':id') }}".replace(':id', button.dataset.id);
        });
    }
    
    // ===== Delete Tax Rate Modal =====
    const deleteRateModal = document.getElementById('delete_tax');
    if (deleteRateModal) {
        deleteRateModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            document.getElementById('delete_rate_id').value = button.dataset.id;
            document.getElementById('delete_rate_name').textContent = button.dataset.name || 'this tax rate';
            
            // Update form action
            const form = document.getElementById('deleteRateForm');
            form.action = "{{ route('tax-rate-settings.destroy', ':id') }}".replace(':id', button.dataset.id);
        });
    }
    
    // ===== Edit Tax Group Modal =====
    const editGroupModal = document.getElementById('edit_tax_group');
    if (editGroupModal) {
        editGroupModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const select = document.getElementById('edit_group_taxes');
            
            document.getElementById('edit_group_id').value = button.dataset.id;
            document.getElementById('edit_group_name').value = button.dataset.name || '';
            document.getElementById('edit_group_active').checked = button.dataset.isActive === '1';
            
            // Parse and select sub_taxes
            const subTaxes = JSON.parse(button.dataset.subTaxes || '[]');
            Array.from(select.options).forEach(option => {
                option.selected = subTaxes.includes(parseInt(option.value));
            });
            
            // Update form action
            const form = document.getElementById('editGroupForm');
            form.action = "{{ route('tax-groups.update', ':id') }}".replace(':id', button.dataset.id);
        });
    }
    
    // ===== Delete Tax Group Modal =====
    const deleteGroupModal = document.getElementById('delete_tax_group');
    if (deleteGroupModal) {
        deleteGroupModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            document.getElementById('delete_group_id').value = button.dataset.id;
            document.getElementById('delete_group_name').textContent = button.dataset.name || 'this tax group';
            
            // Update form action
            const form = document.getElementById('deleteGroupForm');
            form.action = "{{ route('tax-groups.destroy', ':id') }}".replace(':id', button.dataset.id);
        });
    }
});
</script>

@endsection