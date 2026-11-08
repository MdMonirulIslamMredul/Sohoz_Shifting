@extends('admin.layouts.app')
@section('title', 'Customer Support & Complaints')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Customer Support Tickets & Complaints</h4>
        <p class="text-muted small mb-0">গ্রাহকদের অভিযোগ, জিজ্ঞাসা ও ফিডব্যাক পর্যালোচনা ও উত্তর দিন</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-2">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <option value="complaint" {{ request('category') === 'complaint' ? 'selected' : '' }}>Complaint</option>
                    <option value="feedback" {{ request('category') === 'feedback' ? 'selected' : '' }}>Feedback</option>
                    <option value="query" {{ request('category') === 'query' ? 'selected' : '' }}>Query</option>
                    <option value="reschedule" {{ request('category') === 'reschedule' ? 'selected' : '' }}>Reschedule Request</option>
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Ticket #</th>
                        <th>Subject</th>
                        <th>Customer</th>
                        <th>Booking</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $t)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-dark font-monospace">#{{ $t->ticket_number }}</span>
                            </td>
                            <td>
                                <strong>{{ $t->subject }}</strong>
                            </td>
                            <td>
                                <div>{{ $t->user?->name }}</div>
                                <small class="text-muted">{{ $t->user?->phone }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.shifting-bookings.show', $t->booking) }}" target="_blank" class="badge bg-light text-primary border text-decoration-none">
                                    {{ $t->booking?->booking_code }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ ucfirst($t->category) }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $t->priority === 'urgent' ? 'bg-danger' : ($t->priority === 'high' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ ucfirst($t->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $t->status === 'resolved' ? 'bg-success' : ($t->status === 'open' ? 'bg-warning text-dark' : ($t->status === 'in_review' ? 'bg-primary' : 'bg-secondary')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $t->created_at->diffForHumans() }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.tickets.show', $t) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-chat-dots me-1"></i> View / Reply
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No support tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tickets->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
@endsection

