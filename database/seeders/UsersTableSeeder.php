<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'web']
        );

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make(value: 'Admin@123'),
                 'status'   => 1, 
            ]
        );

        // Assign admin role
        $admin->assignRole($adminRole);
    }
}
