<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        return view('frontend.about', [
            'about' => About::latest()->first(),
            'setting' => Setting::first(),
        ]);
    }
}
