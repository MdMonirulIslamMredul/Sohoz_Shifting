<div class="row g-4">
    {{-- Card 1: Page Header & Core Info --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title text-dark fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-person-fill text-primary"></i> Page Header &amp; Overview
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">About Page Title <span class="text-danger">*</span></label>
                        <input name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $about->title ?? '') }}" placeholder="e.g. About Our Company" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-dark">Years of Experience</label>
                        <input name="years_experience" type="number" min="0" class="form-control"
                            value="{{ old('years_experience', $about->years_experience ?? '') }}" placeholder="e.g. 12">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-dark">Establishment Year</label>
                        <input name="establishment_year" type="number" min="1800" max="2500" class="form-control"
                            value="{{ old('establishment_year', $about->establishment_year ?? '') }}" placeholder="e.g. 2012">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark">Page Details / Lead Introduction</label>
                        <textarea name="page_details" class="form-control about-editor" rows="5"
                            placeholder="Enter the main introductory text for the about page...">{{ old('page_details', $about->page_details ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Banner Image</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*">
                        <div class="form-text text-secondary">Recommended: High-resolution wide banner (1800x600 px). Max: 4MB.</div>
                        @if (!empty($about->banner_image ?? null))
                            <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                <img src="{{ asset('storage/' . $about->banner_image) }}" class="img-fluid rounded"
                                    style="max-height: 110px; object-fit: cover;" alt="Banner preview">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_banner_image" value="1"
                                        id="remove_banner_image">
                                    <label class="form-check-label text-danger small fw-semibold" for="remove_banner_image">
                                        <i class="bi bi-trash"></i> Remove current banner
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Key Values <small class="text-secondary">(One per line)</small></label>
                        <textarea name="key_values_text" class="form-control" rows="5"
                            placeholder="Clean Energy Excellence&#10;Customer-First Commitment&#10;Sustainable Innovation&#10;Rigorous Safety & Quality">{{ old('key_values_text', isset($about) && is_array($about->key_values) ? implode(PHP_EOL, $about->key_values) : '') }}</textarea>
                        <div class="form-text text-secondary">Each line will be displayed as a distinct bullet in the values section.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Mission, Vision & History --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title text-dark fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-compass-fill text-success"></i> Purpose, Mission &amp; Vision
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-bullseye text-danger"></i> Our Mission
                        </label>
                        <textarea name="mission" class="form-control" rows="4"
                            placeholder="Describe your company's core purpose and daily objective for clients...">{{ old('mission', $about->mission ?? ($setting->mission ?? '')) }}</textarea>
                        <div class="form-text text-secondary">Displayed on the frontend About page in the highlight mission card.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-eye-fill text-primary"></i> Our Vision
                        </label>
                        <textarea name="vision" class="form-control" rows="4"
                            placeholder="Describe your company's long-term aspiration and future impact...">{{ old('vision', $about->vision ?? ($setting->vision ?? '')) }}</textarea>
                        <div class="form-text text-secondary">Displayed on the frontend About page in the highlight vision card.</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-warning"></i> Our Journey / History
                        </label>
                        <textarea name="history" class="form-control about-editor" rows="6"
                            placeholder="Share the story of how your company was founded, milestones achieved, and key growth points...">{{ old('history', $about->history ?? ($setting->history ?? '')) }}</textarea>
                        <div class="form-text text-secondary">Rich text formatted narrative describing company history on the About page.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: In-Depth Narrative Sections --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title text-dark fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-card-text text-info"></i> In-Depth Content Narratives
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- Narrative 1 + Image 1 --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Details Block 1</label>
                        <textarea name="details1" class="form-control about-editor" rows="5">{{ old('details1', $about->details1 ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Image 1</label>
                        <input type="file" name="image1" class="form-control" accept="image/*">
                        <div class="form-text text-secondary">Featured beside Details Block 1. Recommended: 800x600 px. Max: 4MB.</div>
                        @if (!empty($about->image1 ?? null))
                            <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                <img src="{{ asset('storage/' . $about->image1) }}" class="img-fluid rounded"
                                    style="max-height: 100px; object-fit: cover;" alt="Image 1 preview">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image1" value="1" id="remove_image1">
                                    <label class="form-check-label text-danger small fw-semibold" for="remove_image1">
                                        <i class="bi bi-trash"></i> Remove current image 1
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Narrative 2 + Image 2 --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Details Block 2</label>
                        <textarea name="details2" class="form-control about-editor" rows="5">{{ old('details2', $about->details2 ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Image 2</label>
                        <input type="file" name="image2" class="form-control" accept="image/*">
                        <div class="form-text text-secondary">Featured beside Details Block 2. Recommended: 800x600 px. Max: 4MB.</div>
                        @if (!empty($about->image2 ?? null))
                            <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                <img src="{{ asset('storage/' . $about->image2) }}" class="img-fluid rounded"
                                    style="max-height: 100px; object-fit: cover;" alt="Image 2 preview">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image2" value="1" id="remove_image2">
                                    <label class="form-check-label text-danger small fw-semibold" for="remove_image2">
                                        <i class="bi bi-trash"></i> Remove current image 2
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Narrative 3 & 4 --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Details Block 3</label>
                        <textarea name="details3" class="form-control about-editor" rows="5">{{ old('details3', $about->details3 ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Details Block 4</label>
                        <textarea name="details4" class="form-control about-editor" rows="5">{{ old('details4', $about->details4 ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Footer About Summary --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title text-dark fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-layout-text-window-reverse text-secondary"></i> Footer About Summary
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="about_content" class="form-label fw-semibold text-dark">Footer Company Summary</label>
                        <textarea id="about_content" name="about_content" class="form-control @error('about_content') is-invalid @enderror" rows="3"
                            placeholder="Enter the concise company bio/summary that appears below your logo in the public website footer...">{{ old('about_content', $setting->about_content ?? '') }}</textarea>
                        @error('about_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
                            <div class="small text-secondary">
                                <strong class="text-primary d-block mb-1">Public Footer Placement:</strong>
                                This summary is displayed under the logo in the website's footer across all public pages. If left blank, it automatically falls back to your company intro.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Submit Bar --}}
    <div class="col-12">
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="text-secondary small">
                    All updates reflect on the public About page and footer immediately upon saving.
                </div>
                <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-check-lg fs-5"></i> Save About Page &amp; Content
                </button>
            </div>
        </div>
    </div>
</div>
