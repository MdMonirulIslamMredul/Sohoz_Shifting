@extends('admin.layouts.app')
@section('title', 'Operations & Revenue Reports')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h4 class="fw-bold mb-0">Operations, Financial & Conversion Reports</h4>
        <p class="text-muted small mb-0">দৈনিক, সাপ্তাহিক ও মাসিক বুকিং, রেভিনিউ এবং শিফটিং পারফরম্যান্স রিপোর্ট</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.campaigns') }}" class="btn btn-outline-primary fw-semibold">
            <i class="bi bi-graph-up me-1"></i> Campaign Performance
        </a>
    </div>
</div>

{{-- Period Filter Toolbar --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.reports.index', ['period' => 'today']) }}" class="btn btn-sm {{ $period === 'today' ? 'btn-primary' : 'btn-outline-secondary' }}">Today</a>
                <a href="{{ route('admin.reports.index', ['period' => 'week']) }}" class="btn btn-sm {{ $period === 'week' ? 'btn-primary' : 'btn-outline-secondary' }}">This Week</a>
                <a href="{{ route('admin.reports.index', ['period' => 'month']) }}" class="btn btn-sm {{ $period === 'month' ? 'btn-primary' : 'btn-outline-secondary' }}">This Month</a>
                <a href="{{ route('admin.reports.index', ['period' => 'year']) }}" class="btn btn-sm {{ $period === 'year' ? 'btn-primary' : 'btn-outline-secondary' }}">This Year</a>
            </div>

            <form method="GET" action="{{ route('admin.reports.index') }}" class="d-flex align-items-center gap-2">
                <input type="hidden" name="period" value="custom">
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date', $startDate?->format('Y-m-d')) }}" required>
                <span>to</span>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date', $endDate?->format('Y-m-d')) }}" required>
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
    </div>
</div>

{{-- KPI Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <small class="text-muted d-block">Total Booking Leads</small>
            <h3 class="fw-bold text-dark mt-1 mb-0">{{ number_format($totalBookings) }}</h3>
            <span class="small text-muted mt-2 d-block">Period: {{ ucfirst($period) }}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <small class="text-muted d-block">Confirmed Moves</small>
            <h3 class="fw-bold text-primary mt-1 mb-0">{{ number_format($confirmedBookings) }}</h3>
            <span class="small text-success mt-2 d-block">Conversion: {{ $conversionRate }}%</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <small class="text-muted d-block">Gross Revenue / Value</small>
            <h3 class="fw-bold text-success mt-1 mb-0">৳{{ number_format($totalRevenue) }}</h3>
            <span class="small text-muted mt-2 d-block">Completed Moves: {{ $completedBookings }}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <small class="text-muted d-block">Advance Collected</small>
            <h3 class="fw-bold text-warning-emphasis mt-1 mb-0">৳{{ number_format($advanceCollected) }}</h3>
            <span class="small text-danger mt-2 d-block">Outstanding Due: ৳{{ number_format($dueOutstanding) }}</span>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Status Pipeline Breakdown --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold mb-0"><i class="bi bi-funnel text-primary me-2"></i>Status Pipeline Distribution</h5>
                <p class="text-muted small mb-0">১০-টি স্টেজ অনুযায়ী বর্তমান বুকিং সংখ্যা ও আনুমানিক মান</p>
            </div>
            <div class="card-body p-4 pt-2">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Status</th>
                                <th class="text-center">Count</th>
                                <th class="text-end">Est. Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statusBreakdown as $row)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ ucfirst(str_replace('_', ' ', $row->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold">{{ $row->count }}</td>
                                    <td class="text-end">৳{{ number_format($row->total_val) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">No data in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Shifting Routes --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt text-danger me-2"></i>Top Shifting Routes</h5>
                <p class="text-muted small mb-0">সবচেয়ে বেশি শিফটিং হওয়া জনপ্রিয় রুটসমূহ</p>
            </div>
            <div class="card-body p-4 pt-2">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Pickup Area</th>
                                <th>Delivery Area</th>
                                <th class="text-center">Total Trips</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topRoutes as $route)
                                <tr>
                                    <td><i class="bi bi-pin-map-fill text-danger me-1"></i> {{ $route->pickup_area }}</td>
                                    <td><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $route->delivery_area }}</td>
                                    <td class="text-center fw-bold">{{ $route->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">No route records available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

