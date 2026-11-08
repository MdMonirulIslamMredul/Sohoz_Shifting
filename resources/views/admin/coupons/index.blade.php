@extends('admin.layouts.app')
@section('title', 'Coupon & Discount Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Coupons & Promotional Discounts</h4>
        <p class="text-muted small mb-0">গ্রাহকদের জন্য কুপন কোড ও বিশেষ ছাড় তৈরি এবং পরিচালনা করুন</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary fw-semibold">
        <i class="bi bi-plus-circle me-1"></i> Add New Coupon
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Code</th>
                        <th>Title / Description</th>
                        <th>Discount Value</th>
                        <th>Min Order</th>
                        <th>Validity</th>
                        <th>Usage</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-dark fs-6 font-monospace">{{ $coupon->code }}</span>
                            </td>
                            <td>{{ $coupon->title ?: '—' }}</td>
                            <td>
                                <strong>
                                    @if($coupon->discount_type === 'percentage')
                                        {{ $coupon->discount_value }}%
                                        @if($coupon->max_discount)
                                            <span class="text-muted small d-block">(Max ৳{{ number_format($coupon->max_discount) }})</span>
                                        @endif
                                    @else
                                        ৳{{ number_format($coupon->discount_value) }}
                                    @endif
                                </strong>
                            </td>
                            <td>৳{{ number_format($coupon->min_booking_amount) }}</td>
                            <td class="small">
                                @if($coupon->starts_at || $coupon->expires_at)
                                    <div>From: {{ $coupon->starts_at ? $coupon->starts_at->format('d M Y') : 'Anytime' }}</div>
                                    <div>To: {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'No expiry' }}</div>
                                @else
                                    <span class="text-muted">Lifetime</span>
                                @endif
                            </td>
                            <td>
                                <span>{{ $coupon->used_count }}</span>
                                @if($coupon->usage_limit)
                                    <span class="text-muted small">/ {{ $coupon->usage_limit }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No coupons found. Click "Add New Coupon" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($coupons->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection

