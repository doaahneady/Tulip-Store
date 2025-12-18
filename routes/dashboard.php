<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\SuperAdminController;
use App\Http\Controllers\Dashboard\ITController;
use App\Http\Controllers\Dashboard\HRController;
use App\Http\Controllers\Dashboard\DriverSupervisorController;
use App\Http\Controllers\Dashboard\FinanceController;
use App\Http\Controllers\Dashboard\VendorController;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the 6-dashboard system:
| - Super Admin Dashboard (God Mode)
| - IT/DevOps Dashboard
| - HR Dashboard
| - Driver Supervisor Dashboard
| - Finance Dashboard
| - Product Owner (Vendor) Dashboard
|
*/

// Routes are already prefixed with 'dashboard' and named 'dashboard.' by RouteServiceProvider
    
    /*
    |--------------------------------------------------------------------------
    | Super Admin Dashboard Routes (God Mode)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [SuperAdminController::class, 'index'])->name('index');
        
        // User Management
        Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
        Route::post('/users', [SuperAdminController::class, 'createUser'])->name('users.create');
        Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [SuperAdminController::class, 'deleteUser'])->name('users.delete');
        
        // RBAC Management
        Route::get('/roles', [SuperAdminController::class, 'roles'])->name('roles');
        Route::post('/roles', [SuperAdminController::class, 'createRole'])->name('roles.create');
        Route::put('/roles/{role}', [SuperAdminController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{role}', [SuperAdminController::class, 'deleteRole'])->name('roles.delete');
        
        // Platform Analytics
        Route::get('/analytics', [SuperAdminController::class, 'analytics'])->name('analytics');
        
        // Audit Logs
        Route::get('/audit-logs', [SuperAdminController::class, 'auditLogs'])->name('audit-logs');
        Route::post('/audit-logs/export', [SuperAdminController::class, 'exportAuditLogs'])->name('audit-logs.export');
        
        // System Settings
        Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');
        
        // Emergency Override Functions
        Route::post('/emergency/unlock-user/{user}', [SuperAdminController::class, 'emergencyUnlockUser'])->name('emergency.unlock-user');
        Route::post('/emergency/force-refund/{order}', [SuperAdminController::class, 'emergencyForceRefund'])->name('emergency.force-refund');
        Route::post('/emergency/maintenance-mode', [SuperAdminController::class, 'toggleMaintenanceMode'])->name('emergency.maintenance-mode');
    });

    /*
    |--------------------------------------------------------------------------
    | IT/DevOps Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:it_admin,devops_engineer'])->prefix('it')->name('it.')->group(function () {
        Route::get('/', [ITController::class, 'index'])->name('index');
        
        // System Health Monitoring
        Route::get('/system-health', [ITController::class, 'systemHealth'])->name('system-health');
        Route::post('/services/{service}/status', [ITController::class, 'updateServiceStatus'])->name('services.update-status');
        
        // System Logs
        Route::get('/logs', [ITController::class, 'systemLogs'])->name('logs');
        Route::post('/logs/export', [ITController::class, 'exportLogs'])->name('logs.export');
        
        // API Error Tracking
        Route::get('/api-errors', [ITController::class, 'apiErrors'])->name('api-errors');
        
        // Database Performance
        Route::get('/database', [ITController::class, 'databasePerformance'])->name('database');
        
        // Database Backup Management
        Route::get('/backups', [ITController::class, 'databaseBackups'])->name('backups');
        Route::post('/backups/trigger', [ITController::class, 'triggerBackup'])->name('backups.trigger');
        
        // Deployment Management
        Route::get('/deployments', [ITController::class, 'deployments'])->name('deployments');
        Route::post('/deployments', [ITController::class, 'createDeployment'])->name('deployments.create');
        Route::post('/deployments/{deployment}/rollback', [ITController::class, 'rollbackDeployment'])->name('deployments.rollback');
        
        // System Alerts
        Route::get('/alerts', [ITController::class, 'systemAlerts'])->name('alerts');
        Route::post('/alerts/{alert}/acknowledge', [ITController::class, 'acknowledgeAlert'])->name('alerts.acknowledge');
        Route::post('/alerts/{alert}/resolve', [ITController::class, 'resolveAlert'])->name('alerts.resolve');
        
        // Integration Health
        Route::get('/integrations', [ITController::class, 'integrationHealth'])->name('integrations');
        Route::post('/integrations/test', [ITController::class, 'testIntegration'])->name('integrations.test');
        
        // System Maintenance
        Route::get('/maintenance', [ITController::class, 'systemMaintenance'])->name('maintenance');
        Route::post('/maintenance/clear-cache', [ITController::class, 'clearCache'])->name('maintenance.clear-cache');
    });

    /*
    |--------------------------------------------------------------------------
    | HR Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:hr_manager,hr_coordinator'])->prefix('hr')->name('hr.')->group(function () {
        Route::get('/', [HRController::class, 'index'])->name('index');
        
        // Employee Management
        Route::get('/employees', [HRController::class, 'employees'])->name('employees');
        Route::post('/employees', [HRController::class, 'createEmployee'])->name('employees.create');
        Route::put('/employees/{employee}', [HRController::class, 'updateEmployee'])->name('employees.update');
        
        // Shift Management
        Route::get('/shifts', [HRController::class, 'shifts'])->name('shifts');
        Route::post('/shifts', [HRController::class, 'scheduleShift'])->name('shifts.schedule');
        Route::put('/shifts/{shift}', [HRController::class, 'updateShift'])->name('shifts.update');
        
        // Driver Shift Management
        Route::get('/driver-shifts', [HRController::class, 'driverShifts'])->name('driver-shifts');
        
        // Payroll Management
        Route::get('/payroll', [HRController::class, 'payroll'])->name('payroll');
        Route::post('/payroll/calculate', [HRController::class, 'calculatePayroll'])->name('payroll.calculate');
        Route::post('/payroll/submit', [HRController::class, 'submitPayroll'])->name('payroll.submit');
        
        // Performance Reviews
        Route::get('/performance-reviews', [HRController::class, 'performanceReviews'])->name('performance-reviews');
        Route::post('/performance-reviews', [HRController::class, 'createPerformanceReview'])->name('performance-reviews.create');
        Route::put('/performance-reviews/{review}', [HRController::class, 'updatePerformanceReview'])->name('performance-reviews.update');
        
        // Recruiting
        Route::get('/recruiting', [HRController::class, 'recruiting'])->name('recruiting');
        Route::post('/job-positions', [HRController::class, 'createJobPosition'])->name('job-positions.create');
        Route::post('/applications/{application}/status', [HRController::class, 'updateApplicationStatus'])->name('applications.update-status');
        
        // Announcements
        Route::get('/announcements', [HRController::class, 'announcements'])->name('announcements');
        Route::post('/announcements', [HRController::class, 'createAnnouncement'])->name('announcements.create');
    });

    /*
    |--------------------------------------------------------------------------
    | Driver Supervisor Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:driver_supervisor,logistics_coordinator'])->prefix('supervisor')->name('supervisor.')->group(function () {
        Route::get('/', [DriverSupervisorController::class, 'index'])->name('index');
        
        // Live Driver Tracking
        Route::get('/live-tracking', [DriverSupervisorController::class, 'liveTracking'])->name('live-tracking');
        Route::get('/api/driver-locations', [DriverSupervisorController::class, 'getDriverLocations'])->name('api.driver-locations');
        
        // Driver Management
        Route::get('/drivers', [DriverSupervisorController::class, 'drivers'])->name('drivers');
        Route::post('/drivers/{driver}/status', [DriverSupervisorController::class, 'updateDriverStatus'])->name('drivers.update-status');
        
        // Order Assignment
        Route::get('/order-assignment', [DriverSupervisorController::class, 'orderAssignment'])->name('order-assignment');
        Route::post('/assign-order', [DriverSupervisorController::class, 'assignOrder'])->name('assign-order');
        
        // Route Optimization
        Route::get('/route-optimization', [DriverSupervisorController::class, 'routeOptimization'])->name('route-optimization');
        Route::post('/optimize-routes', [DriverSupervisorController::class, 'optimizeRoutes'])->name('optimize-routes');
        
        // Vehicle Maintenance
        Route::get('/vehicle-maintenance', [DriverSupervisorController::class, 'vehicleMaintenance'])->name('vehicle-maintenance');
        Route::post('/vehicle-maintenance', [DriverSupervisorController::class, 'logMaintenance'])->name('vehicle-maintenance.log');
        
        // Delivery Proof Review
        Route::get('/delivery-proof', [DriverSupervisorController::class, 'deliveryProof'])->name('delivery-proof');
        Route::post('/deliveries/{assignment}/verify', [DriverSupervisorController::class, 'verifyDelivery'])->name('deliveries.verify');
    });

    /*
    |--------------------------------------------------------------------------
    | Finance Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:finance_manager,accountant'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        
        // Transaction Management
        Route::get('/transactions', [FinanceController::class, 'transactions'])->name('transactions');
        Route::post('/transactions/{transaction}/approve', [FinanceController::class, 'approveTransaction'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [FinanceController::class, 'rejectTransaction'])->name('transactions.reject');
        
        // Payout Management
        Route::get('/payouts', [FinanceController::class, 'payouts'])->name('payouts');
        Route::post('/payouts/{payout}/approve', [FinanceController::class, 'approvePayout'])->name('payouts.approve');
        Route::post('/payouts/{payout}/process', [FinanceController::class, 'processPayout'])->name('payouts.process');
        Route::post('/payouts/{payout}/reject', [FinanceController::class, 'rejectPayout'])->name('payouts.reject');
        
        // Revenue Analytics
        Route::get('/revenue', [FinanceController::class, 'revenue'])->name('revenue');
        
        // Expense Management
        Route::get('/expenses', [FinanceController::class, 'expenses'])->name('expenses');
        
        // Financial Reports
        Route::get('/reports', [FinanceController::class, 'reports'])->name('reports');
        Route::post('/reports/export', [FinanceController::class, 'exportReport'])->name('reports.export');
        
        // Tax Management
        Route::get('/tax', [FinanceController::class, 'tax'])->name('tax');
        Route::post('/tax/calculate', [FinanceController::class, 'calculateTax'])->name('tax.calculate');
        
        // Payroll Processing (from HR)
        Route::get('/payroll', [FinanceController::class, 'payrollProcessing'])->name('payroll');
        Route::post('/payroll/{payrollRecord}/approve', [FinanceController::class, 'approvePayroll'])->name('payroll.approve');
        Route::post('/payroll/{payrollRecord}/process', [FinanceController::class, 'processPayroll'])->name('payroll.process');
    });

    /*
    |--------------------------------------------------------------------------
    | Product Owner (Vendor) Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:product_owner,store_manager'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        
        // Inventory Management
        Route::get('/products', [VendorController::class, 'products'])->name('products');
        Route::post('/products', [VendorController::class, 'createProduct'])->name('products.create');
        Route::put('/products/{product}', [VendorController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [VendorController::class, 'deleteProduct'])->name('products.delete');
        Route::post('/products/{product}/stock', [VendorController::class, 'updateStock'])->name('products.update-stock');
        
        // Order Management
        Route::get('/orders', [VendorController::class, 'orders'])->name('orders');
        Route::post('/orders/{order}/status', [VendorController::class, 'updateOrderStatus'])->name('orders.update-status');
        
        // Sales Analytics
        Route::get('/analytics', [VendorController::class, 'analytics'])->name('analytics');
        
        // Financial Management
        Route::get('/earnings', [VendorController::class, 'earnings'])->name('earnings');
        Route::post('/payouts/request', [VendorController::class, 'requestPayout'])->name('payouts.request');
        
        // Store Management
        Route::get('/store-profile', [VendorController::class, 'storeProfile'])->name('store-profile');
        Route::put('/store-profile', [VendorController::class, 'updateStoreProfile'])->name('store-profile.update');
    });

    /*
    |--------------------------------------------------------------------------
    | API Routes for Real-time Updates
    |--------------------------------------------------------------------------
    */
    Route::prefix('api')->name('api.')->group(function () {
        // Super Admin API
        Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
            Route::get('/metrics', [SuperAdminController::class, 'getMetrics'])->name('metrics');
            Route::get('/system-status', [SuperAdminController::class, 'getSystemStatus'])->name('system-status');
        });
        
        // IT/DevOps API
        Route::middleware(['role:it_admin,devops_engineer'])->prefix('it')->name('it.')->group(function () {
            Route::get('/system-health', [ITController::class, 'getSystemHealth'])->name('system-health');
            Route::get('/live-logs', [ITController::class, 'getLiveLogs'])->name('live-logs');
        });
        
        // Driver Supervisor API
        Route::middleware(['role:driver_supervisor,logistics_coordinator'])->prefix('supervisor')->name('supervisor.')->group(function () {
            Route::get('/driver-locations', [DriverSupervisorController::class, 'getDriverLocations'])->name('driver-locations');
            Route::post('/driver-location', [DriverSupervisorController::class, 'updateDriverLocation'])->name('update-driver-location');
        });
        
        // Finance API
        Route::middleware(['role:finance_manager,accountant'])->prefix('finance')->name('finance.')->group(function () {
            Route::get('/dashboard-metrics', [FinanceController::class, 'getDashboardMetrics'])->name('dashboard-metrics');
        });
        
        // Vendor API
        Route::middleware(['role:product_owner,store_manager'])->prefix('vendor')->name('vendor.')->group(function () {
            Route::get('/dashboard-metrics', [VendorController::class, 'getDashboardMetrics'])->name('dashboard-metrics');
        });
    });

/*
|--------------------------------------------------------------------------
| WebSocket Routes for Real-time Updates
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('ws')->name('ws.')->group(function () {
    Route::get('/admin', function () {
        return response()->json(['channel' => 'admin.global']);
    })->middleware(['role:super_admin']);
    
    Route::get('/devops', function () {
        return response()->json(['channel' => 'devops.global']);
    })->middleware(['role:it_admin,devops_engineer']);
    
    Route::get('/hr', function () {
        return response()->json(['channel' => 'hr.global']);
    })->middleware(['role:hr_manager,hr_coordinator']);
    
    Route::get('/supervisor', function () {
        return response()->json(['channel' => 'supervisor.global']);
    })->middleware(['role:driver_supervisor,logistics_coordinator']);
    
    Route::get('/finance', function () {
        return response()->json(['channel' => 'finance.global']);
    })->middleware(['role:finance_manager,accountant']);
    
    Route::get('/vendor/{storeId}', function ($storeId) {
        return response()->json(['channel' => "vendor.{$storeId}"]);
    })->middleware(['role:product_owner,store_manager']);
});