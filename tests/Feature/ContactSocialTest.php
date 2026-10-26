<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSocialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create([
            'site_name' => 'SolarTech Services',
            'contact_email' => 'old@example.com',
            'contact_phone' => '+1 111-222-3333',
            'contact_address' => 'Old Address',
            'google_map_embed' => 'https://maps.google.com/old',
            'social_links' => [
                'facebook' => 'https://facebook.com/old',
                'linkedin' => 'https://linkedin.com/old',
                'youtube' => 'https://youtube.com/old',
            ],
        ]);
    }

    protected function getAdminUser(): User
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'Web_Settings']);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test@admin.com',
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);
        $user->givePermissionTo('Web_Settings');

        return $user;
    }

    public function test_admin_can_view_contact_and_social_links_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/contact-social');

        $response->assertStatus(200);
        $response->assertSee('Contact &amp; Social Links', false);
        $response->assertSee('old@example.com');
        $response->assertSee('https://facebook.com/old');
    }

    public function test_admin_can_update_contact_and_social_links(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post('/admin/contact-social', [
            'contact_email' => 'new@solartech.com',
            'contact_phone' => '+1 (555) 987-6543',
            'contact_address' => '789 Sun Blvd, Solar City, CA',
            'google_map_embed' => 'https://maps.google.com/new',
            'facebook_url' => 'https://facebook.com/solartech',
            'twitter_url' => 'https://x.com/solartech',
            'instagram_url' => 'https://instagram.com/solartech',
            'linkedin_url' => 'https://linkedin.com/company/solartech',
            'youtube_url' => 'https://youtube.com/@solartech',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = Setting::first();
        $this->assertEquals('new@solartech.com', $setting->contact_email);
        $this->assertEquals('+1 (555) 987-6543', $setting->contact_phone);
        $this->assertEquals('789 Sun Blvd, Solar City, CA', $setting->contact_address);
        $this->assertEquals('https://maps.google.com/new', $setting->google_map_embed);
        $this->assertEquals('https://facebook.com/solartech', $setting->social_links['facebook'] ?? null);
        $this->assertEquals('https://x.com/solartech', $setting->social_links['twitter'] ?? null);
        $this->assertEquals('https://instagram.com/solartech', $setting->social_links['instagram'] ?? null);
        $this->assertEquals('https://linkedin.com/company/solartech', $setting->social_links['linkedin'] ?? null);
        $this->assertEquals('https://youtube.com/@solartech', $setting->social_links['youtube'] ?? null);
    }

    public function test_frontend_footer_displays_all_social_links_and_google_map(): void
    {
        $setting = Setting::first();
        $setting->update([
            'social_links' => [
                'facebook' => 'https://facebook.com/solartech',
                'twitter' => 'https://x.com/solartech',
                'instagram' => 'https://instagram.com/solartech',
                'linkedin' => 'https://linkedin.com/company/solartech',
                'youtube' => 'https://youtube.com/@solartech',
            ],
            'google_map_embed' => 'https://maps.google.com/embed/testmap',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('https://x.com/solartech');
        $response->assertSee('https://instagram.com/solartech');
        $response->assertSee('bi-twitter-x');
        $response->assertSee('bi-instagram');
        $response->assertSee('https://maps.google.com/embed/testmap');
    }
}
