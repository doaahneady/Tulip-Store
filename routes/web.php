<?php

use Illuminate\Support\Facades\Route;

// Test route
Route::get('/test', function () {
    return 'Hello from Laravel';
});

// Serve React app via Blade template
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!api\/).*$');
