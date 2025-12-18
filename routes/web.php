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
})->name('home');

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
    
    // Public homepage packages API (Legacy)
    Route::get('/api/homepage/packages', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'getPackages']);
    
    // Package products page (Legacy)
    Route::get('/package/{packageId}', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'showPackagePage'])->name('package.show');
    
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

// Legacy Admin Dashboard routes (kept for backward compatibility - use /dashboard/admin for new system)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Homepage Management (Legacy)
    Route::get('/homepage', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'index'])->name('homepage');
    Route::view('/homepage/manage', 'admin.homepage')->name('homepage.manage');
    
    // Order Management (Legacy)
    Route::resource('orders', \App\Http\Controllers\Legacy\Admin\OrderManagementController::class)->only(['index', 'show']);
    Route::post('/orders/{order}/update-status', [\App\Http\Controllers\Legacy\Admin\OrderManagementController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/update-payment-status', [\App\Http\Controllers\Legacy\Admin\OrderManagementController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::post('/orders/{order}/add-note', [\App\Http\Controllers\Legacy\Admin\OrderManagementController::class, 'addNote'])->name('orders.add-note');
    
    // Category Management (Legacy)
    Route::resource('categories', \App\Http\Controllers\Legacy\Admin\CategoryManagementController::class);
    
    // Product Management (Legacy)
    Route::resource('products', \App\Http\Controllers\Legacy\Admin\ProductManagementController::class);
    Route::post('/products/{product}/toggle-featured', [\App\Http\Controllers\Legacy\Admin\ProductManagementController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::post('/products/{product}/toggle-active', [\App\Http\Controllers\Legacy\Admin\ProductManagementController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('/products/bulk-action', [\App\Http\Controllers\Legacy\Admin\ProductManagementController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('/products/export/csv', [\App\Http\Controllers\Legacy\Admin\ProductManagementController::class, 'export'])->name('products.export');
    Route::post('/products/{product}/quick-update', [\App\Http\Controllers\Legacy\Admin\ProductManagementController::class, 'quickUpdate'])->name('products.quick-update');
    
    // User Management (Legacy)
    Route::resource('users', \App\Http\Controllers\Legacy\Admin\UserManagementController::class)->only(['index', 'show', 'destroy']);
    Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\Legacy\Admin\UserManagementController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::put('/users/{user}/role', [\App\Http\Controllers\Legacy\Admin\UserManagementController::class, 'updateRole'])->name('users.update-role');
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

// Legacy Reports routes (kept for backward compatibility)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [\App\Http\Controllers\Legacy\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Legacy\Admin\ReportsController::class, 'export'])->name('reports.export');
});

/*
|--------------------------------------------------------------------------
| Legacy IT Dashboard routes (DEPRECATED - use /dashboard/it for new system)
|--------------------------------------------------------------------------
| These routes are kept for backward compatibility but should be migrated
| to the new dashboard system at /dashboard/it
*/
// Route::middleware(['auth'])->prefix('it')->name('it.legacy.')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'index'])->name('dashboard');
//     Route::post('/clear-cache', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'clearCache'])->name('clear-cache');
//     Route::post('/update-system', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'updateSystem'])->name('update-system');
//     Route::post('/check-database', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'checkDatabase'])->name('check-database');
//     Route::post('/create-backup', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'createBackup'])->name('create-backup');
//     Route::post('/optimize-performance', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'optimizePerformance'])->name('optimize-performance');
//     Route::post('/security-scan', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'securityScan'])->name('security-scan');
//     Route::post('/execute-command', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'executeCommand'])->name('execute-command');
//     Route::get('/live-logs', [\App\Http\Controllers\Legacy\IT\ITDashboardController::class, 'getLiveLogs'])->name('live-logs');
// });

/*
|--------------------------------------------------------------------------
| Legacy Customer Service Dashboard routes (DEPRECATED - use /dashboard/cs for new system)
|--------------------------------------------------------------------------
| These routes are kept for backward compatibility but should be migrated
| to the new dashboard system at /dashboard/cs
*/
// Route::middleware(['auth'])->prefix('cs')->name('cs.legacy.')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'index'])->name('dashboard');
//     
//     // Tickets Management
//     Route::get('/tickets', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'tickets'])->name('tickets.index');
//     Route::get('/tickets/create', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'createTicket'])->name('tickets.create');
//     Route::post('/tickets', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'storeTicket'])->name('tickets.store');
//     Route::get('/tickets/{ticket}', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'showTicket'])->name('tickets.show');
//     Route::get('/tickets/{ticket}/edit', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'editTicket'])->name('tickets.edit');
//     Route::put('/tickets/{ticket}', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'updateTicket'])->name('tickets.update');
//     Route::post('/tickets/{ticket}/assign', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'assignTicket'])->name('tickets.assign');
//     Route::post('/tickets/{ticket}/status', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'updateTicketStatus'])->name('tickets.status');
//     Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'replyToTicket'])->name('tickets.reply');
//     
//     // Customer Feedback
//     Route::get('/feedback', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'feedback'])->name('feedback.index');
//     Route::get('/feedback/{feedback}', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'showFeedback'])->name('feedback.show');
//     Route::post('/feedback/{feedback}/respond', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'respondToFeedback'])->name('feedback.respond');
//     
//     // Reports
//     Route::get('/reports', [\App\Http\Controllers\Legacy\CS\CustomerServiceController::class, 'reports'])->name('reports');
// });

/*
|--------------------------------------------------------------------------
| Legacy Accounting Dashboard routes (DEPRECATED - use /dashboard/finance for new system)
|--------------------------------------------------------------------------
| These routes are kept for backward compatibility but should be migrated
| to the new dashboard system at /dashboard/finance
*/
// Route::middleware(['auth'])->prefix('accounting')->name('accounting.legacy.')->group(function () {
//     // Dashboard
//     Route::get('/dashboard', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'index'])->name('dashboard');
//     
//     // Chart of Accounts
//     Route::get('/chart-of-accounts', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'chartOfAccounts'])->name('chart-of-accounts');
//     Route::get('/accounts/tree', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'accountsTree'])->name('accounts.tree');
//     Route::get('/accounts/create', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'createAccount'])->name('accounts.create');
//     Route::get('/accounts', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'getAccounts'])->name('accounts');
//     Route::post('/accounts', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'storeAccount'])->name('accounts.store');
//     Route::put('/accounts/{id}', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'updateAccount'])->name('accounts.update');
//     
//     // Journal Entries
//     Route::get('/journal-entries', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'journalEntries'])->name('journal-entries');
//     Route::get('/journal-entries/create', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'createJournalEntry'])->name('journal-entries.create');
//     Route::get('/journal-entries/adjustments', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'adjustmentEntries'])->name('journal-entries.adjustments');
//     Route::post('/journal-entries', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'storeJournalEntry'])->name('journal-entries.store');
//     Route::post('/journal-entries/{id}/post', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'postJournalEntry'])->name('journal-entries.post');
//     Route::post('/journal-entries/{id}/reverse', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'reverseJournalEntry'])->name('journal-entries.reverse');
//     
//     // Financial Reports
//     Route::get('/trial-balance', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'trialBalance'])->name('trial-balance');
//     Route::get('/balance-sheet', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'balanceSheet'])->name('balance-sheet');
//     Route::get('/income-statement', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'incomeStatement'])->name('income-statement');
//     Route::get('/cash-flow', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'cashFlow'])->name('cash-flow');
//     Route::get('/general-ledger', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'generalLedger'])->name('general-ledger');
//     
//     // Calculators
//     Route::get('/calculators/depreciation', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'depreciationCalculator'])->name('calculators.depreciation');
//     Route::get('/calculators/loan', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'loanCalculator'])->name('calculators.loan');
//     Route::get('/calculators/vat', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'vatCalculator'])->name('calculators.vat');
//     Route::get('/calculators/profit-margin', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'profitMarginCalculator'])->name('calculators.profit-margin');
//     Route::get('/calculators/break-even', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'breakEvenCalculator'])->name('calculators.break-even');
//     Route::post('/calculator', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'calculator'])->name('calculator');
//     
//     // Other Modules
//     Route::get('/invoices', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'invoices'])->name('invoices');
//     Route::get('/receivables', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'receivables'])->name('receivables');
//     Route::get('/payables', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'payables'])->name('payables');
//     Route::get('/inventory', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'inventory'])->name('inventory');
//     Route::get('/fixed-assets', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'fixedAssets'])->name('fixed-assets');
//     Route::get('/payroll', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'payroll'])->name('payroll');
//     Route::get('/settings', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'settings'])->name('settings');
//     
//     // Tools
//     Route::post('/quick-entry', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'quickEntry'])->name('quick-entry');
//     Route::get('/export', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'exportReport'])->name('export');
//     
//     // Additional Actions
//     Route::post('/accounts/{id}/toggle', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'toggleAccount'])->name('accounts.toggle');
//     Route::delete('/journal-entries/{id}', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'deleteJournalEntry'])->name('journal-entries.delete');
//     Route::post('/bulk-action', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'bulkAction'])->name('bulk-action');
//     Route::post('/settings/save', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'saveSettings'])->name('settings.save');
//     Route::post('/settings/backup', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'createBackup'])->name('settings.backup');
//     Route::post('/invoices/{id}/send-email', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'sendInvoiceEmail'])->name('invoices.send-email');
//     Route::post('/payroll/{id}/process', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'processPayroll'])->name('payroll.process');
//     Route::post('/fixed-assets/{id}/depreciation', [\App\Http\Controllers\Legacy\Accounting\AccountingController::class, 'calculateAssetDepreciation'])->name('fixed-assets.depreciation');
// });

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

// Test login route - automatically logs in as admin for testing
Route::get('/test-login', function() {
    $user = \App\Models\User::where('email', 'admin@test.com')->first();
    
    if (!$user) {
        // Create a test admin user
        $user = \App\Models\User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'is_it' => true,
            'is_hr' => true,
            'is_finance' => true,
            'is_driver_supervisor' => true,
            'is_trader' => true,
        ]);
    }
    
    // Log in the user
    Auth::login($user);
    
    return redirect('/dashboard/admin')->with('success', 'Logged in as test admin! You can now access all dashboards.');
});

// Debug route to check authentication status
Route::get('/test-auth', function() {
    $user = Auth::user();
    if ($user) {
        return response()->json([
            'authenticated' => true,
            'user' => $user->toArray(),
            'dashboard_links' => [
                'admin' => route('dashboard.admin.index'),
                'analytics' => route('dashboard.admin.analytics'),
            ]
        ]);
    } else {
        return response()->json([
            'authenticated' => false,
            'message' => 'Not logged in'
        ]);
    }
});

// Very basic test route
Route::get('/test-basic', function() {
    return 'Hello World! Laravel is working!';
});

// Test dashboard route without middleware
Route::get('/test-dashboard-simple', function() {
    return view('dashboards.super-admin.analytics', [
        'metrics' => [
            'total_revenue' => 1000000,
            'total_orders' => 5000,
            'total_users' => 1200,
            'conversion_rate' => 3.5
        ]
    ]);
});

// Simple test dashboard page
Route::get('/test-dashboard', function() {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Test Dashboard</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
            .card { background: white; padding: 20px; border-radius: 8px; margin: 10px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .success { color: green; }
            .error { color: red; }
        </style>
    </head>
    <body>
        <h1>🧪 Dashboard Test Page</h1>
        
        <div class="card">
            <h2>Authentication Status</h2>
            ' . (Auth::check() ? 
                '<p class="success">✅ Logged in as: ' . Auth::user()->name . ' (' . Auth::user()->email . ')</p>' : 
                '<p class="error">❌ Not logged in</p>'
            ) . '
        </div>
        
        <div class="card">
            <h2>Available Dashboard Links</h2>
            ' . (Auth::check() ? '
                <ul>
                    <li><a href="' . route('dashboard.admin.index') . '">Super Admin Dashboard</a></li>
                    <li><a href="' . route('dashboard.admin.analytics') . '">Analytics Page</a></li>
                    <li><a href="' . route('dashboard.it.index') . '">IT Dashboard</a></li>
                    <li><a href="' . route('dashboard.hr.index') . '">HR Dashboard</a></li>
                    <li><a href="' . route('dashboard.finance.index') . '">Finance Dashboard</a></li>
                    <li><a href="' . route('dashboard.supervisor.index') . '">Supervisor Dashboard</a></li>
                    <li><a href="' . route('dashboard.vendor.index') . '">Vendor Dashboard</a></li>
                </ul>
            ' : '
                <p>Please <a href="/test-login">log in first</a></p>
            ') . '
        </div>
        
        <div class="card">
            <h2>Quick Actions</h2>
            <p><a href="/test-login" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">🔑 Auto Login as Admin</a></p>
            <p><a href="/" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">🏠 Go to Home Page</a></p>
        </div>
        
        <div class="card">
            <h2>System Info</h2>
            <p><strong>Laravel Version:</strong> ' . app()->version() . '</p>
            <p><strong>PHP Version:</strong> ' . PHP_VERSION . '</p>
            <p><strong>Current Time:</strong> ' . now() . '</p>
        </div>
    </body>
    </html>';
})->name('test.dashboard');

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

/*
|--------------------------------------------------------------------------
| Legacy HR Dashboard routes (DEPRECATED - use /dashboard/hr for new system)
|--------------------------------------------------------------------------
| These routes are kept for backward compatibility but should be migrated
| to the new dashboard system at /dashboard/hr
*/
// Route::middleware(['auth'])->prefix('hr')->name('hr.legacy.')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Legacy\HR\HRController::class, 'index'])->name('dashboard');
//     
//     // Employee Management
//     Route::get('/employees', [\App\Http\Controllers\Legacy\HR\HRController::class, 'employees'])->name('employees');
//     Route::get('/employees/create', [\App\Http\Controllers\Legacy\HR\HRController::class, 'createEmployee'])->name('employees.create');
//     Route::post('/employees', [\App\Http\Controllers\Legacy\HR\HRController::class, 'storeEmployee'])->name('employees.store');
//     Route::get('/employees/{employee}/edit', [\App\Http\Controllers\Legacy\HR\HRController::class, 'editEmployee'])->name('employees.edit');
//     Route::put('/employees/{employee}', [\App\Http\Controllers\Legacy\HR\HRController::class, 'updateEmployee'])->name('employees.update');
//     
//     // Attendance Management
//     Route::get('/attendance', [\App\Http\Controllers\Legacy\HR\HRController::class, 'attendance'])->name('attendance');
//     Route::post('/attendance/mark', [\App\Http\Controllers\Legacy\HR\HRController::class, 'markAttendance'])->name('attendance.mark');
//     
//     // Leave Management
//     Route::get('/leaves', [\App\Http\Controllers\Legacy\HR\HRController::class, 'leaveRequests'])->name('leaves');
//     Route::post('/leaves/{leave}/approve', [\App\Http\Controllers\Legacy\HR\HRController::class, 'approveLeave'])->name('leaves.approve');
//     Route::post('/leaves/{leave}/reject', [\App\Http\Controllers\Legacy\HR\HRController::class, 'rejectLeave'])->name('leaves.reject');
//     
//     // Payroll Management
//     Route::get('/payroll', [\App\Http\Controllers\Legacy\HR\HRController::class, 'payroll'])->name('payroll');
//     Route::post('/payroll/generate', [\App\Http\Controllers\Legacy\HR\HRController::class, 'generatePayroll'])->name('payroll.generate');
//     Route::post('/payroll/{payroll}/process', [\App\Http\Controllers\Legacy\HR\HRController::class, 'processPayroll'])->name('payroll.process');
//     
//     // Performance Reviews
//     Route::get('/performance', [\App\Http\Controllers\Legacy\HR\HRController::class, 'performanceReviews'])->name('performance');
//     Route::get('/performance/create', [\App\Http\Controllers\Legacy\HR\HRController::class, 'createReview'])->name('performance.create');
//     Route::post('/performance', [\App\Http\Controllers\Legacy\HR\HRController::class, 'storeReview'])->name('performance.store');
//     
//     // Training Programs
//     Route::get('/training', [\App\Http\Controllers\Legacy\HR\HRController::class, 'trainingPrograms'])->name('training');
//     Route::get('/training/create', [\App\Http\Controllers\Legacy\HR\HRController::class, 'createTraining'])->name('training.create');
//     Route::post('/training', [\App\Http\Controllers\Legacy\HR\HRController::class, 'storeTraining'])->name('training.store');
//     
//     // Reports
//     Route::get('/reports', [\App\Http\Controllers\Legacy\HR\HRController::class, 'reports'])->name('reports');
//     Route::get('/reports/attendance', [\App\Http\Controllers\Legacy\HR\HRController::class, 'attendanceReport'])->name('reports.attendance');
// });

/*
|--------------------------------------------------------------------------
| Legacy Delivery Supervisor Dashboard routes (DEPRECATED - use /dashboard/delivery for new system)
|--------------------------------------------------------------------------
| These routes are kept for backward compatibility but should be migrated
| to the new dashboard system at /dashboard/delivery
*/
// Route::middleware(['auth'])->prefix('delivery/supervisor')->name('delivery.supervisor.legacy.')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'index'])->name('dashboard');
//     Route::get('/locations', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'getDriverLocations'])->name('locations');
//     Route::post('/drivers/{driver}/location', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'updateDriverLocation'])->name('driver.location');
//     Route::post('/assign-driver', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'assignDriver'])->name('assign');
//     Route::post('/assignments/{assignment}/status', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'updateDeliveryStatus'])->name('assignment.status');
//     Route::get('/drivers/{driver}/history', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'getDriverHistory'])->name('driver.history');
//     
//     // Driver Management
//     Route::get('/manage-drivers', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'manageDrivers'])->name('manage-drivers');
//     Route::post('/drivers', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'storeDriver'])->name('drivers.store');
//     Route::put('/drivers/{driver}', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'updateDriver'])->name('drivers.update');
//     Route::delete('/drivers/{driver}', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'deleteDriver'])->name('drivers.delete');
//     Route::post('/drivers/{driver}/toggle', [\App\Http\Controllers\Legacy\Delivery\DeliverySupervisorController::class, 'toggleDriverStatus'])->name('drivers.toggle');
// });

// Driver Tracking Page (for drivers to track themselves)
Route::get('/driver/tracking', [\App\Http\Controllers\DriverTrackingController::class, 'index'])->name('driver.tracking');

// Mobile Driver API routes (for GPS tracking from phones)
Route::prefix('api/driver')->name('api.driver.')->group(function () {
    Route::post('/location/update', [\App\Http\Controllers\Api\DriverLocationController::class, 'updateLocation'])->name('location.update');
    Route::post('/location/batch', [\App\Http\Controllers\Api\DriverLocationController::class, 'batchUpdateLocation'])->name('location.batch');
    Route::post('/status/update', [\App\Http\Controllers\Api\DriverLocationController::class, 'updateStatus'])->name('status.update');
    Route::post('/info', [\App\Http\Controllers\Api\DriverLocationController::class, 'getDriverInfo'])->name('info');
});

// Legacy Driver Supervisor Order Management routes (use /dashboard/delivery for new system)
Route::middleware(['auth'])->prefix('driver-supervisor')->name('driver-supervisor.')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\Legacy\DriverSupervisor\OrderManagementController::class, 'index'])->name('orders');
});

