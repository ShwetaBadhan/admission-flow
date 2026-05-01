 <div class="col-md-12 d-flex">
     <div class="card flex-fill">
         <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
             <h6 class="mb-0">Consultants</h6>
            
         </div>
         <div class="card-body">
             <div class="table-responsive custom-table">
                 <table class="table dataTable table-nowrap">
                     <thead class="table-light">
                         <tr>
                             <th>Name</th>
                             <th>Email</th>
                             <th>Status</th>
                         </tr>
                     </thead>
                     <tbody>
                         @forelse($consultants as $consultant)
                             <tr>
                                 <td>
                                     <a href="{{ $consultant->id ? route('consultants.show', $consultant->id) : '#' }}"
                                         class="fw-medium text-dark">
                                         {{ $consultant->name ?? ($consultant->full_name ?? 'N/A') }}
                                     </a>
                                 </td>
                                 <td class="fw-medium">{{ $consultant->email ?? 'N/A' }}</td>
                                 <td>
                                     @php
                                         $isActive = (int) $consultant->status === 1;
                                         $badgeClass = $isActive ? 'bg-success' : 'bg-danger';
                                         $statusLabel = $isActive ? 'Active' : 'Inactive';
                                     @endphp
                                     <span class="badge badge-pill {{ $badgeClass }}">{{ $statusLabel }}</span>
                                 </td>
                             </tr>
                         @empty
                             <tr>
                                 <td colspan="3" class="text-center py-4 text-muted">
                                     <i class="ti ti-user-off fs-1 d-block mb-2"></i>
                                     No consultants found
                                 </td>
                             </tr>
                         @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
     </div> <!-- end card -->
 </div> <!-- end col -->
