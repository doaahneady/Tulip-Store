<?php

use Illuminate\Support\Facades\Route;

// SPA: serve React build (index.html) for any non-API route
Route::get('/{any?}', function () {
    return response()->file(public_path('index.html'));
})->where('any', '^(?!api\/).*$');
