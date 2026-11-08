@extends('admin.layouts.app')
@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="bi bi-arrow-left me-1"></i> Back to Tickets
    </a>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Ticket #{{ $ticket->ticket_number }}: {{ $ticket->subject }}</h4>
            <span class="text-muted small">Submitted by: <strong>{{ $ticket->user?->name }}</strong> ({{ $ticket->user?->phone }})</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="d-flex gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_review" {{ $ticket->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </form>
            @if($ticket->booking)
                <a href="{{ route('admin.shifting-bookings.show', $ticket->booking) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i> View Booking
                </a>
            @endif
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4" style="max-width: 900px;">
    <div class="card-body p-4">
        {{-- Thread History --}}
        <div class="p-3 bg-light rounded-3 mb-4" style="min-height: 250px;">
            @foreach($ticket->messages as $msg)
                <div class="mb-3 p-3 rounded-3 {{ ($msg['sender'] ?? '') === 'admin' ? 'bg-primary-subtle border-start border-primary border-4 text-dark ms-5' : 'bg-white shadow-sm me-5' }}">
                    <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom border-secondary border-opacity-10">
                        <strong class="{{ ($msg['sender'] ?? '') === 'admin' ? 'text-primary' : 'text-dark' }}">
                            {{ $msg['sender_name'] ?? 'User' }}
                        </strong>
                        <span class="small text-muted">{{ isset($msg['timestamp']) ? \Carbon\Carbon::parse($msg['timestamp'])->format('d M, Y h:i A') : '' }}</span>
                    </div>
                    <div style="white-space: pre-line;">{{ $msg['message'] ?? '' }}</div>
                </div>
            @endforeach
        </div>

        {{-- Reply Form --}}
        <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Reply to Customer</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Type your response here..." required></textarea>
                <small class="text-muted">Customer will see this response directly in their dashboard.</small>
            </div>
            <button type="submit" class="btn btn-primary fw-bold px-4">
                <i class="bi bi-send me-1"></i> Send Reply
            </button>
        </form>
    </div>
</div>
@endsection

