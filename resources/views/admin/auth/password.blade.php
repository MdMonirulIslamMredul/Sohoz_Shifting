@extends('admin.layouts.app')
@section('title', 'Change Password')
@section('content')
    <div class="card p-4" style="max-width:720px;">
        <h4 class="mb-3">Change Password</h4>
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            <div class="mb-3"><label class="form-label">Current Password</label><input type="password" name="current_password"
                    class="form-control" required></div>
            <div class="mb-3"><label class="form-label">New Password</label><input type="password" name="password"
                    class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Confirm New Password</label><input type="password"
                    name="password_confirmation" class="form-control" required></div>
            <button class="btn btn-primary">Update Password</button>
        </form>
    </div>
@endsection
