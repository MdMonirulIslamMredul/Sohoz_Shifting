@extends('admin.layouts.app')
@section('title', 'Product Subcategories')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Product Subcategories</h4>
        <a href="{{ route('admin.product-subcategories.create') }}" class="btn btn-primary">Add Subcategory</a>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcategories as $subcategory)
                        <tr>
                            <td>{{ $subcategory->name }}</td>
                            <td>{{ $subcategory->category->name ?? '—' }}</td>
                            <td>{{ $subcategory->slug }}</td>
                            <td>{{ $subcategory->status ? 'Active' : 'Inactive' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.product-subcategories.edit', $subcategory) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST"
                                    action="{{ route('admin.product-subcategories.destroy', $subcategory) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete subcategory?')"
                                        class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No subcategories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $subcategories->links() }}
    </div>
@endsection
