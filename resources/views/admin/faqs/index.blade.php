@extends('admin.layouts.app')
@section('title', 'FAQs')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">FAQs</h4>
            <div class="text-secondary">Manage accordion questions that appear on the homepage.</div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Add FAQ</h5>
            <form method="POST" action="{{ route('admin.faqs.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Question</label>
                        <input type="text" name="question" class="form-control" value="{{ old('question') }}"
                            placeholder="Enter FAQ question">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Answer</label>
                        <textarea name="answer" class="form-control" rows="4" placeholder="Enter the answer">{{ old('answer') }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}"
                            min="0">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="faqStatusAdd"
                                checked>
                            <label class="form-check-label" for="faqStatusAdd">Active</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Add FAQ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($faqs as $faq)
            <div class="col-lg-6">
                <div class="card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h5 class="mb-1">FAQ #{{ $faq->id }}</h5>
                            <div class="text-secondary small">Homepage accordion item</div>
                        </div>
                        <span class="badge {{ $faq->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $faq->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Question</label>
                                <input type="text" name="question" class="form-control"
                                    value="{{ old('question', $faq->question) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Answer</label>
                                <textarea name="answer" class="form-control" rows="4">{{ old('answer', $faq->answer) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Order</label>
                                <input type="number" name="order" class="form-control"
                                    value="{{ old('order', $faq->order) }}" min="0">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" value="1"
                                        id="faqStatus{{ $faq->id }}"
                                        {{ old('status', $faq->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="faqStatus{{ $faq->id }}">Active</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirm('Delete this FAQ?')">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0">No FAQs found.</div>
            </div>
        @endforelse
    </div>
@endsection
