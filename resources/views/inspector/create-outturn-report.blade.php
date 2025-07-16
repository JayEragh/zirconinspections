@extends('layouts.app')

@section('title', 'Create Outturn Report')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Create Outturn Report
                </h2>
                <a href="{{ route('inspector.outturn-reports') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Outturn Reports
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('inspector.outturn-reports.store', $serviceRequest->id) }}" method="POST" id="outturn-form">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Report Details -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Report Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="report_title">Report Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('report_title') is-invalid @enderror" 
                                           id="report_title" name="report_title" value="{{ old('report_title') }}" required>
                                    @error('report_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="report_date">Report Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('report_date') is-invalid @enderror" 
                                           id="report_date" name="report_date" value="{{ old('report_date', date('Y-m-d')) }}" required>
                                    @error('report_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tank Data -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Tank Data</h6>
                        <button type="button" class="btn btn-success btn-sm" onclick="addTank()">
                            <i class="fas fa-plus me-2"></i>Add Tank
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="tanks-container">
                            @foreach($tankNumbers as $index => $tankNumber)
                                <div class="tank-card card mb-4" data-tank-id="{{ $index + 1 }}">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Tank: {{ $tankNumber }}</h6>
                                        @if($index > 0)
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeTank(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="tanks[{{ $index + 1 }}][tank_number]" value="{{ $tankNumber }}">
                                        
                                        <!-- Initial Data -->
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <h6 class="text-primary">Initial Data</h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Inspection Date</label>
                                                    <input type="date" class="form-control" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][inspection_date]" 
                                                           value="{{ date('Y-m-d') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Inspection Time</label>
                                                    <input type="time" class="form-control" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][inspection_time]" 
                                                           value="{{ date('H:i') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Product Gauge</label>
                                                    <input type="number" step="0.001" class="form-control initial-gauge" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][product_gauge]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Water Gauge</label>
                                                    <input type="number" step="0.001" class="form-control initial-gauge" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][water_gauge]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Temperature (°C)</label>
                                                    <input type="number" step="0.1" class="form-control initial-temp" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][temperature]" 
                                                           placeholder="0.0" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Density</label>
                                                    <input type="number" step="0.0001" class="form-control initial-density" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][density]" 
                                                           placeholder="0.0000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>VCF</label>
                                                    <input type="number" step="0.0001" class="form-control initial-vcf" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][vcf]" 
                                                           placeholder="0.0000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>TOV</label>
                                                    <input type="number" step="0.001" class="form-control initial-tov" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][tov]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Water Volume</label>
                                                    <input type="number" step="0.001" class="form-control initial-water" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][water_volume]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Has Roof</label>
                                                    <select class="form-control initial-roof" 
                                                            name="tanks[{{ $index + 1 }}][initial_data][has_roof]" required>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Roof Weight</label>
                                                    <input type="number" step="0.001" class="form-control initial-roof-weight" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][roof_weight]" 
                                                           placeholder="0.000">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Roof Volume</label>
                                                    <input type="number" step="0.001" class="form-control initial-roof-vol" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][roof_volume]" 
                                                           placeholder="0.000">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>GOV</label>
                                                    <input type="number" step="0.001" class="form-control initial-gov" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][gov]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>GSV</label>
                                                    <input type="number" step="0.001" class="form-control initial-gsv" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][gsv]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>MT Air</label>
                                                    <input type="number" step="0.001" class="form-control initial-mt-air" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][mt_air]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>MT Vac</label>
                                                    <input type="number" step="0.001" class="form-control initial-mt-vac" 
                                                           name="tanks[{{ $index + 1 }}][initial_data][mt_vac]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Notes</label>
                                                    <textarea class="form-control" 
                                                              name="tanks[{{ $index + 1 }}][initial_data][notes]" 
                                                              rows="2" placeholder="Initial data notes..."></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <!-- Final Data -->
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <h6 class="text-success">Final Data</h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Inspection Date</label>
                                                    <input type="date" class="form-control" 
                                                           name="tanks[{{ $index + 1 }}][final_data][inspection_date]" 
                                                           value="{{ date('Y-m-d') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Inspection Time</label>
                                                    <input type="time" class="form-control" 
                                                           name="tanks[{{ $index + 1 }}][final_data][inspection_time]" 
                                                           value="{{ date('H:i') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Product Gauge</label>
                                                    <input type="number" step="0.001" class="form-control final-gauge" 
                                                           name="tanks[{{ $index + 1 }}][final_data][product_gauge]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Water Gauge</label>
                                                    <input type="number" step="0.001" class="form-control final-gauge" 
                                                           name="tanks[{{ $index + 1 }}][final_data][water_gauge]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Temperature (°C)</label>
                                                    <input type="number" step="0.1" class="form-control final-temp" 
                                                           name="tanks[{{ $index + 1 }}][final_data][temperature]" 
                                                           placeholder="0.0" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Density</label>
                                                    <input type="number" step="0.0001" class="form-control final-density" 
                                                           name="tanks[{{ $index + 1 }}][final_data][density]" 
                                                           placeholder="0.0000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>VCF</label>
                                                    <input type="number" step="0.0001" class="form-control final-vcf" 
                                                           name="tanks[{{ $index + 1 }}][final_data][vcf]" 
                                                           placeholder="0.0000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>TOV</label>
                                                    <input type="number" step="0.001" class="form-control final-tov" 
                                                           name="tanks[{{ $index + 1 }}][final_data][tov]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Water Volume</label>
                                                    <input type="number" step="0.001" class="form-control final-water" 
                                                           name="tanks[{{ $index + 1 }}][final_data][water_volume]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Has Roof</label>
                                                    <select class="form-control final-roof" 
                                                            name="tanks[{{ $index + 1 }}][final_data][has_roof]" required>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Roof Weight</label>
                                                    <input type="number" step="0.001" class="form-control final-roof-weight" 
                                                           name="tanks[{{ $index + 1 }}][final_data][roof_weight]" 
                                                           placeholder="0.000">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Roof Volume</label>
                                                    <input type="number" step="0.001" class="form-control final-roof-vol" 
                                                           name="tanks[{{ $index + 1 }}][final_data][roof_volume]" 
                                                           placeholder="0.000">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>GOV</label>
                                                    <input type="number" step="0.001" class="form-control final-gov" 
                                                           name="tanks[{{ $index + 1 }}][final_data][gov]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>GSV</label>
                                                    <input type="number" step="0.001" class="form-control final-gsv" 
                                                           name="tanks[{{ $index + 1 }}][final_data][gsv]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>MT Air</label>
                                                    <input type="number" step="0.001" class="form-control final-mt-air" 
                                                           name="tanks[{{ $index + 1 }}][final_data][mt_air]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>MT Vac</label>
                                                    <input type="number" step="0.001" class="form-control final-mt-vac" 
                                                           name="tanks[{{ $index + 1 }}][final_data][mt_vac]" 
                                                           placeholder="0.000" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Notes</label>
                                                    <textarea class="form-control" 
                                                              name="tanks[{{ $index + 1 }}][final_data][notes]" 
                                                              rows="2" placeholder="Final data notes..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('inspector.outturn-reports') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Create Outturn Report
                    </button>
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

                <!-- Guidelines -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">Outturn Report Guidelines</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Enter initial data before transfer begins
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Enter final data after transfer is complete
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Ensure all measurements are accurate
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Include all relevant tank numbers
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Add notes for any special conditions
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let tankCounter = {{ count($tankNumbers) }};

