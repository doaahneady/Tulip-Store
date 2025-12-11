<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some orders and users
        $orders = \DB::table('orders')->limit(5)->get();
        $users = \DB::table('users')->get();
        $adminId = $users->where('is_admin', true)->first()->id ?? 1;
        
        if ($orders->isEmpty()) {
            return; // No orders to create refunds for
        }
        
        $refunds = [];
        
        foreach ($orders->take(3) as $index => $order) {
            $refunds[] = [
                'order_id' => $order->id,
                'user_id' => $order->user_id ?? $users->first()->id,
                'amount' => $index === 0 ? $order->total : $order->total / 2, // First is full, others partial
                'type' => $index === 0 ? 'full' : 'partial',
                'reason' => $index === 0 ? 'المنتج معيب' : 'تم إرجاع بعض المنتجات',
                'status' => ['pending', 'approved', 'processed'][$index % 3],
                'approved_by' => $index > 0 ? $adminId : null,
                'admin_notes' => $index > 0 ? 'تمت الموافقة على الاسترجاع' : null,
                'approved_at' => $index > 0 ? now()->subDays($index) : null,
                'created_at' => now()->subDays($index + 1),
                'updated_at' => now()->subDays($index),
            ];
        }
        
        \DB::table('refunds')->insert($refunds);
    }
}
