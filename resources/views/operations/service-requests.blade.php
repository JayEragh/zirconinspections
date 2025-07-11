@extends('layouts.app')

@section('title', 'Service Requests Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Service Requests Management
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

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        All Service Requests
                    </h5>
                </div>
                <div class="card-body">
                    @if($serviceRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Service ID</th>
                                        <th>Client</th>
                                        <th>Product</th>
                                        <th>Depot</th>
                                        <th>Inspector</th>
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
                                        <td>
                                            <div>
                                                <strong>{{ $request->client->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $request->client->company_name }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $request->product }}</td>
                                        <td>{{ $request->depot }}</td>
                                        <td>
                                            @if($request->inspector)
                                                <span class="badge bg-success">{{ $request->inspector->user->name }}</span>
                                            @else
                                                <span class="badge bg-warning">Unassigned</span>
                                            @endif
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
                                                <a href="{{ route('operations.service-requests.show', $request) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($request->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#assignModal{{ $request->id }}" 
                                                        title="Assign Inspector">
                                                    <i class="fas fa-user-plus"></i>
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
                            <h5 class="text-muted">No service requests found</h5>
                            <p class="text-muted">Service requests will appear here once clients submit them.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Inspector Modals -->
@foreach($serviceRequests as $request)
    @if($request->status === 'pending')
    <div class="modal fade" id="assignModal{{ $request->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $request->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel{{ $request->id }}">
                        Assign Inspector to {{ $request->service_id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('operations.service-requests.assign', $request) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inspector_id" class="form-label">Select Inspector</label>
                            <select class="form-select" id="inspector_id" name="inspector_id" required>
                                <option value="">Choose an inspector...</option>
                                @foreach(\App\Models\Inspector::where('is_active', true)->get() as $inspector)
                                    <option value="{{ $inspector->id }}">
                                        {{ $inspector->user->name }} ({{ $inspector->certification_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>
                            Assign Inspector
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection 