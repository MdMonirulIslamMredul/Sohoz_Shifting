<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function photos()
    {
        return view('frontend.gallery.photos', [
            'items' => Gallery::where('status', true)->where('type', 'photo')->latest()->paginate(12),
        ]);
    }

    public function videos()
    {
        return view('frontend.gallery.videos', [
            'items' => Gallery::where('status', true)->where('type', 'video')->latest()->paginate(9),
        ]);
    }
}
