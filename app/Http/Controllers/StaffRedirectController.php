<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffRedirectController extends Controller
{
    /**
     * Redirect to employee login page
     */
    public function redirect(Request $request)
    {
        // Log the access for debugging
        \Log::info('Staff redirect accessed', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'user_authenticated' => auth()->check(),
            'employee_authenticated' => auth('employee')->check(),
        ]);

        // Clear any existing authentication to prevent conflicts
        if (auth()->check()) {
            auth()->logout();
        }

        if (auth('employee')->check()) {
            auth('employee')->logout();
        }

        // Clear and regenerate session
        $request->session()->flush();
        $request->session()->regenerate();

        // Redirect to employee login
        return redirect('/employee/login')
            ->with('info', 'Please login with your employee credentials');
    }
}
