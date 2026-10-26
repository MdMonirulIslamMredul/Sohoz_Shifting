@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Users</h4>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Register User</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="btn btn-sm btn-outline-primary me-2">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $users->links() }}</div>
    </div>
@endsection
