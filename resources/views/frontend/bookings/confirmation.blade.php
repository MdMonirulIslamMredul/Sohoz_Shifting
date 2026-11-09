@extends('frontend.layouts.app')

@section('title', 'বুকিং নিশ্চিতকরণ | সহজ শিফটিং')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 text-center bg-white">
                    <div class="mb-4">
                        <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-check-lg display-4"></i>
                        </div>
                    </div>

                    <h2 class="fw-bold text-dark mb-2">আপনার বুকিং রিকোয়েস্ট জমা হয়েছে!</h2>
                    <p class="text-muted mb-4">আমাদের প্রতিনিধি খুব শীঘ্রই আপনার সাথে ফোনে যোগাযোগ করে শিডিউল চূড়ান্ত করবেন।</p>

                    @if(session('auto_registered'))
                        <div class="alert alert-success border-0 rounded-3 text-start mb-4 p-3 d-flex align-items-center gap-3 shadow-sm" style="background-color: #ecfdf5; border-left: 4px solid #10b981 !important;">
                            <i class="bi bi-person-check-fill fs-2 text-success"></i>
                            <div>
                                <strong class="d-block text-dark">আপনার অ্যাকাউন্ট স্বয়ংক্রিয়ভাবে তৈরি হয়েছে এবং আপনি লগইন আছেন!</strong>
                                <small class="text-secondary">ভবিষ্যতে লগইন করতে আপনার মোবাইল নাম্বার (<strong>{{ $booking->customer_phone }}</strong>) এবং পাসওয়ার্ড হিসেবে একই মোবাইল নাম্বার ব্যবহার করতে পারেন।</small>
                            </div>
                        </div>
                    @endif

                    <div class="p-3 bg-light rounded-3 border mb-4 text-start">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <label class="text-muted small">বুকিং কোড (Booking Code):</label>
                                <div class="fw-bold fs-5 text-primary">{{ $booking->booking_code }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small">গ্রাহকের নাম:</label>
                                <div class="fw-bold text-dark">{{ $booking->customer_name }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small">প্রপার্টি:</label>
                                <div class="fw-semibold">{{ $booking->display_property_type }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small">শিফটিংয়ের তারিখ:</label>
                                <div class="fw-semibold">{{ $booking->shifting_date->format('d M, Y') }} ({{ $booking->preferred_time_slot }})</div>
                            </div>
                            <div class="col-12 border-top pt-2 mt-2">
                                <label class="text-muted small">রুট:</label>
                                <div class="fw-semibold">
                                    <span class="text-primary">{{ $booking->pickup_area }}</span>
                                    <i class="bi bi-arrow-right mx-1"></i>
                                    <span class="text-success">{{ $booking->delivery_area }}</span>
                                </div>
                            </div>
                            <div class="col-12 border-top pt-2 mt-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">ভাড়া / চার্জ:</span>
                                    @if($booking->final_cost)
                                        <span class="fs-4 fw-bold text-success">৳{{ number_format($booking->final_cost, 2) }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark fs-6 py-2 px-3">
                                            <i class="bi bi-chat-quote-fill me-1"></i>আলোচনা সাপেক্ষে
                                        </span>
                                    @endif
                                </div>
                                @if(!$booking->final_cost)
                                    <small class="text-muted d-block mt-1 text-center">আমাদের প্রতিনিধি শীঘ্রই ফোনে আপনার সাথে আলোচনা করে চূড়ান্ত ভাড়া নির্ধারণ করবেন।</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- WhatsApp Action --}}
                    <div class="mb-4">
                        <a href="{{ $whatsappUrl }}" target="_blank" onclick="if(typeof fbq==='function'){ fbq('trackCustom', 'WhatsAppBookingClick', { booking_code: '{{ $booking->booking_code }}' }); }" class="btn btn-success btn-lg w-100 py-3 shadow-sm fw-bold">
                            <i class="bi bi-whatsapp me-2"></i>হোয়াটসঅ্যাপে বুকিং কনফার্ম করুন (1-Click)
                        </a>
                        <small class="text-muted d-block mt-2">হোয়াটসঅ্যাপে ক্লিক করে বুকিং সারাংশ দ্রুত পাঠান</small>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-warning text-dark fw-bold px-4 shadow-sm" style="background-color: #f59e0b; border: none;">
                            <i class="bi bi-grid-fill me-1"></i>আমার বুকিং ড্যাশবোর্ড
                        </a>
                        <a href="{{ route('bookings.track', ['search' => $booking->booking_code]) }}" class="btn btn-outline-primary px-4">
                            <i class="bi bi-search me-1"></i>বুকিং ট্র্যাক
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-house me-1"></i>হোমপেজ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($settings?->meta_pixel_id) && ($settings->enable_marketing_tracking ?? true))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof fbq === 'function') {
                // Meta (Facebook) Pixel Purchase Event on Booking Confirmation
                fbq('track', 'Purchase', {
                    content_name: @json($booking->display_property_type),
                    content_category: 'Shifting Service',
                    content_ids: [@json($booking->booking_code)],
                    content_type: 'product',
                    value: {{ (float) ($booking->final_cost ?: ($booking->estimated_cost ?: 1.00)) }},
                    currency: 'BDT',
                    num_items: 1,
                    order_id: @json($booking->booking_code)
                });

                // Meta Lead Event
                fbq('track', 'Lead', {
                    content_name: @json($booking->display_property_type),
                    content_category: 'Shifting Booking',
                    value: {{ (float) ($booking->final_cost ?: ($booking->estimated_cost ?: 1.00)) }},
                    currency: 'BDT'
                });
            }
        });
    </script>
    @endpush
@endif
@endsection
