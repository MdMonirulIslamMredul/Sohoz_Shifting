@extends('admin.layouts.app')
@section('title', 'Add Coupon')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="bi bi-arrow-left me-1"></i> Back to Coupons
    </a>
    <h4 class="fw-bold mb-0">Create Promotional Coupon</h4>
</div>

<div class="card border-0 shadow-sm rounded-4" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Coupon Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. SOHOZ500" value="{{ old('code') }}" required>
                    <small class="text-muted">Customers will enter this code at booking checkout</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Title / Campaign Note</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Eid Shifting Offer" value="{{ old('title') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Discount Type <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-select" required>
                        <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (BDT)</option>
                        <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Discount Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="discount_value" class="form-control" placeholder="e.g. 500 or 10" value="{{ old('discount_value') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Minimum Booking Amount (BDT)</label>
                    <input type="number" step="0.01" name="min_booking_amount" class="form-control" placeholder="e.g. 3000" value="{{ old('min_booking_amount', 0) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Max Discount Limit (for % discount)</label>
                    <input type="number" step="0.01" name="max_discount" class="form-control" placeholder="e.g. 1500" value="{{ old('max_discount') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Total Usage Limit</label>
                    <input type="number" name="usage_limit" class="form-control" placeholder="e.g. 100 (Leave blank for unlimited)" value="{{ old('usage_limit') }}">
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch pt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveSwitch" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isActiveSwitch">Coupon is Active</label>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Create Coupon</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

