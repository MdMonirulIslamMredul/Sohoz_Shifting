@extends('admin.layouts.app')
@section('title', 'Services')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Services (সেবা সমূহ)</h4>
            <p class="text-muted small mb-0">Manage moving services, dynamic price rates, and price visibility settings</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Service
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card p-3 shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">Icon</th>
                        <th>Title</th>
                        <th>Price / Rate</th>
                        <th style="width: 140px;">Price on Web</th>
                        <th style="width: 140px;">Booking Add-on</th>
                        <th style="width: 90px;">Status</th>
                        <th class="text-end" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>
                                <div class="bg-primary-subtle text-primary rounded p-2 text-center" style="width: 38px; height: 38px;">
                                    <i class="bi {{ $service->icon ?: 'bi-box-seam' }} fs-5"></i>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $service->title }}</strong>
                                @if ($service->short_description)
                                    <div class="text-muted small text-truncate" style="max-width: 300px;">
                                        {{ $service->short_description }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($service->pricing_type === 'fixed' && $service->price)
                                    <span class="text-success fw-bold">৳{{ number_format($service->price, 2) }}</span>
                                @elseif($service->pricing_type === 'range' && $service->price_range)
                                    <span class="text-primary fw-semibold">{{ $service->price_range }}</span>
                                @else
                                    <span class="text-muted small">আলোচনা সাপেক্ষে</span>
                                @endif
                            </td>
                            <td>
                                @if($service->show_price)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-eye me-1"></i> Visible
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border">
                                        <i class="bi bi-eye-slash me-1"></i> Hidden
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($service->is_addon_for_booking)
                                    <span class="badge bg-info-subtle text-info-emphasis border">
                                        <i class="bi bi-check-circle me-1"></i> In Booking
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($service->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline" onsubmit="return confirm('Delete this service?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No services found. Click "Add Service" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $services->links() }}
        </div>
    </div>
@endsection
