@extends('layouts.app')

@section('title', 'Inspector Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    Inspector Details
                </h2>
                <a href="{{ route('operations.inspectors') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Inspectors
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-id-badge me-2"></i>
                        Inspector Information
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Full Name</dt>
                        <dd class="col-sm-8">{{ $inspector->name }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $inspector->email }}</dd>

                        <dt class="col-sm-4">Phone</dt>
                        <dd class="col-sm-8">{{ $inspector->phone ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Address</dt>
                        <dd class="col-sm-8">{{ $inspector->address ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Specialization</dt>
                        <dd class="col-sm-8">{{ $inspector->specialization ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Certification Number</dt>
                        <dd class="col-sm-8">{{ $inspector->certification_number ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">{{ ucfirst($inspector->status ?? 'active') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        User Account
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">User Name</dt>
                        <dd class="col-sm-8">{{ $inspector->user->name }}</dd>

                        <dt class="col-sm-4">User Email</dt>
                        <dd class="col-sm-8">{{ $inspector->user->email }}</dd>

                        <dt class="col-sm-4">Role</dt>
                        <dd class="col-sm-8">{{ ucfirst($inspector->user->role) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Assigned Service Requests
                    </h6>
                </div>
                <div class="card-body">
                    @if($inspector->serviceRequests && $inspector->serviceRequests->count())
                        <ul class="list-group">
                            @foreach($inspector->serviceRequests as $request)
                                <li class="list-group-item">
                                    #{{ $request->id }} - {{ $request->status }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No service requests assigned.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 