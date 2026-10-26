<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services.index', [
            'services' => Service::orderBy('sort_order')->orderBy('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validatedData($request, $service->id);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return back()->with('success', 'Service deleted successfully.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug,' . ($id ?? 'NULL') . ',id'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'price_range' => ['nullable', 'string', 'max:255'],
            'pricing_type' => ['nullable', 'string', 'in:fixed,range,negotiable,free_quote'],
            'show_price' => ['nullable', 'boolean'],
            'icon' => ['nullable', 'string', 'max:100'],
            'is_addon_for_booking' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['pricing_type'] = $data['pricing_type'] ?? 'negotiable';
        $data['show_price'] = $request->boolean('show_price');
        $data['is_addon_for_booking'] = $request->boolean('is_addon_for_booking', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['status'] = $request->boolean('status', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
