@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Create Invoice</h1>
                <a href="{{ route('operations.invoices') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Invoices
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Invoice Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('operations.invoices.store') }}" method="POST" id="invoice-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="service_request_id" class="form-label">Service Request</label>
                                <select name="service_request_id" id="service_request_id" class="form-select" required>
                                    <option value="">Select Service Request</option>
                                    @foreach($serviceRequests as $serviceRequest)
                                    <option value="{{ $serviceRequest->id }}" 
                                            data-client-id="{{ $serviceRequest->client_id }}"
                                            data-service-type="{{ $serviceRequest->service_type }}"
                                            data-depot="{{ $serviceRequest->depot }}"
                                            data-specific-instructions="{{ $serviceRequest->specific_instructions ?? '' }}">
                                        #{{ $serviceRequest->id }} - {{ ucfirst($serviceRequest->service_type) }} at {{ $serviceRequest->depot }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('service_request_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="client_id" class="form-label">Client</label>
                                <select name="client_id" id="client_id" class="form-select" required>
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount (GH₵)</label>
                                <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" required>
                                @error('amount')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" required>
                                @error('due_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4" required placeholder="Service description will be auto-populated when you select a service request..."></textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Tax Calculation Preview</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-8">Subtotal:</div>
                        <div class="col-4 text-end" id="subtotal">GH₵ 0.00</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">NHIL (2.5%):</div>
                        <div class="col-4 text-end" id="nhil-tax">GH₵ 0.00</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">GETFUND (2.5%):</div>
                        <div class="col-4 text-end" id="getfund-tax">GH₵ 0.00</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">COVID (1%):</div>
                        <div class="col-4 text-end" id="covid-tax">GH₵ 0.00</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-8"><strong>Total Amount:</strong></div>
                        <div class="col-4 text-end"><strong id="total-amount">GH₵ 0.00</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const serviceRequestSelect = document.getElementById('service_request_id');
    const clientSelect = document.getElementById('client_id');
    const descriptionTextarea = document.getElementById('description');
    
    // Auto-fill client and description when service request is selected
    serviceRequestSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const clientId = selectedOption.getAttribute('data-client-id');
            const specificInstructions = selectedOption.getAttribute('data-specific-instructions');
            
            // Auto-fill client
            clientSelect.value = clientId;
            
            // Auto-populate description with service request details
            const serviceType = selectedOption.getAttribute('data-service-type');
            const depot = selectedOption.getAttribute('data-depot');
            
            let description = `Service Type: ${serviceType}\nDepot: ${depot}`;
            
            if (specificInstructions && specificInstructions.trim() !== '') {
                description += `\n\nSpecific Instructions:\n${specificInstructions}`;
            }
            
            descriptionTextarea.value = description;
        } else {
            // Clear fields if no service request is selected
            clientSelect.value = '';
            descriptionTextarea.value = '';
        }
    });
    
    // Calculate taxes when amount changes
    amountInput.addEventListener('input', calculateTaxes);
    
    function calculateTaxes() {
        const amount = parseFloat(amountInput.value) || 0;
        const nhilTax = amount * 0.025;
        const getfundTax = amount * 0.025;
        const covidTax = amount * 0.01;
        const total = amount + nhilTax + getfundTax + covidTax;
        
        document.getElementById('subtotal').textContent = 'GH₵ ' + amount.toFixed(2);
        document.getElementById('nhil-tax').textContent = 'GH₵ ' + nhilTax.toFixed(2);
        document.getElementById('getfund-tax').textContent = 'GH₵ ' + getfundTax.toFixed(2);
        document.getElementById('covid-tax').textContent = 'GH₵ ' + covidTax.toFixed(2);
        document.getElementById('total-amount').textContent = 'GH₵ ' + total.toFixed(2);
    }
    
    // Set minimum due date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('due_date').min = tomorrow.toISOString().split('T')[0];
});
</script>
@endsection 