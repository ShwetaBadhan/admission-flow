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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Admission Requests</li>
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
                                <a href="{{ route('admissions.index', array_merge(request()->query(), ['sort' => 'created_at', 'order' => 'desc'])) }}" 
                                   class="dropdown-item {{ request('sort') == 'created_at' && request('order') == 'desc' ? 'active' : '' }}">
                                    Newest
                                </a>
                                <a href="{{ route('admissions.index', array_merge(request()->query(), ['sort' => 'created_at', 'order' => 'asc'])) }}" 
                                   class="dropdown-item {{ request('sort') == 'created_at' && request('order') == 'asc' ? 'active' : '' }}">
                                    Oldest
                                </a>
                            </div>
                        </div>

                        <!-- Date Range Picker -->
                        <form method="GET" action="{{ route('admissions.index') }}" class="d-flex align-items-center gap-2" id="dateFilterForm">
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
                        <form method="GET" action="{{ route('admissions.index') }}" class="d-flex">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="order" value="{{ request('order') }}">
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       placeholder="Search..." value="{{ request('search') }}">
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
                                    <form method="GET" action="{{ route('admissions.index') }}" id="filterForm">
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
                                                            @foreach(['draft', 'submitted', 'under_review', 'accepted', 'rejected', 'withdrawn'] as $status)
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center">
                                                                    <input class="form-check-input m-0 me-1" type="radio" name="status" value="{{ $status }}" 
                                                                           {{ request('status') == $status ? 'checked' : '' }}> 
                                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                                </label>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center gap-2 mt-3">
                                            <a href="{{ route('admissions.index') }}" class="btn btn-outline-light w-100">Reset</a>
                                            <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /table header -->

                <!-- Admissions Table -->
                <div class="table-responsive table-nowrap custom-table">
                    <table class="table table-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th class="no-sort">
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </div>
                                </th>
                                <th>Student</th>
                                <th>College</th>
                                <th>Course</th>
                                <th>Counselor</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissions as $admission)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" name="admission_ids[]" value="{{ $admission->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        @if($admission->lead)
                                            <a href="{{ route('lead-details', $admission->lead) }}" class="text-decoration-none fw-medium">
                                                {{ $admission->lead->full_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($admission->college)
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-building me-1 text-muted"></i>
                                                <span>{{ $admission->college->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $admission->course->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $admission->submittedBy->name ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $admission->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                    @can('update-admission-status')
                                        <!-- Status Dropdown -->
                                        <div class="dropdown">
                                            <span class="badge bg-{{
                                                $admission->status == 'accepted' ? 'success' :
                                                ($admission->status == 'rejected' ? 'danger' :
                                                ($admission->status == 'under_review' ? 'warning' :
                                                ($admission->status == 'submitted' ? 'info' :
                                                ($admission->status == 'withdrawn' ? 'secondary' : 'light')))
                                            ) }} dropdown-toggle px-3 py-2" 
                                            data-bs-toggle="dropdown" style="cursor: pointer;">
                                                {{ ucfirst(str_replace('_', ' ', $admission->status)) }}
                                            </span>
                                            <div class="dropdown-menu">
                                                @foreach(['draft', 'submitted', 'under_review', 'accepted', 'rejected', 'withdrawn'] as $status)
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                       onclick="confirmStatusUpdate({{ $admission->id }}, '{{ $status }}')">
                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden Form -->
                                        <form id="status-form-{{ $admission->id }}"
                                              action="{{ route('admissions.update-status', $admission) }}"
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" id="status-input-{{ $admission->id }}">
                                        </form>  
                                    @endcan
                                      
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('lead-details', $admission->lead) }}" class="btn btn-sm btn-link text-primary p-0" title="View Lead">
                                            <i class="ti ti-eye me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="avatar avatar-lg bg-soft-secondary rounded-circle mb-3 mx-auto">
                                            <i class="ti ti-file-text text-secondary fs-24"></i>
                                        </div>
                                        <h6 class="fw-semibold mb-1">No admission requests found</h6>
                                        <p class="text-muted mb-0">Admission requests will appear here once created.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($admissions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $admissions->links('vendor.pagination.bootstrap-5') }}
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
// Select All Checkbox
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('input[name="admission_ids[]"]');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
    
    // Date Range Picker
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

// Status Update Confirmation
function confirmStatusUpdate(id, newStatus) {
    const statusText = newStatus.replace('_', ' ');
    Swal.fire({
        title: 'Update Status?',
        text: `Are you sure you want to set status to "${statusText}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Update',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('status-input-' + id).value = newStatus;
            document.getElementById('status-form-' + id).submit();
        }
    });
}
</script>
@endpush