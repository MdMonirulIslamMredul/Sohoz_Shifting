<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Partner::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $partners = $query->orderBy('sort_order')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.partners.index', [
            'partners' => $partners,
            'search' => $request->input('search'),
            'totalPartners' => Partner::count(),
            'activePartners' => Partner::where('status', true)->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.partners.create', [
            'partner' => new Partner(),
            'nextSortOrder' => ((int) Partner::max('sort_order')) + 1,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request, true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('partners', 'public');
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner added successfully.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.edit', [
            'partner' => $partner,
        ]);
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $data = $this->validatedData($request, false);

        if ($request->hasFile('image')) {
            if ($partner->image && Storage::disk('public')->exists($partner->image)) {
                Storage::disk('public')->delete($partner->image);
            }
            $data['image'] = $request->file('image')->store('partners', 'public');
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        if ($partner->image && Storage::disk('public')->exists($partner->image)) {
            Storage::disk('public')->delete($partner->image);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner deleted successfully.');
    }

    private function validatedData(Request $request, bool $imageRequired): array
    {
        $imageRule = $imageRequired
            ? ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096']
            : ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => $imageRule,
            'website_url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = isset($data['sort_order']) && $data['sort_order'] !== ''
            ? (int) $data['sort_order']
            : (((int) Partner::max('sort_order')) + 1);

        return $data;
    }
}
