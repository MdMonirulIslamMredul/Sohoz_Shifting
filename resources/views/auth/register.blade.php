@extends('frontend.layouts.app')

@section('title', 'রেজিস্ট্রেশন করুন | সহজ শিফটিং')

@section('content')
<section class="py-5 bg-light min-vh-75 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-2 fw-semibold">গ্রাহক অ্যাকাউন্ট</span>
                        <h4 class="fw-bold mb-1">নতুন অ্যাকাউন্ট তৈরি করুন</h4>
                        <p class="text-white-50 small mb-0">শিফটিং বুকিং, কোটেশন ও ইনভয়েস দেখার জন্য সাইন আপ করুন</p>
                    </div>

                    <div class="card-body p-4 p-md-5 bg-white">
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3 mb-4">
                                <ul class="mb-0 small ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">আপনার পুরো নাম <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control form-control-lg" placeholder="e.g. Monirul Islam" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">মোবাইল নাম্বার <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" name="phone" class="form-control form-control-lg" placeholder="e.g. 017XXXXXXXX" value="{{ old('phone') }}" required>
                                </div>
                                <small class="text-muted">এই নাম্বারে আপনি লগইন এবং বুকিং আপডেট পাবেন</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">ইমেইল অ্যাড্রেস <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="e.g. monirulislam@gmail.com" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">বর্তমান ঠিকানা (ঐচ্ছিক)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" name="address" class="form-control" placeholder="e.g. মিরপুর, ঢাকা" value="{{ old('address') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="কমপক্ষে ৬ অক্ষর" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="পাসওয়ার্ড পুনরায় লিখুন" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 py-3 fw-bold text-dark shadow-sm" style="background-color: #f59e0b; border: none;">
                                <i class="bi bi-person-plus-fill me-1"></i> অ্যাকাউন্ট তৈরি করুন
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted">ইতিমধ্যে অ্যাকাউন্ট আছে?</span>
                            <a href="{{ route('login') }}" class="fw-bold text-decoration-none ms-1 text-primary">এখানে লগইন করুন</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
