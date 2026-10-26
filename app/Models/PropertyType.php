<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'base_price',
        'icon',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function getDisplayNameAttribute(): string
    {
        if (!empty($this->name_bn) && !empty($this->name)) {
            return "{$this->name_bn} ({$this->name})";
        }

        return $this->name_bn ?: $this->name;
    }

    protected static function booted()
    {
        static::creating(function ($propertyType) {
            if (empty($propertyType->slug)) {
                $propertyType->slug = Str::slug($propertyType->name);
            }
        });
    }
}
