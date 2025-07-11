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
                <a href="{{ route('operations.messages') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Messages
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope-open me-2"></i>
                        {{ $message->subject }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong><i class="fas fa-user me-2"></i>From:</strong>
                                <div class="mt-1">
                                    {{ $message->sender->name ?? 'N/A' }}
                                    <small class="text-muted d-block">{{ $message->sender->email ?? '' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong><i class="fas fa-user-check me-2"></i>To:</strong>
                                <div class="mt-1">
                                    {{ $message->recipient->name ?? 'N/A' }}
                                    <small class="text-muted d-block">{{ $message->recipient->email ?? '' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($message->serviceRequest)
                    <div class="mb-3">
                        <strong><i class="fas fa-clipboard-list me-2"></i>Related Service Request:</strong>
                        <div class="mt-1">
                            <a href="{{ route('operations.service-requests.show', $message->serviceRequest) }}" class="text-decoration-none">
                                {{ $message->serviceRequest->service_id ?? 'N/A' }}
                            </a>
                            <small class="text-muted d-block">{{ $message->serviceRequest->service_type ?? '' }}</small>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong><i class="fas fa-calendar me-2"></i>Sent:</strong>
                        <div class="mt-1">
                            {{ $message->created_at->format('F j, Y \a\t g:i A') }}
                        </div>
                    </div>

                    @if($message->read)
                    <div class="mb-3">
                        <strong><i class="fas fa-eye me-2"></i>Read:</strong>
                        <div class="mt-1">
                            {{ $message->read_at ? $message->read_at->format('F j, Y \a\t g:i A') : 'Yes' }}
                        </div>
                    </div>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <strong><i class="fas fa-file-text me-2"></i>Message Content:</strong>
                        <div class="mt-3 p-3 bg-light rounded">
                            {!! nl2br(e($message->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="{{ route('operations.messages') }}" class="btn btn-secondary">
                            <i class="fas fa-list me-2"></i>
                            Back to Messages
                        </a>
                        @if($message->serviceRequest)
                        <a href="{{ route('operations.service-requests.show', $message->serviceRequest) }}" class="btn btn-info">
                            <i class="fas fa-clipboard-list me-2"></i>
                            View Service Request
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 