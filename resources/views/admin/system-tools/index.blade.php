@extends('admin.layouts.app')
@section('title', 'System & Maintenance Tools')
@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold"><i class="bi bi-hdd-network me-2 text-primary"></i>System &amp; Maintenance Tools</h4>
            <p class="text-muted mb-0 small">Manage caches, database migrations, and file storage without terminal/SSH access.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{!! session('success') !!}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{!! session('warning') !!}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-x-octagon-fill me-2 fs-5"></i>
                <div>{!! session('error') !!}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- System Diagnostics -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title mb-0 fw-semibold text-secondary">
                <i class="bi bi-speedometer me-2"></i>Server Diagnostics &amp; Health
            </h5>
        </div>
        <div class="card-body pt-0">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted small fw-medium">PHP Version</div>
                        <div class="fs-5 fw-bold text-dark mt-1">{{ $diagnostics['php_version'] }}</div>
                        <span class="badge bg-success-subtle text-success mt-1"><i class="bi bi-check2"></i> Supported</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted small fw-medium">Upload Limits</div>
                        <div class="fs-6 fw-bold text-dark mt-1">
                            File: {{ $diagnostics['upload_max_filesize'] }} | Post: {{ $diagnostics['post_max_size'] }}
                        </div>
                        <span class="badge bg-info-subtle text-info mt-1">Memory: {{ $diagnostics['memory_limit'] }}</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted small fw-medium">Storage Folder Writable</div>
                        <div class="fs-5 fw-bold mt-1 {{ $diagnostics['storage_writable'] ? 'text-success' : 'text-danger' }}">
                            {{ $diagnostics['storage_writable'] ? 'Writable' : 'Read Only' }}
                        </div>
                        @if ($diagnostics['storage_writable'])
                            <span class="badge bg-success-subtle text-success mt-1"><i class="bi bi-check-circle"></i> Ready for uploads</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger mt-1"><i class="bi bi-exclamation-circle"></i> Chmod 775 needed</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted small fw-medium">Image Storage System</div>
                        <div class="fs-6 fw-bold mt-1 text-dark">
                            @if ($diagnostics['storage_linked'])
                                <span class="text-success"><i class="bi bi-link-45deg"></i> Symlink Active</span>
                            @else
                                <span class="text-primary"><i class="bi bi-shield-check"></i> Fallback Route Active</span>
                            @endif
                        </div>
                        <span class="badge bg-success-subtle text-success mt-1"><i class="bi bi-check2-all"></i> Images display safely</span>
                    </div>
                </div>
            </div>

            <div class="mt-3 p-3 bg-light-subtle border rounded-3 small">
                <div class="row gy-2">
                    <div class="col-md-6">
                        <i class="bi bi-folder-check text-success me-1"></i>
                        <strong>Bootstrap Cache:</strong>
                        <span class="{{ $diagnostics['bootstrap_writable'] ? 'text-success' : 'text-danger' }}">
                            {{ $diagnostics['bootstrap_writable'] ? 'Writable' : 'Not Writable' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-terminal text-secondary me-1"></i>
                        <strong>PHP symlink() Function:</strong>
                        <span class="{{ $diagnostics['symlink_function_enabled'] ? 'text-success' : 'text-warning' }}">
                            {{ $diagnostics['symlink_function_enabled'] ? 'Enabled' : 'Disabled on this host (fallback route active)' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-database text-primary me-1"></i>
                        <strong>Database:</strong>
                        <span class="{{ $diagnostics['db_connected'] ? 'text-success' : 'text-danger' }}">
                            {{ $diagnostics['db_connected'] ? 'Connected' : 'Connection failed (' . $diagnostics['db_error'] . ')' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-hdd text-info me-1"></i>
                        <strong>Public Storage Root:</strong>
                        <code class="text-muted">{{ $diagnostics['storage_disk_root'] }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Tools -->
    <div class="row g-4">
        <!-- Cache Management -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 bg-warning-subtle text-warning rounded-3 me-3">
                            <i class="bi bi-lightning-charge fs-4"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">Cache Management</h5>
                            <small class="text-muted">Clear or refresh cached configurations</small>
                        </div>
                    </div>
                    <p class="text-muted small flex-grow-1">
                        If you update <code>.env</code>, routes, or Blade templates on shared hosting without a terminal, click <strong>Clear All Caches</strong> to apply the changes immediately.
                    </p>
                    <div class="d-grid gap-2 mt-3">
                        <form action="{{ route('admin.system-tools.clear-cache') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 fw-semibold text-dark">
                                <i class="bi bi-arrow-clockwise me-1"></i> Clear All Caches
                            </button>
                        </form>
                        <form action="{{ route('admin.system-tools.optimize') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-dark w-100">
                                <i class="bi bi-speedometer2 me-1"></i> Optimize Application
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Migrations -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 bg-primary-subtle text-primary rounded-3 me-3">
                            <i class="bi bi-database-gear fs-4"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">Database Migrations</h5>
                            <small class="text-muted">Run updates on your live database</small>
                        </div>
                    </div>
                    <p class="text-muted small flex-grow-1">
                        Executes <code>php artisan migrate --force</code>. Use this whenever you upload new database migration files to the server and do not have SSH terminal access.
                    </p>
                    <div class="d-grid gap-2 mt-3">
                        <form action="{{ route('admin.system-tools.run-migrations') }}" method="POST" onsubmit="return confirm('Run database migrations now?');">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                <i class="bi bi-play-circle me-1"></i> Run Migrations Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage & Images -->
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-2 bg-success-subtle text-success rounded-3 me-3">
                            <i class="bi bi-images fs-4"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">Storage &amp; Symlink</h5>
                            <small class="text-muted">Link uploaded files to public access</small>
                        </div>
                    </div>
                    <p class="text-muted small flex-grow-1">
                        Attempts to create the <code>public/storage</code> symlink. If your hosting does not allow symlinks, <strong>you do not need to worry</strong>: the built-in route fallback automatically serves all images!
                    </p>
                    <div class="d-grid gap-2 mt-3">
                        <form action="{{ route('admin.system-tools.link-storage') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 fw-semibold">
                                <i class="bi bi-link-45deg me-1"></i> Create Storage Link
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Server Best Practice Tips -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title mb-0 fw-semibold text-secondary">
                <i class="bi bi-info-circle me-2"></i>How Uploads &amp; Live Hosting Work in This Project
            </h5>
        </div>
        <div class="card-body pt-0 small text-muted">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark"><i class="bi bi-shield-check text-success me-1"></i> Zero Terminal Required</h6>
                    <p>
                        Normally, Laravel requires <code>php artisan storage:link</code> via terminal. On this site, we configured a safe <strong>image fallback route</strong>. Even if your cPanel/shared host has disabled terminal access or the PHP <code>symlink()</code> function, images will load seamlessly.
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark"><i class="bi bi-key text-primary me-1"></i> Initial Deployment Setup Route</h6>
                    <p>
                        If you upload this project to a server where the database is not yet migrated, you can run setup from your browser by defining <code>SETUP_KEY=your_secret_password</code> in <code>.env</code> and visiting:
                        <br>
                        <code>https://yourdomain.com/system/setup?key=your_secret_password</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
