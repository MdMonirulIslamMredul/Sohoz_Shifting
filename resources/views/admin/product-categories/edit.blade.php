@extends('admin.layouts.app')
@section('title', 'Edit Product Category')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Product Category</h4>
        <form method="POST" action="{{ route('admin.product-categories.update', $productCategory) }}">
            @csrf
            @method('PUT')
            @include('admin.product-categories._form')
            <button class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection
