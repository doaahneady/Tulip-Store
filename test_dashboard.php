<?php

// Simple test script to check dashboard functionality
echo "Testing Dashboard System...\n\n";

// Test 1: Check if classes exist
$controllers = [
    'SuperAdminController' => 'App\Http\Controllers\Dashboard\SuperAdminController',
    'FinanceController' => 'App\Http\Controllers\Dashboard\FinanceController',
    'HRController' => 'App\Http\Controllers\Dashboard\HRController',
    'ITController' => 'App\Http\Controllers\Dashboard\ITController',
    'DriverSupervisorController' => 'App\Http\Controllers\Dashboard\DriverSupervisorController',
    'VendorController' => 'App\Http\Controllers\Dashboard\VendorController',
];

echo "1. Controller Classes:\n";
foreach ($controllers as $name => $class) {
    $exists = class_exists($class) ? '✓' : '✗';
    echo "   {$exists} {$name}\n";
}

// Test 2: Check if view files exist
echo "\n2. View Files:\n";
$views = [
    'super-admin/index' => 'resources/views/dashboards/super-admin/index.blade.php',
    'finance/index' => 'resources/views/dashboards/finance/index.blade.php',
    'hr/index' => 'resources/views/dashboards/hr/index.blade.php',
    'it/index' => 'resources/views/dashboards/it/index.blade.php',
    'supervisor/index' => 'resources/views/dashboards/supervisor/index.blade.php',
    'vendor/index' => 'resources/views/dashboards/vendor/index.blade.php',
];

foreach ($views as $name => $path) {
    $exists = file_exists($path) ? '✓' : '✗';
    echo "   {$exists} {$name}\n";
}

// Test 3: Check if models exist
echo "\n3. Model Classes:\n";
$models = [
    'User' => 'App\Models\User',
    'Role' => 'App\Models\Role',
    'Permission' => 'App\Models\Permission',
    'Store' => 'App\Models\Store',
    'Product' => 'App\Models\Product',
    'Order' => 'App\Models\Order',
    'Employee' => 'App\Models\Employee',
    'Driver' => 'App\Models\Driver',
    'PayrollRecord' => 'App\Models\PayrollRecord',
    'Shift' => 'App\Models\Shift',
];

foreach ($models as $name => $class) {
    $exists = class_exists($class) ? '✓' : '✗';
    echo "   {$exists} {$name}\n";
}

// Test 4: Check route files
echo "\n4. Route Files:\n";
$routes = [
    'dashboard.php' => 'routes/dashboard.php',
    'test-dashboard.php' => 'routes/test-dashboard.php',
];

foreach ($routes as $name => $path) {
    $exists = file_exists($path) ? '✓' : '✗';
    echo "   {$exists} {$name}\n";
}

echo "\nDashboard system structure check complete!\n";