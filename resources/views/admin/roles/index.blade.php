@extends('admin.layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Roles</h4>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Create Role</a>
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
                    <th>Permissions</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->permissions_count }}</td>
                        <td>{{ $role->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.roles.edit', $role) }}"
                                class="btn btn-sm btn-outline-primary me-2">Edit</a>
                            @if ($role->name !== 'Admin')
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $roles->links() }}</div>
    </div>
@endsection
