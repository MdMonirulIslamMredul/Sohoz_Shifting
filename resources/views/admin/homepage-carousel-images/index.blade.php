@extends('admin.layouts.app')
@section('title', 'Homepage Carousel Images')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Homepage Carousel Images</h4>
            <div class="text-secondary">Manage homepage slider images, titles, and active status here.</div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Add New Image</h5>
            <form method="POST" action="{{ route('admin.homepage-carousel-images.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}"
                            placeholder="Image title">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="is_active">
                            <option value="1" @selected(old('is_active', '1') === '1')>Active</option>
                            <option value="0" @selected(old('is_active') === '0')>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Subtitle</label>
                        <textarea class="form-control" name="subtitle" rows="3" placeholder="Image subtitle">{{ old('subtitle') }}</textarea>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Add Image</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Existing Images</h5>
                <span class="badge bg-secondary">{{ $slides->count() }} total</span>
            </div>

            <div class="row g-4">
                @forelse ($slides as $slide)
                    <div class="col-lg-6">
                        <div class="border rounded-3 h-100 bg-light-subtle">
                            <div
                                class="d-flex justify-content-between align-items-center p-3 border-bottom bg-white rounded-top">
                                <div>
                                    <h6 class="mb-1">Image #{{ $slide->sort_order }}</h6>
                                    <small class="text-secondary">ID {{ $slide->id }}</small>
                                </div>
                                <span class="badge {{ $slide->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="p-3">
                                <form method="POST" action="{{ route('admin.homepage-carousel-images.update', $slide) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title"
                                                value="{{ old('title', $slide->title) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="is_active">
                                                <option value="1" @selected(old('is_active', $slide->is_active ? '1' : '0') === '1')>Active</option>
                                                <option value="0" @selected(old('is_active', $slide->is_active ? '1' : '0') === '0')>Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Subtitle</label>
                                            <textarea class="form-control" name="subtitle" rows="3">{{ old('subtitle', $slide->subtitle) }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Current Image</label>
                                            @if ($slide->image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $slide->image) }}"
                                                        alt="{{ $slide->title ?? 'Carousel image' }}"
                                                        class="img-fluid rounded"
                                                        style="max-height: 180px; object-fit: cover; width: 100%;">
                                                </div>
                                            @else
                                                <div class="text-secondary mb-2">No image uploaded.</div>
                                            @endif
                                            <input type="file" class="form-control" name="image">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_image"
                                                    value="1" id="remove_image_{{ $slide->id }}">
                                                <label class="form-check-label"
                                                    for="remove_image_{{ $slide->id }}">Remove current image</label>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">Update Image</button>
                                        </div>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('admin.homepage-carousel-images.destroy', $slide) }}"
                                    class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('Delete this carousel image?')">
                                        Delete Image
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info mb-0">No homepage carousel images added yet.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
