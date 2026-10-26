<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'price',
        'price_range',
        'pricing_type',
        'show_price',
        'icon',
        'is_addon_for_booking',
        'image',
        'is_featured',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'show_price' => 'boolean',
        'is_addon_for_booking' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
