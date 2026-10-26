<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductSubcategoryController extends Controller
{
    public function index()
    {
        return view('admin.product-subcategories.index', [
            'subcategories' => ProductSubcategory::with('category')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.product-subcategories.create', [
            'categories' => ProductCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        ProductSubcategory::create($data);

        return redirect()->route('admin.product-subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function edit(ProductSubcategory $productSubcategory)
    {
        return view('admin.product-subcategories.edit', [
            'productSubcategory' => $productSubcategory,
            'categories' => ProductCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, ProductSubcategory $productSubcategory)
    {
        $productSubcategory->update($this->validatedData($request, $productSubcategory->id));

        return redirect()->route('admin.product-subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(ProductSubcategory $productSubcategory)
    {
        $productSubcategory->delete();

        return back()->with('success', 'Subcategory deleted successfully.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:product_subcategories,slug,' . ($id ?? 'NULL') . ',id'],
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
