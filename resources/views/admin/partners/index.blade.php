@extends('admin.layouts.app')
@section('title', 'Partners')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Partners &amp; Collaborators</h4>
            <div class="text-secondary">Manage company partners, sponsors, and logos displayed on the website.</div>
        </div>
        <div>
            <a href="{{ route('admin.partners.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Add New Partner
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card p-3 shadow-sm border-0 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small text-uppercase fw-semibold">Total Partners</div>
                        <div class="fs-3 fw-bold text-dark">{{ $totalPartners }}</div>
                    </div>
                    <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-handshake fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card p-3 shadow-sm border-0 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small text-uppercase fw-semibold">Active Partners</div>
                        <div class="fs-3 fw-bold text-success">{{ $activePartners }}</div>
                    </div>
                    <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card p-3 shadow-sm border-0 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small text-uppercase fw-semibold">Inactive Partners</div>
                        <div class="fs-3 fw-bold text-warning">{{ $totalPartners - $activePartners }}</div>
                    </div>
                    <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-pause-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card p-3 shadow-sm border-0 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small text-uppercase fw-semibold">Frontend Page</div>
                        <div>
                            <a href="{{ route('partners.index') }}" target="_blank" class="btn btn-sm btn-outline-info mt-1">
                                <i class="bi bi-box-arrow-up-right me-1"></i> View Page
                            </a>
                        </div>
                    </div>
                    <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info">
                        <i class="bi bi-globe fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="card">
        <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <form method="GET" action="{{ route('admin.partners.index') }}" class="d-flex align-items-center gap-2" style="max-width: 320px; width: 100%;">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Search partner name..."
                            value="{{ $search }}">
                        @if ($search)
                            <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary" title="Clear">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <div class="text-secondary small">
                    Showing {{ $partners->count() }} of {{ $partners->total() }} partners
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">Logo</th>
                        <th>Partner Name</th>
                        <th>Website</th>
                        <th>Description</th>
                        <th class="text-center" style="width: 100px;">Sort Order</th>
                        <th class="text-center" style="width: 110px;">Status</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($partners as $partner)
                        <tr>
                            <td>
                                <div class="bg-white p-1 rounded border d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 58px; height: 58px;">
                                    <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}"
                                        style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $partner->name }}</div>
                                <small class="text-secondary">Added {{ $partner->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                @if ($partner->website_url)
                                    <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer"
                                        class="text-info text-decoration-none small d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-link-45deg"></i>
                                        {{ Str::limit(preg_replace('#^https?://#', '', $partner->website_url), 28) }}
                                    </a>
                                @else
                                    <span class="text-secondary small">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($partner->description)
                                    <span class="text-secondary small" title="{{ $partner->description }}">
                                        {{ Str::limit($partner->description, 45) }}
                                    </span>
                                @else
                                    <span class="text-secondary small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                    {{ $partner->sort_order }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $partner->status ? 'bg-success' : 'bg-secondary' }} px-2 py-1">
                                    {{ $partner->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.partners.edit', $partner) }}"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete partner &quot;{{ $partner->name }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-secondary mb-3">
                                    <i class="bi bi-handshake" style="font-size: 3rem;"></i>
                                </div>
                                <h6 class="text-dark">No partners found</h6>
                                <p class="text-secondary small mb-3">
                                    @if ($search)
                                        No partners match your search query "{{ $search }}".
                                    @else
                                        Add your first partner to showcase your alliances on the website and home carousel.
                                    @endif
                                </p>
                                <a href="{{ route('admin.partners.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Add Partner Now
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($partners->hasPages())
            <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 py-3">
                <div class="d-flex justify-content-center">
                    {{ $partners->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
