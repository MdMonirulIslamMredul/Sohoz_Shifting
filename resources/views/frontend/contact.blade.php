@extends('frontend.layouts.app')

@section('title', 'Contact Us - SolarTech')
@section('meta_description', 'Get in touch with SolarTech experts. Request a solar consultation, product inquiries, or emergency technical support.')

@php
    $mapEmbed = $setting?->google_map_embed;
    $mapSrc = null;
    if (!empty($mapEmbed)) {
        if (preg_match('/src=["\']([^"\']+)["\']/', $mapEmbed, $matches)) {
            $mapSrc = $matches[1];
        } else {
            $mapSrc = $mapEmbed;
        }
    }
    if (!$mapSrc && !empty($setting?->contact_address)) {
        $mapSrc = 'https://maps.google.com/maps?q=' . urlencode($setting->contact_address) . '&t=&z=13&ie=UTF8&iwloc=&output=embed';
    }
@endphp

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 55%, #0284c7 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(56, 189, 248, 0.18) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(14, 165, 233, 0.15) 0%, transparent 45%);
        pointer-events: none;
    }
    .contact-icon-box {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="contact-hero py-5">
        <div class="container py-lg-4 position-relative" data-aos="fade-up">
            <div class="row align-items-center justify-content-between g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 text-white small fw-semibold mb-3 backdrop-blur">
                        <i class="bi bi-chat-dots-fill text-warning"></i> 24/7 Support Available
                    </div>
                    <h1 class="display-5 fw-bold text-white mb-3">Let's Connect &amp; Discuss Your Project</h1>
                    <p class="lead text-white-50 mb-0" style="max-width: 720px;">
                        Have questions about equipment, customized solar packages, commercial financing, or system maintenance? Our engineering team is ready to assist.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-5">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 mb-4 p-4 border-0 d-flex align-items-center gap-3" role="alert" data-aos="fade-down">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success text-white p-2">
                        <i class="bi bi-check-lg fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1 text-success">Message Sent Successfully!</h5>
                        <div class="small mb-0">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4 p-3 border-0" role="alert">
                    <div class="d-flex align-items-center gap-2 fw-semibold">
                        <i class="bi bi-exclamation-triangle-fill"></i> Please correct the errors below:
                    </div>
                    <ul class="mb-0 mt-2 small ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4 align-items-stretch">
                {{-- Form Column --}}
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="card p-4 p-lg-5 border-0 shadow-sm rounded-4 h-100">
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-1">Send a Message</h3>
                            <p class="text-secondary small mb-0">Fill out the form below and an engineer or sales representative will contact you within 24 hours.</p>
                        </div>

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Your Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary">
                                            <i class="bi bi-person-fill"></i>
                                        </span>
                                        <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary">
                                            <i class="bi bi-envelope-fill"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="e.g. john@example.com" required>
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Phone Number <small class="text-secondary">(Optional)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary">
                                            <i class="bi bi-telephone-fill"></i>
                                        </span>
                                        <input type="text" name="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}" placeholder="e.g. +1 234 567 890">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Subject <small class="text-secondary">(Optional)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary">
                                            <i class="bi bi-chat-text-fill"></i>
                                        </span>
                                        <input type="text" name="subject" class="form-control border-start-0 @error('subject') is-invalid @enderror"
                                            value="{{ old('subject') }}" placeholder="e.g. Solar consultation inquiry">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Your Message <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5"
                                        placeholder="Please detail your project requirements, location, estimated energy consumption..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-send-fill"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Contact Info Column --}}
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="d-flex flex-column gap-4 h-100">
                        {{-- Office Information Card --}}
                        <div class="card p-4 border-0 shadow-sm rounded-4">
                            <h4 class="fw-bold mb-3 text-dark">Contact Information</h4>
                            <div class="d-flex flex-column gap-3">
                                @if ($setting?->contact_address)
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="contact-icon-box bg-danger-subtle text-danger">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <div>
                                            <div class="small fw-semibold text-dark">Office Address</div>
                                            <div class="text-secondary small">{{ $setting->contact_address }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($setting?->contact_email)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="contact-icon-box bg-primary-subtle text-primary">
                                            <i class="bi bi-envelope-fill"></i>
                                        </div>
                                        <div>
                                            <div class="small fw-semibold text-dark">Email Inquiries</div>
                                            <a href="mailto:{{ $setting->contact_email }}" class="text-secondary small text-decoration-none hover-primary">
                                                {{ $setting->contact_email }}
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if ($setting?->contact_phone)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="contact-icon-box bg-success-subtle text-success">
                                            <i class="bi bi-telephone-fill"></i>
                                        </div>
                                        <div>
                                            <div class="small fw-semibold text-dark">Hotline &amp; Support</div>
                                            <a href="tel:{{ $setting->contact_phone }}" class="text-secondary small text-decoration-none hover-primary">
                                                {{ $setting->contact_phone }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Map Card --}}
                        <div class="card p-3 border-0 shadow-sm rounded-4 flex-grow-1 overflow-hidden d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-2 px-2 pt-1">
                                <span class="fw-bold text-dark small d-flex align-items-center gap-2">
                                    <i class="bi bi-map-fill text-primary"></i> Map Location
                                </span>
                                @if ($mapSrc)
                                    <a href="{{ $mapSrc }}" target="_blank" rel="noopener" class="small text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                                        Open Map <i class="bi bi-box-arrow-up-right small"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="rounded-3 overflow-hidden flex-grow-1" style="min-height: 220px; background: #0f172a;">
                                <iframe src="{{ $mapSrc ?: 'https://maps.google.com/maps?q=Dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed' }}"
                                    width="100%"
                                    height="100%"
                                    style="border:0; min-height: 220px;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
