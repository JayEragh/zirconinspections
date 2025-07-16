@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Invoice Details</h1>
                <div>
                    <a href="{{ route('client.dashboard') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-tachometer-alt"></i> Back to Dashboard
                    </a>
                    <a href="{{ route('client.invoices') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Invoices
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Invoice Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                            <p><strong>Status:</strong> 
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'info',
                                        'paid' => 'success',
                                        'overdue' => 'danger',
                                        'cancelled' => 'secondary'
                                    ];
                                    $statusColor = $statusColors[$invoice->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ $invoice->getStatusWithOverdue() }}
                                </span>
                            </p>
                            <p><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</p>
                            @if($invoice->payment_deadline)
                            <p><strong>Payment Deadline:</strong> 
                                @if($invoice->isOverdue())
                                    <span class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $invoice->payment_deadline->format('M d, Y') }} (Overdue by {{ $invoice->getOverdueDays() }} days)
                                    </span>
                                @else
                                    <span class="{{ $invoice->getDaysUntilDeadline() <= 2 ? 'text-warning' : '' }}">
                                        {{ $invoice->payment_deadline->format('M d, Y') }}
                                        @if($invoice->getDaysUntilDeadline() > 0)
                                            ({{ $invoice->getDaysUntilDeadline() }} days left)
                                        @endif
                                    </span>
                                @endif
                            </p>
                            @endif
                            @if($invoice->paid_at)
                            <p><strong>Paid Date:</strong> {{ $invoice->paid_at->format('M d, Y H:i') }}</p>
                            @endif
                            @if($invoice->approved_at)
                            <p><strong>Approved Date:</strong> {{ $invoice->approved_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Created:</strong> {{ $invoice->created_at->format('M d, Y H:i') }}</p>
                            <p><strong>Last Updated:</strong> {{ $invoice->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Service Request Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Service Request #:</strong> {{ $invoice->serviceRequest->id }}</p>
                            <p><strong>Service Type:</strong> {{ ucfirst($invoice->serviceRequest->service_type) }}</p>
                            <p><strong>Depot:</strong> {{ $invoice->serviceRequest->depot }}</p>
                            <p><strong>Product:</strong> {{ $invoice->serviceRequest->product }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $invoice->serviceRequest->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($invoice->serviceRequest->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $invoice->description }}</p>
                </div>
            </div>

            @if($invoice->status === 'approved' || $invoice->status === 'overdue')
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Payment Evidence</h5>
                </div>
                <div class="card-body">
                    @if($invoice->payment_evidence)
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle"></i> Payment Evidence Uploaded</h6>
                            <p class="mb-2">Payment evidence has been uploaded for this invoice.</p>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt me-2"></i>
                                <span>{{ $invoice->payment_evidence_filename }}</span>
                                <a href="{{ $invoice->payment_evidence_url }}" target="_blank" class="btn btn-sm btn-outline-primary ms-3">
                                    <i class="fas fa-download"></i> View File
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Upload Payment Evidence</h6>
                            <p class="mb-3">Please upload proof of payment (receipt, bank transfer screenshot, etc.) to mark this invoice as paid.</p>
                            
                            <form action="{{ route('client.invoices.payment-evidence', $invoice) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="payment_evidence" class="form-label">Payment Evidence File</label>
                                    <input type="file" class="form-control @error('payment_evidence') is-invalid @enderror" 
                                           id="payment_evidence" name="payment_evidence" 
                                           accept=".pdf,.jpg,.jpeg,.png" required>
                                    <div class="form-text">
                                        Accepted formats: PDF, JPG, JPEG, PNG (max 5MB)
                                    </div>
                                    @error('payment_evidence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload"></i> Upload Payment Evidence
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Tax Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-8">Subtotal:</div>
                        <div class="col-4 text-end">{{ $invoice->formatted_amount }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">NHIL (2.5%):</div>
                        <div class="col-4 text-end">{{ $invoice->formatted_nhil_tax }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">GETFUND (2.5%):</div>
                        <div class="col-4 text-end">{{ $invoice->formatted_getfund_tax }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">COVID (1%):</div>
                        <div class="col-4 text-end">{{ $invoice->formatted_covid_tax }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">VAT (15%):</div>
                        <div class="col-4 text-end">{{ $invoice->formatted_vat }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-8"><strong>Total Amount:</strong></div>
                        <div class="col-4 text-end"><strong>{{ $invoice->formatted_total }}</strong></div>
                    </div>
                </div>
            </div>

            @if($invoice->status === 'approved' || $invoice->status === 'overdue')
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Payment Actions</h5>
                </div>
                <div class="card-body">
                    <a href="#" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </a>
                    <a href="#" class="btn btn-outline-primary w-100">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 