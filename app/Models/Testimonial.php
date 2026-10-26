<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'message',
        'image',
        'rating',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => 'boolean',
    ];
}
