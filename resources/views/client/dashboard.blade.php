@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Welcome, {{ $user->name }}
                </h1>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-2"></i>
                        {{ $user->name }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="{{ route('client.profile') }}"><i class="fas fa-user-cog me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('client.settings') }}"><i class="fas fa-cog me-2"></i>Settings</a></li>
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
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Service Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $client->serviceRequests()->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Reports
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $client->serviceRequests()->whereHas('reports')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $client->serviceRequests()->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Invoices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $client->invoices()->count() }}
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
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('client.service-requests.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i>
                                New Service Request
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('client.service-requests') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-2"></i>
                                View Requests
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('client.reports') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-alt me-2"></i>
                                View Reports
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('client.messages') }}" class="btn btn-outline-info w-100">
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
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Recent Service Requests
                    </h5>
                </div>
                <div class="card-body">
                    @if($client->serviceRequests()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service ID</th>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->serviceRequests()->latest()->take(5)->get() as $request)
                                    <tr>
                                        <td>{{ $request->service_id }}</td>
                                        <td>{{ $request->product }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('client.service-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No service requests yet</h5>
                            <p class="text-muted">Create your first service request to get started.</p>
                            <a href="{{ route('client.service-requests.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Create Service Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        Company Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Company:</strong> {{ $client->company_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Contact:</strong> {{ $client->contact_person }}
                    </div>
                    @if($client->phone)
                    <div class="mb-3">
                        <strong>Phone:</strong> {{ $client->phone }}
                    </div>
                    @endif
                    @if($client->address)
                    <div class="mb-3">
                        <strong>Address:</strong> {{ $client->address }}
                    </div>
                    @endif
                    @if($client->tax_id)
                    <div class="mb-3">
                        <strong>Tax ID:</strong> {{ $client->tax_id }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 