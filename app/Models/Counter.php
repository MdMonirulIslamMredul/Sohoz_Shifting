<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = [
        'title',
        'value',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
