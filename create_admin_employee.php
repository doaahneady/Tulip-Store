<?php

/**
 * Quick Script to Create Admin Employee
 * Run this via: php create_admin_employee.php
 * Or visit: http://127.0.0.1:8000/create-admin-employee
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

try {
    echo "🚀 Creating Admin Employee...\n\n";

    // Check if admin already exists
    $existingAdmin = Employee::where('email', 'admin@tulipstore.com')->first();

    if ($existingAdmin) {
        echo "⚠️  Admin employee already exists!\n";
        echo "Email: {$existingAdmin->email}\n";
        echo "Status: {$existingAdmin->status}\n";
        echo 'Roles: ';
        $roles = [];
        if ($existingAdmin->is_admin) {
            $roles[] = 'Admin';
        }
        if ($existingAdmin->is_it) {
            $roles[] = 'IT';
        }
        if ($existingAdmin->is_hr) {
            $roles[] = 'HR';
        }
        if ($existingAdmin->is_finance) {
            $roles[] = 'Finance';
        }
        if ($existingAdmin->is_driver_supervisor) {
            $roles[] = 'Supervisor';
        }
        if ($existingAdmin->is_trader) {
            $roles[] = 'Vendor';
        }
        echo implode(', ', $roles)."\n\n";

        // Update password to ensure it's correct
        $existingAdmin->password = Hash::make('password123');
        $existingAdmin->status = 'active';
        $existingAdmin->email_verified_at = now();
        $existingAdmin->save();

        echo "✅ Password reset to: password123\n";
        echo "✅ Status set to: active\n";
        echo "✅ Email verified\n\n";
    } else {
        // Create new admin employee
        $admin = Employee::create([
            'employee_code' => 'SA001',
            'first_name' => 'Ahmed',
            'last_name' => 'Al-Manager',
            'email' => 'admin@tulipstore.com',
            'password' => Hash::make('password123'),
            'phone' => '+963-11-1234567',
            'department' => 'Administration',
            'position' => 'Chief Executive Officer',
            'employment_type' => 'full_time',
            'hire_date' => now(),
            'status' => 'active',
            'salary' => 150000.00,
            'city' => 'Damascus',
            'country' => 'Syria',
            'gender' => 'male',
            'email_verified_at' => now(),
            // All roles enabled
            'is_admin' => true,
            'is_it' => true,
            'is_hr' => true,
            'is_finance' => true,
            'is_driver_supervisor' => true,
            'is_trader' => true,
        ]);

        echo "✅ Admin employee created successfully!\n\n";
    }

    echo "═══════════════════════════════════════════════════\n";
    echo "📋 LOGIN CREDENTIALS:\n";
    echo "═══════════════════════════════════════════════════\n";
    echo "Email:    admin@tulipstore.com\n";
    echo "Password: password123\n";
    echo "URL:      http://127.0.0.1:8000/employee/login\n";
    echo "═══════════════════════════════════════════════════\n\n";

    // Verify the employee can be found
    $verify = Employee::where('email', 'admin@tulipstore.com')->first();
    if ($verify && Hash::check('password123', $verify->password)) {
        echo "✅ Verification: Login credentials are correct!\n";
    } else {
        echo "❌ Verification: Login credentials check failed!\n";
    }

} catch (\Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
    echo "Stack trace:\n".$e->getTraceAsString()."\n";
}
