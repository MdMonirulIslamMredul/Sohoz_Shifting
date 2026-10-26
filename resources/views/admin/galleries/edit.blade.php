@extends('admin.layouts.app')
@section('title', 'Edit Gallery Item')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Gallery Item</h4>
        <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.galleries._form')
            <button class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection
