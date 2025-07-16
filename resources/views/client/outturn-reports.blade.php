@extends('layouts.app')

@section('title', 'Outturn Reports')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Outturn Reports
                </h2>
                <a href="{{ route('client.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Dashboard
                </a>
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

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Your Outturn Reports
                    </h5>
                </div>
                <div class="card-body">
                    @if($outturnReports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Report #</th>
                                        <th>Report Title</th>
                                        <th>Service Request</th>
                                        <th>Inspector</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($outturnReports as $outturnReport)
                                    <tr>
                                        <td>
                                            <strong>#{{ $outturnReport->id }}</strong>
                                        </td>
                                        <td>{{ $outturnReport->report_title }}</td>
                                        <td>
                                            <span class="badge bg-info">#{{ $outturnReport->serviceRequest->id }}</span>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($outturnReport->serviceRequest->service_type) }}</small>
                                        </td>
                                        <td>{{ $outturnReport->inspector->user->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($outturnReport->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($outturnReport->status === 'declined')
                                                <span class="badge bg-danger">Declined</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $outturnReport->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('client.outturn-reports.show', $outturnReport) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Report">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('client.outturn-reports.pdf', $outturnReport) }}" 
                                                   class="btn btn-sm btn-outline-success" title="Download PDF">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Outturn Reports Found</h5>
                            <p class="text-muted">Outturn reports will appear here once they are created by inspectors for your service requests.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 