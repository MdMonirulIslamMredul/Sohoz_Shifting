@extends('admin.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="bi bi-arrow-left me-1"></i> Back to Coupons
    </a>
    <h4 class="fw-bold mb-0">Edit Coupon: {{ $coupon->code }}</h4>
</div>

<div class="card border-0 shadow-sm rounded-4" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Coupon Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $coupon->code) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Title / Campaign Note</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $coupon->title) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Discount Type <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-select" required>
                        <option value="fixed" {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (BDT)</option>
                        <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Discount Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Minimum Booking Amount (BDT)</label>
                    <input type="number" step="0.01" name="min_booking_amount" class="form-control" value="{{ old('min_booking_amount', $coupon->min_booking_amount) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Max Discount Limit (for % discount)</label>
                    <input type="number" step="0.01" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon->max_discount) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Total Usage Limit</label>
                    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                    <small class="text-muted">Used so far: {{ $coupon->used_count }} times</small>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch pt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveSwitch" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isActiveSwitch">Coupon is Active</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Update Coupon</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

