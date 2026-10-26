<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', true)->with(['category', 'subcategory']);

        // Search Keyword
        $search = trim($request->input('search') ?? $request->input('q') ?? '');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter (by slug or ID)
        $selectedCategory = null;
        if ($request->filled('category')) {
            $catParam = $request->input('category');
            $selectedCategory = ProductCategory::where('slug', $catParam)
                ->orWhere('id', $catParam)
                ->first();

            if ($selectedCategory) {
                $query->where('product_category_id', $selectedCategory->id);
            }
        }

        // Subcategory filter (by slug or ID)
        $selectedSubcategory = null;
        if ($request->filled('subcategory')) {
            $subParam = $request->input('subcategory');
            $selectedSubcategory = ProductSubcategory::where('slug', $subParam)
                ->orWhere('id', $subParam)
                ->first();

            if ($selectedSubcategory) {
                $query->where('product_subcategory_id', $selectedSubcategory->id);
            }
        }

        // Featured Filter
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Price range presets (BDT ৳)
        $priceRange = $request->input('price_range');
        if ($priceRange) {
            switch ($priceRange) {
                case 'under_10k':
                    $query->where('price', '<', 10000);
                    break;
                case '10k_50k':
                    $query->whereBetween('price', [10000, 50000]);
                    break;
                case '50k_150k':
                    $query->whereBetween('price', [50000, 150000]);
                    break;
                case '150k_500k':
                    $query->whereBetween('price', [150000, 500000]);
                    break;
                case 'above_500k':
                    $query->where('price', '>', 500000);
                    break;
            }
        }

        // Custom Min/Max price
        if ($request->filled('min_price') && is_numeric($request->input('min_price'))) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price') && is_numeric($request->input('max_price'))) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'featured':
                $query->orderByDesc('is_featured')->latest();
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('sort_order', 'asc')->latest();
                break;
        }

        $products = $query->paginate(9)->withQueryString();

        // Categories list with active products count and subcategories
        $categories = ProductCategory::where('status', true)
            ->withCount(['products' => function ($q) {
                $q->where('status', true);
            }])
            ->with(['subcategories' => function ($q) {
                $q->where('status', true)->withCount(['products' => function ($pq) {
                    $pq->where('status', true);
                }]);
            }])
            ->orderBy('sort_order')
            ->get();

        $totalActiveCount = Product::where('status', true)->count();
        $featuredCount = Product::where('status', true)->where('is_featured', true)->count();

        // Active filters count for badge
        $activeFiltersCount = 0;
        if ($search !== '') $activeFiltersCount++;
        if ($selectedCategory) $activeFiltersCount++;
        if ($selectedSubcategory) $activeFiltersCount++;
        if ($request->boolean('featured')) $activeFiltersCount++;
        if ($priceRange) $activeFiltersCount++;
        if ($request->filled('min_price') || $request->filled('max_price')) $activeFiltersCount++;

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'selectedCategory',
            'selectedSubcategory',
            'totalActiveCount',
            'featuredCount',
            'search',
            'sort',
            'priceRange',
            'activeFiltersCount'
        ));
    }

    public function show(Product $product)
    {
        abort_unless($product->status, 404);

        $product->load(['category', 'subcategory']);

        $relatedProducts = Product::where('status', true)
            ->where('id', '!=', $product->id)
            ->when($product->product_category_id, function ($q) use ($product) {
                $q->where('product_category_id', $product->product_category_id);
            })
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}

