<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home page - New Design
Route::get('/', function () {
    return view('home-new');
});

// Original home page with database
Route::get('/home-db', function () {
    return view('home');
});

// Legacy welcome page (kept for compatibility)
Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Arabic auth pages (custom static views)
Route::view('/ar-login', 'pages.ar-login');
Route::view('/ar-signup', 'pages.ar-signup');
Route::view('/ar-login-error', 'pages.ar-login-error');
Route::view('/ar-forgot-password', 'pages.ar-forgot-password');
Route::view('/ar-verify-code', 'pages.ar-verify-code');
Route::view('/ar-verify-registration', 'pages.ar-verify-registration');
Route::view('/ar-reset-password', 'pages.ar-reset-password');

// Override Laravel login (forces custom UI at /login)
Route::view('/login', 'pages.ar-login')->name('login');

// Override Laravel register (forces custom UI at /register)
Route::view('/register', 'pages.ar-signup')->name('register');

// Override Laravel forgot password
Route::view('/forgot-password', 'pages.ar-forgot-password')->name('password.request');

// Auth API routes
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;

Route::middleware(['web'])->group(function () {
    Route::post('/api/register', [CustomAuthController::class, 'register']);
    Route::post('/api/verify-registration', [CustomAuthController::class, 'verifyRegistration']);
    Route::post('/api/login', [CustomAuthController::class, 'login']);
    Route::post('/api/forgot-password', [CustomAuthController::class, 'forgotPassword']);
    Route::post('/api/verify-code', [CustomAuthController::class, 'verifyCode']);
    Route::post('/api/reset-password', [CustomAuthController::class, 'resetPassword']);
    Route::post('/api/logout', [CustomAuthController::class, 'logout'])->middleware('auth');
    
    // Google OAuth
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
    
    // Product & Category API routes
    Route::get('/api/products/search', [ProductController::class, 'search']);
    Route::get('/api/products', [ProductController::class, 'index']);
    Route::get('/api/categories', [CategoryController::class, 'index']);
    
    // Public homepage packages API
    Route::get('/api/homepage/packages', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'getPackages']);
    
    // Package products page
    Route::get('/package/{packageId}', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'showPackagePage'])->name('package.show');
    
    // Cart API routes
    Route::get('/api/cart', [CartController::class, 'index']);
    Route::post('/api/cart/add', [CartController::class, 'add']);
    Route::post('/api/cart/update', [CartController::class, 'update']);
    Route::post('/api/cart/remove', [CartController::class, 'remove']);
    Route::post('/api/cart/clear', [CartController::class, 'clear']);
    Route::get('/api/cart/items', [CartController::class, 'getItems']);
    
    // Order API routes
    Route::post('/api/orders/create', [\App\Http\Controllers\OrderController::class, 'create']);
    Route::post('/api/orders/{id}/upload-receipt', [\App\Http\Controllers\OrderController::class, 'uploadReceipt']);
    Route::get('/api/user/profile', function() {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'name' => $user->name ?? $user->user_full_name,
                'phone' => $user->phone ?? $user->mobile,
                'email' => $user->email
            ]);
        }
        return response()->json(['error' => 'Not authenticated'], 401);
    });
    
    // Saved cards API (mock data for now)
    Route::get('/api/user/saved-cards', function() {
        if (Auth::check()) {
            // Return mock saved cards - in production, fetch from database
            return response()->json([
                [
                    'id' => '1',
                    'last4' => '4242',
                    'expiry' => '12/25',
                    'brand' => 'Visa'
                ],
                [
                    'id' => '2',
                    'last4' => '5555',
                    'expiry' => '08/26',
                    'brand' => 'Mastercard'
                ]
            ]);
        }
        return response()->json([], 401);
    });
});

// Category page route
Route::get('/category/{slug}', [ProductController::class, 'byCategory'])->name('category.show');

// Product details page route
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

// Favorites page route
Route::get('/favorites', function () {
    return view('favorites');
})->name('favorites');

