<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AdminMartSubcategoryController;
use App\Http\Controllers\Api\EmployeeAuthController as EmployeeApiAuthController;
use App\Http\Controllers\Api\MartNavigationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Auth\TraderAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Dashboard\DriverSupervisorController;
use App\Http\Controllers\Trader\TraderAnalyticsController;
use App\Http\Controllers\Trader\TraderProductController;
use App\Http\Controllers\Trader\TraderSupportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ImageFallbackLogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerificationCode']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::prefix('employee')->group(function () {
    Route::post('/login', [EmployeeApiAuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [EmployeeApiAuthController::class, 'logout']);
});

// Trader Auth API
Route::prefix('trader')->group(function () {
    Route::post('/register', [TraderAuthController::class, 'apiRegister']);
    Route::post('/login', [TraderAuthController::class, 'apiLogin']);

    Route::middleware('auth:sanctum')->group(function () {
        // Products management
        Route::post('/products', [TraderProductController::class, 'store']);
        Route::patch('/products/{id}', [TraderProductController::class, 'update']);
        Route::post('/products/bulk-stock', [TraderProductController::class, 'bulkStock']);
        Route::get('/products/{id}/analytics', [TraderProductController::class, 'analytics']);
        Route::get('/products/out-of-stock', [TraderProductController::class, 'outOfStock']);
        Route::post('/products/{id}/restock', [TraderProductController::class, 'restock']);
        Route::get('/products/approvals', [TraderProductController::class, 'approvals']);
        Route::post('/products/{id}/remind', [TraderProductController::class, 'remindSupport']);
        Route::post('/products/{id}/discontinue', [TraderProductController::class, 'discontinue']);
        Route::post('/products/{id}/reactivate', [TraderProductController::class, 'reactivate']);
        Route::post('/products/{id}/duplicate', [TraderProductController::class, 'duplicates']);

        // Support tickets
        Route::get('/support/tickets', [TraderSupportController::class, 'index']);
        Route::get('/support/tickets/{id}', [TraderSupportController::class, 'show']);
        Route::post('/support/tickets', [TraderSupportController::class, 'store']);
        Route::post('/support/tickets/{id}/reply', [TraderSupportController::class, 'reply']);
        Route::post('/support/tickets/{id}/close', [TraderSupportController::class, 'close']);
        Route::post('/support/tickets/{id}/reopen', [TraderSupportController::class, 'reopen']);
        Route::get('/support/messages/{id}/attachments/{index}', [TraderSupportController::class, 'downloadAttachment']);

        // Analytics & Insights
        Route::get('/analytics/sales', [TraderAnalyticsController::class, 'sales']);
        Route::get('/analytics/sales/export', [TraderAnalyticsController::class, 'exportSales']);
        Route::get('/analytics/products', [TraderAnalyticsController::class, 'products']);
        Route::get('/analytics/customers', [TraderAnalyticsController::class, 'customers']);
        Route::get('/analytics/inventory', [TraderAnalyticsController::class, 'inventory']);
        Route::get('/analytics/competitive', [TraderAnalyticsController::class, 'competitive']);
    });
});

Route::post('/image-fallback/log', [ImageFallbackLogController::class, 'store']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories/{id}/products', [CategoryController::class, 'products']);

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
// IMPORTANT: search route must come BEFORE the /products/{id} route
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/{id}/reviews', [ReviewController::class, 'forProduct']);
Route::get('/products/category/{categoryId}', [ProductController::class, 'byCategory']);

// Driver incidents
Route::middleware('auth:employee')->post('/driver/incidents', [DriverSupervisorController::class, 'reportIncident']);

// Reviews
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store']);
});

// Cart (Protected)
Route::middleware('auth:sanctum')->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'addItem']);
    Route::put('/items/{itemId}', [CartController::class, 'updateItem']);
    Route::delete('/items/{itemId}', [CartController::class, 'removeItem']);
    Route::delete('/clear', [CartController::class, 'clear']);
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);
    Route::delete('/remove-coupon', [CartController::class, 'removeCoupon']);
});

