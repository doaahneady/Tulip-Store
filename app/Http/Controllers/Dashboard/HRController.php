<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HRController extends HRController_backup
{
    public function employees(Request $request)
    {
        $employees = Employee::query()
            ->when($request->search, function ($q, $search) {
                $search = trim((string) $search);
                $q->where(function ($qq) use ($search) {
                    $qq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%");
                });
            })
            ->when($request->department, fn ($q, $department) => $q->where('department', $department))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->employment_type, fn ($q, $type) => $q->where('employment_type', $type))
            ->orderBy(Schema::hasColumn('employees', 'hire_date') ? 'hire_date' : 'created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $departments = Employee::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('dashboards.hr.employees', compact('employees', 'departments'));
    }
}