// Legacy API routes for driver supervisor (without auth middleware for easier testing)
Route::prefix('api/driver-supervisor')->group(function () {
    Route::get('/orders/{order}', [\App\Http\Controllers\Legacy\DriverSupervisor\OrderManagementController::class, 'getOrderDetails']);
    Route::post('/orders/{order}/assign', [\App\Http\Controllers\Legacy\DriverSupervisor\OrderManagementController::class, 'assignDriver']);
});

// Customer Order Confirmation (public link)
Route::get('/order/confirm/{order}/{token}', [\App\Http\Controllers\OrderConfirmationController::class, 'show'])->name('order.confirm');
Route::post('/order/confirm/{order}/{token}', [\App\Http\Controllers\OrderConfirmationController::class, 'confirm'])->name('order.confirm.submit');

// Homepage Management API (Admin only - Legacy)
Route::middleware(['auth'])->prefix('api/admin/homepage')->group(function () {
    Route::get('/sections', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'getSections']);
    Route::post('/sections', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'updateSections']);
    Route::post('/sections/{sectionId}/toggle', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'toggleSection']);
    Route::post('/sections/reorder', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'reorderSections']);
    Route::get('/lightning-deals', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'getLightningDeals']);
    Route::post('/lightning-deals', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'updateLightningDeals']);
    
    // Featured products management
    Route::get('/featured/{type}', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'getFeaturedProducts']);
    Route::post('/featured/{type}', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'saveFeaturedProducts']);
    Route::get('/featured-counts', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'getFeaturedCounts']);
    
    // Packages management
    Route::get('/packages', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'getPackages']);
    Route::post('/packages', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'savePackages']);
    Route::post('/packages/add', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'addPackage']);
    Route::put('/packages/{packageId}', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'updatePackage']);
    Route::delete('/packages/{packageId}', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'deletePackage']);
});

