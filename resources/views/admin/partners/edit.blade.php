@extends('admin.layouts.app')
@section('title', 'Edit Partner')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Edit Partner</h4>
            <div class="text-secondary">Update details or logo for "{{ $partner->name }}".</div>
        </div>
        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Partners
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Partner Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-dark">Partner / Company Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $partner->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Website URL --}}
                            <div class="col-md-6">
                                <label for="website_url" class="form-label fw-semibold text-dark">Website URL <small class="text-secondary">(Optional)</small></label>
                                <input type="url" id="website_url" name="website_url"
                                    class="form-control @error('website_url') is-invalid @enderror"
                                    value="{{ old('website_url', $partner->website_url) }}" placeholder="https://example.com">
                                @error('website_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Sort Order & Status --}}
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label fw-semibold text-dark">Sort Order</label>
                                <input type="number" id="sort_order" name="sort_order" min="0"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    value="{{ old('sort_order', $partner->sort_order) }}">
                                <div class="form-text text-secondary">Lower numbers appear first.</div>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 d-flex align-items-center pt-md-3">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status"
                                        id="partnerStatus" value="1" {{ old('status', $partner->status ? '1' : '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-semibold ms-2" for="partnerStatus">
                                        Active / Visible on website
                                    </label>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold text-dark">Short Description / Collaboration Details <small class="text-secondary">(Optional)</small></label>
                                <textarea id="description" name="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $partner->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Current Logo --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark d-block">Current Logo</label>
                                <div class="p-3 bg-white rounded border d-inline-flex align-items-center justify-content-center"
                                    style="min-width: 140px; min-height: 80px; max-height: 100px;">
                                    <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}"
                                        style="max-height: 70px; max-width: 200px; object-fit: contain;">
                                </div>
                            </div>

                            {{-- Replace Logo --}}
                            <div class="col-12">
                                <label for="partnerImage" class="form-label fw-semibold text-dark">Replace Logo / Image <small class="text-secondary">(Leave blank to keep current)</small></label>
                                <input type="file" id="partnerImage" name="image" accept="image/*"
                                    class="form-control @error('image') is-invalid @enderror"
                                    onchange="previewPartnerImage(this)">
                                <div class="form-text text-secondary">
                                    Uploading a new file replaces the existing logo. Recommended: PNG, SVG, or WebP. Max: 4MB.
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="imagePreviewContainer" class="mt-3 d-none">
                                    <div class="small text-secondary mb-1">New Logo Preview:</div>
                                    <div class="p-3 bg-white rounded border d-inline-flex align-items-center justify-content-center"
                                        style="max-width: 220px; max-height: 120px;">
                                        <img id="imagePreview" src="#" alt="New Logo Preview"
                                            style="max-width: 100%; max-height: 90px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-lg me-1"></i> Update Partner
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

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title text-dark fw-bold mb-3">Partner Meta</h6>
                    <div class="d-flex flex-column gap-2 text-secondary small">
                        <div><strong>ID:</strong> {{ $partner->id }}</div>
                        <div><strong>Created:</strong> {{ $partner->created_at->format('M d, Y H:i') }}</div>
                        <div><strong>Last Updated:</strong> {{ $partner->updated_at->format('M d, Y H:i') }}</div>
                        @if ($partner->website_url)
                            <div class="mt-2">
                                <a href="{{ $partner->website_url }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Visit Partner Site
                                </a>
                            </div>
                        @endif
                    </div>
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
