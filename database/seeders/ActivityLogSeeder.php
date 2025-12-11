<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \DB::table('users')->where('is_admin', true)->get();
        $adminId = $users->first()->id ?? 1;
        
        $activities = [
            [
                'user_id' => $adminId,
                'action' => 'created',
                'model' => 'Product',
                'model_id' => 1,
                'description' => 'Created new product: Sample Product',
                'changes' => json_encode(['name' => 'Sample Product', 'price' => 99.99]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => $adminId,
                'action' => 'updated',
                'model' => 'Order',
                'model_id' => 1,
                'description' => 'Updated order status to shipped',
                'changes' => json_encode(['old' => 'processing', 'new' => 'shipped']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id' => $adminId,
                'action' => 'deleted',
                'model' => 'Product',
                'model_id' => 999,
                'description' => 'Deleted product: Old Product',
                'changes' => json_encode(['name' => 'Old Product']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => $adminId,
                'action' => 'updated',
                'model' => 'User',
                'model_id' => 2,
                'description' => 'Updated user role to admin',
                'changes' => json_encode(['old' => 'user', 'new' => 'admin']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => $adminId,
                'action' => 'created',
                'model' => 'Category',
                'model_id' => 1,
                'description' => 'Created new category: Electronics',
                'changes' => json_encode(['name' => 'Electronics']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subHours(12),
                'updated_at' => now()->subHours(12),
            ],
        ];
        
        \DB::table('activity_logs')->insert($activities);
    }
}
