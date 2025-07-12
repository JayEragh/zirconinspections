@extends('layouts.app')

@section('title', 'Service Request Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Service Request Details
                </h1>
                <div>
                    @if($serviceRequest->status === 'pending')
                        <a href="{{ route('client.service-requests.edit', $serviceRequest) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit Request
                        </a>
                    @endif
                    <a href="{{ route('client.service-requests') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Service Request Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Request Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Service ID:</strong> {{ $serviceRequest->service_id }}</p>
                            <p><strong>Service Type:</strong> {{ ucwords(str_replace('_', ' ', $serviceRequest->service_type)) }}</p>
                            <p><strong>Depot:</strong> {{ $serviceRequest->depot }}</p>
                            <p><strong>Product:</strong> {{ $serviceRequest->product }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Quantity (GSV):</strong> {{ number_format($serviceRequest->quantity_gsv, 2) }} L</p>
                            <p><strong>Quantity (MT):</strong> {{ number_format($serviceRequest->quantity_mt, 3) }} MT</p>
                            <p><strong>Tank Numbers:</strong> {{ $serviceRequest->tank_numbers }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $serviceRequest->status === 'completed' ? 'success' : ($serviceRequest->status === 'pending' ? 'warning' : 'info') }}">
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

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>Created:</strong> {{ $serviceRequest->created_at->format('M d, Y H:i') }}</p>
                            @if($serviceRequest->assigned_at)
                                <p><strong>Assigned:</strong> {{ $serviceRequest->assigned_at->format('M d, Y H:i') }}</p>
                            @endif
                            @if($serviceRequest->completed_at)
                                <p><strong>Completed:</strong> {{ $serviceRequest->completed_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($serviceRequest->inspector)
                                <p><strong>Assigned Inspector:</strong> {{ $serviceRequest->inspector->user->name }}</p>
                            @else
                                <p><strong>Assigned Inspector:</strong> <span class="text-muted">Not assigned yet</span></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attachments Card -->
            @if($serviceRequest->outturn_file || $serviceRequest->quality_certificate_file)
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-paperclip me-2"></i>
                        Attachments
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($serviceRequest->outturn_file)
                        <div class="col-md-6">
                            <p><strong>Outturn Report:</strong></p>
                            <a href="{{ Storage::url($serviceRequest->outturn_file) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                        </div>
                        @endif
                        @if($serviceRequest->quality_certificate_file)
                        <div class="col-md-6">
                            <p><strong>Quality Certificate:</strong></p>
                            <a href="{{ Storage::url($serviceRequest->quality_certificate_file) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Status Timeline Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Status Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Request Created</h6>
                                <small class="text-muted">{{ $serviceRequest->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        
                        @if($serviceRequest->assigned_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Inspector Assigned</h6>
                                <small class="text-muted">{{ $serviceRequest->assigned_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($serviceRequest->completed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Request Completed</h6>
                                <small class="text-muted">{{ $serviceRequest->completed_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($serviceRequest->reports->count() > 0)
                            <a href="{{ route('client.reports') }}?service_request={{ $serviceRequest->id }}" class="btn btn-outline-success">
                                <i class="fas fa-file-alt me-2"></i>View Reports
                            </a>
                        @endif
                        @if($serviceRequest->invoice)
                            <a href="{{ route('client.invoices') }}?service_request={{ $serviceRequest->id }}" class="btn btn-outline-warning">
                                <i class="fas fa-receipt me-2"></i>View Invoice
                            </a>
                        @endif
                        <a href="{{ route('client.messages') }}?service_request={{ $serviceRequest->id }}" class="btn btn-outline-info">
                            <i class="fas fa-envelope me-2"></i>View Messages
                        </a>
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
    background-color: #dee2e6;
}
</style>
@endsection 