@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Messages
                </h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#composeModal">
                    <i class="fas fa-plus me-2"></i>
                    New Message
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Your Messages
                    </h5>
                </div>
                <div class="card-body">
                    @if($messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>From</th>
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
                                                <a href="#" class="btn btn-sm btn-outline-primary" title="View Message">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-success" title="Reply">
                                                    <i class="fas fa-reply"></i>
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
                            <h5 class="text-muted">No messages available</h5>
                            <p class="text-muted">Messages from inspectors and operations will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Compose Message Modal -->
<div class="modal fade" id="composeModal" tabindex="-1" aria-labelledby="composeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="composeModalLabel">
                    <i class="fas fa-edit me-2"></i>
                    Compose Message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">To</label>
                        <select class="form-select" id="recipient" name="recipient" required>
                            <option value="">Select Recipient</option>
                            <option value="operations">Operations Team</option>
                            <option value="inspector">Assigned Inspector</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message_content" class="form-label">Message</label>
                        <textarea class="form-control" id="message_content" name="message_content" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 