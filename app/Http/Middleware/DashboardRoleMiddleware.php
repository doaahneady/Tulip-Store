<?php

namespace App\Http\Middleware;

use App\Services\DashboardPermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dashboard Role-Based Access Control Middleware
 *
 * Enforces role-based access control for dashboard routes.
 * Supports multiple roles (OR logic) and admin override.
 *
 * @see Requirements 2.1, 2.2, 2.5
 */
class DashboardRoleMiddleware
{
    protected static array $templateExistsCache = [];
    /**
     * Role flag mappings from role name to User model attribute
     */
    protected array $roleFlags = [
        'admin' => 'is_admin',
        'it' => 'is_it',
        'it_super' => 'is_it_super',
        'hr' => 'is_hr',
        'cs' => 'is_cs',
        'finance' => 'is_finance',
        'accountant' => 'is_accountant',
        'delivery_supervisor' => 'is_driver_supervisor',
        'store_owner' => 'is_trader',
    ];

    /**
     * Handle an incoming request.
     *
     * Checks if the authenticated user has at least one of the required roles.
     * Admin users have full access override to all dashboard routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Required roles (OR logic - user needs at least one)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('ip_blacklists')) {
            $blocked = \App\Models\IPBlacklist::where('ip_address', $request->ip())
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                })
                ->exists();
            if ($blocked) {
                abort(403, 'Access denied');
            }
        }

        if (! auth('employee')->check()) {
            return redirect()->route('employee.login');
        }

        $user = auth('employee')->user();
        if ($user) {
            $user->refresh();
        }

        $dashboardKey = $this->resolveDashboardKey($request);
        if ($dashboardKey && class_exists(DashboardPermissionService::class)) {
            $resolved = DashboardPermissionService::resolve($user, $dashboardKey);
            $request->attributes->set('resolved_dashboard_permissions', $resolved);

            $hasEmployeeOverride = \Illuminate\Support\Facades\Schema::hasTable('employee_dashboard_overrides')
                && \App\Models\EmployeeDashboardOverride::query()
                    ->where('employee_id', $user->id)
                    ->where('dashboard_key', $dashboardKey)
                    ->where('is_override', true)
                    ->exists();

            $hasRoleTemplateForDashboard = \Illuminate\Support\Facades\Schema::hasTable('dashboard_role_permissions')
                && $this->hasPermissionTemplatesForDashboard($dashboardKey);

            if (($hasEmployeeOverride || $hasRoleTemplateForDashboard) && ! $resolved['can_view']) {
                abort(403, 'You don\'t have permission to view this dashboard');
            }
        }
        $explicitKeys = method_exists($user, 'getExplicitDashboardKeys') ? $user->getExplicitDashboardKeys() : [];
        if (! empty($explicitKeys)) {
            if (! $dashboardKey || $this->hasExplicitDashboardAccess($user, $dashboardKey)) {
                return $next($request);
            }
            abort(403, 'You don\'t have permission to access this resource');
        }

        // Admin override: only applies in legacy mode (no explicit dashboard permissions configured)
        if ($this->isAdmin($user)) {
            return $next($request);
        }

        // Check if user has at least one of the required roles (OR logic)
        // Requirement 2.1, 2.3: Role checking with multiple role support
        if ($this->hasAnyRole($user, $roles)) {
            return $next($request);
        }

        // Requirement 2.2: Return 403 for unauthorized access
        abort(403, 'You don\'t have permission to access this resource');
    }

    protected function resolveDashboardKey(Request $request): ?string
    {
        $routeName = (string) optional($request->route())->getName();
        if ($routeName !== '') {
            if (str_starts_with($routeName, 'dashboard.admin.mart.')) {
                return 'mart';
            }
            if (str_starts_with($routeName, 'dashboard.supervisor.live-tracking') || str_starts_with($routeName, 'dashboard.supervisor.api.driver-locations')) {
                return 'supervisor_map';
            }
            if (str_starts_with($routeName, 'dashboard.supervisor.order-assignment')) {
                return 'supervisor_orders';
            }
            if (str_starts_with($routeName, 'dashboard.admin.')) {
                return 'admin';
            }
            if (str_starts_with($routeName, 'dashboard.it.')) {
                return 'it';
            }
            if (str_starts_with($routeName, 'dashboard.hr.')) {
                return 'hr';
            }
            if (str_starts_with($routeName, 'dashboard.finance.')) {
                return 'finance';
            }
            if (str_starts_with($routeName, 'dashboard.cs.') || str_starts_with($routeName, 'dashboard.support.')) {
                return 'cs';
            }
            if (str_starts_with($routeName, 'dashboard.supervisor.')) {
                return 'supervisor';
            }
            if (str_starts_with($routeName, 'dashboard.vendor.')) {
                return 'vendor';
            }
            if (str_starts_with($routeName, 'dashboard.driver.')) {
                return 'driver';
            }
        }

        return null;
    }

    protected function hasExplicitDashboardAccess($user, string $dashboardKey): bool
    {
        if (! method_exists($user, 'canAccessDashboard')) {
            return false;
        }
        if ($user->canAccessDashboard($dashboardKey)) {
            return true;
        }

        if ($dashboardKey === 'supervisor_map' || $dashboardKey === 'supervisor_orders') {
            return $user->canAccessDashboard('supervisor');
        }
        if ($dashboardKey === 'mart') {
            return $user->canAccessDashboard('admin');
        }

        return false;
    }

    /**
     * Check if user is an admin
     *
     * @param  mixed  $user
     */
    public function isAdmin($user): bool
    {
        return (bool) ($user->is_admin ?? false);
    }

    /**
     * Check if user has any of the specified roles
     *
     * @param  mixed  $user
     */
    public function hasAnyRole($user, array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($user, $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has a specific role
     *
     * @param  mixed  $user
     */
    public function hasRole($user, string $role): bool
    {
        // Driver dashboard: logistics supervisors OR employees with a Driver profile
        if ($role === 'driver') {
            if ($user->is_driver_supervisor ?? false) {
                return true;
            }
            if (method_exists($user, 'driver') && $user->driver()->exists()) {
                return true;
            }

            return false;
        }

        // Check role flag on user model
        if (isset($this->roleFlags[$role])) {
            $flag = $this->roleFlags[$role];
            if ($user->$flag ?? false) {
                return true;
            }
        }

        // Special case: IT role includes IT super
        if ($role === 'it' && ($user->is_it_super ?? false)) {
            return true;
        }

        // Check role relationship (for Role model based roles)
        if ($user->role && $user->role->name === $role) {
            return true;
        }

        return false;
    }

    /**
     * Get all roles a user has
     *
     * @param  mixed  $user
     */
    public function getUserRoles($user): array
    {
        $roles = [];

        foreach ($this->roleFlags as $role => $flag) {
            if ($user->$flag ?? false) {
                $roles[] = $role;
            }
        }

        // Add role from relationship if exists
        if ($user->role && $user->role->name) {
            $roles[] = $user->role->name;
        }

        return array_unique($roles);
    }

    protected function hasPermissionTemplatesForDashboard(string $dashboardKey): bool
    {
        if (array_key_exists($dashboardKey, self::$templateExistsCache)) {
            return self::$templateExistsCache[$dashboardKey];
        }

        $exists = \App\Models\DashboardRolePermission::query()
            ->where('dashboard_key', $dashboardKey)
            ->exists();

        self::$templateExistsCache[$dashboardKey] = $exists;

        return $exists;
    }
}
