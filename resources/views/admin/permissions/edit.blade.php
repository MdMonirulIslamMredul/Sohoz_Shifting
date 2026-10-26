@extends('admin.layouts.app')

@section('title', 'Edit Permission')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Permission</h4>

        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Permission name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
            </div>
            <button class="btn btn-primary">Update Permission</button>
        </form>
    </div>
@endsection