// Legacy Dashboards Index (DEPRECATED - use /dashboard for new system)
Route::get('/dashboards', function () {
    return view('legacy.dashboards.index');
})->middleware('auth')->name('dashboards.index');

// Finance Dashboard routes (New Dashboard System - Requirements 13.1, 13.2, 13.4)
Route::middleware(['auth'])->prefix('dashboard/finance')->name('dashboard.finance.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\FinanceDashboardController::class, 'index'])->name('index');
    Route::get('/transactions', [\App\Http\Controllers\Dashboard\FinanceDashboardController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/export', [\App\Http\Controllers\Dashboard\FinanceDashboardController::class, 'exportTransactions'])->name('transactions.export');
    Route::get('/payouts', [\App\Http\Controllers\Dashboard\FinanceDashboardController::class, 'payouts'])->name('payouts');
    Route::get('/payouts/export', [\App\Http\Controllers\Dashboard\FinanceDashboardController::class, 'exportPayouts'])->name('payouts.export');
    Route::post('/payouts/{id}/approve', [\App\Http\Controllers\Dashboard\FinanceDashboardController::class, 'approvePayout'])->name('payouts.approve');
    Route::post('/payouts/{id}/reject', [\App\Http\Controllers\Dashboard\FinanceDashboardController::class, 'rejectPayout'])->name('payouts.reject');
    Route::get('/reports', [\App\Http\Controllers\Dashboard\FinanceDashboardController::class, 'reports'])->name('reports');
});

/*
|--------------------------------------------------------------------------
| Legacy Finance routes (DEPRECATED - use /dashboard/finance for new system)
|--------------------------------------------------------------------------
| These routes are kept for backward compatibility but should be migrated
| to the new dashboard system at /dashboard/finance
*/
// Route::middleware(['auth'])->prefix('finance')->name('finance.legacy.')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Legacy\Finance\FinanceController::class, 'index'])->name('dashboard');
//     Route::get('/invoices', [\App\Http\Controllers\Legacy\Finance\FinanceController::class, 'invoices'])->name('invoices');
//     Route::get('/payouts', [\App\Http\Controllers\Legacy\Finance\FinanceController::class, 'payouts'])->name('payouts');
//     Route::get('/refunds', [\App\Http\Controllers\Legacy\Finance\FinanceController::class, 'refunds'])->name('refunds');
//     Route::post('/refunds/{id}/approve', [\App\Http\Controllers\Legacy\Finance\FinanceController::class, 'approveRefund'])->name('refunds.approve');
//     Route::post('/refunds/{id}/reject', [\App\Http\Controllers\Legacy\Finance\FinanceController::class, 'rejectRefund'])->name('refunds.reject');
//     Route::get('/reports', [\App\Http\Controllers\Legacy\Finance\FinanceController::class, 'reports'])->name('reports');
//     Route::get('/export', [\App\Http\Controllers\Legacy\Finance\FinanceController::class, 'exportReport'])->name('export');
// });

/*
|--------------------------------------------------------------------------
| Legacy Store Owner Dashboard routes (DEPRECATED - use /dashboard/store for new system)
|--------------------------------------------------------------------------
| These routes are kept for backward compatibility but should be migrated
| to the new dashboard system at /dashboard/store
*/
// Route::middleware(['auth'])->prefix('store-owner')->name('store-owner.legacy.')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'index'])->name('dashboard');
//     Route::get('/products', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'products'])->name('products');
//     Route::get('/products/create', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'createProduct'])->name('products.create');
//     Route::post('/products', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'storeProduct'])->name('products.store');
//     Route::get('/products/{id}/edit', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'editProduct'])->name('products.edit');
//     Route::put('/products/{id}', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'updateProduct'])->name('products.update');
//     Route::get('/orders', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'orders'])->name('orders');
//     Route::get('/analytics', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'analytics'])->name('analytics');
//     Route::get('/reviews', [\App\Http\Controllers\Legacy\Store\StoreOwnerController::class, 'reviews'])->name('reviews');
// });

// IT Dashboard routes (New Dashboard System - Requirements 8.1, 8.2, 8.4, 8.5)
Route::middleware(['auth'])->prefix('dashboard/it')->name('dashboard.it.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'index'])->name('index');
    Route::get('/logs', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'logs'])->name('logs');
    Route::get('/logs/export', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'exportLogs'])->name('logs.export');
    Route::get('/security', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'security'])->name('security');
    Route::get('/performance', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'performance'])->name('performance');
    Route::get('/alerts', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'alerts'])->name('alerts');
    Route::post('/alerts/{id}/resolve', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'resolveAlert'])->name('alerts.resolve');
    Route::post('/cache/clear', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'clearCache'])->name('cache.clear');
    Route::post('/services/{id}/status', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'updateServiceStatus'])->name('services.status');
    Route::post('/health-check', [\App\Http\Controllers\Dashboard\ITDashboardController::class, 'runHealthCheck'])->name('health-check');
});

