<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Setup Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .setup-card { max-width: 700px; margin: 40px auto; border-radius: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card setup-card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3 rounded-top-3">
                <h4 class="mb-0 fw-bold"><i class="bi bi-gear-wide-connected me-2"></i>System Setup &amp; Deployment</h4>
            </div>
            <div class="card-body p-4">
                <p class="text-muted">The deployment setup commands have been executed on your hosting server.</p>

                <!-- Migrations -->
                <div class="mb-4">
                    <h6 class="fw-bold d-flex align-items-center">
                        @if($results['migrations']['success'])
                            <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                            Database Migrations
                        @else
                            <i class="bi bi-x-circle-fill text-danger me-2 fs-5"></i>
                            Database Migrations (Failed)
                        @endif
                    </h6>
                    <pre class="bg-light p-3 rounded border small mb-0">{{ $results['migrations']['output'] ?: 'No output or all tables up-to-date.' }}</pre>
                </div>

                <!-- Storage Link -->
                <div class="mb-4">
                    <h6 class="fw-bold d-flex align-items-center">
                        @if($results['storage_link']['success'])
                            <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                            Storage Link &amp; Image System
                        @else
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2 fs-5"></i>
                            Storage Link Notice
                        @endif
                    </h6>
                    <pre class="bg-light p-3 rounded border small mb-0">{{ $results['storage_link']['output'] ?: 'Storage link configured.' }}</pre>
                </div>

                <!-- Cache -->
                <div class="mb-4">
                    <h6 class="fw-bold d-flex align-items-center">
                        @if($results['cache']['success'])
                            <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                            Application Caches
                        @else
                            <i class="bi bi-x-circle-fill text-danger me-2 fs-5"></i>
                            Application Caches (Failed)
                        @endif
                    </h6>
                    <pre class="bg-light p-3 rounded border small mb-0">{{ $results['cache']['output'] ?: 'Caches cleared.' }}</pre>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-house me-1"></i> Visit Website
                    </a>
                    <a href="{{ route('admin.login') }}" class="btn btn-primary">
                        <i class="bi bi-lock me-1"></i> Go to Admin Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
