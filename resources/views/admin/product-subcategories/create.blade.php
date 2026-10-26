@extends('admin.layouts.app')
@section('title', 'Create Product Subcategory')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Product Subcategory</h4>
        <form method="POST" action="{{ route('admin.product-subcategories.store') }}">
            @csrf
            @include('admin.product-subcategories._form')
            <button class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
@endsection
