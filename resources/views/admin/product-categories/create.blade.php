@extends('admin.layouts.app')
@section('title', 'Create Product Category')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Product Category</h4>
        <form method="POST" action="{{ route('admin.product-categories.store') }}">
            @csrf
            @include('admin.product-categories._form')
            <button class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
@endsection
