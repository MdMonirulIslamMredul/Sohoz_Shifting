@extends('admin.layouts.app')
@section('title', 'Add New Partner')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Add New Partner</h4>
            <div class="text-secondary">Upload partner logo and provide details for display on the website.</div>
        </div>
        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Partners
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            {{-- Partner Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-dark">Partner / Company Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="e.g. SMA Solar, Tesla Energy, Siemens" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Website URL --}}
                            <div class="col-md-6">
                                <label for="website_url" class="form-label fw-semibold text-dark">Website URL <small class="text-secondary">(Optional)</small></label>
                                <input type="url" id="website_url" name="website_url"
                                    class="form-control @error('website_url') is-invalid @enderror"
                                    value="{{ old('website_url') }}" placeholder="https://example.com">
                                @error('website_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Sort Order & Status --}}
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label fw-semibold text-dark">Sort Order</label>
                                <input type="number" id="sort_order" name="sort_order" min="0"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    value="{{ old('sort_order', $nextSortOrder) }}" placeholder="0">
                                <div class="form-text text-secondary">Lower numbers appear first in the carousel and list.</div>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 d-flex align-items-center pt-md-3">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status"
                                        id="partnerStatus" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark ms-2" for="partnerStatus">
                                        Active / Visible on website
                                    </label>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold text-dark">Short Description / Collaboration Details <small class="text-secondary">(Optional)</small></label>
                                <textarea id="description" name="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Brief note about the partnership, equipment supplied, or accreditation...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Partner Logo / Image --}}
                            <div class="col-12">
                                <label for="partnerImage" class="form-label fw-semibold text-dark">Partner Logo / Image <span class="text-danger">*</span></label>
                                <input type="file" id="partnerImage" name="image" accept="image/*"
                                    class="form-control @error('image') is-invalid @enderror" required
                                    onchange="previewPartnerImage(this)">
                                <div class="form-text text-secondary">
                                    Recommended format: PNG (transparent background), SVG, or WebP. Max size: 4MB.
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="imagePreviewContainer" class="mt-3 d-none">
                                    <div class="small text-secondary mb-1">Logo Preview:</div>
                                    <div class="p-3 bg-white rounded border d-inline-flex align-items-center justify-content-center"
                                        style="max-width: 220px; max-height: 120px;">
                                        <img id="imagePreview" src="#" alt="Logo Preview"
                                            style="max-width: 100%; max-height: 90px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-lg me-1"></i> Save Partner
                                </button>
                                <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary px-4">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Side Helper Info --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="card-title text-dark fw-bold mb-3">
                        <i class="bi bi-info-circle text-primary me-1"></i> Display Tips
                    </h6>
                    <ul class="text-secondary small ps-3 mb-0 d-flex flex-column gap-2">
                        <li>Logos appear in the continuous infinity carousel directly below the FAQ section on the home page.</li>
                        <li>Logos also feature in the dedicated <code>/partners</code> directory page.</li>
                        <li>Transparent PNG or SVG images with light or neutral colors render cleanly across both light and dark themes.</li>
                        <li>Partners marked as Inactive are hidden from both the home marquee and the public partners page.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewPartnerImage(input) {
                const container = document.getElementById('imagePreviewContainer');
                const preview = document.getElementById('imagePreview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        container.classList.remove('d-none');
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    container.classList.add('d-none');
                    preview.src = '#';
                }
            }
        </script>
    @endpush
@endsection
