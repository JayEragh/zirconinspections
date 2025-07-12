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
                                @php
                                    // Sample data for demonstration
                                    $sampleData = [
                                        ['date' => '2024-01-15', 'time' => '08:30', 'tank' => 'T-001', 'product_gauge' => 12.45, 'water_gauge' => 0.15, 'temperature' => 25.5, 'density' => 0.8456, 'vcf' => 0.9987, 'tov' => 1250.50, 'water_volume' => 15.25, 'gov' => 1235.25, 'gsv' => 1233.45, 'mt_air' => 1042.89],
                                        ['date' => '2024-01-16', 'time' => '09:15', 'tank' => 'T-001', 'product_gauge' => 12.38, 'water_gauge' => 0.18, 'temperature' => 26.2, 'density' => 0.8448, 'vcf' => 0.9985, 'tov' => 1245.75, 'water_volume' => 18.50, 'gov' => 1227.25, 'gsv' => 1225.40, 'mt_air' => 1035.67],
                                        ['date' => '2024-01-17', 'time' => '08:45', 'tank' => 'T-001', 'product_gauge' => 12.52, 'water_gauge' => 0.12, 'temperature' => 24.8, 'density' => 0.8462, 'vcf' => 0.9989, 'tov' => 1255.25, 'water_volume' => 12.00, 'gov' => 1243.25, 'gsv' => 1241.55, 'mt_air' => 1050.23],
                                        ['date' => '2024-01-18', 'time' => '09:30', 'tank' => 'T-001', 'product_gauge' => 12.29, 'water_gauge' => 0.22, 'temperature' => 27.1, 'density' => 0.8435, 'vcf' => 0.9982, 'tov' => 1238.50, 'water_volume' => 22.75, 'gov' => 1215.75, 'gsv' => 1213.95, 'mt_air' => 1023.45],
                                        ['date' => '2024-01-19', 'time' => '08:20', 'tank' => 'T-001', 'product_gauge' => 12.61, 'water_gauge' => 0.08, 'temperature' => 23.9, 'density' => 0.8471, 'vcf' => 0.9991, 'tov' => 1262.75, 'water_volume' => 8.00, 'gov' => 1254.75, 'gsv' => 1253.20, 'mt_air' => 1062.78],
                                        ['date' => '2024-01-20', 'time' => '09:45', 'tank' => 'T-001', 'product_gauge' => 12.33, 'water_gauge' => 0.25, 'temperature' => 28.3, 'density' => 0.8421, 'vcf' => 0.9978, 'tov' => 1241.25, 'water_volume' => 25.50, 'gov' => 1215.75, 'gsv' => 1213.85, 'mt_air' => 1021.56],
                                        ['date' => '2024-01-21', 'time' => '08:10', 'tank' => 'T-001', 'product_gauge' => 12.68, 'water_gauge' => 0.05, 'temperature' => 22.7, 'density' => 0.8482, 'vcf' => 0.9993, 'tov' => 1268.00, 'water_volume' => 5.25, 'gov' => 1262.75, 'gsv' => 1261.45, 'mt_air' => 1070.12],
                                        ['date' => '2024-01-22', 'time' => '09:55', 'tank' => 'T-001', 'product_gauge' => 12.26, 'water_gauge' => 0.28, 'temperature' => 29.1, 'density' => 0.8412, 'vcf' => 0.9975, 'tov' => 1238.75, 'water_volume' => 28.25, 'gov' => 1210.50, 'gsv' => 1208.45, 'mt_air' => 1016.34],
                                        ['date' => '2024-01-23', 'time' => '08:05', 'tank' => 'T-001', 'product_gauge' => 12.75, 'water_gauge' => 0.02, 'temperature' => 21.4, 'density' => 0.8495, 'vcf' => 0.9995, 'tov' => 1272.50, 'water_volume' => 2.50, 'gov' => 1270.00, 'gsv' => 1268.85, 'mt_air' => 1077.89],
                                        ['date' => '2024-01-24', 'time' => '10:00', 'tank' => 'T-001', 'product_gauge' => 12.19, 'water_gauge' => 0.32, 'temperature' => 30.2, 'density' => 0.8401, 'vcf' => 0.9972, 'tov' => 1235.25, 'water_volume' => 32.00, 'gov' => 1203.25, 'gsv' => 1201.05, 'mt_air' => 1008.90],
                                    ];
                                @endphp
                                
                                @foreach($sampleData as $data)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}</td>
                                    <td>{{ $data['time'] }}</td>
                                    <td>{{ $data['tank'] }}</td>
                                    <td>{{ number_format($data['product_gauge'], 2) }}</td>
                                    <td>{{ number_format($data['water_gauge'], 2) }}</td>
                                    <td>{{ number_format($data['temperature'], 1) }}</td>
                                    <td>{{ number_format($data['density'], 4) }}</td>
                                    <td>{{ number_format($data['vcf'], 4) }}</td>
                                    <td>{{ number_format($data['tov'], 2) }}</td>
                                    <td>{{ number_format($data['water_volume'], 2) }}</td>
                                    <td>{{ number_format($data['gov'], 2) }}</td>
                                    <td>{{ number_format($data['gsv'], 2) }}</td>
                                    <td>{{ number_format($data['mt_air'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Time Series Charts -->
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
                            <p><strong>Inspection Date:</strong> {{ $report->inspection_date ? $report->inspection_date->format('M d, Y') : 'N/A' }}</p>
                            <p><strong>Inspection Time:</strong> {{ $report->inspection_time ? $report->inspection_time->format('H:i') : 'N/A' }}</p>
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
    // Sample data for charts
    const dates = ['Jan 15', 'Jan 16', 'Jan 17', 'Jan 18', 'Jan 19', 'Jan 20', 'Jan 21', 'Jan 22', 'Jan 23', 'Jan 24'];
    const productGaugeData = [12.45, 12.38, 12.52, 12.29, 12.61, 12.33, 12.68, 12.26, 12.75, 12.19];
    const temperatureData = [25.5, 26.2, 24.8, 27.1, 23.9, 28.3, 22.7, 29.1, 21.4, 30.2];
    const gsvData = [1233.45, 1225.40, 1241.55, 1213.95, 1253.20, 1213.85, 1261.45, 1208.45, 1268.85, 1201.05];
    const waterVolumeData = [15.25, 18.50, 12.00, 22.75, 8.00, 25.50, 5.25, 28.25, 2.50, 32.00];

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
});
</script>
@endsection 