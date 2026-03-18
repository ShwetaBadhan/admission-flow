 <div class="card">
                                <div
                                    class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                    <h5 class="fw-semibold mb-0">Notes</h5>
                                    <div class="d-inline-flex align-items-center">
                                        <div class="dropdown me-2">
                                            <a href="javascript:void(0);"
                                                class="dropdown-toggle btn btn-outline-light px-2 shadow"
                                                data-bs-toggle="dropdown"><i class="ti ti-sort-ascending-2 me-2"></i>Sort
                                                By</a>
                                            <div class="dropdown-menu">
                                                <ul>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item">Newest</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item">Oldest</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_notes"
                                            class="link-primary fw-medium"><i class="ti ti-circle-plus me-1"></i>Add
                                            New</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @forelse($lead->communications->where('type', 'note')->take(5) as $note)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 pb-2">
                                                    <div class="d-inline-flex align-items-center mb-2">
                                                        <span class="avatar avatar-md me-2 flex-shrink-0">
                                                            <img src="{{ asset('assets/img/profiles/avatar-19.jpg') }}"
                                                                alt="img">
                                                        </span>
                                                        <div>
                                                            <h6 class="fw-medium fs-14 mb-1">
                                                                {{ $note->createdBy->name ?? 'System' }}</h6>
                                                            <p class="mb-0 fs-13">
                                                                {{ $note->created_at->format('d M Y, h:i A') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <div class="dropdown">
                                                            <a href="#"
                                                                class="action-icon btn btn-icon btn-sm btn-outline-light shadow"
                                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                    class="ti ti-dots-vertical"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="javascript:void(0);"
                                                                    data-bs-toggle="modal" data-bs-target="#edit_notes"><i
                                                                        class="ti ti-edit me-1"></i>Edit</a>
                                                                <form
                                                                    action="{{ route('leads.delete-communication', $note) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="dropdown-item"
                                                                        onclick="return confirm('Delete?')"><i
                                                                            class="ti ti-trash me-1"></i>Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="mb-3">{{ Str::limit($note->content, 200) }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">No notes yet.</p>
                                    @endforelse
                                </div>
                            </div>



                              <!-- Add Note Modal -->
    <div class="modal fade" id="add_notes" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Notes</h5>
                    <button type="button" class="btn-close custom-btn-close border p-1 me-0 text-dark"
                        data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('leads.add-communication', $lead) }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="note">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger"> *</span></label>
                            <input class="form-control" type="text" name="subject" placeholder="Add Title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Note <span class="text-danger"> *</span></label>
                            <textarea class="form-control" name="content" rows="4" placeholder="Add Description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-light" data-bs-dismiss="modal">Cancel</a>
                        <button class="btn btn-primary" type="submit">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Add Note -->