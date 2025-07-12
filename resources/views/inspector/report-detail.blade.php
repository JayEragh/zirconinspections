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
                    <a href="{{ route('inspector.reports.edit', $report->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>
                        Edit Report
                    </a>
                    <a href="{{ route('inspector.reports') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Reports
                    </a>
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
                            <p><strong>Inspector:</strong> {{ $report->inspector->name }}</p>
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Technical Inspection Data</h6>
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
                    <a href="{{ route('inspector.service-requests.show', $report->serviceRequest->id) }}" class="btn btn-sm btn-info">
                        View Service Request
                    </a>
                </div>
            </div>



            <!-- Report Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('inspector.reports.edit', $report->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Edit Report
                        </a>
                        <a href="{{ route('inspector.reports.pdf', $report->id) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-2"></i>
                            Export to PDF
                        </a>
                        @if($report->status === 'draft')
                        <form action="{{ route('inspector.reports.update', $report->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $report->title }}">
                            <input type="hidden" name="content" value="{{ $report->content }}">
                            <input type="hidden" name="findings" value="{{ $report->findings }}">
                            <input type="hidden" name="recommendations" value="{{ $report->recommendations }}">
                            <input type="hidden" name="status" value="submitted">
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to submit this report?')">
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Report
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 