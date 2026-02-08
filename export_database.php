<?php

// Database Export Script for Hostinger Deployment
// This script exports your current database to SQL file

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Get database configuration
    $host = config('database.connections.mysql.host');
    $database = config('database.connections.mysql.database');
    $username = config('database.connections.mysql.username');
    $password = config('database.connections.mysql.password');

    // Create export filename with timestamp
    $filename = 'tulip_store_export_'.date('Y-m-d_H-i-s').'.sql';

    // Build mysqldump command
    $command = sprintf(
        'mysqldump -h%s -u%s %s %s > %s',
        escapeshellarg($host),
        escapeshellarg($username),
        $password ? '-p'.escapeshellarg($password) : '',
        escapeshellarg($database),
        escapeshellarg($filename)
    );

    echo "🗄️  Exporting database...\n";
    echo "Database: {$database}\n";
    echo "Host: {$host}\n";
    echo "Output file: {$filename}\n\n";

    // Execute the command
    $output = [];
    $return_var = 0;
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        echo "✅ Database exported successfully!\n";
        echo "📁 File saved as: {$filename}\n";
        echo '📊 File size: '.formatBytes(filesize($filename))."\n\n";

        echo "📋 Next steps:\n";
        echo "1. Upload this SQL file to your Hostinger phpMyAdmin\n";
        echo "2. Import it into your Hostinger database\n";
        echo "3. Update your .env file with Hostinger database credentials\n";
    } else {
        echo "❌ Database export failed!\n";
        echo 'Error output: '.implode("\n", $output)."\n";
        echo "\n💡 Alternative: Use phpMyAdmin to export your database manually\n";
    }

} catch (Exception $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
    echo "\n💡 Alternative: Use phpMyAdmin to export your database manually\n";
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

    return round(pow(1024, $base - floor($base)), $precision).' '.$suffixes[floor($base)];
}
