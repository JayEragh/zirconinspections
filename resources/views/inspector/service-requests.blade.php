@extends('layouts.app')

@section('title', 'Service Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">My Service Requests</h1>
                <a href="{{ route('inspector.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Assigned Service Requests</h6>
                </div>
                <div class="card-body">
                    @if($serviceRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Request #</th>
                                        <th>Service Type</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Assigned Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceRequests as $request)
                                    <tr>
                                        <td>#{{ $request->id }}</td>
                                        <td>{{ $request->service_type }}</td>
                                        <td>
                                            <span class="badge badge-{{ $request->priority === 'high' ? 'danger' : ($request->priority === 'medium' ? 'warning' : 'info') }}">
                                                {{ ucfirst($request->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $request->status === 'completed' ? 'success' : ($request->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('inspector.service-requests.show', $request->id) }}" class="btn btn-sm btn-info">View</a>
                                                @if($request->status !== 'completed')
                                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#updateModal{{ $request->id }}">
                                                        Update
                                                    </button>
                                                @endif
                                                @if($request->status === 'in_progress' && !$request->report)
                                                    <a href="{{ route('inspector.reports.create', $request->id) }}" class="btn btn-sm btn-success">Create Report</a>
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
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No service requests assigned yet</h5>
                            <p class="text-muted">You will see service requests here once they are assigned to you.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modals -->
@foreach($serviceRequests as $request)
<div class="modal fade" id="updateModal{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('inspector.service-requests.update', $request->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel{{ $request->id }}">Update Service Request #{{ $request->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status{{ $request->id }}">Status</label>
                        <select class="form-control" id="status{{ $request->id }}" name="status" required>
                            <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $request->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $request->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $request->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes{{ $request->id }}">Notes</label>
                        <textarea class="form-control" id="notes{{ $request->id }}" name="notes" rows="3" placeholder="Add any notes about the service request...">{{ $request->inspector_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection 