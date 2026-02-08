<?php

use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\AdministrativeApprovalsController;
use App\Http\Controllers\Dashboard\DriverSupervisorController;
use App\Http\Controllers\Dashboard\FinanceController;
use App\Http\Controllers\Dashboard\HRController;
use App\Http\Controllers\Dashboard\ITController;
use App\Http\Controllers\Dashboard\MainController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ReviewModerationController;
use App\Http\Controllers\Dashboard\SuperAdminController;
use App\Http\Controllers\Dashboard\SupportDashboardController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes - Professional Dashboards
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $employee = auth('employee')->user();
    $dashboards = $employee?->available_dashboards ?? [];
    if (is_array($dashboards) && count($dashboards) === 1) {
        return redirect()->route($dashboards[0]['route']);
    }
    if (is_array($dashboards) && count($dashboards) > 0) {
        return redirect()->route($dashboards[0]['route']);
    }

    return redirect()->route('employee.dashboard');
})->name('main');

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);
    session(['dashboard_locale' => $locale]);

    return back();
})->name('locale.set');

// General Employee Routes (Flow 12 - Attendance)
Route::prefix('my-attendance')->name('my-attendance.')->middleware('auth:employee')->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\AttendanceController::class, 'index'])->name('index');
    Route::post('/check-in', [\App\Http\Controllers\Dashboard\AttendanceController::class, 'checkIn'])->name('check-in');
    Route::post('/check-out', [\App\Http\Controllers\Dashboard\AttendanceController::class, 'checkOut'])->name('check-out');
});

Route::prefix('administrative-approvals')->name('administrative-approvals.')->middleware('auth:employee')->group(function () {
    Route::get('/', [AdministrativeApprovalsController::class, 'index'])->name('index');
    Route::post('/', [AdministrativeApprovalsController::class, 'store'])->name('store');
    Route::get('/manage', [AdministrativeApprovalsController::class, 'manage'])->middleware('dashboard.role:admin,hr')->name('manage');
    Route::post('/{approval}/approve', [AdministrativeApprovalsController::class, 'approve'])->middleware('dashboard.role:admin,hr')->name('approve');
    Route::post('/{approval}/reject', [AdministrativeApprovalsController::class, 'reject'])->middleware('dashboard.role:admin,hr')->name('reject');
});

