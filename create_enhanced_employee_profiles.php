<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "🚀 Creating Enhanced Employee Profiles...\n\n";

    // Super Admin Profile
    $superAdmin = Employee::updateOrCreate(
        ['email' => 'admin@tulipstore.com'],
        [
            'employee_code' => 'SA001',
            'first_name' => 'Ahmed',
            'last_name' => 'Al-Manager',
            'email' => 'admin@tulipstore.com',
            'password' => Hash::make('password123'),
            'phone' => '+963-11-1234567',
            'bio' => 'Experienced CEO with over 15 years in e-commerce and retail management. Passionate about digital transformation and team leadership.',
            'national_id' => '01234567890',
            'date_of_birth' => '1985-01-15',
            'gender' => 'male',
            'marital_status' => 'married',
            'address' => 'Damascus Business District, Building 15, Floor 10',
            'city' => 'Damascus',
            'country' => 'Syria',
            'department' => 'Administration',
            'position' => 'Chief Executive Officer',
            'work_location' => 'Head Office',
            'employment_type' => 'full_time',
            'hire_date' => '2020-01-01',
            'salary' => 150000.00,
            'approval_limit' => 100000.00,
            'status' => 'active',
            'security_level' => '5',
            'skills' => ['Strategic Planning', 'Team Leadership', 'Business Development', 'Digital Marketing', 'Financial Management'],
            'qualifications' => ['MBA in Business Administration', 'Bachelor in Computer Science'],
            'certifications' => ['PMP Certified', 'Digital Marketing Professional'],
            'languages' => ['Arabic (Native)', 'English (Fluent)', 'French (Intermediate)'],
            'preferred_communication' => 'email',
            'emergency_contact_name' => 'Fatima Al-Manager',
            'emergency_contact_phone' => '+963-11-7654321',
            'emergency_contact_relation' => 'spouse',
            'performance_score' => 4.9,
            'last_review_date' => '2024-01-15',
            'next_review_date' => '2025-01-15',
            'is_admin' => true,
            'is_it' => true,
            'is_hr' => true,
            'is_finance' => true,
            'is_driver_supervisor' => true,
            'is_trader' => true,
            'is_manager' => true,
            'can_approve_expenses' => true,
            'can_manage_inventory' => true,
        ]
    );

    // IT Specialist Profile
    $itSpecialist = Employee::updateOrCreate(
        ['email' => 'it@tulipstore.com'],
        [
            'employee_code' => 'IT001',
            'first_name' => 'Omar',
            'last_name' => 'Tech',
            'email' => 'it@tulipstore.com',
            'password' => Hash::make('password123'),
            'phone' => '+963-11-2345678',
            'bio' => 'Senior IT Specialist with expertise in Laravel development, system administration, and cybersecurity. Committed to maintaining robust and secure systems.',
            'national_id' => '01234567891',
            'date_of_birth' => '1992-05-20',
            'gender' => 'male',
            'marital_status' => 'single',
            'address' => 'New Damascus, Tech Valley, Building 7',
            'city' => 'Damascus',
            'country' => 'Syria',
            'department' => 'Information Technology',
            'position' => 'Senior IT Specialist',
            'work_location' => 'IT Department',
            'manager_id' => $superAdmin->id,
            'employment_type' => 'full_time',
            'hire_date' => '2021-03-15',
            'salary' => 80000.00,
            'approval_limit' => 5000.00,
            'status' => 'active',
            'security_level' => '4',
            'skills' => ['Laravel/PHP Development', 'Database Administration', 'Server Management', 'Security Implementation', 'System Monitoring', 'Backup Management'],
            'qualifications' => ['Bachelor in Computer Engineering', 'Diploma in Network Security'],
            'certifications' => ['AWS Certified Solutions Architect', 'Laravel Certified Developer', 'MySQL Database Administrator'],
            'languages' => ['Arabic (Native)', 'English (Fluent)'],
            'preferred_communication' => 'email',
            'emergency_contact_name' => 'Khalil Tech',
            'emergency_contact_phone' => '+963-11-8765432',
            'emergency_contact_relation' => 'father',
            'performance_score' => 4.7,
            'last_review_date' => '2024-03-15',
            'next_review_date' => '2025-03-15',
            'is_it' => true,
            'is_team_lead' => true,
        ]
    );

    // HR Manager Profile
    $hrManager = Employee::updateOrCreate(
        ['email' => 'hr@tulipstore.com'],
        [
            'employee_code' => 'HR001',
            'first_name' => 'Layla',
            'last_name' => 'People',
            'email' => 'hr@tulipstore.com',
            'password' => Hash::make('password123'),
            'phone' => '+963-11-3456789',
            'bio' => 'Experienced HR Manager specializing in talent acquisition, employee development, and organizational culture. Dedicated to creating positive work environments.',
            'national_id' => '01234567892',
            'date_of_birth' => '1988-08-10',
            'gender' => 'female',
            'marital_status' => 'married',
            'address' => 'Mezzeh District, HR Plaza, Suite 201',
            'city' => 'Damascus',
            'country' => 'Syria',
            'department' => 'Human Resources',
            'position' => 'HR Manager',
            'work_location' => 'HR Department',
            'manager_id' => $superAdmin->id,
            'employment_type' => 'full_time',
            'hire_date' => '2020-06-01',
            'salary' => 75000.00,
            'approval_limit' => 10000.00,
            'status' => 'active',
            'security_level' => '3',
            'skills' => ['Employee Relations', 'Recruitment & Selection', 'Performance Management', 'Payroll Administration', 'Training & Development', 'Labor Law Compliance'],
            'qualifications' => ['Master in Human Resources', 'Bachelor in Psychology'],
            'certifications' => ['SHRM Certified Professional', 'PHR Certification'],
            'languages' => ['Arabic (Native)', 'English (Fluent)', 'French (Basic)'],
            'preferred_communication' => 'phone',
            'emergency_contact_name' => 'Mahmoud People',
            'emergency_contact_phone' => '+963-11-9876543',
            'emergency_contact_relation' => 'husband',
            'performance_score' => 4.8,
            'last_review_date' => '2024-06-01',
            'next_review_date' => '2025-06-01',
            'is_hr' => true,
            'is_manager' => true,
            'can_approve_expenses' => true,
        ]
    );

    // Finance Manager Profile
    $financeManager = Employee::updateOrCreate(
        ['email' => 'finance@tulipstore.com'],
        [
            'employee_code' => 'FN001',
            'first_name' => 'Khaled',
            'last_name' => 'Money',
            'email' => 'finance@tulipstore.com',
            'password' => Hash::make('password123'),
            'phone' => '+963-11-4567890',
            'bio' => 'Chief Financial Officer with extensive experience in financial planning, analysis, and risk management. Expert in e-commerce financial operations.',
            'national_id' => '01234567893',
            'date_of_birth' => '1985-12-03',
            'gender' => 'male',
            'marital_status' => 'married',
            'address' => 'Financial District, Tower 7, Floor 15',
            'city' => 'Damascus',
            'country' => 'Syria',
            'department' => 'Finance & Accounting',
            'position' => 'Chief Financial Officer',
            'work_location' => 'Finance Department',
            'manager_id' => $superAdmin->id,
            'employment_type' => 'full_time',
            'hire_date' => '2019-09-01',
            'salary' => 120000.00,
            'approval_limit' => 50000.00,
            'status' => 'active',
            'security_level' => '4',
            'skills' => ['Financial Analysis', 'Budget Planning', 'Risk Management', 'Tax Planning', 'Investment Analysis', 'Financial Reporting'],
            'qualifications' => ['CPA (Certified Public Accountant)', 'MBA in Finance', 'Bachelor in Accounting'],
            'certifications' => ['CFA Charterholder', 'FRM Certified'],
            'languages' => ['Arabic (Native)', 'English (Fluent)'],
            'preferred_communication' => 'email',
            'emergency_contact_name' => 'Nour Money',
            'emergency_contact_phone' => '+963-11-6543210',
            'emergency_contact_relation' => 'spouse',
            'performance_score' => 4.9,
            'last_review_date' => '2024-09-01',
            'next_review_date' => '2025-09-01',
            'is_finance' => true,
            'is_manager' => true,
            'can_approve_expenses' => true,
        ]
    );

    // Driver Supervisor Profile
    $driverSupervisor = Employee::updateOrCreate(
        ['email' => 'supervisor@tulipstore.com'],
        [
            'employee_code' => 'DS001',
            'first_name' => 'Mahmoud',
            'last_name' => 'Fleet',
            'email' => 'supervisor@tulipstore.com',
            'password' => Hash::make('password123'),
            'phone' => '+963-11-5678901',
            'bio' => 'Experienced Fleet Supervisor with expertise in logistics coordination, route optimization, and driver management. Committed to efficient delivery operations.',
            'national_id' => '01234567894',
            'date_of_birth' => '1990-04-25',
            'gender' => 'male',
            'marital_status' => 'single',
            'address' => 'Industrial Zone, Logistics Hub, Building 3',
            'city' => 'Damascus',
            'country' => 'Syria',
            'department' => 'Logistics & Delivery',
            'position' => 'Fleet Supervisor',
            'work_location' => 'Logistics Center',
            'manager_id' => $superAdmin->id,
            'employment_type' => 'full_time',
            'hire_date' => '2021-01-10',
            'salary' => 60000.00,
            'approval_limit' => 2000.00,
            'status' => 'active',
            'security_level' => '3',
            'skills' => ['Fleet Management', 'Route Optimization', 'Driver Training', 'GPS Tracking Systems', 'Logistics Coordination', 'Safety Management'],
            'qualifications' => ['Diploma in Logistics Management', 'Certificate in Fleet Operations'],
            'certifications' => ['Professional Driver Trainer', 'Fleet Safety Coordinator'],
            'languages' => ['Arabic (Native)', 'English (Intermediate)'],
            'preferred_communication' => 'whatsapp',
            'emergency_contact_name' => 'Ali Fleet',
            'emergency_contact_phone' => '+963-11-7890123',
            'emergency_contact_relation' => 'brother',
            'performance_score' => 4.6,
            'last_review_date' => '2024-01-10',
            'next_review_date' => '2025-01-10',
            'is_driver_supervisor' => true,
            'is_team_lead' => true,
        ]
    );

    // Store Manager Profile
    $storeManager = Employee::updateOrCreate(
        ['email' => 'store@tulipstore.com'],
        [
            'employee_code' => 'ST001',
            'first_name' => 'Nour',
            'last_name' => 'Store',
            'email' => 'store@tulipstore.com',
            'password' => Hash::make('password123'),
            'phone' => '+963-11-6789012',
            'bio' => 'Dynamic Store Manager with expertise in inventory management, customer service, and sales optimization. Passionate about delivering exceptional shopping experiences.',
            'national_id' => '01234567895',
            'date_of_birth' => '1993-07-18',
            'gender' => 'female',
            'marital_status' => 'single',
            'address' => 'Commercial District, Store 25, Manager Office',
            'city' => 'Damascus',
            'country' => 'Syria',
            'department' => 'Store Operations',
            'position' => 'Store Manager',
            'work_location' => 'Main Store',
            'manager_id' => $superAdmin->id,
            'employment_type' => 'full_time',
            'hire_date' => '2022-02-14',
            'salary' => 55000.00,
            'approval_limit' => 1000.00,
            'commission_rate' => 2.5,
            'status' => 'active',
            'security_level' => '2',
            'skills' => ['Inventory Management', 'Product Merchandising', 'Customer Service', 'Sales Analytics', 'Vendor Relations', 'Quality Control'],
            'qualifications' => ['Bachelor in Business Administration', 'Certificate in Retail Management'],
            'certifications' => ['Certified Retail Manager', 'Customer Service Excellence'],
            'languages' => ['Arabic (Native)', 'English (Good)'],
            'preferred_communication' => 'phone',
            'emergency_contact_name' => 'Rania Store',
            'emergency_contact_phone' => '+963-11-8901234',
            'emergency_contact_relation' => 'sister',
            'performance_score' => 4.5,
            'last_review_date' => '2024-02-14',
            'next_review_date' => '2025-02-14',
            'is_trader' => true,
            'can_manage_inventory' => true,
        ]
    );

    // Multi-role Employee (HR + Finance)
    $multiEmployee = Employee::updateOrCreate(
        ['email' => 'multi@tulipstore.com'],
        [
            'employee_code' => 'MR001',
            'first_name' => 'Sarah',
            'last_name' => 'Multi',
            'email' => 'multi@tulipstore.com',
            'password' => Hash::make('password123'),
            'phone' => '+963-11-7890123',
            'bio' => 'Versatile department manager with dual expertise in HR and Finance operations. Excellent at cross-functional collaboration and process optimization.',
            'national_id' => '01234567896',
            'date_of_birth' => '1987-11-22',
            'gender' => 'female',
            'marital_status' => 'married',
            'address' => 'Management District, Executive Building, Suite 301',
            'city' => 'Damascus',
            'country' => 'Syria',
            'department' => 'Management',
            'position' => 'Department Manager',
            'work_location' => 'Executive Floor',
            'manager_id' => $superAdmin->id,
            'employment_type' => 'full_time',
            'hire_date' => '2021-08-01',
            'salary' => 85000.00,
            'approval_limit' => 15000.00,
            'status' => 'active',
            'security_level' => '3',
            'skills' => ['HR Management', 'Financial Analysis', 'Process Optimization', 'Team Leadership', 'Strategic Planning', 'Change Management'],
            'qualifications' => ['MBA in Management', 'Bachelor in Finance', 'HR Management Certificate'],
            'certifications' => ['PMP Certified', 'SHRM-CP'],
            'languages' => ['Arabic (Native)', 'English (Fluent)', 'German (Basic)'],
            'preferred_communication' => 'email',
            'emergency_contact_name' => 'Ahmad Multi',
            'emergency_contact_phone' => '+963-11-9012345',
            'emergency_contact_relation' => 'husband',
            'performance_score' => 4.7,
            'last_review_date' => '2024-08-01',
            'next_review_date' => '2025-08-01',
            'is_hr' => true,
            'is_finance' => true,
            'is_manager' => true,
            'can_approve_expenses' => true,
        ]
    );

    echo "✅ Enhanced Employee Profiles Created Successfully!\n\n";

    echo "📊 Employee Summary:\n";
    echo "==================\n";
    echo "Super Admin: admin@tulipstore.com (All Dashboards)\n";
    echo "IT Specialist: it@tulipstore.com (IT Dashboard)\n";
    echo "HR Manager: hr@tulipstore.com (HR Dashboard)\n";
    echo "Finance Manager: finance@tulipstore.com (Finance Dashboard)\n";
    echo "Driver Supervisor: supervisor@tulipstore.com (Supervisor Dashboard)\n";
    echo "Store Manager: store@tulipstore.com (Vendor Dashboard)\n";
    echo "Multi-Role Manager: multi@tulipstore.com (HR + Finance Dashboards)\n\n";

    echo "🔑 All passwords: password123\n\n";

    echo "🎯 Features Added:\n";
    echo "- Comprehensive personal and professional information\n";
    echo "- Skills, qualifications, and certifications tracking\n";
    echo "- Security levels and approval limits\n";
    echo "- Performance scores and review dates\n";
    echo "- Emergency contacts and preferences\n";
    echo "- Manager-subordinate relationships\n";
    echo "- Enhanced role and permission system\n\n";

    echo "🚀 Next Steps:\n";
    echo "1. Run the migration: php artisan migrate\n";
    echo "2. Visit /staff to login with any employee\n";
    echo "3. Access employee profiles from dashboard dropdown\n";
    echo "4. Test the comprehensive profile system\n\n";

    echo "✨ Enhanced Employee Profile System Ready!\n";

} catch (Exception $e) {
    echo '❌ Error creating enhanced employee profiles: '.$e->getMessage()."\n";
    echo 'Stack trace: '.$e->getTraceAsString()."\n";
}
