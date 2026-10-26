@extends('admin.layouts.app')
@section('title', 'Message from ' . $contact->name)

@push('styles')
<style>
    .message-card {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #e2e8f0;
    }
    .sender-avatar-large {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
    }
    .message-body-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 24px;
        font-size: 1.05rem;
        line-height: 1.7;
        white-space: pre-wrap;
        word-break: break-word;
        color: #1e293b;
    }
    .badge-soft-success {
        background-color: #dcfce7;
        color: #166534;
    }
    .badge-soft-secondary {
        background-color: #f1f5f9;
        color: #475569;
    }
    .badge-soft-info {
        background-color: #e0f2fe;
        color: #0369a1;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Messages
            </a>
            <div>
                <h4 class="mb-0 fw-bold text-dark">Message Details</h4>
                <div class="small text-secondary">
                    Received {{ $contact->created_at->format('M d, Y \a\t h:i A') }} ({{ $contact->created_at->diffForHumans() }})
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            {{-- Toggle Read/Unread Form --}}
            <form action="{{ route('admin.contacts.toggle-read', $contact) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $contact->is_read ? 'btn-outline-secondary' : 'btn-outline-primary' }}">
                    <i class="bi {{ $contact->is_read ? 'bi-envelope' : 'bi-envelope-open' }} me-1"></i>
                    {{ $contact->is_read ? 'Mark as Unread' : 'Mark as Read' }}
                </button>
            </form>

            {{-- Toggle Connected Form --}}
            <form action="{{ route('admin.contacts.toggle-connected', $contact) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $contact->is_connected ? 'btn-success text-white' : 'btn-outline-success' }}">
                    <i class="bi {{ $contact->is_connected ? 'bi-check-circle-fill' : 'bi-telephone-forward' }} me-1"></i>
                    {{ $contact->is_connected ? 'Connected' : 'Mark as Connected' }}
                </button>
            </form>

            {{-- Reply via Email --}}
            <a href="mailto:{{ $contact->email }}?subject={{ urlencode('Re: ' . ($contact->subject ?: 'Your inquiry with ' . ($settings->site_name ?? 'us')) ) }}"
                class="btn btn-sm btn-primary">
                <i class="bi bi-reply-fill me-1"></i> Reply via Email
            </a>

            {{-- Delete --}}
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Permanently delete this message?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Main Content Column --}}
    <div class="col-lg-8">
        {{-- Message Container --}}
        <div class="card border-0 shadow-sm message-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                    <div>
                        <span class="text-secondary small text-uppercase fw-semibold">Subject</span>
                        <h4 class="mb-0 text-dark fw-bold">
                            {{ $contact->subject ?: 'Inquiry from ' . $contact->name }}
                        </h4>
                    </div>
                    <button class="btn btn-sm btn-light border" onclick="copyMessage()" id="copyBtn" title="Copy message text">
                        <i class="bi bi-clipboard me-1"></i> Copy
                    </button>
                </div>

                <div class="mb-2 text-secondary small fw-medium">Message Body:</div>
                <div class="message-body-box" id="messageBody">{{ $contact->message }}</div>
            </div>
        </div>

        {{-- Admin Notes Card --}}
        <div class="card border-0 shadow-sm message-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-stickies text-warning me-2"></i>Admin Internal Notes
                    </h5>
                    <span class="badge bg-light text-secondary border">Private Notes</span>
                </div>
                <p class="text-secondary small mb-3">
                    Record communication history, follow-up status, phone call outcomes, or notes for your team.
                </p>
                <form action="{{ route('admin.contacts.update-notes', $contact) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <textarea name="admin_notes" class="form-control" rows="4"
                            placeholder="Add notes about client conversation, requirements, or next steps...">{{ old('admin_notes', $contact->admin_notes) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-dark btn-sm px-3">
                        <i class="bi bi-save me-1"></i> Save Notes
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right Sidebar Column --}}
    <div class="col-lg-4">
        {{-- Sender Profile Card --}}
        <div class="card border-0 shadow-sm message-card mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Sender Details</h5>
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="sender-avatar-large bg-primary bg-opacity-10 text-primary">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">{{ $contact->name }}</h5>
                        <span class="text-secondary small">Visitor / Lead</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-secondary small text-uppercase fw-semibold mb-1 d-block">Email Address</label>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="mailto:{{ $contact->email }}" class="text-decoration-none fw-medium text-primary">
                            {{ $contact->email }}
                        </a>
                        <button class="btn btn-sm btn-link p-0 text-muted" onclick="navigator.clipboard.writeText('{{ $contact->email }}'); alert('Email copied!');" title="Copy email">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                </div>

                @if ($contact->phone)
                    <div class="mb-3">
                        <label class="text-secondary small text-uppercase fw-semibold mb-1 d-block">Phone Number</label>
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="tel:{{ $contact->phone }}" class="text-decoration-none fw-medium text-dark">
                                {{ $contact->phone }}
                            </a>
                            <a href="tel:{{ $contact->phone }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="Call">
                                <i class="bi bi-telephone"></i> Call
                            </a>
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="text-secondary small text-uppercase fw-semibold mb-1 d-block">Submitted At</label>
                    <div class="text-dark">
                        {{ $contact->created_at->format('M d, Y - h:i A') }}
                    </div>
                    <div class="text-secondary small">
                        ({{ $contact->created_at->diffForHumans() }})
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Summary Card --}}
        <div class="card border-0 shadow-sm message-card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Status Overview</h5>

                {{-- Read / Open Status --}}
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small text-uppercase fw-semibold">Open Status</span>
                        @if ($contact->is_read)
                            <span class="badge badge-soft-info border border-info-subtle px-2 py-1">
                                <i class="bi bi-envelope-open me-1"></i> Read / Opened
                            </span>
                        @else
                            <span class="badge badge-soft-danger border border-danger-subtle px-2 py-1">
                                <i class="bi bi-envelope-fill me-1"></i> Unread / Not Opened
                            </span>
                        @endif
                    </div>
                    @if ($contact->read_at)
                        <div class="small text-secondary">
                            First opened: {{ $contact->read_at->format('M d, Y h:i A') }}
                        </div>
                    @endif
                </div>

                {{-- Connection Status --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small text-uppercase fw-semibold">Connection Status</span>
                        @if ($contact->is_connected)
                            <span class="badge badge-soft-success border border-success-subtle px-2 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Connected
                            </span>
                        @else
                            <span class="badge badge-soft-secondary border px-2 py-1">
                                <i class="bi bi-dash-circle me-1"></i> Not Connected
                            </span>
                        @endif
                    </div>
                    @if ($contact->connected_at && $contact->is_connected)
                        <div class="small text-secondary">
                            Connected on: {{ $contact->connected_at->format('M d, Y h:i A') }}
                        </div>
                    @else
                        <div class="small text-muted">
                            Pending outreach or reply to the customer.
                        </div>
                    @endif
                </div>

                <div class="pt-2">
                    <form action="{{ route('admin.contacts.toggle-connected', $contact) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn w-100 {{ $contact->is_connected ? 'btn-outline-danger' : 'btn-success text-white' }} btn-sm py-2">
                            <i class="bi {{ $contact->is_connected ? 'bi-x-circle' : 'bi-check2-circle' }} me-1"></i>
                            {{ $contact->is_connected ? 'Change to Not Connected' : 'Change to Connected' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyMessage() {
        const text = document.getElementById('messageBody').innerText;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById('copyBtn');
            btn.innerHTML = '<i class="bi bi-check me-1"></i> Copied!';
            btn.classList.add('btn-success', 'text-white');
            btn.classList.remove('btn-light');
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-clipboard me-1"></i> Copy';
                btn.classList.remove('btn-success', 'text-white');
                btn.classList.add('btn-light');
            }, 2000);
        });
    }
</script>
@endpush
