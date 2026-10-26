@extends('frontend.layouts.app')

@section('title', 'Services & Solutions')
@section('meta_description', 'Discover our professional technical services, engineering solutions, installation, and ongoing maintenance services.')

@push('styles')
<style>
    /* Hero Banner */
    .services-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .services-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(56, 189, 248, 0.18) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(14, 165, 233, 0.15) 0%, transparent 45%);
        pointer-events: none;
    }

    /* Hero Badge & Search Box */
    .hero-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.84rem;
        font-weight: 600;
        backdrop-filter: blur(8px);
    }
    .hero-search-wrapper {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 16px;
        padding: 8px;
        backdrop-filter: blur(8px);
    }
    .hero-search-wrapper .form-control {
        color: #ffffff !important;
    }
    .hero-search-wrapper .form-control::placeholder {
        color: rgba(255, 255, 255, 0.65) !important;
    }

    /* Trust Highlights Ribbon */
    .trust-ribbon {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
    }
    .trust-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
    }
    .trust-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(2, 132, 199, 0.1);
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Service Card */
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

    /* Service checklist bullets */
    .service-perks {
        list-style: none;
        padding: 0;
        margin: 0 0 1.25rem;
    }
    .service-perks li {
        font-size: 0.82rem;
        color: #475569;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }
    .service-perks li i {
        color: #0284c7;
        font-size: 0.9rem;
    }

    /* Process Step Card */
    .process-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 2rem 1.5rem;
        height: 100%;
        position: relative;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .process-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        border-color: #0284c7;
    }
    .process-num {
        font-size: 2.2rem;
        font-weight: 900;
        color: #e2e8f0;
        line-height: 1;
        margin-bottom: 0.75rem;
        transition: color 0.25s ease;
    }
    .process-card:hover .process-num {
        color: #0284c7;
    }
    .process-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(2, 132, 199, 0.1);
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
    }

    /* CTA Section */
    .service-cta-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #0369a1 100%);
        border-radius: 24px;
        color: #ffffff;
        padding: 3.5rem 2.5rem;
        position: relative;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

    {{-- HERO BANNER --}}
    <section class="services-hero py-5">
        <div class="container py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">হোম</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">সেবাসমূহ</li>
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="hero-badge-pill mb-3">
                        <i class="bi bi-shield-check text-warning"></i>
                        <span>নিরাপদ ও নির্ভরযোগ্য শিফটিং সার্ভিস</span>
                    </div>
                    <h1 class="display-6 fw-bold mb-2">বাসা ও অফিস শিফটিং সেবাসমূহ</h1>
                    <p class="lead text-white-50 mb-0" style="max-width: 650px; font-size: 1.05rem;">
                        ঢাকা মহানগরী সহ সারাদেশে দ্রুত ও নিরাপদ বাসা-অফিস শিফটিং। দক্ষ লেবার, আধুনিক কাভার্ড ভ্যান, ফার্নিচার সেটআপ ও অভিজ্ঞ টেকনিশিয়ান সেবা।
                    </p>
                </div>
                <div class="col-lg-5">
                    {{-- Quick Search Form --}}
                    <form action="{{ route('services.index') }}" method="GET" class="hero-search-wrapper shadow-sm">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-white-50 ps-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control bg-transparent border-0 shadow-none text-white"
                                   placeholder="সার্ভিসের নাম লিখে খুঁজুন..."
                                   value="{{ $search }}">
                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold text-dark" style="background-color: #f59e0b; border: none;">
                                সার্চ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- TRUST HIGHLIGHTS RIBBON --}}
    <section class="trust-ribbon py-3">
        <div class="container">
            <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                <div class="col">
                    <div class="trust-item">
                        <div class="trust-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706;"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="fw-bold text-dark fs-6">প্রশিক্ষিত লেবার টিম</div>
                            <div class="small text-muted">অভিজ্ঞ ও ভদ্র কর্মী বাহিনী</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="trust-item">
                        <div class="trust-icon" style="background: rgba(15, 23, 42, 0.08); color: #0f172a;"><i class="bi bi-truck"></i></div>
                        <div>
                            <div class="fw-bold text-dark fs-6">আধুনিক কাভার্ড ভ্যান</div>
                            <div class="small text-muted">বৃষ্টি ও রোদমুক্ত পরিবহন</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="trust-item">
                        <div class="trust-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;"><i class="bi bi-headset"></i></div>
                        <div>
                            <div class="fw-bold text-dark fs-6">২৪/৭ কাস্টমার সাপোর্ট</div>
                            <div class="small text-muted">সার্বক্ষণিক সহায়তা ও তথ্য</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="trust-item">
                        <div class="trust-icon" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <div class="fw-bold text-dark fs-6">১০০% নিরাপদ ডেলিভারি</div>
                            <div class="small text-muted">জিরো-ড্যামেজ নিশ্চয়তা</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICES GRID SECTION --}}
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">

            {{-- Top Controls Bar --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('services.index') }}"
                       class="btn btn-sm {{ !request('featured') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 fw-semibold">
                        All Services ({{ $totalActiveCount }})
                    </a>
                    @if($featuredCount > 0)
                        <a href="{{ route('services.index', ['featured' => 1]) }}"
                           class="btn btn-sm {{ request('featured') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 fw-semibold">
                            <i class="bi bi-star-fill text-warning me-1"></i> Featured Only ({{ $featuredCount }})
                        </a>
                    @endif
                    @if($search !== '')
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill small">
                            Keyword: "{{ Str::limit($search, 16) }}"
                            <a href="{{ route('services.index') }}" class="text-info text-decoration-none ms-1">&times;</a>
                        </span>
                    @endif
                </div>

                <div class="text-muted small fw-semibold">
                    Showing {{ $services->count() }} of {{ $services->total() }} Services
                </div>
            </div>

            {{-- Grid --}}
            @if($services->count() > 0)
                <div class="row g-4">
                    @foreach($services as $service)
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
                                                    <i class="bi bi-tools" style="font-size: 3.5rem;"></i>
                                                    <span class="small mt-1 text-muted">Engineering Service</span>
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

                {{-- Pagination --}}
                <div class="mt-5 d-flex justify-content-center">
                    {{ $services->links() }}
                </div>

            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                    <div class="py-4">
                        <div class="d-inline-flex p-4 rounded-circle bg-light text-primary mb-3">
                            <i class="bi bi-search fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">No Services Found</h4>
                        <p class="text-muted mx-auto" style="max-width: 480px;">
                            We couldn't find any services matching your search criteria.
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('services.index') }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
                                View All Services
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

    {{-- OUR SERVICE METHODOLOGY (HOW WE WORK) --}}
    <section class="py-5 bg-white border-top border-bottom">
        <div class="container py-4">
            <div class="text-center mx-auto mb-5" style="max-width: 680px;" data-aos="fade-up">
                <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-2 fw-semibold mb-2">সহজ শিফটিং পদ্ধতি</span>
                <h2 class="display-6 fw-bold text-dark">আমাদের শিফটিং প্রক্রিয়া (৪টি ধাপ)</h2>
                <p class="text-muted">বুকিং থেকে শুরু করে নতুন বাসায় মালামাল সাজানো পর্যন্ত প্রতিটি ধাপ সুপরিকল্পিত ও সুশৃঙ্খল।</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-card">
                        <div class="process-num">০১</div>
                        <div class="process-icon"><i class="bi bi-calendar-check-fill text-warning"></i></div>
                        <h4 class="fs-5 fw-bold text-dark mb-2">অনলাইন বুকিং</h4>
                        <p class="text-muted small mb-0">বাসা ও মালামালের ধরন জানিয়ে ওয়েবসাইট বা ফোনে তাৎক্ষণিক ফ্রি কোটেশন ও বুকিং সম্পন্ন করুন।</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-card">
                        <div class="process-num">০২</div>
                        <div class="process-icon"><i class="bi bi-box-seam-fill text-primary"></i></div>
                        <h4 class="fs-5 fw-bold text-dark mb-2">প্যাকিং ও প্রিপারেশন</h4>
                        <p class="text-muted small mb-0">অভিজ্ঞ কর্মী দ্বারা বাবল র‍্যাপ, ফোম ও কার্টনে মালামাল যত্নসহকারে প্যাক করা হয়।</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-card">
                        <div class="process-num">০৩</div>
                        <div class="process-icon"><i class="bi bi-truck text-success"></i></div>
                        <h4 class="fs-5 fw-bold text-dark mb-2">নিরাপদ পরিবহন</h4>
                        <p class="text-muted small mb-0">পরিচ্ছন্ন ও আবহাওয়া-সুরক্ষিত কাভার্ড ভ্যানে সতর্কতার সাথে গন্তব্যে মালামাল পরিবহন।</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="process-card">
                        <div class="process-num">০৪</div>
                        <div class="process-icon"><i class="bi bi-house-door-fill text-info"></i></div>
                        <h4 class="fs-5 fw-bold text-dark mb-2">আনপ্যাকিং ও সেটআপ</h4>
                        <p class="text-muted small mb-0">নতুন বাসায় ফার্নিচার ও এসি পুনরায় ফিটিং করে আপনার নির্দেশমতো কক্ষ সাজিয়ে দেওয়া।</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA BANNER --}}
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="service-cta-banner p-4 p-lg-5 shadow-lg" data-aos="zoom-in" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #f59e0b 100%);">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <div class="hero-badge-pill mb-3">
                            <i class="bi bi-telephone-outbound text-warning"></i>
                            <span>জরুরি শিফটিং সেবা</span>
                        </div>
                        <h2 class="display-6 fw-bold mb-2 text-white">বাসা বা অফিস বদলানোর পরিকল্পনা করছেন?</h2>
                        <p class="lead text-white-50 mb-0" style="font-size: 1.05rem;">
                            আমাদের কাস্টমার কেয়ার প্রতিনিধির সাথে কথা বলে ফ্রি কোটেশন নিন এবং এখনই আপনার শিফটিং বুক করুন।
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('bookings.create') }}"
                           class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold text-dark shadow" style="background-color: #f59e0b; border: none;">
                            অনলাইন বুকিং দিন <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
