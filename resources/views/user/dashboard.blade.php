@extends('frontend.layouts.app')

@section('title', 'আমার অ্যাকাউন্ট ও ড্যাশবোর্ড | সহজ শিফটিং')

@push('styles')
<style>
    .user-kpi-card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .user-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }
    .booking-summary-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        overflow: hidden;
    }
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        position: relative;
    }
    .stepper-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        font-size: 0.72rem;
        text-align: center;
    }
    .stepper-item::before {
        position: absolute;
        content: "";
        border-bottom: 3px solid #e2e8f0;
        width: 100%;
        top: 14px;
        left: -50%;
        z-index: 1;
    }
    .stepper-item:first-child::before {
        content: none;
    }
    .stepper-item .step-counter {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-weight: bold;
        margin-bottom: 6px;
    }
    .stepper-item.completed .step-counter {
        background-color: #10b981;
        color: #ffffff;
    }
    .stepper-item.completed::before {
        border-bottom-color: #10b981;
    }
    .stepper-item.active .step-counter {
        background-color: #f59e0b;
        color: #0f172a;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.25);
    }
</style>
@endpush

@section('content')
<div class="py-5 bg-light min-vh-100">
    <div class="container">
        {{-- Profile Header Banner --}}
        <div class="card border-0 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-3">
                    <div class="col-md-8">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold mb-2">গ্রাহক অ্যাকাউন্ট</span>
                        <h2 class="fw-bold mb-1">স্বাগতম, {{ $user->name }}! 👋</h2>
                        <p class="text-white-50 mb-0">
                            <i class="bi bi-telephone me-1"></i>{{ $user->phone ?? 'মোবাইল যুক্ত নেই' }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('bookings.create') }}" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm px-4">
                            <i class="bi bi-plus-circle me-1"></i> নতুন বুকিং দিন
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- KPI Summary Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="user-kpi-card p-3 shadow-sm text-center">
                    <span class="text-muted small d-block">মোট বুকিং</span>
                    <h3 class="fw-bold text-dark mb-0 mt-1">{{ $activeBookings->count() + $pastBookings->count() }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="user-kpi-card p-3 shadow-sm text-center">
                    <span class="text-muted small d-block">চলতি কার্যক্রম</span>
                    <h3 class="fw-bold text-primary mb-0 mt-1">{{ $activeBookings->count() }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="user-kpi-card p-3 shadow-sm text-center">
                    <span class="text-muted small d-block">বাকি টাকা (Total Due)</span>
                    <h3 class="fw-bold text-danger mb-0 mt-1">৳{{ number_format($activeBookings->sum('due_amount')) }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="user-kpi-card p-3 shadow-sm text-center">
                    <span class="text-muted small d-block">সহায়তা বার্তা / টিকিট</span>
                    <h3 class="fw-bold text-success mb-0 mt-1">{{ $tickets->count() }}</h3>
                </div>
            </div>
        </div>

        {{-- Nav Tabs for Sections --}}
        <ul class="nav nav-tabs nav-fill mb-4 bg-white p-2 rounded-3 border shadow-sm" id="customerTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold py-2" id="active-tab" data-bs-toggle="tab" data-bs-target="#tab-active">
                    <i class="bi bi-clock-history me-1"></i> সক্রিয় বুকিং ({{ $activeBookings->count() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold py-2" id="history-tab" data-bs-toggle="tab" data-bs-target="#tab-history">
                    <i class="bi bi-check2-all me-1"></i> পূর্বের ইতিহাস ({{ $pastBookings->count() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold py-2" id="tickets-tab" data-bs-toggle="tab" data-bs-target="#tab-tickets">
                    <i class="bi bi-chat-left-dots me-1"></i> সহায়তা ও অভিযোগ ({{ $tickets->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="customerTabsContent">
            {{-- Tab 1: Active Bookings --}}
            <div class="tab-pane fade show active" id="tab-active">
                @forelse($activeBookings as $booking)
                    <div class="card booking-summary-card shadow-sm mb-4">
                        {{-- Booking Header --}}
                        <div class="card-header bg-white border-bottom p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <span class="badge bg-dark px-2 py-1 me-2">{{ $booking->booking_code }}</span>
                                <span class="text-muted small">বুকিং তারিখ: {{ $booking->created_at->format('d M, Y h:i A') }}</span>
                            </div>
                            <div>
                                <span class="{{ $booking->status_badge_class }} px-3 py-1 rounded-pill fs-6">
                                    {{ $booking->status_label_bn }}
                                </span>
                            </div>
                        </div>

                        {{-- 10-Stage Status Stepper --}}
                        <div class="p-3 p-md-4 bg-light border-bottom">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt text-primary me-1"></i>বুকিংয়ের বর্তমান অগ্রগতি (Live Status Tracker)</h6>
                            
                            @php
                                $statusSteps = [
                                    'new_request' => 'নতুন রিকোয়েস্ট',
                                    'contacted' => 'যোগাযোগ হয়েছে',
                                    'site_visit_scheduled' => 'ভিজিট নির্ধারিত',
                                    'quotation_sent' => 'কোটেশন প্রেরিত',
                                    'customer_approved' => 'গ্রাহক সম্মত',
                                    'confirmed' => 'বুকিং নিশ্চিত',
                                    'in_progress' => 'শিফটিং চলছে',
                                    'completed' => 'সম্পন্ন',
                                ];
                                $statusKeys = array_keys($statusSteps);
                                $currentIdx = array_search($booking->status, $statusKeys);
                                if ($currentIdx === false) {
                                    if ($booking->status === 'pending') $currentIdx = 0;
                                    elseif ($booking->status === 'assessment_required') $currentIdx = 1;
                                    else $currentIdx = 0;
                                }
                            @endphp

                            <div class="stepper-wrapper overflow-auto py-2">
                                @foreach($statusSteps as $key => $label)
                                    @php
                                        $idx = array_search($key, $statusKeys);
                                        $class = '';
                                        if ($idx < $currentIdx) $class = 'completed';
                                        elseif ($idx === $currentIdx) $class = 'active';
                                    @endphp
                                    <div class="stepper-item {{ $class }}">
                                        <div class="step-counter">
                                            @if($idx < $currentIdx)
                                                <i class="bi bi-check"></i>
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </div>
                                        <div class="step-name">{{ $label }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Booking Body --}}
                        <div class="card-body p-3 p-md-4">
                            <div class="row g-4">
                                {{-- Route details --}}
                                <div class="col-lg-5 border-end-lg">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-start mb-2">
                                            <i class="bi bi-geo-alt-fill text-danger fs-5 me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">পিকআপ লোকেশন</small>
                                                <strong>{{ $booking->pickup_area }}</strong>
                                                <div class="text-muted small">{{ $booking->pickup_address }} (তলা: {{ $booking->pickup_floor }}, লিফট: {{ $booking->pickup_has_lift ? 'আছে' : 'নেই' }})</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-geo-alt-fill text-success fs-5 me-2"></i>
                                            <div>
                                                <small class="text-muted d-block">ডেলিভারি লোকেশন</small>
                                                <strong>{{ $booking->delivery_area }}</strong>
                                                <div class="text-muted small">{{ $booking->delivery_address }} (তলা: {{ $booking->delivery_floor }}, লিফট: {{ $booking->delivery_has_lift ? 'আছে' : 'নেই' }})</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-light rounded-3 small">
                                        <div><i class="bi bi-calendar-event text-primary me-1"></i> <strong>শিফটিং তারিখ:</strong> {{ $booking->shifting_date->format('d M, Y') }} ({{ $booking->preferred_time_slot }})</div>
                                        <div class="mt-1"><i class="bi bi-truck text-secondary me-1"></i> <strong>গাড়ি:</strong> {{ $booking->vehicle_type_label_bn }} | <strong>লেবার:</strong> {{ $booking->labour_count }} জন</div>
                                        @if($booking->special_items_json)
                                            <div class="mt-2 text-warning-emphasis">
                                                <strong><i class="bi bi-box-seam me-1"></i>বিশেষ মালামাল:</strong>
                                                {{ implode(', ', $booking->special_items_json) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Financial & Quotation details --}}
                                <div class="col-lg-4 border-end-lg">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-cash-stack text-success me-1"></i>ভাড়া ও পেমেন্টের হিসাব</h6>
                                    
                                    <div class="d-flex justify-content-between py-1 border-bottom small">
                                        <span class="text-muted">আনুমানিক মোট ভাড়া:</span>
                                        <strong>৳{{ number_format($booking->estimated_cost) }}</strong>
                                    </div>

                                    @if($booking->discount_amount > 0)
                                        <div class="d-flex justify-content-between py-1 border-bottom small text-success">
                                            <span>কুপন ছাড় (Discount):</span>
                                            <strong>-৳{{ number_format($booking->discount_amount) }}</strong>
                                        </div>
                                    @endif

                                    @if($booking->final_cost)
                                        <div class="d-flex justify-content-between py-1 border-bottom small">
                                            <span class="text-primary fw-bold">চূড়ান্ত ভাড়া (Final):</span>
                                            <strong class="text-primary">৳{{ number_format($booking->final_cost) }}</strong>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between py-1 border-bottom small">
                                        <span class="text-muted">অগ্রিম পরিশোধিত:</span>
                                        <strong class="text-success">৳{{ number_format($booking->advance_amount) }}</strong>
                                    </div>

                                    <div class="d-flex justify-content-between py-2 mb-2">
                                        <span class="fw-bold">অবশিষ্ট বাকি (Due):</span>
                                        <strong class="text-danger fs-5">৳{{ number_format($booking->due_amount) }}</strong>
                                    </div>

                                    <div>
                                        <span class="badge bg-secondary-subtle text-dark border">
                                            পেমেন্ট স্ট্যাটাস: {{ $booking->payment_status_label_bn }}
                                        </span>
                                    </div>

                                    {{-- Quotation Notice & Approval Button --}}
                                    @if($booking->latestQuotation)
                                        <div class="mt-3 p-2 rounded bg-primary-subtle border border-primary border-opacity-25 small">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <strong>অফিসিয়াল কোটেশন (#{{ $booking->latestQuotation->quotation_number }}):</strong>
                                                <span class="badge {{ $booking->latestQuotation->status === 'approved' ? 'bg-success' : 'bg-primary' }}">
                                                    {{ ucfirst($booking->latestQuotation->status) }}
                                                </span>
                                            </div>
                                            <div class="mb-2">মোট চূড়ান্ত ভাড়া: <strong>৳{{ number_format($booking->latestQuotation->total_amount) }}</strong></div>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('user.quotations.show', $booking) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i> কোটেশন দেখুন
                                                </a>
                                                @if($booking->latestQuotation->status !== 'approved' && in_array($booking->status, ['quotation_sent', 'site_visit_scheduled']))
                                                    <form action="{{ route('user.quotations.approve', $booking) }}" method="POST" class="w-100 m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success w-100 fw-bold">
                                                            <i class="bi bi-check2-circle me-1"></i> সম্মত / Approve
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Action Buttons --}}
                                <div class="col-lg-3 d-flex flex-column justify-content-between">
                                    <div class="d-grid gap-2">
                                        @if($booking->latestInvoice)
                                            <a href="{{ route('user.invoices.show', $booking) }}" target="_blank" class="btn btn-success fw-bold">
                                                <i class="bi bi-receipt me-1"></i> ইনভয়েস ডাউনলোড
                                            </a>
                                        @endif

                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal_{{ $booking->id }}">
                                            <i class="bi bi-calendar-range me-1"></i> তারিখ পরিবর্তন
                                        </button>

                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal_{{ $booking->id }}">
                                            <i class="bi bi-x-circle me-1"></i> বুকিং বাতিল
                                        </button>

                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ticketModal_{{ $booking->id }}">
                                            <i class="bi bi-chat-square-text me-1"></i> অভিযোগ / বার্তা
                                        </button>
                                    </div>

                                    <div class="text-center mt-3 pt-2 border-top">
                                        <a href="https://wa.me/8801700000000?text=Hello%20Sohoz%20Shifting,%20Booking%20Code:%20{{ $booking->booking_code }}" target="_blank" class="btn btn-sm btn-outline-success w-100">
                                            <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপে কথা বলুন
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal: Reschedule --}}
                    <div class="modal fade" id="rescheduleModal_{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('user.bookings.reschedule', $booking) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">শিফটিংয়ের তারিখ পরিবর্তন করুন</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">নতুন কাঙ্ক্ষিত তারিখ <span class="text-danger">*</span></label>
                                            <input type="date" name="reschedule_requested_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">কারণ বা বিশেষ নোট</label>
                                            <textarea name="reschedule_notes" class="form-control" rows="3" placeholder="তারিখ পরিবর্তনের কারণ উল্লেখ করুন..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                        <button type="submit" class="btn btn-primary">অনুরোধ জমা দিন</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal: Cancel --}}
                    <div class="modal fade" id="cancelModal_{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold text-danger">বুকিং বাতিল করার অনুরোধ</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted small">বুকিং বাতিল করতে চাইলে কারণ উল্লেখ করুন। আমাদের কাস্টমার সাপোর্ট শীঘ্রই যাচাই করবে।</p>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">বাতিল করার কারণ <span class="text-danger">*</span></label>
                                            <textarea name="cancellation_reason" class="form-control" rows="3" placeholder="e.g. পরিকল্পনা পরিবর্তন হয়েছে..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ফিরে যান</button>
                                        <button type="submit" class="btn btn-danger">বুকিং বাতিল করুন</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal: Ticket / Feedback --}}
                    <div class="modal fade" id="ticketModal_{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('user.tickets.store', $booking) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold"><i class="bi bi-chat-left-dots text-primary me-2"></i>অভিযোগ বা মতামত জানান</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">বিষয় (Subject) <span class="text-danger">*</span></label>
                                            <input type="text" name="subject" class="form-control" placeholder="e.g. লেবার দেরিতে এসেছে / ভাড়া সংক্রান্ত বিষয়" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label fw-bold">ক্যাটেগরি</label>
                                                <select name="category" class="form-select">
                                                    <option value="complaint">অভিযোগ (Complaint)</option>
                                                    <option value="feedback">ফিডব্যাক (Feedback)</option>
                                                    <option value="query">জিজ্ঞাসা (Query)</option>
                                                    <option value="reschedule">শিডিউল পরিবর্তন</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-bold">অগ্রাধিকার</label>
                                                <select name="priority" class="form-select">
                                                    <option value="normal">সাধারণ (Normal)</option>
                                                    <option value="high">জরুরি (High)</option>
                                                    <option value="urgent">অতি জরুরি (Urgent)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">আপনার বিস্তারিত বার্তা <span class="text-danger">*</span></label>
                                            <textarea name="message" class="form-control" rows="4" placeholder="আপনার সমস্যা বা মন্তব্য বিস্তারিত লিখুন..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                        <button type="submit" class="btn btn-primary">বার্তা পাঠান</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="card p-5 text-center bg-white rounded-4 border-0 shadow-sm">
                        <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                        <h5 class="fw-bold text-dark">কোনো সক্রিয় বুকিং নেই</h5>
                        <p class="text-muted small">আপনার বাসা বা অফিস শিফটিংয়ের জন্য এখনই একটি অনলাইন বুকিং দিন।</p>
                        <div>
                            <a href="{{ route('bookings.create') }}" class="btn btn-warning fw-bold px-4 text-dark shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> নতুন বুকিং শুরু করুন
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Tab 2: Past Bookings --}}
            <div class="tab-pane fade" id="tab-history">
                @forelse($pastBookings as $booking)
                    <div class="card p-3 mb-3 bg-white border rounded-3 shadow-sm">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-secondary me-2">{{ $booking->booking_code }}</span>
                                <strong>{{ $booking->pickup_area }} ➔ {{ $booking->delivery_area }}</strong>
                                <span class="text-muted small ms-2">({{ $booking->shifting_date->format('d M, Y') }})</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                <span class="{{ $booking->status_badge_class }}">{{ $booking->status_label_bn }}</span>
                                @if($booking->latestInvoice)
                                    <a href="{{ route('user.invoices.show', $booking) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-receipt"></i> ইনভয়েস
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted small bg-white rounded-4 border">
                        কোনো পূর্বের সম্পূর্ণ বা বাতিলকৃত বুকিং নেই।
                    </div>
                @endforelse
            </div>

            {{-- Tab 3: Tickets & Complaints --}}
            <div class="tab-pane fade" id="tab-tickets">
                @forelse($tickets as $ticket)
                    <div class="card p-4 mb-3 bg-white border rounded-3 shadow-sm">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-dark me-2">#{{ $ticket->ticket_number }}</span>
                                <strong class="fs-6">{{ $ticket->subject }}</strong>
                                <span class="badge bg-light text-secondary border ms-2">{{ ucfirst($ticket->category) }}</span>
                            </div>
                            <div>
                                <span class="badge {{ $ticket->status === 'resolved' ? 'bg-success' : ($ticket->status === 'closed' ? 'bg-secondary' : 'bg-warning text-dark') }} px-3 py-1 rounded-pill">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Conversation messages --}}
                        <div class="p-3 bg-light rounded-3 mb-3" style="max-height: 250px; overflow-y: auto;">
                            @foreach($ticket->messages as $msg)
                                <div class="mb-2 p-2 rounded {{ ($msg['sender'] ?? '') === 'admin' ? 'bg-primary-subtle border-start border-primary border-4 text-dark ms-4' : 'bg-white shadow-sm me-4' }}">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <strong>{{ $msg['sender_name'] ?? 'ব্যবহারকারী' }}</strong>
                                        <span>{{ isset($msg['timestamp']) ? \Carbon\Carbon::parse($msg['timestamp'])->diffForHumans() : '' }}</span>
                                    </div>
                                    <div>{{ $msg['message'] ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Reply form if not closed --}}
                        @if($ticket->status !== 'closed')
                            <form action="{{ route('user.tickets.reply', $ticket) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="message" class="form-control" placeholder="আপনার উত্তর লিখুন..." required>
                                    <button type="submit" class="btn btn-primary fw-bold">পাঠান</button>
                                </div>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5 text-muted small bg-white rounded-4 border">
                        কোনো সক্রিয় অভিযোগ বা টিকিট নেই।
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
