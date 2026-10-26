<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutUnifiedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create([
            'site_name' => 'SolarTech Services',
            'about_content' => 'Initial footer about text',
            'cta_title' => 'Initial CTA Title',
            'cta_text' => 'Initial CTA Text',
            'cta_button_text' => 'Get Started',
            'cta_button_link' => '/contact',
        ]);

        About::create([
            'title' => 'About SolarTech',
            'page_details' => 'We provide leading clean solar systems.',
            'mission' => 'Leading the solar revolution.',
            'vision' => 'A 100% green energy world.',
            'history' => '<p>Founded in 2015 to power communities.</p>',
        ]);
    }

    protected function getAdminUser(): User
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'Web_Settings']);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin_about@admin.com',
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);
        $user->givePermissionTo('Web_Settings');

        return $user;
    }

    public function test_admin_can_view_unified_about_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/about');

        $response->assertStatus(200);
        $response->assertSee('Manage About Page &amp; Content', false);
        $response->assertSee('Our Mission');
        $response->assertSee('Our Vision');
        $response->assertSee('Our Journey / History');
        $response->assertSee('Footer Company Summary');
        $response->assertSee('Initial footer about text');
    }

    public function test_admin_can_update_about_content_mission_vision_history(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post('/admin/about', [
            'title' => 'Updated About Title',
            'page_details' => 'Updated lead intro text.',
            'mission' => 'Our updated daily mission.',
            'vision' => 'Our updated long-term vision.',
            'history' => '<p>Updated history timeline and milestones.</p>',
            'about_content' => 'New footer summary bio text.',
            'years_experience' => 15,
            'establishment_year' => 2011,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $about = About::first();
        $this->assertEquals('Updated About Title', $about->title);
        $this->assertEquals('Our updated daily mission.', $about->mission);
        $this->assertEquals('Our updated long-term vision.', $about->vision);
        $this->assertEquals('<p>Updated history timeline and milestones.</p>', $about->history);

        $setting = Setting::first();
        $this->assertEquals('New footer summary bio text.', $setting->about_content);
    }

    public function test_frontend_about_page_renders_mission_vision_and_history(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('Our Mission');
        $response->assertSee('Leading the solar revolution.');
        $response->assertSee('Our Vision');
        $response->assertSee('A 100% green energy world.');
        $response->assertSee('Our Journey &amp; History', false);
        $response->assertSee('Founded in 2015 to power communities.', false);
    }

    public function test_frontend_footer_displays_about_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Initial footer about text');
    }

    public function test_admin_can_update_cta_in_general_settings(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->post('/admin/settings', [
            'site_name' => 'SolarTech Services',
            'cta_title' => 'Ready to Power Your Home?',
            'cta_text' => 'Get a quote in less than 2 minutes.',
            'cta_button_text' => 'Claim Free Quote',
            'cta_button_link' => '/contact',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = Setting::first();
        $this->assertEquals('Ready to Power Your Home?', $setting->cta_title);
        $this->assertEquals('Claim Free Quote', $setting->cta_button_text);

        // Verify frontend homepage displays the updated CTA
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Ready to Power Your Home?');
        $homeResponse->assertSee('Claim Free Quote');
    }

    public function test_page_content_route_redirects_to_admin_about(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/page-content');

        $response->assertRedirect('/admin/about');
    }
}
