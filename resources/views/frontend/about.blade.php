@extends('frontend.layouts.app')

@section('title', $about?->title ?? ($setting->about_title ?? 'About Us'))

@section('content')
    @php
        $bannerImage = !empty($about?->banner_image)
            ? asset('storage/' . $about->banner_image)
            : 'https://images.unsplash.com/photo-1497440001374-f26997328c1b?auto=format&fit=crop&w=1800&q=80';
        $image1 = !empty($about?->image1)
            ? asset('storage/' . $about->image1)
            : 'https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=1200&q=80';
        $image2 = !empty($about?->image2)
            ? asset('storage/' . $about->image2)
            : 'https://images.unsplash.com/photo-1559302504-64aae6ca6b6d?auto=format&fit=crop&w=1200&q=80';
        $keyValues = collect($about?->key_values ?? [])
            ->filter()
            ->values();
    @endphp

    <section class="py-5 text-white"
        style="position: relative; background: url('{{ $bannerImage }}') center/cover no-repeat;">
        <div
            style="position: absolute; inset: 0; background: linear-gradient(110deg, rgba(15, 23, 42, 0.90), rgba(245, 158, 11, 0.28));">
        </div>
        <div class="container position-relative py-3" data-aos="fade-up">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">আমাদের পরিচিতি</span>
            <h1 class="display-5 fw-bold mb-3">{{ $about?->title ?? ($setting->about_title ?? 'সহজ শিফটিং সম্পর্কে') }}</h1>
            <p class="lead mb-0 text-white-50" style="max-width: 760px;">
                {!! $about?->page_details ??
                    ($setting->about_content ?? 'ঢাকা মহানগরী সহ সারাদেশে দ্রুত, নিরাপদ ও ঝামেলামুক্ত বাসা-অফিস শিফটিং সেবায় আপনার নির্ভরযোগ্য সঙ্গী।') !!}
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            {{-- Mission & Vision Section --}}
            @php
                $missionText = $about?->mission ?? ($setting->mission ?? null);
                $visionText = $about?->vision ?? ($setting->vision ?? null);
                $historyText = $about?->history ?? ($setting->history ?? null);
            @endphp

            @if ($missionText || $visionText)
                <div class="row g-4 mb-5" data-aos="fade-up">
                    @if ($missionText)
                        <div class="{{ $visionText ? 'col-md-6' : 'col-12' }}">
                            <div class="card h-100 p-4 border-0 shadow-sm rounded-4 position-relative overflow-hidden"
                                style="border-top: 4px solid #f59e0b !important;">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning-subtle text-warning"
                                        style="width: 48px; height: 48px; font-size: 1.5rem;">
                                        <i class="bi bi-bullseye"></i>
                                    </div>
                                    <h3 class="h4 fw-bold mb-0">Our Mission <span class="fs-6 text-muted fw-normal">(আমাদের লক্ষ্য)</span></h3>
                                </div>
                                <div class="text-secondary leading-relaxed fs-6">
                                    {!! nl2br(e($missionText)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($visionText)
                        <div class="{{ $missionText ? 'col-md-6' : 'col-12' }}">
                            <div class="card h-100 p-4 border-0 shadow-sm rounded-4 position-relative overflow-hidden"
                                style="border-top: 4px solid #0f172a !important;">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary"
                                        style="width: 48px; height: 48px; font-size: 1.5rem;">
                                        <i class="bi bi-eye-fill"></i>
                                    </div>
                                    <h3 class="h4 fw-bold mb-0">Our Vision <span class="fs-6 text-muted fw-normal">(আমাদের ভিশন)</span></h3>
                                </div>
                                <div class="text-secondary leading-relaxed fs-6">
                                    {!! nl2br(e($visionText)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="row g-4 align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="{{ $image1 }}" alt="About Image 1" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 420px; object-fit: cover;">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card p-4 p-lg-5 h-100 border-0 shadow-sm rounded-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-warning fw-semibold small">
                            <i class="bi bi-box-seam-fill"></i> নিরাপদ প্যাকিং ও স্থানান্তর
                        </div>
                        <h3 class="h4 fw-bold mb-3">নিরাপদ হ্যান্ডলিং পদ্ধতি</h3>
                        <div class="text-secondary leading-relaxed">{!! $about?->details1 ?? '<p>উন্নত বাবল শিট, স্ট্রেচ র‍্যাপ ও কার্টন বক্সের মাধ্যমে মালামালের শতভাগ সুরক্ষা নিশ্চিত করা হয়।</p>' !!}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-center mt-1">
                <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                    <img src="{{ $image2 }}" alt="About Image 2" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 420px; object-fit: cover;">
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <div class="card p-4 p-lg-5 h-100 border-0 shadow-sm rounded-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-success fw-semibold small">
                            <i class="bi bi-people-fill"></i> প্রশিক্ষিত ও ভদ্র টিম
                        </div>
                        <h3 class="h4 fw-bold mb-3">দক্ষ কর্মী বাহিনী</h3>
                        <div class="text-secondary leading-relaxed">{!! $about?->details2 ?? '<p>আমাদের প্রতিটি কর্মী শিফটিং কাজে প্রশিক্ষিত এবং ভদ্র আচরণের মাধ্যমে গ্রাহকের মন জয় করতে সচেষ্ট।</p>' !!}</div>
                    </div>
                </div>
            </div>

            {{-- Optional Journey / History Section --}}
            @if ($historyText)
                <div class="row g-4 mt-1" data-aos="fade-up">
                    <div class="col-12">
                        <div class="card p-4 p-lg-5 border-0 shadow-sm rounded-4 position-relative overflow-hidden"
                            style="border-left: 5px solid var(--bs-warning, #ffc107) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning-subtle text-warning"
                                    style="width: 48px; height: 48px; font-size: 1.5rem;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <h3 class="h4 fw-bold mb-0">Our Journey &amp; History <span class="fs-6 text-muted fw-normal">(আমাদের অগ্রযাত্রা)</span></h3>
                            </div>
                            <div class="text-secondary leading-relaxed">
                                {!! $historyText !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-4 mt-1">
                <div class="col-md-6" data-aos="fade-up">
                    <div class="card p-4 h-100 border-0 shadow-sm rounded-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-warning fw-semibold small">
                            <i class="bi bi-truck-front-fill"></i> আধুনিক পরিবহন ব্যবস্থা
                        </div>
                        <h4 class="h5 fw-bold mb-3">পরিচ্ছন্ন কাভার্ড ভ্যান ও পিকআপ</h4>
                        <div class="text-secondary leading-relaxed">{!! $about?->details3 ?? '<p>বৃষ্টি বা রোদ থেকে মালামালকে সম্পূর্ণ সুরক্ষিত রাখতে আধুনিক কাভার্ড ট্রাকের ব্যবস্থা রয়েছে।</p>' !!}</div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card p-4 h-100 border-0 shadow-sm rounded-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-info fw-semibold small">
                            <i class="bi bi-headset"></i> সার্বক্ষণিক যোগাযোগ
                        </div>
                        <h4 class="h5 fw-bold mb-3">২৪/৭ কাস্টমার সাপোর্ট ও কোঅর্ডিনেশন</h4>
                        <div class="text-secondary leading-relaxed">{!! $about?->details4 ?? '<p>বুকিং থেকে শুরু করে নতুন বাসায় মাল পৌঁছানো পর্যন্ত সবসময় ডেডিকেটেড শিফটিং অফিসার সহায়তায় নিয়োজিত।</p>' !!}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-md-4" data-aos="zoom-in">
                    <div class="card p-4 text-center h-100 border-0 shadow-sm rounded-4">
                        <h3 class="mb-1 text-warning fw-bold">{{ $about?->years_experience ?? 8 }}+</h3>
                        <p class="mb-0 text-secondary">বছরের অভিজ্ঞতা</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="80">
                    <div class="card p-4 text-center h-100 border-0 shadow-sm rounded-4">
                        <h3 class="mb-1 text-dark fw-bold">{{ $about?->establishment_year ?? '২০১৮' }}</h3>
                        <p class="mb-0 text-secondary">প্রতিষ্ঠিত</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="160">
                    <div class="card p-4 h-100 border-0 shadow-sm rounded-4">
                        <h5 class="mb-2 fw-bold">Key Values</h5>
                        @if ($keyValues->isNotEmpty())
                            <ul class="mb-0 ps-3 text-secondary">
                                @foreach ($keyValues as $value)
                                    <li>{{ $value }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0 text-secondary">Add key values from admin panel to show them here.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
