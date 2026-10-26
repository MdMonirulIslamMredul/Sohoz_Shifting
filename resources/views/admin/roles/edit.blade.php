@extends('admin.layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Edit Role</h4>

        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.roles._form', ['role' => $role])
            <button class="btn btn-primary mt-3">Update Role</button>
        </form>
    </div>
@endsection
