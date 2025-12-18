<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\SuperAdminController;

// Test route without middleware to verify controller works
Route::get('/test-super-admin', function () {
    // Create mock metrics data
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

// Test controller instantiation
Route::get('/test-controller', function () {
    try {
        $controller = new SuperAdminController();
        return "✅ SuperAdminController instantiated successfully!";
    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});