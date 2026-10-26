<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create([
            'site_name' => 'Sohoz Shifting',
        ]);
    }

    public function test_admin_can_render_dashboard_without_errors(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::firstOrCreate(['name' => 'manage products']);

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test@admin.com',
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);
        $user->givePermissionTo('manage products');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }
}