// Cart page route
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

// Store page route
Route::get('/store', function () {
    return view('store');
})->name('store');

// Checkout page route (requires authentication)
Route::get('/checkout', function () {
    return view('checkout');
})->middleware('auth')->name('checkout');

// Order confirmation page
Route::get('/order-confirmation/{id}', [\App\Http\Controllers\OrderController::class, 'show'])->name('order.confirmation');

// My Orders page (requires authentication)
Route::get('/my-orders', [\App\Http\Controllers\OrderController::class, 'myOrders'])->name('my.orders');

// Invoice routes
Route::get('/order/{id}/invoice', [\App\Http\Controllers\OrderController::class, 'viewInvoice'])->name('order.invoice');
Route::get('/order/{id}/invoice/download', [\App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('order.invoice.download');

// Admin Dashboard routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [\App\Http\Controllers\Admin\DashboardController::class, 'analytics'])->name('analytics');
    
    // Homepage Management
    Route::get('/homepage', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'index'])->name('homepage');
    Route::view('/homepage/manage', 'admin.homepage')->name('homepage.manage');
    
    // Order Management
    Route::resource('orders', \App\Http\Controllers\Admin\OrderManagementController::class)->only(['index', 'show']);
    Route::post('/orders/{order}/update-status', [\App\Http\Controllers\Admin\OrderManagementController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/update-payment-status', [\App\Http\Controllers\Admin\OrderManagementController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::post('/orders/{order}/add-note', [\App\Http\Controllers\Admin\OrderManagementController::class, 'addNote'])->name('orders.add-note');
    
    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryManagementController::class);
    
    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductManagementController::class);
    Route::post('/products/{product}/toggle-featured', [\App\Http\Controllers\Admin\ProductManagementController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::post('/products/{product}/toggle-active', [\App\Http\Controllers\Admin\ProductManagementController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('/products/bulk-action', [\App\Http\Controllers\Admin\ProductManagementController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('/products/export/csv', [\App\Http\Controllers\Admin\ProductManagementController::class, 'export'])->name('products.export');
    Route::post('/products/{product}/quick-update', [\App\Http\Controllers\Admin\ProductManagementController::class, 'quickUpdate'])->name('products.quick-update');
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class)->only(['index', 'show', 'destroy']);
    Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRole'])->name('users.update-role');
});

// Notifications routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::get('/api/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount']);
});

// Chat routes
Route::middleware('auth')->group(function () {
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::get('/api/chat/unread-count', [\App\Http\Controllers\ChatController::class, 'getUnreadCount']);
    Route::get('/api/chat/messages/{userId}', [\App\Http\Controllers\ChatController::class, 'getMessages']);
    Route::post('/api/chat/broadcast', [\App\Http\Controllers\ChatController::class, 'broadcast'])->name('chat.broadcast');
    Route::get('/api/chat/users-by-role', [\App\Http\Controllers\ChatController::class, 'getUsersByRole']);
});

// Reports routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportsController::class, 'export'])->name('reports.export');
});

// IT Dashboard routes
Route::middleware(['auth'])->prefix('it')->name('it.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\IT\ITDashboardController::class, 'index'])->name('dashboard');
    Route::post('/clear-cache', [\App\Http\Controllers\IT\ITDashboardController::class, 'clearCache'])->name('clear-cache');
    Route::post('/update-system', [\App\Http\Controllers\IT\ITDashboardController::class, 'updateSystem'])->name('update-system');
    Route::post('/check-database', [\App\Http\Controllers\IT\ITDashboardController::class, 'checkDatabase'])->name('check-database');
    Route::post('/create-backup', [\App\Http\Controllers\IT\ITDashboardController::class, 'createBackup'])->name('create-backup');
    Route::post('/optimize-performance', [\App\Http\Controllers\IT\ITDashboardController::class, 'optimizePerformance'])->name('optimize-performance');
    Route::post('/security-scan', [\App\Http\Controllers\IT\ITDashboardController::class, 'securityScan'])->name('security-scan');
    Route::post('/execute-command', [\App\Http\Controllers\IT\ITDashboardController::class, 'executeCommand'])->name('execute-command');
    Route::get('/live-logs', [\App\Http\Controllers\IT\ITDashboardController::class, 'getLiveLogs'])->name('live-logs');
});

