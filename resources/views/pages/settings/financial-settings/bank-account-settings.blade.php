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
                            <h4 class="fs-17 mb-0">Bank Accounts</h4>
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_bank">
                                <i class="ti ti-square-rounded-plus-filled me-1"></i>Add New Account
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

                        <div class="row row-gap-3">
                            @forelse($bankAccounts as $account)
                                <!-- Bank Account Card -->
                                <div class="col-xxl-4 col-sm-6">
                                    <div class="position-relative">
                                        {{-- Radio for selecting default --}}
                                        <form action="{{ route('bank-account-settings.toggle', $account->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="radio" name="is_default" value="1" 
                                                   class="bank-radio position-absolute top-0 end-0 m-3 opacity-0" 
                                                   id="bank_{{ $account->id }}"
                                                   {{ $account->is_default ? 'checked' : '' }}
                                                   onchange="this.form.submit()">
                                        </form>
                                        
                                        <label for="bank_{{ $account->id }}" class="bank-box card border-0 shadow-sm mb-0 cursor-pointer" style="min-height: 180px;">
                                            <div class="card-body">
                                                @if($account->is_default)
                                                    <span class="badge badge-soft-primary position-absolute top-0 end-0 m-2">Default</span>
                                                @endif
                                                <div class="mb-4">
                                                    <h5 class="fw-bold mb-1 fs-16">{{ $account->bank_name }}</h5>
                                                    <p class="mb-0 fs-14 text-muted">**** **** {{ substr($account->account_number, -4) }}</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h6 class="fw-semibold mb-1 fs-14">Holder Name</h6>
                                                        <p class="fs-13">{{ $account->account_holder_name }}</p>
                                                    </div>

                                                    <div class="dropdown table-action position-relative z-1">
                                                        <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            {{-- Edit Trigger --}}
                                                            <a class="dropdown-item edit-bank-trigger" href="#" 
                                                               data-bs-toggle="modal" 
                                                               data-bs-target="#edit_bank"
                                                               data-id="{{ $account->id }}"
                                                               data-bank-name="{{ $account->bank_name }}"
                                                               data-holder-name="{{ $account->account_holder_name }}"
                                                               data-account-number="{{ $account->account_number }}"
                                                               data-branch-name="{{ $account->branch_name }}"
                                                               data-aba-number="{{ $account->aba_number }}"
                                                               data-is-active="{{ $account->is_active ? '1' : '0' }}">
                                                                <i class="ti ti-edit text-blue me-1"></i>Edit
                                                            </a>
                                                            {{-- Delete Trigger --}}
                                                            <a class="dropdown-item delete-bank-trigger" href="#"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#delete_bank"
                                                               data-id="{{ $account->id }}"
                                                               data-name="{{ $account->bank_name }} (****{{ substr($account->account_number, -4) }})">
                                                                <i class="ti ti-trash text-danger me-1"></i>Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <!-- /Bank Account Card -->
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="ti ti-building-bank fs-1 text-muted"></i>
                                    <p class="mt-3 text-muted">No bank accounts added yet.</p>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_bank">
                                        <i class="ti ti-square-rounded-plus-filled me-1"></i>Add First Account
                                    </a>
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        @if($bankAccounts->hasPages())
                            <div class="mt-4">
                                {{ $bankAccounts->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div>
</div>

<!-- ============ ADD BANK MODAL ============ -->
<div class="modal fade" id="add_bank" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('bank-account-settings.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Bank Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <select name="bank_name" class="form-select @error('bank_name') is-invalid @enderror" required>
                            <option value="">Select Bank</option>
                            <option value="HDFC" {{ old('bank_name') == 'HDFC' ? 'selected' : '' }}>HDFC Bank</option>
                            <option value="SBI" {{ old('bank_name') == 'SBI' ? 'selected' : '' }}>State Bank of India</option>
                            <option value="ICICI" {{ old('bank_name') == 'ICICI' ? 'selected' : '' }}>ICICI Bank</option>
                            <option value="KVB" {{ old('bank_name') == 'KVB' ? 'selected' : '' }}>Karur Vysya Bank</option>
                            <option value="AXIS" {{ old('bank_name') == 'AXIS' ? 'selected' : '' }}>Axis Bank</option>
                            <option value="OTHER" {{ old('bank_name') == 'OTHER' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                        <input type="text" name="account_holder_name" class="form-control @error('account_holder_name') is-invalid @enderror" 
                               value="{{ old('account_holder_name') }}" required>
                        @error('account_holder_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                               value="{{ old('account_number') }}" required maxlength="50" placeholder="Enter full account number">
                        @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror" 
                               value="{{ old('branch_name') }}" required>
                        @error('branch_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ABA/Routing Number</label>
                        <input type="text" name="aba_number" class="form-control @error('aba_number') is-invalid @enderror" 
                               value="{{ old('aba_number') }}" maxlength="20" placeholder="Optional">
                        @error('aba_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" id="add_is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="add_is_default">Set as Default Account</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ EDIT BANK MODAL ============ -->
<div class="modal fade" id="edit_bank" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editBankForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_account_id">
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bank Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                        <select name="bank_name" class="form-select @error('bank_name') is-invalid @enderror" id="edit_bank_name" required>
                            <option value="">Select Bank</option>
                            <option value="HDFC">HDFC Bank</option>
                            <option value="SBI">State Bank of India</option>
                            <option value="ICICI">ICICI Bank</option>
                            <option value="KVB">Karur Vysya Bank</option>
                            <option value="AXIS">Axis Bank</option>
                            <option value="OTHER">Other</option>
                        </select>
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                        <input type="text" name="account_holder_name" class="form-control @error('account_holder_name') is-invalid @enderror" 
                               id="edit_holder_name" required>
                        @error('account_holder_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" 
                               id="edit_account_number" required maxlength="50">
                        @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror" 
                               id="edit_branch_name" required>
                        @error('branch_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ABA/Routing Number</label>
                        <input type="text" name="aba_number" class="form-control @error('aba_number') is-invalid @enderror" 
                               id="edit_aba_number" maxlength="20">
                        @error('aba_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" id="edit_is_default" value="1">
                            <label class="form-check-label" for="edit_is_default">Set as Default Account</label>
                        </div>
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
                    <button type="submit" class="btn btn-primary">Update Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============ DELETE CONFIRMATION MODAL ============ -->
<div class="modal fade" id="delete_bank" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form method="POST" id="deleteBankForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="delete_account_id">
                
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle">
                            <i class="ti ti-trash fs-24"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Delete Confirmation</h5>
                    <p class="mb-3">Are you sure you want to delete <strong id="delete_account_name"></strong>?</p>
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
    // Edit Modal Population
    const editModal = document.getElementById('edit_bank');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            document.getElementById('edit_account_id').value = button.dataset.id;
            document.getElementById('edit_bank_name').value = button.dataset.bankName || '';
            document.getElementById('edit_holder_name').value = button.dataset.holderName || '';
            document.getElementById('edit_account_number').value = button.dataset.accountNumber || '';
            document.getElementById('edit_branch_name').value = button.dataset.branchName || '';
            document.getElementById('edit_aba_number').value = button.dataset.abaNumber || '';
            document.getElementById('edit_is_active').checked = button.dataset.isActive === '1';
            // Note: is_default checkbox handled separately if needed
            
            // Update form action
            const form = document.getElementById('editBankForm');
            form.action = "{{ route('bank-account-settings.update', ':id') }}".replace(':id', button.dataset.id);
        });
    }
    
    // Delete Modal Population
    const deleteModal = document.getElementById('delete_bank');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            document.getElementById('delete_account_id').value = button.dataset.id;
            document.getElementById('delete_account_name').textContent = button.dataset.name || 'this account';
            
            // Update form action
            const form = document.getElementById('deleteBankForm');
            form.action = "{{ route('bank-account-settings.destroy', ':id') }}".replace(':id', button.dataset.id);
        });
    }
});
</script>

@endsection