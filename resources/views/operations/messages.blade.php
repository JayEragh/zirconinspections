@extends('layouts.app')

@section('title', 'Messages Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Messages Management
                </h1>
                <a href="{{ route('operations.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        All Messages
                    </h5>
                </div>
                <div class="card-body">
                    @if($messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Subject</th>
                                        <th>Service Request</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                    <tr class="{{ $message->read_at ? '' : 'table-warning' }}">
                                        <td>
                                            <strong>{{ $message->sender->name }}</strong>
                                            @if(!$message->read_at)
                                                <span class="badge bg-danger ms-2">New</span>
                                            @endif
                                        </td>
                                        <td>{{ $message->recipient->name }}</td>
                                        <td>{{ $message->subject }}</td>
                                        <td>
                                            @if($message->serviceRequest)
                                                <span class="badge bg-info">{{ $message->serviceRequest->service_id }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $message->read_at ? 'success' : 'warning' }}">
                                                {{ $message->read_at ? 'Read' : 'Unread' }}
                                            </span>
                                        </td>
                                        <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('operations.messages.show', $message) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="View Message">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No messages found</h5>
                            <p class="text-muted">Messages will appear here once users start communicating.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 