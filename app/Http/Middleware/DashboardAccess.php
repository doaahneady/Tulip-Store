<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAccess
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role) {
            switch ($role) {
                case 'admin':
                    if (! $user->is_admin) {
                        abort(403, 'Unauthorized access to admin dashboard');
                    }
                    break;
                case 'it':
                    if (! ($user->is_it || $user->is_it_super || $user->is_admin)) {
                        abort(403, 'Unauthorized access to IT dashboard');
                    }
                    break;
                case 'cs':
                    if (! ($user->is_cs || $user->is_admin)) {
                        abort(403, 'Unauthorized access to Customer Service dashboard');
                    }
                    break;
                case 'hr':
                    if (! ($user->is_hr || $user->is_admin)) {
                        abort(403, 'Unauthorized access to HR dashboard');
                    }
                    break;
                case 'delivery':
                    if (! ($user->is_driver_supervisor || $user->is_admin)) {
                        abort(403, 'Unauthorized access to Delivery dashboard');
                    }
                    break;
                case 'finance':
                    if (! ($user->is_finance || $user->is_accountant || $user->is_admin)) {
                        abort(403, 'Unauthorized access to Finance dashboard');
                    }
                    break;
                case 'store-owner':
                    if (! ($user->is_trader || $user->is_admin)) {
                        abort(403, 'Unauthorized access to Store Owner dashboard');
                    }
                    break;
                default:
                    abort(403, 'Invalid role specified');
            }
        }

        return $next($request);
    }
}
