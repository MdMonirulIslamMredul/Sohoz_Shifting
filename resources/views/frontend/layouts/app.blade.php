<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings?->meta_title ?? 'সহজ শিফটিং | বাসা ও অফিস শিফটিং সার্ভিস')</title>
    <meta name="description" content="@yield('meta_description', $settings?->meta_description ?? 'ঢাকা মহানগরী সহ সারাদেশে দ্রুত ও নিরাপদ বাসা-অফিস শিফটিং, দক্ষ লেবার ও কাভার্ড ভ্যান সার্ভিস')">
    <meta name="keywords" content="@yield('meta_keywords', $settings?->seo_keywords ?? 'বাসা শিফটিং, অফিস শিফটিং, moving service dhaka, sohoz shifting, pickup van, house shifting bd')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @if ($settings?->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $settings->favicon_path) }}">
    @else
        <link rel="icon" href="{{ asset('images/logo.png') }}">
    @endif
    <style>
        :root {
            --primary-color: {{ $settings?->primary_color ?? '#0d6efd' }};
            --secondary-color: {{ $settings?->secondary_color ?? '#6c757d' }};
            --accent-color: {{ $settings?->accent_color ?? '#f39c12' }};
            --text-color: {{ $settings?->text_color ?? '#333333' }};
            --bg-color: {{ $settings?->bg_color ?? '#ffffff' }};
            --brand: var(--primary-color);
            --brand-2: var(--accent-color);
            --ink: var(--text-color);
            --bg: var(--bg-color);
            --surface-color: #ffffff;
            --surface-alt-color: #f8fafc;
            --border-color: rgba(15, 23, 42, 0.08);
            --muted-color: #64748b;
            --navbar-bg: rgba(255, 255, 255, 0.88);
            --footer-bg: #0b1324;
            --footer-text: #cbd5e1;
            --page-gradient: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }

        body {
            font-family: 'Hind Siliguri', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        html[data-theme='light'] {
            --surface-color: #ffffff;
            --surface-alt-color: #f4f8fb;
            --border-color: rgba(15, 23, 42, 0.08);
            --muted-color: #5f6b7a;
            --navbar-bg: rgba(255, 255, 255, 0.94);
            --footer-bg: #0f172a;
            --footer-text: #dbe4ee;
            --page-gradient: linear-gradient(180deg, #f8fafc 0%, #eef5ff 100%);
        }

        html[data-theme='dark'] {
            --surface-color: #0f172a;
            --surface-alt-color: #111827;
            --border-color: rgba(148, 163, 184, 0.18);
            --muted-color: #94a3b8;
            --navbar-bg: rgba(15, 23, 42, 0.92);
            --footer-bg: #020617;
            --footer-text: #e2e8f0;
            --page-gradient: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color-scheme: dark;
        }

        html[data-theme='dark'] body,
        html[data-theme='dark'] body * {
            color: #e5e7eb !important;
        }

        html[data-theme='dark'] h1,
        html[data-theme='dark'] h2,
        html[data-theme='dark'] h3,
        html[data-theme='dark'] h4,
        html[data-theme='dark'] h5,
        html[data-theme='dark'] h6,
        html[data-theme='dark'] p,
        html[data-theme='dark'] li,
        html[data-theme='dark'] label,
        html[data-theme='dark'] small,
        html[data-theme='dark'] strong,
        html[data-theme='dark'] span,
        html[data-theme='dark'] td,
        html[data-theme='dark'] th,
        html[data-theme='dark'] dt,
        html[data-theme='dark'] dd,
        html[data-theme='dark'] .form-text,
        html[data-theme='dark'] .breadcrumb-item,
        html[data-theme='dark'] .page-link {
            color: #e5e7eb !important;
        }

        html[data-theme='dark'] .text-secondary,
        html[data-theme='dark'] .text-muted {
            color: #cbd5e1 !important;
        }

        html[data-theme='dark'] .bg-white,
        html[data-theme='dark'] .section-modern,
        html[data-theme='dark'] .section-modern-light,
        html[data-theme='dark'] .faq-card,
        html[data-theme='dark'] .modern-card,
        html[data-theme='dark'] .counter-card,
        html[data-theme='dark'] .testimonial-card,
        html[data-theme='dark'] .accordion-item,
        html[data-theme='dark'] .card,
        html[data-theme='dark'] .card-body,
        html[data-theme='dark'] .border,
        html[data-theme='dark'] .border-0.shadow-sm,
        html[data-theme='dark'] .alert-light,
        html[data-theme='dark'] .bg-light,
        html[data-theme='dark'] .table,
        html[data-theme='dark'] .dropdown-menu,
        html[data-theme='dark'] .modal-content,
        html[data-theme='dark'] .offcanvas,
        html[data-theme='dark'] .list-group-item,
        html[data-theme='dark'] .pagination .page-link,
        html[data-theme='dark'] .form-control,
        html[data-theme='dark'] .form-select,
        html[data-theme='dark'] .input-group-text {
            background-color: var(--surface-color) !important;
            color: #e5e7eb !important;
            border-color: var(--border-color) !important;
        }

        html[data-theme='dark'] .section-modern-light,
        html[data-theme='dark'] .faq-accordion .accordion-item,
        html[data-theme='dark'] .bg-light,
        html[data-theme='dark'] .table> :not(caption)>*>* {
            background-color: var(--surface-alt-color) !important;
        }

        html[data-theme='dark'] .navbar,
        html[data-theme='dark'] footer,
        html[data-theme='dark'] .theme-fab {
            border-color: var(--border-color) !important;
        }

        html[data-theme='dark'] .form-control::placeholder,
        html[data-theme='dark'] .form-select::placeholder {
            color: #94a3b8 !important;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--page-gradient);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            backdrop-filter: blur(8px);
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .navbar.navbar-home-overlay {
            background: transparent;
            border-bottom-color: transparent;
            box-shadow: none;
            backdrop-filter: blur(1px);
            z-index: 1035;
        }

        .navbar.navbar-home-overlay .navbar-brand,
        .navbar.navbar-home-overlay .nav-link {
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(2, 6, 23, 0.35);
        }

        .navbar.navbar-home-overlay .nav-link:hover,
        .navbar.navbar-home-overlay .nav-link.active {
            color: #ffffff;
        }

        .navbar.navbar-home-overlay .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.45);
        }

        .navbar.navbar-home-overlay .navbar-toggler-icon {
            filter: invert(1) brightness(200%);
        }

        .navbar.navbar-home-overlay.navbar-scrolled .navbar-toggler {
            border-color: rgba(15, 23, 42, 0.12);
        }

        .navbar.navbar-home-overlay.navbar-scrolled .navbar-toggler-icon {
            filter: none;
        }

        .navbar.navbar-scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.08);
            border-bottom-color: rgba(15, 23, 42, 0.08);
        }

        .navbar.navbar-scrolled .navbar-brand,
        .navbar.navbar-scrolled .nav-link {
            color: var(--text-color);
            text-shadow: none;
        }

        .navbar.navbar-scrolled .nav-link:hover,
        .navbar.navbar-scrolled .nav-link.active {
            color: var(--primary-color);
        }

        .navbar.navbar-home-overlay.navbar-scrolled .navbar-collapse.show {
            background: rgba(255, 255, 255, 0.98);
        }

        html[data-theme='dark'] .navbar.navbar-scrolled {
            background: rgba(15, 23, 42, 0.96);
            border-bottom-color: var(--border-color);
        }

        html[data-theme='dark'] .navbar.navbar-home-overlay.navbar-scrolled .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.18);
        }

        html[data-theme='dark'] .navbar.navbar-home-overlay.navbar-scrolled .navbar-toggler-icon {
            filter: invert(1) brightness(200%);
        }

        html[data-theme='dark'] .navbar.navbar-home-overlay.navbar-scrolled .navbar-collapse.show {
            background: rgba(15, 23, 42, 0.96);
        }

        @media (max-width: 991.98px) {
            .navbar .navbar-collapse {
                background: var(--surface-color);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid var(--border-color);
                border-radius: 1rem;
                margin-top: 0.75rem;
                padding: 1.1rem 1.25rem;
                box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
                transition: all 0.3s ease-in-out;
            }

            .navbar.navbar-home-overlay:not(.navbar-scrolled) .navbar-collapse {
                background: rgba(15, 23, 42, 0.94);
                border-color: rgba(255, 255, 255, 0.12);
                box-shadow: 0 16px 36px rgba(0, 0, 0, 0.35);
            }

            .navbar.navbar-home-overlay:not(.navbar-scrolled) .navbar-collapse .nav-link {
                color: #e2e8f0 !important;
            }

            .navbar.navbar-home-overlay:not(.navbar-scrolled) .navbar-collapse .nav-link:hover,
            .navbar.navbar-home-overlay:not(.navbar-scrolled) .navbar-collapse .nav-link.active {
                color: var(--primary-color) !important;
            }
        }

        .navbar .navbar-brand,
        .navbar .nav-link {
            color: var(--text-color);
            transition: color 0.25s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--primary-color);
        }

        .dropdown-menu {
            border-color: var(--border-color);
            background: var(--surface-color);
        }

        .dropdown-item {
            color: var(--text-color);
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: var(--surface-alt-color);
            color: var(--primary-color);
        }

        a {
            color: var(--primary-color);
            transition: color 0.25s ease;
        }

        a:hover {
            color: var(--accent-color);
        }

        .hero-gradient {
            background: radial-gradient(circle at 20% 20%, #d1fae5 0%, #e0f2fe 35%, #f8fafc 100%);
        }

        .section-title {
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .section-subtitle-modern,
        .text-secondary,
        .small,
        .text-muted {
            color: var(--muted-color) !important;
        }

        .card {
            border: 0;
            border-radius: 16px;
            background: var(--surface-color);
            color: var(--text-color);
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.08);
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            transition: .25s ease;
        }

        .bg-white,
        .section-modern,
        .section-modern-light,
        .faq-card,
        .modern-card,
        .counter-card,
        .testimonial-card,
        .accordion-item,
        .card-body,
        .border,
        .border-0.shadow-sm,
        .alert-light {
            background-color: var(--surface-color) !important;
            color: var(--text-color);
        }

        .section-modern-light,
        .faq-accordion .accordion-item,
        .accordion-item,
        .bg-light,
        .table,
        .table> :not(caption)>*>* {
            background-color: var(--surface-alt-color) !important;
            color: var(--text-color);
        }

        .border,
        .border-top,
        .border-bottom,
        .border-start,
        .border-end,
        .table> :not(caption)>*>* {
            border-color: var(--border-color) !important;
        }

        .btn-outline-light {
            --bs-btn-color: var(--text-color);
            --bs-btn-border-color: var(--text-color);
            --bs-btn-hover-bg: var(--text-color);
            --bs-btn-hover-border-color: var(--text-color);
            --bs-btn-hover-color: var(--bg-color);
        }

        .btn-light {
            --bs-btn-bg: var(--surface-color);
            --bs-btn-border-color: var(--border-color);
            --bs-btn-color: var(--text-color);
            --bs-btn-hover-bg: var(--surface-alt-color);
            --bs-btn-hover-border-color: var(--border-color);
        }

        .btn-primary {
            --bs-btn-bg: var(--primary-color);
            --bs-btn-border-color: var(--primary-color);
            --bs-btn-hover-bg: var(--primary-color);
            --bs-btn-hover-border-color: var(--primary-color);
            --bs-btn-active-bg: var(--primary-color);
            --bs-btn-active-border-color: var(--primary-color);
        }

        .btn-secondary {
            --bs-btn-bg: var(--secondary-color);
            --bs-btn-border-color: var(--secondary-color);
            --bs-btn-hover-bg: var(--secondary-color);
            --bs-btn-hover-border-color: var(--secondary-color);
            --bs-btn-active-bg: var(--secondary-color);
            --bs-btn-active-border-color: var(--secondary-color);
        }

        .btn-warning {
            --bs-btn-bg: var(--accent-color);
            --bs-btn-border-color: var(--accent-color);
            --bs-btn-hover-bg: var(--accent-color);
            --bs-btn-hover-border-color: var(--accent-color);
            --bs-btn-active-bg: var(--accent-color);
            --bs-btn-active-border-color: var(--accent-color);
            --bs-btn-color: #111827;
        }

        .btn-outline-primary {
            --bs-btn-color: var(--primary-color);
            --bs-btn-border-color: var(--primary-color);
            --bs-btn-hover-bg: var(--primary-color);
            --bs-btn-hover-border-color: var(--primary-color);
        }

        .btn-outline-secondary {
            --bs-btn-color: var(--secondary-color);
            --bs-btn-border-color: var(--secondary-color);
            --bs-btn-hover-bg: var(--secondary-color);
            --bs-btn-hover-border-color: var(--secondary-color);
        }

        .btn-outline-warning {
            --bs-btn-color: var(--accent-color);
            --bs-btn-border-color: var(--accent-color);
            --bs-btn-hover-bg: var(--accent-color);
            --bs-btn-hover-border-color: var(--accent-color);
        }

        .btn-dark {
            --bs-btn-bg: var(--text-color);
            --bs-btn-border-color: var(--text-color);
            --bs-btn-hover-bg: var(--text-color);
            --bs-btn-hover-border-color: var(--text-color);
        }

        .btn-outline-dark {
            --bs-btn-color: var(--text-color);
            --bs-btn-border-color: var(--text-color);
            --bs-btn-hover-bg: var(--text-color);
            --bs-btn-hover-border-color: var(--text-color);
            --bs-btn-hover-color: var(--bg-color);
        }

        .btn,
        .nav-link,
        .dropdown-item {
            transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .card {
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.28s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.08) !important;
        }

        footer {
            background: var(--footer-bg);
            color: var(--footer-text);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        footer a {
            color: var(--footer-text);
        }

        footer a:hover {
            color: var(--accent-color);
        }

        .theme-switch {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            padding: 0.45rem 0.8rem;
            background: var(--surface-color);
            color: var(--text-color);
        }

        .theme-switch .form-check-input {
            margin-top: 0;
            cursor: pointer;
        }

        .theme-switch-label {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .theme-fab {
            position: fixed;
            left: 1rem;
            bottom: 1rem;
            z-index: 1080;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 999px;
            border: 1px solid var(--border-color);
            background: var(--surface-color);
            color: var(--text-color);
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.18);
            transition: transform 0.25s ease, background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease;
        }

        .theme-fab:hover {
            transform: translateY(-2px);
        }

        .theme-fab .theme-fab-icon {
            font-size: 1.05rem;
        }

        html[data-theme='dark'] .theme-fab {
            background: rgba(15, 23, 42, 0.95);
        }

        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed;
            right: 1.5rem;
            bottom: 1.5rem;
            z-index: 1080;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #25D366;
            color: #ffffff !important;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4), 0 4px 10px rgba(0, 0, 0, 0.12);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: whatsapp-pulse 2.8s infinite;
        }

        .whatsapp-float:hover,
        .whatsapp-float:focus-visible {
            transform: scale(1.1) translateY(-2px);
            background-color: #20ba5a;
            color: #ffffff !important;
            box-shadow: 0 14px 30px rgba(37, 211, 102, 0.55), 0 6px 14px rgba(0, 0, 0, 0.18);
            animation: none;
        }

        .whatsapp-float .whatsapp-icon-svg {
            width: 34px;
            height: 34px;
            fill: #ffffff;
            transition: transform 0.25s ease;
        }

        .whatsapp-float:hover .whatsapp-icon-svg {
            transform: scale(1.08);
        }

        /* Tooltip that slides out smoothly on hover */
        .whatsapp-float .whatsapp-tooltip {
            position: absolute;
            right: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%) translateX(8px);
            background: #0f172a;
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            padding: 6px 14px;
            border-radius: 50px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.25s ease;
        }

        .whatsapp-float .whatsapp-tooltip::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -6px;
            transform: translateY(-50%);
            border-width: 6px 0 6px 6px;
            border-style: solid;
            border-color: transparent transparent transparent #0f172a;
        }

        .whatsapp-float:hover .whatsapp-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateY(-50%) translateX(0);
        }

        @keyframes whatsapp-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.65), 0 10px 25px rgba(37, 211, 102, 0.4);
            }
            70% {
                box-shadow: 0 0 0 14px rgba(37, 211, 102, 0), 0 10px 25px rgba(37, 211, 102, 0.4);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0), 0 10px 25px rgba(37, 211, 102, 0.4);
            }
        }

        @media (max-width: 575px) {
            .whatsapp-float {
                right: 1.1rem;
                bottom: 1.1rem;
                width: 54px;
                height: 54px;
            }

            .whatsapp-float .whatsapp-icon-svg {
                width: 30px;
                height: 30px;
            }

            .whatsapp-float .whatsapp-tooltip {
                display: none;
            }
        }

        /* Floating flash alert styles */
        .flash-alert-floating {
            position: fixed;
            left: 0;
            right: 0;
            top: var(--flash-top, 72px);
            z-index: 1060;
            pointer-events: none;
            display: flex;
            justify-content: center;
            padding: 0.5rem;
        }

        .flash-alert-floating .alert {
            pointer-events: auto;
            border-radius: 0.6rem;
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.08);
            margin-bottom: 0;
        }

        @media (max-width: 575px) {
            .flash-alert-floating {
                padding: 0.4rem;
            }

            .flash-alert-floating .alert {
                font-size: 0.95rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('frontend.partials.navbar')

    @if (session('success') || session('error') || session('status') || $errors->any())
        <div class="flash-alert-floating" aria-live="polite">
            <div class="container">
                <div class="d-flex justify-content-center">
                    <div class="w-100" style="max-width:1100px;">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    @php
        $rawPhone = $settings?->contact_phone ?? '+880 1700-000000';
        $whatsappNumber = preg_replace('/\D+/', '', $rawPhone);
    @endphp
    @if ($whatsappNumber)
        <a href="https://wa.me/{{ $whatsappNumber }}" class="whatsapp-float" target="_blank"
            rel="noopener noreferrer" aria-label="Chat on WhatsApp" title="Chat on WhatsApp">
            <span class="whatsapp-tooltip">Chat with us</span>
            <svg class="whatsapp-icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
        </a>
    @endif

    <button type="button" class="theme-fab" id="themeToggleButton" aria-label="Toggle dark mode">
        <i class="bi bi-moon-stars-fill theme-fab-icon" id="themeToggleIcon" aria-hidden="true"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        (function() {
            const storageKey = 'frontend-theme-mode';
            const root = document.documentElement;
            const toggleButton = document.getElementById('themeToggleButton');
            const toggleIcon = document.getElementById('themeToggleIcon');
            const homeNavbar = document.querySelector('.navbar[data-navbar-home="true"]');

            const getPreferredTheme = () => {
                const storedTheme = localStorage.getItem(storageKey);

                if (storedTheme === 'dark' || storedTheme === 'light') {
                    return storedTheme;
                }

                return 'light';
            };

            const applyTheme = (theme) => {
                root.setAttribute('data-theme', theme);
                root.setAttribute('data-bs-theme', theme);
                root.style.colorScheme = theme;

                if (toggleButton && toggleIcon) {
                    const isDark = theme === 'dark';
                    toggleIcon.className = isDark ? 'bi bi-sun-fill theme-fab-icon' :
                        'bi bi-moon-stars-fill theme-fab-icon';
                    toggleButton.setAttribute('aria-label', isDark ? 'Switch to light mode' :
                        'Switch to dark mode');
                }

                document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
                    toggle.checked = theme === 'dark';
                });
            };

            const theme = getPreferredTheme();
            applyTheme(theme);

            window.addEventListener('storage', (event) => {
                if (event.key === storageKey) {
                    applyTheme(event.newValue === 'dark' ? 'dark' : 'light');
                }
            });

            const handleToggle = () => {
                const currentTheme = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem(storageKey, nextTheme);
                applyTheme(nextTheme);
            };

            const syncHomeNavbar = () => {
                if (!homeNavbar) {
                    return;
                }

                homeNavbar.classList.toggle('navbar-scrolled', window.scrollY > 20);
            };

            if (toggleButton) {
                toggleButton.addEventListener('click', handleToggle);
            }

            syncHomeNavbar();
            window.addEventListener('scroll', syncHomeNavbar, {
                passive: true
            });

            document.addEventListener('change', (event) => {
                const target = event.target;

                if (!target || !target.matches('[data-theme-toggle]')) {
                    return;
                }

                const nextTheme = target.checked ? 'dark' : 'light';
                localStorage.setItem(storageKey, nextTheme);
                applyTheme(nextTheme);
            });
        })();

        AOS.init({
            once: true,
            duration: 750,
            easing: 'ease-out-cubic'
        });

        // Auto-close mobile navbar on link click or outside click
        document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', () => {
                const collapseEl = document.getElementById('mainNav');
                if (collapseEl && collapseEl.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapseEl);
                    if (bsCollapse) bsCollapse.hide();
                }
            });
        });
        // position floating flash below the navbar exactly
        (function() {
            function setFlashTop() {
                var nav = document.querySelector('.navbar');
                var top = 72; // default
                if (nav) {
                    var rect = nav.getBoundingClientRect();
                    top = Math.ceil(rect.height) + 12;
                }
                document.documentElement.style.setProperty('--flash-top', top + 'px');
            }

            setFlashTop();
            window.addEventListener('resize', setFlashTop, {
                passive: true
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
