@extends('admin.layouts.app')
@section('title', 'Edit Product')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Product</h4>
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">@csrf
            @method('PUT')@include('admin.products._form')<button class="btn btn-primary mt-3">Update</button></form>
    </div>
@endsection
