<?php

namespace App\Models;

use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'image',
        'price',
        'product_category_id',
        'product_subcategory_id',
        'is_featured',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(ProductSubcategory::class, 'product_subcategory_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
