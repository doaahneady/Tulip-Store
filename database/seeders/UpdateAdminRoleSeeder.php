<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UpdateAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@tulipstore.com')->first();
        $superAdminRole = Role::where('name', 'super_admin')->first();

        if ($admin && $superAdminRole) {
            $admin->role_id = $superAdminRole->id;
            $admin->is_admin = true;
            $admin->save();
            echo "Admin user updated with super_admin role\n";
        }
    }
}
