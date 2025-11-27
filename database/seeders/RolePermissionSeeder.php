<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
  public function run(): void
  {
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create roles
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'customer']);

    // You can create permissions here if needed
    // Permission::create(['name' => 'edit articles']);
    // And assign them to roles
    // $role->givePermissionTo('edit articles');
  }
}
