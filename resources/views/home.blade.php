@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        We don't just monitor stocks — we safeguard value.
                    </h1>
                    <p class="lead mb-4">
                        Trusted partner in petroleum stock monitoring, delivering accurate, independent oversight across the Oil & Gas supply chain.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg me-3">
                        <i class="fas fa-rocket me-2"></i>
                        Request a Service
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">
                        Learn More
                    </a>
                </div>
                <div class="col-lg-6 d-flex align-items-center justify-content-center" style="min-height: 280px;">
                    <img src="{{ asset('images/logo.svg') }}" alt="Zircon Inspections Logo" class="homepage-logo" style="max-width: 280px; width: 100%; height: auto; margin: 0 auto; display: block; background: transparent;">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-3">Our Services</h2>
                    <p class="lead text-muted">
                        Comprehensive petroleum stock monitoring and oversight services
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card h-100 text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-gas-pump"></i>
                        </div>
                        <h4>Petroleum Tank Stock Monitoring</h4>
                        <p class="text-muted">
                            Accurate monitoring and measurement of petroleum products in storage tanks with real-time reporting and alerts.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card service-card h-100 text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h4>Custody Transfer Oversight</h4>
                        <p class="text-muted">
                            Independent oversight of custody transfers ensuring accurate measurement and documentation of product movements.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card service-card h-100 text-center p-4">
                        <div class="service-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Stock Auditing & Reconciliation</h4>
                        <p class="text-muted">
                            Comprehensive auditing and reconciliation services to verify stock levels and identify discrepancies.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portal Access Section -->
    <section id="portals" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-3">Access Your Portal</h2>
                    <p class="lead text-muted">
                        Login to your dedicated portal to manage your services and view reports
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card portal-card h-100 text-center p-4">
                        <div class="portal-icon mb-3">
                            <i class="fas fa-user-tie text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4>Client Portal</h4>
                        <p class="text-muted mb-4">
                            Access your service requests, view reports, manage invoices, and communicate with our team.
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Client Login
                        </a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card portal-card h-100 text-center p-4">
                        <div class="portal-icon mb-3">
                            <i class="fas fa-clipboard-check text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4>Inspector Portal</h4>
                        <p class="text-muted mb-4">
                            Manage assigned inspections, create reports, and track your work progress.
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-success">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Inspector Login
                        </a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card portal-card h-100 text-center p-4">
                        <div class="portal-icon mb-3">
                            <i class="fas fa-cogs text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h4>Operations Portal</h4>
                        <p class="text-muted mb-4">
                            Manage clients, inspectors, service requests, and oversee all operations.
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-warning">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Operations Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-3">What Our Clients Say</h2>
                    <p class="lead text-muted">
                        Trusted by leading companies in the petroleum industry
                    </p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">John Smith</h6>
                                <small class="text-muted">Operations Manager, PetroCorp</small>
                            </div>
                        </div>
                        <p class="mb-0">
                            "Zircon Inspections has been instrumental in helping us maintain accurate stock records. Their attention to detail and professional approach gives us complete confidence in our inventory management."
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Sarah Johnson</h6>
                                <small class="text-muted">Logistics Director, OilFlow Ltd</small>
                            </div>
                        </div>
                        <p class="mb-0">
                            "The team at Zircon Inspections provides exceptional service. Their reports are comprehensive and their turnaround time is impressive. Highly recommended!"
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Michael Chen</h6>
                                <small class="text-muted">CEO, EnergyMax Solutions</small>
                            </div>
                        </div>
                        <p class="mb-0">
                            "We've been working with Zircon Inspections for over 5 years. Their expertise in petroleum stock monitoring has helped us optimize our operations and reduce losses significantly."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-4">Get In Touch</h2>
                    <p class="lead mb-4">
                        Ready to safeguard your petroleum stock value? Contact us today for a consultation.
                    </p>
                    
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6><i class="fas fa-envelope me-2 text-primary"></i>Email</h6>
                            <p class="text-muted">info@zirconinspections.com</p>
                        </div>
                        <div class="col-sm-6">
                            <h6><i class="fas fa-phone me-2 text-primary"></i>Phone</h6>
                            <p class="text-muted">+1 (555) 123-4567</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6><i class="fas fa-map-marker-alt me-2 text-primary"></i>Office Location</h6>
                        <p class="text-muted">
                            123 Petroleum Plaza<br>
                            Houston, TX 77001<br>
                            United States
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Send us a Message</h5>
                            
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            <form action="{{ route('contact.send') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 

@push('styles')
<style>
    .homepage-logo {
        max-width: 280px;
        width: 100%;
        height: auto;
        margin: 0 auto;
        display: block;
        background: transparent;
        filter: drop-shadow(0 2px 8px rgba(30,58,138,0.08));
    }
    @media (max-width: 991px) {
        .homepage-logo {
            max-width: 200px;
        }
    }
    @media (max-width: 576px) {
        .homepage-logo {
            max-width: 160px;
        }
    }
</style>
@endpush 