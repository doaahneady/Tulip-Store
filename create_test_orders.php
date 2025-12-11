<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

echo "Creating test data for Driver Supervisor Orders...\n\n";

// Check if we have any users
$user = User::first();
if (!$user) {
    echo "No users found. Creating a test user...\n";
    $user = User::create([
        'name' => 'عميل تجريبي',
        'email' => 'customer@test.com',
        'password' => bcrypt('password'),
    ]);
}

echo "User ID: {$user->id}\n";

// Create test orders
$orders = [
    [
        'order_number' => 'ORD-TEST-' . rand(1000, 9999),
        'user_id' => $user->id,
        'recipient_name' => 'أحمد محمود',
        'phone' => '0912345678',
        'village' => 'دمشق - المزة',
        'address_note' => 'بناء رقم 5، الطابق الثالث',
        'latitude' => 33.5138,
        'longitude' => 36.2765,
        'delivery_method' => 'home_delivery',
        'payment_method' => 'cash',
        'status' => 'confirmed',
        'payment_status' => 'pending',
        'subtotal' => 50.00,
        'delivery_cost' => 5.00,
        'service_fee' => 2.50,
        'total' => 57.50,
    ],
    [
        'order_number' => 'ORD-TEST-' . rand(1000, 9999),
        'user_id' => $user->id,
        'recipient_name' => 'فاطمة علي',
        'phone' => '0923456789',
        'village' => 'حلب - الشهباء',
        'address_note' => 'شارع الجامعة، بناء 12',
        'latitude' => 36.2021,
        'longitude' => 37.1343,
        'delivery_method' => 'home_delivery',
        'payment_method' => 'card',
        'status' => 'confirmed',
        'payment_status' => 'paid',
        'subtotal' => 100.00,
        'delivery_cost' => 10.00,
        'service_fee' => 5.00,
        'total' => 115.00,
    ],
    [
        'order_number' => 'ORD-TEST-' . rand(1000, 9999),
        'user_id' => $user->id,
        'recipient_name' => 'محمد حسن',
        'phone' => '0934567890',
        'village' => 'حمص - الوعر',
        'address_note' => 'قرب المسجد الكبير',
        'latitude' => 34.7324,
        'longitude' => 36.7137,
        'delivery_method' => 'home_delivery',
        'payment_method' => 'cash',
        'status' => 'pending',
        'payment_status' => 'pending',
        'subtotal' => 75.00,
        'delivery_cost' => 7.00,
        'service_fee' => 3.00,
        'total' => 85.00,
    ],
];

foreach ($orders as $orderData) {
    $order = Order::create($orderData);
    echo "Created order: {$order->order_number} (Status: {$order->status}, Payment: {$order->payment_method})\n";
    
    // Create order items
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => 1,
        'product_name' => 'منتج تجريبي',
        'quantity' => 2,
        'price' => 25.00,
        'subtotal' => 50.00,
    ]);
}

// Create test drivers
echo "\nCreating test drivers...\n";

$drivers = [
    ['name' => 'أحمد السائق', 'email' => 'driver1@test.com'],
    ['name' => 'محمد السائق', 'email' => 'driver2@test.com'],
    ['name' => 'علي السائق', 'email' => 'driver3@test.com'],
];

foreach ($drivers as $driverData) {
    $existing = User::where('email', $driverData['email'])->first();
    if (!$existing) {
        $driver = User::create([
            'name' => $driverData['name'],
            'email' => $driverData['email'],
            'password' => bcrypt('password'),
            'is_driver' => true,
            'is_active' => true,
        ]);
        echo "Created driver: {$driver->name}\n";
    } else {
        // Update existing user to be a driver
        $existing->update(['is_driver' => true, 'is_active' => true]);
        echo "Updated driver: {$existing->name}\n";
    }
}

echo "\n✅ Test data created successfully!\n";
echo "\nNow go to: http://127.0.0.1:8000/driver-supervisor/orders\n";
echo "You should see 3 test orders ready for delivery.\n";
