@extends('admin.layouts.app')

@section('title', 'Create Permission')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Permission</h4>

        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Permission name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <button class="btn btn-primary">Save Permission</button>
        </form>
    </div>
@endsection
