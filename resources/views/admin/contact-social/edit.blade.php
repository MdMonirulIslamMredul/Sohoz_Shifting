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

            {{-- Marketing & Tracking Suite (Meta Pixel, GA4, CAPI, WhatsApp) --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="card-title text-dark fw-bold mb-1 d-flex align-items-center gap-2">
                                <i class="bi bi-graph-up-arrow text-primary"></i> Marketing, Pixels &amp; Sales Tracking Suite
                            </h5>
                            <div class="text-secondary small">Meta (Facebook) Pixel, Google Analytics 4 (GA4), Conversion API (CAPI) এবং WhatsApp ট্র্যাকিং কনফিগার করুন</div>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">
                            <i class="bi bi-check2-circle me-1"></i> Full Website Pixel Active
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            {{-- Meta Pixel ID --}}
                            <div class="col-md-6">
                                <label for="meta_pixel_id" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-facebook text-primary me-1"></i> Meta (Facebook) Pixel ID
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary border-end-0">
                                        <i class="bi bi-tag-fill"></i>
                                    </span>
                                    <input type="text" id="meta_pixel_id" name="meta_pixel_id"
                                        class="form-control border-start-0 font-monospace @error('meta_pixel_id') is-invalid @enderror"
                                        value="{{ old('meta_pixel_id', $setting->meta_pixel_id) }}"
                                        placeholder="e.g. 1234567890123456">
                                    @error('meta_pixel_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-secondary">
                                    ফেসবুক অ্যাডস লিড ও কনভার্সন ট্র্যাকিং পিক্সেল আইডি (Meta Events Manager হতে প্রাপ্ত)।
                                </div>
                            </div>

                            {{-- GA4 Measurement ID --}}
                            <div class="col-md-6">
                                <label for="ga4_measurement_id" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-google text-danger me-1"></i> Google Analytics 4 (GA4) Measurement ID
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-danger border-end-0">
                                        <i class="bi bi-bar-chart-fill"></i>
                                    </span>
                                    <input type="text" id="ga4_measurement_id" name="ga4_measurement_id"
                                        class="form-control border-start-0 font-monospace @error('ga4_measurement_id') is-invalid @enderror"
                                        value="{{ old('ga4_measurement_id', $setting->ga4_measurement_id) }}"
                                        placeholder="e.g. G-XXXXXXXXXX">
                                    @error('ga4_measurement_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-secondary">
                                    গুগল অ্যানালিটিক্স ৪ প্রোপার্টি মেজারমেন্ট আইডি।
                                </div>
                            </div>

                            {{-- WhatsApp Click Tracking Hotline --}}
                            <div class="col-md-6">
                                <label for="whatsapp_tracking_number" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-whatsapp text-success me-1"></i> WhatsApp Click Tracking Hotline
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-success border-end-0">
                                        <i class="bi bi-telephone-outbound-fill"></i>
                                    </span>
                                    <input type="text" id="whatsapp_tracking_number" name="whatsapp_tracking_number"
                                        class="form-control border-start-0 font-monospace @error('whatsapp_tracking_number') is-invalid @enderror"
                                        value="{{ old('whatsapp_tracking_number', $setting->whatsapp_tracking_number) }}"
                                        placeholder="e.g. 8801700000000">
                                    @error('whatsapp_tracking_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text text-secondary">
                                    হোয়াটসঅ্যাপ ক্লিক ট্র্যাকিং ও সরাসরি চ্যাটের নাম্বার (কান্ট্রি কোড সহ)।
                                </div>
                            </div>

                            {{-- Enable Marketing Tracking Switch --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Tracking Status</label>
                                <div class="p-2 border rounded-3 bg-light">
                                    <div class="form-check form-switch pt-1">
                                        <input class="form-check-input" type="checkbox" name="enable_marketing_tracking" id="enableMarketingSwitch" value="1"
                                            {{ old('enable_marketing_tracking', $setting->enable_marketing_tracking ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold text-dark" for="enableMarketingSwitch">
                                            পিক্সেল ও অ্যানালিটিক্স স্ক্রিপ্ট চালু রাখুন (Enable Tracking)
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">টিক দেওয়া থাকলে ওয়েবসাইটে পিক্সেল ও অ্যানালিটিক্স কোড সক্রিয় থাকবে।</small>
                                </div>
                            </div>

                            {{-- Meta Conversion API (CAPI) --}}
                            <div class="col-12">
                                <label for="meta_capi_token" class="form-label fw-semibold text-dark">
                                    Meta Conversion API (CAPI) Access Token (ঐচ্ছিক)
                                </label>
                                <textarea id="meta_capi_token" class="form-control font-monospace" name="meta_capi_token" rows="2"
                                    placeholder="EAAG... (Facebook Events Manager হতে প্রাপ্ত সার্ভার সাইড টোকেন)">{{ old('meta_capi_token', $setting->meta_capi_token) }}</textarea>
                                <div class="form-text text-secondary">
                                    সার্ভার-সাইড ট্র্যাকিংয়ের জন্য ফেসবুক CAPI অ্যাক্সেস টোকেন।
                                </div>
                            </div>

                            {{-- Automated Events Summary --}}
                            <div class="col-12 mt-2">
                                <div class="p-3 rounded-3 bg-light border">
                                    <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                        <i class="bi bi-activity text-success"></i> কনফিগার করা অটোমেটেড ট্র্যাকিং ইভেন্টসমূহ (Active Events):
                                    </h6>
                                    <div class="row g-2 small">
                                        <div class="col-md-6">
                                            <div class="p-3 bg-white rounded border h-100">
                                                <strong class="text-primary d-block mb-1"><i class="bi bi-eye-fill me-1"></i> View Event (PageView):</strong>
                                                <span class="text-secondary">ওয়েবসাইটের প্রতিটি পেজে ভিজিটর প্রবেশ করলেই ফেসবুকে স্বয়ংক্রিয়ভাবে <code>PageView</code> ইভেন্ট পাঠানো হবে।</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 bg-white rounded border h-100">
                                                <strong class="text-success d-block mb-1"><i class="bi bi-bag-check-fill me-1"></i> Purchase &amp; Lead Event:</strong>
                                                <span class="text-secondary">গ্রাহক বুকিং কনফার্ম করার সাথে সাথে বুকিং কোড, প্রপার্টি ও ভাড়ার অংক সহ <code>Purchase</code> এবং <code>Lead</code> ইভেন্ট ফেসবুকে পাঠানো হবে।</span>
                                            </div>
                                        </div>
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
