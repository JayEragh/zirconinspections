@extends('layouts.app')

@section('title', 'Outturn Report Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Outturn Report Details
                </h2>
                <div>
                    <a href="{{ route('inspector.dashboard') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Back to Dashboard
                    </a>
                    <a href="{{ route('inspector.outturn-reports.pdf', $outturnReport) }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Report Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Report Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Report Title:</strong> {{ $outturnReport->report_title }}</p>
                            <p><strong>BDC Name:</strong> {{ $outturnReport->bdc_name }}</p>
                            <p><strong>Report Date:</strong> {{ $outturnReport->report_date->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Service Request:</strong> #{{ $outturnReport->serviceRequest->id }}</p>
                            <p><strong>Client:</strong> {{ $outturnReport->serviceRequest->client->user->name }}</p>
                            <p><strong>Inspector:</strong> {{ $outturnReport->inspector->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Data -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Detailed Tank Data</h5>
                </div>
                <div class="card-body">
                    @php
                        $tanks = $outturnReport->outturnDataSets->groupBy('tank_number');
                    @endphp
                    
                    @foreach($tanks as $tankNumber => $dataSets)
                        @php
                            $initialData = $dataSets->where('data_type', 'initial')->first();
                            $finalData = $dataSets->where('data_type', 'final')->first();
                        @endphp
                        
                        <div class="tank-data mb-4">
                            <h6 class="text-primary mb-3">Tank: {{ $tankNumber }}</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Initial Data</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Date:</strong></td>
                                                <td>{{ $initialData->inspection_date->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Time:</strong></td>
                                                <td>{{ $initialData->inspection_time->format('H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Product Gauge:</strong></td>
                                                <td>{{ $initialData->formatted_product_gauge }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Water Gauge:</strong></td>
                                                <td>{{ $initialData->formatted_water_gauge }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Temperature:</strong></td>
                                                <td>{{ $initialData->formatted_temperature }}°C</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Density:</strong></td>
                                                <td>{{ $initialData->formatted_density }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>VCF:</strong></td>
                                                <td>{{ $initialData->formatted_vcf }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>TOV:</strong></td>
                                                <td>{{ $initialData->formatted_tov }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Water Volume:</strong></td>
                                                <td>{{ $initialData->formatted_water_volume }}</td>
                                            </tr>
                                            @if($initialData->has_roof)
                                            <tr>
                                                <td><strong>Roof Weight:</strong></td>
                                                <td>{{ $initialData->formatted_roof_weight }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Roof Volume:</strong></td>
                                                <td>{{ $initialData->formatted_roof_volume }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td><strong>GOV:</strong></td>
                                                <td>{{ $initialData->formatted_gov }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>GSV:</strong></td>
                                                <td>{{ $initialData->formatted_gsv }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>MT Air:</strong></td>
                                                <td>{{ $initialData->formatted_mt_air }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>MT Vac:</strong></td>
                                                <td>{{ $initialData->formatted_mt_vac }}</td>
                                            </tr>
                                            @if($initialData->notes)
                                            <tr>
                                                <td><strong>Notes:</strong></td>
                                                <td>{{ $initialData->notes }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-success">Final Data</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Date:</strong></td>
                                                <td>{{ $finalData->inspection_date->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Time:</strong></td>
                                                <td>{{ $finalData->inspection_time->format('H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Product Gauge:</strong></td>
                                                <td>{{ $finalData->formatted_product_gauge }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Water Gauge:</strong></td>
                                                <td>{{ $finalData->formatted_water_gauge }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Temperature:</strong></td>
                                                <td>{{ $finalData->formatted_temperature }}°C</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Density:</strong></td>
                                                <td>{{ $finalData->formatted_density }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>VCF:</strong></td>
                                                <td>{{ $finalData->formatted_vcf }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>TOV:</strong></td>
                                                <td>{{ $finalData->formatted_tov }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Water Volume:</strong></td>
                                                <td>{{ $finalData->formatted_water_volume }}</td>
                                            </tr>
                                            @if($finalData->has_roof)
                                            <tr>
                                                <td><strong>Roof Weight:</strong></td>
                                                <td>{{ $finalData->formatted_roof_weight }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Roof Volume:</strong></td>
                                                <td>{{ $finalData->formatted_roof_volume }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td><strong>GOV:</strong></td>
                                                <td>{{ $finalData->formatted_gov }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>GSV:</strong></td>
                                                <td>{{ $finalData->formatted_gsv }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>MT Air:</strong></td>
                                                <td>{{ $finalData->formatted_mt_air }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>MT Vac:</strong></td>
                                                <td>{{ $finalData->formatted_mt_vac }}</td>
                                            </tr>
                                            @if($finalData->notes)
                                            <tr>
                                                <td><strong>Notes:</strong></td>
                                                <td>{{ $finalData->notes }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-info">Tank Differences (Final - Initial)</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><strong>GOV:</strong> 
                                                <span class="{{ ($finalData->gov - $initialData->gov) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($finalData->gov - $initialData->gov, 3) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>GSV:</strong> 
                                                <span class="{{ ($finalData->gsv - $initialData->gsv) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($finalData->gsv - $initialData->gsv, 3) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>MT Air:</strong> 
                                                <span class="{{ ($finalData->mt_air - $initialData->mt_air) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($finalData->mt_air - $initialData->mt_air, 3) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>MT Vac:</strong> 
                                                <span class="{{ ($finalData->mt_vac - $initialData->mt_vac) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($finalData->mt_vac - $initialData->mt_vac, 3) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$loop->last)
                            <hr class="my-4">
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Summary Totals -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Summary Totals</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered w-auto">
                            <thead class="table-light">
                                <tr>
                                    <th>Metric</th>
                                    <th>Initial</th>
                                    <th>Final</th>
                                    <th>Difference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>GOV</strong></td>
                                    <td>{{ $outturnReport->formatted_total_gov_initial }}</td>
                                    <td>{{ $outturnReport->formatted_total_gov_final }}</td>
                                    <td class="{{ $outturnReport->total_gov_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $outturnReport->formatted_total_gov_difference }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>GSV</strong></td>
                                    <td>{{ $outturnReport->formatted_total_gsv_initial }}</td>
                                    <td>{{ $outturnReport->formatted_total_gsv_final }}</td>
                                    <td class="{{ $outturnReport->total_gsv_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $outturnReport->formatted_total_gsv_difference }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>MT Air</strong></td>
                                    <td>{{ $outturnReport->formatted_total_mt_air_initial }}</td>
                                    <td>{{ $outturnReport->formatted_total_mt_air_final }}</td>
                                    <td class="{{ $outturnReport->total_mt_air_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $outturnReport->formatted_total_mt_air_difference }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>MT Vac</strong></td>
                                    <td>{{ $outturnReport->formatted_total_mt_vac_initial }}</td>
                                    <td>{{ $outturnReport->formatted_total_mt_vac_final }}</td>
                                    <td class="{{ $outturnReport->total_mt_vac_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $outturnReport->formatted_total_mt_vac_difference }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Service Request Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Service Request Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Request #:</strong> {{ $outturnReport->serviceRequest->id }}</p>
                    <p><strong>Service ID:</strong> {{ $outturnReport->serviceRequest->service_id }}</p>
                    <p><strong>Service Type:</strong> {{ ucfirst($outturnReport->serviceRequest->service_type) }}</p>
                    <p><strong>Depot:</strong> {{ $outturnReport->serviceRequest->depot }}</p>
                    <p><strong>Product:</strong> {{ $outturnReport->serviceRequest->product }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $outturnReport->serviceRequest->status === 'completed' ? 'success' : ($outturnReport->serviceRequest->status === 'in_progress' ? 'warning' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $outturnReport->serviceRequest->status)) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('inspector.outturn-reports.pdf', $outturnReport) }}" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>
                            Download PDF
                        </a>
                        <a href="{{ route('inspector.outturn-reports') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Outturn Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 