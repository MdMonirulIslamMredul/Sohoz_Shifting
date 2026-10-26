<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;
use App\Models\ProductSubcategory;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function subcategories(): HasMany
    {
        return $this->hasMany(ProductSubcategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
