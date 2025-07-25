@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-user me-2"></i>
                    User Details
                </h1>
                <div>
                    <a href="{{ route('operations.users.edit', $user) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i>
                        Edit User
                    </a>
                    <a href="{{ route('operations.users') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Users
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
        <!-- User Information -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-initial rounded-circle bg-{{ $user->role === 'operations' ? 'danger' : ($user->role === 'inspector' ? 'warning' : 'primary') }} text-white mx-auto" style="width: 80px; height: 80px; font-size: 32px; display: flex; align-items: center; justify-content: center;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                        <span class="badge bg-{{ $user->role === 'operations' ? 'danger' : ($user->role === 'inspector' ? 'warning' : 'primary') }} fs-6">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-6">
                            {{ $user->email }}
                            @if($user->email_verified_at)
                                <i class="fas fa-check-circle text-success ms-1" title="Verified"></i>
                            @else
                                <i class="fas fa-exclamation-circle text-warning ms-1" title="Unverified"></i>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Phone:</strong>
                        </div>
                        <div class="col-sm-6">
                            {{ $user->phone ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-6">
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Registered:</strong>
                        </div>
                        <div class="col-sm-6">
                            {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Last Updated:</strong>
                        </div>
                        <div class="col-sm-6">
                            {{ $user->updated_at->format('M d, Y') }}
                        </div>
                    </div>

                    @if($user->address)
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Address:</strong>
                            </div>
                            <div class="col-sm-6">
                                {{ $user->address }}
                            </div>
                        </div>
                    @endif

                    <hr>

                    <h6 class="mb-3">Notification Preferences</h6>
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-6">
                            @if($user->notifications_email)
                                <i class="fas fa-check text-success"></i> Enabled
                            @else
                                <i class="fas fa-times text-danger"></i> Disabled
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>SMS:</strong>
                        </div>
                        <div class="col-sm-6">
                            @if($user->notifications_sms)
                                <i class="fas fa-check text-success"></i> Enabled
                            @else
                                <i class="fas fa-times text-danger"></i> Disabled
                            @endif
                        </div>
                    </div>

                    @if($user->id !== Auth::id())
                        <hr>
                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('operations.users.toggle-status', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} w-100">
                                    @if($user->is_active)
                                        <i class="fas fa-ban me-2"></i>Deactivate User
                                    @else
                                        <i class="fas fa-check me-2"></i>Activate User
                                    @endif
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Role-Specific Information and Activity -->
        <div class="col-lg-8">
            <!-- Role-Specific Information -->
            @if($user->isClient() && $user->client)
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-building me-2"></i>
                            Client Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Company Name:</strong><br>
                                {{ $user->client->company_name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Service Requests:</strong><br>
                                <span class="badge bg-info fs-6">{{ $serviceRequests->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($user->isInspector() && $user->inspector)
                <div class="card shadow mb-4">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-tie me-2"></i>
                            Inspector Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Specialization:</strong><br>
                                {{ $user->inspector->specialization }}
                            </div>
                            <div class="col-md-6">
                                <strong>License Number:</strong><br>
                                {{ $user->inspector->license_number ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Service Requests -->
            @if($serviceRequests->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Recent Service Requests ({{ $serviceRequests->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Request #</th>
                                        <th>Service Type</th>
                                        <th>Status</th>
                                        @if($user->isClient())
                                            <th>Inspector</th>
                                        @else
                                            <th>Client</th>
                                        @endif
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceRequests as $request)
                                    <tr>
                                        <td>#{{ $request->id }}</td>
                                        <td>{{ $request->service_type }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        @if($user->isClient())
                                            <td>{{ $request->inspector->user->name ?? 'Unassigned' }}</td>
                                        @else
                                            <td>{{ $request->client->user->name }}</td>
                                        @endif
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Reports (for inspectors) -->
            @if($user->isInspector() && $reports->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Recent Reports ({{ $reports->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Report #</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>#{{ $report->id }}</td>
                                        <td>{{ $report->serviceRequest->client->user->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $report->status === 'approved' ? 'success' : ($report->status === 'submitted' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Invoices (for clients) -->
            @if($user->isClient() && $invoices->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-dollar-sign me-2"></i>
                            Recent Invoices ({{ $invoices->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td>#{{ $invoice->invoice_number }}</td>
                                        <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'sent' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Activity -->
            @if($loginLogs->count() > 0)
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Recent Login Activity ({{ $loginLogs->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loginLogs as $log)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $log->action === 'login' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>
                                            <small>{{ Str::limit($log->user_agent, 50) }}</small>
                                        </td>
                                        <td>{{ $log->logged_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 