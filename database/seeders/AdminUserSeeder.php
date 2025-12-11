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
        User::updateOrCreate(
            ['email' => 'admin@tulipstore.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'email' => 'admin@tulipstore.com',
                'phone' => '+1234567890',
                'is_admin' => true,
                'verified' => true,
                'is_trader' => false,
            ]
        );

        // Create a regular test user
        User::updateOrCreate(
            ['email' => 'user@tulipstore.com'],
            [
                'name' => 'Test User',
                'username' => 'testuser',
                'password' => Hash::make('user123'),
                'email' => 'user@tulipstore.com',
                'phone' => '+0987654321',
                'is_admin' => false,
                'verified' => true,
                'is_trader' => false,
            ]
        );

        echo "✅ Admin user created: admin@tulipstore.com / admin123\n";
        echo "✅ Test user created: user@tulipstore.com / user123\n";
    }
}
