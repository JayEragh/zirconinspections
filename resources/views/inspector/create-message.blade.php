@extends('layouts.app')

@section('title', 'Create Message')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    {{ $replyToMessage ? 'Reply to Message' : 'Create New Message' }}
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
                    <h5 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        {{ $replyToMessage ? 'Reply to Message' : 'New Message' }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($replyToMessage)
                        <div class="alert alert-info">
                            <h6><i class="fas fa-reply me-2"></i>Replying to:</h6>
                            <p class="mb-1"><strong>From:</strong> {{ $replyToMessage->sender->name }}</p>
                            <p class="mb-1"><strong>Subject:</strong> {{ $replyToMessage->subject }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $replyToMessage->created_at->format('M d, Y H:i') }}</p>
                            <div class="mt-2 p-2 bg-light rounded">
                                <small class="text-muted">Original message:</small>
                                <p class="mb-0">{{ $replyToMessage->content }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('inspector.messages.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="recipient_id" class="form-label">To <span class="text-danger">*</span></label>
                                    <select class="form-control @error('recipient_id') is-invalid @enderror" 
                                            id="recipient_id" name="recipient_id" required>
                                        <option value="">Select Recipient</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->user_id }}" 
                                                    {{ $replyToMessage && $replyToMessage->sender_id == $client->user_id ? 'selected' : '' }}>
                                                {{ $client->name }} (Client)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('recipient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_request_id" class="form-label">Related Service Request (Optional)</label>
                                    <select class="form-control @error('service_request_id') is-invalid @enderror" 
                                            id="service_request_id" name="service_request_id">
                                        <option value="">Select Service Request</option>
                                        @foreach($serviceRequests as $serviceRequest)
                                            <option value="{{ $serviceRequest->id }}"
                                                    {{ $replyToMessage && $replyToMessage->service_request_id == $serviceRequest->id ? 'selected' : '' }}>
                                                #{{ $serviceRequest->id }} - {{ $serviceRequest->client->name }} - {{ ucfirst($serviceRequest->service_type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_request_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" 
                                   value="{{ $replyToMessage ? 'Re: ' . $replyToMessage->subject : old('subject') }}" 
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="content" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inspector.messages') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                {{ $replyToMessage ? 'Send Reply' : 'Send Message' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Message Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Be clear and professional
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Include relevant service request details
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Use appropriate subject lines
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Respond promptly to client inquiries
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 