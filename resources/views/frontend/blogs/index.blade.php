@extends('frontend.layouts.app')

@section('title', 'Blog & Industry Insights')
@section('meta_description', 'Read the latest technical articles, industrial news, engineering guides, and expert perspectives.')

@push('styles')
<style>
    /* Hero Banner */
    .blog-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .blog-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(56, 189, 248, 0.18) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(14, 165, 233, 0.15) 0%, transparent 45%);
        pointer-events: none;
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

    /* Filter Pills Bar */
    .archive-pills-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 6px;
        scrollbar-width: thin;
    }
    .archive-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
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
    .archive-pill:hover {
        background: #f1f5f9;
        color: #0284c7;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
    .archive-pill.active {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
    }
    .archive-pill .pill-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #64748b;
    }
    .archive-pill.active .pill-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Applied Filters Chips */
    .applied-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        margin-right: 6px;
        margin-bottom: 6px;
    }
    .applied-chip a {
        color: #0284c7;
        text-decoration: none;
        font-weight: 800;
        font-size: 0.85rem;
    }
    .applied-chip a:hover {
        color: #ef4444;
    }

    /* Blog Card */
    .blog-card {
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
    .blog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    .blog-media {
        position: relative;
        height: 230px;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        overflow: hidden;
    }
    .blog-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }
    .blog-card:hover .blog-media img {
        transform: scale(1.06);
    }
    .blog-media-placeholder {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    }

    /* Prominent Floating Calendar Date Badge */
    .blog-calendar-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.15);
        text-align: center;
        min-width: 52px;
        z-index: 3;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .blog-cal-month {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 3px 6px;
        line-height: 1.1;
    }
    .blog-cal-day {
        color: #0f172a;
        font-size: 1.35rem;
        font-weight: 900;
        line-height: 1.1;
        padding: 4px 6px 1px;
    }
    .blog-cal-year {
        color: #64748b;
        font-size: 0.65rem;
        font-weight: 700;
        padding-bottom: 3px;
        line-height: 1;
    }

    /* Read time badge */
    .blog-read-time {
        position: absolute;
        top: 14px;
        right: 14px;
        background: rgba(15, 23, 42, 0.78);
        backdrop-filter: blur(8px);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 50px;
        z-index: 3;
    }

    .blog-card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .blog-meta-row {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .blog-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        margin-bottom: 0.6rem;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.15s ease;
    }
    .blog-title:hover {
        color: #0284c7;
    }
    .blog-excerpt {
        color: #64748b;
        font-size: 0.88rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    /* Sidebar Widget */
    .sidebar-widget {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.02);
    }
    .sidebar-widget-title {
        font-size: 0.95rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0f172a;
        margin-bottom: 1.1rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .recent-post-item {
        display: flex;
        gap: 12px;
        padding: 8px 0;
        border-bottom: 1px solid #f8fafc;
        text-decoration: none;
    }
    .recent-post-item:last-child {
        border-bottom: none;
    }
    .recent-post-thumb {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        overflow: hidden;
        background: #f1f5f9;
        flex-shrink: 0;
    }
    .recent-post-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .recent-post-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.15s ease;
    }
    .recent-post-item:hover .recent-post-title {
        color: #0284c7;
    }
    .recent-post-date {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 3px;
    }

    /* Archive Link */
    .archive-list-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border-radius: 8px;
        color: #475569;
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 500;
        transition: all 0.15s ease;
        margin-bottom: 2px;
    }
    .archive-list-link:hover {
        background: #f1f5f9;
        color: #0284c7;
        padding-left: 16px;
    }
    .archive-list-link.active {
        background: rgba(2, 132, 199, 0.1);
        color: #0284c7;
        font-weight: 700;
    }
</style>
@endpush

