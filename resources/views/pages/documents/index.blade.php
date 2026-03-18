@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content pb-0">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Documents</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- card start -->
        <div class="card border-0 rounded-0">
            <div class="card-body">

                <!-- table header -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <!-- Sort Dropdown -->
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown">
                                <i class="ti ti-sort-ascending-2 me-2"></i>Sort By
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ route('documents.index', array_merge(request()->query(), ['sort' => 'created_at', 'order' => 'desc'])) }}" 
                                   class="dropdown-item {{ request('sort') == 'created_at' ? 'active' : '' }}">
                                    Newest
                                </a>
                                <a href="{{ route('documents.index', array_merge(request()->query(), ['sort' => 'created_at', 'order' => 'asc'])) }}" 
                                   class="dropdown-item {{ request('sort') == 'created_at' && request('order') == 'asc' ? 'active' : '' }}">
                                    Oldest
                                </a>
                                <a href="{{ route('documents.index', array_merge(request()->query(), ['sort' => 'file_name', 'order' => 'asc'])) }}" 
                                   class="dropdown-item {{ request('sort') == 'file_name' ? 'active' : '' }}">
                                    Name (A-Z)
                                </a>
                            </div>
                        </div>

                        <!-- Date Range Picker -->
                        <form method="GET" action="{{ route('documents.index') }}" class="d-flex align-items-center gap-2" id="dateFilterForm">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="order" value="{{ request('order') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            
                            <div id="reportrange" class="reportrange-picker d-flex align-items-center shadow cursor-pointer" style="cursor: pointer;">
                                <i class="ti ti-calendar-due text-dark fs-14 me-1"></i>
                                <span class="reportrange-picker-field">
                                    {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M, Y') : '9 Jun 25' }} - 
                                    {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M, Y') : '9 Jun 25' }}
                                </span>
                            </div>
                            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </form>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <!-- Search -->
                        <form method="GET" action="{{ route('documents.index') }}" class="d-flex">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="order" value="{{ request('order') }}">
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       placeholder="Search documents..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Filter Dropdown -->
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="ti ti-filter me-2"></i>Filter<i class="ti ti-chevron-down ms-2"></i>
                            </a>
                            <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                                <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                    <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filter</h6>
                                    <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown" aria-label="Close"></button>
                                </div>
                                <div class="filter-set-view p-3">
                                    <form method="GET" action="{{ route('documents.index') }}" id="filterForm">
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                        <input type="hidden" name="order" value="{{ request('order') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                        
                                        <div class="accordion" id="filterAccordion">
                                            <!-- Status Filter -->
                                            <div class="filter-set-content">
                                                <div class="filter-set-content-head">
                                                    <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#filterStatus" aria-expanded="false">
                                                        Status
                                                    </a>
                                                </div>
                                                <div class="filter-set-contents accordion-collapse collapse" id="filterStatus" data-bs-parent="#filterAccordion">
                                                    <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                        <ul>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center">
                                                                    <input class="form-check-input m-0 me-1" type="radio" name="status" value="" 
                                                                           {{ !request('status') ? 'checked' : '' }}> All
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center">
                                                                    <input class="form-check-input m-0 me-1" type="radio" name="status" value="pending" 
                                                                           {{ request('status') == 'pending' ? 'checked' : '' }}> Pending
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center">
                                                                    <input class="form-check-input m-0 me-1" type="radio" name="status" value="verified" 
                                                                           {{ request('status') == 'verified' ? 'checked' : '' }}> Verified
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center">
                                                                    <input class="form-check-input m-0 me-1" type="radio" name="status" value="rejected" 
                                                                           {{ request('status') == 'rejected' ? 'checked' : '' }}> Rejected
                                                                </label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center gap-2 mt-3">
                                            <a href="{{ route('documents.index') }}" class="btn btn-outline-light w-100">Reset</a>
                                            <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Manage Columns (Optional - can be removed if not needed) -->
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn bg-soft-indigo px-2 border-0" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="ti ti-columns-3 me-2"></i>Manage Columns
                            </a>
                            <div class="dropdown-menu dropdown-menu-md dropdown-md p-3">
                                <ul>
                                    <li class="gap-1 d-flex align-items-center mb-2">       
                                        <i class="ti ti-columns me-1"></i>                                     
                                        <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                            <span>File Name</span>   
                                            <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked disabled>     
                                        </label>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">       
                                        <i class="ti ti-columns me-1"></i>                                     
                                        <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                            <span>Type</span>   
                                            <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked disabled>     
                                        </label>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">       
                                        <i class="ti ti-columns me-1"></i>                                     
                                        <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                            <span>Lead</span>   
                                            <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>     
                                        </label>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">       
                                        <i class="ti ti-columns me-1"></i>                                     
                                        <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                            <span>Size</span>   
                                            <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>     
                                        </label>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">       
                                        <i class="ti ti-columns me-1"></i>                                     
                                        <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                            <span>Uploaded By</span>   
                                            <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>     
                                        </label>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">       
                                        <i class="ti ti-columns me-1"></i>                                     
                                        <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                            <span>Uploaded Date</span>   
                                            <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>     
                                        </label>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-2">       
                                        <i class="ti ti-columns me-1"></i>                                     
                                        <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                            <span>Status</span>   
                                            <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked>     
                                        </label>
                                    </li>
                                    <li class="gap-1 d-flex align-items-center mb-0">       
                                        <i class="ti ti-columns me-1"></i>                                     
                                        <label class="form-check-label d-flex align-items-center gap-2 w-100">
                                            <span>Action</span>   
                                            <input class="form-check-input switchCheckDefault ms-auto" type="checkbox" role="switch" checked disabled>     
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /table header -->

              <!-- Documents Table -->
<div class="table-responsive table-nowrap custom-table">
    <table class="table table-nowrap">
        <thead class="table-light">
            <tr>
                <th class="no-sort">
                    <div class="form-check form-check-md">
                        <input class="form-check-input" type="checkbox" id="select-all">
                    </div>
                </th>
                <th>Document</th>
                <th>File</th>
                <th>Status</th>
                <th>Verified By</th>
                <th>Uploaded</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $doc)
                <tr>
                    <td>
                        <div class="form-check form-check-md">
                            <input class="form-check-input" type="checkbox" name="document_ids[]" value="{{ $doc->id }}">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-soft-primary rounded-circle me-2">
                                <i class="ti ti-file-text text-primary"></i>
                            </div>
                            <span class="fw-medium"> {{ $doc->documentSetting?->name ?? ucfirst($doc->document_type) }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="text-dark">{{ $doc->file_name }}</span>
                    </td>
                    <td>
                        @php
                            $verified = $doc->is_verified;
                        @endphp
                        
                        @if($verified == 1 || $verified === true)
                            <span class="badge badge-pill bg-soft-success text-success border-0">
                                <i class="ti ti-check me-1"></i>verified
                            </span>
                        @elseif($verified == 0 || $verified === false)
                            <span class="badge badge-pill bg-soft-danger text-danger border-0">
                                <i class="ti ti-x me-1"></i>rejected
                            </span>
                        @else
                            <span class="badge badge-pill bg-soft-warning text-warning border-0">
                                <i class="ti ti-clock me-1"></i>pending
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($doc->is_verified == 1 || $doc->is_verified === true)
                            <span class="text-dark">{{ $doc->uploaded_by ?? '—' }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted">{{ \Carbon\Carbon::parse($doc->created_at)->diffForHumans() }}</span>
                    </td>
                    <td class="text-end">
                        @if($doc->is_verified === null)
                            <!-- Pending: Show Verify and Reject buttons -->
                            <div class="d-inline-flex align-items-center gap-2">
                                <form action="{{ route('leads.verify-document', [$doc->lead, $doc]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-success p-0" 
                                            onclick="return confirm('Verify this document?')">
                                        Verify
                                    </button>
                                </form>
                                <span class="text-muted">|</span>
                                <form action="{{ route('leads.reject-document', [$doc->lead, $doc]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"
                                            onclick="return confirm('Reject this document?')">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Verified/Rejected: Show View button -->
                            <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn btn-sm btn-link text-dark p-0">
                                <i class="ti ti-external-link me-1"></i>View
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="avatar avatar-lg bg-soft-secondary rounded-circle mb-3 mx-auto">
                            <i class="ti ti-file text-secondary fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-1">No documents found</h6>
                        <p class="text-muted mb-0">Upload documents from individual lead pages.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

                <!-- Pagination -->
                @if($documents->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $documents->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>
        <!-- /card end -->

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All Checkbox
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('input[name="document_ids[]"]');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
    
    // Date Range Picker (if using flatpickr)
    const reportRange = document.getElementById('reportrange');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const dateFilterForm = document.getElementById('dateFilterForm');
    
    if (reportRange && window.flatpickr) {
        flatpickr(reportRange, {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: [
                "{{ request('start_date') ?? now()->subDays(30)->format('Y-m-d') }}",
                "{{ request('end_date') ?? now()->format('Y-m-d') }}"
            ],
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    startDateInput.value = selectedDates[0].toISOString().split('T')[0];
                    endDateInput.value = selectedDates[1].toISOString().split('T')[0];
                    dateFilterForm.submit();
                }
            }
        });
    }
    
    // Filter form auto-submit on radio change
    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endpush