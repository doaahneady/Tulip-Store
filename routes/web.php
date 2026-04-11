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

use App\Models\Category as PublicCategory;
use App\Models\Product as PublicProduct;
use App\Models\Setting as PublicSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

Route::get('/storage/{path}', function (string $path) {
    $path = ltrim($path, '/');
    if ($path === '' || str_contains($path, '..')) {
        abort(404);
    }

    // Try multiple possible storage base directories for maximum compatibility
    $basePaths = [
        storage_path('app/public'),
        storage_path('app'),
        base_path('storage/app/public'),
        base_path('storage/app'),
        public_path('storage'),
    ];

    foreach ($basePaths as $base) {
        if (!is_dir($base)) continue;
        
        $fullPath = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            $mime = 'application/octet-stream';
            try {
                $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
            } catch (\Exception $e) {
                // Ignore mime errors
            }
            
            return response()->file($fullPath, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        }
    }

    abort(404);
})->where('path', '.*');

Route::post('/api/session/exchange-rate', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'usd_to_syp_rate' => 'required|numeric|min:1|max:100000',
    ]);

    session(['usd_to_syp_rate' => (float) $validated['usd_to_syp_rate']]);

    return response()->json([
        'success' => true,
        'usd_to_syp_rate' => (float) $validated['usd_to_syp_rate'],
    ]);
});
Route::get('/', function () {
    $martSlugs = ['fruits', 'vegetables', 'khdroaat', 'khodraat', 'mart-fruits', 'mart-vegetables'];

    $productsQuery = PublicProduct::with('category')
        ->active()
        ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'store'));
    $products = $productsQuery->orderBy('created_at', 'desc')->take(20)->get();

    if ($products->isEmpty()) {
        $products = PublicProduct::with('category')
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'store'))
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
    }

    $categoriesQuery = PublicCategory::query()
        ->when(Schema::hasColumn('categories', 'is_active'), fn ($q) => $q->where('is_active', true))
        ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'store'))
        ->when(Schema::hasColumn('categories', 'slug'), fn ($q) => $q->whereNotIn('slug', $martSlugs));
    $categories = $categoriesQuery
        ->when(Schema::hasColumn('categories', 'display_order'), fn ($q) => $q->orderBy('display_order'))
        ->orderBy('name')
        ->take(12)
        ->get();

    if ($categories->isEmpty()) {
        $categories = PublicCategory::query()
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'store'))
            ->when(Schema::hasColumn('categories', 'slug'), fn ($q) => $q->whereNotIn('slug', $martSlugs))
            ->when(Schema::hasColumn('categories', 'display_order'), fn ($q) => $q->orderBy('display_order'))
            ->orderBy('name')
            ->take(12)
            ->get();
    }

    $slides = PublicSetting::get('homepage_slider_slides', []);
    $defaultSlides = [
        [
            'image' => 'public\images\banner1.jpg',
            'title' => 'ط·آ·ط¢آ£ط·آ·ط¢آ±ط·آ·ط¢آ³ط·آ¸أ¢â‚¬â€چ',
            'subtitle' => 'ط·آ·ط¹آ¾ط·آ·ط¢آ³ط·آ¸ط«â€ ط·آ¸أ¢â‚¬ع‘ ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ¹ط·آ¸أ¢â‚¬آ ط·آ·ط¢آ§ ط·آ·ط¢آ£ط·آ¸ط¸آ¾ط·آ·ط¢آ¶ط·آ¸أ¢â‚¬â€چ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ¸أ¢â‚¬آ¦ط·آ¸أ¢â‚¬آ ط·آ·ط¹آ¾ط·آ·ط¢آ¬ط·آ·ط¢آ§ط·آ·ط¹آ¾ ط·آ¸ط«â€ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ¹ط·آ·ط¢آ±ط·آ¸ط«â€ ط·آ·ط¢آ¶',
            'link' => '/store',
        ],
        [
            'image' => '/images/banner2.jpg',
            'title' => 'ط·آ·ط¢آ¹ط·آ·ط¢آ±ط·آ¸ط«â€ ط·آ·ط¢آ¶ ط·آ¸ط«â€ ط·آ·ط¢آ®ط·آ·ط¢آµط·آ¸ط«â€ ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ§ط·آ·ط¹آ¾',
            'subtitle' => 'ط·آ·ط¢آ§ط·آ¸ط¦â€™ط·آ·ط¹آ¾ط·آ·ط¢آ´ط·آ¸ط¸آ¾ ط·آ·ط¢آ¹ط·آ·ط¢آ±ط·آ¸ط«â€ ط·آ·ط¢آ¶ط·آ¸أ¢â‚¬آ ط·آ·ط¢آ§ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ¸أ¢â‚¬آ¦ط·آ¸أ¢â‚¬آ¦ط·آ¸ط¸آ¹ط·آ·ط¢آ²ط·آ·ط¢آ© ط·آ¸ط«â€ ط·آ·ط¹آ¾ط·آ¸ط«â€ ط·آ¸ط¸آ¾ط·آ¸ط¸آ¹ط·آ·ط¢آ± ط·آ·ط¢آ£ط·آ¸ط¦â€™ط·آ·ط¢آ¨ط·آ·ط¢آ± ط·آ·ط¢آ¹ط·آ¸أ¢â‚¬â€چط·آ¸أ¢â‚¬آ° ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ´ط·آ·ط¹آ¾ط·آ·ط¢آ±ط·آ¸ط¸آ¹ط·آ·ط¢آ§ط·آ·ط¹آ¾ط·آ¸ط¦â€™',
            'link' => '/store?on_sale=1',
        ],
        [
            'image' => '/images/banner3.jpg',
            'title' => 'ط·آ¸ط«â€ ط·آ·ط¢آµط·آ¸أ¢â‚¬â€چ ط·آ·ط¢آ­ط·آ·ط¢آ¯ط·آ¸ط¸آ¹ط·آ·ط¢آ«ط·آ·ط¢آ§ط·آ¸أ¢â‚¬آ¹',
            'subtitle' => 'ط·آ·ط¢آ§ط·آ¸ط¦â€™ط·آ·ط¹آ¾ط·آ·ط¢آ´ط·آ¸ط¸آ¾ ط·آ·ط¢آ£ط·آ·ط¢آ­ط·آ·ط¢آ¯ط·آ·ط¢آ« ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ¸أ¢â‚¬آ¦ط·آ¸أ¢â‚¬آ ط·آ·ط¹آ¾ط·آ·ط¢آ¬ط·آ·ط¢آ§ط·آ·ط¹آ¾ ط·آ¸ط¸آ¾ط·آ¸ط¸آ¹ ط·آ¸أ¢â‚¬آ¦ط·آ·ط¹آ¾ط·آ·ط¢آ¬ط·آ·ط¢آ±ط·آ¸أ¢â‚¬آ ط·آ·ط¢آ§',
            'link' => '/store?sort=newest',
        ],
    ];

    if (! is_array($slides)) {
        $slides = [];
    }

    $slides = array_values(array_filter($slides, fn ($s) => is_array($s)));

    if (count($slides) < 3) {
        for ($i = count($slides); $i < 3; $i++) {
            $slides[] = $defaultSlides[$i];
        }
        PublicSetting::set('homepage_slider_slides', $slides, 'json', 'Home page slider slides');
    }

    if (empty($slides)) {
        $slides = $defaultSlides;
        PublicSetting::set('homepage_slider_slides', $slides, 'json', 'Home page slider slides');
    }

    return view('home-new', [
        'products' => $products,
        'categories' => $categories,
        'slides' => $slides,
    ]);
})->name('home');

