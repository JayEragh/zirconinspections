@extends('layouts.app')

@section('title', 'Operations Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Operations Dashboard
                </h1>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-2"></i>
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="{{ route('operations.profile') }}"><i class="fas fa-user-cog me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('operations.settings') }}"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Clients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_clients'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Inspectors
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_inspectors'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_requests'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Completed Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['completed_requests'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total Reports
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_reports'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_invoices'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('operations.clients') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users me-2"></i>
                                Manage Clients
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('operations.inspectors') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-user-tie me-2"></i>
                                Manage Inspectors
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('operations.service-requests') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-clipboard-list me-2"></i>
                                Service Requests
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('operations.reports') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-file-alt me-2"></i>
                                Reports
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('operations.outturn-reports') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-chart-line me-2"></i>
                                Outturn Reports
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('operations.invoices') }}" class="btn btn-outline-danger w-100">
                                <i class="fas fa-dollar-sign me-2"></i>
                                Invoices
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('operations.messages') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-envelope me-2"></i>
                                Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Recent Service Requests
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Service ID</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                    <tr>
                                        <td>
                                            <a href="#" class="text-decoration-none">
                                                {{ $request->service_id }}
                                            </a>
                                        </td>
                                        <td>{{ $request->client->user->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No recent service requests</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Recent Reports
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentReports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Inspector</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentReports as $report)
                                    <tr>
                                        <td>
                                            <a href="#" class="text-decoration-none">
                                                {{ $report->report_id }}
                                            </a>
                                        </td>
                                        <td>{{ $report->inspector->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $report->status === 'approved' ? 'success' : 'warning' }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $report->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No recent reports</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Login/Logout Logs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login & Logout Activity (Last 5)
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filter/Search Form -->
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search user, email, or IP" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="action" class="form-select">
                                <option value="">All Actions</option>
                                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                                <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('operations.dashboard') }}#login-logs" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </form>
                    
                    <!-- Export Button -->
                    <div class="mb-3">
                        <a href="{{ route('operations.login-logs.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-file-excel me-2"></i>Export to Excel
                        </a>
                    </div>
                    @if($loginLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                        <th>Date & Time</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loginLogs as $log)
                                    <tr>
                                        <td>{{ $log->user->name ?? 'N/A' }}</td>
                                        <td>{{ $log->user->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $log->action === 'login' ? 'success' : 'danger' }}">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->logged_at ? \Carbon\Carbon::parse($log->logged_at)->format('M d, Y H:i:s') : '' }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td class="text-truncate" style="max-width: 200px;">{{ $log->user_agent }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $loginLogs->links() }}
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-sign-in-alt fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No login or logout activity found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 