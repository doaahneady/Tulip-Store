<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Handle different guard redirects
                if ($guard === 'employee') {
                    return redirect()->route('employee.dashboard');
                }
                if ($guard === 'trader') {
                    return redirect()->route('dashboard.vendor.index');
                }

                return redirect('/');
            }
        }

        return $next($request);
    }
}
