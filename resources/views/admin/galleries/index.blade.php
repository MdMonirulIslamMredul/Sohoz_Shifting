@extends('admin.layouts.app')
@section('title', 'Gallery')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Gallery</h4><a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">Add Gallery Item</a>
    </div>
    <div class="card p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($galleries as $gallery)
                    <tr>
                        <td>{{ $gallery->title }}</td>
                        <td>{{ ucfirst($gallery->type) }}</td>
                        <td>{{ $gallery->status ? 'Active' : 'Inactive' }}</td>
                        <td class="text-end"><a href="{{ route('admin.galleries.edit', $gallery) }}"
                                class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" class="d-inline">
                                @csrf @method('DELETE')<button onclick="return confirm('Delete item?')"
                                    class="btn btn-sm btn-outline-danger">Delete</button></form>
                        </td>
                </tr>@empty<tr>
                        <td colspan="4">No gallery items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>{{ $galleries->links() }}
    </div>
@endsection
