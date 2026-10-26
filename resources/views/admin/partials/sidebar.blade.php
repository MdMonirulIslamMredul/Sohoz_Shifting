<div class="col-lg-2 sidebar p-3 d-flex flex-column" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary border-opacity-25">
        <h5 class="text-white mb-0">Admin Panel</h5>
        <a href="{{ Route::has('home') ? route('home') : url('/') }}" target="_blank"
            class="btn btn-sm btn-outline-light border-secondary text-white py-1 px-2"
            title="View Frontend Website">
            <i class="bi bi-globe"></i>
        </a>
    </div>

    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    {{-- Messages --}}
    <a href="{{ route('admin.contacts.index') }}"
        class="d-flex justify-content-between align-items-center {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-envelope{{ !empty($unreadContactsCount) && $unreadContactsCount > 0 ? '-fill text-warning' : '' }} me-2"></i>
            Messages
        </span>
        @if (!empty($unreadContactsCount) && $unreadContactsCount > 0)
            <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.75rem; font-weight: 600;">
                {{ $unreadContactsCount }}
            </span>
        @endif
    </a>

    {{-- Shifting Operations Menu --}}
    @php
        $isShiftingActive = request()->routeIs(['admin.shifting-bookings.*', 'admin.property-types.*', 'admin.services.*']);
    @endphp
    <a class="d-flex justify-content-between align-items-center {{ $isShiftingActive ? 'parent-active' : 'collapsed' }}"
        data-bs-toggle="collapse" href="#shiftingOpsMenu"
        role="button" aria-expanded="{{ $isShiftingActive ? 'true' : 'false' }}" aria-controls="shiftingOpsMenu">
        <span>
            <i class="bi bi-truck me-2 text-warning"></i>
            <span>Shifting Ops</span>
        </span>
        <div class="d-flex align-items-center gap-1">
            @if (!empty($pendingBookingsCount) && $pendingBookingsCount > 0)
                <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.75rem; font-weight: 600;">
                    {{ $pendingBookingsCount }}
                </span>
            @endif
            <i class="bi bi-chevron-down"></i>
        </div>
    </a>
    <div class="collapse ps-3 {{ $isShiftingActive ? 'show' : '' }}" id="shiftingOpsMenu">
        <a href="{{ route('admin.shifting-bookings.index') }}"
            class="d-flex justify-content-between align-items-center {{ request()->routeIs('admin.shifting-bookings.*') ? 'active' : '' }}">
            <span><i class="bi bi-calendar-check me-2"></i>Bookings</span>
            @if (!empty($pendingBookingsCount) && $pendingBookingsCount > 0)
                <span class="badge bg-warning text-dark rounded-pill px-2 py-0" style="font-size: 0.7rem;">
                    {{ $pendingBookingsCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.property-types.index') }}"
            class="{{ request()->routeIs('admin.property-types.*') ? 'active' : '' }}">
            <i class="bi bi-building me-2"></i>Property Types
        </a>
        <a href="{{ route('admin.services.index') }}"
            class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            <i class="bi bi-tools me-2"></i>Services &amp; Pricing
        </a>
    </div>

    {{-- Web Settings Menu --}}
    @can('Web_Settings')
        @php
            $webSettingsRoutes = [
                'admin.settings.*',
                'admin.contact-social.*',
                'admin.homepage-carousel-images.*',
                'admin.counters.*',
                'admin.testimonials.*',
                'admin.faqs.*',
                'admin.about.*',
                'admin.team-members.*',
                'admin.partners.*',
                'admin.blogs.*',
                'admin.galleries.*',
                'admin.system-tools.*',
            ];
            $isWebSettingsActive = request()->routeIs($webSettingsRoutes);
        @endphp
        <a class="d-flex justify-content-between align-items-center {{ $isWebSettingsActive ? 'parent-active' : 'collapsed' }}"
            data-bs-toggle="collapse" href="#webSettingsMenu"
            role="button" aria-expanded="{{ $isWebSettingsActive ? 'true' : 'false' }}" aria-controls="webSettingsMenu">
            <span><i class="bi bi-gear me-2"></i>Web Settings</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $isWebSettingsActive ? 'show' : '' }}" id="webSettingsMenu">
            <a href="{{ route('admin.settings.edit') }}"
                class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">General Settings</a>
            <a href="{{ route('admin.about.edit') }}"
                class="{{ request()->routeIs('admin.about.*') ? 'active' : '' }}">About Page</a>
            <a href="{{ route('admin.contact-social.edit') }}"
                class="{{ request()->routeIs('admin.contact-social.*') ? 'active' : '' }}">Contact &amp; Social Links</a>
            <a href="{{ route('admin.homepage-carousel-images.index') }}"
                class="{{ request()->routeIs('admin.homepage-carousel-images.*') ? 'active' : '' }}">Homepage Carousel Images</a>
            <a href="{{ route('admin.counters.index') }}"
                class="{{ request()->routeIs('admin.counters.*') ? 'active' : '' }}">Counters</a>
            <a href="{{ route('admin.testimonials.index') }}"
                class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">Testimonials</a>
            <a href="{{ route('admin.faqs.index') }}"
                class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">FAQs</a>
            <a href="{{ route('admin.team-members.index') }}"
                class="{{ request()->routeIs('admin.team-members.*') ? 'active' : '' }}">Team Members</a>
            <a href="{{ route('admin.partners.index') }}"
                class="{{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">Partners</a>
            <a href="{{ route('admin.blogs.index') }}"
                class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">Blogs</a>
            <a href="{{ route('admin.galleries.index') }}"
                class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">Gallery</a>
            <a href="{{ route('admin.system-tools.index') }}"
                class="{{ request()->routeIs('admin.system-tools.*') ? 'active' : '' }}">
                <i class="bi bi-hdd-network me-1"></i> System &amp; Cache
            </a>
        </div>
    @endcan

    {{-- Management Menu --}}
    @canany(['manage users', 'manage roles', 'manage permissions'])
        @php
            $managementRoutes = [
                'admin.users.*',
                'admin.roles.*',
                'admin.permissions.*',
            ];
            $isManagementActive = request()->routeIs($managementRoutes);
        @endphp
        <a class="d-flex justify-content-between align-items-center {{ $isManagementActive ? 'parent-active' : 'collapsed' }}"
            data-bs-toggle="collapse" href="#managementMenu"
            role="button" aria-expanded="{{ $isManagementActive ? 'true' : 'false' }}" aria-controls="managementMenu">
            <span><i class="bi bi-people me-2"></i>Management</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $isManagementActive ? 'show' : '' }}" id="managementMenu">
            @can('manage users')
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
            @endcan
            @can('manage roles')
                <a href="{{ route('admin.roles.index') }}"
                    class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">Roles</a>
            @endcan
            @can('manage permissions')
                <a href="{{ route('admin.permissions.index') }}"
                    class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">Permissions</a>
            @endcan
        </div>
    @endcanany

    {{-- Products Menu --}}
    @can('manage products')
        @php
            $productsRoutes = [
                'admin.products.*',
                'admin.product-categories.*',
                'admin.product-subcategories.*',
            ];
            $isProductsActive = request()->routeIs($productsRoutes);
        @endphp
        <a class="d-flex justify-content-between align-items-center {{ $isProductsActive ? 'parent-active' : 'collapsed' }}"
            data-bs-toggle="collapse" href="#productsMenu"
            role="button" aria-expanded="{{ $isProductsActive ? 'true' : 'false' }}" aria-controls="productsMenu">
            <span><i class="bi bi-box-seam me-2"></i>Products</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-3 {{ $isProductsActive ? 'show' : '' }}" id="productsMenu">
            <a href="{{ route('admin.products.index') }}"
                class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Products</a>
            <a href="{{ route('admin.product-categories.index') }}"
                class="{{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">Product Categories</a>
            <a href="{{ route('admin.product-subcategories.index') }}"
                class="{{ request()->routeIs('admin.product-subcategories.*') ? 'active' : '' }}">Product Subcategories</a>
        </div>
    @endcan

    {{-- Services --}}
    @can('manage services')
        <a href="{{ route('admin.services.index') }}"
            class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            <i class="bi bi-tools me-2"></i> Services
        </a>
    @endcan

    <div class="mt-auto pt-3 border-top border-secondary border-opacity-25">
        {{-- Visit Website --}}
        <a href="{{ Route::has('home') ? route('home') : url('/') }}" target="_blank"
            class="btn btn-outline-info w-100 text-start px-3 py-2 mb-2">
            <i class="bi bi-box-arrow-up-right me-2"></i> Visit Website
        </a>

        {{-- Log Out --}}
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 text-start px-3 py-2">
                <i class="bi bi-box-arrow-right me-2"></i> Log Out
            </button>
        </form>
    </div>
</div>
