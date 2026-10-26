<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return view('admin.product-categories.index', [
            'categories' => ProductCategory::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.product-categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        ProductCategory::create($data);

        return redirect()->route('admin.product-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product-categories.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $productCategory->update($this->validatedData($request, $productCategory->id));

        return redirect()->route('admin.product-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return back()->with('success', 'Category deleted successfully.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:product_categories,slug,' . ($id ?? 'NULL') . ',id'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['status'] = $request->boolean('status', false);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
