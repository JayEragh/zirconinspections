@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>
                    Invoices
                </h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Your Invoices
                    </h5>
                </div>
                <div class="card-body">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Service Request</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td>
                                            <strong>{{ $invoice->invoice_number }}</strong>
                                        </td>
                                        <td>{{ $invoice->serviceRequest->service_id }}</td>
                                        <td>
                                            <strong>${{ number_format($invoice->total_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'paid' => 'success',
                                                    'overdue' => 'danger',
                                                    'cancelled' => 'secondary'
                                                ];
                                                $statusColor = $statusColors[$invoice->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" class="btn btn-sm btn-outline-primary" title="View Invoice">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-success" title="Download PDF">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @if($invoice->status === 'pending')
                                                <a href="#" class="btn btn-sm btn-outline-info" title="Pay Now">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $invoices->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No invoices available</h5>
                            <p class="text-muted">Invoices will appear here once your service requests are completed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 