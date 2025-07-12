@extends('layouts.app')

@section('title', 'Client Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Client Details
                </h2>
                <div>
                    <a href="{{ route('operations.clients.edit', $client) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>
                        Edit Client
                    </a>
                    <a href="{{ route('operations.clients') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Clients
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Client Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Client Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Personal Information</h6>
                            <p><strong>Name:</strong> {{ $client->user->name }}</p>
                            <p><strong>Email:</strong> {{ $client->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $client->phone ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $client->address ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Company Information</h6>
                            <p><strong>Company Name:</strong> {{ $client->company_name ?? 'N/A' }}</p>
                            <p><strong>Contact Person:</strong> {{ $client->contact_person ?? 'N/A' }}</p>
                            <p><strong>Tax ID:</strong> {{ $client->tax_id ?? 'N/A' }}</p>
                            <p><strong>Registered:</strong> {{ $client->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Requests Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Service Requests ({{ $client->serviceRequests->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($client->serviceRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Service Type</th>
                                        <th>Status</th>
                                        <th>Assigned Inspector</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->serviceRequests as $request)
                                    <tr>
                                        <td>
                                            <strong>#{{ $request->id }}</strong>
                                        </td>
                                        <td>{{ $request->service_type }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($request->inspector)
                                                {{ $request->inspector->user->name }}
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('operations.service-requests.show', $request) }}" 
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
                            <i class="fas fa-clipboard-list fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No service requests found for this client.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reports Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Reports ({{ $client->serviceRequests->flatMap->reports->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $reports = $client->serviceRequests->flatMap->reports;
                    @endphp
                    
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Service Request</th>
                                        <th>Inspector</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>
                                            <strong>#{{ $report->id }}</strong>
                                        </td>
                                        <td>#{{ $report->serviceRequest->id }}</td>
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
                            <p class="text-muted">No reports found for this client.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Invoices Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Invoices ({{ $client->invoices->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($client->invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>Service Request</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->invoices as $invoice)
                                    <tr>
                                        <td>
                                            <strong>#{{ $invoice->id }}</strong>
                                        </td>
                                        <td>#{{ $invoice->serviceRequest->id }}</td>
                                        <td>${{ number_format($invoice->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('operations.invoices.show', $invoice) }}" 
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
                            <i class="fas fa-receipt fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No invoices found for this client.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ $client->serviceRequests->count() }}</h4>
                                <small class="text-muted">Service Requests</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1">{{ $client->serviceRequests->flatMap->reports->count() }}</h4>
                                <small class="text-muted">Reports</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1">{{ $client->invoices->count() }}</h4>
                                <small class="text-muted">Invoices</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1">{{ $client->serviceRequests->where('status', 'completed')->count() }}</h4>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('operations.service-requests') }}?client_id={{ $client->id }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-clipboard-list me-2"></i>
                            View All Service Requests
                        </a>
                        <a href="{{ route('operations.reports') }}?client_id={{ $client->id }}" 
                           class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-alt me-2"></i>
                            View All Reports
                        </a>
                        <a href="{{ route('operations.invoices') }}?client_id={{ $client->id }}" 
                           class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-receipt me-2"></i>
                            View All Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 