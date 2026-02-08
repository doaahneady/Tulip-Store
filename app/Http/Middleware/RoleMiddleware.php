<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $guard = null;
        if (Auth::guard('web')->check()) {
            $guard = 'web';
        } elseif (Auth::guard('trader')->check()) {
            $guard = 'trader';
        }

        if (! $guard) {
            if ($request->is('trader/*')) {
                return redirect()->route('trader.login.form');
            }

            return redirect()->route('login');
        }

        $user = Auth::guard($guard)->user();
        $allowedRoles = explode(',', $roles);

        // Check if user has any of the required roles
        if (! $user->hasAnyRole($allowedRoles)) {
            abort(403, 'Unauthorized access. Required roles: '.implode(', ', $allowedRoles));
        }

        return $next($request);
    }
}
