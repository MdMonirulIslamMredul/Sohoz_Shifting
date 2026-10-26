@extends('frontend.layouts.app')

@section('title', $service->title . ' - Services & Solutions')
@section('meta_description', Str::limit(strip_tags($service->short_description ?? $service->description), 155))

@push('styles')
<style>
    .service-detail-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
    }
    .hero-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        backdrop-filter: blur(8px);
    }
    .service-detail-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .service-main-img-box {
        position: relative;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        overflow: hidden;
        min-height: 340px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
    }
    .service-main-img-box img {
        width: 100%;
        max-height: 440px;
        object-fit: cover;
    }
    .deliverable-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem;
        height: 100%;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .deliverable-card:hover {
        transform: translateY(-3px);
        border-color: #0284c7;
        background: #ffffff;
    }
    .deliverable-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(2, 132, 199, 0.12);
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        margin-bottom: 0.75rem;
    }
    .sidebar-widget {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .other-service-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        border-radius: 10px;
        color: #334155;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 600;
        transition: all 0.15s ease;
        border: 1px solid transparent;
        margin-bottom: 4px;
    }
    .other-service-link:hover {
        background: #f0f9ff;
        color: #0284c7;
        border-color: #bae6fd;
        padding-left: 18px;
    }
    .other-service-link.active {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
    }
    .other-service-link.active i {
        color: #ffffff !important;
    }
    .fact-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }
    .fact-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')

    {{-- HERO / BREADCRUMB BANNER --}}
    <section class="service-detail-hero py-4">
        <div class="container py-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-white-50 text-decoration-none">Services</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ Str::limit($service->title, 40) }}</li>
                </ol>
            </nav>
            <div class="hero-badge-pill mb-2">
                <i class="bi bi-tools text-info"></i>
                <span>Technical Service</span>
            </div>
            <h1 class="display-6 fw-bold mb-1">{{ $service->title }}</h1>
            @if($service->short_description)
                <p class="lead text-white-50 mb-0" style="font-size: 1.05rem; max-width: 780px;">{{ $service->short_description }}</p>
            @endif
        </div>
    </section>

    {{-- MAIN CONTENT & SIDEBAR --}}
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4">

                {{-- Left Column: Main Service Detail --}}
                <div class="col-lg-8">
                    <div class="service-detail-card p-4 p-lg-5 mb-4">

                        {{-- Service Image --}}
                        <div class="service-main-img-box mb-4">
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="img-fluid rounded-3">
                            @else
                                <div class="text-center p-5 text-muted">
                                    <i class="bi bi-gear-wide-connected" style="font-size: 4.5rem; color: #0284c7;"></i>
                                    <h5 class="mt-3 text-dark fw-bold">{{ $service->title }}</h5>
                                    <p class="small text-muted mb-0">Professional Engineering Service</p>
                                </div>
                            @endif

                            @if($service->is_featured)
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill shadow-sm fw-bold">
                                    <i class="bi bi-star-fill me-1"></i> Featured Service
                                </span>
                            @endif
                        </div>

                        {{-- Overview & Description --}}
                        <h3 class="fw-bold text-dark mb-3">Service Scope &amp; Overview</h3>
                        <div class="text-secondary lh-lg fs-6 mb-5">
                            {!! nl2br(e($service->description ?? $service->short_description)) !!}
                        </div>

                        {{-- Key Deliverables / What's Included --}}
                        <h4 class="fw-bold text-dark mb-3">What This Service Includes</h4>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <div class="deliverable-card">
                                    <div class="deliverable-icon"><i class="bi bi-clipboard2-check-fill"></i></div>
                                    <h5 class="fs-6 fw-bold text-dark mb-1">On-Site Feasibility &amp; Assessment</h5>
                                    <p class="small text-muted mb-0">Comprehensive technical inspection, load analysis, and site safety audit before deployment.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="deliverable-card">
                                    <div class="deliverable-icon"><i class="bi bi-diagram-3-fill"></i></div>
                                    <h5 class="fs-6 fw-bold text-dark mb-1">Custom Engineering Blueprint</h5>
                                    <p class="small text-muted mb-0">Tailored schematic design using compliant industrial standards and optimized equipment.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="deliverable-card">
                                    <div class="deliverable-icon"><i class="bi bi-tools"></i></div>
                                    <h5 class="fs-6 fw-bold text-dark mb-1">Certified Professional Installation</h5>
                                    <p class="small text-muted mb-0">Direct execution by qualified engineers following strict safety and quality standards.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="deliverable-card">
                                    <div class="deliverable-icon"><i class="bi bi-shield-check"></i></div>
                                    <h5 class="fs-6 fw-bold text-dark mb-1">Testing, Handover &amp; Warranty</h5>
                                    <p class="small text-muted mb-0">Full commissioning verification, detailed documentation, and dedicated after-service support.</p>
                                </div>
                            </div>
                        </div>

                        {{-- FAQ / Key Inquiries --}}
                        <h4 class="fw-bold text-dark mb-3">Frequently Asked Questions</h4>
                        <div class="accordion" id="serviceFaqAccordion">
                            <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        How soon can this service be scheduled?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#serviceFaqAccordion">
                                    <div class="accordion-body text-secondary small">
                                        Our engineering coordinators typically conduct initial consultations within 24 to 48 hours of your inquiry. Urgent site visits can be arranged depending on your location.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Do you provide a formal technical proposal and estimate?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#serviceFaqAccordion">
                                    <div class="accordion-body text-secondary small">
                                        Yes. Following the initial consultation and assessment, we deliver an itemized quotation outlining labor, equipment, timelines, and warranty terms.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Right Column: Sidebar --}}
                <div class="col-lg-4">

                    {{-- 1. Consultation & Quote Card --}}
                    <div class="sidebar-widget p-4 text-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; border-radius: 18px;">
                        <div class="d-inline-flex p-3 rounded-circle mb-3 fs-3" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b;">
                            <i class="bi bi-calendar2-check-fill"></i>
                        </div>
                        <h4 class="fw-bold text-white fs-5 mb-2">এই সার্ভিসটি বুকিং করুন</h4>
                        <p class="small text-white-50 mb-4">
                            মাত্র ২ মিনিটে প্রয়োজনীয় তথ্য দিয়ে সরাসরি শিফটিং বুকিং দিন ও তাৎক্ষণিক ফ্রি কোটেশন পান।
                        </p>
                        <a href="{{ route('bookings.create') }}"
                           class="btn btn-warning rounded-pill w-100 py-2 fw-bold text-dark shadow" style="background-color: #f59e0b; border: none;">
                            অনলাইন বুকিং দিন <i class="bi bi-arrow-right ms-1"></i>
                        </a>

                        <div class="mt-3 pt-3 border-top border-white border-opacity-15 small">
                            <span class="text-white-50">সরাসরি ফোন বা হোয়াটসঅ্যাপ করুন:</span>
                            <a href="tel:{{ $settings->contact_phone ?? '01700000000' }}" class="text-white fw-bold text-decoration-none d-block mt-1">
                                <i class="bi bi-telephone-fill me-1 text-warning"></i> {{ $settings->contact_phone ?? '01700000000' }}
                            </a>
                        </div>
                    </div>

                    {{-- 2. Service Facts Card --}}
                    <div class="sidebar-widget">
                        <h5 class="fw-bold text-dark fs-6 mb-3 border-bottom pb-2">
                            <i class="bi bi-info-circle me-1 text-primary"></i> Service Information
                        </h5>
                        <div class="fact-item">
                            <span class="text-muted">Service Type</span>
                            <span class="fw-semibold text-dark">Engineering &amp; Technical</span>
                        </div>
                        <div class="fact-item">
                            <span class="text-muted">Delivery Mode</span>
                            <span class="fw-semibold text-dark">On-Site &amp; Field Support</span>
                        </div>
                        <div class="fact-item">
                            <span class="text-muted">Coverage Area</span>
                            <span class="fw-semibold text-dark">Nationwide</span>
                        </div>
                        <div class="fact-item">
                            <span class="text-muted">Service Status</span>
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Available</span>
                        </div>
                        <div class="fact-item">
                            <span class="text-muted">Warranty</span>
                            <span class="fw-semibold text-dark">Workmanship Guaranteed</span>
                        </div>
                    </div>

                    {{-- 3. All Other Services Navigation --}}
                    @if(isset($otherServices) && $otherServices->count() > 0)
                        <div class="sidebar-widget">
                            <h5 class="fw-bold text-dark fs-6 mb-3 border-bottom pb-2">
                                <i class="bi bi-grid me-1 text-primary"></i> Other Services
                            </h5>
                            <div class="other-services-list">
                                @foreach($otherServices as $other)
                                    <a href="{{ route('services.show', $other->slug) }}" class="other-service-link">
                                        <span>{{ $other->title }}</span>
                                        <i class="bi bi-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mt-3 pt-2 text-center border-top">
                                <a href="{{ route('services.index') }}" class="small fw-bold text-primary text-decoration-none">
                                    View All Services <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection
