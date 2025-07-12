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

                        <!-- Technical Inspection Data -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Technical Inspection Data</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_time">Date/Time</label>
                                            <input type="datetime-local" class="form-control" id="date_time" name="date_time" 
                                                   value="{{ old('date_time', now()->format('Y-m-d\TH:i')) }}" readonly>
                                            <small class="form-text text-muted">Auto-generated</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tank_number">Tank Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('tank_number') is-invalid @enderror" 
                                                   id="tank_number" name="tank_number" value="{{ old('tank_number') }}" required>
                                            @error('tank_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="product_gauge">Product Gauge <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('product_gauge') is-invalid @enderror" 
                                                   id="product_gauge" name="product_gauge" value="{{ old('product_gauge') }}" required>
                                            @error('product_gauge')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="h20_gauge">H20 Gauge <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('h20_gauge') is-invalid @enderror" 
                                                   id="h20_gauge" name="h20_gauge" value="{{ old('h20_gauge') }}" required>
                                            @error('h20_gauge')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="temperature">Temperature (°C) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.1" class="form-control @error('temperature') is-invalid @enderror" 
                                                   id="temperature" name="temperature" value="{{ old('temperature') }}" required>
                                            @error('temperature')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Roof</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="roof" id="roof_yes" value="yes" 
                                                       {{ old('roof') === 'yes' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="roof_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="roof" id="roof_no" value="no" 
                                                       {{ old('roof') === 'no' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="roof_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="roof_weight_group" style="display: none;">
                                            <label for="roof_weight">Roof Weight</label>
                                            <input type="number" step="0.01" class="form-control" id="roof_weight" name="roof_weight" 
                                                   value="{{ old('roof_weight') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="density">Density (@ 20°C) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.001" class="form-control @error('density') is-invalid @enderror" 
                                                   id="density" name="density" value="{{ old('density') }}" required>
                                            @error('density')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="vcf">VCF (ASTM 60 B) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.0001" class="form-control @error('vcf') is-invalid @enderror" 
                                                   id="vcf" name="vcf" value="{{ old('vcf') }}" required>
                                            @error('vcf')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tov">TOV <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('tov') is-invalid @enderror" 
                                                   id="tov" name="tov" value="{{ old('tov') }}" required>
                                            @error('tov')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="water_vol">Water Volume <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('water_vol') is-invalid @enderror" 
                                                   id="water_vol" name="water_vol" value="{{ old('water_vol') }}" required>
                                            @error('water_vol')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="roof_vol">Roof Volume (Calculated)</label>
                                            <input type="number" step="0.01" class="form-control" id="roof_vol" name="roof_vol" readonly>
                                            <small class="form-text text-muted">Roof weight ÷ (Density × VCF)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gov">GOV (Calculated)</label>
                                            <input type="number" step="0.01" class="form-control" id="gov" name="gov" readonly>
                                            <small class="form-text text-muted">TOV - Water Volume - Roof Volume</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gsv">GSV (Calculated)</label>
                                            <input type="number" step="0.01" class="form-control" id="gsv" name="gsv" readonly>
                                            <small class="form-text text-muted">GOV × VCF</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mt_air">MT Air (Calculated)</label>
                                            <input type="number" step="0.001" class="form-control" id="mt_air" name="mt_air" readonly>
                                            <small class="form-text text-muted">GSV × (Density - 0.0011)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="file_upload">File Upload</label>
                                    <input type="file" class="form-control @error('file_upload') is-invalid @enderror" 
                                           id="file_upload" name="file_upload" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG</small>
                                    @error('file_upload')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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

            <!-- Client Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Client Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $serviceRequest->client->name }}</p>
                    <p><strong>Email:</strong> {{ $serviceRequest->client->email }}</p>
                    <p><strong>Phone:</strong> {{ $serviceRequest->client->phone }}</p>
                    <p><strong>Address:</strong> {{ $serviceRequest->client->address }}</p>
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
        document.getElementById('roof_vol').value = roofVol.toFixed(2);

        // Calculate GOV: TOV - Water Volume - Roof Volume
        const gov = tov - waterVol - roofVol;
        document.getElementById('gov').value = gov.toFixed(2);

        // Calculate GSV: GOV × VCF
        const gsv = gov * vcf;
        document.getElementById('gsv').value = gsv.toFixed(2);

        // Calculate MT Air: GSV × (Density - 0.0011)
        const mtAir = gsv * (density - 0.0011);
        document.getElementById('mt_air').value = mtAir.toFixed(3);
    }

    // Initialize calculations on page load
    calculateValues();
});
</script>
@endsection 