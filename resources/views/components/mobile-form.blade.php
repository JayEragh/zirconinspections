@props(['action', 'method' => 'POST', 'enctype' => null])

<form action="{{ $action }}" method="{{ $method }}" @if($enctype) enctype="{{ $enctype }}" @endif class="mobile-form">
    @csrf
    @if($method !== 'GET' && $method !== 'POST')
        @method($method)
    @endif
    
    {{ $slot }}
</form>

<style>
.mobile-form {
    max-width: 100%;
}

.mobile-form .form-control,
.mobile-form .form-select {
    font-size: 16px; /* Prevents zoom on iOS */
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.mobile-form .form-control:focus,
.mobile-form .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.mobile-form .form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.mobile-form .form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.mobile-form .btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}

.mobile-form .btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.mobile-form .btn-primary:hover {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.mobile-form .form-check {
    margin-bottom: 0.5rem;
}

.mobile-form .form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.mobile-form .form-check-label {
    font-size: 0.875rem;
}

/* Mobile-specific improvements */
@media (max-width: 768px) {
    .mobile-form .form-control,
    .mobile-form .form-select {
        padding: 0.75rem 1rem;
        font-size: 16px;
    }
    
    .mobile-form .form-label {
        font-size: 0.875rem;
        margin-bottom: 0.375rem;
    }
    
    .mobile-form .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
    }
    
    .mobile-form .form-text {
        font-size: 0.75rem;
    }
    
    .mobile-form .form-check-label {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .mobile-form .form-control,
    .mobile-form .form-select {
        padding: 0.625rem 0.875rem;
    }
    
    .mobile-form .btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.8rem;
    }
    
    .mobile-form .form-label {
        font-size: 0.8rem;
    }
}

/* Improved form validation styling */
.mobile-form .form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.mobile-form .form-control.is-valid {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.mobile-form .invalid-feedback {
    font-size: 0.75rem;
    color: #dc3545;
}

.mobile-form .valid-feedback {
    font-size: 0.75rem;
    color: #198754;
}

/* Form group spacing */
.mobile-form .form-group {
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    .mobile-form .form-group {
        margin-bottom: 1.25rem;
    }
}

@media (max-width: 576px) {
    .mobile-form .form-group {
        margin-bottom: 1rem;
    }
}

/* Floating labels support */
.mobile-form .form-floating .form-control {
    height: calc(3.5rem + 2px);
    line-height: 1.25;
}

.mobile-form .form-floating .form-control:focus {
    height: calc(3.5rem + 2px);
}

.mobile-form .form-floating .form-control:focus ~ .form-label,
.mobile-form .form-floating .form-control:not(:placeholder-shown) ~ .form-label {
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    color: var(--primary-color);
}

/* File input styling */
.mobile-form .form-control[type="file"] {
    padding: 0.375rem 0.75rem;
}

@media (max-width: 768px) {
    .mobile-form .form-control[type="file"] {
        padding: 0.5rem 1rem;
    }
}
</style> 