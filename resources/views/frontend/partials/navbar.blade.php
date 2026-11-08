@php
    $navSetting = $settings ?? null;
    $isHome = request()->routeIs('home');
@endphp
<nav class="navbar navbar-expand-lg {{ $isHome ? 'navbar-home navbar-home-overlay fixed-top' : 'sticky-top' }} border-bottom"
    data-navbar-home="{{ $isHome ? 'true' : 'false' }}">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center py-1" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="{{ $navSetting->site_name ?? 'Sohoz Shifting' }}" style="height: 48px; width: auto; max-width: 190px; object-fit: contain;">
        </a>
        <div class="d-flex align-items-center d-lg-none gap-2">
            <a href="{{ route('bookings.create') }}" class="btn btn-warning btn-sm fw-bold px-3 text-dark" style="background-color: #f59e0b; border: none;">
                বুকিং দিন
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span
                    class="navbar-toggler-icon"></span></button>
        </div>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto gap-lg-2 align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">সেবা সমূহ (Services)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('bookings.track') }}">বুকিং ট্র্যাকিং (Track)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact.index') }}">Contact</a></li>
                <li class="nav-item d-none d-lg-block ms-lg-2">
                    <a href="{{ route('bookings.create') }}" class="btn btn-warning fw-bold px-3 py-2 text-dark shadow-sm" style="background-color: #f59e0b; border: none;">
                        <i class="bi bi-calendar2-check me-1"></i> বুকিং দিন
                    </a>
                </li>

                @guest
                    <li class="nav-item ms-lg-1">
                        <a class="btn btn-outline-secondary btn-sm px-3 py-2 fw-semibold" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> লগইন
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm px-3 py-2 fw-semibold text-white" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> রেজিস্টার
                        </a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle btn btn-light btn-sm px-3 py-2 fw-semibold border text-dark" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle text-primary me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            @if (auth()->user()->is_admin)
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-grid-fill me-2 text-primary"></i>আমার বুকিং ও ড্যাশবোর্ড</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>লগআউট</button>
                                    </form>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
