@extends('admin.layouts.app')
@section('title', 'Admin Dashboard - Shifting Operations & Services')

@push('styles')
<style>
    .kpi-card {
        border: none;
        border-radius: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: #ffffff;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }
    .kpi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .badge-pipeline {
        text-decoration: none;
        transition: all 0.2s ease;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-pipeline:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }
    .service-mini-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .service-mini-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
    }
    .service-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .quick-link-item {
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: #334155;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        transition: all 0.15s ease;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .quick-link-item:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateX(3px);
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper pb-4">

    {{-- Welcome Banner --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                            <i class="bi bi-shield-check me-1"></i> Sohoz Shifting Control Center
                        </span>
                        <span class="text-secondary small">|</span>
                        <span class="text-secondary small">{{ now()->format('l, d M Y') }}</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-white">স্বাগতম, {{ auth()->user()->name }}! 👋</h3>
                    <p class="text-slate-300 text-secondary mb-0" style="font-size: 0.95rem;">
                        শিফটিং বুকিং, সার্ভিসসমূহ এবং গ্রাহক অনুসন্ধান কার্যক্রমের সামগ্রিক চিত্র একনজরে দেখুন।
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('bookings.create') }}" target="_blank" class="btn btn-warning text-dark fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> New Booking
                    </a>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-outline-light">
                        <i class="bi bi-tools me-1"></i> Add Service
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Operations & Financial KPI Cards --}}
    <div class="row g-3 mb-4">
        {{-- Total Bookings --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi-card shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Bookings</span>
                        <h2 class="fw-bold text-dark mb-1 mt-1">{{ $bookingStats['total'] }}</h2>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                            <i class="bi bi-check-circle me-1"></i> {{ $bookingStats['confirmed'] }} Confirmed
                        </span>
                    </div>
                    <div class="kpi-icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-calendar-range"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.shifting-bookings.index') }}" class="text-decoration-none small text-primary fw-semibold">
                        View All Bookings <i class="bi bi-arrow-right"></i>
                    </a>
                    <span class="text-muted small">{{ $bookingStats['completed'] }} Finished</span>
                </div>
            </div>
        </div>

        {{-- Pending Requests (Urgent) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi-card shadow-sm p-3 h-100 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Pending Inquiries</span>
                        <h2 class="fw-bold text-warning-emphasis mb-1 mt-1">{{ $bookingStats['pending'] }}</h2>
                        @if($bookingStats['pending'] > 0)
                            <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Action Needed
                            </span>
                        @else
                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                <i class="bi bi-check-all me-1"></i> All Caught Up
                            </span>
                        @endif
                    </div>
                    <div class="kpi-icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.shifting-bookings.index', ['status' => 'pending']) }}" class="text-decoration-none small text-warning-emphasis fw-semibold">
                        Review Requests <i class="bi bi-arrow-right"></i>
                    </a>
                    <span class="text-muted small">New ({{ $bookingStats['pending'] }})</span>
                </div>
            </div>
        </div>

        {{-- Active Operations (In Transit) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi-card shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Active Operations</span>
                        <h2 class="fw-bold text-info-emphasis mb-1 mt-1">{{ $bookingStats['active_operations'] }}</h2>
                        <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                            <i class="bi bi-truck me-1"></i> {{ $bookingStats['in_progress'] }} In Progress / {{ $bookingStats['confirmed'] }} Confirmed
                        </span>
                    </div>
                    <div class="kpi-icon-box bg-info-subtle text-info">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.shifting-bookings.index', ['status' => 'in_progress']) }}" class="text-decoration-none small text-info fw-semibold">
                        Monitor Fleet <i class="bi bi-arrow-right"></i>
                    </a>
                    <span class="text-muted small">On The Road</span>
                </div>
            </div>
        </div>

        {{-- Estimated Value & Revenue --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card kpi-card shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Est. Booking Value</span>
                        <h2 class="fw-bold text-success mb-1 mt-1">৳{{ number_format($bookingStats['total_estimated_value']) }}</h2>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                            <i class="bi bi-wallet2 me-1"></i> ৳{{ number_format($bookingStats['total_advance_collected']) }} Advance
                        </span>
                    </div>
                    <div class="kpi-icon-box bg-success-subtle text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Due: ৳{{ number_format($bookingStats['total_due']) }}</span>
                    <a href="{{ route('admin.shifting-bookings.index') }}" class="text-decoration-none small text-success fw-semibold">
                        Financials <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Booking Pipeline Status Bar --}}
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-filter-circle me-1 text-primary"></i> Booking Lifecycle Pipeline
            </h6>
            <a href="{{ route('admin.shifting-bookings.index') }}" class="small text-decoration-none text-muted">
                Detailed view <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.shifting-bookings.index') }}" class="badge-pipeline bg-secondary-subtle text-dark">
                <span>All ({{ $bookingStats['total'] }})</span>
            </a>
            <a href="{{ route('admin.shifting-bookings.index', ['status' => 'new_request']) }}" class="badge-pipeline bg-warning-subtle text-warning-emphasis">
                <span>New Request</span>
                <span class="badge bg-warning text-dark rounded-pill">{{ $bookingStats['new_request'] }}</span>
            </a>
            <a href="{{ route('admin.shifting-bookings.index', ['status' => 'contacted']) }}" class="badge-pipeline bg-info-subtle text-info-emphasis">
                <span>Contacted</span>
                <span class="badge bg-info text-dark rounded-pill">{{ $bookingStats['contacted'] }}</span>
            </a>
            <a href="{{ route('admin.shifting-bookings.index', ['status' => 'site_visit_scheduled']) }}" class="badge-pipeline bg-dark-subtle text-dark">
                <span>Site Visit</span>
                <span class="badge bg-dark text-white rounded-pill">{{ $bookingStats['site_visit_scheduled'] }}</span>
            </a>
            <a href="{{ route('admin.shifting-bookings.index', ['status' => 'quotation_sent']) }}" class="badge-pipeline bg-primary-subtle text-primary">
                <span>Quote Sent</span>
                <span class="badge bg-primary text-white rounded-pill">{{ $bookingStats['quotation_sent'] }}</span>
            </a>
            <a href="{{ route('admin.shifting-bookings.index', ['status' => 'customer_approved']) }}" class="badge-pipeline bg-info-subtle text-dark">
                <span>Approved</span>
                <span class="badge bg-info text-dark rounded-pill">{{ $bookingStats['customer_approved'] }}</span>
            </a>
            <a href="{{ route('admin.shifting-bookings.index', ['status' => 'confirmed']) }}" class="badge-pipeline bg-primary-subtle text-primary">
                <span>Confirmed</span>
                <span class="badge bg-primary text-white rounded-pill">{{ $bookingStats['confirmed'] }}</span>
            </a>
            <a href="{{ route('admin.shifting-bookings.index', ['status' => 'in_progress']) }}" class="badge-pipeline bg-secondary-subtle text-secondary-emphasis">
                <span>In Progress</span>
                <span class="badge bg-secondary text-white rounded-pill">{{ $bookingStats['in_progress'] }}</span>
            </a>
            <a href="{{ route('admin.shifting-bookings.index', ['status' => 'completed']) }}" class="badge-pipeline bg-success-subtle text-success">
                <span>Completed</span>
                <span class="badge bg-success text-white rounded-pill">{{ $bookingStats['completed'] }}</span>
            </a>
            @if($bookingStats['cancelled'] > 0)
                <a href="{{ route('admin.shifting-bookings.index', ['status' => 'cancelled']) }}" class="badge-pipeline bg-danger-subtle text-danger">
                    <span>Cancelled</span>
                    <span class="badge bg-danger text-white rounded-pill">{{ $bookingStats['cancelled'] }}</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="row g-4">
        {{-- Left Column: Recent Bookings & Services Grid --}}
        <div class="col-lg-8">

            {{-- 1. Recent Bookings Table --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-calendar-check text-primary me-2"></i>Recent Shifting Bookings
                        </h5>
                        <p class="text-muted small mb-0">সাম্প্রতিক গ্রাহকদের বুকিং রিকোয়েস্ট ও শিফটিং কার্যক্রম</p>
                    </div>
                    <a href="{{ route('admin.shifting-bookings.index') }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">
                        View All ({{ $bookingStats['total'] }})
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4" style="min-width: 140px;">Code / Customer</th>
                                    <th style="min-width: 160px;">Route</th>
                                    <th style="min-width: 100px;">Date</th>
                                    <th style="min-width: 110px;">Est. Cost</th>
                                    <th style="min-width: 130px;">Status</th>
                                    <th class="text-end pe-4" style="min-width: 80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $booking)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <a href="{{ route('admin.shifting-bookings.show', $booking) }}" class="fw-bold text-dark text-decoration-none font-monospace">
                                                {{ $booking->booking_code }}
                                            </a>
                                            <div class="fw-semibold text-secondary small">
                                                {{ $booking->customer_name }}
                                            </div>
                                            <a href="tel:{{ $booking->customer_phone }}" class="text-muted small text-decoration-none text-nowrap">
                                                <i class="bi bi-telephone me-1 text-success"></i>{{ $booking->customer_phone }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold text-dark text-truncate" style="max-width: 180px;" title="{{ $booking->pickup_address }}">
                                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $booking->pickup_area ?: 'Pickup' }}
                                            </div>
                                            <div class="text-muted small text-truncate" style="max-width: 180px;" title="{{ $booking->delivery_address }}">
                                                <i class="bi bi-geo-fill text-success me-1"></i>{{ $booking->delivery_area ?: 'Delivery' }}
                                            </div>
                                            <div class="text-secondary" style="font-size: 0.75rem;">
                                                <span class="badge bg-light text-dark border">{{ $booking->display_property_type }}</span>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">
                                            <div class="fw-semibold text-dark small">
                                                {{ $booking->shifting_date ? $booking->shifting_date->format('d M, Y') : 'N/A' }}
                                            </div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                {{ $booking->preferred_time_slot ?: 'Flexible' }}
                                            </div>
                                        </td>
                                        <td class="text-nowrap">
                                            <div class="fw-bold text-dark">
                                                ৳{{ number_format($booking->estimated_cost) }}
                                            </div>
                                            <span class="badge {{ $booking->payment_status === 'paid_in_full' ? 'bg-success' : ($booking->payment_status === 'partial_advance' ? 'bg-info text-dark' : 'bg-secondary') }}" style="font-size: 0.7rem;">
                                                {{ $booking->payment_status_label_bn }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <span class="{{ $booking->status_badge_class }} px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                                {{ $booking->status_label_bn }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 text-nowrap">
                                            <a href="{{ route('admin.shifting-bookings.show', $booking) }}" class="btn btn-sm btn-outline-dark" title="View Booking Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                            <p class="mb-2">No bookings recorded yet.</p>
                                            <a href="{{ route('bookings.create') }}" target="_blank" class="btn btn-sm btn-primary">
                                                Create First Booking
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 2. Services Management & Pricing Matrix ("Design with services") --}}
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="fw-bold mb-0 text-dark">
                                    <i class="bi bi-tools text-primary me-2"></i>Shifting Services &amp; Dynamic Pricing
                                </h5>
                                <span class="badge bg-primary rounded-pill px-2 py-1">{{ $serviceStats['total'] }} Active Services</span>
                            </div>
                            <p class="text-muted small mb-0 mt-1">শিফটিং প্যাকেজ, অ্যাড-অন সেবা ও বুকিং ক্যালকুলেটরের সার্ভিসসমূহ</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.services.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="bi bi-plus-lg me-1"></i> Add Service
                            </a>
                            <a href="{{ route('admin.services.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                Manage All
                            </a>
                        </div>
                    </div>

                    {{-- Service Highlights Badges --}}
                    <div class="d-flex flex-wrap gap-2 mt-3 pt-2 border-top">
                        <span class="badge bg-light text-dark border px-3 py-2">
                            <i class="bi bi-check2-circle text-success me-1"></i> {{ $serviceStats['active'] }} Active on Web
                        </span>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            <i class="bi bi-cart-plus text-primary me-1"></i> {{ $serviceStats['addon_for_booking'] }} Booking Add-ons
                        </span>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            <i class="bi bi-star-fill text-warning me-1"></i> {{ $serviceStats['featured'] }} Featured Services
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        @forelse($servicesList as $service)
                            <div class="col-12 col-md-6">
                                <div class="service-mini-card h-100 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="service-icon-wrapper bg-primary-subtle text-primary">
                                                <i class="bi {{ $service->icon ?: 'bi-box-seam' }}"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="fw-bold mb-1 text-dark text-truncate" title="{{ $service->title }}">
                                                        {{ $service->title }}
                                                    </h6>
                                                    @if($service->status)
                                                        <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 0.65rem;">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill" style="font-size: 0.65rem;">Draft</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small text-truncate mb-2" style="font-size: 0.8rem;">
                                                    {{ $service->short_description ?: 'পেশাদার শিফটিং ও পরিবহন সেবা' }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Service Pricing Display --}}
                                        <div class="bg-light rounded p-2 mt-2 d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Pricing:</span>
                                            <div>
                                                @if($service->pricing_type === 'fixed' && $service->price)
                                                    <span class="text-success fw-bold">৳{{ number_format($service->price, 2) }}</span>
                                                @elseif($service->pricing_type === 'range' && $service->price_range)
                                                    <span class="text-primary fw-semibold small">{{ $service->price_range }}</span>
                                                @else
                                                    <span class="text-muted small">আলোচনা সাপেক্ষে</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Bottom Meta & Action --}}
                                    <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-1">
                                            @if($service->is_addon_for_booking)
                                                <span class="badge bg-info-subtle text-info-emphasis" style="font-size: 0.65rem;">
                                                    <i class="bi bi-plus-circle me-1"></i> Booking Add-on
                                                </span>
                                            @endif
                                            @if($service->is_featured)
                                                <span class="badge bg-warning-subtle text-warning-emphasis" style="font-size: 0.65rem;">
                                                    <i class="bi bi-star me-1"></i> Featured
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted" style="font-size: 0.75rem;" title="Times booked">
                                                <i class="bi bi-clipboard2-check text-secondary"></i> {{ $service->booking_services_count }} Bookings
                                            </span>
                                            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;">
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted">
                                <p class="mb-2">No services found.</p>
                                <a href="{{ route('admin.services.create') }}" class="btn btn-sm btn-primary">Add Service</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column: Operations Hub, Recent Inquiries, Content Counters --}}
        <div class="col-lg-4">

            {{-- 1. Operations Quick Hub --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-grid-1x2 text-primary me-2"></i>Operations Quick Hub
                    </h5>
                    <p class="text-muted small mb-0">গুরুত্বপূর্ণ অপারেশনাল মেনু ও সেটিংস</p>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('admin.shifting-bookings.index') }}" class="quick-link-item">
                            <span><i class="bi bi-truck me-2 text-warning"></i>All Bookings &amp; Dispatch</span>
                            <span class="badge bg-primary rounded-pill">{{ $bookingStats['total'] }}</span>
                        </a>
                        <a href="{{ route('admin.property-types.index') }}" class="quick-link-item">
                            <span><i class="bi bi-building me-2 text-info"></i>Property Types (বাসা/অফিস)</span>
                            <span class="badge bg-secondary rounded-pill">{{ $contentStats['property_types'] }}</span>
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="quick-link-item">
                            <span><i class="bi bi-tools me-2 text-success"></i>Services &amp; Pricing Setup</span>
                            <span class="badge bg-success rounded-pill">{{ $serviceStats['total'] }}</span>
                        </a>
                        <a href="{{ route('admin.contacts.index') }}" class="quick-link-item">
                            <span><i class="bi bi-envelope me-2 text-danger"></i>Customer Messages</span>
                            @if($contentStats['unread_contacts'] > 0)
                                <span class="badge bg-danger rounded-pill">{{ $contentStats['unread_contacts'] }} new</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">{{ $contentStats['contacts'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.system-tools.index') }}" class="quick-link-item">
                            <span><i class="bi bi-hdd-network me-2 text-secondary"></i>System Cache &amp; Storage</span>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                        <a href="{{ Route::has('home') ? route('home') : url('/') }}" target="_blank" class="quick-link-item">
                            <span><i class="bi bi-globe me-2 text-primary"></i>Live Frontend Website</span>
                            <i class="bi bi-box-arrow-up-right text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. Recent Customer Inquiries / Messages --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-chat-left-dots text-primary me-2"></i>Customer Inquiries
                        </h5>
                        <p class="text-muted small mb-0">ওয়েবসাইট থেকে আসা সাম্প্রতিক বার্তা</p>
                    </div>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2" style="font-size: 0.75rem;">
                        View All
                    </a>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="list-group list-group-flush">
                        @forelse($recentContacts as $contact)
                            <a href="{{ route('admin.contacts.show', $contact->id) }}" class="list-group-item list-group-item-action border-0 px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark small text-truncate" style="max-width: 160px;">{{ $contact->name }}</strong>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ $contact->created_at->diffForHumans(null, true) }}</span>
                                </div>
                                <div class="text-muted small text-truncate" style="font-size: 0.8rem;">
                                    {{ $contact->message }}
                                </div>
                                @if(!$contact->is_read)
                                    <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem;">Unread</span>
                                @endif
                            </a>
                        @empty
                            <div class="text-center py-3 text-muted small">
                                No contact inquiries yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 3. Website Content Summary --}}
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                    <h6 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-file-earmark-text text-secondary me-2"></i>Website Content Summary
                    </h6>
                    <p class="text-muted small mb-0">কনটেন্ট ও পাবলিকেশন স্ট্যাটাস</p>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <a href="{{ route('admin.blogs.index') }}" class="text-decoration-none">
                                <div class="p-2 border rounded bg-light">
                                    <h5 class="fw-bold text-dark mb-0">{{ $contentStats['blogs'] }}</h5>
                                    <small class="text-muted" style="font-size: 0.75rem;">Blogs</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('admin.team-members.index') }}" class="text-decoration-none">
                                <div class="p-2 border rounded bg-light">
                                    <h5 class="fw-bold text-dark mb-0">{{ $contentStats['team_members'] }}</h5>
                                    <small class="text-muted" style="font-size: 0.75rem;">Team</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('admin.partners.index') }}" class="text-decoration-none">
                                <div class="p-2 border rounded bg-light">
                                    <h5 class="fw-bold text-dark mb-0">{{ $contentStats['partners'] }}</h5>
                                    <small class="text-muted" style="font-size: 0.75rem;">Partners</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('admin.galleries.index') }}" class="text-decoration-none">
                                <div class="p-2 border rounded bg-light">
                                    <h5 class="fw-bold text-dark mb-0">{{ $contentStats['galleries'] }}</h5>
                                    <small class="text-muted" style="font-size: 0.75rem;">Gallery</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('admin.testimonials.index') }}" class="text-decoration-none">
                                <div class="p-2 border rounded bg-light">
                                    <h5 class="fw-bold text-dark mb-0">{{ $contentStats['testimonials'] }}</h5>
                                    <small class="text-muted" style="font-size: 0.75rem;">Reviews</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('admin.settings.edit') }}" class="text-decoration-none">
                                <div class="p-2 border rounded bg-light">
                                    <i class="bi bi-gear fs-5 text-dark d-block"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Settings</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
