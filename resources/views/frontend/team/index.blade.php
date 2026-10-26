@extends('frontend.layouts.app')

@section('title', 'Our Team & Engineering Experts')
@section('meta_description', 'Meet our certified solar engineers, technical specialists, and visionary leaders at Enerix Solutions dedicated to clean energy and industrial excellence.')

@push('styles')
<style>
    /* Hero Header */
    .team-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .team-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(56, 189, 248, 0.18) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(14, 165, 233, 0.15) 0%, transparent 45%);
        pointer-events: none;
    }

    .hero-badge-pill {
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

    .hero-search-wrapper {
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.28);
        border-radius: 16px;
        padding: 8px;
        backdrop-filter: blur(8px);
    }
    .hero-search-wrapper .form-control {
        color: #ffffff !important;
    }
    .hero-search-wrapper .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    /* Trust / Highlights Ribbon */
    .team-stats-ribbon {
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

    /* Filter Pills */
    .team-filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .team-filter-pill:hover {
        background: #f1f5f9;
        color: #0284c7;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
    .team-filter-pill.active {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
    }

    /* Applied filter chip */
    .applied-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .applied-chip a {
        color: #0284c7;
        text-decoration: none;
        font-weight: 800;
        font-size: 0.9rem;
    }
    .applied-chip a:hover {
        color: #ef4444;
    }

    /* Team Member Card */
    .team-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        position: relative;
    }
    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
        border-color: #cbd5e1;
    }

    /* Card Pattern Header Bar */
    .team-card-banner {
        height: 100px;
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 60%, #0284c7 100%);
        position: relative;
        overflow: hidden;
    }
    .team-card-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.3) 0%, transparent 60%);
    }

    /* Avatar Container */
    .team-avatar-wrapper {
        width: 126px;
        height: 126px;
        border-radius: 50%;
        margin: -63px auto 1rem;
        position: relative;
        z-index: 2;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        background: #f8fafc;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease, border-color 0.3s ease;
    }
    .team-card:hover .team-avatar-wrapper {
        transform: scale(1.05);
        border-color: #bae6fd;
    }
    .team-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Fallback Initials Avatar */
    .team-avatar-initials {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0284c7 0%, #1e3a8a 100%);
        color: #ffffff;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 1px;
        user-select: none;
        position: relative;
    }
    .team-avatar-initials::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    }

    /* Card Details */
    .team-card-body {
        padding: 0 1.6rem 1.6rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .team-member-name {
        font-size: 1.22rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.35rem;
        line-height: 1.3;
    }
    .team-designation-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 0.9rem;
        align-self: center;
    }
    .team-member-bio {
        font-size: 0.86rem;
        color: #64748b;
        line-height: 1.55;
        margin-bottom: 1.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    /* Social Action Icons */
    .team-social-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
    }
    .team-social-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #475569;
        font-size: 0.95rem;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    .team-social-btn:hover {
        transform: translateY(-2px);
    }
    .team-social-btn.btn-linkedin:hover {
        background: #0077b5;
        color: #ffffff;
        border-color: #0077b5;
        box-shadow: 0 4px 10px rgba(0, 119, 181, 0.3);
    }
    .team-social-btn.btn-twitter:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.3);
    }
    .team-social-btn.btn-facebook:hover {
        background: #1877f2;
        color: #ffffff;
        border-color: #1877f2;
        box-shadow: 0 4px 10px rgba(24, 119, 242, 0.3);
    }

    /* Join Us Banner */
    .join-team-banner {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 60%, #0284c7 100%);
        border-radius: 24px;
        color: #ffffff;
        padding: 3.5rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px rgba(2, 6, 23, 0.15);
    }
    .join-team-banner::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.2) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Dark Mode Adjustments */
    html[data-theme='dark'] .team-stats-ribbon {
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
    html[data-theme='dark'] .team-card {
        background: #0f172a;
        border-color: #1e293b;
    }
    html[data-theme='dark'] .team-avatar-wrapper {
        border-color: #0f172a;
        background: #1e293b;
    }
    html[data-theme='dark'] .team-card:hover .team-avatar-wrapper {
        border-color: #0284c7;
    }
    html[data-theme='dark'] .team-member-name {
        color: #f8fafc;
    }
    html[data-theme='dark'] .team-designation-pill {
        background: rgba(2, 132, 199, 0.2);
        color: #38bdf8;
    }
    html[data-theme='dark'] .team-member-bio {
        color: #94a3b8;
    }
    html[data-theme='dark'] .team-social-bar {
        border-color: #1e293b;
    }
    html[data-theme='dark'] .team-social-btn {
        background: #1e293b;
        color: #cbd5e1;
        border-color: #334155;
    }
    html[data-theme='dark'] .team-filter-pill {
        background: #0f172a;
        border-color: #1e293b;
        color: #cbd5e1;
    }
    html[data-theme='dark'] .team-filter-pill:hover {
        background: #1e293b;
        color: #38bdf8;
    }
</style>
@endpush

@section('content')

    {{-- HERO SECTION --}}
    <section class="team-hero py-5">
        <div class="container py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3 small">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" class="text-white text-opacity-75 text-decoration-none">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Our Team</li>
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="mb-3">
                        <span class="hero-badge-pill">
                            <i class="bi bi-people-fill text-info"></i> Leadership & Technical Experts
                        </span>
                    </div>
                    <h1 class="display-5 fw-bold text-white mb-3">
                        Meet the Minds Driving Engineering Excellence
                    </h1>
                    <p class="lead text-white text-opacity-90 mb-0" style="max-width: 620px;">
                        Our multidisciplinary team of licensed engineers, renewable energy architects, and technical consultants work collaboratively to power industrial innovation and sustainable growth.
                    </p>
                </div>

                {{-- Quick Live Search Form --}}
                <div class="col-lg-5">
                    <div class="hero-search-wrapper">
                        <form action="{{ route('team.index') }}" method="GET">
                            @if(request('designation'))
                                <input type="hidden" name="designation" value="{{ request('designation') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-white ps-3">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control bg-transparent border-0 shadow-none"
                                    placeholder="Search by name, role, or specialty..." value="{{ $search ?? '' }}"
                                    aria-label="Search team">
                                @if(!empty($search))
                                    <a href="{{ route('team.index', array_filter(['designation' => $selectedDesignation, 'sort' => request('sort')])) }}"
                                        class="btn btn-sm btn-link text-white text-decoration-none d-flex align-items-center px-2"
                                        title="Clear search">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </a>
                                @endif
                                <button class="btn btn-primary rounded-pill px-4 fw-semibold" type="submit">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS / HIGHLIGHTS RIBBON --}}
    <section class="team-stats-ribbon py-2">
        <div class="container">
            <div class="row g-3 py-2">
                <div class="col-6 col-lg-3">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <div class="stat-title">Certified Experts</div>
                            <p class="stat-desc">Licensed engineers & certified technicians</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div>
                            <div class="stat-title">Solar & Automation</div>
                            <p class="stat-desc">End-to-end sustainable systems</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <div class="stat-title">Proven Execution</div>
                            <p class="stat-desc">Quality-assured turnkey deliveries</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-box">
                        <div class="stat-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div>
                            <div class="stat-title">24/7 Client Care</div>
                            <p class="stat-desc">Dedicated engineering consultations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN TEAM DIRECTORY SECTION --}}
    <section class="py-5" style="background-color: var(--bs-body-bg, #f8fafc);">
        <div class="container">

            {{-- Filter & Sorting Header --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">

                {{-- Left: Designation Filter Pills --}}
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('team.index', array_filter(['search' => $search, 'sort' => request('sort')])) }}"
                        class="team-filter-pill {{ empty($selectedDesignation) ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i> All Members
                        <span class="badge rounded-pill {{ empty($selectedDesignation) ? 'bg-white text-primary' : 'bg-light text-muted' }}">
                            {{ $totalCount ?? $teamMembers->total() }}
                        </span>
                    </a>

                    @if(isset($designations) && $designations->count() > 0)
                        @foreach($designations as $designation)
                            <a href="{{ route('team.index', array_filter(['designation' => $designation, 'search' => $search, 'sort' => request('sort')])) }}"
                                class="team-filter-pill {{ ($selectedDesignation ?? '') === $designation ? 'active' : '' }}">
                                {{ $designation }}
                            </a>
                        @endforeach
                    @endif
                </div>

                {{-- Right: Sort Dropdown & Count --}}
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div class="small text-muted d-none d-sm-block">
                        Showing <strong>{{ $teamMembers->count() }}</strong> of <strong>{{ $teamMembers->total() }}</strong> specialists
                    </div>

                    <form action="{{ route('team.index') }}" method="GET" class="d-inline-flex align-items-center gap-2">
                        @if(!empty($search))
                            <input type="hidden" name="search" value="{{ $search }}">
                        @endif
                        @if(!empty($selectedDesignation))
                            <input type="hidden" name="designation" value="{{ $selectedDesignation }}">
                        @endif
                        <select name="sort" class="form-select form-select-sm rounded-pill border-secondary-subtle" onchange="this.form.submit()">
                            <option value="order" {{ ($sort ?? '') === 'order' ? 'selected' : '' }}>Default Order</option>
                            <option value="name_asc" {{ ($sort ?? '') === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="name_desc" {{ ($sort ?? '') === 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                            <option value="newest" {{ ($sort ?? '') === 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Active Filter Chips (if search or designation applied) --}}
            @if(!empty($search) || !empty($selectedDesignation))
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4 p-3 bg-white rounded-4 border border-light-subtle shadow-sm">
                    <span class="small fw-bold text-muted text-uppercase me-2">
                        <i class="bi bi-funnel-fill text-primary"></i> Active Filters:
                    </span>

                    @if(!empty($search))
                        <span class="applied-chip">
                            <i class="bi bi-search"></i> "{{ $search }}"
                            <a href="{{ route('team.index', array_filter(['designation' => $selectedDesignation, 'sort' => request('sort')])) }}" title="Remove keyword">&times;</a>
                        </span>
                    @endif

                    @if(!empty($selectedDesignation))
                        <span class="applied-chip">
                            <i class="bi bi-person-badge"></i> {{ $selectedDesignation }}
                            <a href="{{ route('team.index', array_filter(['search' => $search, 'sort' => request('sort')])) }}" title="Remove role">&times;</a>
                        </span>
                    @endif

                    <a href="{{ route('team.index') }}" class="small text-danger text-decoration-none fw-semibold ms-2">
                        Clear all filters
                    </a>
                </div>
            @endif

            {{-- Team Members Grid --}}
            @if($teamMembers->count() > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                    @foreach($teamMembers as $member)
                        @php
                            // Extract initials for executive fallback
                            $words = preg_split('/\s+/', trim($member->name));
                            $initials = '';
                            foreach (array_slice($words, 0, 2) as $w) {
                                if (!empty($w)) {
                                    $initials .= strtoupper($w[0]);
                                }
                            }
                            if (empty($initials)) {
                                $initials = 'TM';
                            }
                        @endphp

                        <div class="col" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 80 }}">
                            <div class="team-card">
                                {{-- Card Banner --}}
                                <div class="team-card-banner"></div>

                                {{-- Avatar Frame --}}
                                <div class="team-avatar-wrapper">
                                    @if ($member->photo)
                                        <img src="{{ asset('storage/' . $member->photo) }}"
                                            class="team-avatar-img"
                                            alt="{{ $member->name }}"
                                            loading="lazy">
                                    @else
                                        <div class="team-avatar-initials" title="{{ $member->name }}">
                                            <span>{{ $initials }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Body --}}
                                <div class="team-card-body">
                                    <h3 class="team-member-name">{{ $member->name }}</h3>

                                    <div class="team-designation-pill">
                                        <i class="bi bi-patch-check-fill"></i>
                                        <span>{{ $member->designation }}</span>
                                    </div>

                                    <p class="team-member-bio">
                                        @if(!empty($member->bio))
                                            {{ $member->bio }}
                                        @else
                                            Dedicated engineering professional committed to excellence, innovative design, and dependable client satisfaction.
                                        @endif
                                    </p>

                                    {{-- Social Media Icons --}}
                                    <div class="team-social-bar">
                                        @if(!empty($member->linkedin_url))
                                            <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener noreferrer"
                                                class="team-social-btn btn-linkedin" title="LinkedIn Profile">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        @endif

                                        @if(!empty($member->twitter_url))
                                            <a href="{{ $member->twitter_url }}" target="_blank" rel="noopener noreferrer"
                                                class="team-social-btn btn-twitter" title="Twitter / X Profile">
                                                <i class="bi bi-twitter-x"></i>
                                            </a>
                                        @endif

                                        @if(!empty($member->facebook_url))
                                            <a href="{{ $member->facebook_url }}" target="_blank" rel="noopener noreferrer"
                                                class="team-social-btn btn-facebook" title="Facebook Profile">
                                                <i class="bi bi-facebook"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('contact.index') }}" class="team-social-btn" title="Contact via Enerix">
                                            <i class="bi bi-envelope-fill text-primary"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination Links --}}
                @if($teamMembers->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $teamMembers->links() }}
                    </div>
                @endif

            @else
                {{-- Empty Search / Filter State --}}
                <div class="card border-0 rounded-4 shadow-sm p-5 text-center my-4">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 72px; height: 72px; font-size: 2rem;">
                            <i class="bi bi-people"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold mb-2">No Team Members Found</h4>
                    <p class="text-muted mb-4" style="max-width: 480px; margin: 0 auto;">
                        We couldn't find any team members matching your current filters or search query. Try searching with different keywords.
                    </p>
                    <div>
                        <a href="{{ route('team.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> View All Team Members
                        </a>
                    </div>
                </div>
            @endif

            {{-- JOIN OUR TEAM CTA BANNER --}}
            <div class="join-team-banner mt-5 text-center" data-aos="fade-up">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold text-uppercase mb-3 shadow-sm">
                            <i class="bi bi-briefcase-fill me-1"></i> Careers & Collaboration
                        </span>
                        <h2 class="display-6 fw-bold mb-3 text-white">
                            Want to Work with Our Industry Specialists?
                        </h2>
                        <p class="text-white text-opacity-90 mb-4 fs-6">
                            Whether you are looking to collaborate on a utility-scale solar project, automate industrial workflows, or explore engineering career opportunities, our experts are here to help.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('contact.index') }}" class="btn btn-light text-primary fw-bold rounded-pill px-4 py-2 shadow-sm">
                                <i class="bi bi-chat-dots-fill me-1"></i> Get in Touch With Us
                            </a>
                            <a href="{{ route('services.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2">
                                <i class="bi bi-gear-wide-connected me-1"></i> Explore Our Solutions
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
