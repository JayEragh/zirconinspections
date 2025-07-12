@extends('layouts.app')

@section('title', 'Report Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Report Details
                </h2>
                <div>
                    <a href="{{ route('client.reports') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Reports
                    </a>
                    @if($report->status === 'approved')
                    <a href="{{ route('client.reports.pdf', $report->id) }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>
                        Download PDF
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Report Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Report #:</strong> {{ $report->id }}</p>
                            <p><strong>Title:</strong> {{ $report->title }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge badge-{{ $report->status === 'approved' ? 'success' : ($report->status === 'submitted' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Created:</strong> {{ $report->created_at->format('M d, Y H:i') }}</p>
                            <p><strong>Updated:</strong> {{ $report->updated_at->format('M d, Y H:i') }}</p>
                            <p><strong>Inspector:</strong> {{ $report->inspector->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tabulation Section -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Inspection Data Tabulation</h6>
                </div>
                <div class="card-body">
                    @if($report->inspectionDataSets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Tank #</th>
                                        <th>Product Gauge</th>
                                        <th>Water Gauge</th>
                                        <th>Temperature (°C)</th>
                                        <th>Density</th>
                                        <th>VCF</th>
                                        <th>TOV</th>
                                        <th>Water Vol</th>
                                        <th>GOV</th>
                                        <th>GSV</th>
                                        <th>MT Air</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report->inspectionDataSets as $dataSet)
                                    <tr>
                                        <td>{{ $dataSet->inspection_date && is_object($dataSet->inspection_date) ? $dataSet->inspection_date->format('M d, Y') : ($dataSet->inspection_date ? $dataSet->inspection_date : 'N/A') }}</td>
                                        <td>{{ $dataSet->inspection_time && is_object($dataSet->inspection_time) ? $dataSet->inspection_time->format('H:i') : ($dataSet->inspection_time ? $dataSet->inspection_time : 'N/A') }}</td>
                                        <td>{{ $dataSet->tank_number }}</td>
                                        <td>{{ number_format($dataSet->product_gauge, 2) }}</td>
                                        <td>{{ number_format($dataSet->water_gauge, 2) }}</td>
                                        <td>{{ number_format($dataSet->temperature, 1) }}</td>
                                        <td>{{ number_format($dataSet->density, 4) }}</td>
                                        <td>{{ number_format($dataSet->vcf, 4) }}</td>
                                        <td>{{ number_format($dataSet->tov, 2) }}</td>
                                        <td>{{ number_format($dataSet->water_volume, 2) }}</td>
                                        <td>{{ number_format($dataSet->gov, 2) }}</td>
                                        <td>{{ number_format($dataSet->gsv, 2) }}</td>
                                        <td>{{ number_format($dataSet->mt_air, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No inspection data sets available for this report.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Time Series Charts -->
            @if($report->inspectionDataSets->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Time Series Analysis</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="text-center mb-3">Product Gauge vs Time</h6>
                            <canvas id="productGaugeChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="text-center mb-3">Temperature vs Time</h6>
                            <canvas id="temperatureChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="text-center mb-3">GSV vs Time</h6>
                            <canvas id="gsvChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="text-center mb-3">Water Volume vs Time</h6>
                            <canvas id="waterVolumeChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Report Content</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6><strong>Content:</strong></h6>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($report->content)) !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6><strong>Findings:</strong></h6>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($report->findings)) !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6><strong>Recommendations:</strong></h6>
                        <div class="bg-light p-3 rounded">
                            {!! nl2br(e($report->recommendations)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Inspection Data -->
            @if($report->inspection_date || $report->tank_number || $report->product_gauge)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Latest Inspection Data</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Inspection Date:</strong> {{ $report->inspection_date && is_object($report->inspection_date) ? $report->inspection_date->format('M d, Y') : ($report->inspection_date ? $report->inspection_date : 'N/A') }}</p>
                            <p><strong>Inspection Time:</strong> {{ $report->inspection_time && is_object($report->inspection_time) ? $report->inspection_time->format('H:i') : ($report->inspection_time ? $report->inspection_time : 'N/A') }}</p>
                            <p><strong>Tank Number:</strong> {{ $report->tank_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Product Gauge:</strong> {{ $report->product_gauge ?? 'N/A' }}</p>
                            <p><strong>H20 Gauge:</strong> {{ $report->water_gauge ?? 'N/A' }}</p>
                            <p><strong>Temperature:</strong> {{ $report->temperature ? $report->temperature . '°C' : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Roof:</strong> {{ $report->has_roof ? 'Yes' : 'No' }}</p>
                            @if($report->has_roof)
                                <p><strong>Roof Weight:</strong> {{ $report->roof_weight ?? 'N/A' }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Density (@ 20°C):</strong> {{ $report->density ?? 'N/A' }}</p>
                            <p><strong>VCF (ASTM 60 B):</strong> {{ $report->vcf ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>TOV:</strong> {{ $report->tov ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Water Volume:</strong> {{ $report->water_volume ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Roof Volume:</strong> {{ $report->roof_volume ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>GOV:</strong> {{ $report->gov ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>GSV:</strong> {{ $report->gsv ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>MT Air:</strong> {{ $report->mt_air ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($report->supporting_file)
                        <div class="mt-3">
                            <h6><strong>Supporting File:</strong></h6>
                            <p><a href="{{ asset('storage/reports/' . $report->supporting_file) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-download me-2"></i>
                                Download File
                            </a></p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Service Request Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Request Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Request #:</strong> {{ $report->serviceRequest->id }}</p>
                    <p><strong>Service ID:</strong> {{ $report->serviceRequest->service_id }}</p>
                    <p><strong>Service Type:</strong> {{ ucfirst($report->serviceRequest->service_type) }}</p>
                    <p><strong>Depot:</strong> {{ $report->serviceRequest->depot }}</p>
                    <p><strong>Product:</strong> {{ $report->serviceRequest->product }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $report->serviceRequest->status === 'completed' ? 'success' : ($report->serviceRequest->status === 'in_progress' ? 'warning' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $report->serviceRequest->status)) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Inspector Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Inspector Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $report->inspector->user->name }}</p>
                    <p><strong>Email:</strong> {{ $report->inspector->user->email }}</p>
                    <p><strong>Phone:</strong> {{ $report->inspector->phone }}</p>
                    <p><strong>Specialization:</strong> {{ $report->inspector->specialization }}</p>
                </div>
            </div>

            <!-- Approval Information -->
            @if($report->status === 'approved')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Approval Information</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Report Approved</strong><br>
                        Approved on {{ $report->approved_at->format('M d, Y H:i') }}
                    </div>
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        This report has been reviewed and approved by operations management.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Chart.js for Time Series Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($report->inspectionDataSets->count() > 0)
        // Use actual data from inspection data sets
        const dataSets = @json($report->inspectionDataSets);
        const dates = dataSets.map(ds => ds.inspection_date);
        const productGaugeData = dataSets.map(ds => ds.product_gauge);
        const temperatureData = dataSets.map(ds => ds.temperature);
        const gsvData = dataSets.map(ds => ds.gsv);
        const waterVolumeData = dataSets.map(ds => ds.water_volume);

        // Product Gauge Chart
        new Chart(document.getElementById('productGaugeChart'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Product Gauge (m)',
                    data: productGaugeData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        // Temperature Chart
        new Chart(document.getElementById('temperatureChart'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Temperature (°C)',
                    data: temperatureData,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        // GSV Chart
        new Chart(document.getElementById('gsvChart'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'GSV (m³)',
                    data: gsvData,
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        // Water Volume Chart
        new Chart(document.getElementById('waterVolumeChart'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Water Volume (m³)',
                    data: waterVolumeData,
                    borderColor: 'rgb(255, 205, 86)',
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    @else
        // No data available - show empty charts
        const ctx1 = document.getElementById('productGaugeChart');
        const ctx2 = document.getElementById('temperatureChart');
        const ctx3 = document.getElementById('gsvChart');
        const ctx4 = document.getElementById('waterVolumeChart');
        
        if (ctx1) {
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Product Gauge (m)',
                        data: [],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        }
        
        if (ctx2) {
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Temperature (°C)',
                        data: [],
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        }
        
        if (ctx3) {
            new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'GSV (m³)',
                        data: [],
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        }
        
        if (ctx4) {
            new Chart(ctx4, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Water Volume (m³)',
                        data: [],
                        borderColor: 'rgb(255, 205, 86)',
                        backgroundColor: 'rgba(255, 205, 86, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        }
    @endif
});
</script>
@endsection 