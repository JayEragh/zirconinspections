@extends('layouts.app')

@section('title', 'Service Request Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Service Request #{{ $serviceRequest->id }}
                </h2>
                <a href="{{ route('inspector.service-requests') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Service Requests
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Request Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Service ID:</strong> {{ $serviceRequest->service_id }}</p>
                            <p><strong>Service Type:</strong> {{ ucfirst($serviceRequest->service_type) }}</p>
                            <p><strong>Depot:</strong> {{ $serviceRequest->depot }}</p>
                            <p><strong>Product:</strong> {{ $serviceRequest->product }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Quantity (GSV):</strong> {{ $serviceRequest->quantity_gsv }}</p>
                            <p><strong>Quantity (MT):</strong> {{ number_format($serviceRequest->quantity_mt, 3) }}</p>
                            <p><strong>Tank Numbers:</strong> {{ $serviceRequest->tank_numbers }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge badge-{{ $serviceRequest->status === 'completed' ? 'success' : ($serviceRequest->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    @if($serviceRequest->specific_instructions)
                    <div class="mt-3">
                        <h6><strong>Specific Instructions:</strong></h6>
                        <p>{{ $serviceRequest->specific_instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('inspector.service-requests.update', $serviceRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="pending" {{ $serviceRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $serviceRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $serviceRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $serviceRequest->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Add any notes about this request...">{{ $serviceRequest->inspector_notes }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Reports -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Reports</h6>
                </div>
                <div class="card-body">
                    @if($serviceRequest->reports->count() > 0)
                        @foreach($serviceRequest->reports as $report)
                        <div class="mb-3">
                            <h6>{{ $report->title }}</h6>
                            <p class="text-muted mb-1">{{ $report->created_at->format('M d, Y') }}</p>
                            <span class="badge badge-{{ $report->status === 'approved' ? 'success' : ($report->status === 'submitted' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                            <a href="{{ route('inspector.reports.show', $report->id) }}" class="btn btn-sm btn-info ml-2">View</a>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No reports created yet.</p>
                        <a href="{{ route('inspector.reports.create', $serviceRequest->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-2"></i>
                            Create Report
                        </a>
                    @endif
                </div>
            </div>

            <!-- Outturn Reports -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Outturn Reports</h6>
                </div>
                <div class="card-body">
                    @php
                        $outturnReports = \App\Models\OutturnReport::where('service_request_id', $serviceRequest->id)->get();
                    @endphp
                    
                    @if($outturnReports->count() > 0)
                        @foreach($outturnReports as $outturnReport)
                        <div class="mb-3">
                            <h6>{{ $outturnReport->report_title }}</h6>
                            <p class="text-muted mb-1">{{ $outturnReport->report_date->format('M d, Y') }}</p>
                            <span class="badge badge-warning">Outturn Report</span>
                            <a href="{{ route('inspector.outturn-reports.show', $outturnReport) }}" class="btn btn-sm btn-info ml-2">View</a>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No outturn reports created yet.</p>
                        <a href="{{ route('inspector.outturn-reports.create', $serviceRequest->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-plus me-2"></i>
                            Create Outturn Report
                        </a>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Request Created</h6>
                                <p class="text-muted mb-0">{{ $serviceRequest->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @if($serviceRequest->assigned_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Assigned to Inspector</h6>
                                <p class="text-muted mb-0">{{ $serviceRequest->assigned_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($serviceRequest->completed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Completed</h6>
                                <p class="text-muted mb-0">{{ $serviceRequest->completed_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 3px);
    background-color: #e3e6f0;
}
</style>
@endsection 