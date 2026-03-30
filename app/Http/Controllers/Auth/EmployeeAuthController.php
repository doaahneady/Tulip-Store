<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SecurityAuditLog;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class EmployeeAuthController extends Controller
{
    /**
     * Show employee login form
     */
    public function showLoginForm()
    {
        return view('auth.employee-login');
    }

    /**
     * Handle employee login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $loginValue = $request->email;
        
        // First try to find employee by email, employee_code, or employee_id
        $employee = Employee::where('email', $loginValue)
            ->orWhere('employee_code', $loginValue)
            ->orWhere('employee_id', $loginValue)
            ->first();
        
        // If not found, check if it's a driver username
        if (!$employee) {
            $user = \App\Models\User::where('username', $loginValue)->first();
            if ($user) {
                // Find employee record linked to this user
                $employee = Employee::where('user_id', $user->id)->first();
            }
        }

        if (! $employee || ! Hash::check($request->password, $employee->password)) {
            if (Schema::hasTable('system_logs')) {
                $data = [
                    'level' => 'warning',
                    'action' => 'employee_login_failed',
                    'message' => 'Employee login failed',
                    'user' => (string) $loginValue,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ];
                if (Schema::hasColumn('system_logs', 'metadata')) {
                    $data['metadata'] = ['identifier' => (string) $loginValue];
                }
                SystemLog::create($data);
            }
            if (Schema::hasTable('security_audit_logs')) {
                $data = [
                    'event_type' => 'login_attempt',
                    'user_type' => Employee::class,
                    'user_id' => 0,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'failed',
                    'description' => 'Employee login failed',
                    'risk_level' => 'low',
                ];
                if (Schema::hasColumn('security_audit_logs', 'metadata')) {
                    $data['metadata'] = ['identifier' => (string) $loginValue];
                }
                SecurityAuditLog::create($data);
            }

            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($employee->status !== 'active') {
            if (Schema::hasTable('system_logs')) {
                $data = [
                    'level' => 'warning',
                    'action' => 'employee_login_blocked',
                    'message' => 'Employee login blocked (inactive account)',
                    'user' => (string) ($employee->email ?? $loginValue),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ];
                if (Schema::hasColumn('system_logs', 'metadata')) {
                    $data['metadata'] = ['employee_id' => $employee->id];
                }
                SystemLog::create($data);
            }
            if (Schema::hasTable('security_audit_logs')) {
                $data = [
                    'event_type' => 'login_attempt',
                    'user_type' => Employee::class,
                    'user_id' => $employee->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'blocked',
                    'description' => 'Employee login blocked (inactive account)',
                    'risk_level' => 'low',
                ];
                if (Schema::hasColumn('security_audit_logs', 'metadata')) {
                    $data['metadata'] = ['employee_id' => $employee->id];
                }
                SecurityAuditLog::create($data);
            }

            throw ValidationException::withMessages([
                'login' => ['Your account is not active. Please contact HR.'],
            ]);
        }

        // Login the employee using the 'employee' guard
        Auth::guard('employee')->login($employee, $request->boolean('remember'));

        // Update login tracking
        $employee->updateLastLogin();

        $request->session()->regenerate();

        if (Schema::hasTable('system_logs')) {
            $data = [
                'level' => 'info',
                'action' => 'employee_login_success',
                'message' => 'Employee logged in',
                'user' => (string) ($employee->email ?? $employee->employee_code ?? $employee->employee_id ?? $employee->id),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];
            if (Schema::hasColumn('system_logs', 'metadata')) {
                $data['metadata'] = ['employee_id' => $employee->id];
            }
            SystemLog::create($data);
        }
        if (Schema::hasTable('security_audit_logs')) {
            $data = [
                'event_type' => 'login_attempt',
                'user_type' => Employee::class,
                'user_id' => $employee->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'description' => 'Employee logged in',
                'risk_level' => 'low',
            ];
            if (Schema::hasColumn('security_audit_logs', 'metadata')) {
                $data['metadata'] = ['employee_id' => $employee->id];
            }
            SecurityAuditLog::create($data);
        }

        // Redirect to appropriate dashboard based on role
        return $this->redirectToDashboard($employee);
    }

    /**
     * Handle employee logout
     */
    public function logout(Request $request)
    {
        $employee = auth('employee')->user();

        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($employee && Schema::hasTable('system_logs')) {
            $data = [
                'level' => 'info',
                'action' => 'employee_logout',
                'message' => 'Employee logged out',
                'user' => (string) ($employee->email ?? $employee->employee_code ?? $employee->employee_id ?? $employee->id),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];
            if (Schema::hasColumn('system_logs', 'metadata')) {
                $data['metadata'] = ['employee_id' => $employee->id];
            }
            SystemLog::create($data);
        }
        if ($employee && Schema::hasTable('security_audit_logs')) {
            $data = [
                'event_type' => 'employee_logout',
                'user_type' => Employee::class,
                'user_id' => $employee->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'description' => 'Employee logged out',
                'risk_level' => 'low',
            ];
            if (Schema::hasColumn('security_audit_logs', 'metadata')) {
                $data['metadata'] = ['employee_id' => $employee->id];
            }
            SecurityAuditLog::create($data);
        }

        return redirect()->route('employee.login');
    }

    /**
     * Redirect employee to appropriate dashboard based on their role
     */
    private function redirectToDashboard(Employee $employee)
    {
        $dashboards = $employee->available_dashboards ?? [];
        if (is_array($dashboards) && count($dashboards) === 1) {
            return redirect()->route($dashboards[0]['route']);
        }

        return redirect()->route('employee.dashboard');
    }

    /**
     * Show employee dashboard selection page
     */
    public function dashboard()
    {
        $employee = auth('employee')->user();
        $availableDashboards = $employee?->available_dashboards ?? [];
        if (is_array($availableDashboards)) {
            $availableDashboards = array_values(array_filter($availableDashboards, function ($d) {
                return ($d['route'] ?? null) !== 'dashboard.vendor.index';
            }));
        }

        return view('auth.employee-dashboard-selection', [
            'availableDashboards' => $availableDashboards,
            'employee' => $employee,
        ]);
    }

    /**
     * Handle trader (store owner) login
     */
    public function traderLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = Employee::where('email', $request->email)->first();

        if (! $employee || ! Hash::check($request->password, $employee->password)) {
            return back()->withErrors([
                'email' => 'بيانات الدخول غير صحيحة',
            ])->withInput($request->only('email'));
        }

        if ($employee->status !== 'active') {
            return back()->withErrors([
                'email' => 'حسابك غير مفعل. يرجى التواصل مع الإدارة.',
            ])->withInput($request->only('email'));
        }

        // Check if user is a trader
        if (! $employee->is_trader) {
            return back()->withErrors([
                'email' => 'هذا الحساب ليس حساب تاجر. يرجى استخدام بوابة الموظفين.',
            ])->withInput($request->only('email'));
        }

        // Login the employee using the 'employee' guard
        Auth::guard('employee')->login($employee, $request->boolean('remember'));

        // Update login tracking
        $employee->updateLastLogin();

        $request->session()->regenerate();

        // Redirect to main dashboard
        return redirect()->route('dashboard.main');
    }
}
