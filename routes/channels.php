<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('dashboard.{dashboard}', function ($employee, string $dashboard) {
    if (! $employee) {
        return false;
    }

    if ((bool) ($employee->is_admin ?? false)) {
        return true;
    }

    return match ($dashboard) {
        'admin' => (bool) ($employee->is_admin ?? false),
        'it' => (bool) ($employee->is_it ?? false),
        'hr' => (bool) ($employee->is_hr ?? false),
        'finance' => (bool) ($employee->is_finance ?? false),
        'cs' => (bool) ($employee->is_cs ?? false),
        'supervisor' => (bool) ($employee->is_driver_supervisor ?? false),
        'vendor' => (bool) ($employee->is_trader ?? false),
        default => false,
    };
});
