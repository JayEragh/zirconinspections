@extends('layouts.app')

@section('title', 'Service Request Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Service Request Details
                </h2>
                <a href="{{ route('operations.service-requests') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Service Requests
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Service Request Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Request Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Request Details</h6>
                            <p><strong>Request ID:</strong> #{{ $serviceRequest->id }}</p>
                            <p><strong>Service Type:</strong> {{ $serviceRequest->service_type }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $serviceRequest->status === 'completed' ? 'success' : ($serviceRequest->status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($serviceRequest->status) }}
                                </span>
                            </p>
                            <p><strong>Priority:</strong> {{ ucfirst($serviceRequest->priority ?? 'Normal') }}</p>
                            <p><strong>Created:</strong> {{ $serviceRequest->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Client Information</h6>
                            <p><strong>Client:</strong> {{ $serviceRequest->client->user->name }}</p>
                            <p><strong>Company:</strong> {{ $serviceRequest->client->company_name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $serviceRequest->client->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $serviceRequest->client->phone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted">Description</h6>
                            <p>{{ $serviceRequest->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>

                    @if($serviceRequest->location)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted">Location</h6>
                            <p>{{ $serviceRequest->location }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Inspector Assignment Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        Inspector Assignment
                    </h5>
                </div>
                <div class="card-body">
                    @if($serviceRequest->inspector)
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Assigned Inspector</h6>
                                <p><strong>Name:</strong> {{ $serviceRequest->inspector->user->name }}</p>
                                <p><strong>Email:</strong> {{ $serviceRequest->inspector->user->email }}</p>
                                <p><strong>Phone:</strong> {{ $serviceRequest->inspector->phone ?? 'N/A' }}</p>
                                <p><strong>Specialization:</strong> {{ $serviceRequest->inspector->specialization ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Assignment Details</h6>
                                <p><strong>Assigned Date:</strong> {{ $serviceRequest->assigned_at ? $serviceRequest->assigned_at->format('M d, Y H:i') : 'N/A' }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-{{ $serviceRequest->status === 'assigned' ? 'info' : ($serviceRequest->status === 'in_progress' ? 'warning' : 'success') }}">
                                        {{ ucfirst($serviceRequest->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-tie fa-2x text-muted mb-3"></i>
                            <h6 class="text-muted">No Inspector Assigned</h6>
                            <p class="text-muted">This service request has not been assigned to an inspector yet.</p>
                            
                            <!-- Assign Inspector Form -->
                            <form action="{{ route('operations.service-requests.assign', $serviceRequest) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select name="inspector_id" class="form-select" required>
                                                <option value="">Select Inspector</option>
                                                @foreach(App\Models\Inspector::with('user')->get() as $inspector)
                                                    <option value="{{ $inspector->id }}">
                                                        {{ $inspector->user->name }} - {{ $inspector->specialization ?? 'No specialization' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-user-plus me-2"></i>
                                                Assign Inspector
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reports Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Reports ({{ $serviceRequest->reports->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($serviceRequest->reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Inspector</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceRequest->reports as $report)
                                    <tr>
                                        <td>
                                            <strong>#{{ $report->id }}</strong>
                                        </td>
                                        <td>{{ $report->inspector->user->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $report->status === 'approved' ? 'success' : ($report->status === 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('operations.reports.show', $report) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No reports found for this service request.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Management Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Status Management
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('operations.service-requests.update', $serviceRequest) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $serviceRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ $serviceRequest->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="in_progress" {{ $serviceRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $serviceRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $serviceRequest->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fas fa-save me-2"></i>
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($serviceRequest->inspector)
                            <a href="{{ route('operations.inspectors.show', $serviceRequest->inspector) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-user-tie me-2"></i>
                                View Inspector Profile
                            </a>
                        @endif
                        <a href="{{ route('operations.clients.show', $serviceRequest->client) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user me-2"></i>
                            View Client Details
                        </a>
                        @if($serviceRequest->reports->count() > 0)
                            <a href="{{ route('operations.reports') }}?service_request_id={{ $serviceRequest->id }}" 
                               class="btn btn-outline-success btn-sm">
                                <i class="fas fa-file-alt me-2"></i>
                                View All Reports
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
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
                        
                        @if($serviceRequest->updated_at != $serviceRequest->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Updated</h6>
                                <small class="text-muted">{{ $serviceRequest->updated_at->format('M d, Y H:i') }}</small>
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
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -20px;
    top: 15px;
    width: 2px;
    height: 25px;
    background-color: #dee2e6;
}
</style>
@endsection 