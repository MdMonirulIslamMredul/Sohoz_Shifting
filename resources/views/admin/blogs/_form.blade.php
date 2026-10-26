<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control"
            value="{{ old('title', $blog->title ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label">Slug</label><input name="slug" class="form-control"
            value="{{ old('slug', $blog->slug ?? '') }}"></div>
    <div class="col-12"><label class="form-label">Excerpt</label>
        <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
    </div>
    <div class="col-12"><label class="form-label">Body</label>
        <textarea name="body" id="bodyEditor" class="form-control" rows="8">{{ old('body', $blog->body ?? '') }}</textarea>
    </div>
    <div class="col-md-4"><label class="form-label">Meta Title</label><input name="meta_title" class="form-control"
            value="{{ old('meta_title', $blog->meta_title ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">SEO Keywords</label><input name="seo_keywords" class="form-control"
            value="{{ old('seo_keywords', $blog->seo_keywords ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Publish Date</label><input type="datetime-local" name="published_at"
            class="form-control"
            value="{{ old('published_at', isset($blog->published_at) ? $blog->published_at->format('Y-m-d\\TH:i') : '') }}">
    </div>
    <div class="col-12"><label class="form-label">Meta Description</label>
        <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
    </div>
    <div class="col-md-6 form-check mt-4"><input class="form-check-input" type="checkbox" name="is_published"
            value="1" {{ old('is_published', $blog->is_published ?? true) ? 'checked' : '' }}><label
            class="form-check-label">Published</label></div>
    <div class="col-md-6"><label class="form-label">Featured Image</label><input type="file" name="image"
            class="form-control"></div>
</div>
