<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemService;

class SystemServicesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'web_server',
                'display_name' => 'Web Server',
                'status' => 'online',
                'response_time' => 45,
            ],
            [
                'name' => 'database',
                'display_name' => 'Database',
                'status' => 'online',
                'response_time' => 12,
            ],
            [
                'name' => 'redis_cache',
                'display_name' => 'Redis Cache',
                'status' => 'online',
                'response_time' => 3,
            ],
            [
                'name' => 'payment_gateway',
                'display_name' => 'Payment Gateway',
                'status' => 'online',
                'response_time' => 120,
            ],
            [
                'name' => 'email_service',
                'display_name' => 'Email Service',
                'status' => 'degraded',
                'response_time' => 2100,
            ],
            [
                'name' => 'file_storage',
                'display_name' => 'File Storage',
                'status' => 'online',
                'response_time' => 89,
            ],
        ];

        foreach ($services as $service) {
            SystemService::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}