<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users
        $users = [
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'user_full_name' => 'John Doe',
                'mobile' => '+1234567890',
                'address' => '123 Main Street, City, Country',
                'language' => 'english',
                'verified' => true,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'user_full_name' => 'Jane Smith',
                'mobile' => '+1234567891',
                'address' => '456 Oak Avenue, City, Country',
                'language' => 'english',
                'verified' => true,
                'email_verified_at' => Carbon::now(),
            ],
            [
                'username' => 'test_user',
            'email' => 'test@example.com',
                'password' => Hash::make('password123'),
                'user_full_name' => 'Test User',
                'mobile' => '+1234567892',
                'address' => '789 Pine Road, City, Country',
                'language' => 'english',
                'verified' => false,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('Test users created successfully!');
        $this->command->info('Email: john@example.com, Password: password123');
        $this->command->info('Email: jane@example.com, Password: password123');
        $this->command->info('Email: test@example.com, Password: password123 (unverified)');
    }
}
