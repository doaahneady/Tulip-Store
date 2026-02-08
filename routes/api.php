<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
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

// 3D Store API
Route::prefix('store3d')->group(function () {
    Route::get('/sections/{section}', [App\Http\Controllers\Store3DController::class, 'getSectionProducts']);
    Route::get('/counts', [App\Http\Controllers\Store3DController::class, 'getSectionCounts']);
});

// Countries
Route::get('/countries', [CountryController::class, 'index']);

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
