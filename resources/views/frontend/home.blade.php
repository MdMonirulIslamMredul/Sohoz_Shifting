@extends('frontend.layouts.app')

@section('title', $setting->meta_title ?? 'সহজ শিফটিং | বাসা ও অফিস শিফটিং সার্ভিস')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
    <style>
        /* Hero Slider: Full Viewport Continuous Track */
        .hero-slider {
            position: relative;
            overflow: hidden;
            background: #0f172a;
            height: 82vh;
            min-height: 560px;
        }

        @media (min-width: 992px) {
            .hero-slider {
                height: 90vh;
                min-height: 620px;
            }
        }

        .hero-swiper {
            width: 100%;
            height: 100%;
            position: relative;
            user-select: none;
            -webkit-user-select: none;
        }

        .hero-swiper .swiper-wrapper {
            height: 100%;
            display: flex;
        }

        .hero-swiper .swiper-slide {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: stretch;
            background: #0f172a;
            height: 100% !important;
            width: 100%;
            flex-shrink: 0;
        }

        .hero-swiper .hero-slide-bg {
            position: absolute;
            top: -4%;
            left: -4%;
            width: 108%;
            height: 108%;
            background-size: cover;
            background-position: center;
            transform: scale(1);
            will-change: transform;
            z-index: 1;
        }

        .hero-swiper .swiper-slide-active .hero-slide-bg {
            animation: heroContinuousZoom 8s ease-out forwards;
        }

        .hero-swiper .swiper-slide:not(.swiper-slide-active) .hero-slide-bg {
            animation: none;
            transform: scale(1);
        }

        @keyframes heroContinuousZoom {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.12);
            }
        }

        .hero-swiper .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.65) 60%, rgba(245, 158, 11, 0.25) 100%);
            z-index: 2;
        }

        .hero-swiper .hero-content {
            position: relative;
            z-index: 3;
            color: #fff;
        }

        .hero-swiper .hero-slide-inner {
            padding-top: 5.5rem;
            padding-bottom: 3.5rem;
            position: relative;
            z-index: 3;
            width: 100%;
            height: 100%;
        }

        @media (min-width: 992px) {
            .hero-swiper .hero-slide-inner {
                padding-top: 5rem;
                padding-bottom: 2.5rem;
            }
        }

        .hero-swiper .swiper-slide .hero-title {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hero-swiper .swiper-slide .hero-subtitle {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hero-swiper .swiper-slide .hero-actions {
            opacity: 0;
            transform: translateY(25px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hero-swiper .swiper-slide-active .hero-title {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.18s;
        }

        .hero-swiper .swiper-slide-active .hero-subtitle {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.36s;
        }

        .hero-swiper .swiper-slide-active .hero-actions {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.52s;
        }

        /* Navigation Arrows */
        .hero-swiper-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 50%;
            background-color: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            cursor: pointer;
            z-index: 10;
            transition: all 0.25s ease;
            opacity: 0.75;
        }

        .hero-swiper-btn:hover {
            background-color: rgba(15, 23, 42, 0.9);
            border-color: rgba(255, 255, 255, 0.7);
            color: #ffffff;
            transform: translateY(-50%) scale(1.12);
            opacity: 1;
        }

        .hero-swiper-prev {
            left: 1.5rem;
        }

        .hero-swiper-next {
            right: 1.5rem;
        }

        @media (max-width: 768px) {
            .hero-swiper-btn {
                display: none;
            }
        }

        .hero-swiper-pagination {
            position: absolute;
            bottom: 2.2rem !important;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            z-index: 10;
        }

        .hero-swiper-pagination .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.75);
            opacity: 0.65;
            margin: 0 !important;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
        }

        .hero-swiper-pagination .swiper-pagination-bullet-active {
            width: 36px;
            border-radius: 12px;
            background-color: #f59e0b;
            border-color: #f59e0b;
            opacity: 1;
        }

        .hero-autoplay-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.15);
            z-index: 10;
        }

        .hero-autoplay-progress-fill {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.6);
        }

        /* Booking Form Styles */
        .home-booking-card {
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .booking-step-badge {
            width: 30px;
            height: 30px;
            background-color: #0f172a;
            color: #ffffff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            margin-right: 8px;
        }

        .cost-calculator-card {
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gold-btn {
            background-color: #f59e0b;
            color: #0f172a;
            font-weight: 700;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .gold-btn:hover {
            background-color: #d97706;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Service Card (Exact replica of Services Page) */
        .service-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
            position: relative;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.09);
            border-color: #cbd5e1;
        }

        .service-media {
            position: relative;
            height: 220px;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            z-index: 2;
        }
        .service-media-img-wrap {
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 20px 20px 0 0;
            position: relative;
        }
        .service-media-img-wrap a {
            display: block;
            width: 100%;
            height: 100%;
        }
        .service-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
            display: block;
        }
        .service-card:hover .service-media-img-wrap img {
            transform: scale(1.08);
        }
        .service-media-placeholder {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }

        /* Floating Circle Icon Badge */
        .service-floating-icon {
            position: absolute;
            bottom: -22px;
            left: 24px;
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
            border: 3px solid #ffffff;
            z-index: 10;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .service-card:hover .service-floating-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Card Featured Badge */
        .service-badge-featured {
            position: absolute;
            top: 14px;
            right: 14px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            padding: 4px 12px;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
            z-index: 2;
        }

        .service-card-body {
            padding: 2rem 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .service-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            text-decoration: none;
            margin-bottom: 0.5rem;
            line-height: 1.35;
            transition: color 0.15s ease;
        }
        .service-title:hover {
            color: #0284c7;
        }
        .service-short-desc {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }

        /* Counter Section */
        .counter-section {
            position: relative;
        }

        .counter-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 1.8rem 1.4rem;
            text-align: center;
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.08);
            height: 100%;
            border: 1px solid #f1f5f9;
        }

        .counter-icon {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.14), rgba(15, 23, 42, 0.08));
            color: #d97706;
        }

        .counter-number {
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.4rem;
            color: #0f172a;
        }

        .counter-title {
            margin-bottom: 0;
            color: #475569;
            font-weight: 600;
        }

        /* Testimonials */
        .testimonial-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.08);
            padding: 1.75rem;
            height: 100%;
            border: 1px solid #e2e8f0;
        }

        .testimonial-avatar {
            width: 58px;
            height: 58px;
            border-radius: 999px;
            object-fit: cover;
            border: 2px solid #f59e0b;
            background: #f8fafc;
        }

        /* Partners Infinity Marquee */
        .partners-infinity-section {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .partner-marquee-wrapper {
            width: 100%;
            overflow: hidden;
            position: relative;
            padding: 1.25rem 0;
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }

        .partner-marquee-track {
            display: flex;
            width: max-content;
            user-select: none;
        }

        .partner-marquee-group {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 1.25rem;
            flex-shrink: 0;
            width: max-content;
            animation: partnerMarqueeScroll 32s linear infinite;
            padding-right: 1.25rem;
        }

        .partner-marquee-wrapper:hover .partner-marquee-group {
            animation-play-state: paused;
        }

        @keyframes partnerMarqueeScroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .partner-marquee-card {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 190px;
            height: 76px;
            padding: 0.35rem 0.85rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            color: #1e293b;
        }
    </style>
@endpush

@section('content')
    @php
        $fallbackSlides = [
            [
                'title' => 'বাসা বদল হোক চাপমুক্ত, সহজে।',
                'subtitle' => 'ঢাকা মহানগরী সহ সারাদেশে দ্রুত ও নিরাপদ বাসা-অফিস শিফটিং। দক্ষ লেবার, আধুনিক কাভার্ড ভ্যান ও অভিজ্ঞ প্যাকিং সেবা।',
                'image' => asset('storage/hero-slider/hero_shifting_1.jpg'),
            ],
            [
                'title' => 'নিরাপদ প্যাকিং ও অভিজ্ঞ হ্যান্ডলিং',
                'subtitle' => 'বাবল র‍্যাপ ও ফোম প্যাকিং দ্বারা আসবাবপত্র ও ইলেকট্রনিক্স মালামালের শতভাগ সুরক্ষা এবং জিরো-ড্যামেজ নিশ্চয়তা।',
                'image' => asset('storage/hero-slider/hero_shifting_2.jpg'),
            ],
            [
                'title' => 'ফার্নিচার খোলা-ফিটিং ও এসি টেকনিশিয়ান',
                'subtitle' => 'খাট, সোফা, আলমারি খোলা ও নতুন বাসায় পুনরায় ফিটিং সহ এসি ও ইলেকট্রিক্যাল লাইনের সম্পূর্ণ টেকনিক্যাল সাপোর্ট।',
                'image' => asset('storage/hero-slider/hero_shifting_3.jpg'),
            ],
        ];

        $heroSlides = collect($heroSlides ?? [])
            ->values()
            ->map(function ($slide, $index) use ($fallbackSlides) {
                $fallback = $fallbackSlides[$index] ?? end($fallbackSlides);

                return [
                    'title' => $slide->title ?: $fallback['title'],
                    'subtitle' => $slide->subtitle ?: $fallback['subtitle'],
                    'image' => !empty($slide->image) ? asset('storage/' . $slide->image) : $fallback['image'],
                ];
            });

        if ($heroSlides->isEmpty()) {
            $heroSlides = collect($fallbackSlides);
        }

        $activeCounters = collect($counters ?? []);
        $testimonialSlides = collect($testimonials ?? [])->chunk(3)->values();
        $faqItems = collect($faqs ?? []);
        $partnerItems = collect($partners ?? []);
    @endphp

    {{-- 1. HERO BANNER SLIDER --}}
    <section class="hero-slider">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @foreach ($heroSlides as $index => $slide)
                    <div class="swiper-slide">
                        <div class="hero-slide-bg" style="background-image: url('{{ $slide['image'] }}');"></div>
                        <div class="hero-overlay"></div>
                        <div class="container h-100 d-flex align-items-center hero-slide-inner">
                            <div class="hero-content col-lg-8">
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6 mb-3 fw-bold d-inline-block shadow-sm">
                                    <i class="bi bi-shield-check me-1"></i> বিশ্বস্ত ও নিরাপদ শিফটিং পার্টনার
                                </span>
                                <h1 class="display-4 fw-bold hero-title mb-3">{{ $slide['title'] }}</h1>
                                <p class="lead mb-4 hero-subtitle opacity-90">{{ $slide['subtitle'] }}</p>
                                <div class="d-flex flex-wrap gap-3 hero-actions">
                                    <a href="#bookingSection" class="btn btn-warning btn-lg fw-bold text-dark px-4 shadow-sm" style="background-color: #f59e0b; border: none;">
                                        <i class="bi bi-calendar-check-fill me-1"></i> অনলাইন বুকিং দিন
                                    </a>
                                    <a href="#servicesSection" class="btn btn-outline-light btn-lg px-4">
                                        আমাদের সেবাসমূহ
                                    </a>
                                    <a href="https://wa.me/8801700000000?text={{ rawurlencode('আসসালামু আলাইকুম, আমি সহজ শিফটিং সার্ভিস সম্পর্কে বিস্তারিত জানতে চাই।') }}" target="_blank" 
                                       class="btn btn-success btn-lg fw-bold d-inline-flex align-items-center px-4 shadow-sm" style="background-color: #10b981; border: none;">
                                        <i class="bi bi-whatsapp me-2"></i> হোয়াটসঅ্যাপ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="hero-swiper-btn hero-swiper-prev" type="button" aria-label="Previous Slide">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="hero-swiper-btn hero-swiper-next" type="button" aria-label="Next Slide">
                <i class="bi bi-chevron-right"></i>
            </button>

            <div class="hero-swiper-pagination"></div>
            <div class="hero-autoplay-progress">
                <div class="hero-autoplay-progress-fill"></div>
            </div>
        </div>
    </section>

    {{-- 2. HOMEPAGE BOOKING SECTION (IMMEDIATELY AFTER HERO BANNER) --}}
    <section class="py-5" id="bookingSection" style="background: linear-gradient(180deg, #f8fafc 0%, #edf2f7 100%);">
        <div class="container">
            <div class="text-center mb-4" data-aos="fade-up">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6 mb-2 fw-bold">
                    <i class="bi bi-lightning-charge-fill me-1"></i> সহজ ও দ্রুত শিফটিং
                </span>
                <h2 class="display-6 fw-bold text-dark mb-2">বাসা ও অফিস শিফটিং বুকিং ও ফ্রি কোটেশন</h2>
                <p class="text-muted mx-auto" style="max-width: 650px;">
                    প্রয়োজনীয় তথ্য দিয়ে মাত্র ২ মিনিটে ফ্রি কোটেশন ও লাইভ সম্ভাব্য খরচ দেখে বুকিং সম্পন্ন করুন
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.store') }}" id="homepageBookingForm">
                @csrf
                <div class="row g-4">
                    {{-- Form Steps Column --}}
                    <div class="col-lg-8">
                        {{-- Step 1: Property Type & Moving Schedule --}}
                        <div class="card home-booking-card p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                                <span class="booking-step-badge">১</span>
                                বাসা / অফিসের ধরন ও তারিখ
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">বাসা / অফিসের ধরন <span class="text-danger">*</span></label>
                                    <select name="property_type_selection" id="homePropertyTypeSelect" class="form-select form-select-lg" required>
                                        <option value="" disabled selected>নির্বাচন করুন</option>
                                        @foreach($propertyTypes as $type)
                                            <option value="{{ $type->id }}" data-price="{{ $type->base_price ?? 4000 }}" {{ old('property_type_selection') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name_bn ?: $type->name }}
                                            </option>
                                        @endforeach
                                        <option value="custom" {{ old('property_type_selection') == 'custom' ? 'selected' : '' }}>
                                            ➕ অন্যান্য / কাস্টম প্রপার্টি (নিজে লিখুন)
                                        </option>
                                    </select>
                                </div>

                                {{-- Manual Custom Property Input --}}
                                <div class="col-md-6" id="homeCustomPropertyContainer" style="{{ old('property_type_selection') == 'custom' ? '' : 'display: none;' }}">
                                    <label class="form-label fw-bold text-primary">আপনার প্রপার্টির নাম লিখুন <span class="text-danger">*</span></label>
                                    <input type="text" name="custom_property_type" id="homeCustomPropertyInput" class="form-control form-control-lg border-primary" 
                                        placeholder="e.g. ৩ তলা ডুপ্লেক্স, শোরুম, বাথরুম সহ রুম" value="{{ old('custom_property_type') }}">
                                    <small class="text-muted">তালিকায় না থাকলে এখানে লিখে দিন</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">শিফটিংয়ের তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="shifting_date" class="form-control form-control-lg" min="{{ date('Y-m-d') }}" value="{{ old('shifting_date', date('Y-m-d', strtotime('+1 day'))) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">পছন্দের সময় (Time Slot)</label>
                                    <select name="preferred_time_slot" class="form-select form-select-lg">
                                        <option value="সকাল (৮টা - ১২টা)" {{ old('preferred_time_slot') == 'সকাল (৮টা - ১২টা)' ? 'selected' : '' }}>সকাল (৮টা - ১২টা)</option>
                                        <option value="দুপুর (১২টা - ৪টা)" {{ old('preferred_time_slot') == 'দুপুর (১২টা - ৪টা)' ? 'selected' : '' }}>দুপুর (১২টা - ৪টা)</option>
                                        <option value="বিকেল (৪টা - রাত ৮টা)" {{ old('preferred_time_slot') == 'বিকেল (৪টা - রাত ৮টা)' ? 'selected' : '' }}>বিকেল (৪টা - রাত ৮টা)</option>
                                        <option value="রাত (৮টা - রাত ১১টা)" {{ old('preferred_time_slot') == 'রাত (৮টা - রাত ১১টা)' ? 'selected' : '' }}>রাত (৮টা - রাত ১১টা)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Route Details (Pickup & Delivery) --}}
                        <div class="card home-booking-card p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                                <span class="booking-step-badge">২</span>
                                শিফটিং লোকেশন (বর্তমান বাসা ও নতুন বাসা)
                            </h5>

                            <div class="row g-4">
                                {{-- Current Location --}}
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> বর্তমান বাসা (Pickup)
                                        </h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">এলাকা / থানা <span class="text-danger">*</span></label>
                                            <input type="text" name="pickup_area" class="form-control" placeholder="e.g. মিরপুর ১০, ধানমন্ডি" value="{{ old('pickup_area') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">বিস্তারিত ঠিকানা <span class="text-danger">*</span></label>
                                            <textarea name="pickup_address" class="form-control" rows="2" placeholder="বাড়ি নং, রোড নং, ল্যান্ডমার্ক..." required>{{ old('pickup_address') }}</textarea>
                                        </div>
                                        <div class="row g-2 align-items-center">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">ফ্লোর / তলা</label>
                                                <input type="number" name="pickup_floor" id="homePickupFloor" class="form-control" min="0" max="40" value="{{ old('pickup_floor', 1) }}">
                                            </div>
                                            <div class="col-6 pt-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="pickup_has_lift" id="homePickupLift" value="1" {{ old('pickup_has_lift') ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold small" for="homePickupLift">লিফট আছে</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Destination Location --}}
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="bi bi-geo-alt-fill text-success me-1"></i> নতুন বাসা (Delivery)
                                        </h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">এলাকা / থানা <span class="text-danger">*</span></label>
                                            <input type="text" name="delivery_area" class="form-control" placeholder="e.g. উত্তরা সেক্টর ৭, গুলশান ২" value="{{ old('delivery_area') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small">বিস্তারিত ঠিকানা <span class="text-danger">*</span></label>
                                            <textarea name="delivery_address" class="form-control" rows="2" placeholder="বাড়ি নং, রোড নং, ফ্ল্যাট নং..." required>{{ old('delivery_address') }}</textarea>
                                        </div>
                                        <div class="row g-2 align-items-center">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">ফ্লোর / তলা</label>
                                                <input type="number" name="delivery_floor" id="homeDeliveryFloor" class="form-control" min="0" max="40" value="{{ old('delivery_floor', 1) }}">
                                            </div>
                                            <div class="col-6 pt-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="delivery_has_lift" id="homeDeliveryLift" value="1" {{ old('delivery_has_lift') ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold small" for="homeDeliveryLift">লিফট আছে</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Vehicle & Staffing --}}
                        <div class="card home-booking-card p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                                <span class="booking-step-badge">৩</span>
                                গাড়ি ও অভিজ্ঞ লেবার পছন্দ
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label fw-bold">পরিবহনের জন্য গাড়ি <span class="text-danger">*</span></label>
                                    <select name="vehicle_type" id="homeVehicleType" class="form-select form-select-lg" required>
                                        <option value="" disabled {{ old('vehicle_type') ? '' : 'selected' }} data-price="0">গাড়ি নির্বাচন করুন</option>
                                        <option value="pickup_7ft" data-price="2500" {{ old('vehicle_type') == 'pickup_7ft' ? 'selected' : '' }}>৭ ফিট ছোট পিকআপ ভ্যান (Small Pickup - ৳২,৫০০)</option>
                                        <option value="covered_9ft" data-price="3800" {{ old('vehicle_type') == 'covered_9ft' ? 'selected' : '' }}>৯ ফিট কাভার্ড ভ্যান (Covered Van - ৳৩,৮০০)</option>
                                        <option value="covered_14ft" data-price="5500" {{ old('vehicle_type') == 'covered_14ft' ? 'selected' : '' }}>১৪ ফিট বড় কাভার্ড ট্রাক (Large Truck - ৳৫,৫০০)</option>
                                        <option value="customer_managed" data-price="0" {{ old('vehicle_type') == 'customer_managed' ? 'selected' : '' }}>গ্রাহক নিজ দায়িত্বে ব্যবস্থা করবেন</option>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label fw-bold">দক্ষ লেবার সংখ্যা</label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" name="labour_count" id="homeLabourCount" class="form-control" min="0" max="15" value="{{ old('labour_count', 0) }}">
                                        <span class="input-group-text">জন</span>
                                    </div>
                                    <small class="text-muted">প্রতিজন অভিজ্ঞ লেবার ৳৮০০ (লোডিং-আনলোডিং)</small>
                                </div>
                            </div>
                        </div>

                        {{-- Step 4: Services & Special Instructions --}}
                        <div class="card home-booking-card p-4 mb-4">
                            <h5 class="fw-bold text-dark mb-2 d-flex align-items-center">
                                <span class="booking-step-badge">৪</span>
                                সার্ভিস ও বিশেষ নির্দেশনা
                            </h5>
                            <p class="text-muted small mb-3">আপনার সুবিধার জন্য নিচের সার্ভিসগুলো বেছে নিতে পারেন:</p>

                            <div class="row g-3 mb-3">
                                @foreach($services as $service)
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 h-100 bg-light d-flex align-items-start">
                                            <div class="form-check w-100">
                                                <input class="form-check-input home-addon-checkbox" type="checkbox" name="selected_services[]" 
                                                    value="{{ $service->id }}" id="home_srv_{{ $service->id }}" data-price="{{ ($service->show_price && $service->price) ? $service->price : 0 }}"
                                                    {{ (is_array(old('selected_services')) && in_array($service->id, old('selected_services'))) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold text-dark ms-2" for="home_srv_{{ $service->id }}">
                                                    <i class="bi {{ $service->icon ?: 'bi-tools' }} text-warning me-1"></i>
                                                    {{ $service->title }}
                                                </label>
                                                @if($service->short_description)
                                                    <div class="text-muted small ms-4">{{ Str::limit($service->short_description, 60) }}</div>
                                                @endif
                                                @if($service->show_price && (($service->pricing_type === 'fixed' && $service->price) || ($service->pricing_type === 'range' && $service->price_range)))
                                                    <div class="ms-4 mt-2">
                                                        @if($service->pricing_type === 'fixed' && $service->price)
                                                            <span class="badge bg-success py-1 px-2">৳{{ number_format($service->price) }}</span>
                                                        @elseif($service->pricing_type === 'range' && $service->price_range)
                                                            <span class="badge bg-primary py-1 px-2">{{ $service->price_range }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="ms-4 mt-2 p-2 rounded bg-white border border-secondary border-opacity-25 shadow-sm small">
                                                        <div class="text-secondary fw-bold" style="font-size: 0.82rem;">
                                                            <i class="bi bi-calculator text-primary me-1"></i>আনুমানিক খরচ
                                                        </div>
                                                        <div class="my-1">
                                                            <span class="badge bg-warning text-dark fw-bold">
                                                                <i class="bi bi-telephone-fill me-1"></i>কল করে জানানো হবে
                                                            </span>
                                                        </div>
                                                        <div class="text-muted" style="font-size: 0.78rem; line-height: 1.35;">
                                                            মূল্য দূরত্ব, ফ্লোর ও মালের পরিমাণের উপর নির্ভর করে।
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Custom Manual Service Request --}}
                            <div class="p-3 bg-light rounded-3 border border-warning">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bi bi-pencil-fill text-warning me-1"></i> অন্য কোনো বিশেষ সার্ভিস বা মালামালের নির্দেশনা প্রয়োজন?
                                </label>
                                <textarea name="custom_service_requests" class="form-control" rows="2" 
                                    placeholder="e.g. কাঁচের ডাইনিং টেবিল বাবল শিট দিয়ে প্যাক করতে হবে, ওয়াল ড্রিলিং সার্ভিস, পিয়ানো শিফটিং...">{{ old('custom_service_requests') }}</textarea>
                                <small class="text-muted">আপনার চাহিদামতো যেকোনো নির্দেশনা এখানে লিখে দিতে পারেন।</small>
                            </div>
                        </div>

                        {{-- Step 5: Customer Details --}}
                        <div class="card home-booking-card p-4">
                            <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                                <span class="booking-step-badge">৫</span>
                                আপনার যোগাযোগের তথ্য
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">আপনার পুরো নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" class="form-control form-control-lg" placeholder="e.g. মোঃ তানভীর আহমেদ" value="{{ old('customer_name') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">মোবাইল নাম্বার <span class="text-danger">*</span></label>
                                    <input type="tel" name="customer_phone" class="form-control form-control-lg" placeholder="e.g. 017XXXXXXXX" value="{{ old('customer_phone') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">হোয়াটসঅ্যাপ নাম্বার (যদি ভিন্ন হয়)</label>
                                    <input type="tel" name="customer_whatsapp" class="form-control form-control-lg" placeholder="e.g. 018XXXXXXXX" value="{{ old('customer_whatsapp') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">ইমেইল (ঐচ্ছিক)</label>
                                    <input type="email" name="customer_email" class="form-control form-control-lg" placeholder="e.g. tanvir@gmail.com" value="{{ old('customer_email') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Live Estimate & Submit Column --}}
                    <div class="col-lg-4">
                        <div class="card cost-calculator-card p-4 shadow-lg sticky-top" style="top: 100px;">
                            <h5 class="fw-bold text-warning mb-3 d-flex align-items-center">
                                <i class="bi bi-calculator-fill me-2 fs-4"></i>সম্ভাব্য খরচ (Live Estimate)
                            </h5>

                            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                                <span class="text-light opacity-75">প্রপার্টি বেস রেট:</span>
                                <span class="fw-bold text-white" id="homeEstBaseRate">৳০</span>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                                <span class="text-light opacity-75">সিঁড়ি ও ফ্লোর চার্জ:</span>
                                <span class="fw-bold text-white" id="homeEstFloorRate">৳০</span>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                                <span class="text-light opacity-75">কাভার্ড ভ্যান / গাড়ি:</span>
                                <span class="fw-bold text-white" id="homeEstVehicleRate">৳০</span>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                                <span class="text-light opacity-75">লেবার চার্জ:</span>
                                <span class="fw-bold text-white" id="homeEstLabourRate">৳০</span>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                                <span class="text-light opacity-75">অতিরিক্ত সার্ভিস:</span>
                                <span class="fw-bold text-white" id="homeEstAddonRate">৳০</span>
                            </div>

                            <div class="py-3 mt-2 text-center bg-dark bg-opacity-25 rounded-3 mb-3">
                                <span class="d-block small text-light opacity-75">আনুমানিক মোট খরচ</span>
                                <h2 class="display-6 fw-bold text-warning mb-0" id="homeEstTotalCost">৳০</h2>
                                <small class="text-light opacity-50 d-block mt-1" id="homeEstNote">*সার্ভিস নির্বাচন করলে স্বয়ংক্রিয়ভাবে খরচ হিসেব হবে</small>
                            </div>

                            <button type="submit" class="btn gold-btn btn-lg w-100 py-3 shadow mb-3">
                                <i class="bi bi-check-circle-fill me-2"></i>বুকিং নিশ্চিত করুন
                            </button>

                            <div class="p-3 rounded-3 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 small text-light opacity-90">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-shield-check text-success me-2"></i> ১০০% নিরাপদ ও বিশ্বস্ত শিফটিং
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-check-circle text-warning me-2"></i> কোনো প্রকার অগ্রিম হিডেন চার্জ নেই
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-headset text-info me-2"></i> ২৪/৭ নিবেদিত সাপোর্ট কোঅর্ডিনেটর
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- 3. SERVICES SECTION: REPLICA OF SERVICES PAGE (CARD GRID WITH IMAGES & FLOATING ICONS) --}}
    <section class="py-5" id="servicesSection" style="background-color: #f8fafc;">
        <div class="container">
            <div class="d-flex flex-wrap align-items-end justify-content-between mb-4">
                <div data-aos="fade-up">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">আমাদের সেবাসমূহ</span>
                    <h2 class="fw-bold mb-1" style="color: #0f172a; font-size: 2.15rem;">পেশাদার শিফটিং ও সাপোর্ট সার্ভিস</h2>
                    <p class="text-muted fs-6 mb-0">সার্ভিস এরিয়া: ঢাকা মহানগরী ও সারাদেশ</p>
                </div>
                <div class="mt-3 mt-md-0" data-aos="fade-up" data-aos-delay="100">
                    <a href="{{ route('services.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
                        সকল সার্ভিস দেখুন <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            @if ($services->isNotEmpty())
                <div class="row g-4">
                    @foreach ($services as $service)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                            <div class="service-card">
                                {{-- Thumbnail --}}
                                <div class="service-media">
                                    <div class="service-media-img-wrap">
                                        @if($service->image)
                                            <a href="{{ route('services.show', $service->slug) }}">
                                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}">
                                            </a>
                                        @else
                                            <a href="{{ route('services.show', $service->slug) }}" class="text-decoration-none">
                                                <div class="service-media-placeholder">
                                                    <i class="bi bi-box-seam" style="font-size: 3.5rem;"></i>
                                                    <span class="small mt-1 text-muted">শিফটিং সার্ভিস</span>
                                                </div>
                                            </a>
                                        @endif
                                    </div>

                                    @if($service->is_featured)
                                        <span class="service-badge-featured">
                                            <i class="bi bi-star-fill me-1"></i> Featured
                                        </span>
                                    @endif

                                    {{-- Floating Icon --}}
                                    <div class="service-floating-icon">
                                        <i class="bi {{ $service->icon ?: 'bi-box-seam' }}"></i>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="service-card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h3 class="fs-5 mb-0">
                                            <a href="{{ route('services.show', $service->slug) }}" class="service-title" title="{{ $service->title }}">
                                                {{ $service->title }}
                                            </a>
                                        </h3>
                                        @if($service->show_price)
                                            @if($service->pricing_type === 'fixed' && $service->price)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                    ৳{{ number_format($service->price) }}
                                                </span>
                                            @elseif($service->pricing_type === 'range' && $service->price_range)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                    {{ $service->price_range }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                                    আলোচনা সাপেক্ষে
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <p class="service-short-desc">
                                        {{ $service->short_description ?? Str::limit(strip_tags($service->description), 110) }}
                                    </p>

                                    {{-- Footer CTAs --}}
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                                        <a href="{{ route('services.show', $service->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            বিস্তারিত <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                        <a href="{{ route('bookings.create') }}"
                                           class="btn btn-sm btn-warning rounded-pill px-3 fw-bold text-dark" style="background-color: #f59e0b; border: none;">
                                            <i class="bi bi-calendar-check me-1"></i> বুকিং দিন
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-light border mb-0 text-center py-4">বর্তমানে কোনো সার্ভিস পাওয়া যায়নি।</div>
            @endif
        </div>
    </section>

    {{-- 4. ABOUT US SECTION --}}
    <section class="section-modern section-modern-light py-5" id="about-section" style="background-color: #f8fafc;">
        @php
            $img1 = !empty($about?->image1)
                ? asset('storage/' . $about->image1)
                : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80';
            $img2 = !empty($about?->image2)
                ? asset('storage/' . $about->image2)
                : 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1200&q=80';

            $defaultValues = ['নিরাপত্তা ও যত্ন (Safety & Care)', 'অভিজ্ঞ ও দক্ষ টিম (Trained Movers)', 'স্বচ্ছ ও ন্যায্য মূল্য (Transparent Pricing)', 'অন-টাইম ডেলিভারি (On-time Service)'];
            $keyValues = collect($about?->key_values ?? [])->filter()->values();
            if ($keyValues->isEmpty()) {
                $keyValues = collect($defaultValues);
            }

            $splitAt = (int) ceil($keyValues->count() / 2);
            $leftValues = $keyValues->slice(0, $splitAt);
            $rightValues = $keyValues->slice($splitAt);
        @endphp

        <div class="container">
            <div class="row gx-5 align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
                    <div class="position-relative">
                        <img src="{{ $img1 }}" alt="Sohoz Shifting about" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 440px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 m-3 p-3 text-white rounded-3 shadow-lg" style="background: rgba(15, 23, 42, 0.95); min-width: 190px;">
                            <div class="fs-2 fw-bold text-warning">{{ $about?->years_experience ?? 8 }} +</div>
                            <div class="small">বছরের অভিজ্ঞতা ও সেবা</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="300">
                    <div class="bg-white p-4 p-lg-5 rounded-4 shadow-sm border">
                        <span class="badge bg-warning-subtle text-dark px-3 py-2 rounded-pill fw-bold mb-2">আমাদের সম্পর্কে</span>
                        <h2 class="display-6 fw-bold text-dark mb-3">{{ $about?->title ?? 'সহজ শিফটিং এ আপনাকে স্বাগতম' }}</h2>
                        <div class="text-secondary mb-4 leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
                            {!! $about?->page_details ?? '<p>সহজ শিফটিং ঢাকা মহানগরী সহ সারাদেশে একটি নির্ভরযোগ্য বাসা ও অফিস শিফটিং সেবাদানকারী প্রতিষ্ঠান। আমাদের রয়েছে প্রশিক্ষিত কর্মী বাহিনী, আধুনিক কাভার্ড ভ্যান ও উন্নত প্যাকিং সামগ্রী।</p>' !!}
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <h5 class="fw-bold text-dark mb-2"><i class="bi bi-star-fill text-warning me-1"></i> আমাদের অঙ্গীকার</h5>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <ul class="list-unstyled mb-0 small text-secondary">
                                        @foreach ($leftValues as $value)
                                            <li class="py-1"><i class="bi bi-check-circle-fill text-success me-2"></i> {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled mb-0 small text-secondary">
                                        @foreach ($rightValues as $value)
                                            <li class="py-1"><i class="bi bi-check-circle-fill text-success me-2"></i> {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. WHY CHOOSE US SECTION --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7" data-aos="fade-right">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">কেন আমরা সেরা</span>
                    <h2 class="display-6 fw-bold text-dark mb-3">কেন সহজ শিফটিং বেছে নেবেন?</h2>
                    <p class="text-secondary leading-relaxed mb-4">
                        বাসা বদলানো একটি কষ্টসাধ্য কাজ। কিন্তু অভিজ্ঞ টিম ও সঠিক পরিকল্পনার মাধ্যমে আমরা আপনার শিফটিংকে করি সম্পূর্ণ চাপমুক্ত, সহজ এবং নিরাপদ।
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle p-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                    <i class="bi bi-shield-check fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">১০০% নিরাপদ হ্যান্ডলিং</h6>
                                    <p class="small text-muted mb-0">জিরো ড্যামেজ পলিসি অনুযায়ী প্রতিটি মালামাল যত্নসহকারে বহন।</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">অন-টাইম উপস্থিতি</h6>
                                    <p class="small text-muted mb-0">নির্ধারিত সময়ে কাভার্ড ভ্যান ও লেবার টিম উপস্থিতির নিশ্চয়তা।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="zoom-in">
                    <div class="card p-4 rounded-4 shadow-sm border bg-light">
                        <h5 class="fw-bold text-dark mb-3">আমাদের বিশেষ সুবিধাসমূহ</h5>
                        <ul class="list-unstyled mb-0 text-secondary">
                            <li class="py-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> প্রশিক্ষিত ও ভদ্র লেবার ফোর্স
                            </li>
                            <li class="py-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> উন্নত বাবল র‍্যাপ ও প্রিমিয়াম বক্স প্যাকিং
                            </li>
                            <li class="py-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> এসি ও ইলেকট্রিক্যাল ফিটিং সাপোর্ট
                            </li>
                            <li class="py-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> ফার্নিচার খোলা ও নতুন বাসায় পুনরায় সেটআপ
                            </li>
                            <li class="pt-2 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> স্বচ্ছ রেট, কোনো প্রকার হিডেন চার্জ নেই
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. COUNTER STATS SECTION --}}
    @if ($activeCounters->isNotEmpty())
        <section class="py-5 counter-section" style="background: #f8fafc;" data-aos="fade-up">
            <div class="container">
                <div class="text-center mb-4" data-aos="fade-up">
                    <span class="badge bg-warning-subtle text-dark px-3 py-2 rounded-pill fw-bold mb-2">আমাদের সাফল্য</span>
                    <h2 class="display-6 fw-bold text-dark mb-1">হাজারো পরিবারের বিশ্বাস ও নির্ভরতা</h2>
                </div>

                <div class="row g-4 justify-content-center">
                    @foreach ($activeCounters as $counter)
                        <div class="col-12 col-sm-6 col-lg-3" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="counter-card">
                                <div class="counter-icon">
                                    <i class="bi {{ $counter->icon ?: 'bi-truck' }}" style="font-size: 2rem;"></i>
                                </div>
                                <div class="counter-number" data-counter-value="{{ $counter->value }}">0+</div>
                                <p class="counter-title">{{ $counter->title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 7. TESTIMONIALS SECTION --}}
    @if ($testimonialSlides->isNotEmpty())
        <section class="py-5 bg-white">
            <div class="container">
                <div class="text-center mb-4" data-aos="fade-up">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">গ্রাহকের মতামত</span>
                    <h2 class="display-6 fw-bold text-dark mb-1">আমাদের সেবা নিয়ে গ্রাহকরা যা বলছেন</h2>
                </div>

                <div id="testimonialCarousel" class="carousel slide section-carousel" data-bs-ride="carousel" data-bs-interval="6000">
                    <div class="carousel-indicators">
                        @foreach ($testimonialSlides as $index => $testimonialChunk)
                            <button type="button" data-bs-target="#testimonialCarousel"
                                data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Testimonial slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner pb-4">
                        @foreach ($testimonialSlides as $slideIndex => $testimonialChunk)
                            <div class="carousel-item {{ $slideIndex === 0 ? 'active' : '' }}">
                                <div class="row g-4 justify-content-center">
                                    @foreach ($testimonialChunk as $testimonial)
                                        <div class="col-md-6 col-lg-4 d-flex" data-aos="fade-up">
                                            <div class="testimonial-card w-100">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    @if ($testimonial->image)
                                                        <img src="{{ asset('storage/' . $testimonial->image) }}"
                                                            alt="{{ $testimonial->name }}" class="testimonial-avatar">
                                                    @else
                                                        <div class="testimonial-avatar d-flex align-items-center justify-content-center text-secondary">
                                                            <i class="bi bi-person-circle fs-2"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h5 class="fw-bold mb-0 text-dark">{{ $testimonial->name }}</h5>
                                                        <p class="small text-muted mb-0">{{ $testimonial->designation }}</p>
                                                    </div>
                                                </div>

                                                <div class="text-warning mb-3">
                                                    @for ($star = 1; $star <= 5; $star++)
                                                        <i class="bi {{ $star <= $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                    @endfor
                                                </div>

                                                <p class="text-secondary small mb-0 leading-relaxed">{{ $testimonial->message }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 8. FAQS & WHATSAPP BOTTOM BANNER --}}
    @if ($faqItems->isNotEmpty())
        <section class="py-5" style="background: #f8fafc;">
            <div class="container">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="display-6 fw-bold text-dark mb-2">সাধারণ জিজ্ঞাসা (FAQs)</h2>
                    <p class="text-muted fs-6">শিফটিং বুকিংয়ের আগে প্রয়োজনীয় কিছু গুরুত্বপূর্ণ তথ্য।</p>
                </div>

                <div class="card p-3 p-lg-4 shadow-sm rounded-4 border bg-white" data-aos="fade-up" data-aos-delay="100">
                    <div class="accordion faq-accordion" id="homepageFaqAccordion">
                        @foreach ($faqItems as $faq)
                            <div class="accordion-item border-0 mb-3 rounded-3 shadow-none bg-light overflow-hidden">
                                <h2 class="accordion-header" id="faqHeading{{ $faq->id }}">
                                    <button class="accordion-button fw-bold text-dark {{ $loop->first ? '' : 'collapsed' }} bg-light" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $faq->id }}"
                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-controls="faqCollapse{{ $faq->id }}">
                                        <i class="bi bi-patch-question-fill text-warning me-2"></i>
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="faqCollapse{{ $faq->id }}"
                                    class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                    aria-labelledby="faqHeading{{ $faq->id }}"
                                    data-bs-parent="#homepageFaqAccordion">
                                    <div class="accordion-body bg-white text-muted pt-3 border-top">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- BOTTOM CONVERSION BANNER (MATCHING CLIENT IMAGE 1) --}}
        <section class="py-4" style="background-color: #f59e0b;">
            <div class="container">
                <div class="row align-items-center justify-content-between g-3">
                    <div class="col-md-7 text-center text-md-start">
                        <h3 class="fw-bold mb-1" style="color: #0f172a;">বাসা বদলানোর তারিখ ঠিক করেছেন?</h3>
                        <p class="mb-0 text-dark opacity-90">আমাদের দক্ষ টিম প্রস্তুত রয়েছে আপনার মালামাল নিরাপদে পৌঁছে দেওয়ার জন্য।</p>
                    </div>
                    <div class="col-md-5 text-center text-md-end">
                        <div class="d-flex justify-content-center justify-content-md-end gap-2">
                            <a href="https://wa.me/8801700000000?text={{ rawurlencode('আসসালামু আলাইকুম, আমি বাসা বদলানোর জন্য শিফটিং সার্ভিস নিতে চাই।') }}" target="_blank" 
                                class="btn btn-success btn-lg px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center" style="background-color: #10b981; border: none; border-radius: 30px;">
                                <i class="bi bi-whatsapp me-2"></i> WhatsApp
                            </a>
                            <a href="{{ route('bookings.create') }}" class="btn btn-dark btn-lg px-4 py-2 fw-bold shadow-sm" style="border-radius: 30px;">
                                বুকিং দিন
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 9. CORPORATE CLIENTS / PARTNERS INFINITY MARQUEE --}}
    @php
        $partnerList = $partnerItems;
        if ($partnerList->isEmpty()) {
            $partnerList = collect([
                (object)['name' => 'BRAC Logistics', 'image' => null, 'fallback_icon' => 'bi-building', 'fallback_color' => '#f59e0b'],
                (object)['name' => 'Grameenphone Housing', 'image' => null, 'fallback_icon' => 'bi-houses', 'fallback_color' => '#0284c7'],
                (object)['name' => 'Square Relocations', 'image' => null, 'fallback_icon' => 'bi-briefcase', 'fallback_color' => '#10b981'],
                (object)['name' => 'Walton Commercial', 'image' => null, 'fallback_icon' => 'bi-truck', 'fallback_color' => '#ef4444'],
                (object)['name' => 'Apex Footwear Shifting', 'image' => null, 'fallback_icon' => 'bi-box-seam', 'fallback_color' => '#8b5cf6'],
                (object)['name' => 'PRAN-RFL Moving', 'image' => null, 'fallback_icon' => 'bi-shield-check', 'fallback_color' => '#f97316'],
            ]);
        }

        if ($partnerList->isNotEmpty() && $partnerList->count() < 12) {
            $multiplied = collect();
            while ($multiplied->count() < 12) {
                $multiplied = $multiplied->concat($partnerList);
            }
            $partnerList = $multiplied;
        }
    @endphp

    <section class="py-4 partners-infinity-section position-relative overflow-hidden">
        <div class="container mb-3 text-center" data-aos="fade-up">
            <h6 class="text-muted fw-bold mb-0">আমাদের কর্পোরেট ক্লায়েন্ট ও পার্টনারসমূহ</h6>
        </div>

        <div class="partner-marquee-wrapper">
            <div class="partner-marquee-track">
                <div class="partner-marquee-group">
                    @foreach ($partnerList as $partner)
                        <div class="partner-marquee-item">
                            <div class="partner-marquee-card">
                                <div class="d-flex align-items-center fw-bold">
                                    <i class="bi {{ $partner->fallback_icon ?? 'bi-building' }} me-2" style="color: {{ $partner->fallback_color ?? '#f59e0b' }};"></i>
                                    <span>{{ $partner->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="partner-marquee-group" aria-hidden="true">
                    @foreach ($partnerList as $partner)
                        <div class="partner-marquee-item">
                            <div class="partner-marquee-card">
                                <div class="d-flex align-items-center fw-bold">
                                    <i class="bi {{ $partner->fallback_icon ?? 'bi-building' }} me-2" style="color: {{ $partner->fallback_color ?? '#f59e0b' }};"></i>
                                    <span>{{ $partner->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Dynamic Admin CTA Section --}}
    @if(!empty($setting?->cta_title))
        <section class="py-5 bg-white">
            <div class="container" data-aos="fade-up">
                <div class="card p-4 p-lg-5 text-center border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
                    <h3 class="fw-bold mb-2 text-white">{{ $setting->cta_title }}</h3>
                    <p class="text-light opacity-75 mb-4">{{ $setting->cta_text ?? 'আমাদের সাথে যোগাযোগ করুন এবং আপনার শিফটিং বুক করুন।' }}</p>
                    <div>
                        <a href="{{ !empty($setting->cta_button_link) ? $setting->cta_button_link : route('bookings.create') }}" class="btn btn-warning px-4 py-2 fw-bold text-dark rounded-pill shadow" style="background-color: #f59e0b; border: none;">
                            {{ !empty($setting->cta_button_text) ? $setting->cta_button_text : 'অনলাইন বুকিং দিন' }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @push('scripts')
        <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
        <script>
            // Reset scroll position on refresh
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }

            // Swiper Slider
            (function() {
                const initHeroSwiper = function() {
                    const swiperEl = document.querySelector('.hero-swiper');
                    if (!swiperEl) return;

                    const progressFill = document.querySelector('.hero-autoplay-progress-fill');
                    const AUTOPLAY_TIME = 5000;

                    new Swiper('.hero-swiper', {
                        loop: true,
                        speed: 850,
                        grabCursor: true,
                        allowTouchMove: true,
                        autoplay: {
                            delay: AUTOPLAY_TIME,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        },
                        navigation: {
                            nextEl: '.hero-swiper-next',
                            prevEl: '.hero-swiper-prev',
                        },
                        pagination: {
                            el: '.hero-swiper-pagination',
                            clickable: true,
                        },
                        on: {
                            slideChangeTransitionStart() {
                                const activeSlide = this.slides[this.activeIndex];
                                if (activeSlide) {
                                    const bg = activeSlide.querySelector('.hero-slide-bg');
                                    if (bg) {
                                        bg.style.animation = 'none';
                                        bg.offsetHeight;
                                        bg.style.animation = '';
                                    }
                                }
                            },
                            autoplayTimeLeft(s, time, progress) {
                                if (progressFill) {
                                    progressFill.style.width = Math.min(100, Math.max(0, (1 - progress) * 100)) + '%';
                                }
                            },
                            slideChange() {
                                if (progressFill) {
                                    progressFill.style.width = '0%';
                                }
                            }
                        }
                    });
                };

                if (typeof Swiper !== 'undefined') {
                    initHeroSwiper();
                } else {
                    window.addEventListener('load', initHeroSwiper);
                }
            })();

            // Counter animation
            (function() {
                const counterSection = document.querySelector('.counter-section');
                if (!counterSection) return;

                const counterElements = counterSection.querySelectorAll('[data-counter-value]');
                const animateCounter = (element) => {
                    if (element.dataset.counted === 'true') return;
                    const endValue = parseInt(element.dataset.counterValue || '0', 10);
                    const duration = 1500;
                    const startTime = performance.now();

                    const step = (now) => {
                        const progress = Math.min((now - startTime) / duration, 1);
                        const currentValue = Math.floor(progress * endValue);
                        element.textContent = `${currentValue.toLocaleString()}+`;
                        if (progress < 1) {
                            requestAnimationFrame(step);
                        } else {
                            element.textContent = `${endValue.toLocaleString()}+`;
                            element.dataset.counted = 'true';
                        }
                    };
                    requestAnimationFrame(step);
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            counterElements.forEach(animateCounter);
                            observer.disconnect();
                        }
                    });
                }, { threshold: 0.35 });

                observer.observe(counterSection);
            })();

            // Homepage Interactive Live Booking Cost Estimator
            document.addEventListener('DOMContentLoaded', function () {
                const propertyTypeSelect = document.getElementById('homePropertyTypeSelect');
                const customPropertyContainer = document.getElementById('homeCustomPropertyContainer');
                const customPropertyInput = document.getElementById('homeCustomPropertyInput');
                const pickupFloor = document.getElementById('homePickupFloor');
                const pickupLift = document.getElementById('homePickupLift');
                const deliveryFloor = document.getElementById('homeDeliveryFloor');
                const deliveryLift = document.getElementById('homeDeliveryLift');
                const vehicleType = document.getElementById('homeVehicleType');
                const labourCount = document.getElementById('homeLabourCount');
                const addonCheckboxes = document.querySelectorAll('.home-addon-checkbox');

                const estBaseRate = document.getElementById('homeEstBaseRate');
                const estFloorRate = document.getElementById('homeEstFloorRate');
                const estVehicleRate = document.getElementById('homeEstVehicleRate');
                const estLabourRate = document.getElementById('homeEstLabourRate');
                const estAddonRate = document.getElementById('homeEstAddonRate');
                const estTotalCost = document.getElementById('homeEstTotalCost');

                function toggleCustomProperty() {
                    if (propertyTypeSelect && propertyTypeSelect.value === 'custom') {
                        customPropertyContainer.style.display = 'block';
                        customPropertyInput.setAttribute('required', 'required');
                        customPropertyInput.focus();
                    } else if (customPropertyContainer) {
                        customPropertyContainer.style.display = 'none';
                        customPropertyInput.removeAttribute('required');
                    }
                }

                function calculateHomeEstimate() {
                    if (!propertyTypeSelect) return;

                    let baseRate = 0;
                    if (propertyTypeSelect.selectedIndex >= 0) {
                        const selectedOption = propertyTypeSelect.options[propertyTypeSelect.selectedIndex];
                        if (selectedOption && selectedOption.value && selectedOption.value !== '' && selectedOption.dataset.price) {
                            baseRate = parseFloat(selectedOption.dataset.price) || 0;
                        }
                    }

                    // Floor penalty: 300 BDT per floor above 1st if no lift - only if property is selected
                    let floorCost = 0;
                    if (baseRate > 0 || (propertyTypeSelect && propertyTypeSelect.value === 'custom')) {
                        const pFloor = parseInt(pickupFloor ? pickupFloor.value : 1) || 1;
                        const dFloor = parseInt(deliveryFloor ? deliveryFloor.value : 1) || 1;
                        if (pickupLift && !pickupLift.checked && pFloor > 1) {
                            floorCost += (pFloor - 1) * 300;
                        }
                        if (deliveryLift && !deliveryLift.checked && dFloor > 1) {
                            floorCost += (dFloor - 1) * 300;
                        }
                    }

                    // Vehicle cost (0 if unselected)
                    let vCost = 0;
                    if (vehicleType && vehicleType.selectedIndex >= 0) {
                        const vOption = vehicleType.options[vehicleType.selectedIndex];
                        if (vOption && vOption.value) {
                            vCost = parseFloat(vOption.dataset.price || 0);
                        }
                    }

                    // Labour cost (800 BDT per mover, 0 if 0)
                    const lCount = parseInt(labourCount ? labourCount.value : 0) || 0;
                    const lCost = lCount * 800;

                    // Addon services
                    let addonCost = 0;
                    addonCheckboxes.forEach(cb => {
                        if (cb.checked) {
                            addonCost += parseFloat(cb.dataset.price || 0);
                        }
                    });

                    const total = baseRate + floorCost + vCost + lCost + addonCost;

                    function formatBnMoney(amount) {
                        return '৳' + Number(amount).toLocaleString('bn-BD');
                    }

                    if (estBaseRate) estBaseRate.textContent = formatBnMoney(baseRate);
                    if (estFloorRate) estFloorRate.textContent = formatBnMoney(floorCost);
                    if (estVehicleRate) estVehicleRate.textContent = formatBnMoney(vCost);
                    if (estLabourRate) estLabourRate.textContent = formatBnMoney(lCost);
                    if (estAddonRate) estAddonRate.textContent = formatBnMoney(addonCost);
                    if (estTotalCost) estTotalCost.textContent = formatBnMoney(total);

                    const estNote = document.getElementById('homeEstNote');
                    if (estNote) {
                        if (total > 0) {
                            estNote.textContent = '*ফোন কলের মাধ্যমে চূড়ান্ত খরচ নিশ্চিত করা হবে';
                        } else {
                            estNote.textContent = '*সার্ভিস নির্বাচন করলে স্বয়ংক্রিয়ভাবে খরচ হিসেব হবে';
                        }
                    }
                }

                if (propertyTypeSelect) {
                    propertyTypeSelect.addEventListener('change', function () {
                        toggleCustomProperty();
                        calculateHomeEstimate();
                    });
                }

                [pickupFloor, pickupLift, deliveryFloor, deliveryLift, vehicleType, labourCount].forEach(el => {
                    if (el) {
                        el.addEventListener('input', calculateHomeEstimate);
                        el.addEventListener('change', calculateHomeEstimate);
                    }
                });

                addonCheckboxes.forEach(cb => {
                    cb.addEventListener('change', calculateHomeEstimate);
                });

                toggleCustomProperty();
                calculateHomeEstimate();
            });
        </script>
    @endpush
@endsection
