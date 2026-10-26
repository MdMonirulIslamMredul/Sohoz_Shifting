<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::where('is_published', true);

        // Search Filter
        $search = trim($request->input('search') ?? $request->input('q') ?? '');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhere('seo_keywords', 'like', "%{$search}%");
            });
        }

        // Year filter
        $selectedYear = $request->input('year');
        if ($selectedYear && is_numeric($selectedYear)) {
            $query->whereYear('published_at', $selectedYear);
        }

        // Month filter (1 - 12)
        $selectedMonth = $request->input('month');
        if ($selectedMonth && is_numeric($selectedMonth)) {
            $query->whereMonth('published_at', $selectedMonth);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc')->orderBy('id', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('published_at', 'desc')->orderBy('id', 'desc');
                break;
        }

        $blogs = $query->paginate(6)->withQueryString();

        $totalCount = Blog::where('is_published', true)->count();

        // Recent posts for sidebar
        $recentBlogs = Blog::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // Monthly Archives list with counts
        $archives = Blog::where('is_published', true)
            ->whereNotNull('published_at')
            ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, count(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Active filters count
        $activeFiltersCount = 0;
        if ($search !== '') $activeFiltersCount++;
        if ($selectedYear) $activeFiltersCount++;
        if ($selectedMonth) $activeFiltersCount++;

        return view('frontend.blogs.index', compact(
            'blogs',
            'recentBlogs',
            'archives',
            'totalCount',
            'search',
            'selectedYear',
            'selectedMonth',
            'sort',
            'activeFiltersCount'
        ));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_published, 404);

        $recentBlogs = Blog::where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        $archives = Blog::where('is_published', true)
            ->whereNotNull('published_at')
            ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, count(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        $prevBlog = Blog::where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->where('published_at', '<=', $blog->published_at ?? now())
            ->orderBy('published_at', 'desc')
            ->first();

        $nextBlog = Blog::where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->where('published_at', '>=', $blog->published_at ?? now())
            ->orderBy('published_at', 'asc')
            ->first();

        return view('frontend.blogs.show', compact('blog', 'recentBlogs', 'archives', 'prevBlog', 'nextBlog'));
    }
}

