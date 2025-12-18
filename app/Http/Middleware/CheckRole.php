<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check new RBAC system first
        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles)) {
            return $next($request);
        }
        
        // Fallback to legacy role flags for backward compatibility
        foreach ($roles as $role) {
            // Check for specific role flags
            switch ($role) {
                case 'super_admin':
                case 'admin':
                    if ($user->is_admin) return $next($request);
                    break;
                case 'it_admin':
                case 'devops_engineer':
                case 'it':
                    if ($user->is_it || $user->is_it_super) return $next($request);
                    break;
                case 'it_super':
                    if ($user->is_it_super) return $next($request);
                    break;
                case 'hr_manager':
                case 'hr_coordinator':
                case 'hr':
                    if ($user->is_hr ?? false) return $next($request);
                    break;
                case 'cs':
                    if ($user->is_cs ?? false) return $next($request);
                    break;
                case 'finance_manager':
                case 'accountant':
                case 'finance':
                    if ($user->is_finance ?? false || $user->is_accountant ?? false || $user->is_admin) return $next($request);
                    break;
                case 'driver_supervisor':
                case 'logistics_coordinator':
                case 'delivery_supervisor':
                    if ($user->is_driver_supervisor ?? false) return $next($request);
                    break;
                case 'product_owner':
                case 'store_manager':
                case 'store_owner':
                    if ($user->is_trader ?? false) return $next($request);
                    break;
                case 'driver':
                    // Check if user has driver record
                    if ($user->driver ?? false) return $next($request);
                    break;
            }
            
            // Check legacy role relationship
            if ($user->role && $user->role->name === $role) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access. Required roles: ' . implode(', ', $roles));
    }
}
