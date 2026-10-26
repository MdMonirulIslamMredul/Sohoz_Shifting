<div class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
    <div>
        <strong>Welcome, {{ auth()->user()->name }}</strong>
    </div>
    <div class="d-flex align-items-center gap-3">
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
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="adminUserMenu"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserMenu">
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
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
