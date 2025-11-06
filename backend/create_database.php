<?php
/**
 * Database Creation Script
 * Run this script to create the tulip_store database
 */

// Database configuration
$host = '127.0.0.1';
$port = 3306;
$rootUser = 'root';
$rootPassword = ''; // Change this if your root user has a password
$databaseName = 'tulip_store';

try {
    // Connect to MySQL server without selecting a database
    $pdo = new PDO(
        "mysql:host={$host};port={$port}",
        $rootUser,
        $rootPassword,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    echo "✅ Database '{$databaseName}' created successfully!\n";
    echo "You can now run: php artisan migrate\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n";
    echo "Please check:\n";
    echo "1. MySQL server is running\n";
    echo "2. Root password is correct (update \$rootPassword in this script if needed)\n";
    echo "3. MySQL is accessible at {$host}:{$port}\n";
    exit(1);
}

