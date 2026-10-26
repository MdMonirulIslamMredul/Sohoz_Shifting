@extends('admin.layouts.app')
@section('title', 'Property Types')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Property Types (বাসা / অফিসের ধরন)</h4>
            <p class="text-muted small mb-0">Manage residential and commercial property types for the booking form</p>
        </div>
        <a href="{{ route('admin.property-types.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Property Type
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card p-3 shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">Icon</th>
                        <th>Name (English)</th>
                        <th>Name (Bangla)</th>
                        <th>Base Price</th>
                        <th style="width: 80px;">Order</th>
                        <th style="width: 100px;">Status</th>
                        <th class="text-end" style="width: 150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($propertyTypes as $type)
                        <tr>
                            <td>
                                <div class="bg-light rounded p-2 text-center text-primary" style="width: 38px; height: 38px;">
                                    <i class="bi {{ $type->icon ?: 'bi-house' }} fs-5"></i>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $type->name }}</strong>
                                @if($type->description)
                                    <div class="text-muted small text-truncate" style="max-width: 280px;">{{ $type->description }}</div>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark fs-6">{{ $type->name_bn ?: '-' }}</span></td>
                            <td>
                                @if($type->base_price)
                                    <span class="text-success fw-bold">৳{{ number_format($type->base_price, 2) }}</span>
                                @else
                                    <span class="text-muted small">আলোচনা সাপেক্ষে</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $type->sort_order }}</span></td>
                            <td>
                                @if($type->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.property-types.edit', $type) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.property-types.destroy', $type) }}" class="d-inline" onsubmit="return confirm('Delete this property type?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No property types found. Click "Add Property Type" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $propertyTypes->links() }}
        </div>
    </div>
@endsection
