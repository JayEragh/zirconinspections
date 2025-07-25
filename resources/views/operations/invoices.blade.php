@extends('layouts.app')

@section('title', 'Invoices Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>
                    Invoices Management
                </h1>
                <div>
                    <a href="{{ route('operations.invoices.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        Create Invoice
                    </a>
                    <a href="{{ route('operations.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        All Invoices
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
                                        <th>Client</th>
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
                                        <td>{{ $invoice->serviceRequest->client->user->name }}</td>
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
                                            @if($invoice->approved_at)
                                            <br>
                                            <small class="text-muted">Approved: {{ $invoice->approved_at->format('M d, Y') }}</small>
                                            @endif
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
                                                <a href="{{ route('operations.invoices.show', $invoice) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Invoice">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('operations.invoices.edit', $invoice) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit Invoice">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($invoice->status === 'pending')
                                                <form action="{{ route('operations.invoices.approve', $invoice) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-info" title="Approve & Send to Client">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if($invoice->status === 'approved' && $invoice->status !== 'paid')
                                                <form action="{{ route('operations.invoices.undo-approval', $invoice) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Undo Approval" 
                                                            onclick="return confirm('Are you sure you want to undo the approval for this invoice? This will revert it to draft status and notify the client.')">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if($invoice->status === 'approved' && $invoice->isOverdue() && !$invoice->overdue_notification_sent)
                                                <form action="{{ route('operations.invoices.send-overdue-notification', $invoice) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Send Overdue Notification">
                                                        <i class="fas fa-bell"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if($invoice->status === 'pending')
                                                <form action="{{ route('operations.invoices.mark-paid', $invoice) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as Paid">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                <form action="{{ route('operations.invoices.delete', $invoice) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Invoice" 
                                                            onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
                            <h5 class="text-muted">No invoices found</h5>
                            <p class="text-muted">Invoices will appear here once service requests are completed.</p>
                            <a href="{{ route('operations.invoices.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Create Your First Invoice
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 