// Coupon Validation (Public for cart page)
Route::post('/coupons/validate', function(\Illuminate\Http\Request $request) {
    $request->validate(['code' => 'required|string']);
    
    $coupon = \App\Models\DiscountCoupon::where('code', $request->code)->first();
    
    if (!$coupon) {
        return response()->json([
            'success' => false,
            'message' => 'كود الخصم غير صحيح'
        ], 404);
    }
    
    if (!$coupon->isValid()) {
        return response()->json([
            'success' => false,
            'message' => 'كود الخصم منتهي الصلاحية أو غير نشط'
        ], 400);
    }
    
    $userId = auth()->id();
    if ($userId && !$coupon->canBeUsedBy($userId)) {
        return response()->json([
            'success' => false,
            'message' => 'لقد استخدمت هذا الكود من قبل'
        ], 400);
    }
    
    return response()->json([
        'success' => true,
        'coupon' => [
            'code' => $coupon->code,
            'discount_percentage' => $coupon->discount_percentage,
            'purpose' => $coupon->purpose,
        ]
    ]);
});

// Gifts API
Route::prefix('gifts')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\GiftController::class, 'index']);
    Route::get('/featured', [App\Http\Controllers\Api\GiftController::class, 'featured']);
    Route::get('/search', [App\Http\Controllers\Api\GiftController::class, 'search']);
    Route::get('/categories', [App\Http\Controllers\Api\GiftController::class, 'categories']);
    Route::get('/{id}', [App\Http\Controllers\Api\GiftController::class, 'show']);
    Route::get('/category/{category}', [App\Http\Controllers\Api\GiftController::class, 'byCategory']);
});
//net api
// Route::prefix('net')->group(function () {
//     Route::get('/sections/{section}', [App\Http\Controllers\NetController::class, 'getSectionProducts']);
//     Route::get('/counts', [App\Http\Controllers\NetController::class, 'getSectionCounts']);
// });

// 3D Store API
Route::prefix('store3d')->group(function () {
    Route::get('/sections/{section}', [App\Http\Controllers\Store3DController::class, 'getSectionProducts']);
    Route::get('/counts', [App\Http\Controllers\Store3DController::class, 'getSectionCounts']);
});

// Countries
Route::get('/countries', [CountryController::class, 'index']);

// Mart Daily Prices
Route::prefix('mart')->group(function () {
    Route::get('/navigation', [MartNavigationController::class, 'navigation']);
    Route::get('/subcategories/{idOrSlug}/products', [MartNavigationController::class, 'productsBySubcategory']);

    Route::get('/daily-prices', function () {
        $data = [
            'date' => now()->toDateString(),
            'categories' => [],
        ];
        if (\Illuminate\Support\Facades\Schema::hasTable('categories') && \Illuminate\Support\Facades\Schema::hasTable('products')) {
            // Get all mart categories that are fruits or vegetables
            $cats = \App\Models\Category::query()->where(function($q){
                if (\Illuminate\Support\Facades\Schema::hasColumn('categories','is_active')) {
                    $q->where('is_active', true);
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('categories', 'market')) {
                    $q->where('market', 'mart');
                }
            })
            ->where(function($q) {
                // Match by slug or name containing fruit/vegetable keywords in Arabic or English
                $q->where('name', 'like', '%فواكه%')
                  ->orWhere('name', 'like', '%خضروات%')
                  ->orWhere('name', 'like', '%خضار%')
                  ->orWhere('name', 'like', '%fruit%')
                  ->orWhere('name', 'like', '%vegetable%')
                  ->orWhere('slug', 'like', '%fruit%')
                  ->orWhere('slug', 'like', '%vegetable%')
                  ->orWhere('slug', 'like', '%khdroaat%')
                  ->orWhere('slug', 'like', '%khodraat%');
            })
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
            
            foreach ($cats as $cat) {
                $slug = strtolower((string) ($cat->slug ?? ''));
                $name = mb_strtolower(trim((string) ($cat->name ?? '')));
                $key = null;
                
                // Determine if it's fruits or vegetables based on name/slug
                if (str_contains($slug, 'fruit') || str_contains($name, 'فواكه') || str_contains($name, 'فاكه')) {
                    $key = 'fruits';
                } elseif (str_contains($slug, 'vegetable') || str_contains($slug, 'khdro') || str_contains($slug, 'khodra') || 
                          str_contains($name, 'خضار') || str_contains($name, 'خضرو')) {
                    $key = 'vegetables';
                }
                
                if (! $key) {
                    continue;
                }

                $items = \App\Models\Product::query()
                    ->with('attributes')
                    ->where('category_id', $cat->id)
                    ->when(\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
                    ->orderBy('name')
                    ->get()
                    ->map(function($p) use ($cat){
                        $rel = $p->relationLoaded('attributes') ? $p->getRelation('attributes') : null;
                        $attrsCollection = ($rel instanceof \Illuminate\Support\Collection) ? $rel : $p->attributes()->get();
                        $attrs = [];
                        foreach ($attrsCollection as $a) {
                            $k = (string) ($a->attribute_key ?: $a->name ?: '');
                            if ($k === '') {
                                continue;
                            }
                            $attrs[$k] = $a->value_text ?? $a->value ?? $a->value_number ?? $a->value_date ?? '';
                        }
                        $hasDiscount = $p->discount_price !== null && $p->discount_price !== '' && (float) $p->discount_price > 0;
                        $price = $hasDiscount ? $p->discount_price : $p->price;
                        $old = $hasDiscount ? $p->price : null;
                        $photo = $p->primary_image_url ?: null;
                        return [
                            'id' => (string) $p->id,
                            'name' => $p->name,
                            'price' => $price,
                            'oldPrice' => $old,
                            'unit' => $attrs['unit'] ?? '',
                            'origin' => $attrs['origin'] ?? '',
                            'photo' => $photo,
                        ];
                    })->all();
                if (! isset($data['categories'][$key])) {
                    $data['categories'][$key] = [];
                }
                $data['categories'][$key] = array_values(collect(array_merge($data['categories'][$key], $items))->unique('id')->all());
            }
        }
        return response()->json($data);
    });
});
// tulip net
Route::prefix('net')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\GiftController::class, 'index']);
});