function addTank() {
    tankCounter++;
    const tankNumber = prompt('Enter tank number:');
    if (!tankNumber) return;
    
    const container = document.getElementById('tanks-container');
    const tankCard = document.querySelector('.tank-card').cloneNode(true);
    
    // Update tank number
    tankCard.querySelector('.card-header h6').textContent = 'Tank: ' + tankNumber;
    tankCard.querySelector('input[name*="[tank_number]"]').value = tankNumber;
    
    // Update all input names
    const inputs = tankCard.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.name) {
            input.name = input.name.replace(/tanks\[\d+\]/, `tanks[${tankCounter}]`);
        }
    });
    
    // Clear all values
    inputs.forEach(input => {
        if (input.type !== 'hidden') {
            input.value = '';
        }
    });
    
    // Set default dates and times
    tankCard.querySelectorAll('input[type="date"]').forEach(input => {
        input.value = new Date().toISOString().split('T')[0];
    });
    tankCard.querySelectorAll('input[type="time"]').forEach(input => {
        input.value = new Date().toTimeString().slice(0, 5);
    });
    
    // Show remove button
    const removeBtn = tankCard.querySelector('.btn-danger');
    if (removeBtn) {
        removeBtn.style.display = 'block';
    }
    
    container.appendChild(tankCard);
}

