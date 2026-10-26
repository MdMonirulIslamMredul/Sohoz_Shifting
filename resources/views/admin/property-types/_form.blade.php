<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Name (English) <span class="text-danger">*</span></label>
        <input name="name" class="form-control" value="{{ old('name', $propertyType->name ?? '') }}" placeholder="e.g. 2 Bedroom Flat" required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Name (Bangla) <span class="text-danger">*</span></label>
        <input name="name_bn" class="form-control" value="{{ old('name_bn', $propertyType->name_bn ?? '') }}" placeholder="e.g. ২ বেডরুম">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Slug (Auto-generated if empty)</label>
        <input name="slug" class="form-control" value="{{ old('slug', $propertyType->slug ?? '') }}" placeholder="e.g. 2-bedroom-flat">
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Base Price (BDT)</label>
        <div class="input-group">
            <span class="input-group-text">৳</span>
            <input type="number" step="0.01" name="base_price" class="form-control" value="{{ old('base_price', $propertyType->base_price ?? '') }}" placeholder="e.g. 7500">
        </div>
        <small class="text-muted">Leave empty if negotiable/custom quote</small>
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Bootstrap Icon</label>
        <input name="icon" class="form-control" value="{{ old('icon', $propertyType->icon ?? 'bi-house-door') }}" placeholder="e.g. bi-house-door, bi-building">
        <small class="text-muted">Use <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a> class</small>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Sort Order</label>
        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $propertyType->sort_order ?? 0) }}">
    </div>

    <div class="col-md-6 d-flex align-items-center mt-4">
        <div class="form-check form-switch fs-5">
            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveSwitch" value="1"
                {{ old('is_active', $propertyType->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fs-6 fw-semibold ms-2" for="isActiveSwitch">Active (Show in booking dropdown)</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Short Description / Guidelines</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Brief description of what this property shifting includes...">{{ old('description', $propertyType->description ?? '') }}</textarea>
    </div>
</div>