// Customer Support Dashboard (Flow 9/10)
Route::prefix('cs')->name('cs.')->middleware('dashboard.role:cs')->group(function () {
    Route::get('/', [SupportDashboardController::class, 'index'])->name('index');
    Route::get('/tickets', [SupportDashboardController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/{ticket}', [SupportDashboardController::class, 'showTicket'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [SupportDashboardController::class, 'replyTicket'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/close', [SupportDashboardController::class, 'closeTicket'])->name('tickets.close');
    Route::post('/tickets/{ticket}/assign-to-me', [SupportDashboardController::class, 'assignToMe'])->name('tickets.assign-to-me');
    Route::post('/tickets/{ticket}/resolve', [SupportDashboardController::class, 'resolveTicket'])->name('tickets.resolve');
    Route::post('/tickets', [SupportDashboardController::class, 'createTicket'])->name('tickets.create');
    Route::post('/tickets/{ticket}/initiate-refund', [SupportDashboardController::class, 'initiateRefund'])->name('tickets.initiate-refund');

    Route::get('/orders', [SupportDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [SupportDashboardController::class, 'showOrder'])->name('orders.show');

    Route::get('/trader-products', [SupportDashboardController::class, 'traderProducts'])->name('trader-products');
    Route::post('/trader-products/{product}/approve', [SupportDashboardController::class, 'approveTraderProduct'])->name('trader-products.approve');
    Route::post('/trader-products/{product}/reject', [SupportDashboardController::class, 'rejectTraderProduct'])->name('trader-products.reject');
});

Route::prefix('admin')->name('admin.')->middleware('dashboard.role:admin')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');
    Route::get('/portal', [AdminDashboardController::class, 'index'])->name('portal');
    Route::get('/metrics', [SuperAdminController::class, 'getMetrics'])->name('metrics');

    Route::get('/cross-department-kpis', [SuperAdminController::class, 'crossDepartmentKPIs'])->name('cross-department-kpis');

    Route::get('/orders', [SuperAdminController::class, 'orders'])->name('orders');
    Route::post('/orders/{order}/status', [SuperAdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/assignment', [SuperAdminController::class, 'overrideOrderAssignment'])->name('orders.override-assignment');

    Route::get('/approvals', [SuperAdminController::class, 'approvals'])->name('approvals');
    Route::post('/approvals/transactions/{transaction}/approve', [SuperAdminController::class, 'approveFinancialTransaction'])->name('approvals.transactions.approve');
    Route::post('/approvals/transactions/{transaction}/reject', [SuperAdminController::class, 'rejectFinancialTransaction'])->name('approvals.transactions.reject');
    Route::post('/approvals/leaves/{leave}/approve', [SuperAdminController::class, 'approveLeave'])->name('approvals.leaves.approve');
    Route::post('/approvals/leaves/{leave}/reject', [SuperAdminController::class, 'rejectLeave'])->name('approvals.leaves.reject');

    Route::get('/alerts', [SuperAdminController::class, 'alerts'])->name('alerts');
    Route::get('/reassignment', [SuperAdminController::class, 'reassignment'])->name('reassignment');
    Route::post('/reassignment/orders/{order}', [SuperAdminController::class, 'reassignOrder'])->name('reassignment.orders');
    Route::post('/reassignment/tickets/{ticket}', [SuperAdminController::class, 'reassignTicket'])->name('reassignment.tickets');

    Route::get('/activity-logs', [SuperAdminController::class, 'activityLogs'])->name('activity-logs');
    Route::get('/financial-override', [SuperAdminController::class, 'financialOverride'])->name('financial-override');
    Route::post('/financial-override/{transaction}', [SuperAdminController::class, 'updateFinancialOverride'])->name('financial-override.update');

    Route::get('/features', [SuperAdminController::class, 'featureToggles'])->name('features');
    Route::post('/features', [SuperAdminController::class, 'updateFeatureToggles'])->name('features.update');

    Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');

    Route::get('/database-health', [SuperAdminController::class, 'databaseHealth'])->name('database-health');
    Route::get('/announcements', [SuperAdminController::class, 'announcements'])->name('announcements');
    Route::post('/announcements', [SuperAdminController::class, 'createAnnouncement'])->name('announcements.create');
    Route::get('/audit-logs', [SuperAdminController::class, 'auditLogs'])->name('audit-logs');
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::post('/users', [SuperAdminController::class, 'createUser'])->name('users.create');
    Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [SuperAdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/roles', [SuperAdminController::class, 'roles'])->name('roles');
    Route::post('/roles/employees/{employee}', [SuperAdminController::class, 'updateEmployeeDashboardRules'])->name('roles.employees.update');

    Route::get('/categories', [SuperAdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [SuperAdminController::class, 'createCategory'])->name('categories.create');
    Route::put('/categories/{category}', [SuperAdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [SuperAdminController::class, 'deleteCategory'])->name('categories.delete');

    Route::get('/employees', [SuperAdminController::class, 'employees'])->name('employees');
    Route::get('/employees/{employee}/dashboards', [SuperAdminController::class, 'editEmployeeDashboards'])->name('employees.dashboards.edit');
    Route::put('/employees/{employee}/dashboards', [SuperAdminController::class, 'updateEmployeeDashboards'])->name('employees.dashboards.update');

    Route::get('/gifts', [SuperAdminController::class, 'gifts'])->name('gifts');
    Route::post('/gifts/{gift}/toggle-active', [SuperAdminController::class, 'toggleGiftActive'])->name('gifts.toggle-active');
    Route::post('/gifts/{gift}/toggle-featured', [SuperAdminController::class, 'toggleGiftFeatured'])->name('gifts.toggle-featured');
    Route::delete('/gifts/{gift}', [SuperAdminController::class, 'deleteGift'])->name('gifts.delete');
    Route::get('/mart', [SuperAdminController::class, 'mart'])->name('mart');
    Route::post('/mart/products/{product}/toggle-active', [SuperAdminController::class, 'toggleMartProductActive'])->name('mart.products.toggle-active');
    Route::post('/mart/products/{product}/toggle-featured', [SuperAdminController::class, 'toggleMartProductFeatured'])->name('mart.products.toggle-featured');
    Route::get('/mart/categories/create', [SuperAdminController::class, 'createMartCategory'])->name('mart.categories.create');
    Route::post('/mart/categories', [SuperAdminController::class, 'storeMartCategory'])->name('mart.categories.store');
    Route::get('/mart/categories/{category}/edit', [SuperAdminController::class, 'editMartCategory'])->name('mart.categories.edit');
    Route::put('/mart/categories/{category}', [SuperAdminController::class, 'updateMartCategory'])->name('mart.categories.update');
    Route::post('/mart/categories/{category}/toggle-active', [SuperAdminController::class, 'toggleMartCategoryActive'])->name('mart.categories.toggle-active');
    Route::delete('/mart/categories/{category}', [SuperAdminController::class, 'deleteMartCategory'])->name('mart.categories.delete');

    // Inventory Alerts & Restock (Flow 11)
    Route::get('/inventory/alerts', [\App\Http\Controllers\Dashboard\InventoryController::class, 'alerts'])->name('inventory.alerts');
    Route::get('/inventory/history/{id}', [\App\Http\Controllers\Dashboard\InventoryController::class, 'history'])->name('inventory.history');
    Route::post('/inventory/restock/{id}', [\App\Http\Controllers\Dashboard\InventoryController::class, 'restock'])->name('inventory.restock');

    Route::get('/attendance', [SuperAdminController::class, 'attendance'])->name('attendance');

    Route::delete('/mart/products/{product}', [SuperAdminController::class, 'deleteMartProduct'])->name('mart.products.delete');
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/users', [SuperAdminController::class, 'exportUsers'])->name('users');
        Route::get('/orders', [SuperAdminController::class, 'exportOrders'])->name('orders');
        Route::get('/financial-transactions', [SuperAdminController::class, 'exportFinancialTransactions'])->name('financial-transactions');
        Route::get('/products', [SuperAdminController::class, 'exportProducts'])->name('products');
        Route::get('/audit-logs', [SuperAdminController::class, 'exportAuditLogs'])->name('audit-logs');
        Route::get('/employees', [SuperAdminController::class, 'exportEmployees'])->name('employees');
        Route::get('/stores', [SuperAdminController::class, 'exportStores'])->name('stores');
        Route::get('/system-report', [SuperAdminController::class, 'exportSystemReport'])->name('system-report');
        Route::get('/user-data', [SuperAdminController::class, 'exportUserData'])->name('user-data');
        Route::get('/annual-audit', [SuperAdminController::class, 'exportAnnualAudit'])->name('annual-audit');
    });

    Route::prefix('emergency')->name('emergency.')->group(function () {
        Route::post('/maintenance-mode', [SuperAdminController::class, 'toggleMaintenanceMode'])->name('maintenance-mode');
        Route::post('/users/{user}/unlock', [SuperAdminController::class, 'emergencyUnlockUser'])->name('users.unlock');
        Route::post('/orders/{order}/force-refund', [SuperAdminController::class, 'emergencyForceRefund'])->name('orders.force-refund');
    });

    Route::prefix('gifts')->name('gifts.')->group(function () {
        Route::post('/boxes/{box}/toggle-active', [SuperAdminController::class, 'toggleGiftBoxActive'])->name('boxes.toggle-active');
        Route::delete('/boxes/{box}', [SuperAdminController::class, 'deleteGiftBox'])->name('boxes.delete');

        Route::post('/wrappings/{wrapping}/toggle-active', [SuperAdminController::class, 'toggleGiftWrappingActive'])->name('wrappings.toggle-active');
        Route::delete('/wrappings/{wrapping}', [SuperAdminController::class, 'deleteGiftWrapping'])->name('wrappings.delete');

        Route::post('/ribbons/{ribbon}/toggle-active', [SuperAdminController::class, 'toggleGiftRibbonActive'])->name('ribbons.toggle-active');
        Route::delete('/ribbons/{ribbon}', [SuperAdminController::class, 'deleteGiftRibbon'])->name('ribbons.delete');

        Route::post('/cards/{card}/toggle-active', [SuperAdminController::class, 'toggleGiftCardActive'])->name('cards.toggle-active');
        Route::delete('/cards/{card}', [SuperAdminController::class, 'deleteGiftCard'])->name('cards.delete');

        Route::post('/fillers/{filler}/toggle-active', [SuperAdminController::class, 'toggleGiftFillerActive'])->name('fillers.toggle-active');
        Route::delete('/fillers/{filler}', [SuperAdminController::class, 'deleteGiftFiller'])->name('fillers.delete');
    });
});

Route::prefix('finance')->name('finance.')->middleware('dashboard.role:finance')->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('index');
    Route::get('/transactions', [FinanceController::class, 'transactions'])->name('transactions');
    Route::get('/payroll', [FinanceController::class, 'payroll'])->name('payroll');
    Route::get('/payroll/{transaction}/pay', [FinanceController::class, 'paySalaryForm'])->name('payroll.pay');
    Route::post('/payroll/{transaction}/mark-paid', [FinanceController::class, 'markSalaryPaid'])->name('payroll.mark-paid');
    Route::post('/transactions', [FinanceController::class, 'createTransaction'])->name('transactions.create');
    Route::get('/transactions/export', [FinanceController::class, 'exportTransactions'])->name('transactions.export');

    Route::get('/approvals', [FinanceController::class, 'approvals'])->name('approvals');
    Route::post('/approvals/transactions/{transaction}/approve', [FinanceController::class, 'approveTransaction'])->name('approvals.transactions.approve');
    Route::post('/approvals/transactions/{transaction}/reject', [FinanceController::class, 'rejectTransaction'])->name('approvals.transactions.reject');
    Route::post('/approvals/payouts/{payout}/approve', [FinanceController::class, 'approvePayout'])->name('approvals.payouts.approve');
    Route::post('/approvals/payouts/{payout}/reject', [FinanceController::class, 'rejectPayout'])->name('approvals.payouts.reject');

    Route::get('/payouts', [FinanceController::class, 'payouts'])->name('payouts');
    Route::get('/revenue', [FinanceController::class, 'revenue'])->name('revenue');
    Route::get('/expenses', [FinanceController::class, 'expenses'])->name('expenses');
    Route::post('/expenses', [FinanceController::class, 'createExpense'])->name('expenses.create');
    Route::get('/reports', [FinanceController::class, 'reports'])->name('reports');
    Route::get('/tax', [FinanceController::class, 'tax'])->name('tax');
    Route::get('/tax/export', [FinanceController::class, 'exportTaxReport'])->name('tax.export');
});

// HR Dashboard - Full HR Management
Route::prefix('hr')->name('hr.')->middleware('dashboard.role:hr,admin')->group(function () {
    Route::get('/', [HRController::class, 'index'])->name('index');

    Route::get('/employees', [HRController::class, 'employees'])->name('employees');
    Route::get('/employees/create', [HRController::class, 'createEmployeeForm'])->name('employees.create');
    Route::get('/create-employee', function () {
        return redirect()->route('dashboard.hr.employees.create');
    })->name('create.employee');
    Route::post('/employees', [HRController::class, 'createEmployee'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [HRController::class, 'editEmployeeForm'])->name('employees.edit');
    Route::put('/employees/{employee}', [HRController::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/employees/{employee}', [HRController::class, 'deleteEmployee'])->name('employees.delete');

    Route::get('/attendance', [HRController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/clock-in', [HRController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [HRController::class, 'clockOut'])->name('attendance.clock-out');

    Route::get('/leave-requests', [HRController::class, 'leaveRequests'])->name('leave-requests');
    Route::post('/leave-requests/submit', [HRController::class, 'submitLeaveRequest'])->name('leave.submit');
    Route::post('/leave-requests/{leaveRequest}/approve', [HRController::class, 'approveLeaveRequest'])->name('leave.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [HRController::class, 'rejectLeaveRequest'])->name('leave.reject');
    Route::post('/leave-requests/{leaveRequest}/cancel', [HRController::class, 'cancelLeaveRequest'])->name('leave.cancel');

    Route::get('/payroll', [HRController::class, 'payroll'])->name('payroll');
    Route::post('/payroll/calculate', [HRController::class, 'calculatePayroll'])->name('payroll.calculate');
    Route::post('/payroll/send-to-finance', [HRController::class, 'sendPayrollToFinance'])->name('payroll.send-to-finance');
    Route::get('/payroll/report/{employee}/{pay_period}', [HRController::class, 'payrollReport'])->name('payroll.report');
    Route::post('/payroll/report/{employee}/{pay_period}/submit', [HRController::class, 'submitPayrollEmployee'])->name('payroll.report.submit');

    Route::get('/shifts', [HRController::class, 'shifts'])->name('shifts');
    Route::get('/driver-shifts', [HRController::class, 'driverShifts'])->name('driver-shifts');
    Route::get('/overtime', [HRController::class, 'overtime'])->name('overtime');

    Route::get('/performance-reviews', [HRController::class, 'performanceReviews'])->name('performance-reviews');
    Route::post('/performance-reviews', [HRController::class, 'createPerformanceReview'])->name('performance.reviews.create');

    Route::get('/recruiting', [HRController::class, 'recruiting'])->name('recruiting');

    Route::get('/skills', [HRController::class, 'skills'])->name('skills');
    Route::post('/skills', [HRController::class, 'createSkill'])->name('skills.create');
    Route::put('/skills/{skill}', [HRController::class, 'updateSkill'])->name('skills.update');
    Route::delete('/skills/{skill}', [HRController::class, 'deleteSkill'])->name('skills.delete');
});

// IT Dashboard - Full System Management
Route::prefix('it')->name('it.')->middleware('dashboard.role:it')->group(function () {
    Route::get('/', [ITController::class, 'index'])->name('index');
    Route::get('/system-health', [ITController::class, 'systemHealth'])->name('system-health');
    Route::put('/services/{service}/status', [ITController::class, 'updateServiceStatus'])->name('services.update-status');

    Route::get('/logs', [ITController::class, 'systemLogs'])->name('logs');
    Route::get('/api-errors', [ITController::class, 'apiErrors'])->name('api-errors');
    Route::get('/database', [ITController::class, 'databasePerformance'])->name('database');

    Route::get('/backups', [ITController::class, 'databaseBackups'])->name('backups');
    Route::post('/backups', [ITController::class, 'triggerBackup'])->name('backups.create');
});

Route::prefix('supervisor')->name('supervisor.')->middleware('dashboard.role:delivery_supervisor')->group(function () {
    Route::get('/', [DriverSupervisorController::class, 'index'])->name('index');
    Route::get('/live-tracking', [DriverSupervisorController::class, 'liveTracking'])->name('live-tracking');
    Route::get('/api/driver-locations', [DriverSupervisorController::class, 'getDriverLocations'])->name('api.driver-locations');
    Route::get('/drivers', [DriverSupervisorController::class, 'drivers'])->name('drivers');
    Route::get('/drivers/create', [DriverSupervisorController::class, 'createDriver'])->name('drivers.create');
    Route::post('/drivers', [DriverSupervisorController::class, 'storeDriver'])->name('drivers.store');
    Route::get('/drivers/{driver}/edit', [DriverSupervisorController::class, 'editDriver'])->name('drivers.edit');
    Route::put('/drivers/{driver}', [DriverSupervisorController::class, 'updateDriver'])->name('drivers.update');
    Route::delete('/drivers/{driver}', [DriverSupervisorController::class, 'deleteDriver'])->name('drivers.delete');
    Route::post('/drivers/{driver}/update-status', [DriverSupervisorController::class, 'updateDriverStatus'])->name('drivers.update-status');
    Route::get('/vehicles', [DriverSupervisorController::class, 'vehicles'])->name('vehicles');
    Route::get('/vehicles/create', [DriverSupervisorController::class, 'createVehicle'])->name('vehicles.create');
    Route::post('/vehicles', [DriverSupervisorController::class, 'storeVehicle'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}/edit', [DriverSupervisorController::class, 'editVehicle'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [DriverSupervisorController::class, 'updateVehicle'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [DriverSupervisorController::class, 'deleteVehicle'])->name('vehicles.delete');

    Route::get('/order-assignment', [DriverSupervisorController::class, 'orderAssignment'])->name('order-assignment');
    Route::post('/assign-order', [DriverSupervisorController::class, 'assignOrder'])->name('assign-order');

    Route::get('/route-optimization', [DriverSupervisorController::class, 'routeOptimization'])->name('route-optimization');
    Route::post('/optimize-routes', [DriverSupervisorController::class, 'optimizeRoutes'])->name('optimize-routes');

    Route::get('/vehicle-maintenance', [DriverSupervisorController::class, 'vehicleMaintenance'])->name('vehicle-maintenance');
    Route::post('/vehicle-maintenance/log', [DriverSupervisorController::class, 'logMaintenance'])->name('vehicle-maintenance.log');

    Route::get('/delivery-proof', [DriverSupervisorController::class, 'deliveryProof'])->name('delivery-proof');
});

Route::get('/home', [MainController::class, 'index'])->name('home');
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/users', [UserController::class, 'index'])->name('users');
Route::get('/products', [ProductController::class, 'index'])->name('products');

// Review Moderation
Route::prefix('reviews')->name('reviews.')->middleware('dashboard.role:admin')->group(function () {
    Route::get('/pending', [ReviewModerationController::class, 'index'])->name('pending');
    Route::post('/{review}/approve', [ReviewModerationController::class, 'approve'])->name('approve');
    Route::post('/{review}/reject', [ReviewModerationController::class, 'reject'])->name('reject');
});