// Customer Service Dashboard routes
Route::middleware(['auth'])->prefix('cs')->name('cs.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CS\CustomerServiceController::class, 'index'])->name('dashboard');
    
    // Tickets Management
    Route::get('/tickets', [\App\Http\Controllers\CS\CustomerServiceController::class, 'tickets'])->name('tickets.index');
    Route::get('/tickets/create', [\App\Http\Controllers\CS\CustomerServiceController::class, 'createTicket'])->name('tickets.create');
    Route::post('/tickets', [\App\Http\Controllers\CS\CustomerServiceController::class, 'storeTicket'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\CS\CustomerServiceController::class, 'showTicket'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [\App\Http\Controllers\CS\CustomerServiceController::class, 'editTicket'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [\App\Http\Controllers\CS\CustomerServiceController::class, 'updateTicket'])->name('tickets.update');
    Route::post('/tickets/{ticket}/assign', [\App\Http\Controllers\CS\CustomerServiceController::class, 'assignTicket'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/status', [\App\Http\Controllers\CS\CustomerServiceController::class, 'updateTicketStatus'])->name('tickets.status');
    Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\CS\CustomerServiceController::class, 'replyToTicket'])->name('tickets.reply');
    
    // Customer Feedback
    Route::get('/feedback', [\App\Http\Controllers\CS\CustomerServiceController::class, 'feedback'])->name('feedback.index');
    Route::get('/feedback/{feedback}', [\App\Http\Controllers\CS\CustomerServiceController::class, 'showFeedback'])->name('feedback.show');
    Route::post('/feedback/{feedback}/respond', [\App\Http\Controllers\CS\CustomerServiceController::class, 'respondToFeedback'])->name('feedback.respond');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\CS\CustomerServiceController::class, 'reports'])->name('reports');
});

