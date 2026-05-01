<!-- Revenue Analytics Chart -->
<div class="col-xxl-8 col-xl-7 d-flex">
    <div class="card flex-fill">
        <div class="card-body pb-0">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center">
                    <span class="line-title d-block me-2"></span>Admission Analytics
                </h5>

            </div>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h4 class="mb-0">{{ number_format($totalAdmissions) }}</h4>
                    <p class="mb-0">Total Admissions (All Time)</p>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center border rounded px-2 py-1">
                        <p class="d-flex align-items-center mb-0">
                            <i class="ti ti-circle-filled fs-8 text-primary me-1"></i>Admissions
                        </p>
                    </div>
                </div>
            </div>
            <div id="performance-stats" style="height: 300px;"></div>
        </div>
    </div>
</div>

<!-- Lead Sources Chart -->
<div class="col-xxl-4 col-xl-5 d-flex">
    <div class="card flex-fill">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-0">
                <h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center">
                    <span class="line-title d-block me-2"></span>Lead Sources
                </h5>
                <a href="{{ route('leads.index') }}" class="btn btn-sm btn-icon btn-outline-light">
                    <i class="ti ti-arrow-right"></i>
                </a>
            </div>
            <div id="traffic-sources-chart" style="height: 250px;"></div>
        </div>
        <div class="mb-1">
            @foreach ($leadSources as $index => $source)
                @php
                    $colors = [
                        'text-success',
                        'text-info',
                        'text-warning',
                        'text-purple',
                        'text-danger',
                        'text-primary',
                    ];
                    $color = $colors[$index % count($colors)];
                @endphp
                <div
                    class="px-3 py-2 d-flex align-items-center justify-content-between {{ $index < count($leadSources) - 1 ? 'border-bottom' : '' }}">
                    <p class="text-dark d-flex align-items-center mb-0">
                        <i class="ti ti-circle-filled {{ $color }} fs-8 me-1"></i>
                        {{ $source->name }}
                    </p>
                    <p class="text-dark fw-semibold mb-0">{{ $source->count }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Revenue/Admission Analytics Chart
        var performanceOptions = {
            series: [{
                name: 'Admissions',
                data: @json($weeklyRevenue['values'])
            }],
            chart: {
                height: 300,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2,
                colors: ['#0d6efd']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            xaxis: {
                categories: @json($weeklyRevenue['labels']),
                labels: {
                    show: true
                }
            },
            yaxis: {
                labels: {
                    show: true
                },
                tickAmount: 5
            },
            colors: ['#0d6efd'],
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 4
            }
        };

        var performanceChart = new ApexCharts(document.querySelector("#performance-stats"), performanceOptions);
        performanceChart.render();

        // Lead Sources Pie Chart
        var trafficOptions = {
            series: @json($leadSources->pluck('count')),
            chart: {
                type: 'donut',
                height: 250
            },
            labels: @json($leadSources->pluck('name')),
            colors: ['#28a745', '#17a2b8', '#ffc107', '#6f42c1', '#dc3545', '#0d6efd'],
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
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
            }
        };

        var trafficChart = new ApexCharts(document.querySelector("#traffic-sources-chart"), trafficOptions);
        trafficChart.render();
    </script>
@endpush
