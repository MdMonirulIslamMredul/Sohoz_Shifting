@extends('admin.layouts.app')
@section('title', 'Blogs')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Blogs</h4><a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">Add Blog</a>
    </div>
    <div class="card p-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Published</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                    <tr>
                        <td>{{ $blog->title }}</td>
                        <td>{{ $blog->is_published ? 'Yes' : 'No' }}</td>
                        <td class="text-end"><a href="{{ route('admin.blogs.edit', $blog) }}"
                                class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" class="d-inline">@csrf
                                @method('DELETE')<button onclick="return confirm('Delete item?')"
                                    class="btn btn-sm btn-outline-danger">Delete</button></form>
                        </td>
                </tr>@empty<tr>
                        <td colspan="3">No blogs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>{{ $blogs->links() }}
    </div>
@endsection
