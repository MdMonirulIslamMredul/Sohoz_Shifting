@php $gallery = $gallery ?? null; @endphp
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control"
            value="{{ old('title', optional($gallery)->title) }}" required></div>
    <div class="col-md-3"><label class="form-label">Type</label><select name="type" class="form-select">
            <option value="photo" {{ old('type', optional($gallery)->type ?? 'photo') === 'photo' ? 'selected' : '' }}>
                Photo
            </option>
            <option value="video" {{ old('type', optional($gallery)->type) === 'video' ? 'selected' : '' }}>Video
            </option>
        </select></div>
    <div class="col-md-3 form-check mt-4"><input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', optional($gallery)->status ?? true) ? 'checked' : '' }}><label
            class="form-check-label">Active</label></div>
    <div class="col-md-6">
        <label class="form-label">Photo (if type photo)</label>
        <input id="galleryImageInput" type="file" name="image" class="form-control" accept="image/*">

        <div class="mt-3">
            @if (!empty($gallery->image ?? null))
                <div class="mb-3">
                    <label class="form-label small text-muted">Current image</label>
                    <div class="border rounded p-2 d-inline-block">
                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="Current gallery image"
                            class="img-fluid" style="max-height: 180px;">
                    </div>
                </div>
            @endif

            <div id="newImagePreviewContainer" class="d-none">
                <label class="form-label small text-muted">New image preview</label>
                <div class="border rounded p-2 d-inline-block">
                    <img id="newImagePreview" src="#" alt="New image preview" class="img-fluid"
                        style="max-height: 180px; display: block;">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6"><label class="form-label">YouTube Link (if type video)</label><input name="video_url"
            class="form-control" value="{{ old('video_url', $gallery->video_url ?? '') }}"></div>
    <div class="col-12"><label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $gallery->description ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('galleryImageInput');
            const previewContainer = document.getElementById('newImagePreviewContainer');
            const previewImage = document.getElementById('newImagePreview');

            if (!imageInput || !previewContainer || !previewImage) {
                return;
            }

            imageInput.addEventListener('change', function() {
                const file = this.files && this.files[0];

                if (!file || !file.type.startsWith('image/')) {
                    previewContainer.classList.add('d-none');
                    previewImage.src = '#';
                    previewImage.alt = '';
                    return;
                }

                previewImage.src = URL.createObjectURL(file);
                previewImage.alt = file.name;
                previewContainer.classList.remove('d-none');
            });
        });
    </script>
@endpush
