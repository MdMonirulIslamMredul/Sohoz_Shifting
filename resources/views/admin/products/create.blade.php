@extends('admin.layouts.app')
@section('title', 'Create Product')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Product</h4>
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.products._form')
            <button class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
@endsection
