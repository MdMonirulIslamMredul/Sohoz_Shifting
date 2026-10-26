@extends('admin.layouts.app')
@section('title', 'Contact Messages')

@push('styles')
<style>
    .contact-row {
        transition: background-color 0.2s ease, transform 0.1s ease;
    }
    .contact-row.is-unread {
        background-color: #f8fafc;
        border-left: 4px solid #0d6efd;
    }
    .contact-row.is-read {
        border-left: 4px solid transparent;
    }
    .contact-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .stat-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .badge-soft-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .badge-soft-success {
        background-color: #dcfce7;
        color: #166534;
    }
    .badge-soft-secondary {
        background-color: #f1f5f9;
        color: #475569;
    }
    .badge-soft-primary {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .btn-toggle-status {
        transition: all 0.2s ease;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="mb-1 text-dark fw-bold">
                <i class="bi bi-envelope-paper me-2 text-primary"></i>Contact Messages
            </h3>
            <p class="text-secondary mb-0">View inquiries, manage read status, and track communication with potential clients.</p>
        </div>
        <div class="d-flex gap-2">
            @if ($unreadCount > 0)
                <form action="{{ route('admin.contacts.mark-all-read') }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Mark all {{ $unreadCount }} unread messages as read?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm px-3 shadow-sm">
                        <i class="bi bi-check2-all me-1"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Metric Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.contacts.index') }}" class="text-decoration-none">
            <div class="stat-card p-3 d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-chat-left-text fs-4"></i>
                </div>
                <div>
                    <span class="text-secondary small fw-medium">Total Messages</span>
                    <h4 class="mb-0 fw-bold text-dark">{{ $totalCount }}</h4>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.contacts.index', array_merge(request()->query(), ['read_status' => 'unread'])) }}" class="text-decoration-none">
            <div class="stat-card p-3 d-flex align-items-center gap-3 {{ request('read_status') === 'unread' ? 'border-danger' : '' }}">
                <div class="rounded-circle p-3 bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-envelope-exclamation fs-4"></i>
                </div>
                <div>
                    <span class="text-secondary small fw-medium">Unread / Not Opened</span>
                    <h4 class="mb-0 fw-bold text-danger">{{ $unreadCount }}</h4>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.contacts.index', array_merge(request()->query(), ['read_status' => 'read'])) }}" class="text-decoration-none">
            <div class="stat-card p-3 d-flex align-items-center gap-3 {{ request('read_status') === 'read' ? 'border-primary' : '' }}">
                <div class="rounded-circle p-3 bg-info bg-opacity-10 text-info">
                    <i class="bi bi-envelope-check fs-4"></i>
                </div>
                <div>
                    <span class="text-secondary small fw-medium">Read / Opened</span>
                    <h4 class="mb-0 fw-bold text-dark">{{ $readCount }}</h4>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('admin.contacts.index', array_merge(request()->query(), ['connection_status' => 'connected'])) }}" class="text-decoration-none">
            <div class="stat-card p-3 d-flex align-items-center gap-3 {{ request('connection_status') === 'connected' ? 'border-success' : '' }}">
                <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success">
                    <i class="bi bi-person-check fs-4"></i>
                </div>
                <div>
                    <span class="text-secondary small fw-medium">Connected Leads</span>
                    <h4 class="mb-0 fw-bold text-success">{{ $connectedCount }}</h4>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Filter & Search Toolbar --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        placeholder="Search by name, email, subject, message..."
                        value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <select name="read_status" class="form-select">
                    <option value="" {{ request('read_status') == '' ? 'selected' : '' }}>All Status (Read & Unread)</option>
                    <option value="unread" {{ request('read_status') === 'unread' ? 'selected' : '' }}>Unread only ({{ $unreadCount }})</option>
                    <option value="read" {{ request('read_status') === 'read' ? 'selected' : '' }}>Read only ({{ $readCount }})</option>
                </select>
            </div>

            <div class="col-sm-6 col-md-3">
                <select name="connection_status" class="form-select">
                    <option value="" {{ request('connection_status') == '' ? 'selected' : '' }}>All Connections</option>
                    <option value="connected" {{ request('connection_status') === 'connected' ? 'selected' : '' }}>Connected ({{ $connectedCount }})</option>
                    <option value="not_connected" {{ request('connection_status') === 'not_connected' ? 'selected' : '' }}>Not Connected ({{ $notConnectedCount }})</option>
                </select>
            </div>

            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary px-3">Filter</button>
                @if (request()->hasAny(['search', 'read_status', 'connection_status', 'sort']))
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary" title="Reset Filters">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Messages List Table Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 130px;">Read Status</th>
                        <th>Sender Details</th>
                        <th>Subject & Message</th>
                        <th style="width: 160px;">Received</th>
                        <th style="width: 170px;" class="text-center">Connection</th>
                        <th style="width: 150px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="contactsTableBody">
                    @forelse ($contacts as $contact)
                        <tr class="contact-row {{ $contact->is_read ? 'is-read' : 'is-unread' }}" id="contact-row-{{ $contact->id }}">
                            {{-- Read / Unread Status --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('admin.contacts.toggle-read', $contact) }}" method="POST" class="d-inline toggle-read-form" data-id="{{ $contact->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="{{ $contact->is_read ? 'Click to mark unread' : 'Click to mark read' }}">
                                            <span class="badge {{ $contact->is_read ? 'badge-soft-secondary border' : 'badge-soft-danger border border-danger-subtle' }} px-2 py-1 read-status-badge">
                                                <i class="bi {{ $contact->is_read ? 'bi-envelope-open' : 'bi-envelope-fill text-danger' }} me-1"></i>
                                                <span class="read-status-text">{{ $contact->is_read ? 'Read' : 'Unread' }}</span>
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            {{-- Sender Info --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="contact-avatar {{ $contact->is_read ? 'bg-secondary bg-opacity-10 text-secondary' : 'bg-primary bg-opacity-10 text-primary' }}">
                                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.contacts.show', $contact) }}" class="text-decoration-none fw-semibold {{ $contact->is_read ? 'text-dark' : 'text-primary' }}">
                                            {{ $contact->name }}
                                        </a>
                                        <div class="small">
                                            <a href="mailto:{{ $contact->email }}" class="text-secondary text-decoration-none" title="Send email">
                                                <i class="bi bi-envelope me-1"></i>{{ $contact->email }}
                                            </a>
                                        </div>
                                        @if ($contact->phone)
                                            <div class="small">
                                                <a href="tel:{{ $contact->phone }}" class="text-muted text-decoration-none" title="Call">
                                                    <i class="bi bi-telephone me-1"></i>{{ $contact->phone }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Subject & Message Snippet --}}
                            <td>
                                @if ($contact->subject)
                                    <div class="fw-semibold text-dark small mb-1">
                                        {{ Str::limit($contact->subject, 50) }}
                                    </div>
                                @endif
                                <div class="text-secondary small" style="max-width: 380px;">
                                    {{ Str::limit($contact->message, 110) }}
                                </div>
                                @if ($contact->admin_notes)
                                    <div class="mt-1 small text-info">
                                        <i class="bi bi-sticky-fill me-1"></i> Note: {{ Str::limit($contact->admin_notes, 40) }}
                                    </div>
                                @endif
                            </td>

                            {{-- Received Date --}}
                            <td>
                                <div class="text-dark small fw-medium">
                                    {{ $contact->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-secondary small" title="{{ $contact->created_at->toDayDateTimeString() }}">
                                    {{ $contact->created_at->diffForHumans() }}
                                </div>
                            </td>

                            {{-- Connection Status Toggle --}}
                            <td class="text-center">
                                <form action="{{ route('admin.contacts.toggle-connected', $contact) }}" method="POST" class="d-inline toggle-connected-form" data-id="{{ $contact->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm rounded-pill px-3 py-1 btn-toggle-status {{ $contact->is_connected ? 'btn-success text-white' : 'btn-outline-secondary' }}"
                                        title="Click to toggle Connected / Not Connected">
                                        <i class="bi {{ $contact->is_connected ? 'bi-check-circle-fill' : 'bi-dash-circle' }} me-1"></i>
                                        <span class="connected-text">{{ $contact->is_connected ? 'Connected' : 'Not Connected' }}</span>
                                    </button>
                                </form>
                                @if ($contact->connected_at && $contact->is_connected)
                                    <div class="text-muted" style="font-size: 0.72rem;">
                                        {{ $contact->connected_at->diffForHumans() }}
                                    </div>
                                @endif
                            </td>

                            {{-- Action Buttons --}}
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.contacts.show', $contact) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Full Message">
                                        <i class="bi bi-eye"></i> View
                                    </a>

                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to permanently delete this message from {{ addslashes($contact->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    <h5>No contact messages found</h5>
                                    <p class="small text-secondary mb-3">
                                        @if (request()->hasAny(['search', 'read_status', 'connection_status']))
                                            No messages match your active filters. Try resetting them.
                                        @else
                                            When visitors submit the contact form, their inquiries will appear here.
                                        @endif
                                    </p>
                                    @if (request()->hasAny(['search', 'read_status', 'connection_status']))
                                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">
                                            Clear All Filters
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contacts->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center">
                <div class="small text-secondary">
                    Showing {{ $contacts->firstItem() ?? 0 }} to {{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() }} messages
                </div>
                <div>
                    {{ $contacts->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // AJAX toggle for read/unread status
    document.querySelectorAll('.toggle-read-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const contactId = this.dataset.id;
            const row = document.getElementById(`contact-row-${contactId}`);
            const badge = this.querySelector('.read-status-badge');
            const icon = this.querySelector('i');
            const text = this.querySelector('.read-status-text');

            fetch(this.action, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.is_read) {
                        badge.className = 'badge badge-soft-secondary border px-2 py-1 read-status-badge';
                        icon.className = 'bi bi-envelope-open me-1';
                        text.textContent = 'Read';
                        if (row) {
                            row.classList.remove('is-unread');
                            row.classList.add('is-read');
                        }
                    } else {
                        badge.className = 'badge badge-soft-danger border border-danger-subtle px-2 py-1 read-status-badge';
                        icon.className = 'bi bi-envelope-fill text-danger me-1';
                        text.textContent = 'Unread';
                        if (row) {
                            row.classList.remove('is-read');
                            row.classList.add('is-unread');
                        }
                    }

                    // Update sidebar/topbar badge if count changed
                    updateUnreadBadges(data.unread_count);
                }
            })
            .catch(err => {
                // Fallback to normal submit on error
                form.submit();
            });
        });
    });

    // AJAX toggle for connected/not connected status
    document.querySelectorAll('.toggle-connected-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button');
            const icon = this.querySelector('i');
            const text = this.querySelector('.connected-text');

            fetch(this.action, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.is_connected) {
                        btn.className = 'btn btn-sm rounded-pill px-3 py-1 btn-toggle-status btn-success text-white';
                        icon.className = 'bi bi-check-circle-fill me-1';
                        text.textContent = 'Connected';
                    } else {
                        btn.className = 'btn btn-sm rounded-pill px-3 py-1 btn-toggle-status btn-outline-secondary';
                        icon.className = 'bi bi-dash-circle me-1';
                        text.textContent = 'Not Connected';
                    }
                }
            })
            .catch(err => {
                // Fallback to normal submit on error
                form.submit();
            });
        });
    });

    function updateUnreadBadges(count) {
        document.querySelectorAll('.sidebar .badge.bg-danger, .topbar .badge.bg-danger').forEach(badge => {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = '';
            } else {
                badge.style.display = 'none';
            }
        });
    }
</script>
@endpush
