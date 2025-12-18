<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RBACSeeder extends Seeder
{
    /**
     * Seed the RBAC system for 6 distinct dashboards
     */
    public function run()
    {
        // =====================================================
        // PERMISSIONS DEFINITION
        // =====================================================
        
        $permissions = [
            // Super Admin Permissions (God Mode)
            ['name' => 'users.create', 'display_name' => 'Create Users', 'category' => 'users'],
            ['name' => 'users.read', 'display_name' => 'View Users', 'category' => 'users'],
            ['name' => 'users.update', 'display_name' => 'Update Users', 'category' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'category' => 'users'],
            ['name' => 'roles.manage', 'display_name' => 'Manage Roles', 'category' => 'rbac'],
            ['name' => 'permissions.manage', 'display_name' => 'Manage Permissions', 'category' => 'rbac'],
            ['name' => 'audit.read', 'display_name' => 'View Audit Logs', 'category' => 'audit'],
            ['name' => 'analytics.global', 'display_name' => 'Global Analytics', 'category' => 'analytics'],
            ['name' => 'emergency.override', 'display_name' => 'Emergency Override', 'category' => 'emergency'],
            ['name' => 'system.maintenance', 'display_name' => 'System Maintenance', 'category' => 'system'],

            // IT/DevOps Permissions
            ['name' => 'system.monitor', 'display_name' => 'Monitor System Health', 'category' => 'system'],
            ['name' => 'logs.read', 'display_name' => 'View System Logs', 'category' => 'logs'],
            ['name' => 'logs.export', 'display_name' => 'Export Logs', 'category' => 'logs'],
            ['name' => 'database.monitor', 'display_name' => 'Monitor Database', 'category' => 'database'],
            ['name' => 'database.backup', 'display_name' => 'Manage Backups', 'category' => 'database'],
            ['name' => 'deployments.manage', 'display_name' => 'Manage Deployments', 'category' => 'deployments'],
            ['name' => 'integrations.monitor', 'display_name' => 'Monitor Integrations', 'category' => 'integrations'],
            ['name' => 'alerts.manage', 'display_name' => 'Manage System Alerts', 'category' => 'alerts'],

            // HR Permissions
            ['name' => 'employees.create', 'display_name' => 'Create Employees', 'category' => 'hr'],
            ['name' => 'employees.read', 'display_name' => 'View Employees', 'category' => 'hr'],
            ['name' => 'employees.update', 'display_name' => 'Update Employees', 'category' => 'hr'],
            ['name' => 'shifts.manage', 'display_name' => 'Manage Shifts', 'category' => 'hr'],
            ['name' => 'payroll.calculate', 'display_name' => 'Calculate Payroll', 'category' => 'hr'],
            ['name' => 'payroll.submit', 'display_name' => 'Submit Payroll', 'category' => 'hr'],
            ['name' => 'reviews.manage', 'display_name' => 'Manage Performance Reviews', 'category' => 'hr'],
            ['name' => 'recruiting.manage', 'display_name' => 'Manage Recruiting', 'category' => 'hr'],
            ['name' => 'announcements.create', 'display_name' => 'Create Announcements', 'category' => 'hr'],

            // Driver Supervisor Permissions
            ['name' => 'drivers.read', 'display_name' => 'View Drivers', 'category' => 'logistics'],
            ['name' => 'drivers.update', 'display_name' => 'Update Driver Status', 'category' => 'logistics'],
            ['name' => 'locations.track', 'display_name' => 'Track Driver Locations', 'category' => 'logistics'],
            ['name' => 'orders.assign', 'display_name' => 'Assign Orders to Drivers', 'category' => 'logistics'],
            ['name' => 'routes.optimize', 'display_name' => 'Optimize Routes', 'category' => 'logistics'],
            ['name' => 'deliveries.monitor', 'display_name' => 'Monitor Deliveries', 'category' => 'logistics'],
            ['name' => 'vehicles.manage', 'display_name' => 'Manage Vehicle Maintenance', 'category' => 'logistics'],
            ['name' => 'delivery.verify', 'display_name' => 'Verify Deliveries', 'category' => 'logistics'],

            // Finance Permissions
            ['name' => 'transactions.read', 'display_name' => 'View Transactions', 'category' => 'finance'],
            ['name' => 'transactions.approve', 'display_name' => 'Approve Transactions', 'category' => 'finance'],
            ['name' => 'payouts.approve', 'display_name' => 'Approve Payouts', 'category' => 'finance'],
            ['name' => 'payouts.process', 'display_name' => 'Process Payouts', 'category' => 'finance'],
            ['name' => 'payroll.approve', 'display_name' => 'Approve Payroll', 'category' => 'finance'],
            ['name' => 'payroll.process', 'display_name' => 'Process Payroll', 'category' => 'finance'],
            ['name' => 'reports.financial', 'display_name' => 'Financial Reports', 'category' => 'finance'],
            ['name' => 'tax.manage', 'display_name' => 'Manage Tax Calculations', 'category' => 'finance'],
            ['name' => 'revenue.track', 'display_name' => 'Track Revenue', 'category' => 'finance'],

            // Product Owner (Vendor) Permissions
            ['name' => 'products.create', 'display_name' => 'Create Products', 'category' => 'vendor'],
            ['name' => 'products.read', 'display_name' => 'View Own Products', 'category' => 'vendor'],
            ['name' => 'products.update', 'display_name' => 'Update Own Products', 'category' => 'vendor'],
            ['name' => 'products.delete', 'display_name' => 'Delete Own Products', 'category' => 'vendor'],
            ['name' => 'inventory.manage', 'display_name' => 'Manage Inventory', 'category' => 'vendor'],
            ['name' => 'orders.read_own', 'display_name' => 'View Own Orders', 'category' => 'vendor'],
            ['name' => 'orders.update_own', 'display_name' => 'Update Own Orders', 'category' => 'vendor'],
            ['name' => 'sales.analytics', 'display_name' => 'View Sales Analytics', 'category' => 'vendor'],
            ['name' => 'payouts.request', 'display_name' => 'Request Payouts', 'category' => 'vendor'],
            ['name' => 'store.manage', 'display_name' => 'Manage Store Profile', 'category' => 'vendor'],

            // Common Permissions
            ['name' => 'dashboard.access', 'display_name' => 'Access Dashboard', 'category' => 'common'],
            ['name' => 'profile.update', 'display_name' => 'Update Own Profile', 'category' => 'common'],
            ['name' => 'notifications.read', 'display_name' => 'View Notifications', 'category' => 'common'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'category' => $permission['category'],
                'description' => "Permission to {$permission['display_name']}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =====================================================
        // ROLES DEFINITION
        // =====================================================
        
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'God mode access to all platform features and data',
                'is_system_role' => true,
                'permissions' => [
                    'users.create', 'users.read', 'users.update', 'users.delete',
                    'roles.manage', 'permissions.manage', 'audit.read',
                    'analytics.global', 'emergency.override', 'system.maintenance',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'it_admin',
                'display_name' => 'IT Administrator',
                'description' => 'System health monitoring and technical operations',
                'is_system_role' => true,
                'permissions' => [
                    'system.monitor', 'logs.read', 'logs.export',
                    'database.monitor', 'database.backup', 'deployments.manage',
                    'integrations.monitor', 'alerts.manage',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'devops_engineer',
                'display_name' => 'DevOps Engineer',
                'description' => 'Development operations and deployment management',
                'is_system_role' => true,
                'permissions' => [
                    'system.monitor', 'logs.read', 'deployments.manage',
                    'database.monitor', 'integrations.monitor', 'alerts.manage',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'hr_manager',
                'display_name' => 'HR Manager',
                'description' => 'Human resources management and employee operations',
                'is_system_role' => true,
                'permissions' => [
                    'employees.create', 'employees.read', 'employees.update',
                    'shifts.manage', 'payroll.calculate', 'payroll.submit',
                    'reviews.manage', 'recruiting.manage', 'announcements.create',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'hr_coordinator',
                'display_name' => 'HR Coordinator',
                'description' => 'HR operations support and employee coordination',
                'is_system_role' => true,
                'permissions' => [
                    'employees.read', 'shifts.manage', 'recruiting.manage',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'driver_supervisor',
                'display_name' => 'Driver Supervisor',
                'description' => 'Fleet management and delivery operations oversight',
                'is_system_role' => true,
                'permissions' => [
                    'drivers.read', 'drivers.update', 'locations.track',
                    'orders.assign', 'routes.optimize', 'deliveries.monitor',
                    'vehicles.manage', 'delivery.verify',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'logistics_coordinator',
                'display_name' => 'Logistics Coordinator',
                'description' => 'Delivery coordination and route planning',
                'is_system_role' => true,
                'permissions' => [
                    'drivers.read', 'locations.track', 'orders.assign',
                    'routes.optimize', 'deliveries.monitor',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'finance_manager',
                'display_name' => 'Finance Manager',
                'description' => 'Financial operations and transaction oversight',
                'is_system_role' => true,
                'permissions' => [
                    'transactions.read', 'transactions.approve', 'payouts.approve',
                    'payouts.process', 'payroll.approve', 'payroll.process',
                    'reports.financial', 'tax.manage', 'revenue.track',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Financial record keeping and reporting',
                'is_system_role' => true,
                'permissions' => [
                    'transactions.read', 'reports.financial', 'tax.manage',
                    'revenue.track', 'payroll.approve',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'product_owner',
                'display_name' => 'Product Owner (Vendor)',
                'description' => 'Store management and product operations',
                'is_system_role' => true,
                'permissions' => [
                    'products.create', 'products.read', 'products.update', 'products.delete',
                    'inventory.manage', 'orders.read_own', 'orders.update_own',
                    'sales.analytics', 'payouts.request', 'store.manage',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'store_manager',
                'display_name' => 'Store Manager',
                'description' => 'Store operations management for vendors',
                'is_system_role' => true,
                'permissions' => [
                    'products.read', 'products.update', 'inventory.manage',
                    'orders.read_own', 'orders.update_own', 'sales.analytics',
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'driver',
                'display_name' => 'Driver',
                'description' => 'Delivery driver with mobile app access',
                'is_system_role' => true,
                'permissions' => [
                    'dashboard.access', 'profile.update', 'notifications.read'
                ]
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Platform customer with shopping access',
                'is_system_role' => true,
                'permissions' => [
                    'profile.update', 'notifications.read'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            // Create role
            $roleId = DB::table('roles')->insertGetId([
                'name' => $roleData['name'],
                'display_name' => $roleData['display_name'],
                'description' => $roleData['description'],
                'is_system_role' => $roleData['is_system_role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign permissions to role
            foreach ($roleData['permissions'] as $permissionName) {
                $permission = DB::table('permissions')->where('name', $permissionName)->first();
                if ($permission) {
                    DB::table('role_permissions')->insertOrIgnore([
                        'role_id' => $roleId,
                        'permission_id' => $permission->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // =====================================================
        // CREATE DEFAULT ADMIN USER
        // =====================================================
        
        $adminUserId = DB::table('users')->insertGetId([
            'name' => 'Super Administrator',
            'email' => 'admin@webstore.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'phone' => '+1234567890',
            'is_admin' => true,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign super_admin role to default admin
        $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
        DB::table('user_roles')->insert([
            'user_id' => $adminUserId,
            'role_id' => $superAdminRole->id,
            'assigned_at' => now(),
            'is_active' => true,
        ]);

        // =====================================================
        // CREATE SAMPLE DASHBOARD USERS
        // =====================================================
        
        $sampleUsers = [
            [
                'name' => 'IT Administrator',
                'email' => 'it@webstore.com',
                'role' => 'it_admin',
                'department' => 'IT'
            ],
            [
                'name' => 'HR Manager',
                'email' => 'hr@webstore.com',
                'role' => 'hr_manager',
                'department' => 'Human Resources'
            ],
            [
                'name' => 'Fleet Supervisor',
                'email' => 'supervisor@webstore.com',
                'role' => 'driver_supervisor',
                'department' => 'Logistics'
            ],
            [
                'name' => 'Finance Manager',
                'email' => 'finance@webstore.com',
                'role' => 'finance_manager',
                'department' => 'Finance'
            ],
            [
                'name' => 'Sample Vendor',
                'email' => 'vendor@webstore.com',
                'role' => 'product_owner',
                'department' => 'Sales'
            ]
        ];

        foreach ($sampleUsers as $userData) {
            $userId = DB::table('users')->insertGetId([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign role
            $role = DB::table('roles')->where('name', $userData['role'])->first();
            if ($role) {
                DB::table('user_roles')->insert([
                    'user_id' => $userId,
                    'role_id' => $role->id,
                    'assigned_at' => now(),
                    'is_active' => true,
                ]);
            }

            // Create employee record for internal staff
            if (in_array($userData['role'], ['hr_manager', 'driver_supervisor', 'finance_manager', 'it_admin'])) {
                DB::table('employees')->insert([
                    'user_id' => $userId,
                    'employee_id' => 'EMP' . str_pad($userId, 4, '0', STR_PAD_LEFT),
                    'department' => $userData['department'],
                    'position' => $userData['name'],
                    'employment_type' => 'full_time',
                    'status' => 'active',
                    'hire_date' => now()->subMonths(rand(1, 24)),
                    'monthly_salary' => rand(3000, 8000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create store for vendor
            if ($userData['role'] === 'product_owner') {
                DB::table('stores')->insert([
                    'organization_id' => 1, // Assuming organization exists
                    'owner_id' => $userId,
                    'name' => 'Sample Store',
                    'slug' => 'sample-store',
                    'description' => 'A sample store for testing',
                    'status' => 'active',
                    'commission_rate' => 0.0500,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // =====================================================
        // SYSTEM SETTINGS
        // =====================================================
        
        $systemSettings = [
            ['key' => 'platform.name', 'value' => 'Webstore Platform', 'type' => 'string', 'is_public' => true],
            ['key' => 'platform.version', 'value' => '1.0.0', 'type' => 'string', 'is_public' => true],
            ['key' => 'commission.default_rate', 'value' => '0.0500', 'type' => 'decimal', 'is_public' => false],
            ['key' => 'tax.default_rate', 'value' => '0.1500', 'type' => 'decimal', 'is_public' => false],
            ['key' => 'notifications.email_enabled', 'value' => 'true', 'type' => 'boolean', 'is_public' => false],
            ['key' => 'notifications.sms_enabled', 'value' => 'false', 'type' => 'boolean', 'is_public' => false],
            ['key' => 'delivery.tracking_enabled', 'value' => 'true', 'type' => 'boolean', 'is_public' => true],
            ['key' => 'maintenance.mode', 'value' => 'false', 'type' => 'boolean', 'is_public' => true],
        ];

        foreach ($systemSettings as $setting) {
            DB::table('system_settings')->insertOrIgnore([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'type' => $setting['type'],
                'description' => "System setting for {$setting['key']}",
                'is_public' => $setting['is_public'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('RBAC system seeded successfully with 6 dashboard roles and permissions!');
    }
}