// Customer Support Dashboard routes (New Dashboard System - Requirements 9.1, 9.2, 9.5)
Route::middleware(['auth'])->prefix('dashboard/cs')->name('dashboard.cs.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'index'])->name('index');
    Route::get('/tickets', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/export', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'exportTickets'])->name('tickets.export');
    Route::get('/tickets/{id}', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'showTicket'])->name('tickets.show');
    Route::post('/tickets/{id}/assign', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'assignTicket'])->name('tickets.assign');
    Route::post('/tickets/{id}/status', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'updateTicketStatus'])->name('tickets.status');
    Route::post('/tickets/{id}/reply', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'replyToTicket'])->name('tickets.reply');
    Route::get('/feedback', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'feedback'])->name('feedback');
    Route::get('/feedback/export', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'exportFeedback'])->name('feedback.export');
    Route::post('/feedback/{id}/respond', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'respondToFeedback'])->name('feedback.respond');
    Route::get('/agent-performance', [\App\Http\Controllers\Dashboard\CSDashboardController::class, 'agentPerformance'])->name('agent-performance');
});

// HR Dashboard routes (New Dashboard System - Requirements 10.1, 10.2, 10.5)
Route::middleware(['auth'])->prefix('dashboard/hr')->name('dashboard.hr.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'index'])->name('index');
    Route::get('/employees', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'employees'])->name('employees');
    Route::get('/employees/export', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'exportEmployees'])->name('employees.export');
    Route::get('/employees/{id}', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'showEmployee'])->name('employees.show');
    Route::post('/employees', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'storeEmployee'])->name('employees.store');
    Route::put('/employees/{id}', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'updateEmployee'])->name('employees.update');
    Route::get('/attendance', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'attendance'])->name('attendance');
    Route::get('/attendance/export', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'exportAttendance'])->name('attendance.export');
    Route::post('/attendance', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'recordAttendance'])->name('attendance.record');
    Route::get('/leaves', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'leaves'])->name('leaves');
    Route::post('/leaves/{id}/approve', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'approveLeave'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'rejectLeave'])->name('leaves.reject');
    Route::get('/payroll', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'payroll'])->name('payroll');
    Route::get('/payroll/export', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'exportPayroll'])->name('payroll.export');
    Route::post('/payroll/calculate', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'calculatePayroll'])->name('payroll.calculate');
    Route::post('/payroll/generate', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'generatePayroll'])->name('payroll.generate');
    Route::post('/payroll/{id}/process', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'processPayroll'])->name('payroll.process');
    Route::post('/payroll/{id}/pay', [\App\Http\Controllers\Dashboard\HRDashboardController::class, 'markPayrollPaid'])->name('payroll.pay');
});

