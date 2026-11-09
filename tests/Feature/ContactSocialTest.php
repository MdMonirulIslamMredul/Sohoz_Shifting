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

    public function test_admin_can_update_meta_pixel_id_and_fires_pageview_on_frontend(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post('/admin/contact-social', [
            'contact_email' => 'info@sohozshifting.com',
            'contact_phone' => '01700000000',
            'meta_pixel_id' => '1234567890123456',
            'ga4_measurement_id' => 'G-XYZ9876543',
            'whatsapp_tracking_number' => '8801700999999',
            'meta_capi_token' => 'EAAG_TEST_TOKEN_123',
            'enable_marketing_tracking' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = Setting::first();
        $this->assertEquals('1234567890123456', $setting->meta_pixel_id);
        $this->assertEquals('G-XYZ9876543', $setting->ga4_measurement_id);
        $this->assertEquals('8801700999999', $setting->whatsapp_tracking_number);
        $this->assertEquals('EAAG_TEST_TOKEN_123', $setting->meta_capi_token);
        $this->assertTrue($setting->enable_marketing_tracking);

        // Verify PageView and GA4 are present across frontend
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee("fbq('init', '1234567890123456');", false);
        $homeResponse->assertSee("fbq('track', 'PageView');", false);
        $homeResponse->assertSee('G-XYZ9876543');
    }

    public function test_facebook_pixel_fires_purchase_event_on_booking_confirmation(): void
    {
        $setting = Setting::first();
        $setting->update([
            'meta_pixel_id' => '1234567890123456',
            'enable_marketing_tracking' => true,
        ]);

        $booking = \App\Models\ShiftingBooking::create([
            'booking_code' => 'SH-2026-PIXEL1',
            'customer_name' => 'Pixel Test Customer',
            'customer_phone' => '01712345678',
            'pickup_address' => 'House 1, Road 2',
            'pickup_area' => 'Mirpur',
            'pickup_floor' => 2,
            'pickup_has_lift' => false,
            'delivery_address' => 'House 5, Road 6',
            'delivery_area' => 'Uttara',
            'delivery_floor' => 4,
            'delivery_has_lift' => true,
            'shifting_date' => now()->addDays(2)->toDateString(),
            'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
            'vehicle_type' => 'covered_9ft',
            'labour_count' => 3,
            'final_cost' => 12500.00,
            'status' => 'new_request',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->get(route('bookings.confirmation', $booking->booking_code));
        $response->assertStatus(200);
        $response->assertSee("fbq('track', 'Purchase'", false);
        $response->assertSee('SH-2026-PIXEL1');
        $response->assertSee('12500');
        $response->assertSee("fbq('track', 'Lead'", false);
    }
}
