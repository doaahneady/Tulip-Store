<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test Dashboard Routes
|--------------------------------------------------------------------------
|
| Simple test routes to verify dashboard functionality without middleware
|
*/

Route::get('/test-admin-dashboard', function () {
    // Create a mock metrics array to test the view
    $metrics = [
        'total_users' => 1247,
        'active_users' => 1156,
        'total_stores' => 156,
        'active_stores' => 142,
        'total_revenue' => 2847500,
        'monthly_revenue' => 284750,
        'total_commission' => 142375,
        'monthly_commission' => 14237,
        'total_orders' => 5623,
        'monthly_orders' => 892,
        'pending_orders' => 45,
        'avg_order_value' => 506.32,
        'total_products' => 8934,
        'active_products' => 8456,
        'low_stock_alerts' => 23,
        'user_growth' => 12,
        'revenue_growth' => 18,
        'order_growth' => 15,
        'system_health' => 99.8,
        'system_alerts' => 2,
        'recent_activities' => collect([]),
        'top_performing_stores' => collect([]),
    ];

    return view('dashboards.super-admin.index', compact('metrics'));
});

Route::get('/test-dashboard-structure', function () {
    $structure = [
        'views' => [
            'super-admin' => file_exists(resource_path('views/dashboards/super-admin/index.blade.php')),
            'finance' => file_exists(resource_path('views/dashboards/finance')),
            'hr' => file_exists(resource_path('views/dashboards/hr')),
            'it' => file_exists(resource_path('views/dashboards/it')),
            'supervisor' => file_exists(resource_path('views/dashboards/supervisor')),
            'vendor' => file_exists(resource_path('views/dashboards/vendor')),
        ],
        'controllers' => [
            'SuperAdminController' => class_exists('App\Http\Controllers\Dashboard\SuperAdminController'),
            'FinanceController' => class_exists('App\Http\Controllers\Dashboard\FinanceController'),
            'HRController' => class_exists('App\Http\Controllers\Dashboard\HRController'),
            'ITController' => class_exists('App\Http\Controllers\Dashboard\ITController'),
            'DriverSupervisorController' => class_exists('App\Http\Controllers\Dashboard\DriverSupervisorController'),
            'VendorController' => class_exists('App\Http\Controllers\Dashboard\VendorController'),
        ],
        'models' => [
            'User' => class_exists('App\Models\User'),
            'Role' => class_exists('App\Models\Role'),
            'Permission' => class_exists('App\Models\Permission'),
            'Store' => class_exists('App\Models\Store'),
            'Product' => class_exists('App\Models\Product'),
            'Order' => class_exists('App\Models\Order'),
            'FinancialTransaction' => class_exists('App\Models\FinancialTransaction'),
            'Employee' => class_exists('App\Models\Employee'),
            'Driver' => class_exists('App\Models\Driver'),
        ],
        'routes' => [
            'dashboard_routes_loaded' => file_exists(base_path('routes/dashboard.php')),
        ],
    ];

    return response()->json($structure, 200, [], JSON_PRETTY_PRINT);
});
