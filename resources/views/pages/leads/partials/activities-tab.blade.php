 <div class="card">
                                <div
                                    class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                    <h5 class="fw-semibold mb-0">Activities</h5>
                                    <div class="dropdown">
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
                                </div>
                                <div class="card-body">
                                    @forelse($lead->communications->take(10) as $comm)
                                        <div class="badge badge-soft-info border-0 mb-3"><i
                                                class="ti ti-calendar-check me-1"></i>{{ $comm->created_at->format('d M Y') }}
                                        </div>
                                        <div class="card border shadow-none mb-3">
                                            <div class="card-body p-3">
                                                <div class="d-flex flex-wrap row-gap-2">
                                                    <span
                                                        class="avatar avatar-md flex-shrink-0 rounded me-2 bg-{{ $comm->type == 'call' ? 'teal' : ($comm->type == 'email' ? 'info' : 'danger') }}">
                                                        <i
                                                            class="ti ti-{{ $comm->type == 'call' ? 'phone' : ($comm->type == 'email' ? 'mail-code' : 'notes') }} fs-20"></i>
                                                    </span>
                                                    <div>
                                                        <h6 class="fw-medium fs-14 mb-1">{{ ucfirst($comm->type) }} by
                                                            {{ $comm->createdBy->name ?? 'System' }}</h6>
                                                        <p class="mb-0">{{ $comm->created_at->format('h:i A') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">No activities yet.</p>
                                    @endforelse
                                </div>
                            </div>