@section('content')

    {{-- HERO HEADER --}}
    <section class="blog-hero py-5">
        <div class="container py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Blog &amp; Insights</li>
                    @if($selectedYear && $selectedMonth)
                        <li class="breadcrumb-item text-white-50">
                            {{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}
                        </li>
                    @elseif($selectedYear)
                        <li class="breadcrumb-item text-white-50">Year {{ $selectedYear }}</li>
                    @endif
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3"
                         style="background: rgba(255, 255, 255, 0.12); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25); font-size: 0.84rem; font-weight: 600;">
                        <i class="bi bi-journal-richtext text-info"></i>
                        <span>Engineering Insights &amp; Knowledge</span>
                    </div>
                    <h1 class="display-6 fw-bold mb-2">Articles, Technical Guides &amp; Updates</h1>
                    <p class="lead text-white-50 mb-0" style="max-width: 650px; font-size: 1.05rem;">
                        Discover in-depth engineering analyses, renewable energy innovations, industry best practices, and project milestones written by our specialist team.
                    </p>
                </div>
                <div class="col-lg-5">
                    {{-- Quick Search Form --}}
                    <form action="{{ route('blogs.index') }}" method="GET" class="hero-search-wrapper shadow-sm">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-white-50 ps-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control bg-transparent border-0 shadow-none"
                                   placeholder="Search articles by title, topic, keyword..."
                                   value="{{ $search }}">
                            @if($selectedYear)
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                            @endif
                            @if($selectedMonth)
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                            @endif
                            <button type="submit" class="btn btn-light rounded-pill px-4 fw-semibold text-primary">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- INTERACTIVE DATE ARCHIVES PILL FILTER BAR --}}
    <section class="py-3 bg-white border-bottom shadow-sm">
        <div class="container">
            <div class="archive-pills-bar">
                {{-- All Posts --}}
                <a href="{{ route('blogs.index', request()->except(['year', 'month', 'page'])) }}"
                   class="archive-pill {{ !$selectedYear && !$selectedMonth ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>All Articles</span>
                    <span class="pill-badge">{{ $totalCount }}</span>
                </a>

                {{-- Each Monthly Archive Pill --}}
                @foreach($archives as $arc)
                    @php
                        $isArcActive = ($selectedYear == $arc->year && $selectedMonth == $arc->month);
                        $monthName = DateTime::createFromFormat('!m', $arc->month)->format('M');
                    @endphp
                    <a href="{{ route('blogs.index', array_merge(request()->except(['page']), ['year' => $arc->year, 'month' => $arc->month])) }}"
                       class="archive-pill {{ $isArcActive ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        <span>{{ $monthName }} {{ $arc->year }}</span>
                        <span class="pill-badge">{{ $arc->count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MAIN BLOG CONTENT SECTION --}}
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4">

                {{-- MAIN COLUMN: ARTICLES GRID (COL-LG-8) --}}
                <div class="col-lg-8">

                    {{-- Top Controls Bar --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 border">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark fs-6">
                                {{ $blogs->total() }} <span class="fw-normal text-muted">Articles Found</span>
                            </span>
                            @if($selectedYear && $selectedMonth)
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                                    {{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}
                                </span>
                            @endif
                        </div>

                        {{-- Sort Dropdown --}}
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <form action="{{ route('blogs.index') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
                                @foreach(request()->except(['sort', 'page']) as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach

                                <label for="sortSelect" class="small fw-semibold text-muted text-nowrap d-none d-sm-inline">
                                    Sort:
                                </label>
                                <select name="sort" id="sortSelect" class="form-select form-select-sm rounded-pill fw-semibold border-secondary-subtle px-3"
                                        onchange="this.form.submit()">
                                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Title: A to Z</option>
                                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Title: Z to A</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    {{-- Applied Filters Chips --}}
                    @if($activeFiltersCount > 0)
                        <div class="mb-4 d-flex flex-wrap align-items-center">
                            <span class="small text-muted fw-bold me-2">Applied Filters:</span>
                            @if($search !== '')
                                <span class="applied-chip">
                                    Search: "{{ Str::limit($search, 14) }}"
                                    <a href="{{ request()->fullUrlWithQuery(['q' => null, 'search' => null]) }}">&times;</a>
                                </span>
                            @endif
                            @if($selectedYear && $selectedMonth)
                                <span class="applied-chip">
                                    Archive: {{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}
                                    <a href="{{ request()->fullUrlWithQuery(['year' => null, 'month' => null]) }}">&times;</a>
                                </span>
                            @elseif($selectedYear)
                                <span class="applied-chip">
                                    Year: {{ $selectedYear }}
                                    <a href="{{ request()->fullUrlWithQuery(['year' => null]) }}">&times;</a>
                                </span>
                            @endif
                            <a href="{{ route('blogs.index') }}" class="small text-danger fw-semibold ms-2 text-decoration-none">
                                Reset All
                            </a>
                        </div>
                    @endif

                    {{-- Articles Grid (2-column layout in col-lg-8) --}}
                    @if($blogs->count() > 0)
                        <div class="row g-4">
                            @foreach($blogs as $blog)
                                @php
                                    $postDate = $blog->published_at ?? $blog->created_at;
                                    $readMinutes = max(1, round(str_word_count(strip_tags($blog->body ?? $blog->excerpt ?? '')) / 180));
                                @endphp
                                <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 2) * 100 }}">
                                    <article class="blog-card">
                                        {{-- Media Banner --}}
                                        <div class="blog-media">
                                            {{-- Prominent Floating Calendar Date Badge --}}
                                            @if($postDate)
                                                <div class="blog-calendar-badge" title="Published {{ $postDate->format('M d, Y') }}">
                                                    <div class="blog-cal-month">{{ $postDate->format('M') }}</div>
                                                    <div class="blog-cal-day">{{ $postDate->format('d') }}</div>
                                                    <div class="blog-cal-year">{{ $postDate->format('Y') }}</div>
                                                </div>
                                            @endif

                                            {{-- Read Time Pill --}}
                                            <span class="blog-read-time">
                                                <i class="bi bi-clock me-1"></i> {{ $readMinutes }} min read
                                            </span>

                                            @if($blog->image)
                                                <a href="{{ route('blogs.show', $blog->slug) }}">
                                                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
                                                </a>
                                            @else
                                                <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none">
                                                    <div class="blog-media-placeholder">
                                                        <i class="bi bi-newspaper" style="font-size: 3.5rem;"></i>
                                                        <span class="small mt-2 text-muted fw-semibold">Engineering Article</span>
                                                    </div>
                                                </a>
                                            @endif
                                        </div>

                                        {{-- Body --}}
                                        <div class="blog-card-body">
                                            {{-- Inline Post Date & Author Meta --}}
                                            <div class="blog-meta-row">
                                                <span>
                                                    <i class="bi bi-calendar3 text-primary me-1"></i>
                                                    <strong>{{ $postDate ? $postDate->format('F d, Y') : 'Recent' }}</strong>
                                                </span>
                                                <span>&bull;</span>
                                                <span>
                                                    <i class="bi bi-person text-secondary me-1"></i> Technical Team
                                                </span>
                                            </div>

                                            <h2 class="fs-5 mb-2">
                                                <a href="{{ route('blogs.show', $blog->slug) }}" class="blog-title" title="{{ $blog->title }}">
                                                    {{ $blog->title }}
                                                </a>
                                            </h2>

                                            <p class="blog-excerpt">
                                                {{ $blog->excerpt ?? Str::limit(strip_tags($blog->body), 110) }}
                                            </p>

                                            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                                                <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                                    Read Article <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                                <span class="text-muted small">
                                                    <i class="bi bi-book me-1"></i> Full Guide
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-5 d-flex justify-content-center">
                            {{ $blogs->links() }}
                        </div>

                    @else
                        {{-- Empty State --}}
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                            <div class="py-4">
                                <div class="d-inline-flex p-4 rounded-circle bg-light text-primary mb-3">
                                    <i class="bi bi-journal-x fs-1"></i>
                                </div>
                                <h4 class="fw-bold text-dark">No Articles Found</h4>
                                <p class="text-muted mx-auto" style="max-width: 480px;">
                                    We couldn't find any blog posts matching your search keywords or selected date archive.
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('blogs.index') }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
                                        <i class="bi bi-arrow-clockwise me-1"></i> View All Articles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- SIDEBAR COLUMN (COL-LG-4) --}}
                <aside class="col-lg-4">

                    {{-- 1. Search Box Widget --}}
                    <div class="sidebar-widget">
                        <div class="sidebar-widget-title">
                            <i class="bi bi-search text-primary"></i> Search Articles
                        </div>
                        <form action="{{ route('blogs.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control rounded-start-pill ps-3"
                                       placeholder="Keywords..." value="{{ $search }}">
                                <button type="submit" class="btn btn-primary rounded-end-pill px-3">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- 2. Recent Posts Widget with Post Dates --}}
                    @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                        <div class="sidebar-widget">
                            <div class="sidebar-widget-title">
                                <i class="bi bi-clock-history text-primary"></i> Recent Articles
                            </div>
                            <div class="recent-posts-list">
                                @foreach($recentBlogs as $recent)
                                    @php
                                        $rDate = $recent->published_at ?? $recent->created_at;
                                    @endphp
                                    <a href="{{ route('blogs.show', $recent->slug) }}" class="recent-post-item">
                                        <div class="recent-post-thumb">
                                            @if($recent->image)
                                                <img src="{{ asset('storage/' . $recent->image) }}" alt="{{ $recent->title }}">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-light">
                                                    <i class="bi bi-file-earmark-text fs-4"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="recent-post-title">{{ $recent->title }}</div>
                                            <div class="recent-post-date">
                                                <i class="bi bi-calendar3 me-1 text-primary"></i>
                                                {{ $rDate ? $rDate->format('M d, Y') : '' }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 3. Monthly Archives Widget --}}
                    @if(isset($archives) && $archives->count() > 0)
                        <div class="sidebar-widget">
                            <div class="sidebar-widget-title">
                                <i class="bi bi-archive text-primary"></i> Monthly Archives
                            </div>
                            <div class="archives-list">
                                @foreach($archives as $arc)
                                    @php
                                        $isArcActive = ($selectedYear == $arc->year && $selectedMonth == $arc->month);
                                        $mName = DateTime::createFromFormat('!m', $arc->month)->format('F');
                                    @endphp
                                    <a href="{{ route('blogs.index', ['year' => $arc->year, 'month' => $arc->month]) }}"
                                       class="archive-list-link {{ $isArcActive ? 'active' : '' }}">
                                        <span>
                                            <i class="bi bi-folder2-open me-2 text-secondary"></i>
                                            {{ $mName }} {{ $arc->year }}
                                        </span>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                            {{ $arc->count }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 4. Consultation / Direct Contact Box --}}
                    <div class="sidebar-widget p-4 text-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color: #ffffff;">
                        <div class="d-inline-flex p-3 rounded-circle mb-3 fs-3" style="background: rgba(255, 255, 255, 0.12); color: #38bdf8;">
                            <i class="bi bi-envelope-paper-heart"></i>
                        </div>
                        <h4 class="fw-bold text-white fs-5 mb-2">Have a Project in Mind?</h4>
                        <p class="small text-white-50 mb-3">
                            Connect with our engineering specialists to discuss custom solutions, industrial equipment, or turnkey installations.
                        </p>
                        <a href="{{ route('contact.index') }}" class="btn btn-primary rounded-pill w-100 py-2 fw-bold shadow">
                            Contact Us Today <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>

                </aside>

            </div>
        </div>
    </section>

@endsection
