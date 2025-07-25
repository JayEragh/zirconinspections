@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-users-cog me-2"></i>
                    User Management
                </h1>
                <div>
                    <a href="{{ route('operations.users.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        Add New User
                    </a>
                    <a href="{{ route('operations.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Dashboard
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

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-search me-2"></i>
                        Search & Filter Users
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('operations.users') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Name, email, or phone">
                            </div>
                            <div class="col-md-2">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">All Roles</option>
                                    <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>Client</option>
                                    <option value="inspector" {{ request('role') === 'inspector' ? 'selected' : '' }}>Inspector</option>
                                    <option value="operations" {{ request('role') === 'operations' ? 'selected' : '' }}>Operations</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="verified" class="form-label">Email Verified</label>
                                <select class="form-select" id="verified" name="verified">
                                    <option value="">All</option>
                                    <option value="verified" {{ request('verified') === 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="unverified" {{ request('verified') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>
                                        Search
                                    </button>
                                    <a href="{{ route('operations.users') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        All Users ({{ $users->total() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Email Verified</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-initial rounded-circle bg-{{ $user->role === 'operations' ? 'danger' : ($user->role === 'inspector' ? 'warning' : 'primary') }} text-white me-2" style="width: 32px; height: 32px; font-size: 14px; display: flex; align-items: center; justify-content: center;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    @if($user->client)
                                                        <br><small class="text-muted">{{ $user->client->company_name }}</small>
                                                    @elseif($user->inspector)
                                                        <br><small class="text-muted">{{ $user->inspector->specialization }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role === 'operations' ? 'danger' : ($user->role === 'inspector' ? 'warning' : 'primary') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->phone ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Unverified
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('operations.users.show', $user) }}">
                                                            <i class="fas fa-eye me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('operations.users.edit', $user) }}">
                                                            <i class="fas fa-edit me-2"></i>Edit User
                                                        </a>
                                                    </li>
                                                    @if($user->id !== Auth::id())
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST" action="{{ route('operations.users.toggle-status', $user) }}" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    @if($user->is_active)
                                                                        <i class="fas fa-ban me-2 text-warning"></i>Deactivate
                                                                    @else
                                                                        <i class="fas fa-check me-2 text-success"></i>Activate
                                                                    @endif
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form method="POST" action="{{ route('operations.users.delete', $user) }}" 
                                                                  class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash me-2"></i>Delete User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><span class="dropdown-item-text text-muted">Current User</span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No users found</h5>
                            @if(request()->hasAny(['search', 'role', 'status', 'verified']))
                                <p class="text-muted">Try adjusting your search criteria.</p>
                                <a href="{{ route('operations.users') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-times me-2"></i>Clear Filters
                                </a>
                            @else
                                <p class="text-muted">Start by creating a new user.</p>
                                <a href="{{ route('operations.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add First User
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 