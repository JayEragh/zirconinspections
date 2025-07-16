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
                                        <th>Total (with taxes)</th>
                                        <th>Status</th>
                                        <th>Payment Deadline</th>
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
                                        <td>
                                            <span class="badge bg-info">#{{ $invoice->serviceRequest->id }}</span>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($invoice->serviceRequest->service_type) }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $invoice->formatted_amount }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $invoice->formatted_total }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                NHIL: {{ $invoice->formatted_nhil_tax }} | 
                                                GETFUND: {{ $invoice->formatted_getfund_tax }} | 
                                                COVID: {{ $invoice->formatted_covid_tax }}
                                            </small>
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
                                            @if($invoice->payment_deadline)
                                                @if($invoice->isOverdue())
                                                    <span class="text-danger">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        {{ $invoice->payment_deadline->format('M d, Y') }}
                                                        <br>
                                                        <small>Overdue by {{ $invoice->getOverdueDays() }} days</small>
                                                    </span>
                                                @else
                                                    <span class="{{ $invoice->getDaysUntilDeadline() <= 2 ? 'text-warning' : '' }}">
                                                        {{ $invoice->payment_deadline->format('M d, Y') }}
                                                        @if($invoice->getDaysUntilDeadline() > 0)
                                                        <br>
                                                        <small>{{ $invoice->getDaysUntilDeadline() }} days left</small>
                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('client.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary" title="View Invoice">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($invoice->status === 'approved' || $invoice->status === 'overdue')
                                                <a href="#" class="btn btn-sm btn-outline-success" title="Pay Now">
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