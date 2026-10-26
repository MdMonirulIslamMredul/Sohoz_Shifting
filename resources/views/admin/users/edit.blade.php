@extends('admin.layouts.app')

@section('title', 'Edit User Roles')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit User Roles</h4>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" value="{{ $user->name }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" value="{{ $user->email }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Assign Roles</label>
                <div class="row g-2">
                    @foreach ($roles as $role)
                        <div class="col-md-4">
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                <span class="form-check-label">{{ $role->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button class="btn btn-primary">Update Roles</button>
        </form>
    </div>
@endsection
