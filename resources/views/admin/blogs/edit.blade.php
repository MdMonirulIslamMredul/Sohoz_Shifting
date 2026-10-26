@extends('admin.layouts.app')
@section('title', 'Edit Blog')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Blog</h4>
        <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">@csrf
            @method('PUT')@include('admin.blogs._form')<button class="btn btn-primary mt-3">Update</button></form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#bodyEditor')).catch(error => console.error(error));
    </script>
@endpush
