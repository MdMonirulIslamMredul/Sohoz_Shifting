<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CounterController extends Controller
{
    public function index(): View
    {
        return view('admin.counters.index', [
            'counters' => Counter::orderBy('id', 'desc')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        Counter::create($data);

        return redirect()->route('admin.counters.index')->with('success', 'Counter added successfully.');
    }

    public function update(Request $request, Counter $counter): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('icon_file')) {
            if ($counter->icon && $this->isStoredIconPath($counter->icon)) {
                Storage::disk('public')->delete($counter->icon);
            }

            $data['icon'] = $request->file('icon_file')->store('counters', 'public');
        } elseif ($request->filled('icon')) {
            if ($counter->icon && $this->isStoredIconPath($counter->icon) && $request->input('icon') !== $counter->icon) {
                Storage::disk('public')->delete($counter->icon);
            }
        } else {
            $data['icon'] = $counter->icon;
        }

        $counter->update($data);

        return redirect()->route('admin.counters.index')->with('success', 'Counter updated successfully.');
    }

    public function destroy(Counter $counter): RedirectResponse
    {
        if ($counter->icon && $this->isStoredIconPath($counter->icon)) {
            Storage::disk('public')->delete($counter->icon);
        }

        $counter->delete();

        return redirect()->route('admin.counters.index')->with('success', 'Counter deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'value' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', 'max:255'],
            'icon_file' => ['nullable', 'image', 'max:2048'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status', true);

        if ($request->hasFile('icon_file')) {
            $data['icon'] = $request->file('icon_file')->store('counters', 'public');
        }

        unset($data['icon_file']);

        return $data;
    }

    private function isStoredIconPath(string $icon): bool
    {
        return str_contains($icon, '/');
    }
}