// Quick access to employee login for testing
Route::get('/staff', function () {
    return redirect()->route('employee.login');
});

// Test employee authentication status
Route::get('/test-auth', function () {
    $userAuth = auth()->check() ? auth()->user() : null;
    $employeeAuth = auth('employee')->check() ? auth('employee')->user() : null;

    return response()->json([
        'customer_auth' => [
            'authenticated' => auth()->check(),
            'user' => $userAuth ? [
                'id' => $userAuth->id,
                'name' => $userAuth->name,
                'email' => $userAuth->email,
            ] : null,
        ],
        'employee_auth' => [
            'authenticated' => auth('employee')->check(),
            'employee' => $employeeAuth ? [
                'id' => $employeeAuth->id,
                'name' => $employeeAuth->full_name,
                'email' => $employeeAuth->email,
                'roles' => [
                    'is_admin' => $employeeAuth->is_admin,
                    'is_it' => $employeeAuth->is_it,
                    'is_hr' => $employeeAuth->is_hr,
                    'is_finance' => $employeeAuth->is_finance,
                    'is_driver_supervisor' => $employeeAuth->is_driver_supervisor,
                    'is_trader' => $employeeAuth->is_trader,
                ],
            ] : null,
        ],
    ]);
});

// Debug Admin Employee Route
Route::get('/debug-admin-employee', function () {
    try {
        $admin = \App\Models\Employee::where('email', 'admin@tulipstore.com')->first();

        if (! $admin) {
            return response()->json([
                'exists' => false,
                'message' => 'Admin employee does not exist. Visit /create-admin-employee to create it.',
            ]);
        }

        $passwordCheck = \Illuminate\Support\Facades\Hash::check('password123', $admin->password);

        return response()->json([
            'exists' => true,
            'employee' => [
                'id' => $admin->id,
                'email' => $admin->email,
                'status' => $admin->status,
                'password_set' => ! empty($admin->password),
                'password_matches' => $passwordCheck,
                'email_verified' => ! is_null($admin->email_verified_at),
                'roles' => [
                    'is_admin' => $admin->is_admin,
                    'is_it' => $admin->is_it,
                    'is_hr' => $admin->is_hr,
                    'is_finance' => $admin->is_finance,
                    'is_driver_supervisor' => $admin->is_driver_supervisor,
                    'is_trader' => $admin->is_trader,
                ],
            ],
            'login_test' => [
                'email' => 'admin@tulipstore.com',
                'password' => 'password123',
                'password_check' => $passwordCheck ? 'ط£آ¢ط¥â€œأ¢â‚¬آ¦ CORRECT' : 'ط£آ¢أ¢â‚¬إ’ط¥â€™ INCORRECT',
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// Create Admin Employee Route
Route::get('/create-admin-employee', function () {
    try {
        $admin = \App\Models\Employee::updateOrCreate(
            ['email' => 'admin@tulipstore.com'],
            [
                'employee_code' => 'SA001',
                'first_name' => 'Ahmed',
                'last_name' => 'Al-Manager',
                'email' => 'admin@tulipstore.com',
                'password' => bcrypt('password123'),
                'phone' => '+963-11-1234567',
                'department' => 'Administration',
                'position' => 'Chief Executive Officer',
                'employment_type' => 'full_time',
                'hire_date' => now(),
                'status' => 'active',
                'salary' => 150000.00,
                'city' => 'Damascus',
                'country' => 'Syria',
                'gender' => 'male',
                'email_verified_at' => now(),
                // All roles enabled
                'is_admin' => true,
                'is_it' => true,
                'is_hr' => true,
                'is_finance' => true,
                'is_driver_supervisor' => true,
                'is_trader' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Admin employee created/updated successfully!',
            'credentials' => [
                'email' => 'admin@tulipstore.com',
                'password' => 'password123',
                'login_url' => url('/employee/login'),
            ],
            'employee' => [
                'id' => $admin->id,
                'email' => $admin->email,
                'status' => $admin->status,
                'roles' => [
                    'is_admin' => $admin->is_admin,
                    'is_it' => $admin->is_it,
                    'is_hr' => $admin->is_hr,
                    'is_finance' => $admin->is_finance,
                    'is_driver_supervisor' => $admin->is_driver_supervisor,
                    'is_trader' => $admin->is_trader,
                ],
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// Test route to create employees with different role combinations
Route::get('/create-test-employees', function () {
    try {
        $adminEmployee = App\Models\Employee::updateOrCreate(
            ['email' => 'admin@tulipstore.com'],
            [
                'employee_code' => 'EMP001',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => bcrypt('password123'),
                'phone' => '1234567890',
                'department' => 'Administration',
                'position' => 'Super Admin',
                'employment_type' => 'full_time',
                'hire_date' => now(),
                'status' => 'active',
                'is_admin' => true,
                'is_it' => true,
                'is_hr' => true,
                'is_finance' => true,
                'is_cs' => true,
                'is_driver_supervisor' => true,
                'is_trader' => true,
            ]
        );

        // Single role employee (IT only)
        $itEmployee = App\Models\Employee::updateOrCreate(
            ['email' => 'it@tulipstore.com'],
            [
                'employee_code' => 'EMP002',
                'first_name' => 'John',
                'last_name' => 'Tech',
                'password' => bcrypt('password123'),
                'phone' => '1234567891',
                'department' => 'Information Technology',
                'position' => 'IT Specialist',
                'employment_type' => 'full_time',
                'hire_date' => now(),
                'status' => 'active',
                'is_it' => true,
            ]
        );

        $hrEmployee = App\Models\Employee::updateOrCreate(
            ['email' => 'hr@tulipstore.com'],
            [
                'employee_code' => 'EMP004',
                'first_name' => 'Hana',
                'last_name' => 'HR',
                'password' => bcrypt('password123'),
                'phone' => '1234567893',
                'department' => 'Human Resources',
                'position' => 'HR Specialist',
                'employment_type' => 'full_time',
                'hire_date' => now(),
                'status' => 'active',
                'is_hr' => true,
            ]
        );

        $financeEmployee = App\Models\Employee::updateOrCreate(
            ['email' => 'finance@tulipstore.com'],
            [
                'employee_code' => 'EMP005',
                'first_name' => 'Fadi',
                'last_name' => 'Finance',
                'password' => bcrypt('password123'),
                'phone' => '1234567894',
                'department' => 'Finance',
                'position' => 'Accountant',
                'employment_type' => 'full_time',
                'hire_date' => now(),
                'status' => 'active',
                'is_finance' => true,
                'is_accountant' => true,
            ]
        );

        $csEmployee = App\Models\Employee::updateOrCreate(
            ['email' => 'support@tulipstore.com'],
            [
                'employee_code' => 'EMP006',
                'first_name' => 'Noor',
                'last_name' => 'Support',
                'password' => bcrypt('password123'),
                'phone' => '1234567895',
                'department' => 'Customer Support',
                'position' => 'Support Agent',
                'employment_type' => 'full_time',
                'hire_date' => now(),
                'status' => 'active',
                'is_cs' => true,
            ]
        );

        $supervisorEmployee = App\Models\Employee::updateOrCreate(
            ['email' => 'supervisor@tulipstore.com'],
            [
                'employee_code' => 'EMP007',
                'first_name' => 'Samer',
                'last_name' => 'Supervisor',
                'password' => bcrypt('password123'),
                'phone' => '1234567896',
                'department' => 'Delivery',
                'position' => 'Dispatch Supervisor',
                'employment_type' => 'full_time',
                'hire_date' => now(),
                'status' => 'active',
                'is_driver_supervisor' => true,
            ]
        );

        // Multi-role employee (HR + Finance)
        $multiEmployee = App\Models\Employee::updateOrCreate(
            ['email' => 'multi@tulipstore.com'],
            [
                'employee_code' => 'EMP003',
                'first_name' => 'Sarah',
                'last_name' => 'Multi',
                'password' => bcrypt('password123'),
                'phone' => '1234567892',
                'department' => 'Management',
                'position' => 'Department Manager',
                'employment_type' => 'full_time',
                'hire_date' => now(),
                'status' => 'active',
                'is_hr' => true,
                'is_finance' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Test employees created/updated successfully!',
            'employees' => [
                [
                    'email' => 'admin@tulipstore.com',
                    'password' => 'password123',
                    'roles' => 'All roles (should see dashboard selection)',
                    'expected_behavior' => 'Dashboard selection page',
                ],
                [
                    'email' => 'it@tulipstore.com',
                    'password' => 'password123',
                    'roles' => 'IT only',
                    'expected_behavior' => 'Direct to IT dashboard',
                ],
                [
                    'email' => 'hr@tulipstore.com',
                    'password' => 'password123',
                    'roles' => 'HR only',
                    'expected_behavior' => 'Direct to HR dashboard',
                ],
                [
                    'email' => 'finance@tulipstore.com',
                    'password' => 'password123',
                    'roles' => 'Finance only',
                    'expected_behavior' => 'Direct to Finance dashboard',
                ],
                [
                    'email' => 'support@tulipstore.com',
                    'password' => 'password123',
                    'roles' => 'Customer support only',
                    'expected_behavior' => 'Direct to CS dashboard',
                ],
                [
                    'email' => 'supervisor@tulipstore.com',
                    'password' => 'password123',
                    'roles' => 'Delivery supervisor only',
                    'expected_behavior' => 'Direct to supervisor dashboard',
                ],
                [
                    'email' => 'multi@tulipstore.com',
                    'password' => 'password123',
                    'roles' => 'HR + Finance',
                    'expected_behavior' => 'Dashboard selection page',
                ],
            ],
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ]);
    }
});

Route::get('/__test/dashboard-events/{dashboard}', function (string $dashboard, \Illuminate\Http\Request $request) {
    abort_unless(app()->environment(['local', 'testing']), 404);
    $allowed = ['admin', 'it', 'hr', 'cs', 'finance', 'supervisor', 'vendor'];
    abort_unless(in_array($dashboard, $allowed, true), 404);

    $key = 'test.dashboard_events.'.$dashboard;
    $events = \Illuminate\Support\Facades\Cache::get($key, []);
    if (! is_array($events)) {
        $events = [];
    }

    $since = $request->query('since');
    if (is_string($since) && $since !== '') {
        $events = array_values(array_filter($events, function ($e) use ($since) {
            $ts = is_array($e) ? ($e['timestamp'] ?? null) : null;
            if (! is_string($ts) || $ts === '') {
                return false;
            }
            return $ts >= $since;
        }));
    }

    if ($request->boolean('clear')) {
        \Illuminate\Support\Facades\Cache::forget($key);
    }

    return response()->json([
        'dashboard' => $dashboard,
        'count' => count($events),
        'events' => $events,
    ]);
});

Route::post('/__test/system-setting', function (\Illuminate\Http\Request $request) {
    abort_unless(app()->environment(['local', 'testing']), 404);
    $data = $request->validate([
        'key' => 'required|string|max:255',
        'value' => 'nullable',
        'type' => 'nullable|string|max:50',
    ]);
    $type = $data['type'] ?? 'string';
    \App\Models\SystemSetting::set($data['key'], $data['value'], $type);
    return response()->json(['success' => true]);
});

Route::post('/__test/orders/create', function (\Illuminate\Http\Request $request) {
    abort_unless(app()->environment(['local', 'testing']), 404);
    $user = \App\Models\User::first();
    if (! $user) {
        $user = \App\Models\User::create([
            'username' => 'e2e_customer',
            'email' => 'e2e@tulipstore.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'user_full_name' => 'E2E Customer',
            'mobile' => '+10000000000',
            'verified' => true,
        ]);
    }
    $product = \App\Models\Product::first();
    $order = \App\Models\Order::create([
        'user_id' => $user->id,
        'customer_id' => $user->id,
        'order_number' => 'ORD-E2E-'.strtoupper(uniqid()),
        'recipient_name' => 'Playwright Customer',
        'phone' => '+10000000000',
        'village' => 'Test Village',
        'address_note' => 'E2E test',
        'latitude' => 33.5138,
        'longitude' => 36.2765,
        'delivery_method' => 'normal',
        'payment_method' => 'cash',
        'status' => 'pending',
        'payment_status' => 'pending',
        'subtotal' => 50.00,
        'delivery_cost' => 0,
        'service_fee' => 0,
        'total' => 50.00,
    ]);
    if ($product) {
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name ?? 'Test Product',
            'quantity' => 1,
            'price' => 50.00,
        ]);
    }
    return response()->json(['success' => true, 'order_id' => $order->id]);
});

Route::post('/__test/orders/{order}/transition', function (\Illuminate\Http\Request $request, \App\Models\Order $order) {
    abort_unless(app()->environment(['local', 'testing']), 404);
    $data = $request->validate([
        'status' => 'required|string|max:50',
        'admin_override' => 'nullable|boolean',
    ]);
    $statusManager = app(\App\Services\OrderStatusManager::class);
    $current = $statusManager->normalize((string) ($order->status ?? 'pending'));
    $next = $statusManager->normalize((string) $data['status']);
    $adminOverride = (bool) ($data['admin_override'] ?? true);
    if ($current === $next) {
        return response()->json(['success' => true, 'order' => $order->fresh()]);
    }
    \App\Services\StatusTransitionService::transition($order, 'status', $next, null, $adminOverride);
    return response()->json(['success' => true, 'order' => $order->fresh()]);
});

Route::post('/__test/drivers/create-and-assign', function (\Illuminate\Http\Request $request) {
    abort_unless(app()->environment(['local', 'testing']), 404);
    $data = $request->validate([
        'order_id' => 'required|integer',
    ]);
    $order = \App\Models\Order::query()->findOrFail($data['order_id']);
    $driverUser = \App\Models\User::updateOrCreate(
        ['email' => 'driver1@tulipstore.com'],
        [
            'username' => 'driver1',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'user_full_name' => 'Driver One',
            'mobile' => '+10000000001',
            'address' => 'Driver Address',
            'language' => 'english',
            'gender' => 'other',
            'currency' => 'USD',
            'verified' => true,
        ]
    );

    $driver = \App\Models\Driver::updateOrCreate(
        ['user_id' => $driverUser->id],
        [
            'vehicle_type' => 'car',
            'vehicle_plate' => 'TEST-001',
            'status' => 'active',
            'availability' => 'available',
        ]
    );

    $assignment = \App\Models\DeliveryAssignment::updateOrCreate(
        ['order_id' => $order->id],
        [
            'driver_id' => $driver->id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]
    );

    return response()->json([
        'success' => true,
        'driver' => [
            'email' => $driverUser->email,
            'password' => 'password123',
            'user_id' => $driverUser->id,
            'driver_id' => $driver->id,
        ],
        'assignment' => [
            'id' => $assignment->id,
            'status' => $assignment->status,
        ],
    ]);
});

// Test page to verify employee authentication and dashboard functionality
Route::get('/test-employee-system', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Employee System Test</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
            .card { background: white; padding: 20px; border-radius: 8px; margin: 10px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .success { color: green; }
            .info { color: blue; }
            .btn { background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 5px; }
        </style>
    </head>
    <body>
        <h1>ط¸â€¹ط¹ط›ط¢آ§ط¹آ¾ Employee Authentication System Test</h1>
        
        <div class="card">
            <h2>Test Instructions</h2>
            <ol>
                <li><strong>Create Test Employees:</strong> <a href="/create-test-employees" class="btn">Create Test Employees</a></li>
                <li><strong>Test Single Role Employee:</strong> Login with <code>it@tulipstore.com</code> / <code>password123</code> - Should go directly to IT dashboard</li>
                <li><strong>Test Multi-Role Employee:</strong> Login with <code>multi@tulipstore.com</code> / <code>password123</code> - Should show dashboard selection</li>
                <li><strong>Test Admin Employee:</strong> Login with <code>admin@tulipstore.com</code> / <code>password123</code> - Should show dashboard selection</li>
                <li><strong>Test Dropdown:</strong> Once in any dashboard, click the profile dropdown in top-right corner</li>
            </ol>
        </div>
        
        <div class="card">
            <h2>Quick Links</h2>
            <a href="/staff" class="btn">Employee Login</a>
            <a href="/test-auth" class="btn">Check Auth Status</a>
            <a href="/" class="btn">Back to Home</a>
        </div>
        
        <div class="card">
            <h2>Expected Behaviors</h2>
            <ul>
                <li><strong>Single Role Employees:</strong> Direct redirect to their dashboard</li>
                <li><strong>Multi-Role Employees:</strong> Dashboard selection page with role options</li>
                <li><strong>Profile Dropdown:</strong> Should show user info, dashboard switcher (if multiple roles), and logout</li>
                <li><strong>Dashboard Switcher:</strong> Should only show dashboards the employee has access to</li>
            </ul>
        </div>
    </body>
    </html>';
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
    Route::get('/settings', function () {
        return view('profile');
    })->name('settings');
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

// Footer Pages
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/returns', 'pages.returns')->name('returns');
Route::view('/shipping', 'pages.shipping')->name('shipping');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/cookies', 'pages.cookies')->name('cookies');

// Auth API routes
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::middleware(['web'])->group(function () {
    Route::post('/api/register', [CustomAuthController::class, 'register']);
    Route::post('/api/verify-registration', [CustomAuthController::class, 'verifyRegistration']);
    Route::get('/api/get-verification-info', [CustomAuthController::class, 'getVerificationInfo']);
    Route::post('/api/login', [CustomAuthController::class, 'login']);
    Route::post('/api/forgot-password', [CustomAuthController::class, 'forgotPassword']);
    Route::post('/api/verify-code', [CustomAuthController::class, 'verifyCode']);
    Route::post('/api/reset-password', [CustomAuthController::class, 'resetPassword']);
    Route::post('/api/logout', [CustomAuthController::class, 'logout'])->middleware('auth');

    // Google OAuth
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

    // Product & Category API routes

    // Public homepage packages API (Legacy)
    Route::get('/api/homepage/packages', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'getPackages']);
    Route::get('/api/homepage/slides', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'getSlides']);

    // Package products page (Legacy)
    Route::get('/package/{packageId}', [\App\Http\Controllers\Legacy\Admin\HomepageManagementController::class, 'showPackagePage'])->name('package.show');

    // Cart API routes
    Route::get('/api/cart', [CartController::class, 'index']);
    Route::post('/api/cart/add', [CartController::class, 'add']);
    Route::post('/api/cart/update', [CartController::class, 'update']);
    Route::post('/api/cart/remove', [CartController::class, 'remove']);
    Route::post('/api/cart/clear', [CartController::class, 'clear']);
    Route::get('/api/cart/items', [CartController::class, 'getItems']);

    Route::middleware('auth')->group(function () {
        Route::get('/api/wishlist', [\App\Http\Controllers\Api\WishlistController::class, 'index']);
        Route::post('/api/wishlist/add', [\App\Http\Controllers\Api\WishlistController::class, 'add']);
        Route::post('/api/wishlist/toggle', [\App\Http\Controllers\Api\WishlistController::class, 'toggle']);
        Route::delete('/api/wishlist/items/{productId}', [\App\Http\Controllers\Api\WishlistController::class, 'remove']);

        Route::get('/api/addresses', [\App\Http\Controllers\Api\UserProfileController::class, 'addresses']);
        Route::post('/api/addresses', [\App\Http\Controllers\Api\UserProfileController::class, 'storeAddress']);
        Route::put('/api/addresses/{id}', [\App\Http\Controllers\Api\UserProfileController::class, 'updateAddress']);
        Route::delete('/api/addresses/{id}', [\App\Http\Controllers\Api\UserProfileController::class, 'deleteAddress']);
        Route::post('/api/addresses/{id}/default', [\App\Http\Controllers\Api\UserProfileController::class, 'setDefaultAddress']);

        Route::get('/profile/orders', [\App\Http\Controllers\Api\UserProfileController::class, 'orders']);
        Route::get('/profile/notifications', [\App\Http\Controllers\Api\UserProfileController::class, 'notifications']);
        Route::put('/profile/update', [\App\Http\Controllers\Api\UserProfileController::class, 'updateProfile']);
        Route::put('/profile/password', [\App\Http\Controllers\Api\UserProfileController::class, 'changePassword']);
        Route::post('/profile/email/verify-request', [\App\Http\Controllers\Api\UserProfileController::class, 'requestEmailVerification']);
        Route::post('/profile/email/verify-confirm', [\App\Http\Controllers\Api\UserProfileController::class, 'confirmEmailVerification']);
    });

    // Custom Gift API routes
    Route::post('/api/custom-gift/add-to-cart', [\App\Http\Controllers\CustomGiftController::class, 'addToCart']);
    Route::get('/api/custom-gift/options', [\App\Http\Controllers\CustomGiftController::class, 'getOptions']);
    Route::post('/api/custom-gift/remove', [\App\Http\Controllers\CustomGiftController::class, 'removeFromCart']);

    // Custom Bouquet API routes
    Route::post('/api/custom-bouquet/add-to-cart', [\App\Http\Controllers\CustomGiftController::class, 'addBouquetToCart']);

    // Order API routes
    Route::post('/api/checkout/delivery-fee', [\App\Http\Controllers\OrderController::class, 'deliveryFeeQuote']);
    Route::post('/api/orders/create', [\App\Http\Controllers\OrderController::class, 'create']);
    Route::post('/api/orders/{id}/upload-receipt', [\App\Http\Controllers\OrderController::class, 'uploadReceipt']);
    Route::get('/api/user/profile', function () {
        if (Auth::check()) {
            $user = Auth::user();

            return response()->json([
                'name' => $user->name ?? $user->user_full_name,
                'phone' => $user->phone ?? $user->mobile,
                'email' => $user->email,
                'balance' => (float) ($user->balance ?? 0),
            ]);
        }

        return response()->json(['error' => 'Not authenticated'], 401);
    });

    // Saved cards API
    Route::get('/api/user/saved-cards', function () {
        if (! Auth::check()) {
            return response()->json([], 401);
        }
        $cards = \App\Models\UserSavedCard::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get(['id', 'brand', 'last4', 'expiry', 'holder_name']);
        return response()->json($cards->toArray());
    });
    Route::post('/api/user/saved-cards', function (\Illuminate\Http\Request $request) {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $validated = $request->validate([
            'last4' => 'required|string|size:4|regex:/^[0-9]+$/',
            'expiry' => 'required|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
            'brand' => 'nullable|string|max:32',
            'holder_name' => 'nullable|string|max:255',
        ]);
        $card = \App\Models\UserSavedCard::create([
            'user_id' => Auth::id(),
            'brand' => $validated['brand'] ?? 'Card',
            'last4' => $validated['last4'],
            'expiry' => $validated['expiry'],
            'holder_name' => $validated['holder_name'] ?? null,
        ]);
        return response()->json(['success' => true, 'data' => $card], 201);
    });
    Route::delete('/api/user/saved-cards/{id}', function ($id) {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $card = \App\Models\UserSavedCard::where('user_id', Auth::id())->where('id', $id)->first();
        if (! $card) {
            return response()->json(['error' => 'Card not found'], 404);
        }
        $card->delete();
        return response()->json(['success' => true]);
    });
});

// Category page route
Route::get('/category/{slug}', [ProductController::class, 'byCategory'])->name('category.show');

Route::get('/categories', function () {
    return view('pages.categories');
})->name('categories');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

// Product details page route
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/{id}', function ($id) {
    return redirect()->route('product.show', ['id' => $id]);
});

// Favorites page route
Route::get('/favorites', function () {
    return view('favorites');
})->name('favorites');

// Cart page route
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

// Store page route
Route::get('/traders/{trader}/products', function (\App\Models\Trader $trader) {
    $avg = 0.0;
    if (Schema::hasColumn('products', 'trader_id')) {
        $base = PublicProduct::query()->where('trader_id', $trader->id)
            ->when(Schema::hasColumn('products', 'market'), fn ($q) => $q->where('market', 'store'));

        if (Schema::hasColumn('products', 'rating')) {
            $avgActive = (clone $base)->active()->avg('rating');
            $avg = $avgActive !== null ? (float) $avgActive : 0.0;
            if ($avg <= 0) {
                $avgAll = (clone $base)->avg('rating');
                $avg = $avgAll !== null ? (float) $avgAll : 0.0;
            }
        }
    }

    return view('store', [
        'trader' => $trader,
        'traderAverageRating' => $avg,
    ]);
})->name('trader.products');

Route::get('/store', function () {
    return view('store');
})->name('store');

// Store 3D redirects to gifts
Route::get('/store-3d', function () {
    return redirect('/gifts');
})->name('store-3d');

// Classic store page (alternative view)
Route::get('/store/classic', function () {
    return view('store');
})->name('store.classic');

// Gift routes
Route::get('/gifts', function () {
    return view('gifts.index');
})->name('gifts.index');

Route::get('/gifts/test', function () {
    return view('gifts.test');
})->name('gifts.test');

Route::get('/gifts/box-arrangement', function () {
    return view('gifts.box-arrangement');
})->name('gifts.box-arrangement');

Route::get('/gifts/flower-bouquet', function () {
    return view('gifts.flower-bouquet');
})->name('gifts.flower-bouquet');

// Tulip Mart (Supermarket)
Route::get('/mart', function () {
    return view('mart.index');
})->name('mart.index');

Route::get('/mart/category/{category}', function ($category) {
    $categoryModel = \App\Models\Category::where('slug', $category)->firstOrFail();
    $subcategories = $categoryModel->subcategories()->where('is_active', true)->orderBy('display_order')->get();
    return view('mart.subcategories', [
        'category' => $categoryModel,
        'subcategories' => $subcategories
    ]);
})->name('mart.category');

Route::get('/mart/category/{category}/{subcategory}', function ($category, $subcategory) {
    $categoryModel = \App\Models\Category::where('slug', $category)->firstOrFail();
    $subcategoryModel = \App\Models\Subcategory::where('slug', $subcategory)
        ->where('category_id', $categoryModel->id)
        ->firstOrFail();
    $products = $subcategoryModel->products()
        ->where('is_active', true)
        ->with(['attributes', 'category'])
        ->get();
    return view('mart.subcategory-products', [
        'category' => $categoryModel,
        'subcategory' => $subcategoryModel,
        'products' => $products
    ]);
})->name('mart.subcategory');

Route::get('/mart/products', function () {
    return view('mart.products');
})->name('mart.products');

Route::get('/mart/daily-prices', function () {
    return view('mart.daily-prices');
})->name('mart.daily-prices');

Route::get('/gifts/{gift}', [App\Http\Controllers\GiftController::class, 'show'])->name('gifts.show');
Route::get('/gifts/category/{category}', [App\Http\Controllers\GiftController::class, 'category'])->name('gifts.category');

// Checkout page route (requires authentication)
Route::get('/checkout', function () {
    return view('checkout');
})->middleware('auth')->name('checkout');

// Order confirmation page
Route::get('/order-confirmation/{id}', [\App\Http\Controllers\OrderController::class, 'show'])->name('order.confirmation');

// My Orders page (owned by the logged-in user; check happens inside controller)
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
Route::get('/test-orders', function () {
    $orders = \App\Models\Order::with('items.product')->paginate(10);

    return view('test-orders', compact('orders'));
});

// Test permissions page
Route::get('/test-permissions', function () {
    return view('test-permissions');
})->middleware('auth');

// Test database connectivity
Route::get('/test-database', function () {
    return view('test-database');
});

/*
|--------------------------------------------------------------------------
| Employee Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('employee')->name('employee.')->group(function () {
    // Employee login routes (guest only)
    Route::middleware('guest:employee')->group(function () {
        Route::get('/login', [App\Http\Controllers\Auth\EmployeeAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Auth\EmployeeAuthController::class, 'login']);
    });

    // Employee authenticated routes
    Route::middleware('auth:employee')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Auth\EmployeeAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [App\Http\Controllers\Auth\EmployeeAuthController::class, 'dashboard'])->name('dashboard');

        // Employee Profile Routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [App\Http\Controllers\EmployeeProfileController::class, 'show'])->name('show');
            Route::get('/edit', [App\Http\Controllers\EmployeeProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [App\Http\Controllers\EmployeeProfileController::class, 'update'])->name('update');
            Route::post('/update-password', [App\Http\Controllers\EmployeeProfileController::class, 'updatePassword'])->name('update-password');
            Route::post('/toggle-2fa', [App\Http\Controllers\EmployeeProfileController::class, 'toggleTwoFactor'])->name('toggle-2fa');

            // Employee Management (for HR and Managers)
            Route::get('/employees', [App\Http\Controllers\EmployeeProfileController::class, 'index'])->name('employees');
            Route::get('/employees/{employee}', [App\Http\Controllers\EmployeeProfileController::class, 'showEmployee'])->name('show-employee');
            Route::put('/employees/{employee}', [App\Http\Controllers\EmployeeProfileController::class, 'updateEmployee'])->name('update-employee');
            Route::get('/stats', [App\Http\Controllers\EmployeeProfileController::class, 'getStats'])->name('stats');
        });
    });
});

Route::prefix('api/support')->middleware('auth:employee')->group(function () {
    Route::get('/traders/pending', [\App\Http\Controllers\Api\SupportApprovalsController::class, 'pendingTraders']);
    Route::post('/traders/{trader}/approve', [\App\Http\Controllers\Api\SupportApprovalsController::class, 'approveTrader']);
    Route::post('/traders/{trader}/reject', [\App\Http\Controllers\Api\SupportApprovalsController::class, 'rejectTrader']);
    
    // Coupon Management Routes
    Route::prefix('coupons')->group(function () {
        Route::get('/', function() {
            $coupons = \App\Models\DiscountCoupon::with('creator')->orderBy('created_at', 'desc')->get();
            return response()->json(['coupons' => $coupons]);
        });
        
        Route::post('/', function(\Illuminate\Http\Request $request) {
            try {
                $validated = $request->validate([
                    'code' => 'required|string|max:50|unique:discount_coupons,code',
                    'discount_percentage' => 'required|numeric|min:0.01|max:100',
                    'purpose' => 'nullable|string',
                    'max_uses' => 'nullable|integer|min:1',
                    'expires_at' => 'nullable|date',
                ]);
                
                $validated['code'] = strtoupper($validated['code']);
                $validated['is_active'] = true;
                $validated['used_count'] = 0;
                
                // Try to get authenticated employee ID from different guards
                $employee = auth('employee')->user() ?? auth()->user();
                if ($employee && isset($employee->id)) {
                    $validated['created_by'] = $employee->id;
                }
                
                $coupon = \App\Models\DiscountCoupon::create($validated);
                
                return response()->json([
                    'success' => true,
                    'coupon' => $coupon,
                    'message' => 'تم إنشاء كود الخصم بنجاح'
                ]);
            } catch (\Exception $e) {
                \Log::error('Coupon creation error: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء الكود: ' . $e->getMessage()
                ], 500);
            }
        });
        
        Route::put('/{id}', function(\Illuminate\Http\Request $request, $id) {
            try {
                $coupon = \App\Models\DiscountCoupon::findOrFail($id);
                
                $validated = $request->validate([
                    'is_active' => 'sometimes|boolean',
                    'discount_percentage' => 'sometimes|numeric|min:0.01|max:100',
                    'purpose' => 'sometimes|nullable|string',
                    'max_uses' => 'sometimes|nullable|integer|min:1',
                    'expires_at' => 'sometimes|nullable|date',
                ]);
                
                $coupon->update($validated);
                
                return response()->json([
                    'success' => true,
                    'coupon' => $coupon,
                    'message' => 'تم تحديث الكود بنجاح'
                ]);
            } catch (\Exception $e) {
                \Log::error('Coupon update error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث الكود'
                ], 500);
            }
        });
        
        Route::delete('/{id}', function($id) {
            try {
                $coupon = \App\Models\DiscountCoupon::findOrFail($id);
                $coupon->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الكود بنجاح'
                ]);
            } catch (\Exception $e) {
                \Log::error('Coupon delete error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف الكود'
                ], 500);
            }
        });
        
        Route::get('/{id}/usage', function($id) {
            try {
                $coupon = \App\Models\DiscountCoupon::findOrFail($id);
                $usages = \App\Models\CouponUsage::where('coupon_id', $id)
                    ->with('user')
                    ->orderBy('used_at', 'desc')
                    ->get();
                
                return response()->json([
                    'coupon' => $coupon,
                    'usages' => $usages
                ]);
            } catch (\Exception $e) {
                \Log::error('Coupon usage error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحميل الاستخدامات'
                ], 500);
            }
        });
    });

    Route::post('/traders/{trader}/request-info', [\App\Http\Controllers\Api\SupportApprovalsController::class, 'requestInfoTrader']);

    Route::get('/trader-products/pending', [\App\Http\Controllers\Api\SupportApprovalsController::class, 'pendingTraderProducts']);
    Route::post('/trader-products/{product}/approve', [\App\Http\Controllers\Api\SupportApprovalsController::class, 'approveTraderProduct']);
    Route::post('/trader-products/{product}/reject', [\App\Http\Controllers\Api\SupportApprovalsController::class, 'rejectTraderProduct']);
    Route::post('/trader-products/{product}/request-changes', [\App\Http\Controllers\Api\SupportApprovalsController::class, 'requestChangesTraderProduct']);
});

// Create test orders for driver supervisor
Route::get('/create-test-orders', function () {
    $user = \App\Models\User::first();
    if (! $user) {
        return 'No users found. Please create a user first.';
    }

    $orders = [
        [
            'order_number' => 'ORD-TEST-'.rand(1000, 9999),
            'user_id' => $user->id,
            'recipient_name' => 'ط·آ·ط¢آ£ط·آ·ط¢آ­ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ¯ ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ­ط·آ¸أ¢â‚¬آ¦ط·آ¸ط«â€ ط·آ·ط¢آ¯',
            'phone' => '0912345678',
            'village' => 'ط·آ·ط¢آ¯ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ´ط·آ¸أ¢â‚¬ع‘ - ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ²ط·آ·ط¢آ©',
            'address_note' => 'ط·آ·ط¢آ¨ط·آ¸أ¢â‚¬آ ط·آ·ط¢آ§ط·آ·ط·إ’ ط·آ·ط¢آ±ط·آ¸أ¢â‚¬ع‘ط·آ¸أ¢â‚¬آ¦ 5ط·آ·ط¥â€™ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ·ط·آ·ط¢آ§ط·آ·ط¢آ¨ط·آ¸أ¢â‚¬ع‘ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ«ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ«',
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
            'order_number' => 'ORD-TEST-'.rand(1000, 9999),
            'user_id' => $user->id,
            'recipient_name' => 'ط·آ¸ط¸آ¾ط·آ·ط¢آ§ط·آ·ط¢آ·ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ© ط·آ·ط¢آ¹ط·آ¸أ¢â‚¬â€چط·آ¸ط¸آ¹',
            'phone' => '0923456789',
            'village' => 'ط·آ·ط¢آ­ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ¨ - ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ´ط·آ¸أ¢â‚¬طŒط·آ·ط¢آ¨ط·آ·ط¢آ§ط·آ·ط·إ’',
            'address_note' => 'ط·آ·ط¢آ´ط·آ·ط¢آ§ط·آ·ط¢آ±ط·آ·ط¢آ¹ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ¬ط·آ·ط¢آ§ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ¹ط·آ·ط¢آ©ط·آ·ط¥â€™ ط·آ·ط¢آ¨ط·آ¸أ¢â‚¬آ ط·آ·ط¢آ§ط·آ·ط·إ’ 12',
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
            'order_number' => 'ORD-TEST-'.rand(1000, 9999),
            'user_id' => $user->id,
            'recipient_name' => 'ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ­ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ¯ ط·آ·ط¢آ­ط·آ·ط¢آ³ط·آ¸أ¢â‚¬آ ',
            'phone' => '0934567890',
            'village' => 'ط·آ·ط¢آ­ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آµ - ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ¸ط«â€ ط·آ·ط¢آ¹ط·آ·ط¢آ±',
            'address_note' => 'ط·آ¸أ¢â‚¬ع‘ط·آ·ط¢آ±ط·آ·ط¢آ¨ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ³ط·آ·ط¢آ¬ط·آ·ط¢آ¯ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ¸ط¦â€™ط·آ·ط¢آ¨ط·آ¸ط¸آ¹ط·آ·ط¢آ±',
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
            'product_name' => 'ط·آ¸أ¢â‚¬آ¦ط·آ¸أ¢â‚¬آ ط·آ·ط¹آ¾ط·آ·ط¢آ¬ ط·آ·ط¹آ¾ط·آ·ط¢آ¬ط·آ·ط¢آ±ط·آ¸ط¸آ¹ط·آ·ط¢آ¨ط·آ¸ط¸آ¹',
            'quantity' => 2,
            'price' => 25.00,
            'subtotal' => 50.00,
        ]);
    }

    // Create test drivers using Driver model
    $driversData = [
        ['name' => 'ط·آ·ط¢آ£ط·آ·ط¢آ­ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ¯ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ³ط·آ·ط¢آ§ط·آ·ط¢آ¦ط·آ¸أ¢â‚¬ع‘', 'phone' => '0911111111', 'email' => 'driver1@test.com', 'license_number' => 'LIC001'],
        ['name' => 'ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ­ط·آ¸أ¢â‚¬آ¦ط·آ·ط¢آ¯ ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ³ط·آ·ط¢آ§ط·آ·ط¢آ¦ط·آ¸أ¢â‚¬ع‘', 'phone' => '0922222222', 'email' => 'driver2@test.com', 'license_number' => 'LIC002'],
    ];

    foreach ($driversData as $driverData) {
        $existing = \App\Models\Driver::where('email', $driverData['email'])->first();
        if (! $existing) {
            \App\Models\Driver::create([
                'name' => $driverData['name'],
                'phone' => $driverData['phone'],
                'email' => $driverData['email'],
                'license_number' => $driverData['license_number'],
                'vehicle_type' => 'motorcycle',
                'vehicle_plate' => 'SYR-'.rand(1000, 9999),
                'status' => 'available',
                'is_active' => true,
                'rating' => 5.00,
                'total_deliveries' => 0,
            ]);
        }
    }

    return 'ط£آ¢ط¥â€œأ¢â‚¬آ¦ ط·آ·ط¹آ¾ط·آ¸أ¢â‚¬آ¦ ط·آ·ط¢آ¥ط·آ¸أ¢â‚¬آ ط·آ·ط¢آ´ط·آ·ط¢آ§ط·آ·ط·إ’ '.count($created).' ط·آ·ط¢آ·ط·آ¸أ¢â‚¬â€چط·آ·ط¢آ¨ط·آ·ط¢آ§ط·آ·ط¹آ¾ ط·آ·ط¹آ¾ط·آ·ط¢آ¬ط·آ·ط¢آ±ط·آ¸ط¸آ¹ط·آ·ط¢آ¨ط·آ¸ط¸آ¹ط·آ·ط¢آ©: '.implode(', ', $created).'<br><br><a href="/delivery/supervisor/dashboard" style="background:#ff6b35;color:white;padding:1rem 2rem;border-radius:8px;text-decoration:none;font-weight:bold;">ط·آ·ط¢آ§ط·آ¸أ¢â‚¬آ ط·آ·ط¹آ¾ط·آ¸أ¢â‚¬ع‘ط·آ¸أ¢â‚¬â€چ ط·آ·ط¢آ¥ط·آ¸أ¢â‚¬â€چط·آ¸أ¢â‚¬آ° ط·آ¸أ¢â‚¬â€چط·آ¸ط«â€ ط·آ·ط¢آ­ط·آ·ط¢آ© ط·آ·ط¢آ§ط·آ¸أ¢â‚¬â€چط·آ·ط¹آ¾ط·آ·ط¢آ­ط·آ¸ط¦â€™ط·آ¸أ¢â‚¬آ¦</a>';
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

Route::prefix('trader')->name('trader.')->group(function () {
        Route::middleware('guest:trader')->group(function () {
            Route::get('/login', [\App\Http\Controllers\Auth\TraderAuthController::class, 'showLoginForm'])->name('login.form');
            Route::post('/login', [\App\Http\Controllers\Auth\TraderAuthController::class, 'login'])->name('login');
            Route::get('/register', [\App\Http\Controllers\Auth\TraderAuthController::class, 'showRegisterForm'])->name('register.form');
            Route::post('/register', [\App\Http\Controllers\Auth\TraderAuthController::class, 'register'])->name('register');
            
            // Trader registration verification routes
            Route::post('/check-email', [\App\Http\Controllers\Auth\TraderAuthController::class, 'checkEmailAvailability'])->name('check-email');
            Route::post('/send-otp', [\App\Http\Controllers\Auth\TraderAuthController::class, 'sendOtp'])->name('send-otp');
            Route::post('/verify-otp', [\App\Http\Controllers\Auth\TraderAuthController::class, 'verifyOtp'])->name('verify-otp');
        });
    Route::middleware(['auth:trader', 'role:store_owner'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\TraderAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [\App\Http\Controllers\Trader\TraderDashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [\App\Http\Controllers\Trader\TraderProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [\App\Http\Controllers\Trader\TraderProductController::class, 'create'])->name('products.create');
        Route::get('/categories/{category}/attributes', [\App\Http\Controllers\Trader\TraderProductController::class, 'categoryAttributes'])->name('categories.attributes');
        Route::post('/products', [\App\Http\Controllers\Trader\TraderProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [\App\Http\Controllers\Trader\TraderProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\Trader\TraderProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Trader\TraderProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/inventory', [\App\Http\Controllers\Trader\TraderProductController::class, 'inventory'])->name('inventory');
        Route::put('/inventory/{product}', [\App\Http\Controllers\Trader\TraderProductController::class, 'updateInventory'])->name('inventory.update');
        Route::get('/sales', [\App\Http\Controllers\Trader\TraderProductController::class, 'sales'])->name('sales');
        Route::post('/logout', [\App\Http\Controllers\Auth\TraderAuthController::class, 'logout'])->name('logout');
    });
});

Route::prefix('dashboard/vendor')->name('dashboard.vendor.')->middleware(['web', 'auth:employee,trader', 'store.owner'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Dashboard\VendorController::class, 'index'])->name('index');
    Route::get('/orders', [\App\Http\Controllers\Dashboard\VendorController::class, 'orders'])->name('orders');
    Route::get('/sales-forecasts', [\App\Http\Controllers\Dashboard\VendorController::class, 'salesForecasts'])->name('sales-forecasts');
    Route::get('/product-performance-metrics', [\App\Http\Controllers\Dashboard\VendorController::class, 'productPerformanceMetrics'])->name('product-performance-metrics');
    Route::get('/products', [\App\Http\Controllers\Dashboard\VendorController::class, 'products'])->name('products');
    Route::post('/products', [\App\Http\Controllers\Dashboard\VendorController::class, 'createProduct'])->name('products.create');
    Route::put('/products/{product}', [\App\Http\Controllers\Dashboard\VendorController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Dashboard\VendorController::class, 'deleteProduct'])->name('products.delete');
    Route::post('/stock/{product}', [\App\Http\Controllers\Dashboard\VendorController::class, 'updateStock'])->name('stock.update');
    Route::post('/restock/{product}', [\App\Http\Controllers\Dashboard\VendorController::class, 'restock'])->name('restock');
    Route::get('/purchase-orders', [\App\Http\Controllers\Dashboard\VendorController::class, 'purchaseOrders'])->name('purchase-orders');
    Route::post('/purchase-orders', [\App\Http\Controllers\Dashboard\VendorController::class, 'createPurchaseOrder'])->name('purchase-orders.create');
    Route::post('/purchase-orders/{purchaseOrder}/receive', [\App\Http\Controllers\Dashboard\VendorController::class, 'receivePurchaseOrder'])->name('purchase-orders.receive');
});

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

require __DIR__.'/auth.php';

Route::middleware(['web', 'auth:employee'])->group(function () {
    Route::middleware('dashboard.role:admin')->group(function () {
        Route::get('/admin/dashboard', fn () => redirect()->route('dashboard.admin.index'));
        Route::get('/admin/gifts', fn () => redirect()->route('dashboard.admin.gifts'));
        Route::get('/admin/mart', fn () => redirect()->route('dashboard.admin.mart.index'));
    });

    Route::middleware('dashboard.role:it')->get('/it/dashboard', fn () => redirect()->route('dashboard.it.index'));
    Route::middleware('dashboard.role:hr')->get('/hr/dashboard', fn () => redirect()->route('dashboard.hr.index'));
    Route::middleware('dashboard.role:cs')->get('/support/dashboard', fn () => redirect()->route('dashboard.cs.index'));
    Route::middleware('dashboard.role:delivery_supervisor')->get('/driver-supervisor/dashboard', fn () => redirect()->route('dashboard.supervisor.index'));
    Route::middleware('dashboard.role:finance')->get('/finance/dashboard', fn () => redirect()->route('dashboard.finance.index'));
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (6-Dashboard System)
|--------------------------------------------------------------------------
|
| Dashboard routes are now loaded by RouteServiceProvider with proper
| middleware and prefixes. No need to require here.
|
*/
