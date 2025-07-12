@extends('layouts.app')

@section('title', 'Service Requests')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Service Requests
                </h1>
                <a href="{{ route('client.service-requests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    New Service Request
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
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Your Service Requests
                    </h5>
                </div>
                <div class="card-body">
                    @if($serviceRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Service ID</th>
                                        <th>Product</th>
                                        <th>Depot</th>
                                        <th>Quantity</th>
                                        <th>Service Type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceRequests as $request)
                                    <tr>
                                        <td>
                                            <strong>{{ $request->service_id }}</strong>
                                        </td>
                                        <td>{{ $request->product }}</td>
                                        <td>{{ $request->depot }}</td>
                                        <td>
                                            {{ number_format($request->quantity_gsv, 2) }} L<br>
                                            <small class="text-muted">{{ number_format($request->quantity_mt, 3) }} MT</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucwords(str_replace('_', ' ', $request->service_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'assigned' => 'info',
                                                    'in_progress' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusColor = $statusColors[$request->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($request->status === 'pending')
                                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $serviceRequests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
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
    </div>
</div>
@endsection 