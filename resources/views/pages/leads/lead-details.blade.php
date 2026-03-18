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
    <!-- ========================
            Start Page Content
            ========================= -->
    <div class="page-wrapper">
        <!-- Start Content -->
        <div class="content pb-0">
            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">Leads</a></li>
                            <li class="breadcrumb-item active">{{ $lead->full_name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('leads.index') }}"><i class="ti ti-arrow-narrow-left me-1"></i>Back to Leads</a>
                    </div>
                    <div class="card">
                        <div class="card-body pb-2">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center mb-2">
                                    <div
                                        class="avatar avatar-xxl avatar-rounded border border-{{ $statuses[$lead->status]['color'] ?? 'warning' }} bg-soft-{{ $statuses[$lead->status]['color'] ?? 'warning' }} me-3 flex-shrink-0">
                                        <h6 class="mb-0 text-{{ $statuses[$lead->status]['color'] ?? 'warning' }}">
                                            {{ strtoupper(substr($lead->full_name, 0, 2)) }}
                                        </h6>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $lead->full_name }}
                                            @if ($lead->priority && $lead->priority->level >= 3)
                                                <i class="ti ti-star-filled text-warning"></i>
                                            @endif
                                        </h5>
                                        <p class="mb-1"><i
                                                class="ti ti-building-skyscraper me-1"></i>{{ $lead->course?->name ?? 'N/A' }}
                                        </p>
                                        <p class="mb-0"><i
                                                class="ti ti-map-pin-pin me-1"></i>{{ $lead->city?->name ?? 'N/A' }},
                                            {{ $lead->state?->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="py-1 px-2 fs-12 bg-soft-danger rounded text-danger fw-medium"><i
                                            class="ti ti-lock me-1"></i>Private</span>
                                    <div class="dropdown">
                                        <form action="{{ route('leads.update-stage', $lead) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="button"
                                                class="btn btn-xs btn-{{ $statuses[$lead->status]['color'] ?? 'success' }} fs-12 py-1 px-2 fw-medium d-inline-flex align-items-center"
                                                data-bs-toggle="dropdown">
                                                <i
                                                    class="ti ti-{{ $lead->status == 'won' ? 'thumb-up' : ($lead->status == 'lost' ? 'thumb-down' : 'clock') }} me-1"></i>
                                                {{ ucfirst($lead->status) }}
                                                <i class="ti ti-chevron-down ms-1"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @foreach ($stages as $stage)
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="this.closest('form').querySelector('input[name=stage]').value='{{ strtolower($stage->name) }}'; this.closest('form').submit();">
                                                        <span>{{ ucfirst($stage->name) }}</span>
                                                    </a>
                                                @endforeach
                                                <input type="hidden" name="stage"
                                                    value="{{ strtolower($lead->status) }}">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Contact User -->
                </div>
                <!-- Contact Sidebar -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <h6 class="mb-3 fw-semibold">Lead Information</h6>
                            <div class="border-bottom mb-3 pb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <p class="mb-0">Date Created</p>
                                    <p class="mb-0 text-dark">{{ $lead->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                                @if ($lead->qualification)
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <p class="mb-0">Qualification</p>
                                        <p class="mb-0 text-dark">{{ $lead->qualification->name }}</p>
                                    </div>
                                @endif
                                @if ($lead->course)
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <p class="mb-0">Interested Course</p>
                                        <p class="mb-0 text-dark">{{ $lead->course->name }}</p>
                                    </div>
                                @endif
                                @if ($lead->intake)
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <p class="mb-0">Preferred Intake</p>
                                        <p class="mb-0 text-dark">{{ $lead->intake->name }}</p>
                                    </div>
                                @endif
                                @if ($lead->leadSource)
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <p class="mb-0">Source</p>
                                        <p class="mb-0 text-dark">{{ $lead->leadSource->name }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Consultant Assignment Section -->
                            <h6 class="mb-3 fw-semibold">Assign Consultant</h6>
                            <div class="border-bottom mb-3 pb-3">
                                <form action="{{ route('leads.assign-consultant', $lead) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label fs-13">Select Consultant</label>
                                        <select name="consultant_id" class="select">
                                            <option value="">Select Consultant</option>
                                            @foreach ($consultants as $consultant)
                                                <option value="{{ $consultant->id }}"
                                                    {{ $lead->consultant_id == $consultant->id ? 'selected' : '' }}>
                                                    {{ $consultant->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        <i class="ti ti-user-plus me-1"></i>Assign Consultant
                                    </button>
                                </form>

                                @if ($lead->consultant)
                                    <div class="mt-2 p-2 bg-soft-primary rounded">
                                        <small class="text-muted">Assigned to:</small><br>
                                        <strong>{{ $lead->consultant->name }}</strong>
                                    </div>
                                @endif
                            </div>
                            <!-- /Consultant Assignment Section -->

                            <h6 class="mb-3 fw-semibold">Priority</h6>
                            <div class="border-bottom mb-3 pb-3">
                                <form action="{{ route('leads.update', $lead) }}" method="POST">
                                    @csrf @method('PUT')
                                    <select name="priority_id" class="select" onchange="this.form.submit()">
                                        <option value="">Select Priority</option>
                                        @foreach ($priorities ?? [] as $priority)
                                            <option value="{{ $priority->id }}"
                                                {{ old('priority_id', $lead->priority_id) == $priority->id ? 'selected' : '' }}>
                                                {{ $priority->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>



                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <p class="mb-0">Last Modified</p>
                                <p class="mb-0 text-dark">{{ $lead->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-0">
                                <p class="mb-0">Modified By</p>
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-xs rounded-circle me-2">
                                        <img src="{{ asset('assets/img/users/avatar-2.jpg') }}" alt="Img"
                                            class="img-fluid rounded-circle w-auto h-auto">
                                    </span>
                                    <div>
                                        <p class="mb-0">{{ auth()->user()->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Contact Sidebar -->

                <!-- Contact Details -->
                <div class="col-xl-8">
                    <div class="mb-3 pb-3 border-bottom">
                        <h5 class="mb-3">Lead Pipeline Status</h5>
                        <div class="step-progress d-flex flex-wrap gap-2">
                            @php
                                // Get stage names in order (assuming ContactStage has 'order' column)
                                $stagesList = $stages->sortBy('order')->pluck('name')->toArray();

                                // Find current stage index by comparing NAME (not ID)
                                $currentIndex = array_search($lead->status, $stagesList);

                                // Colors for all stages (all will be colored)
                                $allStageColors = [
                                    'indigo',
                                    'cyan',
                                    'success',
                                    'orange',
                                    'danger',
                                    'purple',
                                    'warning',
                                ];
                            @endphp

                            @foreach ($stages->sortBy('order') as $index => $stage)
                                @php
                                    $baseColor = $allStageColors[$index] ?? 'secondary';
                                    $isActive = strtolower($stage->name) == strtolower($lead->status);
                                @endphp

                                <div
                                    class="step {{ $isActive ? 'bg-' . $baseColor . ' border-2 border-white shadow' : 'bg-soft-' . $baseColor . ' text-' . $baseColor }}">
                                    {{ ucfirst($stage->name) }}
                                    @if ($isActive)
                                        <i class="ti ti-check ms-1"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body pb-0 pt-2 px-2">
                            <ul class="nav nav-tabs nav-bordered border-0 mb-0" role="tablist">
                                {{-- <li class="nav-item" role="presentation">
                                    <a href="#tab_1" data-bs-toggle="tab" aria-expanded="false"
                                        class="nav-link active border-3" aria-selected="true" role="tab">
                                        <span class="d-md-inline-block"><i
                                                class="ti ti-alarm-minus me-1"></i>Activities</span>
                                    </a>
                                </li> --}}
                                {{-- <li class="nav-item" role="presentation">
                                    <a href="#tab_2" data-bs-toggle="tab" aria-expanded="true"
                                        class="nav-link border-3" aria-selected="false" role="tab" tabindex="-1">
                                        <span class="d-md-inline-block"><i class="ti ti-notes me-1"></i>Notes</span>
                                    </a>
                                </li> --}}
                                <li class="nav-item" role="presentation">
                                    <a href="#tab_3" data-bs-toggle="tab" aria-expanded="false"
                                        class="nav-link active border-3" aria-selected="false" tabindex="-1" role="tab">
                                        <span class="d-md-inline-block"><i class="ti ti-phone me-1"></i>Calls</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tab_4" data-bs-toggle="tab" aria-expanded="false"
                                        class="nav-link border-3" aria-selected="false" tabindex="-1" role="tab">
                                        <span class="d-md-inline-block"><i class="ti ti-file me-1"></i>Files</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tab_5" data-bs-toggle="tab" aria-expanded="false"
                                        class="nav-link border-3" aria-selected="false" tabindex="-1" role="tab">
                                        <span class="d-md-inline-block"><i class="ti ti-mail-check me-1"></i>Email & SMS</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tab_6" data-bs-toggle="tab" aria-expanded="false"
                                        class="nav-link border-3" aria-selected="false" tabindex="-1" role="tab">
                                        <span class="d-md-inline-block"><i class="ti ti-user me-1"></i>Admission
                                            Requests</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
    <div class="card-body">
    <div class="tab-content pt-0">
                        <!-- Activities -->
                        {{-- <div class="tab-pane active show" id="tab_1">
                           @include('pages.leads.partials.activities-tab')
                        </div> --}}
                        <!-- /Activities -->

                        <!-- Notes -->
                        {{-- <div class="tab-pane fade" id="tab_2">
                           @include('pages.leads.partials.notes')
                        </div> --}}
                        <!-- /Notes -->

                        <!-- Calls -->
                        <div class="tab-pane  active show" id="tab_3">
                            @include('pages.leads.partials.communications-tab')
                        </div>
                        <!-- /Calls -->

                        <!-- Files -->
                        <div class="tab-pane fade" id="tab_4">
                           @include('pages.leads.partials.documents-tab')
                        </div>
                        <!-- /Files -->

                        <!-- Email -->
                        <div class="tab-pane fade" id="tab_5">
                          @include('pages.leads.partials.email')
                        </div>
                        <!-- /Email -->
                        <!-- Admission Requests -->
                        <div class="tab-pane fade" id="tab_6">
                            <!-- Admission Requests Section -->
                            @include('pages.leads.partials.admission-request')
                        </div>
                        <!-- /Admission Requests -->
                   
                    </div>
    </div>
                    <!-- Tab Content -->
                    
                    </div>
                    <!-- /Tab Content -->
                </div>
                <!-- /Contact Details -->
            </div>
            <!-- Start Footer -->
        </div>
    </div>
    <!-- ========================
            End Page Content
            ========================= -->

  




@endsection

@push('scripts')
    <!-- JavaScript for Status Update -->
    <script>
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
