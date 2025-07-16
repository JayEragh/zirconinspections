@extends('layouts.app')

@section('title', 'Outturn Reports Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Outturn Reports Management
                </h1>
                <a href="{{ route('operations.dashboard') }}" class="btn btn-outline-secondary">
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        All Outturn Reports
                    </h5>
                </div>
                <div class="card-body">
                    @if($outturnReports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Report Title</th>
                                        <th>Service Request</th>
                                        <th>Inspector</th>
                                        <th>Client</th>
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
                                        </td>
                                        <td>{{ $outturnReport->inspector->user->name ?? 'N/A' }}</td>
                                        <td>{{ $outturnReport->serviceRequest->client->user->name }}</td>
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
                                                <a href="{{ route('operations.outturn-reports.show', $outturnReport) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Report">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('operations.outturn-reports.pdf', $outturnReport) }}" 
                                                   class="btn btn-sm btn-outline-success" title="Download PDF">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @if($outturnReport->status !== 'approved' && $outturnReport->status !== 'declined')
                                                <form action="{{ route('operations.outturn-reports.approve', $outturnReport) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                                            title="Approve Report" 
                                                            onclick="return confirm('Are you sure you want to approve this outturn report?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('operations.outturn-reports.decline', $outturnReport) }}" 
                                                      method="POST" class="d-inline ms-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            title="Decline Report" 
                                                            onclick="return confirm('Are you sure you want to decline this outturn report and request amendment by the inspector?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if($outturnReport->status === 'approved' && !$outturnReport->sent_to_client_at)
                                                <form action="{{ route('operations.outturn-reports.send-to-client', $outturnReport) }}" 
                                                      method="POST" class="d-inline ms-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-info" 
                                                            title="Send to Client" 
                                                            onclick="return confirm('Send this approved outturn report notification to the client?')">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $outturnReports->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Outturn Reports Found</h5>
                            <p class="text-muted">There are no outturn reports available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 