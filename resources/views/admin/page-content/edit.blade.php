@extends('admin.layouts.app')
@section('title', 'Page Content - Merged')
@section('content')
    <div class="card shadow-sm border-0 p-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary p-3">
                <i class="bi bi-info-circle-fill fs-3"></i>
            </div>
            <div>
                <h4 class="text-dark fw-bold mb-1">Page Content Has Been Unified</h4>
                <div class="text-secondary">
                    These settings have been merged into dedicated, easier-to-manage sections.
                </div>
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-dark"><i class="bi bi-file-earmark-person-fill text-primary me-2"></i>About Page &amp; Footer Summary</h5>
                        <p class="text-secondary small mb-3">
                            Manage About Us page content, mission, vision, milestone history, key values, narrative blocks, and the public footer summary.
                        </p>
                    </div>
                    <a href="{{ route('admin.about.edit') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2">
                        Go to About Page <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-dark"><i class="bi bi-megaphone-fill text-success me-2"></i>Homepage Call To Action (CTA)</h5>
                        <p class="text-secondary small mb-3">
                            Manage homepage call-to-action title, description, button label, and destination link under General Settings.
                        </p>
                    </div>
                    <a href="{{ route('admin.settings.edit') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2">
                        Go to General Settings <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