// Accounting Dashboard routes (Full الأمين-style system)
Route::middleware(['auth'])->prefix('accounting')->name('accounting.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Accounting\AccountingController::class, 'index'])->name('dashboard');
    
    // Chart of Accounts
    Route::get('/chart-of-accounts', [\App\Http\Controllers\Accounting\AccountingController::class, 'chartOfAccounts'])->name('chart-of-accounts');
    Route::get('/accounts/tree', [\App\Http\Controllers\Accounting\AccountingController::class, 'accountsTree'])->name('accounts.tree');
    Route::get('/accounts/create', [\App\Http\Controllers\Accounting\AccountingController::class, 'createAccount'])->name('accounts.create');
    Route::get('/accounts', [\App\Http\Controllers\Accounting\AccountingController::class, 'getAccounts'])->name('accounts');
    Route::post('/accounts', [\App\Http\Controllers\Accounting\AccountingController::class, 'storeAccount'])->name('accounts.store');
    Route::put('/accounts/{id}', [\App\Http\Controllers\Accounting\AccountingController::class, 'updateAccount'])->name('accounts.update');
    
    // Journal Entries
    Route::get('/journal-entries', [\App\Http\Controllers\Accounting\AccountingController::class, 'journalEntries'])->name('journal-entries');
    Route::get('/journal-entries/create', [\App\Http\Controllers\Accounting\AccountingController::class, 'createJournalEntry'])->name('journal-entries.create');
    Route::get('/journal-entries/adjustments', [\App\Http\Controllers\Accounting\AccountingController::class, 'adjustmentEntries'])->name('journal-entries.adjustments');
    Route::post('/journal-entries', [\App\Http\Controllers\Accounting\AccountingController::class, 'storeJournalEntry'])->name('journal-entries.store');
    Route::post('/journal-entries/{id}/post', [\App\Http\Controllers\Accounting\AccountingController::class, 'postJournalEntry'])->name('journal-entries.post');
    Route::post('/journal-entries/{id}/reverse', [\App\Http\Controllers\Accounting\AccountingController::class, 'reverseJournalEntry'])->name('journal-entries.reverse');
    
    // Financial Reports
    Route::get('/trial-balance', [\App\Http\Controllers\Accounting\AccountingController::class, 'trialBalance'])->name('trial-balance');
    Route::get('/balance-sheet', [\App\Http\Controllers\Accounting\AccountingController::class, 'balanceSheet'])->name('balance-sheet');
    Route::get('/income-statement', [\App\Http\Controllers\Accounting\AccountingController::class, 'incomeStatement'])->name('income-statement');
    Route::get('/cash-flow', [\App\Http\Controllers\Accounting\AccountingController::class, 'cashFlow'])->name('cash-flow');
    Route::get('/general-ledger', [\App\Http\Controllers\Accounting\AccountingController::class, 'generalLedger'])->name('general-ledger');
    
    // Calculators
    Route::get('/calculators/depreciation', [\App\Http\Controllers\Accounting\AccountingController::class, 'depreciationCalculator'])->name('calculators.depreciation');
    Route::get('/calculators/loan', [\App\Http\Controllers\Accounting\AccountingController::class, 'loanCalculator'])->name('calculators.loan');
    Route::get('/calculators/vat', [\App\Http\Controllers\Accounting\AccountingController::class, 'vatCalculator'])->name('calculators.vat');
    Route::get('/calculators/profit-margin', [\App\Http\Controllers\Accounting\AccountingController::class, 'profitMarginCalculator'])->name('calculators.profit-margin');
    Route::get('/calculators/break-even', [\App\Http\Controllers\Accounting\AccountingController::class, 'breakEvenCalculator'])->name('calculators.break-even');
    Route::post('/calculator', [\App\Http\Controllers\Accounting\AccountingController::class, 'calculator'])->name('calculator');
    
    // Other Modules
    Route::get('/invoices', [\App\Http\Controllers\Accounting\AccountingController::class, 'invoices'])->name('invoices');
    Route::get('/receivables', [\App\Http\Controllers\Accounting\AccountingController::class, 'receivables'])->name('receivables');
    Route::get('/payables', [\App\Http\Controllers\Accounting\AccountingController::class, 'payables'])->name('payables');
    Route::get('/inventory', [\App\Http\Controllers\Accounting\AccountingController::class, 'inventory'])->name('inventory');
    Route::get('/fixed-assets', [\App\Http\Controllers\Accounting\AccountingController::class, 'fixedAssets'])->name('fixed-assets');
    Route::get('/payroll', [\App\Http\Controllers\Accounting\AccountingController::class, 'payroll'])->name('payroll');
    Route::get('/settings', [\App\Http\Controllers\Accounting\AccountingController::class, 'settings'])->name('settings');
    
    // Tools
    Route::post('/quick-entry', [\App\Http\Controllers\Accounting\AccountingController::class, 'quickEntry'])->name('quick-entry');
    Route::get('/export', [\App\Http\Controllers\Accounting\AccountingController::class, 'exportReport'])->name('export');
    
    // Additional Actions
    Route::post('/accounts/{id}/toggle', [\App\Http\Controllers\Accounting\AccountingController::class, 'toggleAccount'])->name('accounts.toggle');
    Route::delete('/journal-entries/{id}', [\App\Http\Controllers\Accounting\AccountingController::class, 'deleteJournalEntry'])->name('journal-entries.delete');
    Route::post('/bulk-action', [\App\Http\Controllers\Accounting\AccountingController::class, 'bulkAction'])->name('bulk-action');
    Route::post('/settings/save', [\App\Http\Controllers\Accounting\AccountingController::class, 'saveSettings'])->name('settings.save');
    Route::post('/settings/backup', [\App\Http\Controllers\Accounting\AccountingController::class, 'createBackup'])->name('settings.backup');
    Route::post('/invoices/{id}/send-email', [\App\Http\Controllers\Accounting\AccountingController::class, 'sendInvoiceEmail'])->name('invoices.send-email');
    Route::post('/payroll/{id}/process', [\App\Http\Controllers\Accounting\AccountingController::class, 'processPayroll'])->name('payroll.process');
    Route::post('/fixed-assets/{id}/depreciation', [\App\Http\Controllers\Accounting\AccountingController::class, 'calculateAssetDepreciation'])->name('fixed-assets.depreciation');
});

