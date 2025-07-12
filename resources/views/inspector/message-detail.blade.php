@extends('layouts.app')

@section('title', 'Message Details')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Message Details
                </h2>
                <a href="{{ route('inspector.messages') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Messages
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-envelope-open me-2"></i>
                            {{ $message->subject }}
                        </h5>
                        <span class="badge bg-{{ $message->read ? 'success' : 'warning' }}">
                            {{ $message->read ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>From:</strong></h6>
                            <p>{{ $message->sender->name }} ({{ $message->sender->email }})</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>To:</strong></h6>
                            <p>{{ $message->recipient->name }} ({{ $message->recipient->email }})</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>Date:</strong></h6>
                            <p>{{ $message->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Status:</strong></h6>
                            <p>{{ $message->read ? 'Read' : 'Unread' }}</p>
                        </div>
                    </div>

                    @if($message->serviceRequest)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6><strong>Related Service Request:</strong></h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p><strong>Service ID:</strong> {{ $message->serviceRequest->service_id }}</p>
                                    <p><strong>Service Type:</strong> {{ ucfirst($message->serviceRequest->service_type) }}</p>
                                    <p><strong>Depot:</strong> {{ $message->serviceRequest->depot }}</p>
                                    <p><strong>Product:</strong> {{ $message->serviceRequest->product }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $message->serviceRequest->status === 'completed' ? 'success' : ($message->serviceRequest->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $message->serviceRequest->status)) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <h6><strong>Message:</strong></h6>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Message Information
                    </h6>
                </div>
                <div class="card-body">
                    <p><strong>Message ID:</strong> {{ $message->id }}</p>
                    <p><strong>Created:</strong> {{ $message->created_at->format('M d, Y') }}</p>
                    @if($message->read)
                        <p><strong>Read:</strong> {{ $message->read_at ? $message->read_at->format('M d, Y \a\t g:i A') : 'Recently' }}</p>
                    @endif
                    
                    @if($message->serviceRequest)
                        <hr>
                        <h6><strong>Service Request Details:</strong></h6>
                        <p><strong>Client:</strong> {{ $message->serviceRequest->client->user->name }}</p>
                        <p><strong>Quantity:</strong> {{ $message->serviceRequest->quantity_gsv }} GSV</p>
                        <p><strong>Tanks:</strong> {{ $message->serviceRequest->tank_numbers }}</p>
                    @endif
                </div>
            </div>

            @if($message->sender_id !== Auth::id())
            <div class="card shadow mt-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-reply me-2"></i>
                        Reply
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('inspector.messages.create') }}?reply_to={{ $message->sender_id }}" class="btn btn-primary w-100">
                        <i class="fas fa-reply me-2"></i>
                        Reply to Message
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 