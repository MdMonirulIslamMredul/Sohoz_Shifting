@extends('admin.layouts.app')
@section('title', 'Edit Service')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Service</h4>
        <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">@csrf
            @method('PUT')@include('admin.services._form')<button class="btn btn-primary mt-3">Update</button></form>
    </div>
@endsection
