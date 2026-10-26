@extends('frontend.layouts.app')

@section('title', ($blog->meta_title ?: $blog->title) . ' - Blog')
@section('meta_description', $blog->meta_description ?: ($blog->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($blog->body), 155)))
@section('meta_keywords', $blog->seo_keywords ?: 'industrial engineering, solar energy, automation, technology')

@push('styles')
<style>
    /* Hero Header */
    .blog-detail-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .blog-detail-hero::before {
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
        gap: 6px;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff !important;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        backdrop-filter: blur(8px);
        transition: all 0.2s ease;
    }
    .hero-badge-pill:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .date-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #0f172a;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.84rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .date-badge-pill i {
        color: #0284c7;
        font-size: 0.95rem;
    }

    /* Featured Media */
    .blog-featured-media-wrapper {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        margin-bottom: 2rem;
        position: relative;
    }
    .blog-featured-media-wrapper img {
        width: 100%;
        max-height: 480px;
        object-fit: cover;
        display: block;
    }

    /* Floating Calendar Card on Featured Image */
    .featured-calendar-badge {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(10px);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.2);
        display: flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.6);
        z-index: 2;
    }
    .featured-cal-cube {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        padding: 10px 14px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1;
    }
    .featured-cal-cube .fc-month {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .featured-cal-cube .fc-day {
        font-size: 1.5rem;
        font-weight: 900;
        margin-top: 3px;
    }
    .featured-cal-info {
        padding: 8px 16px;
        font-size: 0.85rem;
        color: #334155;
    }
    .featured-cal-info strong {
        color: #0f172a;
        display: block;
    }

    /* Article Lead / Excerpt Callout */
    .article-lead-callout {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-left: 5px solid #0284c7;
        border-radius: 0 16px 16px 0;
        padding: 1.5rem 1.8rem;
        font-size: 1.12rem;
        line-height: 1.7;
        color: #0c4a6e;
        font-weight: 500;
        margin-bottom: 2rem;
        position: relative;
    }
    .article-lead-callout i.quote-icon {
        font-size: 1.8rem;
        color: #0284c7;
        opacity: 0.4;
        margin-bottom: 0.5rem;
        display: inline-block;
    }

    /* Article Card & Content Typography */
    .article-body-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .article-content {
        font-size: 1.05rem;
        line-height: 1.85;
        color: #334155;
    }
    .article-content p {
        margin-bottom: 1.5rem;
    }
    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4 {
        color: #0f172a;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        line-height: 1.35;
    }
    .article-content h2 {
        font-size: 1.6rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f1f5f9;
    }
    .article-content h3 {
        font-size: 1.35rem;
    }
    .article-content ul,
    .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.6rem;
    }
    .article-content li {
        margin-bottom: 0.5rem;
    }
    .article-content blockquote {
        margin: 1.8rem 0;
        padding: 1.2rem 1.6rem;
        background: #f8fafc;
        border-left: 4px solid #0284c7;
        border-radius: 0 12px 12px 0;
        font-style: italic;
        color: #475569;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 14px;
        margin: 1.5rem 0;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }
    .article-content table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }
    .article-content table th,
    .article-content table td {
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        text-align: left;
    }
    .article-content table th {
        background: #f8fafc;
        font-weight: 700;
        color: #0f172a;
    }

    /* Tags / Keywords */
    .article-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-right: 6px;
        margin-bottom: 6px;
    }
    .article-tag-pill:hover {
        background: #e0f2fe;
        color: #0284c7;
        transform: translateY(-1px);
    }

    /* Social Share Buttons */
    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        color: #ffffff;
        font-size: 0.95rem;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        color: #ffffff;
    }
    .share-linkedin { background: #0077b5; }
    .share-twitter { background: #1da1f2; }
    .share-facebook { background: #1877f2; }
    .share-whatsapp { background: #25d366; }
    .share-copy { background: #475569; }

    /* Next / Previous Article Navigation */
    .article-nav-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: all 0.2s ease;
        height: 100%;
    }
    .article-nav-card:hover {
        border-color: #0284c7;
        box-shadow: 0 8px 24px rgba(2, 132, 199, 0.08);
        transform: translateY(-2px);
    }
    .article-nav-direction {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #0284c7;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .article-nav-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .article-nav-date {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 6px;
    }

    /* Sidebar Widgets */
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

    /* Recent Post Items */
    .recent-post-item {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f8fafc;
        text-decoration: none;
    }
    .recent-post-item:last-child {
        border-bottom: none;
    }
    .recent-post-thumb {
        width: 65px;
        height: 65px;
        border-radius: 12px;
        overflow: hidden;
        background: #f1f5f9;
        flex-shrink: 0;
        position: relative;
    }
    .recent-post-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .recent-post-item:hover .recent-post-thumb img {
        transform: scale(1.08);
    }
    .recent-post-title {
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.35;
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
        font-size: 0.74rem;
        color: #64748b;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Archive Date Filter Links */
    .archive-list-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px;
        border-radius: 10px;
        color: #475569;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        transition: all 0.15s ease;
        margin-bottom: 4px;
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
    .archive-count-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #64748b;
    }
    .archive-list-link.active .archive-count-badge {
        background: #0284c7;
        color: #ffffff;
    }

    /* Consultation Sidebar CTA Box */
    .sidebar-cta-card {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%);
        color: #ffffff;
        border-radius: 18px;
        padding: 1.8rem;
        position: relative;
        overflow: hidden;
    }
    .sidebar-cta-card::after {
        content: '';
        position: absolute;
        top: -30%;
        right: -30%;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, transparent 70%);
        pointer-events: none;
    }

    @media (max-width: 767.98px) {
        .article-body-card {
            padding: 1.5rem;
        }
        .featured-calendar-badge {
            bottom: 12px;
            left: 12px;
        }
    }
</style>
@endpush

@section('content')

    {{-- ARTICLE HERO HEADER --}}
    <section class="blog-detail-hero py-5">
        <div class="container py-3">
            {{-- Breadcrumbs --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3 small">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" class="text-white text-opacity-75 text-decoration-none">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('blogs.index') }}" class="text-white text-opacity-75 text-decoration-none">
                            Blog & Insights
                        </a>
                    </li>
                    @if($blog->published_at)
                        <li class="breadcrumb-item">
                            <a href="{{ route('blogs.index', ['year' => $blog->published_at->year, 'month' => $blog->published_at->month]) }}" class="text-white text-opacity-75 text-decoration-none">
                                {{ $blog->published_at->format('F Y') }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active text-white" aria-current="page">
                        {{ \Illuminate\Support\Str::limit($blog->title, 35) }}
                    </li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-10">
                    {{-- Badges row --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="hero-badge-pill">
                            <i class="bi bi-journal-text text-info"></i> Industry Insight
                        </span>
                        @if($blog->published_at)
                            <a href="{{ route('blogs.index', ['year' => $blog->published_at->year, 'month' => $blog->published_at->month]) }}" class="hero-badge-pill" title="Filter by {{ $blog->published_at->format('F Y') }}">
                                <i class="bi bi-archive text-warning"></i> {{ $blog->published_at->format('F Y') }} Archive
                            </a>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h1 class="display-6 fw-bold text-white mb-4 lh-sm">
                        {{ $blog->title }}
                    </h1>

                    {{-- Prominent Meta Bar with Post Date --}}
                    <div class="d-flex flex-wrap align-items-center gap-3 pt-2">
                        @if ($blog->published_at)
                            <div class="date-badge-pill" title="Publication Date">
                                <i class="bi bi-calendar-check-fill"></i>
                                <span>Published: {{ $blog->published_at->format('M d, Y') }}</span>
                            </div>
                        @else
                            <div class="date-badge-pill">
                                <i class="bi bi-calendar-event"></i>
                                <span>Recently Published</span>
                            </div>
                        @endif

                        @php
                            $readingMinutes = max(1, (int) ceil(str_word_count(strip_tags($blog->body)) / 200));
                        @endphp
                        <span class="d-inline-flex align-items-center text-white text-opacity-80 small fw-semibold">
                            <i class="bi bi-clock me-1 text-info"></i> {{ $readingMinutes }} min read
                        </span>

                        <span class="d-inline-flex align-items-center text-white text-opacity-80 small fw-semibold">
                            <i class="bi bi-person-circle me-1 text-info"></i> Enerix Editorial Team
                        </span>

                        <a href="{{ route('blogs.index') }}" class="ms-auto btn btn-sm btn-outline-light rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-2">
                            <i class="bi bi-arrow-left"></i> All Articles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN ARTICLE & SIDEBAR SECTION --}}
    <section class="py-5" style="background-color: #f8fafc;">
        <div class="container">
            <div class="row g-4 g-lg-5">

                {{-- Left: Article Content --}}
                <div class="col-lg-8">

                    {{-- Featured Image with Date Badge Overlay --}}
                    @if ($blog->image)
                        <div class="blog-featured-media-wrapper">
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid">

                            @if($blog->published_at)
                                <div class="featured-calendar-badge d-none d-sm-flex">
                                    <div class="featured-cal-cube">
                                        <span class="fc-month">{{ $blog->published_at->format('M') }}</span>
                                        <span class="fc-day">{{ $blog->published_at->format('d') }}</span>
                                    </div>
                                    <div class="featured-cal-info">
                                        <strong>Posted on {{ $blog->published_at->format('F d, Y') }}</strong>
                                        <small class="text-muted">{{ $readingMinutes }} min read &bull; Editorial</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Article Excerpt Callout --}}
                    @if ($blog->excerpt)
                        <div class="article-lead-callout">
                            <i class="bi bi-quote quote-icon"></i>
                            <div>{{ $blog->excerpt }}</div>
                        </div>
                    @endif

                    {{-- Article Body Card --}}
                    <article class="article-body-card">
                        <div class="article-content">
                            {!! $blog->body !!}
                        </div>

                        {{-- Keywords / Tags Bar --}}
                        @if ($blog->seo_keywords)
                            @php
                                $keywords = array_filter(array_map('trim', explode(',', $blog->seo_keywords)));
                            @endphp
                            @if(count($keywords) > 0)
                                <div class="mt-4 pt-4 border-top">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="small fw-bold text-uppercase text-muted me-1">
                                            <i class="bi bi-tags-fill me-1 text-primary"></i> Topics:
                                        </span>
                                        @foreach($keywords as $keyword)
                                            <a href="{{ route('blogs.index', ['search' => $keyword]) }}" class="article-tag-pill">
                                                #{{ $keyword }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Share & Meta Footer --}}
                        <div class="mt-4 pt-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="small fw-bold text-dark text-uppercase">Share Article:</span>
                                @php
                                    $currentUrl = urlencode(request()->fullUrl());
                                    $encodedTitle = urlencode($blog->title);
                                @endphp
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $currentUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-linkedin" title="Share on LinkedIn">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text={{ $encodedTitle }}&url={{ $currentUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-twitter" title="Share on X (Twitter)">
                                    <i class="bi bi-twitter-x"></i>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $currentUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-facebook" title="Share on Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ $encodedTitle }}%20{{ $currentUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-whatsapp" title="Share via WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <button type="button" class="share-btn share-copy border-0" id="copyShareLinkBtn" title="Copy Article Link" data-url="{{ request()->fullUrl() }}">
                                    <i class="bi bi-link-45deg"></i>
                                </button>
                                <span id="copyLinkSuccess" class="text-success small fw-bold d-none">
                                    <i class="bi bi-check-circle-fill"></i> Copied!
                                </span>
                            </div>

                            <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                <i class="bi bi-grid me-1"></i> Browse All Insights
                            </a>
                        </div>
                    </article>

                    {{-- Next / Previous Article Navigation --}}
                    @if(isset($prevBlog) || isset($nextBlog))
                        <div class="row g-3 mt-4">
                            <div class="col-sm-6">
                                @if(isset($prevBlog))
                                    <a href="{{ route('blogs.show', $prevBlog->slug) }}" class="article-nav-card">
                                        <div class="article-nav-direction">
                                            <i class="bi bi-arrow-left"></i> Previous Article
                                        </div>
                                        <div class="article-nav-title">{{ $prevBlog->title }}</div>
                                        <div class="article-nav-date">
                                            <i class="bi bi-calendar3 me-1"></i> {{ $prevBlog->published_at ? $prevBlog->published_at->format('M d, Y') : 'Recent' }}
                                        </div>
                                    </a>
                                @else
                                    <div class="article-nav-card opacity-50">
                                        <div class="article-nav-direction text-muted">
                                            <i class="bi bi-dash"></i> Beginning of Archive
                                        </div>
                                        <div class="article-nav-title text-muted">No older articles</div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-sm-6">
                                @if(isset($nextBlog))
                                    <a href="{{ route('blogs.show', $nextBlog->slug) }}" class="article-nav-card text-sm-end">
                                        <div class="article-nav-direction justify-content-sm-end">
                                            Next Article <i class="bi bi-arrow-right"></i>
                                        </div>
                                        <div class="article-nav-title">{{ $nextBlog->title }}</div>
                                        <div class="article-nav-date">
                                            <i class="bi bi-calendar3 me-1"></i> {{ $nextBlog->published_at ? $nextBlog->published_at->format('M d, Y') : 'Recent' }}
                                        </div>
                                    </a>
                                @else
                                    <div class="article-nav-card opacity-50 text-sm-end">
                                        <div class="article-nav-direction text-muted justify-content-sm-end">
                                            Latest Article <i class="bi bi-dash"></i>
                                        </div>
                                        <div class="article-nav-title text-muted">You are reading the newest post</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Right: Sidebar with Filters, Archives & Recent Posts --}}
                <div class="col-lg-4">

                    {{-- Search Widget --}}
                    <div class="sidebar-widget">
                        <div class="sidebar-widget-title">
                            <i class="bi bi-search text-primary"></i> Search Insights
                        </div>
                        <form action="{{ route('blogs.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control rounded-start-pill border-end-0 ps-3" placeholder="Search keywords..." aria-label="Search">
                                <button class="btn btn-primary rounded-end-pill px-3" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Date Archives Widget --}}
                    @if(isset($archives) && $archives->count() > 0)
                        <div class="sidebar-widget">
                            <div class="sidebar-widget-title">
                                <i class="bi bi-calendar3 text-primary"></i> Date Archives
                            </div>
                            <div class="d-flex flex-column">
                                @foreach($archives as $archive)
                                    @php
                                        $monthName = \Carbon\Carbon::createFromDate($archive->year, $archive->month, 1)->format('F Y');
                                        $isCurrentArticleMonth = $blog->published_at && ($blog->published_at->year == $archive->year && $blog->published_at->month == $archive->month);
                                    @endphp
                                    <a href="{{ route('blogs.index', ['year' => $archive->year, 'month' => $archive->month]) }}" class="archive-list-link {{ $isCurrentArticleMonth ? 'active' : '' }}">
                                        <span>
                                            <i class="bi bi-folder2-open me-2 text-primary"></i> {{ $monthName }}
                                        </span>
                                        <span class="archive-count-badge">{{ $archive->count }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mt-3 pt-2 text-center">
                                <a href="{{ route('blogs.index') }}" class="small text-primary text-decoration-none fw-semibold">
                                    View all archives &rarr;
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Recent Articles Widget --}}
                    @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                        <div class="sidebar-widget">
                            <div class="sidebar-widget-title">
                                <i class="bi bi-clock-history text-primary"></i> Recent Articles
                            </div>
                            <div class="d-flex flex-column">
                                @foreach($recentBlogs as $recent)
                                    <a href="{{ route('blogs.show', $recent->slug) }}" class="recent-post-item">
                                        <div class="recent-post-thumb">
                                            @if($recent->image)
                                                <img src="{{ asset('storage/' . $recent->image) }}" alt="{{ $recent->title }}">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                    <i class="bi bi-file-earmark-text fs-4 text-primary opacity-50"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <div class="recent-post-title">{{ $recent->title }}</div>
                                            <div class="recent-post-date">
                                                <i class="bi bi-calendar3 text-primary"></i>
                                                {{ $recent->published_at ? $recent->published_at->format('M d, Y') : 'Recent' }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Consultation / Contact CTA --}}
                    <div class="sidebar-cta-card text-center">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle" style="width: 52px; height: 52px; font-size: 1.5rem; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </span>
                        </div>
                        <h4 class="fw-bold mb-2 text-white">Need Engineering Solutions?</h4>
                        <p class="small text-white text-opacity-80 mb-4">
                            Connect with Enerix Solutions experts for solar power, industrial machinery, and automation consultations.
                        </p>
                        <a href="{{ route('contact.index') }}" class="btn btn-light text-primary fw-bold rounded-pill w-100 py-2 shadow-sm">
                            <i class="bi bi-chat-dots me-1"></i> Request a Consultation
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const copyBtn = document.getElementById('copyShareLinkBtn');
        const copySuccess = document.getElementById('copyLinkSuccess');

        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                const url = this.getAttribute('data-url') || window.location.href;
                navigator.clipboard.writeText(url).then(function () {
                    if (copySuccess) {
                        copySuccess.classList.remove('d-none');
                        setTimeout(function () {
                            copySuccess.classList.add('d-none');
                        }, 2500);
                    }
                }).catch(function (err) {
                    console.error('Could not copy text: ', err);
                });
            });
        }
    });
</script>
@endpush
