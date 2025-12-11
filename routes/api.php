<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\CountryController;

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

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
// IMPORTANT: search route must come BEFORE the /products/{id} route
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/category/{categoryId}', [ProductController::class, 'byCategory']);

// Cart (Protected)
Route::middleware('auth:sanctum')->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'addItem']);
    Route::put('/items/{itemId}', [CartController::class, 'updateItem']);
    Route::delete('/items/{itemId}', [CartController::class, 'removeItem']);
    Route::delete('/clear', [CartController::class, 'clear']);
});

// Countries
Route::get('/countries', [CountryController::class, 'index']);

// User Activity & Personalization
use App\Http\Controllers\UserActivityController;
Route::post('/activity/track', [UserActivityController::class, 'track']);
Route::get('/activity/recommendations', [UserActivityController::class, 'getRecommendations']);

