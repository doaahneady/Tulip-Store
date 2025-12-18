<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DashboardUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'مدير النظام',
                'username' => 'admin',
                'email' => 'admin@tulip.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ],
            [
                'name' => 'مسؤول تقنية المعلومات',
                'username' => 'it_admin',
                'email' => 'it@tulip.com',
                'password' => Hash::make('password'),
                'is_it_super' => true,
                'is_it' => true,
            ],
            [
                'name' => 'موظف الموارد البشرية',
                'username' => 'hr_user',
                'email' => 'hr@tulip.com',
                'password' => Hash::make('password'),
                'is_hr' => true,
            ],
            [
                'name' => 'موظف خدمة العملاء',
                'username' => 'cs_user',
                'email' => 'cs@tulip.com',
                'password' => Hash::make('password'),
                'is_cs' => true,
            ],
            [
                'name' => 'المدير المالي',
                'username' => 'finance_user',
                'email' => 'finance@tulip.com',
                'password' => Hash::make('password'),
                'is_finance' => true,
            ],
            [
                'name' => 'المحاسب',
                'username' => 'accountant',
                'email' => 'accountant@tulip.com',
                'password' => Hash::make('password'),
                'is_accountant' => true,
            ],
            [
                'name' => 'مشرف التوصيل',
                'username' => 'delivery_sup',
                'email' => 'delivery@tulip.com',
                'password' => Hash::make('password'),
                'is_driver_supervisor' => true,
            ],
            [
                'name' => 'صاحب متجر',
                'username' => 'store_owner',
                'email' => 'store@tulip.com',
                'password' => Hash::make('password'),
                'is_trader' => true,
            ],
        ];

        foreach ($users as $userData) {
            $existingByEmail = User::where('email', $userData['email'])->first();
            $existingByUsername = User::where('username', $userData['username'])->first();
            
            if ($existingByEmail) {
                // Update existing user by email
                $existingByEmail->update(array_diff_key($userData, ['username' => '', 'email' => '']));
            } elseif ($existingByUsername) {
                // Update existing user by username
                $existingByUsername->update(array_diff_key($userData, ['username' => '']));
            } else {
                // Create new user
                User::create($userData);
            }
        }

        $this->command->info('Dashboard users created successfully!');
        $this->command->table(
            ['Email', 'Role'],
            collect($users)->map(fn($u) => [$u['email'], $this->getRole($u)])->toArray()
        );
    }

    private function getRole($user): string
    {
        if ($user['is_admin'] ?? false) return 'Admin';
        if ($user['is_it_super'] ?? false) return 'IT Supervisor';
        if ($user['is_hr'] ?? false) return 'HR';
        if ($user['is_cs'] ?? false) return 'Customer Service';
        if ($user['is_finance'] ?? false) return 'Finance';
        if ($user['is_accountant'] ?? false) return 'Accountant';
        if ($user['is_driver_supervisor'] ?? false) return 'Delivery Supervisor';
        if ($user['is_trader'] ?? false) return 'Store Owner';
        return 'User';
    }
}
