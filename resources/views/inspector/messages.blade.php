@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Messages</h1>
                <div>
                    <a href="{{ route('inspector.messages.create') }}" class="btn btn-primary">New Message</a>
                    <a href="{{ route('inspector.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Messages</h6>
                </div>
                <div class="card-body">
                    @if($messages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>From/To</th>
                                        <th>Subject</th>
                                        <th>Service Request</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                    <tr class="{{ !$message->read && $message->recipient_id === auth()->id() ? 'table-warning' : '' }}">
                                        <td>
                                            @if($message->sender_id === auth()->id())
                                                <span class="badge badge-info">Sent</span>
                                            @else
                                                <span class="badge badge-primary">Received</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($message->sender_id === auth()->id())
                                                To: {{ $message->recipient->name }}
                                            @else
                                                From: {{ $message->sender->name }}
                                            @endif
                                        </td>
                                        <td>{{ $message->subject }}</td>
                                        <td>
                                            @if($message->serviceRequest)
                                                #{{ $message->serviceRequest->id }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($message->sender_id === auth()->id())
                                                <span class="badge badge-secondary">Sent</span>
                                            @else
                                                @if($message->read)
                                                    <span class="badge badge-success">Read</span>
                                                @else
                                                    <span class="badge badge-warning">Unread</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('inspector.messages.show', $message->id) }}" class="btn btn-sm btn-info">View</a>
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
                        <div class="text-center py-4">
                            <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No messages found</h5>
                            <p class="text-muted">Start a conversation by sending a new message.</p>
                            <a href="{{ route('inspector.messages.create') }}" class="btn btn-primary">Send Message</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 