<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        if ($request->routeIs('trader.*') || $request->is('trader/*')) {
            return route('trader.login.form');
        }
        if ($request->routeIs('dashboard.*') || $request->is('dashboard/*') || $request->is('employee/*')) {
            if (Auth::guard('trader')->check()) {
                return route('dashboard.vendor.index');
            }

            return route('employee.login');
        }

        return route('login');
    }
}
