<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            ['name' => 'view_dashboard', 'display_name' => 'عرض لوحة الإدارة', 'description' => 'الوصول إلى لوحة الإدارة'],
            ['name' => 'manage_products', 'display_name' => 'إدارة المنتجات', 'description' => 'إضافة وتعديل وحذف المنتجات'],
            ['name' => 'manage_orders', 'display_name' => 'إدارة الطلبات', 'description' => 'عرض وتعديل حالة الطلبات'],
            ['name' => 'manage_users', 'display_name' => 'إدارة المستخدمين', 'description' => 'إدارة حسابات المستخدمين'],
            ['name' => 'manage_categories', 'display_name' => 'إدارة الفئات', 'description' => 'إضافة وتعديل الفئات'],
            ['name' => 'view_reports', 'display_name' => 'عرض التقارير', 'description' => 'الوصول إلى التقارير والإحصائيات'],
            ['name' => 'manage_settings', 'display_name' => 'إدارة الإعدادات', 'description' => 'تعديل إعدادات النظام'],
            ['name' => 'manage_roles', 'display_name' => 'إدارة الأدوار', 'description' => 'إدارة الأدوار والصلاحيات'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['display_name' => 'مدير عام', 'description' => 'صلاحيات كاملة على النظام']
        );

        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'مسؤول', 'description' => 'صلاحيات إدارية']
        );

        $manager = Role::firstOrCreate(
            ['name' => 'manager'],
            ['display_name' => 'مدير', 'description' => 'إدارة المنتجات والطلبات']
        );

        $customer = Role::firstOrCreate(
            ['name' => 'customer'],
            ['display_name' => 'عميل', 'description' => 'مستخدم عادي']
        );

        // Assign all permissions to super admin
        $superAdmin->permissions()->sync(Permission::all());

        // Assign specific permissions to admin
        $admin->permissions()->sync(
            Permission::whereIn('name', [
                'view_dashboard',
                'manage_products',
                'manage_orders',
                'manage_users',
                'manage_categories',
                'view_reports',
            ])->pluck('id')
        );

        // Assign specific permissions to manager
        $manager->permissions()->sync(
            Permission::whereIn('name', [
                'view_dashboard',
                'manage_products',
                'manage_orders',
                'manage_categories',
            ])->pluck('id')
        );

        // Customer has no admin permissions
    }
}
