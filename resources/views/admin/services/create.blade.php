@extends('admin.layouts.app')
@section('title', 'Create Service')
@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Service</h4>
        <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
            @csrf@include('admin.services._form')<button class="btn btn-primary mt-3">Save</button></form>
    </div>
@endsection
