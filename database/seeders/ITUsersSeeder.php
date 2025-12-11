<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ITUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create IT Supervisor
        User::updateOrCreate(
            ['email' => 'it.supervisor@tulipstore.com'],
            [
                'name' => 'IT Supervisor',
                'username' => 'it_supervisor',
                'password' => Hash::make('it123'),
                'is_admin' => false,
                'is_it_super' => true,
                'is_it' => false,
                'email_verified_at' => now(),
            ]
        );

        // Create IT Crew Member
        User::updateOrCreate(
            ['email' => 'it.crew@tulipstore.com'],
            [
                'name' => 'IT Crew Member',
                'username' => 'it_crew',
                'password' => Hash::make('it123'),
                'is_admin' => false,
                'is_it_super' => false,
                'is_it' => true,
                'email_verified_at' => now(),
            ]
        );

        echo "IT users created successfully!\n";
        echo "IT Supervisor: it.supervisor@tulipstore.com / it123\n";
        echo "IT Crew: it.crew@tulipstore.com / it123\n";
    }
}
