<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user (or create one if doesn't exist)
        $user = \App\Models\User::first();
        
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => 'محمد أحمد',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'phone' => '0944123456',
                'email_verified_at' => now()
            ]);
        }
        
        // Get some products
        $products = \App\Models\Product::take(5)->get();
        
        if ($products->isEmpty()) {
            echo "⚠️ No products found. Please seed products first.\n";
            return;
        }
        
        // Create 10 test orders with different statuses
        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        $deliveryMethods = ['normal', 'express', 'instant'];
        $paymentMethods = ['cash', 'card', 'syriatel', 'bank'];
        $villages = ['السويداء', 'شهبا', 'صلخد', 'قنوات', 'الكفر', 'المزرعة', 'شقا', 'المجدل'];
        
        for ($i = 1; $i <= 10; $i++) {
            $deliveryMethod = $deliveryMethods[array_rand($deliveryMethods)];
            $status = $statuses[array_rand($statuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            
            // Calculate delivery days based on method
            $deliveryDays = [
                'normal' => 7,
                'express' => 3,
                'instant' => 1
            ];
            
            // Random date in the past 30 days
            $createdAt = now()->subDays(rand(0, 30));
            $estimatedDelivery = $createdAt->copy()->addDays($deliveryDays[$deliveryMethod]);
            
            // Calculate totals
            $subtotal = rand(50, 300);
            $deliveryCost = rand(5, 20);
            $serviceFee = $subtotal * 0.05;
            $total = $subtotal + $deliveryCost + $serviceFee;
            
            // Payment status logic
            $paymentStatus = 'pending';
            if ($status === 'delivered') {
                $paymentStatus = 'paid';
            } elseif ($status === 'cancelled') {
                $paymentStatus = 'failed';
            } elseif ($paymentMethod !== 'cash' && in_array($status, ['confirmed', 'processing', 'shipped'])) {
                $paymentStatus = rand(0, 1) ? 'paid' : 'pending';
            }
            
            $order = \App\Models\Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $user->id,
                'recipient_name' => $user->name,
                'phone' => $user->phone ?? '0944123456',
                'village' => $villages[array_rand($villages)],
                'address_note' => 'بالقرب من ' . ['المسجد', 'المدرسة', 'الساحة', 'المركز الصحي'][array_rand(['المسجد', 'المدرسة', 'الساحة', 'المركز الصحي'])],
                'latitude' => 32.7081 + (rand(-100, 100) / 1000),
                'longitude' => 36.5675 + (rand(-100, 100) / 1000),
                'delivery_method' => $deliveryMethod,
                'payment_method' => $paymentMethod,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'estimated_delivery' => $estimatedDelivery,
                'subtotal' => $subtotal,
                'delivery_cost' => $deliveryCost,
                'service_fee' => $serviceFee,
                'total' => $total,
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ]);
            
            // Add 2-4 random products to each order
            $numProducts = rand(2, 4);
            $orderProducts = $products->random(min($numProducts, $products->count()));
            
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->discount_price ?? $product->price;
                
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity
                ]);
            }
            
            echo "✅ Created order #{$i}: {$order->order_number} - Status: {$status}\n";
        }
        
        echo "\n🎉 Successfully created 10 test orders!\n";
    }
}
