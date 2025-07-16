@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Invoice Details</h1>
                <div>
                    <a href="{{ route('operations.invoices.edit', $invoice) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit Invoice
                    </a>
                    <a href="{{ route('operations.invoices') }}" class="btn btn-outline-secondary">
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
                                        'paid' => 'success',
                                        'overdue' => 'danger',
                                        'cancelled' => 'secondary'
                                    ];
                                    $statusColor = $statusColors[$invoice->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">{{ ucfirst($invoice->status) }}</span>
                            </p>
                            <p><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</p>
                            @if($invoice->paid_at)
                            <p><strong>Paid Date:</strong> {{ $invoice->paid_at->format('M d, Y H:i') }}</p>
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
                            <p><strong>Client:</strong> {{ $invoice->serviceRequest->client->user->name }}</p>
                            <p><strong>Client Email:</strong> {{ $invoice->serviceRequest->client->user->email }}</p>
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
                    <hr>
                    <div class="row">
                        <div class="col-8"><strong>Total Amount:</strong></div>
                        <div class="col-4 text-end"><strong>{{ $invoice->formatted_total }}</strong></div>
                    </div>
                </div>
            </div>

            @if($invoice->status === 'pending')
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('operations.invoices.mark-paid', $invoice) }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Mark as Paid
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 