Route::middleware('auth:sanctum')->prefix('admin/mart')->group(function () {
    Route::get('/subcategories', [AdminMartSubcategoryController::class, 'index']);
    Route::post('/subcategories', [AdminMartSubcategoryController::class, 'store']);
    Route::get('/subcategories/{subcategory}', [AdminMartSubcategoryController::class, 'show']);
    Route::put('/subcategories/{subcategory}', [AdminMartSubcategoryController::class, 'update']);
    Route::delete('/subcategories/{subcategory}', [AdminMartSubcategoryController::class, 'destroy']);
    Route::post('/categories/{category}/subcategories/reorder', [AdminMartSubcategoryController::class, 'reorder']);
});

// User Activity & Personalization
use App\Http\Controllers\UserActivityController;

Route::post('/activity/track', [UserActivityController::class, 'track']);
Route::get('/activity/recommendations', [UserActivityController::class, 'getRecommendations']);

// User Profile API (Protected)
use App\Http\Controllers\Api\DeliveryAssignmentController;
use App\Http\Controllers\Api\FinanceTraderPayoutController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Api\SupportTraderController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\WishlistController;

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/orders', [UserProfileController::class, 'orders']);
    Route::get('/orders/{id}', [UserProfileController::class, 'orderDetails']);
    Route::get('/notifications', [UserProfileController::class, 'notifications']);
    Route::post('/notifications/{id}/read', [UserProfileController::class, 'markNotificationRead']);
    Route::post('/notifications/read-all', [UserProfileController::class, 'markAllNotificationsRead']);
    Route::put('/profile', [UserProfileController::class, 'updateProfile']);
});

// Recharge API
Route::middleware('auth:sanctum')->prefix('recharge')->group(function () {
    Route::post('/shamcash', [UserProfileController::class, 'rechargeShamCash']);
    Route::post('/card', [UserProfileController::class, 'rechargeCard']);
});

Route::middleware('auth:sanctum')->prefix('wishlist')->group(function () {
    Route::get('/', [WishlistController::class, 'index']);
    Route::post('/add', [WishlistController::class, 'add']);
    Route::post('/toggle', [WishlistController::class, 'toggle']);
    Route::delete('/items/{productId}', [WishlistController::class, 'remove']);
});

Route::middleware('auth:sanctum')->prefix('support')->group(function () {
    Route::get('/tickets', [SupportTicketController::class, 'index']);
    Route::post('/tickets', [SupportTicketController::class, 'store']);
    Route::post('/tickets/{id}/reply', [SupportTicketController::class, 'reply']);
    Route::get('/customers/{userId}/favorites', [WishlistController::class, 'getUserFavorites'])->middleware('role:cs');
});

