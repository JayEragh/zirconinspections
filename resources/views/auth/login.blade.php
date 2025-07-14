@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Login to Your Account
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Demo Account Credentials -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Demo Account Credentials
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Client:</strong><br>
                                Email: client@demo.com<br>
                                Password: password
                            </div>
                            <div class="col-md-4">
                                <strong>Inspector:</strong><br>
                                Email: inspector@demo.com<br>
                                Password: password
                            </div>
                            <div class="col-md-4">
                                <strong>Operations:</strong><br>
                                Email: operations@demo.com<br>
                                Password: password
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Don't have an account?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary mt-2">
                            <i class="fas fa-user-plus me-2"></i>
                            Register Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 