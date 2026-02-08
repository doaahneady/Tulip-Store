<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DemoAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demos = [
            [
                'email' => 'admin@tulipstore.com',
                'password' => 'password123',
                'roles' => ['is_admin' => true],
                'first_name' => 'Demo',
                'last_name' => 'Admin',
                'department' => 'Administration',
                'position' => 'System Administrator',
            ],
            [
                'email' => 'hr@tulipstore.com',
                'password' => 'password123',
                'roles' => ['is_hr' => true],
                'first_name' => 'Demo',
                'last_name' => 'HR',
                'department' => 'Human Resources',
                'position' => 'HR Manager',
            ],
            [
                'email' => 'finance@tulipstore.com',
                'password' => 'password123',
                'roles' => ['is_finance' => true],
                'first_name' => 'Demo',
                'last_name' => 'Finance',
                'department' => 'Finance',
                'position' => 'Finance Manager',
            ],
            [
                'email' => 'it@tulipstore.com',
                'password' => 'password123',
                'roles' => ['is_it' => true],
                'first_name' => 'Demo',
                'last_name' => 'IT',
                'department' => 'IT',
                'position' => 'IT Specialist',
            ],
            [
                'email' => 'support@tulipstore.com',
                'password' => 'password123',
                'roles' => ['is_cs' => true],
                'first_name' => 'Demo',
                'last_name' => 'Support',
                'department' => 'Customer Support',
                'position' => 'Support Agent',
            ],
        ];

        foreach ($demos as $demo) {
            $employee = Employee::updateOrCreate(
                ['email' => $demo['email']],
                [
                    'password' => Hash::make($demo['password']),
                    'first_name' => $demo['first_name'],
                    'last_name' => $demo['last_name'],
                    'department' => $demo['department'],
                    'position' => $demo['position'],
                    'employee_code' => strtoupper(substr($demo['department'], 0, 3)).rand(100, 999),
                    'phone' => '+1234567890',
                    'employment_type' => 'full_time',
                    'status' => 'active',
                    'salary' => 50000,
                    'hire_date' => now(),
                ]
            );

            $reset = [
                'is_admin' => false,
                'is_hr' => false,
                'is_cs' => false,
                'is_finance' => false,
                'is_it' => false,
                'is_driver_supervisor' => false,
                'is_trader' => false,
            ];

            $reset = array_filter($reset, fn ($_, $k) => Schema::hasColumn('employees', $k), ARRAY_FILTER_USE_BOTH);
            $roles = array_filter($demo['roles'], fn ($_, $k) => Schema::hasColumn('employees', $k), ARRAY_FILTER_USE_BOTH);

            if ($reset) {
                $employee->update($reset);
            }
            if ($roles) {
                $employee->update($roles);
            }
        }

        $this->command->info('Demo accounts created/updated successfully.');
        $this->command->table(
            ['Email', 'Password', 'Role'],
            collect($demos)->map(fn ($d) => [$d['email'], $d['password'], array_key_first($d['roles'])])
        );
    }
}