// Test orders page
Route::get('/test-orders', function() {
    $orders = \App\Models\Order::with('items.product')->paginate(10);
    return view('test-orders', compact('orders'));
});

// Test permissions page
Route::get('/test-permissions', function() {
    return view('test-permissions');
})->middleware('auth');

// Test database connectivity
Route::get('/test-database', function() {
    return view('test-database');
});

// Create test orders for driver supervisor
Route::get('/create-test-orders', function() {
    $user = \App\Models\User::first();
    if (!$user) {
        return 'No users found. Please create a user first.';
    }
    
    $orders = [
        [
            'order_number' => 'ORD-TEST-' . rand(1000, 9999),
            'user_id' => $user->id,
            'recipient_name' => 'أحمد محمود',
            'phone' => '0912345678',
            'village' => 'دمشق - المزة',
            'address_note' => 'بناء رقم 5، الطابق الثالث',
            'latitude' => 33.5138,
            'longitude' => 36.2765,
            'delivery_method' => 'home_delivery',
            'payment_method' => 'cash',
            'status' => 'confirmed',
            'payment_status' => 'pending',
            'subtotal' => 50.00,
            'delivery_cost' => 5.00,
            'service_fee' => 2.50,
            'total' => 57.50,
        ],
        [
            'order_number' => 'ORD-TEST-' . rand(1000, 9999),
            'user_id' => $user->id,
            'recipient_name' => 'فاطمة علي',
            'phone' => '0923456789',
            'village' => 'حلب - الشهباء',
            'address_note' => 'شارع الجامعة، بناء 12',
            'latitude' => 36.2021,
            'longitude' => 37.1343,
            'delivery_method' => 'home_delivery',
            'payment_method' => 'card',
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'subtotal' => 100.00,
            'delivery_cost' => 10.00,
            'service_fee' => 5.00,
            'total' => 115.00,
        ],
        [
            'order_number' => 'ORD-TEST-' . rand(1000, 9999),
            'user_id' => $user->id,
            'recipient_name' => 'محمد حسن',
            'phone' => '0934567890',
            'village' => 'حمص - الوعر',
            'address_note' => 'قرب المسجد الكبير',
            'latitude' => 34.7324,
            'longitude' => 36.7137,
            'delivery_method' => 'home_delivery',
            'payment_method' => 'cash',
            'status' => 'pending',
            'payment_status' => 'pending',
            'subtotal' => 75.00,
            'delivery_cost' => 7.00,
            'service_fee' => 3.00,
            'total' => 85.00,
        ],
    ];
    
    $created = [];
    foreach ($orders as $orderData) {
        $order = \App\Models\Order::create($orderData);
        $created[] = $order->order_number;
        
        // Create order item
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => 1,
            'product_name' => 'منتج تجريبي',
            'quantity' => 2,
            'price' => 25.00,
            'subtotal' => 50.00,
        ]);
    }
    
    // Create test drivers using Driver model
    $driversData = [
        ['name' => 'أحمد السائق', 'phone' => '0911111111', 'email' => 'driver1@test.com', 'license_number' => 'LIC001'],
        ['name' => 'محمد السائق', 'phone' => '0922222222', 'email' => 'driver2@test.com', 'license_number' => 'LIC002'],
    ];
    
    foreach ($driversData as $driverData) {
        $existing = \App\Models\Driver::where('email', $driverData['email'])->first();
        if (!$existing) {
            \App\Models\Driver::create([
                'name' => $driverData['name'],
                'phone' => $driverData['phone'],
                'email' => $driverData['email'],
                'license_number' => $driverData['license_number'],
                'vehicle_type' => 'motorcycle',
                'vehicle_plate' => 'SYR-' . rand(1000, 9999),
                'status' => 'available',
                'is_active' => true,
                'rating' => 5.00,
                'total_deliveries' => 0,
            ]);
        }
    }
    
    return '✅ تم إنشاء ' . count($created) . ' طلبات تجريبية: ' . implode(', ', $created) . '<br><br><a href="/delivery/supervisor/dashboard" style="background:#ff6b35;color:white;padding:1rem 2rem;border-radius:8px;text-decoration:none;font-weight:bold;">انتقل إلى لوحة التحكم</a>';
});

