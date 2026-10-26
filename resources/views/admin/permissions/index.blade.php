@extends('admin.layouts.app')

@section('title', 'Permissions')

@section('content')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Permissions</h4>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">Create Permission</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.permissions.edit', $permission) }}"
                                class="btn btn-sm btn-outline-primary me-2">Edit</a>
                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">{{ $permissions->links() }}</div>
    </div>
@endsection