function removeTank(button) {
    if (confirm('Are you sure you want to remove this tank?')) {
        button.closest('.tank-card').remove();
    }
}

// Auto-calculation for initial data
document.addEventListener('DOMContentLoaded', function() {
    const tankCards = document.querySelectorAll('.tank-card');
    
    tankCards.forEach(card => {
        // Initial data calculations
        const initialInputs = card.querySelectorAll('.initial-tov, .initial-water, .initial-roof-vol, .initial-vcf, .initial-density');
        initialInputs.forEach(input => {
            input.addEventListener('input', () => calculateInitialValues(card));
        });
        
        // Final data calculations
        const finalInputs = card.querySelectorAll('.final-tov, .final-water, .final-roof-vol, .final-vcf, .final-density');
        finalInputs.forEach(input => {
            input.addEventListener('input', () => calculateFinalValues(card));
        });
    });
});

function calculateInitialValues(card) {
    const tov = parseFloat(card.querySelector('.initial-tov').value) || 0;
    const waterVol = parseFloat(card.querySelector('.initial-water').value) || 0;
    const roofVol = parseFloat(card.querySelector('.initial-roof-vol').value) || 0;
    const vcf = parseFloat(card.querySelector('.initial-vcf').value) || 0;
    const density = parseFloat(card.querySelector('.initial-density').value) || 0;
    
    // Calculate GOV: TOV - Water Volume - Roof Volume
    const gov = tov - waterVol - roofVol;
    card.querySelector('.initial-gov').value = gov.toFixed(3);
    
    // Calculate GSV: GOV × VCF
    const gsv = gov * vcf;
    card.querySelector('.initial-gsv').value = gsv.toFixed(3);
    
    // Calculate MT Air: GSV × (Density - 0.0011)
    const mtAir = gsv * (density - 0.0011);
    card.querySelector('.initial-mt-air').value = mtAir.toFixed(3);
    
    // Calculate MT Vac: GSV × (Density - 0.0011)
    const mtVac = gsv * (density - 0.0011);
    card.querySelector('.initial-mt-vac').value = mtVac.toFixed(3);
}

function calculateFinalValues(card) {
    const tov = parseFloat(card.querySelector('.final-tov').value) || 0;
    const waterVol = parseFloat(card.querySelector('.final-water').value) || 0;
    const roofVol = parseFloat(card.querySelector('.final-roof-vol').value) || 0;
    const vcf = parseFloat(card.querySelector('.final-vcf').value) || 0;
    const density = parseFloat(card.querySelector('.final-density').value) || 0;
    
    // Calculate GOV: TOV - Water Volume - Roof Volume
    const gov = tov - waterVol - roofVol;
    card.querySelector('.final-gov').value = gov.toFixed(3);
    
    // Calculate GSV: GOV × VCF
    const gsv = gov * vcf;
    card.querySelector('.final-gsv').value = gsv.toFixed(3);
    
    // Calculate MT Air: GSV × (Density - 0.0011)
    const mtAir = gsv * (density - 0.0011);
    card.querySelector('.final-mt-air').value = mtAir.toFixed(3);
    
    // Calculate MT Vac: GSV × (Density - 0.0011)
    const mtVac = gsv * (density - 0.0011);
    card.querySelector('.final-mt-vac').value = mtVac.toFixed(3);
}
</script>
@endsection 