// Admin Dashboard routes (New Dashboard System - Requirements 7.1, 7.2, 7.3, 7.4)
Route::middleware(['auth'])->prefix('dashboard/admin')->name('dashboard.admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'index'])->name('index');
    Route::get('/users', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/export', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'exportUsers'])->name('users.export');
    Route::get('/orders', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/export', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'exportOrders'])->name('orders.export');
    Route::get('/stores', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'stores'])->name('stores');
    Route::get('/stores/export', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'exportStores'])->name('stores.export');
    Route::get('/alerts', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'alerts'])->name('alerts');
    Route::get('/settings', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'updateSettings'])->name('settings.update');
    Route::post('/bulk-action', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'bulkAction'])->name('bulk-action');
});

// Store Owner Dashboard routes (New Dashboard System - Requirements 12.1, 12.2, 12.4)
Route::middleware(['auth'])->prefix('dashboard/store')->name('dashboard.store.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'index'])->name('index');
    Route::get('/products', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'products'])->name('products');
    Route::get('/products/export', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'exportProducts'])->name('products.export');
    Route::get('/products/create', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/orders', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/export', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'exportOrders'])->name('orders.export');
    Route::get('/analytics', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/earnings', [\App\Http\Controllers\Dashboard\StoreOwnerDashboardController::class, 'earnings'])->name('earnings');
});

