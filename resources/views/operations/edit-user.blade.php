@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-user-edit me-2"></i>
                    Edit User
                </h1>
                <div>
                    <a href="{{ route('operations.users.show', $user) }}" class="btn btn-outline-info me-2">
                        <i class="fas fa-eye me-2"></i>
                        View Details
                    </a>
                    <a href="{{ route('operations.users') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6><i class="fas fa-exclamation-circle me-2"></i>Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-form me-2"></i>
                        Edit User: {{ $user->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('operations.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Basic Information
                                </h6>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave blank to keep current password. Minimum 8 characters if changing.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required onchange="toggleRoleFields()">
                                    <option value="">Select Role</option>
                                    <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Client</option>
                                    <option value="inspector" {{ old('role', $user->role) === 'inspector' ? 'selected' : '' }}>Inspector</option>
                                    <option value="operations" {{ old('role', $user->role) === 'operations' ? 'selected' : '' }}>Operations</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($user->role !== old('role', $user->role))
                                    <div class="form-text text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Changing role will update user permissions and may delete role-specific data.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role-Specific Fields -->
                        <div id="client-fields" class="role-fields" style="display: none;">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-info border-bottom pb-2 mb-3">
                                        <i class="fas fa-building me-2"></i>Client Information
                                    </h6>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                           id="company_name" name="company_name" 
                                           value="{{ old('company_name', $user->client->company_name ?? '') }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="inspector-fields" class="role-fields" style="display: none;">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-warning border-bottom pb-2 mb-3">
                                        <i class="fas fa-user-tie me-2"></i>Inspector Information
                                    </h6>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="specialization" class="form-label">Specialization <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                           id="specialization" name="specialization" 
                                           value="{{ old('specialization', $user->inspector->specialization ?? '') }}"
                                           placeholder="e.g., Grain Inspection, Commodity Analysis">
                                    @error('specialization')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                           id="license_number" name="license_number" 
                                           value="{{ old('license_number', $user->inspector->license_number ?? '') }}">
                                    @error('license_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-secondary border-bottom pb-2 mb-3">
                                    <i class="fas fa-cog me-2"></i>Account Settings
                                </h6>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                           @if($user->id === Auth::id()) disabled @endif>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Active Account</strong>
                                        <div class="form-text">
                                            @if($user->id === Auth::id())
                                                Cannot deactivate your own account
                                            @else
                                                User can login and access the system
                                            @endif
                                        </div>
                                    </label>
                                </div>
                                @if($user->id === Auth::id())
                                    <input type="hidden" name="is_active" value="1">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" 
                                           {{ old('email_verified', $user->email_verified_at ? true : false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_verified">
                                        <strong>Email Verified</strong>
                                        <div class="form-text">Mark email as verified</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-secondary border-bottom pb-2 mb-3">
                                    <i class="fas fa-bell me-2"></i>Notification Preferences
                                </h6>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifications_email" name="notifications_email" value="1" 
                                           {{ old('notifications_email', $user->notifications_email) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifications_email">
                                        <strong>Email Notifications</strong>
                                        <div class="form-text">Receive notifications via email</div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifications_sms" name="notifications_sms" value="1" 
                                           {{ old('notifications_sms', $user->notifications_sms) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifications_sms">
                                        <strong>SMS Notifications</strong>
                                        <div class="form-text">Receive notifications via SMS</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- User Information Summary -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-secondary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Account Information
                                </h6>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <small class="text-muted">Created:</small><br>
                                <strong>{{ $user->created_at->format('M d, Y H:i') }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Last Updated:</small><br>
                                <strong>{{ $user->updated_at->format('M d, Y H:i') }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">User ID:</small><br>
                                <strong>#{{ $user->id }}</strong>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('operations.users.show', $user) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRoleFields() {
    const role = document.getElementById('role').value;
    const roleFields = document.querySelectorAll('.role-fields');
    
    // Hide all role-specific fields
    roleFields.forEach(field => {
        field.style.display = 'none';
    });
    
    // Show relevant fields based on selected role
    if (role === 'client') {
        document.getElementById('client-fields').style.display = 'block';
    } else if (role === 'inspector') {
        document.getElementById('inspector-fields').style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleRoleFields();
});
</script>
@endsection 