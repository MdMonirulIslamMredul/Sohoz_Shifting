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
    .summary-card {
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
    .special-item-badge {
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        background: #f8fafc;
    }
    .special-item-badge.selected {
        border-color: #f59e0b;
        background: #fffbeb;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
    }
    .image-preview-thumb {
        position: relative;
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #cbd5e1;
    }
    .image-preview-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
            <p class="text-muted">প্রয়োজনীয় তথ্য দিয়ে মাত্র ২ মিনিটে বুকিং রিকোয়েস্ট পাঠান (ভাড়া অ্যাডমিনের সাথে আলোচনা সাপেক্ষে নির্ধারিত হবে)</p>
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

        <form method="POST" action="{{ route('bookings.store') }}" id="shiftingBookingForm" enctype="multipart/form-data">
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
                                        <option value="{{ $type->id }}" {{ old('property_type_selection') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name_bn ?: $type->name }}
                                        </option>
                                    @endforeach
                                    <option value="custom" {{ old('property_type_selection') == 'custom' ? 'selected' : '' }}>
                                        ➕ অন্যান্য / কাস্টম প্রপার্টি (নিজে লিখুন)
                                    </option>
                                </select>
                            </div>

                            {{-- Manual Custom Property Type Input --}}
                            <div class="col-md-6" id="customPropertyTypeContainer" style="{{ old('property_type_selection') == 'custom' ? '' : 'display: none;' }}">
                                <label class="form-label fw-bold text-primary">আপনার প্রপার্টির ধরন লিখুন <span class="text-danger">*</span></label>
                                <input type="text" name="custom_property_type" id="customPropertyTypeInput" class="form-control form-control-lg border-primary" 
                                    placeholder="e.g. ৩ তলা ডুপ্লেক্স, গোডাউন, বাথরুম সহ রুম" value="{{ old('custom_property_type') }}">
                                <small class="text-muted">আপনার চাহিদামতো প্রপার্টির নাম লিখুন</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">শিফটিংয়ের তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="shifting_date" id="shiftingDateInput" class="form-control form-control-lg" min="{{ date('Y-m-d') }}" value="{{ old('shifting_date', date('Y-m-d', strtotime('+1 day'))) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">পছন্দের সময় (Time Slot)</label>
                                <select name="preferred_time_slot" id="timeSlotSelect" class="form-select form-select-lg">
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
                                        <input type="text" name="pickup_area" id="pickupAreaInput" class="form-control" placeholder="e.g. মিরপুর ১০, উত্তরা সেক্টর ৩" value="{{ old('pickup_area') }}" required>
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
                                        <input type="text" name="delivery_area" id="deliveryAreaInput" class="form-control" placeholder="e.g. ধানমন্ডি ৩২, বসুন্ধরা আর/এ" value="{{ old('delivery_area') }}" required>
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
                                    <option value="" disabled {{ old('vehicle_type') ? '' : 'selected' }}>গাড়ি নির্বাচন করুন</option>
                                    <option value="pickup_7ft" {{ old('vehicle_type') == 'pickup_7ft' ? 'selected' : '' }}>৭ ফিট ছোট পিকআপ ভ্যান (Small Pickup 7ft)</option>
                                    <option value="covered_9ft" {{ old('vehicle_type') == 'covered_9ft' ? 'selected' : '' }}>৯ ফিট কাভার্ড ভ্যান (Covered Van 9ft)</option>
                                    <option value="covered_14ft" {{ old('vehicle_type') == 'covered_14ft' ? 'selected' : '' }}>১৪ ফিট বড় কাভার্ড ট্রাক (Large Truck 14ft)</option>
                                    <option value="customer_managed" {{ old('vehicle_type') == 'customer_managed' ? 'selected' : '' }}>গ্রাহক নিজ দায়িত্বে ব্যবস্থা করবেন</option>
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-bold">দক্ষ লেবার সংখ্যা</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="labour_count" id="labourCount" class="form-control" min="0" max="15" value="{{ old('labour_count', 2) }}">
                                    <span class="input-group-text">জন</span>
                                </div>
                                <small class="text-muted">প্রয়োজনীয় অভিজ্ঞ লোডিং-আনলোডিং শ্রমিকের সংখ্যা</small>
                            </div>
                        </div>
                    </div>

                    {{-- 4. High-Value Special Items --}}
                    <div class="card booking-card p-4 mb-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">৪</span>
                            বিশেষ বা ভারী মালামালের তালিকা (Special Items)
                        </div>
                        <p class="text-muted small mb-3">নিচের বিশেষ মালামাল থাকলে সিলেক্ট করুন, যাতে আমাদের টিম প্রয়োজনীয় প্যাকিং ম্যাটেরিয়াল ও সাবধানতা অবলম্বন করতে পারে:</p>

                        <div class="row g-2 mb-3">
                            @php
                                $presetSpecialItems = [
                                    ['id' => 'item_ac', 'name' => 'স্প্লিট এসি (Split AC)', 'icon' => 'bi-snow'],
                                    ['id' => 'item_fridge', 'name' => 'বড় ফ্রিজ (Deep/Large Refrigerator)', 'icon' => 'bi-box-seam'],
                                    ['id' => 'item_safe', 'name' => 'হেভি লোহার সিন্দুক / ভল্ট (Locker/Safe)', 'icon' => 'bi-safe2'],
                                    ['id' => 'item_piano', 'name' => 'পিয়ানো / মিউজিক্যাল ইন্সট্রুমেন্ট', 'icon' => 'bi-music-note-beamed'],
                                    ['id' => 'item_glass', 'name' => 'কাঁচের ডাইনিং টেবিল / শোকেস', 'icon' => 'bi-gem'],
                                    ['id' => 'item_tv', 'name' => '৫৫"+ স্মার্ট টিভি / ওএলইডি টিভি', 'icon' => 'bi-tv'],
                                ];
                            @endphp

                            @foreach($presetSpecialItems as $item)
                                <div class="col-md-6">
                                    <label class="special-item-badge d-flex align-items-center justify-content-between w-100" for="{{ $item['id'] }}">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input special-item-check me-2" type="checkbox" name="special_items[]" 
                                                value="{{ $item['name'] }}" id="{{ $item['id'] }}">
                                            <div>
                                                <i class="bi {{ $item['icon'] }} text-primary me-1"></i>
                                                <span class="fw-semibold text-dark">{{ $item['name'] }}</span>
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-primary border"><i class="bi bi-shield-check me-1"></i>স্পেশাল কেয়ার</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 5. Luggage & Room Photos Upload --}}
                    <div class="card booking-card p-4 mb-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">৫</span>
                            মালামালের ছবি আপলোড (Luggage & Furniture Photos)
                        </div>
                        <p class="text-muted small mb-3">রুমের বা বিশেষ মালামালের ছবি আপলোড করলে অ্যাডমিন সঠিক সাইজের গাড়ি ও নির্ভুল কোটেশন দিতে পারবেন (সর্বোচ্চ ১০টি ছবি):</p>

                        <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light mb-3">
                            <i class="bi bi-cloud-arrow-up fs-1 text-primary mb-2 d-block"></i>
                            <label for="luggageImagesInput" class="btn btn-outline-primary btn-sm px-4 fw-bold mb-2">
                                <i class="bi bi-images me-1"></i> ছবি নির্বাচন করুন
                            </label>
                            <input type="file" name="luggage_images[]" id="luggageImagesInput" class="d-none" multiple accept="image/*">
                            <div class="text-muted small">JPG, PNG, WEBP ফরম্যাট (প্রতিটি সর্বোচ্চ ৫MB)</div>
                        </div>

                        {{-- Image previews container --}}
                        <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2"></div>
                    </div>

                    {{-- 6. Categorized Services & Custom Requests --}}
                    <div class="card booking-card p-4 mb-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">৬</span>
                            প্যাকিং ও অন্যান্য সার্ভিস
                        </div>

                        {{-- Nav tabs for categorized services --}}
                        <ul class="nav nav-pills mb-3" id="serviceTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-semibold" id="packing-tab" data-bs-toggle="pill" data-bs-target="#tab-packing" type="button">
                                    <i class="bi bi-box-seam me-1"></i> প্যাকিং সার্ভিস
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-semibold" id="labour-tab" data-bs-toggle="pill" data-bs-target="#tab-labour" type="button">
                                    <i class="bi bi-people me-1"></i> লেবার সার্ভিস
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-semibold" id="electrical-tab" data-bs-toggle="pill" data-bs-target="#tab-electrical" type="button">
                                    <i class="bi bi-lightning-charge me-1"></i> এসি ও ইলেকট্রিক্যাল
                                </button>
                            </li>
                            @if(count($categorizedServices['others']) > 0)
                                <li class="nav-item">
                                    <button class="nav-link fw-semibold" id="others-tab" data-bs-toggle="pill" data-bs-target="#tab-others" type="button">
                                        <i class="bi bi-grid me-1"></i> অন্যান্য
                                    </button>
                                </li>
                            @endif
                        </ul>

                        <div class="tab-content mb-4" id="serviceTabsContent">
                            {{-- Packing Tab --}}
                            <div class="tab-pane fade show active" id="tab-packing" role="tabpanel">
                                <div class="row g-3">
                                    @forelse($categorizedServices['packing'] as $service)
                                        @include('frontend.bookings.partials.service-card', ['service' => $service])
                                    @empty
                                        <div class="col-12 text-muted small">কোনো নির্দিষ্ট প্যাকিং সার্ভিস পাওয়া যায়নি।</div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Labour Tab --}}
                            <div class="tab-pane fade" id="tab-labour" role="tabpanel">
                                <div class="row g-3">
                                    @forelse($categorizedServices['labour'] as $service)
                                        @include('frontend.bookings.partials.service-card', ['service' => $service])
                                    @empty
                                        <div class="col-12 text-muted small">কোনো নির্দিষ্ট লেবার সার্ভিস পাওয়া যায়নি।</div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Electrical Tab --}}
                            <div class="tab-pane fade" id="tab-electrical" role="tabpanel">
                                <div class="row g-3">
                                    @forelse($categorizedServices['electrical'] as $service)
                                        @include('frontend.bookings.partials.service-card', ['service' => $service])
                                    @empty
                                        <div class="col-12 text-muted small">কোনো নির্দিষ্ট এসি/ইলেকট্রিক্যাল সার্ভিস পাওয়া যায়নি।</div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Others Tab --}}
                            @if(count($categorizedServices['others']) > 0)
                                <div class="tab-pane fade" id="tab-others" role="tabpanel">
                                    <div class="row g-3">
                                        @foreach($categorizedServices['others'] as $service)
                                            @include('frontend.bookings.partials.service-card', ['service' => $service])
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Manual Custom Service Input Box --}}
                        <div class="p-3 bg-light rounded-3 border border-warning">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-pencil-fill text-warning me-1"></i>অন্য কোনো বিশেষ সার্ভিস বা মালামালের নির্দেশনা প্রয়োজন?
                            </label>
                            <textarea name="custom_service_requests" class="form-control" rows="2" 
                                placeholder="e.g. বিশেষ বাবল শিট দিয়ে কাঁচ প্যাক করতে হবে, ওয়াল ড্রিলিং সার্ভিস...">{{ old('custom_service_requests') }}</textarea>
                            <small class="text-muted">আপনার যেকোনো বিশেষ চাহিদা থাকলে এখানে উল্লেখ করুন, আমাদের টিম সে অনুযায়ী প্রস্তুতি নিয়ে আসবে।</small>
                        </div>
                    </div>

                    {{-- 7. Customer Details --}}
                    <div class="card booking-card p-4 bg-white">
                        <div class="form-section-title d-flex align-items-center">
                            <span class="badge bg-primary rounded-circle p-2 me-2">৭</span>
                            আপনার যোগাযোগের তথ্য
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">আপনার পুরো নাম <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control form-control-lg" placeholder="e.g. মোঃ তানভীর আহমেদ" value="{{ old('customer_name', auth()->user()?->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">মোবাইল নাম্বার <span class="text-danger">*</span></label>
                                <input type="tel" name="customer_phone" class="form-control form-control-lg" placeholder="e.g. 017XXXXXXXX" value="{{ old('customer_phone', auth()->user()?->phone) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">হোয়াটসঅ্যাপ নাম্বার (যদি ভিন্ন হয়)</label>
                                <input type="tel" name="customer_whatsapp" class="form-control form-control-lg" placeholder="e.g. 018XXXXXXXX" value="{{ old('customer_whatsapp', auth()->user()?->phone) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">ইমেইল (ঐচ্ছিক)</label>
                                <input type="email" name="customer_email" class="form-control form-control-lg" placeholder="e.g. tanvir@gmail.com" value="{{ old('customer_email', auth()->user()?->email) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Live Booking Summary & Submission --}}
                <div class="col-lg-4">
                    <div class="card summary-card p-4 shadow-lg sticky-top" style="top: 100px;">
                        <h5 class="fw-bold text-warning mb-3">
                            <i class="bi bi-clipboard-check me-2"></i>বুকিং সারাংশ (Summary)
                        </h5>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25 small">
                            <span class="text-light opacity-75">প্রপার্টি:</span>
                            <span class="fw-semibold text-warning" id="summaryProperty">নির্বাচন করুন</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25 small">
                            <span class="text-light opacity-75">শিফটিং তারিখ:</span>
                            <span class="fw-semibold text-light" id="summaryDate">-</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25 small">
                            <span class="text-light opacity-75">টাইম স্লট:</span>
                            <span class="fw-semibold text-light" id="summaryTime">-</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25 small">
                            <span class="text-light opacity-75">রুট (পিকআপ ➔ ডেলিভারি):</span>
                            <span class="fw-semibold text-light text-end" id="summaryRoute">-</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25 small">
                            <span class="text-light opacity-75">গাড়ি ও লেবার:</span>
                            <span class="fw-semibold text-light text-end" id="summaryTransport">-</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25 small">
                            <span class="text-light opacity-75">বিশেষ মালামাল:</span>
                            <span class="fw-semibold text-light" id="summarySpecial">০টি</span>
                        </div>

                        <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-25 small">
                            <span class="text-light opacity-75">অতিরিক্ত সার্ভিস:</span>
                            <span class="fw-semibold text-light" id="summaryServices">০টি নির্বাচিত</span>
                        </div>

                        {{-- Price Notice Box --}}
                        <div class="my-3 p-3 rounded-3 bg-black bg-opacity-35 border border-warning border-opacity-50 text-center">
                            <span class="d-block small text-light opacity-75 mb-1">শিফটিং চার্জ / ভাড়া</span>
                            <div class="badge bg-warning text-dark fs-6 py-2 px-3 fw-bold mb-2">
                                <i class="bi bi-chat-quote-fill me-1"></i>আলোচনা সাপেক্ষে
                            </div>
                            <small class="text-warning d-block fw-semibold mb-2">গ্রাহকের সাথে আলোচনা করে অ্যাডমিন নির্ধারণ করবেন</small>
                            <p class="text-light opacity-75 small mb-0" style="font-size: 0.8rem; line-height: 1.45;">
                                <i class="bi bi-info-circle-fill text-warning me-1"></i>বুকিংয়ের সময় কোনো পেমেন্ট লাগবে না। আমাদের প্রতিনিধি আপনার সাথে কথা বলে সেরা ও সাশ্রয়ী ভাড়া নির্ধারণ করবেন।
                            </p>
                        </div>

                        {{-- Optional Coupon Box --}}
                        <div class="mb-3 p-2 rounded bg-black bg-opacity-25 border border-secondary border-opacity-50">
                            <label class="form-label small fw-bold text-warning mb-1">
                                <i class="bi bi-ticket-perforated me-1"></i>কুপন / প্রোমো কোড (যদি থাকে)
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="coupon_code" id="couponCodeInput" class="form-control text-uppercase" placeholder="e.g. SOHOZ500" value="{{ old('coupon_code') }}">
                                <button type="button" id="applyCouponBtn" class="btn btn-warning fw-bold text-dark">যাচাই</button>
                            </div>
                            <div id="couponMessage" class="small mt-1" style="display: none;"></div>
                        </div>

                        <button type="submit" class="btn gold-btn btn-lg w-100 py-3 shadow fw-bold">
                            <i class="bi bi-send-check-fill me-2"></i>বুকিং রিকোয়েস্ট পাঠান
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-light opacity-75">
                                <i class="bi bi-shield-check text-success me-1"></i>১০০% ফ্রি বুকিং রিকোয়েস্ট | নিরাপদ শিফটিং
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
    const shiftingDateInput = document.getElementById('shiftingDateInput');
    const timeSlotSelect = document.getElementById('timeSlotSelect');
    const pickupAreaInput = document.getElementById('pickupAreaInput');
    const deliveryAreaInput = document.getElementById('deliveryAreaInput');
    const pickupFloor = document.getElementById('pickupFloor');
    const pickupLift = document.getElementById('pickupLift');
    const deliveryFloor = document.getElementById('deliveryFloor');
    const deliveryLift = document.getElementById('deliveryLift');
    const vehicleType = document.getElementById('vehicleType');
    const labourCount = document.getElementById('labourCount');
    const addonCheckboxes = document.querySelectorAll('.addon-service-checkbox');
    const specialItemChecks = document.querySelectorAll('.special-item-check');

    // Summary display elements
    const summaryProperty = document.getElementById('summaryProperty');
    const summaryDate = document.getElementById('summaryDate');
    const summaryTime = document.getElementById('summaryTime');
    const summaryRoute = document.getElementById('summaryRoute');
    const summaryTransport = document.getElementById('summaryTransport');
    const summarySpecial = document.getElementById('summarySpecial');
    const summaryServices = document.getElementById('summaryServices');

    // Coupon elements
    const couponCodeInput = document.getElementById('couponCodeInput');
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    const couponMessage = document.getElementById('couponMessage');

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

    function updateSummary() {
        // 1. Property
        if (summaryProperty) {
            if (propertyTypeSelect.value === 'custom') {
                summaryProperty.innerText = customPropertyTypeInput.value.trim() || 'কাস্টম প্রপার্টি';
            } else if (propertyTypeSelect.selectedIndex >= 0 && propertyTypeSelect.value) {
                summaryProperty.innerText = propertyTypeSelect.options[propertyTypeSelect.selectedIndex].text.trim();
            } else {
                summaryProperty.innerText = 'নির্বাচন করুন';
            }
        }

        // 2. Date
        if (summaryDate) {
            summaryDate.innerText = shiftingDateInput.value || '-';
        }

        // 3. Time slot
        if (summaryTime) {
            summaryTime.innerText = timeSlotSelect.value || '-';
        }

        // 4. Route
        if (summaryRoute) {
            const pArea = pickupAreaInput.value.trim();
            const dArea = deliveryAreaInput.value.trim();
            if (pArea || dArea) {
                summaryRoute.innerText = (pArea || 'পিকআপ') + ' ➔ ' + (dArea || 'ডেলিভারি');
            } else {
                summaryRoute.innerText = '-';
            }
        }

        // 5. Transport (Vehicle & Labour)
        if (summaryTransport) {
            let vText = '';
            if (vehicleType.selectedIndex >= 0 && vehicleType.value) {
                vText = vehicleType.options[vehicleType.selectedIndex].text.trim();
                // Shorten text if needed
                vText = vText.split('(')[0].trim();
            }
            const lText = (labourCount.value || 0) + ' জন লেবার';
            summaryTransport.innerText = (vText ? vText + ' | ' : '') + lText;
        }

        // 6. Special Items
        if (summarySpecial) {
            let count = 0;
            specialItemChecks.forEach(cb => { if (cb.checked) count++; });
            summarySpecial.innerText = count > 0 ? count + 'টি নির্বাচিত' : '০টি';
        }

        // 7. Services
        if (summaryServices) {
            let count = 0;
            addonCheckboxes.forEach(cb => { if (cb.checked) count++; });
            summaryServices.innerText = count > 0 ? count + 'টি নির্বাচিত' : '০টি';
        }
    }

    // Special items styling sync
    specialItemChecks.forEach(cb => {
        cb.addEventListener('change', function () {
            const parent = this.closest('.special-item-badge');
            if (parent) {
                if (this.checked) parent.classList.add('selected');
                else parent.classList.remove('selected');
            }
            updateSummary();
        });
    });

    // Image Upload Preview
    const luggageImagesInput = document.getElementById('luggageImagesInput');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    if (luggageImagesInput && imagePreviewContainer) {
        luggageImagesInput.addEventListener('change', function () {
            imagePreviewContainer.innerHTML = '';
            Array.from(this.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const thumb = document.createElement('div');
                        thumb.className = 'image-preview-thumb shadow-sm';
                        thumb.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                        imagePreviewContainer.appendChild(thumb);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // Coupon Apply Action
    if (applyCouponBtn && couponCodeInput) {
        applyCouponBtn.addEventListener('click', function () {
            const code = couponCodeInput.value.trim();
            if (!code) return;

            couponMessage.style.display = 'block';
            couponMessage.className = 'small text-warning';
            couponMessage.innerText = 'যাচাই করা হচ্ছে...';

            fetch('{{ route("bookings.check-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    couponMessage.className = 'small text-success fw-bold';
                    couponMessage.innerText = data.message;
                } else {
                    couponMessage.className = 'small text-danger';
                    couponMessage.innerText = data.message || 'কুপন কোডটি সঠিক নয়।';
                }
            })
            .catch(() => {
                couponMessage.className = 'small text-danger';
                couponMessage.innerText = 'সার্ভারে সমস্যা হচ্ছে, অনুগ্রহ করে পরে চেষ্টা করুন।';
            });
        });
    }

    // Event listeners for updating live summary
    if (propertyTypeSelect) propertyTypeSelect.addEventListener('change', () => { toggleCustomInput(); updateSummary(); });
    if (customPropertyTypeInput) customPropertyTypeInput.addEventListener('input', updateSummary);
    if (shiftingDateInput) shiftingDateInput.addEventListener('change', updateSummary);
    if (timeSlotSelect) timeSlotSelect.addEventListener('change', updateSummary);
    if (pickupAreaInput) pickupAreaInput.addEventListener('input', updateSummary);
    if (deliveryAreaInput) deliveryAreaInput.addEventListener('input', updateSummary);
    if (pickupFloor) pickupFloor.addEventListener('input', updateSummary);
    if (deliveryFloor) deliveryFloor.addEventListener('input', updateSummary);
    if (vehicleType) vehicleType.addEventListener('change', updateSummary);
    if (labourCount) labourCount.addEventListener('input', updateSummary);
    addonCheckboxes.forEach(cb => cb.addEventListener('change', updateSummary));

    // Initial run
    updateSummary();

    // Meta Pixel InitiateCheckout Event
    if (typeof fbq === 'function') {
        fbq('track', 'InitiateCheckout', {
            content_name: 'Shifting Booking Form',
            content_category: 'Shifting Service',
            currency: 'BDT'
        });
    }
});
</script>
@endpush
