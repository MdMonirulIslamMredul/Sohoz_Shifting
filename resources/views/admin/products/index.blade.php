@extends('admin.layouts.app')
@section('title', 'Products')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Products</h4><a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->title }}</td>
                            <td>
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}"
                                        class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>

                            <td>{{ $product->price }}</td>
                            <td>{{ $product->status ? 'Active' : 'Inactive' }}</td>
                            <td class="text-end"><a href="{{ route('admin.products.edit', $product) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    class="d-inline">@csrf @method('DELETE')<button onclick="return confirm('Delete item?')"
                                        class="btn btn-sm btn-outline-danger">Delete</button></form>
                            </td>
                    </tr>@empty<tr>
                            <td colspan="4">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>{{ $products->links() }}
    </div>
@endsection
