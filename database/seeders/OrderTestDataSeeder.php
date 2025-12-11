<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get users and products
        $users = User::where('is_admin', false)->get();
        $products = Product::where('is_active', true)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            echo "Please ensure you have users and products in the database first.\n";
            return;
        }

        $statuses = ['pending', 'delivered', 'cancelled'];
        $paymentMethods = ['cash', 'card', 'bank_transfer', 'paypal'];
        $deliveryMethods = ['standard', 'express', 'pickup'];

        // Generate orders for the last 60 days
        for ($day = 60; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);
            
            // More orders on recent days, fewer on older days
            $ordersPerDay = $day < 30 ? rand(3, 8) : rand(1, 4);
            
            for ($i = 0; $i < $ordersPerDay; $i++) {
                $user = $users->random();
                $status = $this->getWeightedStatus($day);
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                
                // Create order
                $village = $this->getRandomVillage();
                $orderData = [
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'recipient_name' => $user->name ?? 'Customer ' . rand(1, 100),
                    'phone' => $user->phone ?? '05' . rand(10000000, 99999999),
                    'village' => $village,
                    'address_note' => 'Test address note',
                    'latitude' => rand(2400, 3200) / 100,
                    'longitude' => rand(3400, 4800) / 100,
                    'delivery_method' => $deliveryMethods[array_rand($deliveryMethods)],
                    'shipping_method' => $deliveryMethods[array_rand($deliveryMethods)],
                    'shipping_address' => $village . ', Saudi Arabia',
                    'payment_method' => $paymentMethod,
                    'status' => $status,
                    'payment_status' => $status === 'delivered' ? 'paid' : ($status === 'cancelled' ? 'failed' : 'pending'),
                    'subtotal' => 0, // Will calculate
                    'delivery_cost' => rand(5, 20),
                    'service_fee' => rand(2, 5),
                    'shipping' => rand(5, 20),
                    'tax' => 0,
                    'total' => 0, // Will calculate
                    'notes' => 'Test order',
                    'created_at' => $date->copy()->addHours(rand(8, 22))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(8, 22))->addMinutes(rand(0, 59)),
                ];

                $order = Order::create($orderData);

                // Add order items (2-5 products per order)
                $itemCount = rand(2, 5);
                $subtotal = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $itemSubtotal = $price * $quantity;
                    $subtotal += $itemSubtotal;

                    DB::table('order_items')->insert([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $itemSubtotal,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ]);
                }

                // Update order totals
                $total = $subtotal + $order->delivery_cost + $order->service_fee;
                $order->update([
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);
            }
        }

        echo "Successfully created test orders for the last 60 days!\n";
        echo "Total orders created: " . Order::count() . "\n";
    }

    private function getWeightedStatus($daysAgo)
    {
        // Older orders are more likely to be delivered
        if ($daysAgo > 30) {
            $weights = [
                'delivered' => 80,
                'cancelled' => 15,
                'pending' => 5,
            ];
        } elseif ($daysAgo > 7) {
            $weights = [
                'delivered' => 60,
                'pending' => 30,
                'cancelled' => 10,
            ];
        } else {
            $weights = [
                'pending' => 50,
                'delivered' => 40,
                'cancelled' => 10,
            ];
        }

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $status => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return 'pending';
    }

    private function getRandomVillage()
    {
        $villages = [
            'الرياض',
            'جدة',
            'مكة المكرمة',
            'المدينة المنورة',
            'الدمام',
            'الخبر',
            'الطائف',
            'تبوك',
            'أبها',
            'القصيم',
            'حائل',
            'جازان',
            'نجران',
            'الباحة',
            'ينبع',
        ];

        return $villages[array_rand($villages)];
    }
}
