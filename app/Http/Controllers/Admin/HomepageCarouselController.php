<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageCarousel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomepageCarouselController extends Controller
{
    public function index(): View
    {
        return view('admin.homepage-carousel-images.index', [
            'slides' => HomepageCarousel::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request, true);
        $data['sort_order'] = ((int) HomepageCarousel::max('sort_order')) + 1;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero-slider', 'public');
        }

        HomepageCarousel::create($data);

        return redirect()->route('admin.homepage-carousel-images.index')
            ->with('success', 'Homepage carousel image added successfully.');
    }

    public function update(Request $request, HomepageCarousel $homepageCarousel): RedirectResponse
    {
        $data = $this->validatedData($request, false);

        if ($request->boolean('remove_image') && $homepageCarousel->image) {
            Storage::disk('public')->delete($homepageCarousel->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($homepageCarousel->image) {
                Storage::disk('public')->delete($homepageCarousel->image);
            }

            $data['image'] = $request->file('image')->store('hero-slider', 'public');
        }

        $homepageCarousel->update($data);

        return redirect()->route('admin.homepage-carousel-images.index')
            ->with('success', 'Homepage carousel image updated successfully.');
    }

    public function destroy(HomepageCarousel $homepageCarousel): RedirectResponse
    {
        if ($homepageCarousel->image) {
            Storage::disk('public')->delete($homepageCarousel->image);
        }

        $homepageCarousel->delete();

        return redirect()->route('admin.homepage-carousel-images.index')
            ->with('success', 'Homepage carousel image deleted successfully.');
    }

    private function validatedData(Request $request, bool $imageRequired): array
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'image' => array_merge($imageRequired ? ['required'] : ['nullable'], ['image', 'max:4096']),
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
