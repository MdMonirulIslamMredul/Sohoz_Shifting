<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Category Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $productCategory->name ?? '') }}"
            required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Slug</label>
        <input type="text" name="slug" class="form-control"
            value="{{ old('slug', $productCategory->slug ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control"
            value="{{ old('sort_order', $productCategory->sort_order ?? 0) }}">
    </div>
    <div class="col-md-4 form-check mt-4">
        <input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', $productCategory->status ?? true) ? 'checked' : '' }}>
        <label class="form-check-label">Active</label>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $productCategory->description ?? '') }}</textarea>
    </div>
</div>
