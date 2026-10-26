@extends('frontend.layouts.app')

@section('title', ($selectedCategory ? $selectedCategory->name . ' - ' : '') . 'Products & Solutions')
@section('meta_description', 'Explore our premium products, solar equipment, and industrial solutions. Filter by category, price, and features.')

@push('styles')
<style>
    /* Hero Banner */
    .products-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .products-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 20% 30%, rgba(56, 189, 248, 0.18) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(14, 165, 233, 0.15) 0%, transparent 50%);
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

    /* Category Pill Tabs Bar */
    .cat-pills-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 6px;
        scrollbar-width: thin;
    }
    .cat-pill {
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
    .cat-pill:hover {
        background: #f1f5f9;
        color: #0284c7;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
    .cat-pill.active {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
    }
    .cat-pill .pill-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #64748b;
    }
    .cat-pill.active .pill-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Subcategories Sub-bar */
    .subcat-pills-bar {
        display: flex;
        align-items: center;
        gap: 6px;
        overflow-x: auto;
        padding-top: 10px;
        padding-bottom: 4px;
    }
    .subcat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .subcat-pill:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .subcat-pill.active {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
    }

    /* Filter Sidebar */
    .filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.4rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .filter-section-title {
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #0f172a;
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .filter-cat-tree-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border-radius: 10px;
        color: #334155;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 600;
        transition: all 0.15s ease;
        margin-bottom: 2px;
    }
    .filter-cat-tree-link:hover {
        background: #f1f5f9;
        color: #0284c7;
        padding-left: 15px;
    }
    .filter-cat-tree-link.active {
        background: rgba(2, 132, 199, 0.1);
        color: #0284c7;
        font-weight: 700;
    }
    .filter-subcat-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 12px 6px 28px;
        border-radius: 8px;
        color: #64748b;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 500;
        transition: all 0.15s ease;
        margin-bottom: 1px;
    }
    .filter-subcat-link:hover {
        background: #f8fafc;
        color: #0284c7;
    }
    .filter-subcat-link.active {
        background: rgba(2, 132, 199, 0.08);
        color: #0284c7;
        font-weight: 700;
    }
    .filter-count-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #64748b;
    }
    .filter-cat-tree-link.active .filter-count-badge {
        background: #0284c7;
        color: #ffffff;
    }

    /* Price radio pills */
    .price-option-label {
        display: flex;
        align-items: center;
        padding: 7px 10px;
        border-radius: 8px;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-bottom: 2px;
    }
    .price-option-label:hover {
        background: #f8fafc;
        color: #0284c7;
    }
    .price-option-label input[type="radio"] {
        accent-color: #0284c7;
        margin-right: 8px;
    }
    .price-option-label.active {
        background: #f0f9ff;
        color: #0284c7;
        font-weight: 700;
    }

    /* Applied Filter Chips */
    .applied-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        border-radius: 50px;
        font-size: 0.76rem;
        font-weight: 600;
        margin-right: 6px;
        margin-bottom: 6px;
    }
    .applied-chip a {
        color: #0284c7;
        text-decoration: none;
        font-weight: 800;
        font-size: 0.85rem;
        line-height: 1;
    }
    .applied-chip a:hover {
        color: #ef4444;
    }

    /* Product Card */
    .product-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .product-thumb {
        position: relative;
        height: 220px;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        overflow: hidden;
    }
    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .product-card:hover .product-thumb img {
        transform: scale(1.06);
    }
    .product-thumb-placeholder {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }

    /* Badges on thumb */
    .badge-thumb-cat {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(8px);
        color: #0f172a;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        z-index: 2;
    }
    .badge-thumb-featured {
        position: absolute;
        top: 12px;
        right: 12px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        padding: 4px 10px;
        border-radius: 50px;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.35);
        z-index: 2;
    }
    .price-thumb-pill {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(15, 23, 42, 0.88);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.84rem;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        border: 1px solid rgba(255, 255, 255, 0.15);
        z-index: 2;
    }

    /* Card Details */
    .product-card-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .product-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        margin-bottom: 0.4rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.35;
        transition: color 0.15s ease;
    }
    .product-title:hover {
        color: #0284c7;
    }
    .product-short-desc {
        color: #64748b;
        font-size: 0.86rem;
        line-height: 1.45;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    /* Results Header Bar */
    .results-control-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.85rem 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }
