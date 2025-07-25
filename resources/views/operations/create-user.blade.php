@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Create New User
                </h1>
                <a href="{{ route('operations.users') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Users
                </a>
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
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-form me-2"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('operations.users.store') }}">
                        @csrf

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
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required onchange="toggleRoleFields()">
                                    <option value="">Select Role</option>
                                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                                    <option value="inspector" {{ old('role') === 'inspector' ? 'selected' : '' }}>Inspector</option>
                                    <option value="operations" {{ old('role') === 'operations' ? 'selected' : '' }}>Operations</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address') }}</textarea>
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
                                           id="company_name" name="company_name" value="{{ old('company_name') }}">
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
                                           id="specialization" name="specialization" value="{{ old('specialization') }}"
                                           placeholder="e.g., Grain Inspection, Commodity Analysis">
                                    @error('specialization')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                           id="license_number" name="license_number" value="{{ old('license_number') }}">
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
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Active Account</strong>
                                        <div class="form-text">User can login and access the system</div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" 
                                           {{ old('email_verified', true) ? 'checked' : '' }}>
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
                                           {{ old('notifications_email', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifications_email">
                                        <strong>Email Notifications</strong>
                                        <div class="form-text">Receive notifications via email</div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifications_sms" name="notifications_sms" value="1" 
                                           {{ old('notifications_sms', false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifications_sms">
                                        <strong>SMS Notifications</strong>
                                        <div class="form-text">Receive notifications via SMS</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('operations.users') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create User
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