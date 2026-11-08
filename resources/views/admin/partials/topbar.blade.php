<div class="topbar bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-secondary d-lg-none p-1 px-2 border-0" type="button" id="sidebarToggler"
            data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar"
            aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>
        <strong class="text-truncate">Welcome, {{ auth()->user()->name }}</strong>
    </div>
    <div class="d-flex align-items-center gap-2 gap-sm-3">
        {{-- Real-time New Booking Notification (Image 4 requirement) --}}
        <a href="{{ route('admin.shifting-bookings.index', ['status' => 'new_request']) }}" class="btn btn-sm btn-outline-warning text-dark position-relative" id="bookingNotificationBell" title="New Booking Requests">
            <i class="bi bi-bell-fill text-warning"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="newBookingCountBadge" style="font-size: 0.65rem; display: none;">
                0
            </span>
        </a>

        <a href="{{ Route::has('home') ? route('home') : url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View Frontend Website">
            <i class="bi bi-globe"></i>
        </a>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-secondary position-relative" title="Contact Messages">
            <i class="bi bi-envelope"></i>
            @if (!empty($unreadContactsCount) && $unreadContactsCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                    {{ $unreadContactsCount }}
                    <span class="visually-hidden">unread messages</span>
                </span>
            @endif
        </a>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle text-truncate" style="max-width: 160px;" type="button" id="adminUserMenu"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="adminUserMenu">
                <li><a class="dropdown-item" href="{{ Route::has('home') ? route('home') : url('/') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>Visit Website</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.password.edit') }}">Change Password</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
