<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        // Drivers in Sweida, Syria (coordinates around Sweida city)
        $drivers = [
            [
                'name' => 'أحمد محمد',
                'phone' => '0501234567',
                'email' => 'ahmed@tulipstore.com',
                'license_number' => 'LIC-001',
                'vehicle_type' => 'سيارة صغيرة',
                'vehicle_plate' => 'أ ب ج 1234',
                'status' => 'available',
                'current_latitude' => 32.7081,
                'current_longitude' => 36.5686,
                'last_location_update' => now(),
                'total_deliveries' => 145,
                'rating' => 4.8,
                'is_active' => true,
            ],
            [
                'name' => 'محمد علي',
                'phone' => '0502345678',
                'email' => 'mohammed@tulipstore.com',
                'license_number' => 'LIC-002',
                'vehicle_type' => 'شاحنة صغيرة',
                'vehicle_plate' => 'د هـ و 5678',
                'status' => 'busy',
                'current_latitude' => 32.7150,
                'current_longitude' => 36.5750,
                'last_location_update' => now(),
                'total_deliveries' => 203,
                'rating' => 4.9,
                'is_active' => true,
            ],
            [
                'name' => 'خالد عبدالله',
                'phone' => '0503456789',
                'email' => 'khaled@tulipstore.com',
                'license_number' => 'LIC-003',
                'vehicle_type' => 'دراجة نارية',
                'vehicle_plate' => 'ز ح ط 9012',
                'status' => 'available',
                'current_latitude' => 32.7020,
                'current_longitude' => 36.5620,
                'last_location_update' => now(),
                'total_deliveries' => 312,
                'rating' => 5.0,
                'is_active' => true,
            ],
            [
                'name' => 'عبدالرحمن سعيد',
                'phone' => '0504567890',
                'email' => 'abdulrahman@tulipstore.com',
                'license_number' => 'LIC-004',
                'vehicle_type' => 'سيارة متوسطة',
                'vehicle_plate' => 'ي ك ل 3456',
                'status' => 'on_break',
                'current_latitude' => 32.7100,
                'current_longitude' => 36.5800,
                'last_location_update' => now(),
                'total_deliveries' => 178,
                'rating' => 4.7,
                'is_active' => true,
            ],
            [
                'name' => 'سعد فهد',
                'phone' => '0505678901',
                'email' => 'saad@tulipstore.com',
                'license_number' => 'LIC-005',
                'vehicle_type' => 'سيارة صغيرة',
                'vehicle_plate' => 'م ن س 7890',
                'status' => 'busy',
                'current_latitude' => 32.7050,
                'current_longitude' => 36.5650,
                'last_location_update' => now(),
                'total_deliveries' => 256,
                'rating' => 4.6,
                'is_active' => true,
            ],
            [
                'name' => 'فيصل ناصر',
                'phone' => '0506789012',
                'email' => 'faisal@tulipstore.com',
                'license_number' => 'LIC-006',
                'vehicle_type' => 'شاحنة كبيرة',
                'vehicle_plate' => 'ع ف ص 2345',
                'status' => 'available',
                'current_latitude' => 32.7130,
                'current_longitude' => 36.5720,
                'last_location_update' => now(),
                'total_deliveries' => 89,
                'rating' => 4.9,
                'is_active' => true,
            ],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }

        // Create location history for each driver
        foreach (Driver::all() as $driver) {
            for ($i = 0; $i < 10; $i++) {
                $driver->locations()->create([
                    'latitude' => $driver->current_latitude + (rand(-100, 100) / 10000),
                    'longitude' => $driver->current_longitude + (rand(-100, 100) / 10000),
                    'speed' => rand(0, 80),
                    'accuracy' => rand(5, 20),
                    'recorded_at' => now()->subMinutes(rand(1, 60)),
                ]);
            }
        }
    }
}
