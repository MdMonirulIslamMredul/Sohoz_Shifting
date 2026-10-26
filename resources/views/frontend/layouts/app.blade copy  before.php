<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings?->meta_title ?? 'Solar Website Template')</title>
    <meta name="description" content="@yield('meta_description', $settings?->meta_description ?? 'Solar service website template built with Laravel')">
    <meta name="keywords" content="@yield('meta_keywords', $settings?->seo_keywords ?? 'solar, renewable, laravel template')">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @if ($settings?->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $settings->favicon_path) }}">
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
            .navbar.navbar-home-overlay .navbar-collapse.show {
                background: rgba(2, 6, 23, 0.78);
                backdrop-filter: blur(14px);
                border-radius: 1rem;
                margin-top: 0.75rem;
                padding: 1rem 1.1rem;
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

        .whatsapp-float {
            position: fixed;
            right: 1.5rem;
            bottom: 1.5rem;
            z-index: 1080;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 3.9rem;
            height: 3.9rem;
            padding: 0.9rem 1rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: #ffffff;
            text-decoration: none;
            box-shadow: 0 18px 45px rgba(37, 211, 102, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            font-weight: 600;
        }

        .whatsapp-float:hover,
        .whatsapp-float:focus-visible {
            transform: translateY(-3px);
            box-shadow: 0 22px 48px rgba(18, 140, 126, 0.22);
            opacity: 0.98;
        }

        .whatsapp-float .whatsapp-icon {
            font-size: 1.35rem;
            line-height: 1;
        }

        .whatsapp-float .whatsapp-label {
            display: none;
        }

        @media (min-width: 576px) {
            .whatsapp-float {
                min-width: auto;
                padding: 0.85rem 1rem;
            }

            .whatsapp-float .whatsapp-label {
                display: inline-block;
                font-size: 0.95rem;
                letter-spacing: 0.01em;
            }
        }

        @media (max-width: 575px) {
            .whatsapp-float {
                width: 3.6rem;
                height: 3.6rem;
                padding: 0.8rem;
            }

            .whatsapp-float .whatsapp-label {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('frontend.partials.navbar')

    @if (session('success') || session('error') || session('status') || $errors->any())
        <div class="container {{ request()->routeIs('home') ? 'mt-5' : 'mt-3' }}">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    @if ($settings?->contact_phone)
        @php
            $whatsappNumber = preg_replace('/\D+/', '', $settings->contact_phone);
        @endphp
        @if ($whatsappNumber)
            <a href="https://wa.me/{{ $whatsappNumber }}" class="whatsapp-float" target="_blank"
                rel="noopener noreferrer" aria-label="Chat on WhatsApp">
                <i class="bi bi-whatsapp whatsapp-icon" aria-hidden="true"></i>
                <span class="whatsapp-label">WhatsApp</span>
            </a>
        @endif
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

                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
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
            duration: 650
        });
    </script>
    @stack('scripts')
</body>

</html>
