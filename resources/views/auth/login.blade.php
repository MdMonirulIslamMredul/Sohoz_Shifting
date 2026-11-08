@extends('frontend.layouts.app')

@section('title', 'লগইন করুন | সহজ শিফটিং')

@section('content')
<section class="py-5 bg-light min-vh-75 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-2 fw-semibold">গ্রাহক পোর্টাল</span>
                        <h4 class="fw-bold mb-1">অ্যাকাউন্টে সাইন ইন করুন</h4>
                        <p class="text-white-50 small mb-0">আপনার বুকিংয়ের লাইভ স্ট্যাটাস, কোটেশন ও ইনভয়েস দেখুন</p>
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

                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">মোবাইল নাম্বার অথবা ইমেইল <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person-check"></i></span>
                                    <input type="text" name="login" class="form-control form-control-lg" placeholder="e.g. 017XXXXXXXX বা your@email.com" value="{{ old('login') }}" required autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">পাসওয়ার্ড <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="আপনার পাসওয়ার্ড দিন" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="remember">মনে রাখুন (Remember me)</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 py-3 fw-bold text-dark shadow-sm" style="background-color: #f59e0b; border: none;">
                                <i class="bi bi-box-arrow-in-right me-1"></i> লগইন করুন
                            </button>
                        </form>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted">কোনো অ্যাকাউন্ট নেই?</span>
                            <a href="{{ route('register') }}" class="fw-bold text-decoration-none ms-1 text-primary">নতুন অ্যাকাউন্ট খুলুন</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
