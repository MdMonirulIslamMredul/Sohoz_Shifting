<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        return view('admin.galleries.index', [
            'galleries' => Gallery::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        Gallery::create($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item added successfully.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return back()->with('success', 'Gallery item deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:photo,video'],
            'description' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if (($data['type'] ?? null) === 'video') {
            $data['image'] = null;
        } else {
            $data['video_url'] = null;
        }

        $data['status'] = $request->boolean('status', true);

        return $data;
    }
}
