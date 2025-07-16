@extends('layouts.app')

@section('title', 'Outturn Reports')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Outturn Reports
                </h2>
                <a href="{{ route('inspector.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-tachometer-alt me-2"></i>
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

    @if($outturnReports->count() > 0)
        <div class="row">
            @foreach($outturnReports as $outturnReport)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-primary">{{ $outturnReport->report_title }}</h6>
                                <span class="badge bg-success">Outturn Report</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">BDC Name</small>
                                    <p class="mb-0 fw-bold">{{ $outturnReport->bdc_name }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Report Date</small>
                                    <p class="mb-0 fw-bold">{{ $outturnReport->report_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Service Request</small>
                                    <p class="mb-0 fw-bold">#{{ $outturnReport->serviceRequest->id }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Client</small>
                                    <p class="mb-0 fw-bold">{{ $outturnReport->serviceRequest->client->user->name }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <small class="text-muted">Summary</small>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">GOV Difference</small>
                                            <p class="mb-0 fw-bold {{ $outturnReport->total_gov_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($outturnReport->total_gov_difference, 3) }}
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">GSV Difference</small>
                                            <p class="mb-0 fw-bold {{ $outturnReport->total_gsv_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($outturnReport->total_gsv_difference, 3) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">MT Air Difference</small>
                                    <p class="mb-0 fw-bold {{ $outturnReport->total_mt_air_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($outturnReport->total_mt_air_difference, 3) }}
                                    </p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">MT Vac Difference</small>
                                    <p class="mb-0 fw-bold {{ $outturnReport->total_mt_vac_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($outturnReport->total_mt_vac_difference, 3) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('inspector.outturn-reports.show', $outturnReport) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    View Details
                                </a>
                                <a href="{{ route('inspector.outturn-reports.pdf', $outturnReport) }}" 
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-download me-1"></i>
                                    Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $outturnReports->links() }}
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Outturn Reports Found</h5>
                        <p class="text-muted mb-4">You haven't created any outturn reports yet.</p>
                        <a href="{{ route('inspector.service-requests') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Create Outturn Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 