@extends('layouts.app')

@section('title', 'Inspectors Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    Inspectors Management
                </h1>
                <div>
                    <a href="{{ route('operations.inspectors.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        Create Inspector
                    </a>
                    <a href="{{ route('operations.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        All Inspectors
                    </h5>
                </div>
                <div class="card-body">
                    @if($inspectors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Inspector Name</th>
                                        <th>Employee ID</th>
                                        <th>Certification</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Assigned Jobs</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inspectors as $inspector)
                                    <!-- Debug: Inspector ID: {{ $inspector->id }}, Name: {{ $inspector->name }}, User: {{ $inspector->user ? $inspector->user->name : 'No user' }} -->
                                    <tr>
                                        <td>
                                            <strong>{{ $inspector->user->name }}</strong>
                                        </td>
                                        <td>{{ $inspector->employee_id }}</td>
                                        <td>{{ $inspector->certification_number ?? 'N/A' }}</td>
                                        <td>{{ $inspector->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $inspector->is_active ? 'success' : 'danger' }}">
                                                {{ $inspector->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $inspector->serviceRequests ? $inspector->serviceRequests->count() : 0 }}</span>
                                        </td>
                                        <td>{{ $inspector->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('operations.inspectors.show', $inspector) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $inspectors->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No inspectors found</h5>
                            <p class="text-muted">Inspectors will appear here once they are registered.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 