@extends('admin.layouts.app')
@section('title', 'Counters')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Counters</h4>
            <div class="text-secondary">Manage the animated homepage counters here.</div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Add Counter</h5>
            <form method="POST" action="{{ route('admin.counters.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                            placeholder="Happy Clients">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Value</label>
                        <input type="number" name="value" class="form-control" value="{{ old('value') }}"
                            placeholder="60" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon') }}"
                            placeholder="bi-emoji-smile">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Icon Upload</label>
                        <input type="file" name="icon_file" class="form-control">
                    </div>
                    <div class="col-12 d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1"
                                id="counterStatusAdd" checked>
                            <label class="form-check-label" for="counterStatusAdd">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Counter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($counters as $counter)
            @php
                $counterIconIsFile = $counter->icon && str_contains($counter->icon, '/');
            @endphp
            <div class="col-lg-6">
                <div class="card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Counter #{{ $counter->id }}</h5>
                        <span class="badge {{ $counter->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $counter->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.counters.update', $counter) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $counter->title) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Value</label>
                                <input type="number" name="value" class="form-control"
                                    value="{{ old('value', $counter->value) }}" min="0">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" value="1"
                                        id="counterStatus{{ $counter->id }}"
                                        {{ old('status', $counter->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="counterStatus{{ $counter->id }}">Active</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Icon Class</label>
                                <input type="text" name="icon" class="form-control"
                                    value="{{ old('icon', $counterIconIsFile ? '' : $counter->icon) }}"
                                    placeholder="bi-emoji-smile">
                                <small class="text-secondary">Use a Bootstrap Icon class or upload an image below.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Icon Upload</label>
                                <input type="file" name="icon_file" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Current Icon</label>
                                <div class="d-flex align-items-center gap-3 p-3 border rounded bg-light">
                                    @if ($counter->icon)
                                        @if ($counterIconIsFile)
                                            <img src="{{ asset('storage/' . $counter->icon) }}"
                                                alt="{{ $counter->title }}"
                                                style="width: 52px; height: 52px; object-fit: cover; border-radius: 12px;">
                                        @else
                                            <i class="bi {{ $counter->icon }}" style="font-size: 2rem;"></i>
                                        @endif
                                    @else
                                        <i class="bi bi-tools text-secondary" style="font-size: 2rem;"></i>
                                    @endif
                                    <div class="text-secondary small">Upload overrides the icon class.</div>
                                </div>
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.counters.destroy', $counter) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirm('Delete this counter?')">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0">No counters found.</div>
            </div>
        @endforelse
    </div>
@endsection
