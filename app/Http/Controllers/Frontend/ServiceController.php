<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::where('status', true);

        // Search Keyword
        $search = trim($request->input('search') ?? $request->input('q') ?? '');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Featured Filter
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $services = $query->orderBy('sort_order')
                          ->orderByDesc('is_featured')
                          ->latest()
                          ->paginate(9)
                          ->withQueryString();

        $totalActiveCount = Service::where('status', true)->count();
        $featuredCount = Service::where('status', true)->where('is_featured', true)->count();

        return view('frontend.services.index', compact(
            'services',
            'totalActiveCount',
            'featuredCount',
            'search'
        ));
    }

    public function show(Service $service)
    {
        abort_unless($service->status, 404);

        $otherServices = Service::where('status', true)
            ->where('id', '!=', $service->id)
            ->orderBy('sort_order')
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.services.show', compact('service', 'otherServices'));
    }
}

