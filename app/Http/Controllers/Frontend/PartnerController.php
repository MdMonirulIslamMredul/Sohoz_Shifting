<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Setting;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partner::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $setting = Setting::first();

        return view('frontend.partners.index', [
            'partners' => $partners,
            'setting' => $setting,
        ]);
    }
}
