<?php
/**
 * Test Multiple Drivers GPS Update Script
 * 
 * This script simulates multiple drivers sending GPS updates simultaneously
 * 
 * Usage: php test_multiple_drivers.php
 */

// Configuration
$baseUrl = 'http://localhost:8000';
$driverIds = [1, 2, 3, 4, 5, 6]; // Test all 6 drivers

// Sweida, Syria - different starting points for each driver
$driverLocations = [
    1 => ['lat' => 32.7081, 'lng' => 36.5686, 'name' => 'أحمد محمد'],
    2 => ['lat' => 32.7150, 'lng' => 36.5750, 'name' => 'محمد علي'],
    3 => ['lat' => 32.7020, 'lng' => 36.5620, 'name' => 'خالد عبدالله'],
    4 => ['lat' => 32.7100, 'lng' => 36.5800, 'name' => 'عبدالرحمن سعيد'],
    5 => ['lat' => 32.7050, 'lng' => 36.5650, 'name' => 'سعد فهد'],
    6 => ['lat' => 32.7130, 'lng' => 36.5720, 'name' => 'فيصل ناصر'],
];

echo "🚗 Starting GPS simulation for {count($driverIds)} drivers\n";
echo "📍 Location: Sweida, Syria\n";
echo "🔄 Sending updates every 5 seconds...\n";
echo "Press Ctrl+C to stop\n\n";

$iteration = 0;

while (true) {
    $iteration++;
    echo "\n--- Update Round #{$iteration} ---\n";
    
    foreach ($driverIds as $driverId) {
        $location = $driverLocations[$driverId];
        
        // Simulate movement
        $lat = $location['lat'] + (rand(-50, 50) / 10000);
        $lng = $location['lng'] + (rand(-50, 50) / 10000);
        $speed = rand(0, 80);
        $accuracy = rand(5, 20);
        
        // Update stored location for next iteration
        $driverLocations[$driverId]['lat'] = $lat;
        $driverLocations[$driverId]['lng'] = $lng;
        
        // Prepare data
        $data = [
            'driver_id' => $driverId,
            'latitude' => $lat,
            'longitude' => $lng,
            'speed' => $speed,
            'accuracy' => $accuracy,
        ];
        
        // Send to API
        $ch = curl_init($baseUrl . '/api/driver/location/update');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Display result
        $timestamp = date('H:i:s');
        $name = $location['name'];
        if ($httpCode == 200) {
            echo "✅ [{$timestamp}] Driver #{$driverId} ({$name}): {$speed} km/h\n";
        } else {
            echo "❌ [{$timestamp}] Driver #{$driverId} ({$name}): FAILED\n";
        }
    }
    
    // Wait 5 seconds before next round
    sleep(5);
}
