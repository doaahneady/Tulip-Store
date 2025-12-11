<?php
/**
 * Test GPS Update Script
 * 
 * This script simulates a driver phone sending GPS location updates
 * Run this to test the live tracking system
 * 
 * Usage: php test_gps_update.php
 */

// Configuration
$baseUrl = 'http://localhost:8000';
$driverId = 1; // Change this to test different drivers

// Sweida, Syria coordinates (will move around the city)
$centerLat = 32.7081;
$centerLng = 36.5686;

echo "🚗 Starting GPS simulation for Driver #{$driverId}\n";
echo "📍 Location: Sweida, Syria\n";
echo "🔄 Sending updates every 3 seconds...\n";
echo "Press Ctrl+C to stop\n\n";

$iteration = 0;

while (true) {
    $iteration++;
    
    // Simulate movement (random walk around Sweida)
    $lat = $centerLat + (rand(-100, 100) / 10000); // ±0.01 degrees (~1km)
    $lng = $centerLng + (rand(-100, 100) / 10000);
    $speed = rand(0, 80); // 0-80 km/h
    $accuracy = rand(5, 20); // 5-20 meters
    
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Display result
    $timestamp = date('H:i:s');
    if ($httpCode == 200) {
        echo "✅ [{$timestamp}] Update #{$iteration}: Lat: {$lat}, Lng: {$lng}, Speed: {$speed} km/h\n";
    } else {
        echo "❌ [{$timestamp}] Failed to update location (HTTP {$httpCode})\n";
        echo "Response: {$response}\n";
    }
    
    // Wait 3 seconds before next update
    sleep(3);
}
