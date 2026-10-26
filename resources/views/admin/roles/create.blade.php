@extends('admin.layouts.app')

@section('title', 'Create Role')

@section('content')
    <div class="card p-4">
        <h4 class="mb-3">Create Role</h4>

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            @include('admin.roles._form', ['role' => null])
            <button class="btn btn-primary mt-3">Save Role</button>
        </form>
    </div>
@endsection
