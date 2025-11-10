<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use App\\Models\\User;
use Illuminate\\Support\\Facades\\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountriesSeeder::class,
            CategorySeeder::class,
        ]);

        // Seed example users that match the register/login form
        User::updateOrCreate(
            ['email' => 'john@example.com'],
            [
                'username' => 'john_doe',
                'password' => Hash::make('password123'),
                'user_full_name' => 'John Doe',
                'mobile' => '+1234567890',
                'address' => '123 Main Street',
                'language' => 'english',
                'gender' => 'male',
                'currency' => 'USD',
                'verified' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'jane@example.com'],
            [
                'username' => 'jane_doe',
                'password' => Hash::make('password123'),
                'user_full_name' => 'Jane Doe',
                'mobile' => '+1987654321',
                'address' => '456 Market Street',
                'language' => 'english',
                'gender' => 'female',
                'currency' => 'USD',
                'verified' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'username' => 'test_user',
                'password' => Hash::make('password123'),
                'user_full_name' => 'Test User',
                'mobile' => '+10000000000',
                'address' => '789 Test Ave',
                'language' => 'english',
                'gender' => 'other',
                'currency' => 'USD',
                'verified' => false,
            ]
        );
    }
}
