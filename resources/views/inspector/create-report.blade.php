@extends('layouts.app')

@section('title', 'Create Report')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Create Report
                </h2>
                <a href="{{ route('inspector.reports') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Report Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('inspector.reports.store', $serviceRequest->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Report Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content">Report Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" 
                                      placeholder="Enter the main content of your report..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="findings">Findings <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('findings') is-invalid @enderror" 
                                      id="findings" name="findings" rows="4" 
                                      placeholder="Describe your findings from the inspection..." required>{{ old('findings') }}</textarea>
                            @error('findings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="recommendations">Recommendations <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('recommendations') is-invalid @enderror" 
                                      id="recommendations" name="recommendations" rows="4" 
                                      placeholder="Provide your recommendations based on the findings..." required>{{ old('recommendations') }}</textarea>
                            @error('recommendations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Create Report
                            </button>
                            <a href="{{ route('inspector.reports') }}" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Service Request Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Request Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Request #:</strong> {{ $serviceRequest->id }}</p>
                    <p><strong>Service ID:</strong> {{ $serviceRequest->service_id }}</p>
                    <p><strong>Service Type:</strong> {{ ucfirst($serviceRequest->service_type) }}</p>
                    <p><strong>Depot:</strong> {{ $serviceRequest->depot }}</p>
                    <p><strong>Product:</strong> {{ $serviceRequest->product }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $serviceRequest->status === 'completed' ? 'success' : ($serviceRequest->status === 'in_progress' ? 'warning' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Report Guidelines -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Report Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Be thorough and detailed in your findings
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Provide clear, actionable recommendations
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Use professional language and tone
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Include all relevant observations
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Save as draft if not ready to submit
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-resize textareas
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Roof weight field toggle
    const roofRadios = document.querySelectorAll('input[name="roof"]');
    const roofWeightGroup = document.getElementById('roof_weight_group');
    const roofWeightInput = document.getElementById('roof_weight');

    roofRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'yes') {
                roofWeightGroup.style.display = 'block';
                roofWeightInput.required = true;
            } else {
                roofWeightGroup.style.display = 'none';
                roofWeightInput.required = false;
                roofWeightInput.value = '';
            }
            calculateValues();
        });
    });

    // Auto-calculation fields
    const calculationFields = [
        'roof_weight', 'density', 'vcf', 'tov', 'water_vol'
    ];

    calculationFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', calculateValues);
        }
    });

    function calculateValues() {
        const roofWeight = parseFloat(document.getElementById('roof_weight').value) || 0;
        const density = parseFloat(document.getElementById('density').value) || 0;
        const vcf = parseFloat(document.getElementById('vcf').value) || 0;
        const tov = parseFloat(document.getElementById('tov').value) || 0;
        const waterVol = parseFloat(document.getElementById('water_vol').value) || 0;

        // Calculate Roof Volume: Roof weight ÷ (Density × VCF)
        let roofVol = 0;
        if (density > 0 && vcf > 0) {
            roofVol = roofWeight / (density * vcf);
        }
        document.getElementById('roof_vol').value = roofVol.toFixed(3);

        // Calculate GOV: TOV - Water Volume - Roof Volume
        const gov = tov - waterVol - roofVol;
        document.getElementById('gov').value = gov.toFixed(3);

        // Calculate GSV: GOV × VCF
        const gsv = gov * vcf;
        document.getElementById('gsv').value = gsv.toFixed(3);

        // Calculate MT Air: GSV × (Density - 0.0011)
        const mtAir = gsv * (density - 0.0011);
        document.getElementById('mt_air').value = mtAir.toFixed(3);
    }

    // Initialize calculations on page load
    calculateValues();
});
</script>
@endsection 