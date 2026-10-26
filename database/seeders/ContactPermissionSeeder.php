<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ContactPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $perm = Permission::firstOrCreate(['name' => 'manage contacts']);

        if ($superAdmin = Role::where('name', 'Super Admin')->first()) {
            $superAdmin->givePermissionTo($perm);
        }

        if ($admin = Role::where('name', 'Admin')->first()) {
            $admin->givePermissionTo($perm);
        }
    }
}
