@extends('admin.layouts.app')
@section('title', 'Edit Product Subcategory')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Product Subcategory</h4>
        <form method="POST" action="{{ route('admin.product-subcategories.update', $productSubcategory) }}">
            @csrf
            @method('PUT')
            @include('admin.product-subcategories._form')
            <button class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection
