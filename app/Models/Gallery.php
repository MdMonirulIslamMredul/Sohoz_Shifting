<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'type',
        'image',
        'video_url',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
