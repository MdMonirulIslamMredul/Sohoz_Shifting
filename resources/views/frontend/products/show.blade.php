@extends('frontend.layouts.app')

@section('title', $product->title . ' - Products & Solutions')
@section('meta_description', Str::limit(strip_tags($product->short_description ?? $product->description), 155))

@push('styles')
<style>
    .product-detail-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
    }
    .product-detail-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .product-main-img-box {
        position: relative;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        overflow: hidden;
        min-height: 380px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
    }
    .product-main-img-box img {
        width: 100%;
        max-height: 480px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .product-main-img-box:hover img {
        transform: scale(1.03);
    }
    .price-box-detail {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1.5px solid #bae6fd;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
    }
    .spec-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.92rem;
    }
    .spec-item:last-child {
        border-bottom: none;
    }
    .related-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .related-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
    }
    .related-thumb {
        height: 160px;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
    }
    .related-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@section('content')

    {{-- HERO / BREADCRUMB BANNER --}}
    <section class="product-detail-hero py-4">
        <div class="container py-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Products</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-white-50 text-decoration-none">
                                {{ $product->category->name }}
                            </a>
                        </li>
                    @endif
                    @if($product->subcategory)
                        <li class="breadcrumb-item">
                            <a href="{{ route('products.index', ['category' => $product->category?->slug, 'subcategory' => $product->subcategory->slug]) }}" class="text-white-50 text-decoration-none">
                                {{ $product->subcategory->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ Str::limit($product->title, 35) }}</li>
                </ol>
            </nav>
            <h1 class="display-6 fw-bold mb-1">{{ $product->title }}</h1>
            @if($product->short_description)
                <p class="lead text-white-50 mb-0" style="font-size: 1.05rem;">{{ $product->short_description }}</p>
            @endif
        </div>
    </section>

    {{-- MAIN PRODUCT DETAIL --}}
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="product-detail-card p-4 p-lg-5 mb-5">
                <div class="row g-5">

                    {{-- Left Column: Product Image --}}
                    <div class="col-lg-6">
                        <div class="product-main-img-box mb-3">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="img-fluid rounded-3">
                            @else
                                <div class="text-center p-5 text-muted">
                                    <i class="bi bi-box-seam" style="font-size: 5rem;"></i>
                                    <p class="mt-2 mb-0 small">No preview image available</p>
                                </div>
                            @endif

                            @if($product->is_featured)
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill shadow-sm fw-bold">
                                    <i class="bi bi-star-fill me-1"></i> Featured Product
                                </span>
                            @endif
                        </div>

                        {{-- Quick Info Badges --}}
                        <div class="d-flex flex-wrap gap-2">
                            @if($product->category)
                                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                                   class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-2 rounded-pill">
                                    <i class="bi bi-folder2-open me-1"></i> {{ $product->category->name }}
                                </a>
                            @endif
                            @if($product->subcategory)
                                <a href="{{ route('products.index', ['category' => $product->category?->slug, 'subcategory' => $product->subcategory->slug]) }}"
                                   class="badge bg-secondary-subtle text-secondary border text-decoration-none px-3 py-2 rounded-pill">
                                    <i class="bi bi-tag me-1"></i> {{ $product->subcategory->name }}
                                </a>
                            @endif
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                <i class="bi bi-check-circle me-1"></i> Available
                            </span>
                        </div>
                    </div>

                    {{-- Right Column: Specs, Price, Actions --}}
                    <div class="col-lg-6 d-flex flex-column">

                        {{-- Category Link --}}
                        @if($product->category)
                            <div class="text-uppercase small fw-bold text-primary mb-2 letter-spacing-1">
                                {{ $product->category->name }}
                                @if($product->subcategory)
                                    &bull; {{ $product->subcategory->name }}
                                @endif
                            </div>
                        @endif

                        <h2 class="fw-bold text-dark mb-3">{{ $product->title }}</h2>

                        {{-- Price Highlight Box --}}
                        <div class="price-box-detail mb-4">
                            <div class="small text-muted fw-semibold">Estimated Unit Price</div>
                            <div class="d-flex align-items-baseline gap-2 mt-1">
                                @if($product->price)
                                    <span class="fs-1 fw-bold text-primary"><span class="text-info me-1">৳</span>{{ number_format($product->price, $product->price == intval($product->price) ? 0 : 2) }}</span>
                                    <span class="badge bg-primary rounded-pill px-3 py-1">BDT</span>
                                @else
                                    <span class="fs-4 fw-bold text-secondary">Contact for Quote</span>
                                @endif
                            </div>
                        </div>

                        {{-- Short Description --}}
                        @if($product->short_description)
                            <p class="text-secondary lead fs-6 mb-4">
                                {{ $product->short_description }}
                            </p>
                        @endif

                        {{-- Specifications Summary Card --}}
                        <div class="card border rounded-3 p-3 mb-4 bg-light">
                            <div class="fw-bold text-dark small mb-2 text-uppercase">Specifications Summary</div>
                            <div class="spec-item">
                                <span class="text-muted">Category</span>
                                <span class="fw-semibold text-dark">{{ $product->category?->name ?? 'Standard' }}</span>
                            </div>
                            @if($product->subcategory)
                                <div class="spec-item">
                                    <span class="text-muted">Subcategory</span>
                                    <span class="fw-semibold text-dark">{{ $product->subcategory->name }}</span>
                                </div>
                            @endif
                            <div class="spec-item">
                                <span class="text-muted">Availability</span>
                                <span class="badge bg-success text-white">In Stock / Available</span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex flex-wrap gap-3 mt-auto">
                            <a href="{{ route('contact.index', ['subject' => 'Inquiry: ' . $product->title]) }}"
                               class="btn btn-primary rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                                <i class="bi bi-chat-quote-fill"></i> Inquire About This Product
                            </a>
                            <a href="{{ route('products.index', $product->category ? ['category' => $product->category->slug] : []) }}"
                               class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Back to Products
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Full Description Section --}}
                @if($product->description)
                    <div class="border-top mt-5 pt-5">
                        <h4 class="fw-bold text-dark mb-3">Product Overview &amp; Details</h4>
                        <div class="text-secondary lh-lg fs-6" style="max-width: 960px;">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif
            </div>

            {{-- RELATED PRODUCTS IN SAME CATEGORY --}}
            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                <div class="mt-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">Related Products</h4>
                            <p class="text-muted small mb-0">More items in {{ $product->category?->name ?? 'this collection' }}</p>
                        </div>
                        @if($product->category)
                            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                               class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                View All ({{ $product->category->products()->where('status', true)->count() }}) <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>

                    <div class="row g-4">
                        @foreach($relatedProducts as $related)
                            <div class="col-md-6 col-lg-3">
                                <div class="related-card h-100 d-flex flex-column">
                                    <div class="related-thumb">
                                        @if($related->image)
                                            <a href="{{ route('products.show', $related->slug) }}">
                                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}">
                                            </a>
                                        @else
                                            <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-box-seam fs-3"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-3 d-flex flex-column flex-grow-1">
                                        @if($related->category)
                                            <div class="small text-muted" style="font-size: 0.72rem;">{{ $related->category->name }}</div>
                                        @endif
                                        <a href="{{ route('products.show', $related->slug) }}" class="fw-bold text-dark text-decoration-none mb-2" style="font-size: 0.92rem;">
                                            {{ Str::limit($related->title, 45) }}
                                        </a>
                                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                            <span class="fw-bold text-primary small">
                                                {{ $related->price ? '৳ ' . number_format($related->price, $related->price == intval($related->price) ? 0 : 2) . ' BDT' : 'Quote' }}
                                            </span>
                                            <a href="{{ route('products.show', $related->slug) }}" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-pill" style="font-size: 0.75rem;">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
