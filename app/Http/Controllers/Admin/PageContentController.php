<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageContentController extends Controller
{
    public function edit(): View
    {
        return view('admin.page-content.edit', [
            'setting' => Setting::firstOrCreate([], [
                'site_name' => 'SolarTech Services',
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_intro' => ['nullable', 'string'],
            'cta_title' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string'],
            'cta_button_text' => ['nullable', 'string', 'max:120'],
            'cta_button_link' => ['nullable', 'string', 'max:255'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_content' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'history' => ['nullable', 'string'],
        ]);

        $setting = Setting::firstOrCreate([]);

        $setting->update($data);

        return back()->with('success', 'Page content updated.');
    }
}
