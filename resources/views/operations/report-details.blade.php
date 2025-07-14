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
                    <a href="{{ route('operations.reports') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Reports
                    </a>
                    <a href="{{ route('operations.reports.pdf', $report->id) }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>
                        Download PDF
                    </a>
                    @if($report->inspectionDataSets->count() > 0)
                    <a href="{{ route('operations.reports.excel', $report->id) }}" class="btn btn-info">
                        <i class="fas fa-file-excel me-2"></i>
                        Download Excel
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
                    <a href="{{ route('operations.service-requests.show', $report->serviceRequest->id) }}" class="btn btn-sm btn-info">
                        View Service Request
                    </a>
                </div>
            </div>

            <!-- Client Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Client Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $report->client->user->name }}</p>
                    <p><strong>Email:</strong> {{ $report->client->user->email }}</p>
                    <p><strong>Phone:</strong> {{ $report->client->phone }}</p>
                    <p><strong>Address:</strong> {{ $report->client->address }}</p>
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
                    <a href="{{ route('operations.inspectors.show', $report->inspector->id) }}" class="btn btn-sm btn-info">
                        View Inspector Details
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
                        @if($report->status === 'submitted')
                        <form action="{{ route('operations.reports.approve', $report->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this report?')">
                                <i class="fas fa-check me-2"></i>
                                Approve Report
                            </button>
                        </form>
                        @elseif($report->status === 'approved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Report Approved on {{ $report->approved_at->format('M d, Y H:i') }}
                        </div>
                        
                        <!-- Send to Client Button -->
                        @if($report->sent_to_client_at)
                            <div class="alert alert-info">
                                <i class="fas fa-paper-plane me-2"></i>
                                <strong>Sent to Client</strong><br>
                                Sent on {{ $report->sent_to_client_at->format('M d, Y H:i') }}
                            </div>
                        @else
                            <form action="{{ route('operations.reports.send-to-client', $report->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Send this approved report notification to the client?')">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send to Client
                                </button>
                            </form>
                        @endif
                        @else
                        <div class="alert alert-secondary">
                            <i class="fas fa-clock me-2"></i>
                            Report is in draft status
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 