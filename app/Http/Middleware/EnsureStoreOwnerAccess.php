<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureStoreOwnerAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = null;

        if (Auth::guard('employee')->check()) {
            $user = Auth::guard('employee')->user();
        } elseif (Auth::guard('trader')->check()) {
            $user = Auth::guard('trader')->user();
        }

        if (! $user) {
            return redirect()->route('trader.login.form');
        }

        if (! ($user->is_trader ?? false)) {
            abort(403);
        }

        return $next($request);
    }
}