// Delivery Supervisor Dashboard routes (New Dashboard System - Requirements 11.1, 11.2, 11.5)
Route::middleware(['auth'])->prefix('dashboard/delivery')->name('dashboard.delivery.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'index'])->name('index');
    Route::get('/drivers', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'drivers'])->name('drivers');
    Route::get('/drivers/export', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'exportDrivers'])->name('drivers.export');
    Route::get('/drivers/{id}', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'showDriver'])->name('drivers.show');
    Route::post('/drivers/{id}/status', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'updateDriverStatus'])->name('drivers.status');
    Route::get('/assignments', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'assignments'])->name('assignments');
    Route::get('/assignments/export', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'exportAssignments'])->name('assignments.export');
    Route::post('/assign', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'assignDriver'])->name('assign');
    Route::post('/assignments/{id}/status', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'updateAssignmentStatus'])->name('assignments.status');
    Route::get('/tracking', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'tracking'])->name('tracking');
    // API endpoints for AJAX polling
    Route::get('/api/locations', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'getDriverLocations'])->name('api.locations');
    Route::get('/api/deliveries', [\App\Http\Controllers\Dashboard\DeliveryDashboardController::class, 'getInTransitDeliveries'])->name('api.deliveries');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Dashboard Routes (6-Dashboard System)
|--------------------------------------------------------------------------
|
| Dashboard routes are now loaded by RouteServiceProvider with proper
| middleware and prefixes. No need to require here.
|
*/

/*
|--------------------------------------------------------------------------
| Test Dashboard Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/test-dashboard.php';
/*
|--------------------------------------------------------------------------
| Simple Test Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/simple-test.php';
/*
|--------------------------------------------------------------------------
| Test Admin Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/test-admin.php';
/*
|--------------------------------------------------------------------------
| Dashboard Fix Test Route (removed - file no longer needed)
|--------------------------------------------------------------------------
*/