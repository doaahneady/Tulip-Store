<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DashboardTestEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            // Super Admin - Access to all dashboards
            [
                'employee_code' => 'SA001',
                'first_name' => 'أحمد',
                'last_name' => 'المدير العام',
                'email' => 'admin@tulipstore.com',
                'password' => Hash::make('password123'),
                'phone' => '+963-11-1234567',
                'department' => 'الإدارة',
                'position' => 'المدير العام',
                'employment_type' => 'full_time',
                'hire_date' => '2020-01-01',
                'status' => 'active',
                'salary' => 150000.00,
                'city' => 'دمشق',
                'country' => 'سوريا',
                'gender' => 'male',
                'email_verified_at' => now(),
                // Roles
                'is_admin' => true,
                'is_it' => true,
                'is_hr' => true,
                'is_finance' => true,
                'is_driver_supervisor' => true,
                'is_trader' => true,
            ],
            // IT/DevOps Employee
            [
                'employee_code' => 'IT001',
                'first_name' => 'محمد',
                'last_name' => 'الشبكة',
                'email' => 'it@tulipstore.com',
                'password' => Hash::make('password123'),
                'phone' => '+963-11-2345678',
                'department' => 'تقنية المعلومات',
                'position' => 'مدير تقنية المعلومات',
                'employment_type' => 'full_time',
                'hire_date' => '2021-03-15',
                'status' => 'active',
                'salary' => 120000.00,
                'city' => 'دمشق',
                'country' => 'سوريا',
                'gender' => 'male',
                'email_verified_at' => now(),
                // Roles
                'is_it' => true,
            ],
            // HR Employee
            [
                'employee_code' => 'HR001',
                'first_name' => 'فاطمة',
                'last_name' => 'الموارد البشرية',
                'email' => 'hr@tulipstore.com',
                'password' => Hash::make('password123'),
                'phone' => '+963-11-3456789',
                'department' => 'الموارد البشرية',
                'position' => 'مدير الموارد البشرية',
                'employment_type' => 'full_time',
                'hire_date' => '2021-06-01',
                'status' => 'active',
                'salary' => 110000.00,
                'city' => 'دمشق',
                'country' => 'سوريا',
                'gender' => 'female',
                'email_verified_at' => now(),
                // Roles
                'is_hr' => true,
            ],
            // Finance Employee
            [
                'employee_code' => 'FIN001',
                'first_name' => 'خالد',
                'last_name' => 'المالي',
                'email' => 'finance@tulipstore.com',
                'password' => Hash::make('password123'),
                'phone' => '+963-11-4567890',
                'department' => 'المالية',
                'position' => 'مدير مالي',
                'employment_type' => 'full_time',
                'hire_date' => '2021-02-10',
                'status' => 'active',
                'salary' => 130000.00,
                'city' => 'دمشق',
                'country' => 'سوريا',
                'gender' => 'male',
                'email_verified_at' => now(),
                // Roles
                'is_finance' => true,
            ],
            // Driver Supervisor Employee
            [
                'employee_code' => 'SUP001',
                'first_name' => 'علي',
                'last_name' => 'التوصيل',
                'email' => 'supervisor@tulipstore.com',
                'password' => Hash::make('password123'),
                'phone' => '+963-11-5678901',
                'department' => 'التوصيل',
                'position' => 'مشرف التوصيل',
                'employment_type' => 'full_time',
                'hire_date' => '2021-04-20',
                'status' => 'active',
                'salary' => 95000.00,
                'city' => 'دمشق',
                'country' => 'سوريا',
                'gender' => 'male',
                'email_verified_at' => now(),
                // Roles
                'is_driver_supervisor' => true,
            ],
            // Vendor/Trader Employee (Product Owner)
            [
                'employee_code' => 'VEN001',
                'first_name' => 'سارة',
                'last_name' => 'التاجر',
                'email' => 'vendor@tulipstore.com',
                'password' => Hash::make('password123'),
                'phone' => '+963-11-6789012',
                'department' => 'المتاجر',
                'position' => 'صاحب متجر',
                'employment_type' => 'full_time',
                'hire_date' => '2021-05-12',
                'status' => 'active',
                'salary' => 85000.00,
                'city' => 'دمشق',
                'country' => 'سوريا',
                'gender' => 'female',
                'email_verified_at' => now(),
                // Roles
                'is_trader' => true,
            ],
        ];

        $this->command->info('Creating test employees for dashboards...');
        $this->command->newLine();

        $created = [];
        foreach ($employees as $employeeData) {
            $employee = Employee::updateOrCreate(
                ['email' => $employeeData['email']],
                $employeeData
            );

            $roles = [];
            if ($employee->is_admin) {
                $roles[] = 'Super Admin';
            }
            if ($employee->is_it) {
                $roles[] = 'IT/DevOps';
            }
            if ($employee->is_hr) {
                $roles[] = 'HR';
            }
            if ($employee->is_finance) {
                $roles[] = 'Finance';
            }
            if ($employee->is_driver_supervisor) {
                $roles[] = 'Driver Supervisor';
            }
            if ($employee->is_trader) {
                $roles[] = 'Vendor';
            }

            $created[] = [
                'Email' => $employee->email,
                'Password' => 'password123',
                'Name' => $employee->first_name.' '.$employee->last_name,
                'Roles' => implode(', ', $roles),
                'Code' => $employee->employee_code,
            ];
        }

        $this->command->table(
            ['Email', 'Password', 'Name', 'Roles', 'Code'],
            $created
        );

        $this->command->newLine();
        $this->command->info('✅ Test employees created successfully!');
        $this->command->newLine();
        $this->command->info('📝 Login Instructions:');
        $this->command->info('   1. Visit: http://127.0.0.1:8000/employee/login');
        $this->command->info('   2. Use any email above with password: password123');
        $this->command->info('   3. You will be redirected to the appropriate dashboard');
        $this->command->newLine();
    }
}
