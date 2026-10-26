@extends('frontend.layouts.app')

@section('title', 'বুকিং ট্র্যাকিং | সহজ শিফটিং')

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                {{-- Search Box --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white text-center">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fs-6 mb-2 d-inline-block">লাইভ ট্র্যাকিং</span>
                    <h2 class="fw-bold text-dark mb-2">আপনার শিফটিং বুকিং ট্র্যাক করুন</h2>
                    <p class="text-muted mb-4">আপনার বুকিং কোড (যেমন: SH-2026-XXXX) অথবা মোবাইল নাম্বার লিখুন</p>

                    <form method="GET" action="{{ route('bookings.track') }}" class="row g-2 justify-content-center">
                        <div class="col-sm-8">
                            <input type="text" name="search" class="form-control form-control-lg" 
                                placeholder="বুকিং কোড বা ফোন নাম্বার..." value="{{ $query }}" required>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                                <i class="bi bi-search me-1"></i> ট্র্যাক করুন
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Result Card --}}
                @if($query && $booking)
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                            <div>
                                <h4 class="fw-bold mb-0 text-primary">{{ $booking->booking_code }}</h4>
                                <div class="text-muted small">বুকিং তারিখ: {{ $booking->created_at->format('d M, Y') }}</div>
                            </div>
                            <span class="{{ $booking->status_badge_class }} fs-6 px-3 py-2">
                                {{ $booking->status_label_bn }}
                            </span>
                        </div>

                        {{-- Visual Stepper --}}
                        <div class="mb-4 py-3">
                            <div class="row text-center g-2">
                                @php
                                    $stages = [
                                        'pending' => 'রিকোয়েস্ট জমা',
                                        'confirmed' => 'কনফার্মড',
                                        'team_assigned' => 'টিম প্রস্তুত',
                                        'in_progress' => 'শিফটিং চলছে',
                                        'completed' => 'সম্পন্ন',
                                    ];
                                    $stageKeys = array_keys($stages);
                                    $currentStageIndex = array_search($booking->status, $stageKeys);
                                    if ($currentStageIndex === false) {
                                        $currentStageIndex = ($booking->status === 'contacted') ? 0 : 0;
                                    }
                                @endphp
                                @foreach($stages as $key => $label)
                                    @php
                                        $index = array_search($key, $stageKeys);
                                        $isPassed = $index <= $currentStageIndex;
                                        $isCurrent = $key === $booking->status;
                                    @endphp
                                    <div class="col">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-1 {{ $isPassed ? 'bg-primary text-white' : 'bg-light text-muted border' }}" style="width: 38px; height: 38px;">
                                            @if($isPassed)
                                                <i class="bi bi-check-lg"></i>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <div class="small fw-semibold {{ $isPassed ? 'text-primary' : 'text-muted' }}">{{ $label }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Details Table --}}
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="text-muted small">গ্রাহকের নাম:</label>
                                    <div class="fw-bold">{{ $booking->customer_name }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small">প্রপার্টি:</label>
                                    <div class="fw-bold">{{ $booking->display_property_type }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small">শিফটিংয়ের তারিখ:</label>
                                    <div class="fw-bold text-dark">{{ $booking->shifting_date->format('d F, Y') }} ({{ $booking->preferred_time_slot }})</div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small">গাড়ি ও লেবার:</label>
                                    <div class="fw-bold">{{ $booking->vehicle_type_label_bn }} | {{ $booking->labour_count }} জন লেবার</div>
                                </div>
                                <div class="col-12 border-top pt-2">
                                    <label class="text-muted small">শিফটিং রুট:</label>
                                    <div class="fw-semibold">
                                        <span class="text-primary">{{ $booking->pickup_area }} ({{ $booking->pickup_floor }} তলা)</span>
                                        <i class="bi bi-arrow-right mx-2"></i>
                                        <span class="text-success">{{ $booking->delivery_area }} ({{ $booking->delivery_floor }} তলা)</span>
                                    </div>
                                </div>

                                @if($booking->assigned_supervisor_name)
                                    <div class="col-12 border-top pt-2">
                                        <div class="p-2 bg-white rounded border">
                                            <span class="fw-bold text-dark">টিম সুপারভাইজার:</span> {{ $booking->assigned_supervisor_name }}
                                            @if($booking->assigned_supervisor_phone)
                                                <a href="tel:{{ $booking->assigned_supervisor_phone }}" class="btn btn-sm btn-outline-success ms-2 py-0">
                                                    <i class="bi bi-telephone"></i> কল করুন
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($query)
                    <div class="alert alert-warning text-center rounded-3 p-4">
                        <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                        <strong>কোনো বুকিং রেকর্ড পাওয়া যায়নি!</strong>
                        <p class="mb-0 text-muted small mt-1">অনুগ্রহ করে আপনার সঠিক বুকিং কোড বা ফোন নাম্বার প্রদান করুন অথবা আমাদের হটলাইনে কল দিন।</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
