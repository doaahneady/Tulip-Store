<?php

use Illuminate\Support\Facades\Route;

// Simple test route to verify the dashboard works
Route::get('/simple-admin-test', function () {
    // Mock data for testing
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

// Test if the view renders
Route::get('/test-view-only', function () {
    return '<h1>Dashboard System Status</h1>
    <ul>
        <li>Super Admin View: '.(view()->exists('dashboards.super-admin.index') ? '✓ EXISTS' : '✗ MISSING').'</li>
        <li>Finance View: '.(view()->exists('dashboards.finance.index') ? '✓ EXISTS' : '✗ MISSING').'</li>
        <li>HR View: '.(view()->exists('dashboards.hr.index') ? '✓ EXISTS' : '✗ MISSING').'</li>
        <li>IT View: '.(view()->exists('dashboards.it.index') ? '✓ EXISTS' : '✗ MISSING').'</li>
        <li>Supervisor View: '.(view()->exists('dashboards.supervisor.index') ? '✓ EXISTS' : '✗ MISSING').'</li>
        <li>Vendor View: '.(view()->exists('dashboards.vendor.index') ? '✓ EXISTS' : '✗ MISSING').'</li>
    </ul>';
});
