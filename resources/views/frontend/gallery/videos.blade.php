@extends('frontend.layouts.app')

@section('title', 'Video Gallery - SolarTech')
@section('meta_description', 'Watch our video showcases, clean solar installations, and technical project walkthroughs.')

@push('styles')
<style>
    .video-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .video-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(56, 189, 248, 0.18) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(14, 165, 233, 0.15) 0%, transparent 45%);
        pointer-events: none;
    }
    /* Toggle Pills */
    .gallery-toggle-wrap {
        display: inline-flex;
        align-items: center;
        padding: 5px;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }
    .gallery-toggle-btn {
        padding: 0.5rem 1.25rem;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }
    .gallery-toggle-btn.active {
        background: #ffffff;
        color: #0f172a !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    .gallery-toggle-btn:not(.active) {
        color: rgba(255, 255, 255, 0.85) !important;
    }
    .gallery-toggle-btn:not(.active):hover {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.12);
    }
    .video-card {
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid var(--border-color, rgba(15, 23, 42, 0.08));
        background: var(--surface-color, #ffffff);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .video-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12) !important;
    }
    .video-frame-wrapper {
        position: relative;
        border-radius: 0.85rem 0.85rem 0 0;
        overflow: hidden;
        background: #0f172a;
    }
</style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="video-hero py-5">
        <div class="container py-lg-4 position-relative" data-aos="fade-up">
            <div class="row align-items-center justify-content-between g-4">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 text-white small fw-semibold mb-3 backdrop-blur">
                        <i class="bi bi-camera-video-fill text-warning"></i> Video Showcase
                    </div>
                    <h1 class="display-5 fw-bold text-white mb-3">Project Video Gallery</h1>
                    <p class="lead text-white-50 mb-0">
                        Explore our high-definition installation videos, client project walk-throughs, and renewable energy technology in action.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="gallery-toggle-wrap">
                        <a href="{{ route('gallery.photos') }}" class="gallery-toggle-btn">
                            <i class="bi bi-images text-primary"></i> Photos
                        </a>
                        <a href="{{ route('gallery.videos') }}" class="gallery-toggle-btn active">
                            <i class="bi bi-play-circle-fill text-danger"></i> Videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Video Grid Section --}}
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($items as $item)
                    @php
                        $videoUrl = $item->video_url ?? '';
                        if (str_contains($videoUrl, 'watch?v=')) {
                            $embedUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                        } elseif (str_contains($videoUrl, 'youtu.be/')) {
                            $embedUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $videoUrl);
                        } else {
                            $embedUrl = $videoUrl;
                        }
                    @endphp
                    <div class="col-md-6 col-lg-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 2) * 120 }}">
                        <div class="video-card h-100 shadow-sm d-flex flex-column">
                            <div class="ratio ratio-16x9 video-frame-wrapper">
                                <iframe src="{{ $embedUrl }}"
                                    title="{{ $item->title }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    loading="lazy">
                                </iframe>
                            </div>
                            <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
                                <h5 class="fw-bold mb-2 text-dark d-flex align-items-start gap-2">
                                    <i class="bi bi-play-btn-fill text-danger fs-5 mt-1"></i>
                                    <span>{{ $item->title }}</span>
                                </h5>
                                @if (!empty($item->created_at))
                                    <div class="text-secondary small mt-2 d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar3"></i> {{ $item->created_at->format('M d, Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5" data-aos="zoom-in">
                        <div class="card p-5 text-center border-0 shadow-sm rounded-4 max-w-md mx-auto" style="max-width: 480px; margin: 0 auto;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mx-auto mb-3" style="width: 72px; height: 72px; font-size: 2rem;">
                                <i class="bi bi-film"></i>
                            </div>
                            <h4 class="fw-bold mb-2 text-dark">No Videos Published Yet</h4>
                            <p class="text-secondary mb-4 small">
                                Project installation videos are coming soon. Check out our photo gallery in the meantime.
                            </p>
                            <div>
                                <a href="{{ route('gallery.photos') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="bi bi-images me-1"></i> View Photo Gallery
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($items->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
