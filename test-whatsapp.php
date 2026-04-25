<?php

/**
 * Simple test script for WhatsApp Service
 * 
 * Usage: php test-whatsapp.php
 * 
 * Make sure to configure GREEN_API_INSTANCE_ID and GREEN_API_TOKEN in .env first
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\WhatsAppService;

echo "=== WhatsApp Service Test ===\n\n";

$whatsappService = new WhatsAppService();

// Check if configured
if (!$whatsappService->isConfigured()) {
    echo "❌ WhatsApp service is NOT configured!\n";
    echo "Please update GREEN_API_INSTANCE_ID and GREEN_API_TOKEN in your .env file.\n";
    exit(1);
}

echo "✅ WhatsApp service is configured\n\n";

// Test phone number (replace with your actual WhatsApp number for testing)
$testPhone = readline("Enter test phone number (with country code, e.g., 966501234567): ");

if (empty($testPhone)) {
    echo "❌ Phone number is required\n";
    exit(1);
}

echo "\nSending test message to: {$testPhone}\n";

// Send test verification code
$testCode = '123456';
$result = $whatsappService->sendVerificationCode($testPhone, $testCode, 'Test User');

if ($result['success']) {
    echo "✅ Message sent successfully!\n";
    echo "Response: " . json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "❌ Failed to send message\n";
    echo "Error: " . ($result['error'] ?? 'Unknown error') . "\n";
}

echo "\n=== Test Complete ===\n";
