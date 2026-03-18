<div class="card mt-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Admission Requests</h6>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#new_admission_request">
                                        <i class="ti ti-plus me-1"></i>New Request
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    @if ($lead->admissionRequests && $lead->admissionRequests->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>College</th>
                                                        <th>Course</th>
                                                        <th>Intake</th>
                                                        <th>Status</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($lead->admissionRequests as $admission)
                                                        <tr>
                                                            <td>{{ $admission->college->name ?? 'N/A' }}</td>
                                                            <td>{{ $admission->course->name ?? 'N/A' }}</td>
                                                            <td>{{ $admission->intake_session }}</td>
                                                            <td>
                                                                <!-- Status Dropdown -->
                                                                <div class="dropdown table-action">
                                                                    <span
                                                                        class="badge bg-{{ $admission->status == 'accepted'
                                                                            ? 'success'
                                                                            : ($admission->status == 'rejected'
                                                                                ? 'danger'
                                                                                : ($admission->status == 'under_review'
                                                                                    ? 'warning'
                                                                                    : 'secondary')) }} dropdown-toggle"
                                                                        data-bs-placement="top" {{-- ✅ Add this line --}}
                                                                        data-bs-toggle="dropdown"
                                                                        style="cursor: pointer;">
                                                                        {{ ucfirst(str_replace('_', ' ', $admission->status)) }}
                                                                    </span>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        @foreach (['draft', 'submitted', 'under_review', 'accepted', 'rejected', 'withdrawn'] as $status)
                                                                            <a class="dropdown-item"
                                                                                href="javascript:void(0);"
                                                                                onclick="confirmStatusUpdate({{ $admission->id }}, '{{ $status }}')">
                                                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                                            </a>
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                <!-- Hidden Form -->
                                                                <form id="status-form-{{ $admission->id }}"
                                                                    action="{{ route('admission-requests.update-status', $admission) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT') <!-- ✅ Correct for PUT request -->
                                                                    <input type="hidden" name="status"
                                                                        id="status-input-{{ $admission->id }}">
                                                                </form>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center py-3 mb-0">No admission requests yet.</p>
                                    @endif
                                </div>
                            </div>


                            
    <!-- New Admission Request Modal -->
    <div class="modal fade" id="new_admission_request" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('leads.admission-request', $lead) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Admission Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">College <span class="text-danger">*</span></label>
                            <select name="college_id" class="select" required>
                                <option value="">Select college</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Course <span class="text-danger">*</span></label>
                            <select name="course_id" class="select" required>
                                <option value="">Select course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ $lead->course?->id == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Assign Counselor</label>
                            <select name="consultant_id" class="select">
                                <option value="">Select counselor (optional)</option>
                                @foreach ($consultants as $consultant)
                                    <option value="{{ $consultant->id }}"
                                        {{ $lead->consultant_id == $consultant->id ? 'selected' : '' }}>
                                        {{ $consultant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>