<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSupervisorUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test driver supervisor user
        User::updateOrCreate(
            ['email' => 'supervisor@tulipstore.com'],
            [
                'name' => 'مشرف التوصيل',
                'username' => 'supervisor',
                'user_full_name' => 'مشرف التوصيل',
                'email' => 'supervisor@tulipstore.com',
                'password' => Hash::make('password123'),
                'mobile' => '0507777777',
                'is_driver_supervisor' => true,
                'email_verified_at' => now(),
            ]
        );

        // Also update existing admin to have driver supervisor access
        $admin = User::where('email', 'admin@tulipstore.com')->first();
        if ($admin) {
            $admin->update(['is_driver_supervisor' => true]);
        }

        echo "✅ Driver Supervisor user created!\n";
        echo "📧 Email: supervisor@tulipstore.com\n";
        echo "🔑 Password: password123\n";
        echo "\n";
        echo "✅ Admin user also granted driver supervisor access\n";
    }
}
