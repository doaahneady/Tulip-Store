<?php

namespace Database\Seeders;

use App\Models\DeliveryAssignment;
use App\Models\DeliveryDriver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeliverySystemSeeder extends Seeder
{
    public function run(): void
    {
        // Create delivery drivers
        $drivers = [
            [
                'driver_name' => 'أحمد محمد',
                'phone' => '0501234567',
                'vehicle_type' => 'motorcycle',
                'vehicle_plate' => 'أ ب ج 1234',
                'license_number' => 'DL123456',
                'status' => 'available',
                'current_latitude' => 33.5138,
                'current_longitude' => 36.2765,
                'rating' => 4.8,
                'total_deliveries' => 245,
            ],
            [
                'driver_name' => 'محمد علي',
                'phone' => '0507654321',
                'vehicle_type' => 'motorcycle',
                'vehicle_plate' => 'د هـ و 5678',
                'license_number' => 'DL234567',
                'status' => 'busy',
                'current_latitude' => 33.5200,
                'current_longitude' => 36.2800,
                'rating' => 4.9,
                'total_deliveries' => 312,
            ],
            [
                'driver_name' => 'خالد حسن',
                'phone' => '0551234567',
                'vehicle_type' => 'car',
                'vehicle_plate' => 'ز ح ط 9012',
                'license_number' => 'DL345678',
                'status' => 'available',
                'current_latitude' => 33.5100,
                'current_longitude' => 36.2700,
                'rating' => 4.7,
                'total_deliveries' => 189,
            ],
            [
                'driver_name' => 'عمر يوسف',
                'phone' => '0557654321',
                'vehicle_type' => 'motorcycle',
                'vehicle_plate' => 'ي ك ل 3456',
                'license_number' => 'DL456789',
                'status' => 'busy',
                'current_latitude' => 33.5250,
                'current_longitude' => 36.2850,
                'rating' => 4.6,
                'total_deliveries' => 156,
            ],
            [
                'driver_name' => 'سعيد أحمد',
                'phone' => '0501112233',
                'vehicle_type' => 'motorcycle',
                'vehicle_plate' => 'م ن س 7890',
                'license_number' => 'DL567890',
                'status' => 'available',
                'current_latitude' => 33.5180,
                'current_longitude' => 36.2720,
                'rating' => 4.9,
                'total_deliveries' => 278,
            ],
            [
                'driver_name' => 'فهد عبدالله',
                'phone' => '0503334455',
                'vehicle_type' => 'car',
                'vehicle_plate' => 'ع ف ص 2345',
                'license_number' => 'DL678901',
                'status' => 'on_break',
                'current_latitude' => 33.5120,
                'current_longitude' => 36.2780,
                'rating' => 4.5,
                'total_deliveries' => 134,
            ],
            [
                'driver_name' => 'ياسر محمود',
                'phone' => '0555556666',
                'vehicle_type' => 'motorcycle',
                'vehicle_plate' => 'ق ر ش 6789',
                'license_number' => 'DL789012',
                'status' => 'offline',
                'current_latitude' => 33.5160,
                'current_longitude' => 36.2740,
                'rating' => 4.4,
                'total_deliveries' => 98,
            ],
            [
                'driver_name' => 'طارق سالم',
                'phone' => '0557778888',
                'vehicle_type' => 'motorcycle',
                'vehicle_plate' => 'ت ث خ 0123',
                'license_number' => 'DL890123',
                'status' => 'available',
                'current_latitude' => 33.5220,
                'current_longitude' => 36.2820,
                'rating' => 4.8,
                'total_deliveries' => 223,
            ],
        ];

        foreach ($drivers as $driverData) {
            // Create user for driver
            $user = User::create([
                'name' => $driverData['driver_name'],
                'email' => strtolower(str_replace(' ', '', $driverData['driver_name'])).'@delivery.com',
                'password' => bcrypt('password'),
                'phone' => $driverData['phone'],
                'is_admin' => false,
            ]);

            $driverData['user_id'] = $user->id;
            $driverData['last_location_update'] = now()->subMinutes(rand(1, 30));

            $driver = DeliveryDriver::create($driverData);

            // Add location history for today
            for ($i = 0; $i < 20; $i++) {
                $driver->locationHistory()->create([
                    'latitude' => $driverData['current_latitude'] + (rand(-100, 100) / 10000),
                    'longitude' => $driverData['current_longitude'] + (rand(-100, 100) / 10000),
                    'speed' => rand(0, 60),
                    'accuracy' => rand(5, 20),
                    'battery_level' => rand(50, 100).'%',
                    'recorded_at' => now()->subMinutes(rand(1, 480)),
                ]);
            }
        }

        // Create some delivery assignments for busy drivers
        $busyDrivers = DeliveryDriver::where('status', 'busy')->get();
        $orders = Order::latest()->take($busyDrivers->count())->get();

        foreach ($busyDrivers as $index => $driver) {
            if (isset($orders[$index])) {
                DeliveryAssignment::create([
                    'order_id' => $orders[$index]->id,
                    'driver_id' => $driver->id,
                    'status' => ['assigned', 'picked_up', 'in_transit'][rand(0, 2)],
                    'assigned_at' => now()->subMinutes(rand(10, 60)),
                    'picked_up_at' => rand(0, 1) ? now()->subMinutes(rand(5, 30)) : null,
                    'delivery_latitude' => $orders[$index]->latitude,
                    'delivery_longitude' => $orders[$index]->longitude,
                    'estimated_time_minutes' => rand(15, 45),
                ]);
            }
        }

        // Create completed deliveries for today
        $allDrivers = DeliveryDriver::all();
        $completedOrders = Order::latest()->skip($busyDrivers->count())->take(15)->get();

        foreach ($completedOrders as $index => $order) {
            $driver = $allDrivers->random();
            $assignedAt = now()->subHours(rand(1, 8));

            DeliveryAssignment::create([
                'order_id' => $order->id,
                'driver_id' => $driver->id,
                'status' => 'delivered',
                'assigned_at' => $assignedAt,
                'picked_up_at' => $assignedAt->copy()->addMinutes(rand(5, 15)),
                'delivered_at' => $assignedAt->copy()->addMinutes(rand(20, 60)),
                'delivery_latitude' => $order->latitude,
                'delivery_longitude' => $order->longitude,
                'distance_km' => rand(2, 15),
                'estimated_time_minutes' => rand(15, 45),
            ]);
        }

        $this->command->info('✅ Delivery system seeded successfully!');
        $this->command->info('📍 Created '.DeliveryDriver::count().' drivers');
        $this->command->info('🚚 Created '.DeliveryAssignment::count().' delivery assignments');
    }
}
