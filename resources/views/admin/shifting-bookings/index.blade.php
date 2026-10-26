@extends('admin.layouts.app')
@section('title', 'Shifting Bookings')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Shifting Bookings (শিফটিং বুকিং ও ডিসপ্যাচ)</h4>
            <p class="text-muted small mb-0">Manage customer booking requests, staff assignment, and status updates</p>
        </div>
        <a href="{{ route('bookings.create') }}" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-plus-lg me-1"></i> New Booking (Frontend)
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Status Filter Badges --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.shifting-bookings.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
            All ({{ $statusCounts['all'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }}">
            Pending ({{ $statusCounts['pending'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-primary' : 'btn-outline-primary' }}">
            Confirmed ({{ $statusCounts['confirmed'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'team_assigned']) }}" class="btn btn-sm {{ request('status') === 'team_assigned' ? 'btn-info text-dark' : 'btn-outline-info text-dark' }}">
            Team Assigned ({{ $statusCounts['team_assigned'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'in_progress']) }}" class="btn btn-sm {{ request('status') === 'in_progress' ? 'btn-secondary' : 'btn-outline-secondary' }}">
            In Progress ({{ $statusCounts['in_progress'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') === 'completed' ? 'btn-success' : 'btn-outline-success' }}">
            Completed ({{ $statusCounts['completed'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
            Cancelled ({{ $statusCounts['cancelled'] }})
        </a>
    </div>

    {{-- Filter Form --}}
    <div class="card p-3 shadow-sm border-0 mb-3">
        <form method="GET" action="{{ route('admin.shifting-bookings.index') }}" class="row g-2 align-items-center">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search Code, Customer Name, Phone, Area..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Filter</button>
                <a href="{{ route('admin.shifting-bookings.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="card p-3 shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Booking Code</th>
                        <th>Customer</th>
                        <th>Property &amp; Route</th>
                        <th>Shifting Date</th>
                        <th>Est. Cost</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('admin.shifting-bookings.show', $booking) }}" class="fw-bold text-decoration-none">
                                    {{ $booking->booking_code }}
                                </a>
                                <div class="text-muted small">{{ $booking->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $booking->customer_name }}</div>
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $booking->customer_phone }}</div>
                                @if($booking->customer_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->customer_whatsapp) }}" target="_blank" class="small text-success text-decoration-none">
                                        <i class="bi bi-whatsapp"></i> WhatsApp
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="badge bg-light text-dark border mb-1">
                                    {{ $booking->display_property_type }}
                                </div>
                                <div class="small">
                                    <span class="text-primary">{{ $booking->pickup_area }}</span> (Fl: {{ $booking->pickup_floor }})
                                    <i class="bi bi-arrow-right text-muted mx-1"></i>
                                    <span class="text-success">{{ $booking->delivery_area }}</span> (Fl: {{ $booking->delivery_floor }})
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $booking->shifting_date->format('d M, Y') }}</div>
                                <div class="text-muted small">{{ $booking->preferred_time_slot }}</div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">৳{{ number_format($booking->estimated_cost, 2) }}</span>
                                @if($booking->advance_amount > 0)
                                    <div class="small text-success">Adv: ৳{{ number_format($booking->advance_amount, 2) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $booking->status_badge_class }}">
                                    {{ $booking->status_label_bn }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $booking->payment_status === 'paid_in_full' ? 'bg-success' : ($booking->payment_status === 'partial_advance' ? 'bg-info text-dark' : 'bg-secondary') }}">
                                    {{ $booking->payment_status_label_bn }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.shifting-bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" title="View & Dispatch">
                                    <i class="bi bi-eye"></i> Manage
                                </a>
                                <a href="{{ route('admin.shifting-bookings.print-challan', $booking) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Print Challan">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No shifting bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection
