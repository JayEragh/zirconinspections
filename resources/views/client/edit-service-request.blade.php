@extends('layouts.app')

@section('title', 'Edit Service Request')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Service Request
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('client.service-requests.update', $serviceRequest) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="depot" class="form-label">Depot/Location *</label>
                                <select class="form-select @error('depot') is-invalid @enderror" 
                                        id="depot" name="depot" required>
                                    <option value="">Select Depot</option>
                                    <option value="TOR" {{ old('depot', $serviceRequest->depot) == 'TOR' ? 'selected' : '' }}>TOR</option>
                                    <option value="VANA" {{ old('depot', $serviceRequest->depot) == 'VANA' ? 'selected' : '' }}>VANA</option>
                                    <option value="TMPT" {{ old('depot', $serviceRequest->depot) == 'TMPT' ? 'selected' : '' }}>TMPT</option>
                                    <option value="TFT" {{ old('depot', $serviceRequest->depot) == 'TFT' ? 'selected' : '' }}>TFT</option>
                                    <option value="PETHUB" {{ old('depot', $serviceRequest->depot) == 'PETHUB' ? 'selected' : '' }}>PETHUB</option>
                                    <option value="MATRIX" {{ old('depot', $serviceRequest->depot) == 'MATRIX' ? 'selected' : '' }}>MATRIX</option>
                                </select>
                                @error('depot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="product" class="form-label">Product *</label>
                                <select class="form-select @error('product') is-invalid @enderror" 
                                        id="product" name="product" required>
                                    <option value="">Select Product</option>
                                    <option value="Gasoline" {{ old('product', $serviceRequest->product) == 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                                    <option value="Gasoil" {{ old('product', $serviceRequest->product) == 'Gasoil' ? 'selected' : '' }}>Gasoil</option>
                                    <option value="ATK" {{ old('product', $serviceRequest->product) == 'ATK' ? 'selected' : '' }}>ATK</option>
                                    <option value="Kerosene" {{ old('product', $serviceRequest->product) == 'Kerosene' ? 'selected' : '' }}>Kerosene</option>
                                    <option value="Fuel Oil" {{ old('product', $serviceRequest->product) == 'Fuel Oil' ? 'selected' : '' }}>Fuel Oil</option>
                                    <option value="LPG" {{ old('product', $serviceRequest->product) == 'LPG' ? 'selected' : '' }}>LPG</option>
                                    <option value="Premix" {{ old('product', $serviceRequest->product) == 'Premix' ? 'selected' : '' }}>Premix</option>
                                </select>
                                @error('product')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity_gsv" class="form-label">Quantity (GSV) *</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('quantity_gsv') is-invalid @enderror" 
                                           id="quantity_gsv" name="quantity_gsv" value="{{ old('quantity_gsv', $serviceRequest->quantity_gsv) }}" required>
                                    <span class="input-group-text">liters</span>
                                </div>
                                @error('quantity_gsv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="quantity_mt" class="form-label">Quantity (MT) *</label>
                                <div class="input-group">
                                    <input type="number" step="0.001" class="form-control @error('quantity_mt') is-invalid @enderror" 
                                           id="quantity_mt" name="quantity_mt" value="{{ old('quantity_mt', $serviceRequest->quantity_mt) }}" required>
                                    <span class="input-group-text">MT</span>
                                </div>
                                @error('quantity_mt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tank_numbers" class="form-label">Tank Numbers *</label>
                                <input type="text" class="form-control @error('tank_numbers') is-invalid @enderror" 
                                       id="tank_numbers" name="tank_numbers" value="{{ old('tank_numbers', $serviceRequest->tank_numbers) }}" 
                                       placeholder="e.g., T-001, T-002" required>
                                @error('tank_numbers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="service_type" class="form-label">Service Type *</label>
                                <select class="form-select @error('service_type') is-invalid @enderror" 
                                        id="service_type" name="service_type" required>
                                    <option value="">Select Service Type</option>
                                    <option value="in_tank_stock_monitoring" {{ old('service_type', $serviceRequest->service_type) == 'in_tank_stock_monitoring' ? 'selected' : '' }}>
                                        In Tank Stock Monitoring
                                    </option>
                                    <option value="inter_depot_product_transfer" {{ old('service_type', $serviceRequest->service_type) == 'inter_depot_product_transfer' ? 'selected' : '' }}>
                                        Inter Depot Product Transfer
                                    </option>
                                    <option value="in_tank_product_transfer" {{ old('service_type', $serviceRequest->service_type) == 'in_tank_product_transfer' ? 'selected' : '' }}>
                                        In Tank Product Transfer
                                    </option>
                                    <option value="product_transfer_audit" {{ old('service_type', $serviceRequest->service_type) == 'product_transfer_audit' ? 'selected' : '' }}>
                                        Product Transfer Audit
                                    </option>
                                    <option value="customized_request" {{ old('service_type', $serviceRequest->service_type) == 'customized_request' ? 'selected' : '' }}>
                                        Customized Request
                                    </option>
                                </select>
                                @error('service_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="specific_instructions" class="form-label">Specific Instructions</label>
                            <textarea class="form-control @error('specific_instructions') is-invalid @enderror" 
                                      id="specific_instructions" name="specific_instructions" rows="4" 
                                      placeholder="Any specific requirements or instructions for the inspection...">{{ old('specific_instructions', $serviceRequest->specific_instructions) }}</textarea>
                            @error('specific_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="outturn_file" class="form-label">Outturn Report (PDF)</label>
                                <input type="file" class="form-control @error('outturn_file') is-invalid @enderror" 
                                       id="outturn_file" name="outturn_file" accept=".pdf">
                                <div class="form-text">Upload outturn report if available (max 2MB)</div>
                                @if($serviceRequest->outturn_file)
                                    <small class="text-muted">Current file: <a href="{{ Storage::url($serviceRequest->outturn_file) }}" target="_blank">View current file</a></small>
                                @endif
                                @error('outturn_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="quality_certificate_file" class="form-label">Quality Certificate (PDF)</label>
                                <input type="file" class="form-control @error('quality_certificate_file') is-invalid @enderror" 
                                       id="quality_certificate_file" name="quality_certificate_file" accept=".pdf">
                                <div class="form-text">Upload quality certificate if available (max 2MB)</div>
                                @if($serviceRequest->quality_certificate_file)
                                    <small class="text-muted">Current file: <a href="{{ Storage::url($serviceRequest->quality_certificate_file) }}" target="_blank">View current file</a></small>
                                @endif
                                @error('quality_certificate_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('client.service-requests') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Requests
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>
                                Update Service Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 