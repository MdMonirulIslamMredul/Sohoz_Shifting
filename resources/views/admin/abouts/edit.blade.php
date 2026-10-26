@extends('admin.layouts.app')
@section('title', 'Manage About Page Content')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Manage About Page &amp; Content</h4>
            <div class="text-secondary">Configure your company narrative, mission, vision, milestone history, and footer summary.</div>
        </div>
        <div>
            <a href="{{ route('about') }}" target="_blank" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-up-right"></i> Preview About Page
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.abouts._form')
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('.about-editor').forEach((editor) => {
            ClassicEditor.create(editor, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
            }).catch((error) => console.error(error));
        });
    </script>
@endpush
