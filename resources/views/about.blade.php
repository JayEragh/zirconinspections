@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">About Zircon Inspections</h1>
                    <p class="lead">
                        Trusted partner in petroleum stock monitoring, delivering accurate, independent oversight across the Oil & Gas supply chain.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <h2 class="mb-4">Our Mission</h2>
                            <p class="lead mb-4">
                                Our mission is simple: to protect the integrity and value of your cargo through every phase of storage, transfer, and handling.
                            </p>
                            
                            <h3 class="mb-3">Industry Expertise</h3>
                            <p class="mb-4">
                                With years of industry experience, we understand that effective stock monitoring is not just about auditing—it requires a deep knowledge of the physical and chemical behaviors of petroleum products, as well as the operational factors that impact them. Our expert inspectors provide actionable insights that help prevent losses, ensure compliance, and maintain product quality.
                            </p>
                            
                            <h3 class="mb-3">Global Network</h3>
                            <p class="mb-4">
                                Operating through a robust global network, we uphold the highest standards of consistency and professionalism in every engagement. Whether in-tank or offshore, we act as the eyes and ears of our clients—ensuring their stocks are accurately accounted for, secure, and in optimal condition.
                            </p>
                            
                            <h3 class="mb-3">Our Commitment</h3>
                            <p class="mb-4">
                                At Zircon Inspections, we don't just monitor stocks—we safeguard value. Our comprehensive approach to petroleum stock monitoring combines cutting-edge technology with experienced professionals to deliver reliable, accurate, and timely results.
                            </p>
                            
                            <div class="row mt-5">
                                <div class="col-md-4 text-center mb-4">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-shield-alt text-white" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5>Reliability</h5>
                                    <p class="text-muted">Consistent, accurate results you can depend on</p>
                                </div>
                                
                                <div class="col-md-4 text-center mb-4">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-globe text-white" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5>Global Reach</h5>
                                    <p class="text-muted">Worldwide network of certified inspectors</p>
                                </div>
                                
                                <div class="col-md-4 text-center mb-4">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-certificate text-white" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5>Expertise</h5>
                                    <p class="text-muted">Deep knowledge of petroleum products and processes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-6 fw-bold mb-4">Ready to Get Started?</h2>
                    <p class="lead mb-4">
                        Join the leading companies that trust Zircon Inspections for their petroleum stock monitoring needs.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-rocket me-2"></i>
                            Request a Service
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-envelope me-2"></i>
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 