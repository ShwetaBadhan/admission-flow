<!-- Recently Created Leads Table -->
<div class="col-xl-6 d-flex">
    <div class="card flex-fill">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <h6 class="mb-0">Recently Created Leads</h6>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive custom-table">
                <table class="table table-nowrap dataTable" id="lead-project">
                    <thead class="table-light">
                        <tr>
                            <th>Lead Name</th>
                            <th>Company Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLeads as $lead)
                            <tr>
                                <td><a href="{{ route('leads.index', $lead->id) }}">{{ $lead->full_name ?? 'N/A' }}</a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="#" class="avatar avatar-rounded border">
                                            <img class="w-auto h-auto"
                                                src="{{ url('assets/img/icons/company-icon-01.svg') }}"
                                                alt="User Image">
                                        </a>
                                        <div class="ms-2">
                                            <h6 class="fs-14 fw-medium mb-0"><a href="#"
                                                    class="d-flex flex-column">{{ $lead->email ?? 'No Company' }}</a>
                                            </h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $lead->mobile ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match (strtolower($lead->status ?? '')) {
                                            'won' => 'bg-success',
                                            'lost' => 'bg-danger',
                                            'new' => 'bg-info',
                                            'contacted', 'negotiation' => 'bg-warning',
                                            'qualified', 'proposal' => 'bg-primary',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span
                                        class="badge badge-pill {{ $badgeClass }}">{{ ucfirst($lead->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">No recent leads found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Projects By Stage Chart -->
<div class="col-xl-6 d-flex">
    <div class="card flex-fill">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <h6 class="mb-0">Leads By Stage</h6>
                
            </div>
        </div>
        <div class="card-body">
            <div id="pieleadchart" class="text-center"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Safe variable injection
            var labels = @json($statusLabels ?? []);
            var counts = @json($statusCounts ?? []);

            if (labels.length === 0 || counts.length === 0) {
                console.warn('Lead status data is empty. Check controller compact() variables.');
                return;
            }

            var leadStatusOptions = {
                series: counts,
                chart: {
                    type: 'donut',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                labels: labels,
                colors: ['#0d6efd', '#198754', '#ffc107', '#0dcaf0', '#6f42c1', '#fd7e14', '#dc3545'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Leads',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 0
                }
            };

            var pieleadchart = new ApexCharts(document.querySelector("#pieleadchart"), leadStatusOptions);
            pieleadchart.render();
        });
    </script>
@endpush
