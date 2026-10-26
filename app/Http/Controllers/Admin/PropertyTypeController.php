<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyTypeController extends Controller
{
    public function index()
    {
        return view('admin.property-types.index', [
            'propertyTypes' => PropertyType::orderBy('sort_order')->orderBy('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.property-types.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        PropertyType::create($data);

        return redirect()->route('admin.property-types.index')->with('success', 'Property Type created successfully.');
    }

    public function edit(PropertyType $propertyType)
    {
        return view('admin.property-types.edit', compact('propertyType'));
    }

    public function update(Request $request, PropertyType $propertyType)
    {
        $data = $this->validatedData($request, $propertyType->id);
        $propertyType->update($data);

        return redirect()->route('admin.property-types.index')->with('success', 'Property Type updated successfully.');
    }

    public function destroy(PropertyType $propertyType)
    {
        // Check if there are bookings using this property type
        if (\App\Models\ShiftingBooking::where('property_type_id', $propertyType->id)->exists()) {
            return back()->with('error', 'Cannot delete this property type because existing bookings reference it. You can set it to inactive instead.');
        }

        $propertyType->delete();

        return back()->with('success', 'Property Type deleted successfully.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:property_types,slug,' . ($id ?? 'NULL') . ',id'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
