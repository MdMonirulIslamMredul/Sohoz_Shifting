@extends('frontend.layouts.app')

@section('title', 'অনলাইন বুকিং দিন | সহজ শিফটিং')

@push('styles')
<style>
    .booking-card {
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        border: 1px solid #e2e8f0;
    }
    .form-section-title {
        color: #0f172a;
        font-weight: 700;
        font-size: 1.15rem;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 0.5rem;
        margin-bottom: 1.25rem;
    }
    .cost-preview-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        border-radius: 16px;
    }
    .gold-btn {
        background-color: #f59e0b;
        color: #0f172a;
        font-weight: 700;
        border: none;
        transition: all 0.2s ease-in-out;
    }
    .gold-btn:hover {
        background-color: #d97706;
        color: #ffffff;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        {{-- Breadcrumb & Title --}}
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6 mb-2">সহজ ও দ্রুত বুকিং</span>
            <h1 class="fw-bold text-dark display-6 mb-2">বাসা ও অফিস শিফটিং বুকিং দিন</h1>
            <p class="text-muted">প্রয়োজনীয় তথ্য দিয়ে মাত্র ২ মিনিটে ফ্রি কোটেশন ও বুকিং সম্পন্ন করুন</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4 rounded-3 shadow-sm">
                <h6 class="fw-bold mb-2">অনুগ্রহ করে নিচের তথ্যগুলো সঠিকভাবে পূরণ করুন:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('bookings.store') }}" id="shiftingBookingForm">
            @csrf
            <div class="row g-4">
                {{-- Left Column: Form Fields --}}
                <div class="col-lg-8">
                    {{-- 1. Property Type & Moving Schedule --}}
                    <div class="card booking-card p-4 mb-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">১</span>
                            বাসা / অফিসের ধরন ও তারিখ
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">বাসা / অফিসের ধরন নির্বাচন করুন <span class="text-danger">*</span></label>
                                <select name="property_type_selection" id="propertyTypeSelect" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>নির্বাচন করুন</option>
                                    @foreach($propertyTypes as $type)
                                        <option value="{{ $type->id }}" data-price="{{ $type->base_price ?? 4000 }}" {{ old('property_type_selection') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name_bn ?: $type->name }}
                                        </option>
                                    @endforeach
                                    <option value="custom" {{ old('property_type_selection') == 'custom' ? 'selected' : '' }}>
                                        ➕ অন্যান্য / কাস্টম প্রপার্টি (নিজে লিখুন)
                                    </option>
                                </select>
                            </div>

                            {{-- Manual Custom Property Type Input (Hidden unless "custom" is selected) --}}
                            <div class="col-md-6" id="customPropertyTypeContainer" style="{{ old('property_type_selection') == 'custom' ? '' : 'display: none;' }}">
                                <label class="form-label fw-bold text-primary">আপনার প্রপার্টির ধরন লিখুন <span class="text-danger">*</span></label>
                                <input type="text" name="custom_property_type" id="customPropertyTypeInput" class="form-control form-control-lg border-primary" 
                                    placeholder="e.g. ৩ তলা ডুপ্লেক্স, গোডাউন, বাথরুম সহ রুম" value="{{ old('custom_property_type') }}">
                                <small class="text-muted">আপনার চাহিদামতো প্রপার্টির নাম লিখুন</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">শিফটিংয়ের তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="shifting_date" class="form-control form-control-lg" min="{{ date('Y-m-d') }}" value="{{ old('shifting_date', date('Y-m-d', strtotime('+1 day'))) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">পছন্দের সময় (Time Slot)</label>
                                <select name="preferred_time_slot" class="form-select form-select-lg">
                                    <option value="সকাল (৮টা - ১২টা)" {{ old('preferred_time_slot') == 'সকাল (৮টা - ১২টা)' ? 'selected' : '' }}>সকাল (৮টা - ১২টা)</option>
                                    <option value="দুপুর (১২টা - ৪টা)" {{ old('preferred_time_slot') == 'দুপুর (১২টা - ৪টা)' ? 'selected' : '' }}>দুপুর (১২টা - ৪টা)</option>
                                    <option value="বিকেল (৪টা - রাত ৮টা)" {{ old('preferred_time_slot') == 'বিকেল (৪টা - রাত ৮টা)' ? 'selected' : '' }}>বিকেল (৪টা - রাত ৮টা)</option>
                                    <option value="রাত (৮টা - রাত ১১টা)" {{ old('preferred_time_slot') == 'রাত (৮টা - রাত ১১টা)' ? 'selected' : '' }}>রাত (৮টা - রাত ১১টা)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Route Details (Pickup & Delivery) --}}
                    <div class="card booking-card p-4 mb-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">২</span>
                            শিফটিং লোকেশন (বর্তমান বাসা ও নতুন বাসা)
                        </div>

                        <div class="row g-4">
                            {{-- Pickup / Origin --}}
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i> বর্তমান বাসা (Pickup)</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">এলাকা / থানা <span class="text-danger">*</span></label>
                                        <input type="text" name="pickup_area" class="form-control" placeholder="e.g. মিরপুর ১০, উত্তরা সেক্টর ৩" value="{{ old('pickup_area') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">বিস্তারিত ঠিকানা <span class="text-danger">*</span></label>
                                        <textarea name="pickup_address" class="form-control" rows="2" placeholder="বাড়ি নং, রোড নং, ল্যান্ডমার্ক..." required>{{ old('pickup_address') }}</textarea>
                                    </div>

                                    <div class="row g-2 align-items-center">
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">ফ্লোর / তলা</label>
                                            <input type="number" name="pickup_floor" id="pickupFloor" class="form-control" min="0" max="40" value="{{ old('pickup_floor', 1) }}">
                                        </div>
                                        <div class="col-6 pt-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="pickup_has_lift" id="pickupLift" value="1" {{ old('pickup_has_lift') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold small" for="pickupLift">লিফট আছে</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Delivery / Destination --}}
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt-fill text-success me-1"></i> নতুন বাসা (Delivery)</h6>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">এলাকা / থানা <span class="text-danger">*</span></label>
                                        <input type="text" name="delivery_area" class="form-control" placeholder="e.g. ধানমন্ডি ৩২, বসুন্ধরা আর/এ" value="{{ old('delivery_area') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">বিস্তারিত ঠিকানা <span class="text-danger">*</span></label>
                                        <textarea name="delivery_address" class="form-control" rows="2" placeholder="বাড়ি নং, রোড নং, ফ্ল্যাট নং..." required>{{ old('delivery_address') }}</textarea>
                                    </div>

                                    <div class="row g-2 align-items-center">
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">ফ্লোর / তলা</label>
                                            <input type="number" name="delivery_floor" id="deliveryFloor" class="form-control" min="0" max="40" value="{{ old('delivery_floor', 1) }}">
                                        </div>
                                        <div class="col-6 pt-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="delivery_has_lift" id="deliveryLift" value="1" {{ old('delivery_has_lift') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold small" for="deliveryLift">লিফট আছে</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Vehicle & Staffing --}}
                    <div class="card booking-card p-4 mb-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">৩</span>
                            গাড়ি ও লেবার পছন্দ
                        </div>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fw-bold">পরিবহনের জন্য গাড়ি <span class="text-danger">*</span></label>
                                <select name="vehicle_type" id="vehicleType" class="form-select form-select-lg" required>
                                    <option value="" disabled {{ old('vehicle_type') ? '' : 'selected' }} data-price="0">গাড়ি নির্বাচন করুন</option>
                                    <option value="pickup_7ft" data-price="2500" {{ old('vehicle_type') == 'pickup_7ft' ? 'selected' : '' }}>৭ ফিট ছোট পিকআপ ভ্যান (Small Pickup - ৳২,৫০০)</option>
                                    <option value="covered_9ft" data-price="3800" {{ old('vehicle_type') == 'covered_9ft' ? 'selected' : '' }}>৯ ফিট কাভার্ড ভ্যান (Covered Van - ৳৩,৮০০)</option>
                                    <option value="covered_14ft" data-price="5500" {{ old('vehicle_type') == 'covered_14ft' ? 'selected' : '' }}>১৪ ফিট বড় কাভার্ড ট্রাক (Large Truck - ৳৫,৫০০)</option>
                                    <option value="customer_managed" data-price="0" {{ old('vehicle_type') == 'customer_managed' ? 'selected' : '' }}>গ্রাহক নিজ দায়িত্বে ব্যবস্থা করবেন</option>
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-bold">দক্ষ লেবার সংখ্যা</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="labour_count" id="labourCount" class="form-control" min="0" max="15" value="{{ old('labour_count', 0) }}">
                                    <span class="input-group-text">জন</span>
                                </div>
                                <small class="text-muted">প্রতিজন অভিজ্ঞ লেবার ৳৮০০ (লোডিং-আনলোডিং)</small>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Dynamic Services & Manual Custom Services --}}
                    <div class="card booking-card p-4 mb-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">৪</span>
                            সার্ভিস ও বিশেষ নির্দেশনা
                        </div>

                        <p class="text-muted small mb-3">আপনার শিফটিংয়ের সুবিধার্থে নিচের প্রয়োজনীয় সার্ভিসগুলো বেছে নিন:</p>

                        <div class="row g-3 mb-4">
                            @foreach($addonServices as $service)
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
                                                    <div class="text-muted" style="font-size: 0.78rem; line-height: 1.35;">
                                                        মূল্য দূরত্ব, ফ্লোর ও মালের পরিমাণের উপর নির্ভর করে।
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Manual Custom Service Input Box --}}
                        <div class="p-3 bg-light rounded-3 border border-warning">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-pencil-fill text-warning me-1"></i>অন্য কোনো বিশেষ সার্ভিস বা মালামালের নির্দেশনা প্রয়োজন?
                            </label>
                            <textarea name="custom_service_requests" class="form-control" rows="2" 
                                placeholder="e.g. একটি বড় পিয়ানো আছে, কাঁচের ডাইনিং টেবিল বিশেষ বাবল শিট দিয়ে প্যাক করতে হবে, ওয়াল ড্রিলিং সার্ভিস...">{{ old('custom_service_requests') }}</textarea>
                            <small class="text-muted">আপনার যেকোনো বিশেষ চাহিদা থাকলে এখানে উল্লেখ করুন, আমাদের টিম সে অনুযায়ী প্রস্তুতি নিয়ে আসবে।</small>
                        </div>
                    </div>

                    {{-- 5. Customer Details --}}
                    <div class="card booking-card p-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">৫</span>
                            আপনার যোগাযোগের তথ্য
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">আপনার পুরো নাম <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control form-control-lg" placeholder="e.g. মোঃ তানভীর আহমেদ" value="{{ old('customer_name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">মোবাইল নাম্বার <span class="text-danger">*</span></label>
                                <input type="tel" name="customer_phone" class="form-control form-control-lg" placeholder="e.g. 017XXXXXXXX" value="{{ old('customer_phone') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">হোয়াটসঅ্যাপ নাম্বার (যদি ভিন্ন হয়)</label>
                                <input type="tel" name="customer_whatsapp" class="form-control form-control-lg" placeholder="e.g. 018XXXXXXXX" value="{{ old('customer_whatsapp') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">ইমেইল (ঐচ্ছিক)</label>
                                <input type="email" name="customer_email" class="form-control form-control-lg" placeholder="e.g. tanvir@gmail.com" value="{{ old('customer_email') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Live Estimate & Submit --}}
                <div class="col-lg-4">
                    <div class="card cost-preview-card p-4 shadow-lg sticky-top" style="top: 100px;">
                        <h5 class="fw-bold text-warning mb-3">
                            <i class="bi bi-calculator me-1"></i>সম্ভাব্য খরচ (Live Estimate)
                        </h5>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                            <span class="text-light opacity-75">প্রপার্টি বেস রেট:</span>
                            <span class="fw-bold" id="estBaseRate">৳০</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                            <span class="text-light opacity-75">সিঁড়ি ও ফ্লোর চার্জ:</span>
                            <span class="fw-bold" id="estFloorRate">৳০</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                            <span class="text-light opacity-75">কাভার্ড ভ্যান / গাড়ি:</span>
                            <span class="fw-bold" id="estVehicleRate">৳০</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                            <span class="text-light opacity-75">লেবার চার্জ:</span>
                            <span class="fw-bold" id="estLabourRate">৳০</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25">
                            <span class="text-light opacity-75">অতিরিক্ত সার্ভিস:</span>
                            <span class="fw-bold" id="estAddonRate">৳০</span>
                        </div>

                        <div class="py-3 mt-2 text-center">
                            <span class="d-block small text-light opacity-75">আনুমানিক মোট খরচ</span>
                            <h2 class="display-6 fw-bold text-warning mb-0" id="estTotalCost">৳০</h2>
                            <small class="text-light opacity-50 d-block mt-1" id="bookingEstNote">*সার্ভিস নির্বাচন করলে স্বয়ংক্রিয়ভাবে খরচ হিসেব হবে</small>
                        </div>

                        <button type="submit" class="btn gold-btn btn-lg w-100 py-3 shadow">
                            <i class="bi bi-check-circle-fill me-2"></i>বুকিং নিশ্চিত করুন
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-light opacity-75">
                                <i class="bi bi-shield-check text-success me-1"></i>কোনো লুকানো চার্জ নেই | ১০০% নিরাপদ শিফটিং
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const propertyTypeSelect = document.getElementById('propertyTypeSelect');
    const customPropertyTypeContainer = document.getElementById('customPropertyTypeContainer');
    const customPropertyTypeInput = document.getElementById('customPropertyTypeInput');
    const pickupFloor = document.getElementById('pickupFloor');
    const pickupLift = document.getElementById('pickupLift');
    const deliveryFloor = document.getElementById('deliveryFloor');
    const deliveryLift = document.getElementById('deliveryLift');
    const vehicleType = document.getElementById('vehicleType');
    const labourCount = document.getElementById('labourCount');
    const addonCheckboxes = document.querySelectorAll('.addon-service-checkbox');

    // Display elements
    const estBaseRate = document.getElementById('estBaseRate');
    const estFloorRate = document.getElementById('estFloorRate');
    const estVehicleRate = document.getElementById('estVehicleRate');
    const estLabourRate = document.getElementById('estLabourRate');
    const estAddonRate = document.getElementById('estAddonRate');
    const estTotalCost = document.getElementById('estTotalCost');

    function toggleCustomInput() {
        if (propertyTypeSelect.value === 'custom') {
            customPropertyTypeContainer.style.display = 'block';
            customPropertyTypeInput.setAttribute('required', 'required');
            customPropertyTypeInput.focus();
        } else {
            customPropertyTypeContainer.style.display = 'none';
            customPropertyTypeInput.removeAttribute('required');
        }
    }

    function calculateEstimate() {
        let baseRate = 0;
        if (propertyTypeSelect && propertyTypeSelect.selectedIndex >= 0) {
            const selectedOption = propertyTypeSelect.options[propertyTypeSelect.selectedIndex];
            if (selectedOption && selectedOption.value && selectedOption.value !== '' && selectedOption.dataset.price) {
                baseRate = parseFloat(selectedOption.dataset.price) || 0;
            }
        }

        // Floor penalties (300 BDT per floor above 1st if no lift) - only apply if property is selected
        let floorCost = 0;
        if (baseRate > 0 || (propertyTypeSelect && propertyTypeSelect.value === 'custom')) {
            const pFloor = parseInt(pickupFloor.value) || 1;
            const dFloor = parseInt(deliveryFloor.value) || 1;
            if (!pickupLift.checked && pFloor > 1) {
                floorCost += (pFloor - 1) * 300;
            }
            if (!deliveryLift.checked && dFloor > 1) {
                floorCost += (dFloor - 1) * 300;
            }
        }

        // Vehicle cost (0 if unselected)
        let vCost = 0;
        if (vehicleType && vehicleType.selectedIndex >= 0) {
            const vOption = vehicleType.options[vehicleType.selectedIndex];
            if (vOption && vOption.value) {
                vCost = parseFloat(vOption.dataset.price || 0);
            }
        }

        // Labour cost (800 BDT per labour, 0 if 0)
        const lCount = parseInt(labourCount.value) || 0;
        const lCost = lCount * 800;

        // Addons cost
        let addonCost = 0;
        addonCheckboxes.forEach(cb => {
            if (cb.checked) {
                addonCost += parseFloat(cb.dataset.price || 0);
            }
        });

        function formatBnMoney(amount) {
            return '৳' + Number(amount).toLocaleString('bn-BD');
        }

        estBaseRate.textContent = formatBnMoney(baseRate);
        estFloorRate.textContent = formatBnMoney(floorCost);
        estVehicleRate.textContent = formatBnMoney(vCost);
        estLabourRate.textContent = formatBnMoney(lCost);
        estAddonRate.textContent = formatBnMoney(addonCost);
        estTotalCost.textContent = formatBnMoney(total);

        const estNote = document.getElementById('bookingEstNote');
        if (estNote) {
            if (total > 0) {
                estNote.textContent = '*ফোন কলের মাধ্যমে চূড়ান্ত খরচ নিশ্চিত করা হবে';
            } else {
                estNote.textContent = '*সার্ভিস নির্বাচন করলে স্বয়ংক্রিয়ভাবে খরচ হিসেব হবে';
            }
        }
    }

    propertyTypeSelect.addEventListener('change', function () {
        toggleCustomInput();
        calculateEstimate();
    });

    [pickupFloor, pickupLift, deliveryFloor, deliveryLift, vehicleType, labourCount].forEach(el => {
        el.addEventListener('input', calculateEstimate);
        el.addEventListener('change', calculateEstimate);
    });

    addonCheckboxes.forEach(cb => {
        cb.addEventListener('change', calculateEstimate);
    });

    // Run on init
    toggleCustomInput();
    calculateEstimate();
});
</script>
@endpush
