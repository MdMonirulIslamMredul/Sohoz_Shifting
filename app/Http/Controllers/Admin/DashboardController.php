<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'services' => Service::count(),
                'blogs' => Blog::count(),
                'team_members' => TeamMember::count(),
                'partners' => Partner::count(),
                'galleries' => Gallery::count(),
                'contacts' => Contact::count(),
            ],
            'recentContacts' => Contact::latest()->take(5)->get(),
        ]);
    }
}
