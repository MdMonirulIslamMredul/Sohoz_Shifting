@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">My Profile</h4>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input class="form-control" value="{{ auth()->user()->email }}" readonly>
            </div>
            <div class="col-12">
                <label class="form-label">Role</label>
                <input class="form-control" value="{{ auth()->user()->roles->pluck('name')->join(', ') }}" readonly>
            </div>
        </div>
    </div>
@endsection
