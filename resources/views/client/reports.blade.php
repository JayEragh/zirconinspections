@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Inspection Reports
                </h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Your Reports
                    </h5>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Service Request</th>
                                        <th>Inspector</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>
                                            <strong>{{ $report->report_id }}</strong>
                                        </td>
                                        <td>{{ $report->serviceRequest->service_id }}</td>
                                        <td>{{ $report->inspector->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucwords(str_replace('_', ' ', $report->report_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $report->status === 'approved' ? 'success' : 'warning' }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary" title="View Report">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-success" title="Download PDF">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No reports available</h5>
                            <p class="text-muted">Reports will appear here once your service requests are completed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 