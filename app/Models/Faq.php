<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
        'status' => 'boolean',
    ];
}
