<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Models\Setting::create([
            'site_name' => 'SolarTech Test',
        ]);
        $this->seed(\Database\Seeders\PartnerSeeder::class);
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

    public function test_homepage_shows_partner_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('partners-infinity-section');
    }

    public function test_frontend_partners_page_renders_successfully(): void
    {
        $response = $this->get('/partners');

        $response->assertStatus(200);
        $response->assertSee('Strategic Partners');
    }

    public function test_admin_partners_index_renders_for_authorized_admin(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/partners');

        $response->assertSee('Partners');
    }

    public function test_admin_partners_create_page_renders(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/partners/create');

        $response->assertStatus(200);
        $response->assertSee('Add New Partner');
    }

    public function test_admin_can_store_partner(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $file = UploadedFile::fake()->image('test_partner.png', 200, 100);

        $response = $this->actingAs($admin)->post('/admin/partners', [
            'name' => 'Feature Test Partner',
            'website_url' => 'https://featuretest.com',
            'description' => 'Test partner description',
            'sort_order' => 99,
            'status' => '1',
            'image' => $file,
        ]);

        $response->assertRedirect('/admin/partners');
        $this->assertDatabaseHas('partners', [
            'name' => 'Feature Test Partner',
            'website_url' => 'https://featuretest.com',
        ]);
    }

    public function test_admin_can_edit_and_update_partner(): void
    {
        $admin = $this->getAdminUser();
        $partner = Partner::first();

        $this->assertNotNull($partner);

        $response = $this->actingAs($admin)->get('/admin/partners/' . $partner->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit Partner');

        $updateResponse = $this->actingAs($admin)->put('/admin/partners/' . $partner->id, [
            'name' => $partner->name . ' Updated',
            'website_url' => $partner->website_url,
            'description' => 'Updated description',
            'sort_order' => $partner->sort_order,
            'status' => '1',
        ]);

        $updateResponse->assertRedirect('/admin/partners');
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'name' => $partner->name . ' Updated',
        ]);
    }
}
