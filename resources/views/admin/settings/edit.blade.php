@extends('admin.layouts.app')
@section('title', 'Settings')
@section('content')
    @php
        $themeDefaults = [
            'primary_color' => '#0d6efd',
            'secondary_color' => '#6c757d',
            'accent_color' => '#f39c12',
            'text_color' => '#333333',
            'bg_color' => '#ffffff',
        ];

        $themeValues = [
            'primary_color' => old('primary_color', $setting->primary_color ?? $themeDefaults['primary_color']),
            'secondary_color' => old('secondary_color', $setting->secondary_color ?? $themeDefaults['secondary_color']),
            'accent_color' => old('accent_color', $setting->accent_color ?? $themeDefaults['accent_color']),
            'text_color' => old('text_color', $setting->text_color ?? $themeDefaults['text_color']),
            'bg_color' => old('bg_color', $setting->bg_color ?? $themeDefaults['bg_color']),
        ];
    @endphp

    <div class="card p-4">
        <h4 class="mb-3">Website Settings</h4>
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <h5 class="card-title mb-1">Theme Settings</h5>
                            <p class="text-secondary mb-0">Customize the site colors without touching code.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                data-theme-preset="light">Light</button>
                            <button type="button" class="btn btn-sm btn-outline-dark"
                                data-theme-preset="dark">Dark</button>
                            <button type="button" class="btn btn-sm btn-outline-success"
                                data-theme-preset="solar">Solar</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="resetThemeColors">Reset
                                Defaults</button>
                        </div>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <div class="col-lg-7">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Primary Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="primary_color"
                                        id="primaryColor" value="{{ $themeValues['primary_color'] }}" title="Primary color">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Secondary Color</label>
                                    <input type="color" class="form-control form-control-color w-100"
                                        name="secondary_color" id="secondaryColor"
                                        value="{{ $themeValues['secondary_color'] }}" title="Secondary color">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Accent Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="accent_color"
                                        id="accentColor" value="{{ $themeValues['accent_color'] }}" title="Accent color">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="bg_color"
                                        id="bgColor" value="{{ $themeValues['bg_color'] }}" title="Background color">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Text Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="text_color"
                                        id="textColor" value="{{ $themeValues['text_color'] }}" title="Text color">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="border rounded-3 p-3 h-100" id="themePreviewPanel"
                                style="background: {{ $themeValues['bg_color'] }}; color: {{ $themeValues['text_color'] }}; transition: background-color 0.25s ease, color 0.25s ease;">
                                <h6 class="mb-2">Live Preview</h6>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="rounded-3 border" id="primarySwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['primary_color'] }};"></span>
                                    <span class="rounded-3 border" id="secondarySwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['secondary_color'] }};"></span>
                                    <span class="rounded-3 border" id="accentSwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['accent_color'] }};"></span>
                                    <span class="rounded-3 border" id="textSwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['text_color'] }};"></span>
                                    <span class="rounded-3 border" id="bgSwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['bg_color'] }};"></span>
                                </div>
                                <p class="small mb-3">Buttons, links, navbar, footer, and sections will inherit these colors
                                    across the frontend.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm" id="previewPrimaryBtn">Primary</button>
                                    <button type="button" class="btn btn-sm" id="previewSecondaryBtn">Secondary</button>
                                    <button type="button" class="btn btn-sm" id="previewAccentBtn">Accent</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Site Name</label>
                    <input class="form-control" name="site_name" value="{{ old('site_name', $setting->site_name) }}"
                        required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Title</label>
                    <input class="form-control" name="meta_title" value="{{ old('meta_title', $setting->meta_title) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Description</label>
                    <textarea class="form-control" name="meta_description" rows="2">{{ old('meta_description', $setting->meta_description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">SEO Keywords</label>
                    <input class="form-control" name="seo_keywords"
                        value="{{ old('seo_keywords', $setting->seo_keywords) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Logo</label>
                    <div class="border rounded-3 bg-light d-flex align-items-center justify-content-center mb-2"
                        style="min-height: 140px;">
                        <img id="logoPreview"
                            src="{{ $setting->logo_path ? asset('storage/' . $setting->logo_path) : '' }}"
                            alt="Logo preview"
                            style="max-width: 100%; max-height: 110px; {{ $setting->logo_path ? '' : 'display:none;' }}">
                        <span id="logoPlaceholder" class="text-secondary {{ $setting->logo_path ? 'd-none' : '' }}">No
                            logo uploaded</span>
                    </div>
                    <input type="file" class="form-control" name="logo" id="logoInput" accept="image/*">
                    <div class="form-text">Allowed: PNG, JPG, JPEG, SVG. Recommended size: 300x100 px or similar wide logo.
                        Max file size: 2 MB.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Favicon</label>
                    <div class="border rounded-3 bg-light d-flex align-items-center justify-content-center mb-2"
                        style="min-height: 100px;">
                        <img id="faviconPreview"
                            src="{{ $setting->favicon_path ? asset('storage/' . $setting->favicon_path) : '' }}"
                            alt="Favicon preview"
                            style="max-width: 56px; max-height: 56px; {{ $setting->favicon_path ? '' : 'display:none;' }}">
                        <span id="faviconPlaceholder"
                            class="text-secondary {{ $setting->favicon_path ? 'd-none' : '' }}">No favicon uploaded</span>
                    </div>
                    <input type="file" class="form-control" name="favicon" id="faviconInput" accept="image/*,.ico">
                    <div class="form-text">Allowed: ICO, PNG, JPG, JPEG, SVG. Recommended size: 32x32 px or 48x48 px. Max file size: 1 MB.</div>
                </div>

                <div class="col-12 mt-4 pt-3 border-top">
                    <h5 class="text-dark fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-megaphone-fill text-primary"></i> Homepage Call To Action (CTA)
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">CTA Title</label>
                            <input class="form-control" name="cta_title" value="{{ old('cta_title', $setting->cta_title) }}" placeholder="e.g. Ready to switch to solar?">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">CTA Button Text</label>
                            <input class="form-control" name="cta_button_text" value="{{ old('cta_button_text', $setting->cta_button_text) }}" placeholder="e.g. Contact Us">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">CTA Button Link</label>
                            <input class="form-control" name="cta_button_link" value="{{ old('cta_button_link', $setting->cta_button_link) }}" placeholder="e.g. /contact">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">CTA Description</label>
                            <textarea class="form-control" name="cta_text" rows="2" placeholder="e.g. Talk to our experts and get a customized clean-energy plan.">{{ old('cta_text', $setting->cta_text) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary mt-4 px-4"><i class="bi bi-check-lg me-1"></i> Save Settings</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const bindPreview = (inputId, previewId, placeholderId) => {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);

                if (!input || !preview || !placeholder) {
                    return;
                }

                input.addEventListener('change', () => {
                    const file = input.files && input.files[0];

                    if (!file) {
                        return;
                    }

                    preview.src = URL.createObjectURL(file);
                    preview.style.display = 'block';
                    placeholder.classList.add('d-none');
                });
            };

            bindPreview('logoInput', 'logoPreview', 'logoPlaceholder');
            bindPreview('faviconInput', 'faviconPreview', 'faviconPlaceholder');

            const themeDefaults = {
                primaryColor: '#0d6efd',
                secondaryColor: '#6c757d',
                accentColor: '#f39c12',
                textColor: '#333333',
                bgColor: '#ffffff',
            };

            const inputs = {
                primary: document.getElementById('primaryColor'),
                secondary: document.getElementById('secondaryColor'),
                accent: document.getElementById('accentColor'),
                text: document.getElementById('textColor'),
                bg: document.getElementById('bgColor'),
            };

            const swatches = {
                primary: document.getElementById('primarySwatch'),
                secondary: document.getElementById('secondarySwatch'),
                accent: document.getElementById('accentSwatch'),
                text: document.getElementById('textSwatch'),
                bg: document.getElementById('bgSwatch'),
            };

            const previewPanel = document.getElementById('themePreviewPanel');
            const previewButtons = {
                primary: document.getElementById('previewPrimaryBtn'),
                secondary: document.getElementById('previewSecondaryBtn'),
                accent: document.getElementById('previewAccentBtn'),
            };

            const applyColors = () => {
                if (!previewPanel) {
                    return;
                }

                const primary = inputs.primary?.value || themeDefaults.primaryColor;
                const secondary = inputs.secondary?.value || themeDefaults.secondaryColor;
                const accent = inputs.accent?.value || themeDefaults.accentColor;
                const text = inputs.text?.value || themeDefaults.textColor;
                const bg = inputs.bg?.value || themeDefaults.bgColor;

                if (swatches.primary) swatches.primary.style.backgroundColor = primary;
                if (swatches.secondary) swatches.secondary.style.backgroundColor = secondary;
                if (swatches.accent) swatches.accent.style.backgroundColor = accent;
                if (swatches.text) swatches.text.style.backgroundColor = text;
                if (swatches.bg) swatches.bg.style.backgroundColor = bg;

                previewPanel.style.backgroundColor = bg;
                previewPanel.style.color = text;

                if (previewButtons.primary) {
                    previewButtons.primary.style.backgroundColor = primary;
                    previewButtons.primary.style.borderColor = primary;
                    previewButtons.primary.style.color = '#ffffff';
                }

                if (previewButtons.secondary) {
                    previewButtons.secondary.style.backgroundColor = secondary;
                    previewButtons.secondary.style.borderColor = secondary;
                    previewButtons.secondary.style.color = '#ffffff';
                }

                if (previewButtons.accent) {
                    previewButtons.accent.style.backgroundColor = accent;
                    previewButtons.accent.style.borderColor = accent;
                    previewButtons.accent.style.color = '#111827';
                }

                document.documentElement.style.setProperty('--primary-color', primary);
                document.documentElement.style.setProperty('--secondary-color', secondary);
                document.documentElement.style.setProperty('--accent-color', accent);
                document.documentElement.style.setProperty('--text-color', text);
                document.documentElement.style.setProperty('--bg-color', bg);
            };

            Object.values(inputs).forEach((input) => {
                if (!input) {
                    return;
                }

                input.addEventListener('input', applyColors);
            });

            document.querySelectorAll('[data-theme-preset]').forEach((button) => {
                button.addEventListener('click', () => {
                    const preset = button.dataset.themePreset;
                    const themes = {
                        light: {
                            primaryColor: '#0d6efd',
                            secondaryColor: '#6c757d',
                            accentColor: '#f39c12',
                            textColor: '#333333',
                            bgColor: '#ffffff',
                        },
                        dark: {
                            primaryColor: '#38bdf8',
                            secondaryColor: '#94a3b8',
                            accentColor: '#f59e0b',
                            textColor: '#f8fafc',
                            bgColor: '#0f172a',
                        },
                        solar: {
                            primaryColor: '#0f766e',
                            secondaryColor: '#64748b',
                            accentColor: '#f59e0b',
                            textColor: '#1e293b',
                            bgColor: '#ffffff',
                        },
                    };

                    const theme = themes[preset];

                    if (!theme) {
                        return;
                    }

                    inputs.primary.value = theme.primaryColor;
                    inputs.secondary.value = theme.secondaryColor;
                    inputs.accent.value = theme.accentColor;
                    inputs.text.value = theme.textColor;
                    inputs.bg.value = theme.bgColor;
                    applyColors();
                });
            });

            const resetButton = document.getElementById('resetThemeColors');
            if (resetButton) {
                resetButton.addEventListener('click', () => {
                    inputs.primary.value = themeDefaults.primaryColor;
                    inputs.secondary.value = themeDefaults.secondaryColor;
                    inputs.accent.value = themeDefaults.accentColor;
                    inputs.text.value = themeDefaults.textColor;
                    inputs.bg.value = themeDefaults.bgColor;
                    applyColors();
                });
            }

            applyColors();
        })();
    </script>
@endpush
