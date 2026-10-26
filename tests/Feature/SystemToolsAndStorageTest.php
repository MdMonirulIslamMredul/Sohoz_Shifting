<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SystemToolsAndStorageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::firstOrCreate(['name' => 'Web_Settings', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo('Web_Settings');
    }

    public function test_storage_fallback_route_serves_uploaded_file_without_symlink(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-banner.jpg');
        $storedPath = $file->store('blogs', 'public');

        $response = $this->get('/storage/' . $storedPath);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_storage_fallback_route_returns_404_for_missing_file(): void
    {
        Storage::fake('public');

        $response = $this->get('/storage/blogs/non-existent-image.jpg');

        $response->assertStatus(404);
    }

    public function test_admin_can_access_system_tools_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->get(route('admin.system-tools.index'));

        $response->assertStatus(200);
        $response->assertSee('System &amp; Maintenance Tools', false);
        $response->assertSee('Server Diagnostics &amp; Health', false);
    }

    public function test_admin_can_clear_cache_via_web(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->post(route('admin.system-tools.clear-cache'));

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admin_can_trigger_link_storage_via_web(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->post(route('admin.system-tools.link-storage'));

        $response->assertRedirect();
    }
}
