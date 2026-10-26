@extends('frontend.layouts.app')

@section('title', 'Our Partners & Global Alliances')
@section('meta_description', 'Discover the trusted technology vendors, renewable energy innovators, and industrial partners collaborating with ' . ($setting->site_name ?? 'SolarTech'))

@push('styles')
<style>
    /* Hero Header */
    .partner-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .partner-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(56, 189, 248, 0.18) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(14, 165, 233, 0.15) 0%, transparent 45%);
        pointer-events: none;
    }

    .partner-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.84rem;
        font-weight: 600;
        backdrop-filter: blur(8px);
    }

    /* Trust / Highlights Ribbon */
    .partner-stats-ribbon {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    }
    .stat-box {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 1rem 0.5rem;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        line-height: 1.2;
    }
    .stat-desc {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0;
        line-height: 1.3;
    }

    /* Partner Card */
    .partner-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        padding: 1.75rem 1.5rem;
        position: relative;
    }
    .partner-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.09);
        border-color: #0284c7;
    }

    .partner-logo-box {
        height: 110px;
        background: #f8fafc;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
        border: 1px solid #f1f5f9;
        transition: background 0.2s ease, border-color 0.2s ease;
    }
    .partner-card:hover .partner-logo-box {
        background: #ffffff;
        border-color: #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }
    .partner-logo-img {
        max-width: 90%;
        max-height: 76px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .partner-card:hover .partner-logo-img {
        transform: scale(1.06);
    }

    .partner-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    .partner-desc {
        font-size: 0.88rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        flex-grow: 1;
    }

    .partner-action-link {
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #0284c7;
        text-decoration: none;
        transition: color 0.2s ease, gap 0.2s ease;
    }
    .partner-action-link:hover {
        color: #0369a1;
        gap: 9px;
    }

    /* CTA Banner */
    .partner-cta-banner {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 60%, #0284c7 100%);
        border-radius: 24px;
        color: #ffffff;
        padding: 3.5rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px rgba(2, 6, 23, 0.15);
    }

    /* Dark Mode Adjustments */
    html[data-theme='dark'] .partner-stats-ribbon {
        background: #0f172a;
        border-color: #1e293b;
    }
    html[data-theme='dark'] .stat-box .stat-title {
        color: #f8fafc;
    }
    html[data-theme='dark'] .stat-box .stat-desc {
        color: #94a3b8;
    }
    html[data-theme='dark'] .stat-icon {
        background: rgba(2, 132, 199, 0.2);
        color: #38bdf8;
    }
    html[data-theme='dark'] .partner-card {
        background: #0f172a;
        border-color: #1e293b;
    }
    html[data-theme='dark'] .partner-logo-box {
        background: #1e293b;
        border-color: #334155;
    }
    html[data-theme='dark'] .partner-card:hover .partner-logo-box {
        background: #1e293b;
    }
    html[data-theme='dark'] .partner-name {
        color: #f8fafc;
    }
    html[data-theme='dark'] .partner-desc {
        color: #94a3b8;
    }
</style>
@endpush

@section('content')

    {{-- HERO SECTION --}}
    <section class="partner-hero py-5">
        <div class="container py-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3 small">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" class="text-white text-opacity-75 text-decoration-none">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Our Partners</li>
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <span class="partner-badge-pill">
                            <i class="bi bi-shield-check text-info"></i> Trusted Global Ecosystem
                        </span>
                    </div>
                    <h1 class="display-5 fw-bold text-white mb-3">
                        Strategic Partners & Technical Collaborators
                    </h1>
                    <p class="lead text-white text-opacity-90 mb-0" style="max-width: 680px;">
                        We partner with tier-one solar manufacturers, industrial innovators, and world-class technology suppliers to deliver certified, high-yield renewable infrastructure.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('contact.index') }}" class="btn btn-warning btn-lg px-4 fw-semibold shadow-sm">
                        <i class="bi bi-handshake me-1"></i> Become a Partner
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS / HIGHLIGHTS RIBBON --}}
    <section class="partner-stats-ribbon py-2">
        <div class="container">
            <div class="row g-3 py-2">
                <div class="col-6 col-lg-3">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <div class="stat-title">Tier-1 Equipment</div>
                            <p class="stat-desc">Certified international hardware standards</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div>
                            <div class="stat-title">Cutting-Edge Tech</div>
                            <p class="stat-desc">Advanced inverters, cells & storage</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <div class="stat-title">Long-Term Warranty</div>
                            <p class="stat-desc">Direct manufacturer-backed guarantees</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-globe-americas"></i>
                        </div>
                        <div>
                            <div class="stat-title">Global Network</div>
                            <p class="stat-desc">Cross-border distribution and engineering</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN PARTNERS DIRECTORY --}}
    <section class="py-5" style="background-color: var(--bs-body-bg, #f8fafc);">
        <div class="container">

            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold text-uppercase mb-2">
                    Collaborative Network
                </span>
                <h2 class="display-6 fw-bold">Built Alongside Industry Leaders</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">
                    Our partnerships allow us to provide sustainable, highly efficient solar solutions backed by world-class technical engineering.
                </p>
            </div>

            @if ($partners->isNotEmpty())
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach ($partners as $partner)
                        <div class="col" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 80 }}">
                            <div class="partner-card">
                                <div class="partner-logo-box">
                                    <img src="{{ asset('storage/' . $partner->image) }}"
                                        alt="{{ $partner->name }}"
                                        class="partner-logo-img"
                                        loading="lazy">
                                </div>

                                <h3 class="partner-name">{{ $partner->name }}</h3>

                                <p class="partner-desc">
                                    @if ($partner->description)
                                        {{ $partner->description }}
                                    @else
                                        Official certified vendor and technology partner providing enterprise solar engineering solutions and equipment.
                                    @endif
                                </p>

                                @if ($partner->website_url)
                                    <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer"
                                        class="partner-action-link" title="Visit {{ $partner->name }} website">
                                        <span>Visit Website</span>
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                @else
                                    <div class="partner-action-link text-muted" style="cursor: default;">
                                        <i class="bi bi-check2-circle text-success"></i>
                                        <span>Certified Partner</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card border-0 rounded-4 shadow-sm p-5 text-center my-4">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle"
                            style="width: 72px; height: 72px; font-size: 2rem;">
                            <i class="bi bi-handshake"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold mb-2">Our Partner Network Is Expanding</h4>
                    <p class="text-muted mb-4" style="max-width: 480px; margin: 0 auto;">
                        We are currently updating our partner roster. Check back shortly to view our featured global suppliers and technical allies.
                    </p>
                    <div>
                        <a href="{{ route('contact.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-chat-dots me-1"></i> Partner With Us
                        </a>
                    </div>
                </div>
            @endif

            {{-- PARTNERSHIP INQUIRY CTA --}}
            <div class="partner-cta-banner mt-5 text-center" data-aos="fade-up">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold text-uppercase mb-3 shadow-sm">
                            <i class="bi bi-building-check me-1"></i> Enterprise Alliances
                        </span>
                        <h2 class="display-6 fw-bold mb-3 text-white">
                            Looking to Partner with {{ $setting->site_name ?? 'SolarTech' }}?
                        </h2>
                        <p class="text-white text-opacity-90 mb-4 fs-6">
                            We actively collaborate with suppliers, utility operators, EPC contractors, and innovative clean-energy startups across the globe.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('contact.index') }}" class="btn btn-light text-primary fw-bold rounded-pill px-4 py-2 shadow-sm">
                                <i class="bi bi-envelope-fill me-1"></i> Submit Partnership Proposal
                            </a>
                            <a href="{{ route('about') }}" class="btn btn-outline-light rounded-pill px-4 py-2">
                                <i class="bi bi-arrow-right-circle me-1"></i> Learn More About Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
