<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = TeamMember::where('status', true);

        // Search query (name, designation, bio)
        $search = trim($request->input('search') ?? $request->input('q') ?? '');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        // Designation filter
        $selectedDesignation = trim($request->input('designation') ?? '');
        if ($selectedDesignation !== '') {
            $query->where('designation', $selectedDesignation);
        }

        // Sorting
        $sort = $request->input('sort', 'order');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'order':
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
                break;
        }

        $teamMembers = $query->paginate(12)->withQueryString();
        $totalCount = TeamMember::where('status', true)->count();

        // Unique designations for filter pills
        $designations = TeamMember::where('status', true)
            ->whereNotNull('designation')
            ->where('designation', '!=', '')
            ->pluck('designation')
            ->unique()
            ->values();

        return view('frontend.team.index', compact(
            'teamMembers',
            'search',
            'selectedDesignation',
            'designations',
            'totalCount',
            'sort'
        ));
    }
}