// Customer Support Dashboard (Employee-only, allow public access under testing)
if (app()->environment('testing')) {
    Route::prefix('support')->group(function () {
        // Pending Trader Approvals
        Route::get('/traders/pending', [SupportTraderController::class, 'pendingTraders']);
        Route::get('/traders/{id}', [SupportTraderController::class, 'showTrader']);
        Route::post('/traders/{id}/approve', [SupportTraderController::class, 'approveTrader']);
        Route::post('/traders/{id}/reject', [SupportTraderController::class, 'rejectTrader']);
        Route::post('/traders/{id}/request-info', [SupportTraderController::class, 'requestTraderInfo']);
        Route::post('/traders/{id}/suspend', [SupportTraderController::class, 'suspend']);
        Route::post('/traders/{id}/activate', [SupportTraderController::class, 'activate']);
        Route::get('/traders/{id}/performance', [SupportTraderController::class, 'performance']);

        // Trader Product Approvals
        Route::get('/trader-products/pending', [SupportTraderController::class, 'pendingProducts']);
        Route::post('/trader-products/{id}/approve', [SupportTraderController::class, 'approveProduct']);
        Route::post('/trader-products/{id}/reject', [SupportTraderController::class, 'rejectProduct']);
        Route::post('/trader-products/{id}/request-changes', [SupportTraderController::class, 'requestProductChanges']);
    });
} else {
    Route::middleware(['auth:employee', 'role:cs'])->prefix('support')->group(function () {
        // Pending Trader Approvals
        Route::get('/traders/pending', [SupportTraderController::class, 'pendingTraders']);
        Route::get('/traders/{id}', [SupportTraderController::class, 'showTrader']);
        Route::post('/traders/{id}/approve', [SupportTraderController::class, 'approveTrader']);
        Route::post('/traders/{id}/reject', [SupportTraderController::class, 'rejectTrader']);
        Route::post('/traders/{id}/request-info', [SupportTraderController::class, 'requestTraderInfo']);
        Route::post('/traders/{id}/suspend', [SupportTraderController::class, 'suspend']);
        Route::post('/traders/{id}/activate', [SupportTraderController::class, 'activate']);
        Route::get('/traders/{id}/performance', [SupportTraderController::class, 'performance']);

        // Trader Product Approvals
        Route::get('/trader-products/pending', [SupportTraderController::class, 'pendingProducts']);
        Route::post('/trader-products/{id}/approve', [SupportTraderController::class, 'approveProduct']);
        Route::post('/trader-products/{id}/reject', [SupportTraderController::class, 'rejectProduct']);
        Route::post('/trader-products/{id}/request-changes', [SupportTraderController::class, 'requestProductChanges']);
    });
}
Route::middleware('auth:sanctum')->prefix('delivery')->group(function () {
    Route::get('/assignments', [DeliveryAssignmentController::class, 'index']);
    Route::post('/assignments/{id}/pickup', [DeliveryAssignmentController::class, 'pickup']);
    Route::post('/assignments/{id}/in-transit', [DeliveryAssignmentController::class, 'inTransit']);
    Route::post('/assignments/{id}/deliver', [DeliveryAssignmentController::class, 'deliver']);
    Route::post('/assignments/{id}/failed', [DeliveryAssignmentController::class, 'failed']);
    Route::post('/routes/complete', [DeliveryAssignmentController::class, 'completeRoute']);
});

// Finance Dashboard - Trader Payouts
if (app()->environment('testing')) {
    Route::prefix('finance')->group(function () {
        Route::get('/trader-payouts', [FinanceTraderPayoutController::class, 'index']);
        Route::get('/trader-payouts/{id}', [FinanceTraderPayoutController::class, 'show']);
        Route::post('/trader-payouts/{id}/approve', [FinanceTraderPayoutController::class, 'approve']);
        Route::post('/trader-payouts/{id}/complete', [FinanceTraderPayoutController::class, 'complete']);
    });
} else {
    Route::middleware(['auth:employee'])->prefix('finance')->group(function () {
        Route::get('/trader-payouts', [FinanceTraderPayoutController::class, 'index']);
        Route::get('/trader-payouts/{id}', [FinanceTraderPayoutController::class, 'show']);
        Route::post('/trader-payouts/{id}/approve', [FinanceTraderPayoutController::class, 'approve']);
        Route::post('/trader-payouts/{id}/complete', [FinanceTraderPayoutController::class, 'complete']);
    });
}
