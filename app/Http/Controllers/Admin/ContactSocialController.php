<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSocialController extends Controller
{
    public function edit(): View
    {
        return view('admin.contact-social.edit', [
            'setting' => Setting::firstOrCreate([], [
                'site_name' => 'SolarTech Services',
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'contact_email' => ['nullable', 'email', 'max:160'],
            'contact_phone' => ['nullable', 'string', 'max:60'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'google_map_embed' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        $setting = Setting::firstOrCreate([]);

        $socialLinks = $setting->social_links ?? [];
        $socialLinks['facebook'] = $request->input('facebook_url');
        $socialLinks['twitter'] = $request->input('twitter_url');
        $socialLinks['x'] = $request->input('twitter_url');
        $socialLinks['instagram'] = $request->input('instagram_url');
        $socialLinks['linkedin'] = $request->input('linkedin_url');
        $socialLinks['youtube'] = $request->input('youtube_url');

        $setting->update([
            'contact_email' => $request->input('contact_email'),
            'contact_phone' => $request->input('contact_phone'),
            'contact_address' => $request->input('contact_address'),
            'google_map_embed' => $request->input('google_map_embed'),
            'social_links' => $socialLinks,
        ]);

        return back()->with('success', 'Contact information and social links updated successfully.');
    }
}
