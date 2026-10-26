@extends('admin.layouts.app')
@section('title', 'Product Categories')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Product Categories</h4>
        <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">Add Category</a>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->sort_order }}</td>
                            <td>{{ $category->status ? 'Active' : 'Inactive' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.product-categories.edit', $category) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.product-categories.destroy', $category) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete category?')"
                                        class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
    </div>
@endsection
