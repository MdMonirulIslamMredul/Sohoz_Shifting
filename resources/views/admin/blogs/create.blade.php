@extends('admin.layouts.app')
@section('title', 'Create Blog')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Blog</h4>
        <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
            @csrf@include('admin.blogs._form')<button class="btn btn-primary mt-3">Save</button></form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#bodyEditor')).catch(error => console.error(error));
    </script>
@endpush
