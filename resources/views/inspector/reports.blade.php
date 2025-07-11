@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">My Reports</h1>
                <a href="{{ route('inspector.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Inspection Reports</h6>
                    <div>
                        <a href="{{ route('inspector.service-requests') }}" class="btn btn-sm btn-primary">View Service Requests</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Report #</th>
                                        <th>Title</th>
                                        <th>Service Request</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>#{{ $report->id }}</td>
                                        <td>{{ $report->title }}</td>
                                        <td>#{{ $report->serviceRequest->id }}</td>
                                        <td>{{ $report->client->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $report->status === 'approved' ? 'success' : ($report->status === 'submitted' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('inspector.reports.show', $report->id) }}" class="btn btn-sm btn-info">View</a>
                                                @if($report->status === 'draft')
                                                    <a href="{{ route('inspector.reports.edit', $report->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No reports created yet</h5>
                            <p class="text-muted">You can create reports for completed service requests.</p>
                            <a href="{{ route('inspector.service-requests') }}" class="btn btn-primary">View Service Requests</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 