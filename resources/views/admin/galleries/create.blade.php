@extends('admin.layouts.app')
@section('title', 'Create Gallery Item')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Gallery Item</h4>
        <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.galleries._form')
            <button class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
@endsection
