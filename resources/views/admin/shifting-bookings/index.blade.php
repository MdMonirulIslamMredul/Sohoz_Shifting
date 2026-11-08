@extends('admin.layouts.app')
@section('title', 'Shifting Bookings & Operations')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Shifting Bookings (শিফটিং বুকিং ও অপারেশন)</h4>
            <p class="text-muted small mb-0">Manage customer booking requests, quotations, dispatch, and lifecycle updates</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-bar-chart me-1"></i> Reports
            </a>
            <a href="{{ route('bookings.create') }}" target="_blank" class="btn btn-primary fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> New Booking
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 10 Status Filter Badges (Image 1 specifications) --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.shifting-bookings.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
            All ({{ $statusCounts['all'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'new_request']) }}" class="btn btn-sm {{ request('status') === 'new_request' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }}">
            New Request ({{ $statusCounts['new_request'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'contacted']) }}" class="btn btn-sm {{ request('status') === 'contacted' ? 'btn-info text-dark' : 'btn-outline-info text-dark' }}">
            Contacted ({{ $statusCounts['contacted'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'assessment_required']) }}" class="btn btn-sm {{ request('status') === 'assessment_required' ? 'btn-secondary' : 'btn-outline-secondary' }}">
            Assessment Req ({{ $statusCounts['assessment_required'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'site_visit_scheduled']) }}" class="btn btn-sm {{ request('status') === 'site_visit_scheduled' ? 'btn-dark' : 'btn-outline-dark' }}">
            Site Visit ({{ $statusCounts['site_visit_scheduled'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'quotation_sent']) }}" class="btn btn-sm {{ request('status') === 'quotation_sent' ? 'btn-primary' : 'btn-outline-primary' }}">
            Quotation Sent ({{ $statusCounts['quotation_sent'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'customer_approved']) }}" class="btn btn-sm {{ request('status') === 'customer_approved' ? 'btn-info text-dark' : 'btn-outline-info text-dark' }}">
            Customer Approved ({{ $statusCounts['customer_approved'] }})
        </a>
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-primary' : 'btn-outline-primary' }}">
            Confirmed ({{ $statusCounts['confirmed'] }})
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
                        <th>Est / Final Cost</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Source</th>
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
                                <div class="small fw-semibold text-dark">{{ $booking->display_property_type }}</div>
                                <div class="small text-muted text-truncate" style="max-width: 220px;">
                                    <span class="text-danger">●</span> {{ $booking->pickup_area }} ➔ <span class="text-success">●</span> {{ $booking->delivery_area }}
                                </div>
                            </td>
                            <td>
                                <strong>{{ $booking->shifting_date->format('d M, Y') }}</strong>
                                <div class="small text-muted">{{ $booking->preferred_time_slot }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">৳{{ number_format($booking->final_cost ?: $booking->estimated_cost) }}</div>
                                @if($booking->due_amount > 0)
                                    <div class="small text-danger">Due: ৳{{ number_format($booking->due_amount) }}</div>
                                @else
                                    <div class="small text-success">Cleared</div>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $booking->status_badge_class }}">
                                    {{ $booking->status_label_bn }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $booking->payment_status_label_bn }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border small">
                                    {{ $booking->utm_source ?: 'direct' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.shifting-bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary me-1" title="Manage & Dispatch">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('admin.shifting-bookings.print-challan', $booking) }}" target="_blank" class="btn btn-sm btn-outline-secondary me-1" title="Challan">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No shifting bookings found.</td>
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
