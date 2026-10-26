<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Service Title <span class="text-danger">*</span></label>
        <input name="title" class="form-control" value="{{ old('title', $service->title ?? '') }}" placeholder="e.g. এসি খোলা ও ফিটিং" required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Slug (Auto-generated if empty)</label>
        <input name="slug" class="form-control" value="{{ old('slug', $service->slug ?? '') }}" placeholder="e.g. ac-repair-fitting">
    </div>

    {{-- Pricing and Visibility Section --}}
    <div class="col-12">
        <div class="p-3 border rounded bg-light">
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-tag-fill text-primary me-2"></i>Pricing & Visibility Controls (মূল্য ও প্রদর্শন সেটিংস)</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pricing Model</label>
                    <select name="pricing_type" class="form-select">
                        <option value="negotiable" {{ old('pricing_type', $service->pricing_type ?? 'negotiable') == 'negotiable' ? 'selected' : '' }}>আলোচনা সাপেক্ষে / কাস্টম কোট (Negotiable)</option>
                        <option value="range" {{ old('pricing_type', $service->pricing_type ?? '') == 'range' ? 'selected' : '' }}>মূল্য সীমা / রেঞ্জ (e.g. ৳১,৫০০ - ৳২,৫০০)</option>
                        <option value="fixed" {{ old('pricing_type', $service->pricing_type ?? '') == 'fixed' ? 'selected' : '' }}>নির্দিষ্ট রেট / ফিক্সড প্রাইস (Fixed Rate)</option>
                        <option value="free_quote" {{ old('pricing_type', $service->pricing_type ?? '') == 'free_quote' ? 'selected' : '' }}>ফ্রি কোটেশন (Free Quote)</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fixed Price (BDT)</label>
                    <div class="input-group">
                        <span class="input-group-text">৳</span>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $service->price ?? '') }}" placeholder="e.g. 2500">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Price Range / Text</label>
                    <input type="text" name="price_range" class="form-control" value="{{ old('price_range', $service->price_range ?? '') }}" placeholder="e.g. ৳১,৫০০ - ৳৩,০০০">
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="show_price" id="showPriceSwitch" value="1"
                            {{ old('show_price', $service->show_price ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold ms-2" for="showPriceSwitch">
                            প্রাইস ওয়েবসাইটে প্রদর্শন করুন (Show Price Badge on Website)
                        </label>
                    </div>
                    <small class="text-muted d-block ms-4">টিক চিহ্ন না দিলে ওয়েবসাইটে মূল্য গোপন থাকবে / আলোচনা সাপেক্ষে দেখাবে।</small>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_addon_for_booking" id="isAddonSwitch" value="1"
                            {{ old('is_addon_for_booking', $service->is_addon_for_booking ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold ms-2" for="isAddonSwitch">
                            বুকিং ফর্মে অতিরিক্ত সেবা হিসেবে দেখান (Show as Add-on in Booking Form)
                        </label>
                    </div>
                    <small class="text-muted d-block ms-4">গ্রাহক বুকিং করার সময় এই সার্ভিসটি নির্বাচন করতে পারবে।</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Bootstrap Icon</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi {{ old('icon', $service->icon ?? 'bi-box-seam') }}"></i></span>
            <input name="icon" class="form-control" value="{{ old('icon', $service->icon ?? 'bi-box-seam') }}" placeholder="e.g. bi-truck, bi-tools, bi-snow">
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Sort Order</label>
        <input name="sort_order" type="number" class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
    </div>

    <div class="col-md-4 d-flex align-items-center mt-4">
        <div class="form-check me-3">
            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeaturedCheck" value="1"
                {{ old('is_featured', $service->is_featured ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="isFeaturedCheck">Featured</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="status" id="statusCheck" value="1"
                {{ old('status', $service->status ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="statusCheck">Active</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Short Description</label>
        <input name="short_description" class="form-control" value="{{ old('short_description', $service->short_description ?? '') }}" placeholder="1-2 sentences for cards and previews">
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Detailed Description</label>
        <textarea name="description" class="form-control" rows="4" placeholder="Detailed service description...">{{ old('description', $service->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Service Image / Banner</label>
        <input type="file" name="image" class="form-control">
        @if (!empty($service->image))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $service->image) }}" alt="Preview" class="rounded border" style="height: 70px; object-fit: cover;">
            </div>
        @endif
    </div>
</div>