</style>
@endpush

@section('content')

    {{-- HERO HEADER --}}
    <section class="products-hero py-5">
        <div class="container py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Products</li>
                    @if($selectedCategory)
                        <li class="breadcrumb-item text-white-50">{{ $selectedCategory->name }}</li>
                    @endif
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h1 class="display-6 fw-bold mb-2">
                        @if($selectedCategory)
                            {{ $selectedCategory->name }}
                        @else
                            Our Products &amp; Solutions
                        @endif
                    </h1>
                    <p class="lead text-white-50 mb-0" style="max-width: 620px; font-size: 1.05rem;">
                        @if($selectedCategory && $selectedCategory->description)
                            {{ $selectedCategory->description }}
                        @else
                            Discover our top-grade equipment, solar components, and specialized industrial solutions designed for reliability and performance.
                        @endif
                    </p>
                </div>
                <div class="col-lg-5">
                    {{-- Quick Search Form --}}
                    <form action="{{ route('products.index') }}" method="GET" class="hero-search-wrapper shadow-sm">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-white-50 ps-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control bg-transparent border-0 shadow-none"
                                   placeholder="Search products by model, title, keyword..."
                                   value="{{ $search }}">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('subcategory'))
                                <input type="hidden" name="subcategory" value="{{ request('subcategory') }}">
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

    {{-- TOP CATEGORY PILLS FILTER BAR --}}
    <section class="py-3 bg-white border-bottom shadow-sm">
        <div class="container">
            <div class="cat-pills-bar">
                {{-- All Products --}}
                <a href="{{ route('products.index', request()->except(['category', 'subcategory', 'page'])) }}"
                   class="cat-pill {{ !$selectedCategory ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>All Products</span>
                    <span class="pill-badge">{{ $totalActiveCount }}</span>
                </a>

                {{-- Each Category --}}
                @foreach($categories as $category)
                    <a href="{{ route('products.index', array_merge(request()->except(['category', 'subcategory', 'page']), ['category' => $category->slug])) }}"
                       class="cat-pill {{ $selectedCategory?->id === $category->id ? 'active' : '' }}">
                        <i class="bi bi-folder2-open"></i>
                        <span>{{ $category->name }}</span>
                        <span class="pill-badge">{{ $category->products_count }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Subcategories pills if category selected --}}
            @if($selectedCategory && $selectedCategory->subcategories->count() > 0)
                <div class="subcat-pills-bar border-top mt-2">
                    <span class="small fw-bold text-muted me-2 text-nowrap">
                        <i class="bi bi-arrow-return-right me-1"></i> Subcategories:
                    </span>
                    <a href="{{ route('products.index', array_merge(request()->except(['subcategory', 'page']), ['category' => $selectedCategory->slug])) }}"
                       class="subcat-pill {{ !$selectedSubcategory ? 'active' : '' }}">
                        All in {{ $selectedCategory->name }}
                    </a>
                    @foreach($selectedCategory->subcategories as $subcat)
                        <a href="{{ route('products.index', array_merge(request()->except(['page']), ['category' => $selectedCategory->slug, 'subcategory' => $subcat->slug])) }}"
                           class="subcat-pill {{ $selectedSubcategory?->id === $subcat->id ? 'active' : '' }}">
                            {{ $subcat->name }}
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1" style="font-size: 0.65rem;">
                                {{ $subcat->products_count ?? $subcat->products()->where('status', true)->count() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- MAIN PRODUCTS & FILTERS CONTENT --}}
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4">

                {{-- FILTER SIDEBAR --}}
                <aside class="col-lg-3">
                    <div class="filter-card sticky-top" style="top: 85px; z-index: 10;">

                        {{-- Sidebar Header --}}
                        <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-sliders text-primary fs-5"></i>
                                <span class="fw-bold fs-6 text-dark">Filter Products</span>
                            </div>
                            @if($activeFiltersCount > 0)
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-link text-danger text-decoration-none p-0 fw-semibold">
                                    <i class="bi bi-x-circle me-1"></i>Reset
                                </a>
                            @endif
                        </div>

                        {{-- Applied Filters Chips --}}
                        @if($activeFiltersCount > 0)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="small text-muted fw-bold mb-2">Applied Filters ({{ $activeFiltersCount }}):</div>
                                <div class="d-flex flex-wrap">
                                    @if($search !== '')
                                        <span class="applied-chip">
                                            Search: "{{ Str::limit($search, 12) }}"
                                            <a href="{{ request()->fullUrlWithQuery(['q' => null, 'search' => null]) }}">&times;</a>
                                        </span>
                                    @endif
                                    @if($selectedCategory)
                                        <span class="applied-chip">
                                            Cat: {{ $selectedCategory->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['category' => null, 'subcategory' => null]) }}">&times;</a>
                                        </span>
                                    @endif
                                    @if($selectedSubcategory)
                                        <span class="applied-chip">
                                            Sub: {{ $selectedSubcategory->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['subcategory' => null]) }}">&times;</a>
                                        </span>
                                    @endif
                                    @if(request('featured'))
                                        <span class="applied-chip">
                                            Featured Only
                                            <a href="{{ request()->fullUrlWithQuery(['featured' => null]) }}">&times;</a>
                                        </span>
                                    @endif
                                    @if($priceRange)
                                        <span class="applied-chip">
                                            Price: {{ ucwords(str_replace('_', ' ', $priceRange)) }}
                                            <a href="{{ request()->fullUrlWithQuery(['price_range' => null]) }}">&times;</a>
                                        </span>
                                    @endif
                                    @if(request('min_price') || request('max_price'))
                                        <span class="applied-chip">
                                            ৳ {{ number_format(request('min_price', 0)) }} - {{ request('max_price') ? '৳ ' . number_format(request('max_price')) : 'Any' }}
                                            <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}">&times;</a>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Filter Form --}}
                        <form action="{{ route('products.index') }}" method="GET" id="sidebarFilterForm">
                            {{-- Retain query params --}}
                            @if($search)
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                            @if($sort)
                                <input type="hidden" name="sort" value="{{ $sort }}">
                            @endif

                            {{-- 1. Category Tree --}}
                            <div class="mb-4">
                                <div class="filter-section-title">
                                    <span><i class="bi bi-tag me-1 text-primary"></i> Categories</span>
                                    <span class="text-muted fw-normal" style="font-size: 0.72rem;">{{ $categories->count() }} total</span>
                                </div>

                                <div class="category-tree">
                                    <a href="{{ route('products.index', request()->except(['category', 'subcategory', 'page'])) }}"
                                       class="filter-cat-tree-link {{ !$selectedCategory ? 'active' : '' }}">
                                        <span>All Categories</span>
                                        <span class="filter-count-badge">{{ $totalActiveCount }}</span>
                                    </a>

                                    @foreach($categories as $cat)
                                        @php
                                            $isCatActive = $selectedCategory?->id === $cat->id;
                                        @endphp
                                        <a href="{{ route('products.index', array_merge(request()->except(['category', 'subcategory', 'page']), ['category' => $cat->slug])) }}"
                                           class="filter-cat-tree-link {{ $isCatActive ? 'active' : '' }}">
                                            <span>{{ $cat->name }}</span>
                                            <span class="filter-count-badge">{{ $cat->products_count }}</span>
                                        </a>

                                        {{-- Subcategories list under this category --}}
                                        @if($isCatActive && $cat->subcategories->count() > 0)
                                            <div class="ps-2 mb-2 border-start ms-2 border-primary border-opacity-25">
                                                @foreach($cat->subcategories as $sub)
                                                    <a href="{{ route('products.index', array_merge(request()->except(['page']), ['category' => $cat->slug, 'subcategory' => $sub->slug])) }}"
                                                       class="filter-subcat-link {{ $selectedSubcategory?->id === $sub->id ? 'active' : '' }}">
                                                        <span>{{ $sub->name }}</span>
                                                        <span class="filter-count-badge" style="font-size: 0.65rem;">
                                                            {{ $sub->products_count ?? $sub->products()->where('status', true)->count() }}
                                                        </span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- 2. Price Range Filter --}}
                            <div class="mb-4 border-top pt-3">
                                <div class="filter-section-title">
                                    <span><i class="bi bi-cash-stack me-1 text-primary"></i> Price Range</span>
                                </div>

                                @if($selectedCategory)
                                    <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                                @endif
                                @if($selectedSubcategory)
                                    <input type="hidden" name="subcategory" value="{{ $selectedSubcategory->slug }}">
                                @endif

                                <div class="price-options mb-3">
                                    <label class="price-option-label {{ !$priceRange && !request('min_price') && !request('max_price') ? 'active' : '' }}">
                                        <input type="radio" name="price_range" value="" onchange="this.form.submit()" {{ !$priceRange ? 'checked' : '' }}>
                                        All Price Ranges
                                    </label>
                                    <label class="price-option-label {{ $priceRange === 'under_10k' ? 'active' : '' }}">
                                        <input type="radio" name="price_range" value="under_10k" onchange="this.form.submit()" {{ $priceRange === 'under_10k' ? 'checked' : '' }}>
                                        Under ৳ 10,000
                                    </label>
                                    <label class="price-option-label {{ $priceRange === '10k_50k' ? 'active' : '' }}">
                                        <input type="radio" name="price_range" value="10k_50k" onchange="this.form.submit()" {{ $priceRange === '10k_50k' ? 'checked' : '' }}>
                                        ৳ 10,000 &ndash; ৳ 50,000
                                    </label>
                                    <label class="price-option-label {{ $priceRange === '50k_150k' ? 'active' : '' }}">
                                        <input type="radio" name="price_range" value="50k_150k" onchange="this.form.submit()" {{ $priceRange === '50k_150k' ? 'checked' : '' }}>
                                        ৳ 50,000 &ndash; ৳ 150,000
                                    </label>
                                    <label class="price-option-label {{ $priceRange === '150k_500k' ? 'active' : '' }}">
                                        <input type="radio" name="price_range" value="150k_500k" onchange="this.form.submit()" {{ $priceRange === '150k_500k' ? 'checked' : '' }}>
                                        ৳ 150,000 &ndash; ৳ 500,000
                                    </label>
                                    <label class="price-option-label {{ $priceRange === 'above_500k' ? 'active' : '' }}">
                                        <input type="radio" name="price_range" value="above_500k" onchange="this.form.submit()" {{ $priceRange === 'above_500k' ? 'checked' : '' }}>
                                        Above ৳ 500,000
                                    </label>
                                </div>

                                {{-- Custom Min / Max Price --}}
                                <div class="p-2 rounded-3 bg-light border">
                                    <div class="small fw-bold text-dark mb-1">Custom Range (৳ BDT)</div>
                                    <div class="row g-2 align-items-center">
                                        <div class="col-5">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text px-1 bg-white text-muted">৳</span>
                                                <input type="number" name="min_price" class="form-control px-1" placeholder="Min"
                                                       value="{{ request('min_price') }}">
                                            </div>
                                        </div>
                                        <div class="col-2 text-center text-muted small">&ndash;</div>
                                        <div class="col-5">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text px-1 bg-white text-muted">৳</span>
                                                <input type="number" name="max_price" class="form-control px-1" placeholder="Max"
                                                       value="{{ request('max_price') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary btn-sm w-100 mt-2 fw-semibold">
                                        Apply Range
                                    </button>
                                </div>
                            </div>

                            {{-- 3. Featured Switch --}}
                            <div class="border-top pt-3">
                                <label class="form-check form-switch d-flex align-items-center justify-content-between p-0 m-0 cursor-pointer">
                                    <span class="form-check-label small fw-bold text-dark" for="featuredSwitch">
                                        <i class="bi bi-star-fill text-warning me-1"></i> Featured Only ({{ $featuredCount }})
                                    </span>
                                    <input class="form-check-input ms-auto" type="checkbox" role="switch"
                                           id="featuredSwitch" name="featured" value="1"
                                           onchange="this.form.submit()"
                                           {{ request('featured') ? 'checked' : '' }}>
                                </label>
                            </div>
                        </form>
                    </div>
                </aside>

                {{-- PRODUCTS GRID & RESULTS --}}
                <main class="col-lg-9">

                    {{-- Results Control Bar --}}
                    <div class="results-control-bar d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark">
                                {{ $products->total() }} <span class="fw-normal text-muted">Products Found</span>
                            </span>
                            @if($selectedCategory)
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                                    {{ $selectedCategory->name }}
                                </span>
                            @endif
                            @if($selectedSubcategory)
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1">
                                    {{ $selectedSubcategory->name }}
                                </span>
                            @endif
                        </div>

                        {{-- Sort Dropdown --}}
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center gap-2 m-0" id="sortForm">
                                {{-- Retain all query parameters except sort --}}
                                @foreach(request()->except(['sort', 'page']) as $k => $v)
                                    @if(is_array($v))
                                        @foreach($v as $arrV)
                                            <input type="hidden" name="{{ $k }}[]" value="{{ $arrV }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endif
                                @endforeach

                                <label for="sortSelect" class="small fw-semibold text-muted text-nowrap d-none d-sm-inline">
                                    Sort by:
                                </label>
                                <select name="sort" id="sortSelect" class="form-select form-select-sm rounded-pill fw-semibold border-secondary-subtle px-3"
                                        onchange="this.form.submit()">
                                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="featured" {{ $sort === 'featured' ? 'selected' : '' }}>Featured First</option>
                                    <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    {{-- Products Grid --}}
                    @if($products->count() > 0)
                        <div class="row g-4">
                            @foreach($products as $product)
                                <div class="col-md-6 col-xl-4" data-aos="fade-up">
                                    <div class="product-card">
                                        {{-- Thumbnail & Badges --}}
                                        <div class="product-thumb">
                                            @if($product->category)
                                                <span class="badge-thumb-cat">
                                                    {{ $product->category->name }}
                                                </span>
                                            @endif

                                            @if($product->is_featured)
                                                <span class="badge-thumb-featured">
                                                    <i class="bi bi-star-fill me-1"></i>Featured
                                                </span>
                                            @endif

                                            @if($product->image)
                                                <a href="{{ route('products.show', $product->slug) }}">
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                                                </a>
                                            @else
                                                <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                                    <div class="product-thumb-placeholder">
                                                        <i class="bi bi-box-seam" style="font-size: 3.5rem;"></i>
                                                        <span class="small mt-1 text-muted">No Image</span>
                                                    </div>
                                                </a>
                                            @endif

                                            @if($product->price)
                                                <div class="price-thumb-pill">
                                                    <span class="text-info fw-bold">৳</span> {{ number_format($product->price, $product->price == intval($product->price) ? 0 : 2) }} <span class="small opacity-75">BDT</span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Body Details --}}
                                        <div class="product-card-body">
                                            @if($product->subcategory)
                                                <div class="small text-muted mb-1 d-flex align-items-center gap-1">
                                                    <i class="bi bi-tag text-primary" style="font-size: 0.75rem;"></i>
                                                    <span>{{ $product->subcategory->name }}</span>
                                                </div>
                                            @endif

                                            <a href="{{ route('products.show', $product->slug) }}" class="product-title" title="{{ $product->title }}">
                                                {{ $product->title }}
                                            </a>

                                            <p class="product-short-desc">
                                                {{ $product->short_description ?? Str::limit(strip_tags($product->description), 90) }}
                                            </p>

                                            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                                                <div>
                                                    @if($product->price)
                                                        <div class="small text-muted" style="font-size: 0.75rem;">Price</div>
                                                        <div class="fw-bold text-primary fs-6">
                                                            <span class="me-1">৳</span>{{ number_format($product->price, $product->price == intval($product->price) ? 0 : 2) }} <span class="text-muted fw-normal small">BDT</span>
                                                        </div>
                                                    @else
                                                        <span class="badge bg-light text-muted border">Inquire for Price</span>
                                                    @endif
                                                </div>
                                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                                    Details <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-5 d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>

                    @else
                        {{-- Empty State --}}
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                            <div class="py-4">
                                <div class="d-inline-flex p-4 rounded-circle bg-light text-primary mb-3">
                                    <i class="bi bi-search fs-1"></i>
                                </div>
                                <h4 class="fw-bold text-dark">No Products Found</h4>
                                <p class="text-muted mx-auto" style="max-width: 480px;">
                                    We couldn't find any products matching your selected category, search keywords, or filters.
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset All Filters
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </main>
            </div>
        </div>
    </section>

@endsection
