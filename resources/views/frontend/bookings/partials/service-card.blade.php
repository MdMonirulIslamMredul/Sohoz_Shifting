<div class="col-md-6">
    <div class="p-3 border rounded-3 h-100 bg-light d-flex align-items-start">
        <div class="form-check w-100">
            <input class="form-check-input addon-service-checkbox" type="checkbox" name="selected_services[]" 
                value="{{ $service->id }}" id="srv_{{ $service->id }}" data-price="{{ ($service->show_price && $service->price) ? $service->price : 0 }}"
                {{ (is_array(old('selected_services')) && in_array($service->id, old('selected_services'))) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-dark ms-2" for="srv_{{ $service->id }}">
                <i class="bi {{ $service->icon ?: 'bi-tools' }} text-primary me-1"></i>
                {{ $service->title }}
            </label>
            @if($service->short_description)
                <div class="text-muted small ms-4">{{ $service->short_description }}</div>
            @endif
            @if($service->show_price && (($service->pricing_type === 'fixed' && $service->price) || ($service->pricing_type === 'range' && $service->price_range)))
                <div class="ms-4 mt-2">
                    @if($service->pricing_type === 'fixed' && $service->price)
                        <span class="badge bg-success py-1 px-2">৳{{ number_format($service->price) }}</span>
                    @elseif($service->pricing_type === 'range' && $service->price_range)
                        <span class="badge bg-primary py-1 px-2">{{ $service->price_range }}</span>
                    @endif
                </div>
            @else
                <div class="ms-4 mt-2 p-2 rounded bg-white border border-secondary border-opacity-25 shadow-sm small">
                    <div class="text-secondary fw-bold" style="font-size: 0.82rem;">
                        <i class="bi bi-calculator text-primary me-1"></i>আনুমানিক খরচ
                    </div>
                    <div class="my-1">
                        <span class="badge bg-warning text-dark fw-bold">
                            <i class="bi bi-telephone-fill me-1"></i>কল করে জানানো হবে
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

