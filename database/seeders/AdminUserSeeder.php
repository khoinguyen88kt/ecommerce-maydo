<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
  public function run(): void
  {
    // Create admin user
    $admin = User::firstOrCreate(
      ['email' => 'admin@example.com'],
      [
        'name' => 'Admin',
        'password' => Hash::make('password'),
      ]
    );

    // Assign admin role
    $admin->assignRole('admin');

    $this->command->info('Admin user created: admin@example.com / password');
  }
}