// HR Dashboard routes
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\HR\HRController::class, 'index'])->name('dashboard');
    
    // Employee Management
    Route::get('/employees', [\App\Http\Controllers\HR\HRController::class, 'employees'])->name('employees');
    Route::get('/employees/create', [\App\Http\Controllers\HR\HRController::class, 'createEmployee'])->name('employees.create');
    Route::post('/employees', [\App\Http\Controllers\HR\HRController::class, 'storeEmployee'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [\App\Http\Controllers\HR\HRController::class, 'editEmployee'])->name('employees.edit');
    Route::put('/employees/{employee}', [\App\Http\Controllers\HR\HRController::class, 'updateEmployee'])->name('employees.update');
    
    // Attendance Management
    Route::get('/attendance', [\App\Http\Controllers\HR\HRController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/mark', [\App\Http\Controllers\HR\HRController::class, 'markAttendance'])->name('attendance.mark');
    
    // Leave Management
    Route::get('/leaves', [\App\Http\Controllers\HR\HRController::class, 'leaveRequests'])->name('leaves');
    Route::post('/leaves/{leave}/approve', [\App\Http\Controllers\HR\HRController::class, 'approveLeave'])->name('leaves.approve');
    Route::post('/leaves/{leave}/reject', [\App\Http\Controllers\HR\HRController::class, 'rejectLeave'])->name('leaves.reject');
    
    // Payroll Management
    Route::get('/payroll', [\App\Http\Controllers\HR\HRController::class, 'payroll'])->name('payroll');
    Route::post('/payroll/generate', [\App\Http\Controllers\HR\HRController::class, 'generatePayroll'])->name('payroll.generate');
    Route::post('/payroll/{payroll}/process', [\App\Http\Controllers\HR\HRController::class, 'processPayroll'])->name('payroll.process');
    
    // Performance Reviews
    Route::get('/performance', [\App\Http\Controllers\HR\HRController::class, 'performanceReviews'])->name('performance');
    Route::get('/performance/create', [\App\Http\Controllers\HR\HRController::class, 'createReview'])->name('performance.create');
    Route::post('/performance', [\App\Http\Controllers\HR\HRController::class, 'storeReview'])->name('performance.store');
    
    // Training Programs
    Route::get('/training', [\App\Http\Controllers\HR\HRController::class, 'trainingPrograms'])->name('training');
    Route::get('/training/create', [\App\Http\Controllers\HR\HRController::class, 'createTraining'])->name('training.create');
    Route::post('/training', [\App\Http\Controllers\HR\HRController::class, 'storeTraining'])->name('training.store');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\HR\HRController::class, 'reports'])->name('reports');
    Route::get('/reports/attendance', [\App\Http\Controllers\HR\HRController::class, 'attendanceReport'])->name('reports.attendance');
});

