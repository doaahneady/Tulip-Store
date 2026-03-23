<?php

namespace App\Services;

use App\Models\DashboardRolePermission;
use App\Models\Employee;
use App\Models\EmployeeDashboardOverride;
use Illuminate\Support\Facades\Schema;

class DashboardPermissionService
{
    public const DASHBOARDS = ['it', 'admin', 'mart', 'cs', 'hr', 'finance', 'supervisor', 'driver', 'vendor'];

    public static function roleKeysForEmployee(Employee $employee): array
    {
        $keys = [];
        if ($employee->is_admin) {
            $keys[] = 'admin';
        }
        if ($employee->is_it) {
            $keys[] = 'it';
        }
        if ($employee->is_hr) {
            $keys[] = 'hr';
        }
        if ($employee->is_cs) {
            $keys[] = 'cs';
        }
        if ($employee->is_finance) {
            $keys[] = 'finance';
        }
        if ($employee->is_driver_supervisor) {
            $keys[] = 'supervisor';
            $keys[] = 'driver';
        }
        if ($employee->is_trader) {
            $keys[] = 'vendor';
        }
        if ($employee->is_admin) {
            $keys[] = 'mart';
        }

        return array_values(array_unique($keys));
    }

    public static function primaryRoleKey(Employee $employee): string
    {
        $keys = self::roleKeysForEmployee($employee);
        return $keys[0] ?? 'staff';
    }

    public static function resolve(Employee $employee, string $dashboardKey): array
    {
        $legacyRoles = self::roleKeysForEmployee($employee);
        $legacyCanView = in_array($dashboardKey, $legacyRoles, true);

        $base = [
            'can_view' => $legacyCanView,
            'can_edit' => $legacyCanView,
            'sections' => [],
            'actions' => [],
            'can_view_sensitive' => false,
            'source' => $legacyCanView ? 'legacy_role' : 'default',
        ];

        if (Schema::hasTable('dashboard_role_permissions')) {
            $roleKeys = self::roleKeysForEmployee($employee);
            if ($roleKeys) {
                $rolePerms = DashboardRolePermission::query()
                    ->whereIn('role_key', $roleKeys)
                    ->where('dashboard_key', $dashboardKey)
                    ->get();

                foreach ($rolePerms as $perm) {
                    $base['can_view'] = $base['can_view'] || (bool) $perm->can_view;
                    $base['can_edit'] = $base['can_edit'] || (bool) $perm->can_edit;
                    $base['can_view_sensitive'] = $base['can_view_sensitive'] || (bool) $perm->can_view_sensitive;
                    $base['sections'] = array_values(array_unique(array_merge($base['sections'], (array) ($perm->sections ?? []))));
                    $base['actions'] = array_values(array_unique(array_merge($base['actions'], (array) ($perm->actions ?? []))));
                }
                if ($rolePerms->isNotEmpty()) {
                    $base['source'] = 'role';
                }
            }
        }

        if (Schema::hasTable('employee_dashboard_overrides')) {
            $override = EmployeeDashboardOverride::query()
                ->where('employee_id', $employee->id)
                ->where('dashboard_key', $dashboardKey)
                ->first();

            if ($override && $override->is_override) {
                $base['can_view'] = (bool) ($override->can_view ?? $base['can_view']);
                $base['can_edit'] = (bool) ($override->can_edit ?? $base['can_edit']);
                $base['can_view_sensitive'] = (bool) ($override->can_view_sensitive ?? $base['can_view_sensitive']);
                $base['sections'] = is_array($override->sections) ? $override->sections : $base['sections'];
                $base['actions'] = is_array($override->actions) ? $override->actions : $base['actions'];
                $base['source'] = 'override';
            }
        }

        return $base;
    }

    public static function canView(Employee $employee, string $dashboardKey): bool
    {
        return (bool) self::resolve($employee, $dashboardKey)['can_view'];
    }
}

