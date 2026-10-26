@extends('admin.layouts.app')
@section('title', 'Contact & Social Links')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Contact &amp; Social Links</h4>
            <div class="text-secondary">Configure company contact details, address, map location, and social media URLs.</div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.contact-social.update') }}">
        @csrf

        <div class="row g-4">
            {{-- Contact Information Section --}}
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title text-dark fw-bold mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-inbound-fill text-primary"></i> Contact Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            {{-- Email --}}
                            <div class="col-12">
                                <label for="contact_email" class="form-label fw-semibold text-dark">Contact Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary border-end-0">
                                        <i class="bi bi-envelope-at-fill"></i>
                                    </span>
                                    <input type="email" id="contact_email" name="contact_email"
                                        class="form-control border-start-0 @error('contact_email') is-invalid @enderror"
                                        value="{{ old('contact_email', $setting->contact_email) }}"
                                        placeholder="info@example.com">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-secondary">Displayed on topbar, footer, and contact page.</div>
                            </div>

                            {{-- Phone --}}
                            <div class="col-12">
                                <label for="contact_phone" class="form-label fw-semibold text-dark">Contact Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-success border-end-0">
                                        <i class="bi bi-telephone-fill"></i>
                                    </span>
                                    <input type="text" id="contact_phone" name="contact_phone"
                                        class="form-control border-start-0 @error('contact_phone') is-invalid @enderror"
                                        value="{{ old('contact_phone', $setting->contact_phone) }}"
                                        placeholder="+1 (555) 000-1234">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-secondary">Main customer support or sales phone number.</div>
                            </div>

                            {{-- Address --}}
                            <div class="col-12">
                                <label for="contact_address" class="form-label fw-semibold text-dark">Physical Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger border-end-0">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </span>
                                    <input type="text" id="contact_address" name="contact_address"
                                        class="form-control border-start-0 @error('contact_address') is-invalid @enderror"
                                        value="{{ old('contact_address', $setting->contact_address) }}"
                                        placeholder="123 Renewable Way, Suite 400, Clean Energy City">
                                    @error('contact_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-secondary">Office address shown in footer and contact page.</div>
                            </div>

                            {{-- Google Map Embed --}}
                            <div class="col-12">
                                <label for="google_map_embed" class="form-label fw-semibold text-dark">Google Map Embed (URL or iframe src)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary border-end-0">
                                        <i class="bi bi-map-fill"></i>
                                    </span>
                                    <input type="text" id="google_map_embed" name="google_map_embed"
                                        class="form-control border-start-0 @error('google_map_embed') is-invalid @enderror"
                                        value="{{ old('google_map_embed', $setting->google_map_embed) }}"
                                        placeholder="https://www.google.com/maps/embed?pb=...">
                                    @error('google_map_embed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-secondary">
                                    Enter the embed link from Google Maps &gt; Share &gt; Embed a map.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Social Media Links Section --}}
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title text-dark fw-bold mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-share-fill text-primary"></i> Social Media Links
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            {{-- Facebook --}}
                            <div class="col-12">
                                <label for="facebook_url" class="form-label fw-semibold text-dark">Facebook Page URL</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="color: #1877f2 !important;">
                                        <i class="bi bi-facebook"></i>
                                    </span>
                                    <input type="url" id="facebook_url" name="facebook_url"
                                        class="form-control border-start-0 @error('facebook_url') is-invalid @enderror"
                                        value="{{ old('facebook_url', $setting->social_links['facebook'] ?? '') }}"
                                        placeholder="https://facebook.com/yourbrand">
                                    @error('facebook_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- X (formerly Twitter) --}}
                            <div class="col-12">
                                <label for="twitter_url" class="form-label fw-semibold text-dark">X (Twitter) Profile URL</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="color: #0f1419 !important;">
                                        <i class="bi bi-twitter-x"></i>
                                    </span>
                                    <input type="url" id="twitter_url" name="twitter_url"
                                        class="form-control border-start-0 @error('twitter_url') is-invalid @enderror"
                                        value="{{ old('twitter_url', $setting->social_links['twitter'] ?? ($setting->social_links['x'] ?? '')) }}"
                                        placeholder="https://x.com/yourbrand">
                                    @error('twitter_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Instagram --}}
                            <div class="col-12">
                                <label for="instagram_url" class="form-label fw-semibold text-dark">Instagram Profile URL</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="color: #e4405f !important;">
                                        <i class="bi bi-instagram"></i>
                                    </span>
                                    <input type="url" id="instagram_url" name="instagram_url"
                                        class="form-control border-start-0 @error('instagram_url') is-invalid @enderror"
                                        value="{{ old('instagram_url', $setting->social_links['instagram'] ?? '') }}"
                                        placeholder="https://instagram.com/yourbrand">
                                    @error('instagram_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- LinkedIn --}}
                            <div class="col-12">
                                <label for="linkedin_url" class="form-label fw-semibold text-dark">LinkedIn Page URL</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="color: #0a66c2 !important;">
                                        <i class="bi bi-linkedin"></i>
                                    </span>
                                    <input type="url" id="linkedin_url" name="linkedin_url"
                                        class="form-control border-start-0 @error('linkedin_url') is-invalid @enderror"
                                        value="{{ old('linkedin_url', $setting->social_links['linkedin'] ?? '') }}"
                                        placeholder="https://linkedin.com/company/yourbrand">
                                    @error('linkedin_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- YouTube --}}
                            <div class="col-12">
                                <label for="youtube_url" class="form-label fw-semibold text-dark">YouTube Channel URL</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0" style="color: #ff0000 !important;">
                                        <i class="bi bi-youtube"></i>
                                    </span>
                                    <input type="url" id="youtube_url" name="youtube_url"
                                        class="form-control border-start-0 @error('youtube_url') is-invalid @enderror"
                                        value="{{ old('youtube_url', $setting->social_links['youtube'] ?? '') }}"
                                        placeholder="https://youtube.com/@yourbrand">
                                    @error('youtube_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Helper notes --}}
                            <div class="col-12 mt-4">
                                <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle">
                                    <div class="small fw-semibold text-primary mb-1 d-flex align-items-center gap-1">
                                        <i class="bi bi-info-circle-fill"></i> Public Placement
                                    </div>
                                    <div class="small text-secondary">
                                        Social links configured here will automatically update the social follow icons displayed in the website header, footer, and contact page. Leave blank to hide a specific channel.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="col-12">
                <div class="card p-3 shadow-sm border-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="text-secondary small">
                            Changes take effect immediately across all website pages and footers.
                        </div>
                        <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-check-lg"></i> Save Contact &amp; Social Links
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