// Delivery Supervisor Dashboard routes
Route::middleware(['auth'])->prefix('delivery/supervisor')->name('delivery.supervisor.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'index'])->name('dashboard');
    Route::get('/locations', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'getDriverLocations'])->name('locations');
    Route::post('/drivers/{driver}/location', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'updateDriverLocation'])->name('driver.location');
    Route::post('/assign-driver', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'assignDriver'])->name('assign');
    Route::post('/assignments/{assignment}/status', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'updateDeliveryStatus'])->name('assignment.status');
    Route::get('/drivers/{driver}/history', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'getDriverHistory'])->name('driver.history');
    
    // Driver Management
    Route::get('/manage-drivers', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'manageDrivers'])->name('manage-drivers');
    Route::post('/drivers', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'storeDriver'])->name('drivers.store');
    Route::put('/drivers/{driver}', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'updateDriver'])->name('drivers.update');
    Route::delete('/drivers/{driver}', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'deleteDriver'])->name('drivers.delete');
    Route::post('/drivers/{driver}/toggle', [\App\Http\Controllers\Delivery\DeliverySupervisorController::class, 'toggleDriverStatus'])->name('drivers.toggle');
});

// Driver Tracking Page (for drivers to track themselves)
Route::get('/driver/tracking', [\App\Http\Controllers\DriverTrackingController::class, 'index'])->name('driver.tracking');

// Mobile Driver API routes (for GPS tracking from phones)
Route::prefix('api/driver')->name('api.driver.')->group(function () {
    Route::post('/location/update', [\App\Http\Controllers\Api\DriverLocationController::class, 'updateLocation'])->name('location.update');
    Route::post('/location/batch', [\App\Http\Controllers\Api\DriverLocationController::class, 'batchUpdateLocation'])->name('location.batch');
    Route::post('/status/update', [\App\Http\Controllers\Api\DriverLocationController::class, 'updateStatus'])->name('status.update');
    Route::post('/info', [\App\Http\Controllers\Api\DriverLocationController::class, 'getDriverInfo'])->name('info');
});

// Driver Supervisor Order Management routes
Route::middleware(['auth'])->prefix('driver-supervisor')->name('driver-supervisor.')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\DriverSupervisor\OrderManagementController::class, 'index'])->name('orders');
});

// API routes for driver supervisor (without auth middleware for easier testing)
Route::prefix('api/driver-supervisor')->group(function () {
    Route::get('/orders/{order}', [\App\Http\Controllers\DriverSupervisor\OrderManagementController::class, 'getOrderDetails']);
    Route::post('/orders/{order}/assign', [\App\Http\Controllers\DriverSupervisor\OrderManagementController::class, 'assignDriver']);
});

// Customer Order Confirmation (public link)
Route::get('/order/confirm/{order}/{token}', [\App\Http\Controllers\OrderConfirmationController::class, 'show'])->name('order.confirm');
Route::post('/order/confirm/{order}/{token}', [\App\Http\Controllers\OrderConfirmationController::class, 'confirm'])->name('order.confirm.submit');

// Homepage Management API (Admin only)
Route::middleware(['auth'])->prefix('api/admin/homepage')->group(function () {
    Route::get('/sections', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'getSections']);
    Route::post('/sections', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'updateSections']);
    Route::post('/sections/{sectionId}/toggle', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'toggleSection']);
    Route::post('/sections/reorder', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'reorderSections']);
    Route::get('/lightning-deals', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'getLightningDeals']);
    Route::post('/lightning-deals', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'updateLightningDeals']);
    
    // Featured products management
    Route::get('/featured/{type}', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'getFeaturedProducts']);
    Route::post('/featured/{type}', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'saveFeaturedProducts']);
    Route::get('/featured-counts', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'getFeaturedCounts']);
    
    // Packages management
    Route::get('/packages', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'getPackages']);
    Route::post('/packages', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'savePackages']);
    Route::post('/packages/add', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'addPackage']);
    Route::put('/packages/{packageId}', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'updatePackage']);
    Route::delete('/packages/{packageId}', [\App\Http\Controllers\Admin\HomepageManagementController::class, 'deletePackage']);
});

require __DIR__.'/auth.php';
