@extends('frontend.layouts.app')

@section('title', 'Photo Gallery - SolarTech')
@section('meta_description', 'Explore our comprehensive photo gallery featuring solar installations, renewable energy projects, engineering achievements, and operations.')

@push('styles')
<style>
    /* Hero Banner */
    .gallery-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .gallery-hero::before {
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

    /* Gallery Card Styling */
    .gallery-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.32s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        position: relative;
    }
    .gallery-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }

    /* Card Image Wrapper */
    .gallery-img-wrap {
        position: relative;
        height: 250px;
        overflow: hidden;
        background: #0f172a;
        cursor: pointer;
    }
    .gallery-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1), filter 0.4s ease;
        display: block;
    }
    .gallery-card:hover .gallery-thumb {
        transform: scale(1.08);
        filter: brightness(0.92);
    }

    /* Overlay on Hover */
    .gallery-img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.1) 0%, rgba(15, 23, 42, 0.65) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    .gallery-card:hover .gallery-img-overlay {
        opacity: 1;
    }

    /* Central Zoom Pill */
    .gallery-zoom-btn {
        background: rgba(255, 255, 255, 0.95);
        color: #0f172a;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 8px 18px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        transform: translateY(12px) scale(0.92);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .gallery-card:hover .gallery-zoom-btn {
        transform: translateY(0) scale(1);
    }

    /* Corner Badges */
    .gallery-badge-index {
        position: absolute;
        top: 14px;
        left: 14px;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(8px);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        z-index: 2;
        letter-spacing: 0.5px;
    }
    .gallery-badge-expand {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(6px);
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 2;
        transition: transform 0.25s ease, background 0.25s ease, color 0.25s ease;
    }
    .gallery-card:hover .gallery-badge-expand {
        background: #0284c7;
        color: #ffffff;
        transform: rotate(90deg);
    }

    /* Card Details Body */
    .gallery-card-body {
        padding: 1.35rem 1.4rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }
    .gallery-title {
        font-size: 1.08rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .gallery-title:hover {
        color: #0284c7;
    }
    .gallery-desc {
        color: #64748b;
        font-size: 0.88rem;
        line-height: 1.5;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .gallery-footer-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.82rem;
        color: #94a3b8;
        padding-top: 0.75rem;
        border-top: 1px dashed #e2e8f0;
        margin-top: auto;
    }
    .gallery-view-link {
        color: #0284c7;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.84rem;
        cursor: pointer;
        transition: gap 0.2s ease;
    }
    .gallery-card:hover .gallery-view-link {
        gap: 8px;
    }

    /* High-End Lightbox Modal */
    .gallery-lightbox {
        position: fixed;
        inset: 0;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        background: rgba(6, 11, 25, 0.94);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        z-index: 99999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .gallery-lightbox.active {
        display: flex;
        opacity: 1;
    }

    /* Lightbox Topbar */
    .lightbox-topbar {
        width: 100%;
        max-width: 1200px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #ffffff;
        padding: 0.5rem 0;
        z-index: 10;
    }
    .lightbox-counter-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .lightbox-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .lightbox-btn {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.15rem;
        transition: background 0.2s ease, transform 0.2s ease, color 0.2s ease;
        text-decoration: none;
    }
    .lightbox-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        transform: scale(1.08);
    }
    .lightbox-btn.close-btn {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.4);
        color: #fca5a5;
    }
    .lightbox-btn.close-btn:hover {
        background: rgba(239, 68, 68, 0.85);
        color: #ffffff;
    }

    /* Lightbox Center Stage */
    .lightbox-stage {
        position: relative;
        width: 100%;
        max-width: 1200px;
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 0;
        margin: 1rem 0;
    }
    .lightbox-img-wrapper {
        position: relative;
        max-width: 100%;
        max-height: 72vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .lightbox-img {
        max-width: 100%;
        max-height: 72vh;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 14px;
        box-shadow: 0 25px 65px rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    .lightbox-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        cursor: pointer;
        z-index: 10;
        transition: all 0.25s ease;
    }
    .lightbox-nav-btn:hover {
        background: #0284c7;
        color: #ffffff;
        border-color: #38bdf8;
        transform: translateY(-50%) scale(1.1);
    }
    .lightbox-nav-prev {
        left: 0;
    }
    .lightbox-nav-next {
        right: 0;
    }

    /* Lightbox Bottom Caption Bar */
    .lightbox-caption-bar {
        width: 100%;
        max-width: 1000px;
        text-align: center;
        color: #ffffff;
        padding: 0.75rem 1rem;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .lightbox-caption-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.25rem;
    }
    .lightbox-caption-desc {
        color: #cbd5e1;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    .lightbox-hint {
        font-size: 0.78rem;
        color: #94a3b8;
    }

    /* Mobile Adaptations */
    @media (max-width: 768px) {
        .gallery-img-wrap {
            height: 220px;
        }
        .lightbox-nav-btn {
            width: 44px;
            height: 44px;
            font-size: 1.1rem;
        }
        .lightbox-stage {
            margin: 0.5rem 0;
        }
        .lightbox-img {
            max-height: 60vh;
        }
        .lightbox-caption-title {
            font-size: 1rem;
        }
        .lightbox-caption-desc {
            font-size: 0.82rem;
        }
    }
</style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="gallery-hero py-5">
        <div class="container py-lg-4 position-relative" data-aos="fade-up">
            <div class="row align-items-center justify-content-between g-4">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 text-white small fw-semibold mb-3 backdrop-blur">
                        <i class="bi bi-images text-warning"></i> Visual Showcase
                    </div>
                    <h1 class="display-5 fw-bold text-white mb-3">Project Photo Gallery</h1>
                    <p class="lead text-white-50 mb-0">
                        Explore our high-resolution captures of commercial solar installations, engineering achievements, and technical field operations.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <div class="gallery-toggle-wrap">
                        <a href="{{ route('gallery.photos') }}" class="gallery-toggle-btn active">
                            <i class="bi bi-images text-primary"></i> Photos
                        </a>
                        <a href="{{ route('gallery.videos') }}" class="gallery-toggle-btn">
                            <i class="bi bi-play-circle-fill text-danger"></i> Videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Gallery Grid Section --}}
    <section class="py-5" style="background: #f8fafc; min-height: 50vh;">
        <div class="container">
            {{-- Toolbar / Counter Header --}}
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-camera me-1"></i> Photography Portfolio
                    </span>
                </div>
                <div class="text-muted small fw-semibold">
                    Showing {{ $items->count() }} of {{ $items->total() }} Photos
                </div>
            </div>

            {{-- Grid --}}
            <div class="row g-4 gallery-grid">
                @forelse($items as $item)
                    @php
                        $photoUrl = $item->image ? asset('storage/' . $item->image) : asset('frontend/images/placeholder.jpg');
                        $photoTitle = $item->title ?? 'Installation Photography';
                        $photoDesc = $item->description ?? '';
                    @endphp
                    <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                        <div class="gallery-card">
                            {{-- Image Wrap --}}
                            <div class="gallery-img-wrap gallery-trigger"
                                data-index="{{ $loop->index }}"
                                data-src="{{ $photoUrl }}"
                                data-title="{{ $photoTitle }}"
                                data-desc="{{ $photoDesc }}"
                                data-date="{{ $item->created_at ? $item->created_at->format('M d, Y') : '' }}"
                                role="button"
                                tabindex="0"
                                aria-label="Open photo {{ $photoTitle }}">
                                
                                <span class="gallery-badge-index">#{{ $loop->iteration }}</span>
                                <span class="gallery-badge-expand" title="Enlarge photo">
                                    <i class="bi bi-arrows-angle-expand"></i>
                                </span>

                                @if($item->image)
                                    <img src="{{ $photoUrl }}" alt="{{ $photoTitle }}" class="gallery-thumb" loading="lazy">
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                                        <span class="small mt-2">No Image Available</span>
                                    </div>
                                @endif

                                {{-- Hover Overlay --}}
                                <div class="gallery-img-overlay">
                                    <span class="gallery-zoom-btn">
                                        <i class="bi bi-search"></i> View Full Photo
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="gallery-card-body">
                                <div>
                                    <h3 class="gallery-title gallery-trigger"
                                        data-index="{{ $loop->index }}"
                                        data-src="{{ $photoUrl }}"
                                        data-title="{{ $photoTitle }}"
                                        data-desc="{{ $photoDesc }}"
                                        data-date="{{ $item->created_at ? $item->created_at->format('M d, Y') : '' }}"
                                        role="button"
                                        tabindex="0"
                                        title="{{ $photoTitle }}">
                                        {{ $photoTitle }}
                                    </h3>

                                    @if(!empty($photoDesc))
                                        <p class="gallery-desc">
                                            {{ $photoDesc }}
                                        </p>
                                    @endif
                                </div>

                                <div class="gallery-footer-meta">
                                    <span>
                                        @if($item->created_at)
                                            <i class="bi bi-calendar3 me-1"></i> {{ $item->created_at->format('M d, Y') }}
                                        @else
                                            <i class="bi bi-camera me-1"></i> Project Showcase
                                        @endif
                                    </span>

                                    <span class="gallery-view-link gallery-trigger"
                                        data-index="{{ $loop->index }}"
                                        data-src="{{ $photoUrl }}"
                                        data-title="{{ $photoTitle }}"
                                        data-desc="{{ $photoDesc }}"
                                        data-date="{{ $item->created_at ? $item->created_at->format('M d, Y') : '' }}">
                                        Enlarge <i class="bi bi-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5" data-aos="zoom-in">
                        <div class="card p-5 text-center border-0 shadow-sm rounded-4 max-w-md mx-auto" style="max-width: 480px; margin: 0 auto; background: #ffffff;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mx-auto mb-3" style="width: 72px; height: 72px; font-size: 2rem;">
                                <i class="bi bi-images"></i>
                            </div>
                            <h4 class="fw-bold mb-2 text-dark">No Photos Uploaded Yet</h4>
                            <p class="text-secondary mb-4 small">
                                Project photography is coming soon. In the meantime, you can explore our video demonstrations.
                            </p>
                            <div>
                                <a href="{{ route('gallery.videos') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="bi bi-play-circle-fill me-1"></i> View Video Gallery
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($items->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- App-Grade Lightbox Modal --}}
    <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Photo Lightbox Viewer">
        {{-- Top Bar --}}
        <div class="lightbox-topbar">
            <div class="lightbox-counter-pill">
                <i class="bi bi-camera-fill text-warning"></i>
                <span id="galleryCounter">1 / 1</span>
            </div>

            <div class="lightbox-controls">
                <button type="button" class="lightbox-btn" id="lightboxFullscreen" title="Toggle Fullscreen" aria-label="Fullscreen">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
                <a href="#" class="lightbox-btn" id="lightboxDownload" target="_blank" download title="Open Original Image" aria-label="Open Original Image">
                    <i class="bi bi-box-arrow-up-right"></i>
                </a>
                <button type="button" class="lightbox-btn close-btn" id="galleryClose" title="Close Lightbox (Esc)" aria-label="Close Lightbox">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        {{-- Main Stage --}}
        <div class="lightbox-stage">
            <button type="button" class="lightbox-nav-btn lightbox-nav-prev" id="galleryPrev" title="Previous Photo (Left Arrow)" aria-label="Previous Photo">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="lightbox-img-wrapper">
                <img src="" alt="" id="galleryImage" class="lightbox-img">
            </div>

            <button type="button" class="lightbox-nav-btn lightbox-nav-next" id="galleryNext" title="Next Photo (Right Arrow)" aria-label="Next Photo">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        {{-- Caption Bar --}}
        <div class="lightbox-caption-bar">
            <div class="lightbox-caption-title" id="galleryTitle"></div>
            <div class="lightbox-caption-desc" id="galleryDesc"></div>
            <div class="lightbox-hint">
                <span><i class="bi bi-keyboard me-1"></i> Use &larr; &rarr; keys to browse &bull; Esc to exit</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const triggers = Array.from(document.querySelectorAll('.gallery-trigger[data-src]'));
        const lightbox = document.getElementById('galleryLightbox');
        const imageEl = document.getElementById('galleryImage');
        const titleEl = document.getElementById('galleryTitle');
        const descEl = document.getElementById('galleryDesc');
        const counterEl = document.getElementById('galleryCounter');
        const downloadBtn = document.getElementById('lightboxDownload');
        const fullscreenBtn = document.getElementById('lightboxFullscreen');
        const closeBtn = document.getElementById('galleryClose');
        const prevBtn = document.getElementById('galleryPrev');
        const nextBtn = document.getElementById('galleryNext');
        
        // Map unique items by data-index to prevent duplicate trigger triggers
        const itemMap = new Map();
        triggers.forEach(t => {
            const idx = parseInt(t.dataset.index, 10);
            if (!itemMap.has(idx)) {
                itemMap.set(idx, {
                    index: idx,
                    src: t.dataset.src,
                    title: t.dataset.title || '',
                    desc: t.dataset.desc || '',
                    date: t.dataset.date || ''
                });
            }
        });

        const items = Array.from(itemMap.values()).sort((a, b) => a.index - b.index);
        let currentIndex = 0;

        if (!items.length || !lightbox) {
            return;
        }

        const updateLightbox = (index) => {
            if (index < 0) index = items.length - 1;
            if (index >= items.length) index = 0;

            currentIndex = index;
            const current = items[currentIndex];

            // Subtle fade transition
            imageEl.style.opacity = '0.3';
            imageEl.style.transform = 'scale(0.96)';

            setTimeout(() => {
                imageEl.src = current.src;
                imageEl.alt = current.title;
                titleEl.textContent = current.title;
                descEl.textContent = current.desc;
                descEl.style.display = current.desc ? 'block' : 'none';
                counterEl.textContent = `${currentIndex + 1} / ${items.length}`;
                downloadBtn.href = current.src;

                imageEl.onload = () => {
                    imageEl.style.opacity = '1';
                    imageEl.style.transform = 'scale(1)';
                };
                // Fallback if cached
                imageEl.style.opacity = '1';
                imageEl.style.transform = 'scale(1)';
            }, 120);

            lightbox.classList.add('active');
            lightbox.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        };

        const closeLightbox = () => {
            lightbox.classList.remove('active');
            lightbox.setAttribute('aria-hidden', 'true');
            imageEl.src = '';
            document.body.style.overflow = '';
        };

        // Attach click listeners to all triggers
        triggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const idx = parseInt(trigger.dataset.index, 10);
                updateLightbox(idx);
            });
            // Allow keyboard activation (Enter / Space) on triggers
            trigger.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const idx = parseInt(trigger.dataset.index, 10);
                    updateLightbox(idx);
                }
            });
        });

        // Close button & Click outside to close
        closeBtn.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox || e.target.classList.contains('lightbox-stage')) {
                closeLightbox();
            }
        });

        // Navigation buttons
        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            updateLightbox(currentIndex - 1);
        });

        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            updateLightbox(currentIndex + 1);
        });

        // Toggle Fullscreen
        fullscreenBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                lightbox.requestFullscreen().catch(err => {
                    console.log('Fullscreen error:', err);
                });
            } else {
                document.exitFullscreen();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) {
                return;
            }

            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                updateLightbox(currentIndex - 1);
            } else if (e.key === 'ArrowRight') {
                updateLightbox(currentIndex + 1);
            }
        });

        // Touch Swipe Navigation for mobile devices
        let touchStartX = 0;
        let touchEndX = 0;

        lightbox.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        lightbox.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        const handleSwipe = () => {
            const threshold = 50;
            if (touchEndX < touchStartX - threshold) {
                // Swipe Left -> Next
                updateLightbox(currentIndex + 1);
            }
            if (touchEndX > touchStartX + threshold) {
                // Swipe Right -> Prev
                updateLightbox(currentIndex - 1);
            }
        };
    });
</script>
@endpush
