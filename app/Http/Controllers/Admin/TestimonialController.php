<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::orderBy('id', 'desc')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added successfully.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }

            $data['image'] = $request->file('image')->store('testimonials', 'public');
        } else {
            $data['image'] = $testimonial->image;
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['rating'] = $data['rating'] ?? 5;
        $data['status'] = $request->boolean('status', true);

        return $data;
    }
}
