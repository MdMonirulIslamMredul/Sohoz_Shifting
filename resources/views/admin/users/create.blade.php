@extends('admin.layouts.app')

@section('title', 'Register User')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Register User</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Select Roles</label>
                    <div class="small text-muted mb-2">Assign specific admin roles like Super Admin, Branch Admin, or
                        Product Admin.</div>
                    <div class="row g-2">
                        @foreach ($roles as $role)
                            <div class="col-md-4">
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="roles[]"
                                        value="{{ $role->name }}"
                                        {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                    <span class="form-check-label">{{ $role->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-3">Register User</button>
        </form>
    </div>
@endsection
