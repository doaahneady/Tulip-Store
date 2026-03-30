<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\FinancialTransaction;
use App\Models\JobApplication;
use App\Models\JobPosition;
use App\Models\LeaveRequest;
use App\Models\PayrollRecord;
use App\Models\PerformanceReview;
use App\Models\Shift;
use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class HRController_backup extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * HR Dashboard
     */
    public function index()
    {
        $metrics = $this->getHRMetrics();

        $pendingLeaves = LeaveRequest::with('employee')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $departments = Employee::selectRaw('COALESCE(department, "Unknown") as name, COUNT(*) as count')
            ->groupBy('department')
            ->orderByDesc('count')
            ->get();

        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $upcomingBirthdays = Employee::whereNotNull('date_of_birth')
            ->get()
            ->filter(function ($e) use ($startOfWeek, $endOfWeek) {
                if (! $e->date_of_birth) {
                    return false;
                }
                $d = \Carbon\Carbon::parse($e->date_of_birth)->setYear(now()->year);

                return $d->between($startOfWeek, $endOfWeek);
            })
            ->take(10)
            ->values();

        $workAnniversaries = Employee::whereNotNull('hire_date')
            ->get()
            ->filter(function ($e) use ($startOfWeek, $endOfWeek) {
                if (! $e->hire_date) {
                    return false;
                }
                $d = \Carbon\Carbon::parse($e->hire_date)->setYear(now()->year);

                return $d->between($startOfWeek, $endOfWeek);
            })
            ->take(10)
            ->values();

        $scheduledLeaves = LeaveRequest::with('employee')
            ->whereIn('status', ['approved', 'pending'])
            ->whereDate('start_date', '>=', $startOfWeek->toDateString())
            ->whereDate('start_date', '<=', $endOfWeek->toDateString())
            ->orderBy('start_date', 'asc')
            ->take(10)
            ->get();

        $leaveStats = [
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
            'this_month' => LeaveRequest::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        $attendanceStats = [
            'late_today' => Attendance::whereDate('date', today())->where('status', 'late')->distinct('employee_id')->count('employee_id'),
            'present_today' => Attendance::whereDate('date', today())->where('status', 'present')->whereNotIn('employee_id', function ($q) {
                $q->select('employee_id')->from('attendance')->whereDate('date', today())->where('status', 'late');
            })->distinct('employee_id')->count('employee_id'),
            'absent_today' => Attendance::whereDate('date', today())->where('status', 'absent')->distinct('employee_id')->count('employee_id'),
            'on_leave_today' => Attendance::whereDate('date', today())->where('status', 'on_leave')->distinct('employee_id')->count('employee_id'),
        ];

        $payrollStats = [
            'total_this_month' => PayrollRecord::where('pay_period', now()->format('Y-m'))->sum('net_pay') ?? 0,
            'pending_payments' => PayrollRecord::where('pay_period', now()->format('Y-m'))->where('status', 'pending')->count(),
            'paid_this_month' => PayrollRecord::where('pay_period', now()->format('Y-m'))->where('status', 'paid')->count(),
        ];

        return view('dashboards.hr.index', compact(
            'metrics',
            'leaveStats',
            'attendanceStats',
            'payrollStats',
            'pendingLeaves',
            'departments',
            'upcomingBirthdays',
            'workAnniversaries',
            'scheduledLeaves'
        ));
    }

    /**
     * Get HR dashboard metrics
     */
    private function getHRMetrics()
    {
        return [
            // Employee Metrics - Real data from database
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'inactive_employees' => Employee::where('status', 'inactive')->count(),
            'new_hires_month' => Employee::whereMonth('hire_date', now()->month)
                ->whereYear('hire_date', now()->year)->count(),
            'new_this_month' => Employee::whereMonth('hire_date', now()->month)
                ->whereYear('hire_date', now()->year)->count(),
            'employees_on_leave' => Employee::whereHas('leaveRequests', function ($query) {
                $query->where('status', 'approved')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now());
            })->count(),

            // Department Breakdown - Real data
            'department_breakdown' => Employee::select('department', DB::raw('count(*) as count'))
                ->whereNotNull('department')
                ->groupBy('department')
                ->pluck('count', 'department')
                ->toArray(),

            // Shift Management - Real data
            'scheduled_shifts_today' => Shift::whereDate('shift_date', today())->count(),
            'active_shifts' => Shift::whereDate('shift_date', today())
                ->where('status', 'active')->count(),
            'completed_shifts_today' => Shift::whereDate('shift_date', today())
                ->where('status', 'completed')->count(),
            'missed_shifts_today' => Shift::whereDate('shift_date', today())
                ->where('status', 'missed')->count(),

            // Driver-specific metrics - Real data
            'total_drivers' => Driver::count(),
            'active_drivers' => Driver::when(\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'is_active'), function ($q) {
                $q->where('is_active', true);
            }, function ($q) {
                $q->where('status', 'active');
            })->count(),
            'drivers_on_shift' => Driver::when(\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'is_active'), function ($q) {
                $q->where('is_active', true);
            })
                ->whereHas('activeAssignments')
                ->count(),

            // Payroll Metrics - Real data
            'pending_payroll' => PayrollRecord::where('status', 'pending')->count(),
            'monthly_payroll_cost' => PayrollRecord::where('pay_period', now()->format('Y-m'))
                ->where('status', 'approved')
                ->sum('net_pay') ?? 0,
            'overtime_hours_month' => PayrollRecord::where('pay_period', now()->format('Y-m'))
                ->sum('overtime_hours') ?? 0,

            // Performance & Reviews - Real data
            'pending_reviews' => PerformanceReview::whereNull('review_date')->count(),
            'overdue_reviews' => PerformanceReview::whereNull('review_date')
                ->where('review_period', '<', now()->subMonths(6)->format('Y-m'))->count(),
            'avg_performance_rating' => PerformanceReview::whereNotNull('overall_rating')
                ->avg('overall_rating') ?? 0,

            // Recruiting - Real data
            'open_positions' => JobPosition::where('status', 'open')->count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'interviews_scheduled' => JobApplication::where('status', 'interview_scheduled')->count(),

            // Recent Activity - Real data
            'recent_hires' => Employee::with('user')
                ->whereMonth('hire_date', now()->month)
                ->latest('hire_date')
                ->take(5)
                ->get(),
            'upcoming_reviews' => PerformanceReview::with('employee')
                ->whereNull('review_date')
                ->orderBy('review_period')
                ->take(5)
                ->get(),
            'shift_alerts' => Shift::with('employee')
                ->whereDate('shift_date', today())
                ->whereIn('status', ['missed', 'no_show'])
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Employee Management
     */
    public function employees(Request $request)
    {
        $employees = Employee::with(['user'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('employee_id', 'like', "%{$search}%");
            })
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->employment_type, function ($query, $type) {
                $query->where('employment_type', $type);
            })
            ->orderBy('hire_date', 'desc')
            ->paginate(20);

        $departments = Employee::distinct()->pluck('department');

        return view('dashboards.hr.employees', compact('employees', 'departments'));
    }

    /**
     * Show create employee form
     */
    public function createEmployeeForm()
    {
        $departments = Employee::distinct()->whereNotNull('department')->pluck('department');
        $skills = Schema::hasTable('skills')
            ? Skill::query()->where('is_active', true)->orderBy('type')->orderBy('name')->get()
            : collect();

        return view('dashboards.hr.create-employee', compact('departments', 'skills'));
    }

    /**
     * Create new employee
     */
    public function createEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'hire_date' => 'required|date|date_format:Y-m-d',
            'hourly_rate' => 'nullable|numeric|min:0',
            'monthly_salary' => 'nullable|numeric|min:0',
            'emergency_contact' => 'required|array',
            'emergency_contact.name' => 'required|string',
            'emergency_contact.phone' => 'required|string',
            'emergency_contact.relationship' => 'required|string',
            'skill_ids' => 'nullable|array',
            'skill_ids.*' => 'integer|exists:skills,id',
        ]);

        DB::beginTransaction();
        try {
            // Create employee record directly as an Authenticatable
            $employee = Employee::create([
                'first_name' => explode(' ', $request->name)[0],
                'last_name' => explode(' ', $request->name)[1] ?? '',
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'employee_id' => $this->generateEmployeeId(),
                'department' => $request->department,
                'position' => $request->position,
                'employment_type' => $request->employment_type,
                'hire_date' => $request->hire_date,
                'hourly_rate' => $request->hourly_rate,
                'monthly_salary' => $request->monthly_salary,
                'emergency_contact_name' => $request->emergency_contact['name'],
                'emergency_contact_phone' => $request->emergency_contact['phone'],
                'emergency_contact_relation' => $request->emergency_contact['relationship'],
                'status' => 'active',
            ]);

            // Assign role fields based on department
            $this->assignDepartmentPermissions($employee, $request->department);

            if (Schema::hasTable('employee_skill') && is_array($request->skill_ids)) {
                $employee->skillsCatalog()->sync($request->skill_ids);
            }

            DB::commit();

            \App\Models\AuditLog::log('create_employee', $employee, null, $employee->toArray(), ['department' => $employee->department, 'position' => $employee->position]);

            return redirect()->route('dashboard.hr.employees')
                ->with('success', 'Employee created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create employee: '.$e->getMessage());
        }
    }

    protected function assignDepartmentPermissions(Employee $employee, $department)
    {
        $dept = strtolower($department);
        if (str_contains($dept, 'admin')) $employee->is_admin = true;
        if (str_contains($dept, 'it')) $employee->is_it = true;
        if (str_contains($dept, 'hr')) $employee->is_hr = true;
        if (str_contains($dept, 'customer support') || str_contains($dept, 'cs')) $employee->is_cs = true;
        if (str_contains($dept, 'finance')) $employee->is_finance = true;
        if (str_contains($dept, 'driver supervisor')) $employee->is_driver_supervisor = true;
        
        $employee->save();
    }

    /**
     * Update employee
     */
    public function updateEmployee(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,'.($employee->user_id ?? 0),
            'phone' => 'nullable|string|max:50',
            'department' => 'required|string',
            'position' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'status' => 'required|in:active,inactive,on_leave,terminated',
            'hourly_rate' => 'nullable|numeric|min:0',
            'monthly_salary' => 'nullable|numeric|min:0',
            'termination_date' => 'nullable|date|date_format:Y-m-d',
            'emergency_contact' => 'nullable|array',
            'emergency_contact.name' => 'nullable|string',
            'emergency_contact.phone' => 'nullable|string',
            'emergency_contact.relationship' => 'nullable|string',
            'skill_ids' => 'nullable|array',
            'skill_ids.*' => 'integer|exists:skills,id',
        ]);

        $old = $employee->getOriginal();
        $employee->update($request->only([
            'department',
            'position',
            'employment_type',
            'status',
            'hourly_rate',
            'monthly_salary',
            'termination_date',
            'emergency_contact',
        ]));

        if ($employee->user) {
            $employee->user->update(array_filter([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
            ], fn ($v) => $v !== null && $v !== ''));
        }

        if (Schema::hasTable('employee_skill') && $request->has('skill_ids') && is_array($request->skill_ids)) {
            $employee->skillsCatalog()->sync($request->skill_ids);
        }

        \App\Models\AuditLog::log('update_employee', $employee, $old, $employee->toArray());

        return redirect()->route('dashboard.hr.employees')
            ->with('success', 'Employee updated successfully!');
    }

    public function editEmployeeForm(Employee $employee)
    {
        $employee->load('user', 'skillsCatalog');
        $departments = Employee::distinct()->whereNotNull('department')->pluck('department');
        $skills = Schema::hasTable('skills')
            ? Skill::query()->where('is_active', true)->orderBy('type')->orderBy('name')->get()
            : collect();

        return view('dashboards.hr.edit-employee', compact('employee', 'departments', 'skills'));
    }

    public function skills(Request $request)
    {
        $skills = Schema::hasTable('skills')
            ? Skill::query()
                ->when($request->search, function ($q, $search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->when($request->type, function ($q, $type) {
                    $q->where('type', $type);
                })
                ->orderBy('type')
                ->orderBy('name')
                ->paginate(30)
                ->withQueryString()
            : collect();

        return view('dashboards.hr.skills', compact('skills'));
    }

    public function createSkill(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'type' => 'required|in:course,strength',
            'is_active' => 'nullable|boolean',
        ]);

        $skill = Skill::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        AuditLog::create([
            'user_id' => auth('employee')->id(),
            'action' => 'employee_skill_create',
            'model_type' => 'Skill',
            'model_id' => $skill->id,
            'new_values' => $skill->toArray(),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Skill created');
    }

    public function updateSkill(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,'.$skill->id,
            'type' => 'required|in:course,strength',
            'is_active' => 'nullable|boolean',
        ]);

        $old = $skill->toArray();
        $skill->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        AuditLog::create([
            'user_id' => auth('employee')->id(),
            'action' => 'employee_skill_update',
            'model_type' => 'Skill',
            'model_id' => $skill->id,
            'old_values' => $old,
            'new_values' => $skill->toArray(),
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Skill updated');
    }

    public function deleteSkill(Skill $skill)
    {
        $old = $skill->toArray();
        $skill->delete();

        AuditLog::create([
            'user_id' => auth('employee')->id(),
            'action' => 'employee_skill_delete',
            'model_type' => 'Skill',
            'model_id' => $old['id'] ?? null,
            'old_values' => $old,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Skill deleted');
    }

    public function deleteEmployee(Employee $employee)
    {
        $employee->load('user');
        $snapshot = $employee->toArray();

        $user = $employee->user;
        $employee->delete();
        if ($user) {
            $user->delete();
        }

        \App\Models\AuditLog::log('delete_employee', null, $snapshot, null);

        return redirect()->route('dashboard.hr.employees')
            ->with('success', 'Employee removed successfully!');
    }

    /**
     * Shift Management
     */
    public function shifts(Request $request)
    {
        $shifts = Shift::with(['employee.user'])
            ->when($request->date, function ($query, $date) {
                $query->whereDate('shift_date', $date);
            }, function ($query) {
                $query->whereDate('shift_date', today());
            })
            ->when($request->employee_id, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('shift_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(20);

        $employees = Employee::with(['user'])->where('status', 'active')->get();

        return view('dashboards.hr.shifts', compact('shifts', 'employees'));
    }

    /**
     * Schedule shift
     */
    public function scheduleShift(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string',
        ]);

        // Check for conflicting shifts
        $conflictingShift = Shift::where('employee_id', $request->employee_id)
            ->where('shift_date', $request->shift_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflictingShift) {
            return back()->with('error', 'Employee already has a shift scheduled during this time.');
        }

        // Block scheduling for inactive/terminated employees
        $emp = Employee::find($request->employee_id);
        if (! $emp || in_array($emp->status, ['inactive', 'terminated'])) {
            return back()->with('error', 'Cannot schedule shift for inactive or terminated employee.');
        }

        $onApprovedLeave = LeaveRequest::where('employee_id', $request->employee_id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $request->shift_date)
            ->whereDate('end_date', '>=', $request->shift_date)
            ->exists();

        if ($onApprovedLeave) {
            return back()->with('error', 'Employee is on approved leave during this date.');
        }

        Shift::create($request->all() + ['status' => 'scheduled']);

        \App\Models\AuditLog::log('schedule_shift', null, null, $request->all(), ['employee_id' => $request->employee_id]);

        return redirect()->route('dashboard.hr.shifts')
            ->with('success', 'Shift scheduled successfully!');
    }

    public function updateShift(Request $request, Shift $shift)
    {
        $request->validate([
            'shift_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:scheduled,active,completed,missed,no_show',
            'notes' => 'nullable|string',
        ]);

        $conflict = Shift::where('employee_id', $shift->employee_id)
            ->where('id', '!=', $shift->id)
            ->whereDate('shift_date', $request->shift_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Shift overlaps with another scheduled shift.');
        }

        $onApprovedLeave = LeaveRequest::where('employee_id', $shift->employee_id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $request->shift_date)
            ->whereDate('end_date', '>=', $request->shift_date)
            ->exists();

        if ($onApprovedLeave) {
            return back()->with('error', 'Employee is on approved leave during this date.');
        }

        $old = $shift->getOriginal();
        $shift->update($request->only(['shift_date', 'start_time', 'end_time', 'status', 'notes']));
        \App\Models\AuditLog::log('update_shift', $shift, $old, $shift->toArray());

        return back()->with('success', 'Shift updated successfully!');
    }

    /**
     * Driver Shift Management (Special handling for drivers)
     */
    public function driverShifts(Request $request)
    {
        $driverShifts = Shift::with(['employee.user', 'employee.driver'])
            ->whereHas('employee.driver')
            ->when($request->date, function ($query, $date) {
                $query->whereDate('shift_date', $date);
            }, function ($query) {
                $query->whereDate('shift_date', today());
            })
            ->orderBy('shift_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(20);

        $drivers = Employee::with(['user', 'driver'])
            ->whereHas('driver')
            ->where('status', 'active')
            ->get();

        return view('dashboards.hr.driver-shifts', compact('driverShifts', 'drivers'));
    }

    /**
     * Payroll Management
     */
    public function payroll(Request $request)
    {
        $reportYear = (int) $request->get('report_year', now()->year);
        $reportMonth = (int) $request->get('report_month', now()->month);
        $reportPayPeriod = sprintf('%04d-%02d', $reportYear, $reportMonth);

        $employees = Employee::with('user')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $periodRecords = PayrollRecord::with(['employee.user'])
            ->where('pay_period', $reportPayPeriod)
            ->get()
            ->keyBy('employee_id');

        $payrollRecords = PayrollRecord::with(['employee.user'])
            ->when($request->pay_period, function ($query, $period) {
                $query->where('pay_period', $period);
            })
            ->when($request->status, function ($query, $status) {
                if ($status === 'sent') {
                    if (Schema::hasColumn('payroll_records', 'breakdown')) {
                        $query->where('status', 'draft')->whereNotNull('breakdown->salary_tx_id');
                    } else {
                        $query->whereRaw('1=0');
                    }

                    return;
                }
                $query->where('status', $status);
            })
            ->orderBy('pay_period', 'desc')
            ->paginate(20);

        $payPeriods = PayrollRecord::distinct()->pluck('pay_period')->sort()->reverse();

        return view('dashboards.hr.payroll', compact('payrollRecords', 'payPeriods', 'employees', 'reportYear', 'reportMonth', 'reportPayPeriod', 'periodRecords'));
    }

    public function sendPayrollToFinance(Request $request)
    {
        $validated = $request->validate([
            'payroll_record_ids' => 'required|array|min:1',
            'payroll_record_ids.*' => 'integer|exists:payroll_records,id',
        ]);

        if (! Schema::hasColumn('payroll_records', 'breakdown')) {
            return back()->with('error', 'Payroll records table is missing required columns. Please run migrations.');
        }

        $records = PayrollRecord::with(['employee.user'])
            ->whereIn('id', $validated['payroll_record_ids'])
            ->get();

        $sentCount = 0;
        foreach ($records as $record) {
            if (in_array($record->status, ['paid', 'approved'], true)) {
                continue;
            }

            $breakdown = is_array($record->breakdown) ? $record->breakdown : [];
            if (! empty($breakdown['salary_tx_id'])) {
                continue;
            }

            $employeeName = $record->employee?->user?->name
                ?? trim(($record->employee?->first_name ?? '').' '.($record->employee?->last_name ?? ''))
                ?? ('Employee #'.$record->employee_id);

            $tx = FinancialTransaction::create([
                'transaction_id' => 'SAL_'.time().'_'.rand(1000, 9999),
                'type' => 'salary_payment',
                'status' => 'pending_approval',
                'amount' => (float) ($record->net_pay ?? 0),
                'currency' => 'USD',
                'description' => 'Salary: '.$employeeName.' ('.$record->pay_period.')',
            ]);
            if (Schema::hasColumn('financial_transactions', 'metadata')) {
                $tx->update([
                    'metadata' => [
                        'payroll_record_id' => $record->id,
                        'employee_id' => $record->employee_id,
                        'pay_period' => $record->pay_period,
                    ],
                ]);
            }

            $breakdown['salary_tx_id'] = $tx->id;
            $breakdown['sent_to_finance_at'] = now()->toDateTimeString();
            $record->update(['breakdown' => $breakdown]);
            $sentCount++;
        }

        return back()->with('success', 'Sent to finance: '.$sentCount);
    }

    public function payrollReport(Request $request, Employee $employee, string $pay_period)
    {
        abort_unless(preg_match('/^\d{4}-\d{2}$/', $pay_period) === 1, 404);

        $year = (int) substr($pay_period, 0, 4);
        $month = (int) substr($pay_period, 5, 2);
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();

        $summary = $this->buildPayrollSummary($employee, $startDate, $endDate);
        $record = PayrollRecord::with(['employee.user'])
            ->where('employee_id', $employee->id)
            ->where('pay_period', $pay_period)
            ->first();

        return view('dashboards.hr.payroll-report', compact('employee', 'pay_period', 'summary', 'record'));
    }

    public function submitPayrollEmployee(Request $request, Employee $employee, string $pay_period)
    {
        abort_unless(preg_match('/^\d{4}-\d{2}$/', $pay_period) === 1, 404);

        $year = (int) substr($pay_period, 0, 4);
        $month = (int) substr($pay_period, 5, 2);
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();
        $summary = $this->buildPayrollSummary($employee, $startDate, $endDate);

        $payload = [
            'gross_pay' => $summary['gross_pay'],
            'net_pay' => $summary['net_pay'],
            'overtime_hours' => $summary['overtime_hours'],
            'overtime_pay' => $summary['overtime_pay'],
            'deductions' => $summary['deductions'],
            'status' => 'draft',
            'breakdown' => $summary,
        ];

        $record = PayrollRecord::updateOrCreate(
            ['employee_id' => $employee->id, 'pay_period' => $pay_period],
            $payload
        );

        if (! Schema::hasColumn('payroll_records', 'breakdown')) {
            return back()->with('error', 'Payroll records table is missing required columns. Please run migrations.');
        }

        $breakdown = is_array($record->breakdown) ? $record->breakdown : [];
        if (! empty($breakdown['salary_tx_id'])) {
            return redirect()->route('dashboard.hr.payroll.report', [$employee->id, $pay_period])->with('success', 'Already sent to finance');
        }

        if (in_array($record->status, ['paid', 'approved'], true)) {
            return redirect()->route('dashboard.hr.payroll.report', [$employee->id, $pay_period])->with('error', 'Cannot submit approved/paid payroll');
        }

        $employeeName = $employee->user?->name
            ?? trim(($employee->first_name ?? '').' '.($employee->last_name ?? ''))
            ?? ('Employee #'.$employee->id);

        $tx = FinancialTransaction::create([
            'transaction_id' => 'SAL_'.time().'_'.rand(1000, 9999),
            'type' => 'salary_payment',
            'status' => 'pending_approval',
            'amount' => (float) ($record->net_pay ?? 0),
            'currency' => 'USD',
            'description' => 'Salary: '.$employeeName.' ('.$pay_period.')',
        ]);
        if (Schema::hasColumn('financial_transactions', 'metadata')) {
            $tx->update([
                'metadata' => [
                    'payroll_record_id' => $record->id,
                    'employee_id' => $employee->id,
                    'pay_period' => $pay_period,
                    'requested_date' => now()->toDateString(),
                ],
            ]);
        }

        $breakdown['salary_tx_id'] = $tx->id;
        $breakdown['sent_to_finance_at'] = now()->toDateTimeString();
        $record->update(['breakdown' => $breakdown]);

        AuditLog::create([
            'user_id' => auth('employee')->id(),
            'action' => 'submit_payroll_to_finance',
            'model_type' => 'PayrollRecord',
            'model_id' => $record->id,
            'new_values' => [
                'pay_period' => $pay_period,
                'employee_id' => $employee->id,
                'salary_tx_id' => $tx->id,
            ],
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('dashboard.hr.payroll.report', [$employee->id, $pay_period])->with('success', 'Sent to finance');
    }

    private function buildPayrollSummary(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        $attendance = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $daysWorked = $attendance->whereIn('status', ['present', 'late'])->count();
        $daysAbsent = $attendance->where('status', 'absent')->count();
        $daysLate = $attendance->where('status', 'late')->count();

        $rawWork = (int) $attendance->sum('work_hours');
        $rawOvertime = (int) $attendance->sum('overtime_hours');

        $maxHours = 24 * (int) $startDate->daysInMonth;
        $regularHours = $rawWork > $maxHours ? round($rawWork / 60, 2) : (float) $rawWork;
        $overtimeHours = $rawOvertime > $maxHours ? round($rawOvertime / 60, 2) : (float) $rawOvertime;

        $leaves = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->get();

        $leaveDays = 0;
        foreach ($leaves as $leave) {
            $s = Carbon::parse($leave->start_date)->startOfDay();
            $e = Carbon::parse($leave->end_date)->endOfDay();
            $rangeStart = $s->greaterThan($startDate) ? $s : $startDate;
            $rangeEnd = $e->lessThan($endDate) ? $e : $endDate;
            $leaveDays += max(0, $rangeStart->diffInDays($rangeEnd) + 1);
        }

        $baseSalary = 0.0;
        if (Schema::hasColumn('employees', 'monthly_salary')) {
            $baseSalary = (float) ($employee->monthly_salary ?? 0);
        }
        if ($baseSalary <= 0 && Schema::hasColumn('employees', 'salary')) {
            $baseSalary = (float) ($employee->salary ?? 0);
        }

        $hourlyRate = Schema::hasColumn('employees', 'hourly_rate')
            ? (float) ($employee->hourly_rate ?? 0)
            : 0.0;
        $overtimeRate = Schema::hasColumn('employees', 'overtime_rate')
            ? (float) ($employee->overtime_rate ?? 0)
            : ($hourlyRate > 0 ? $hourlyRate * 1.5 : 0.0);

        $basePay = $baseSalary > 0 ? $baseSalary : round($regularHours * $hourlyRate, 2);
        $overtimePay = round($overtimeHours * $overtimeRate, 2);
        $grossPay = round($basePay + $overtimePay, 2);

        $daysInMonth = (int) $startDate->daysInMonth;
        $deductions = 0.0;
        if ($baseSalary > 0 && $daysAbsent > 0) {
            $dailyRate = $daysInMonth > 0 ? ($baseSalary / $daysInMonth) : 0;
            $deductions = round($daysAbsent * $dailyRate, 2);
        }

        $netPay = round($grossPay - $deductions, 2);

        return [
            'pay_period' => $startDate->format('Y-m'),
            'period_start' => $startDate->toDateString(),
            'period_end' => $endDate->toDateString(),
            'total_days' => $daysInMonth,
            'days_worked' => $daysWorked,
            'days_absent' => $daysAbsent,
            'days_late' => $daysLate,
            'leave_days' => $leaveDays,
            'regular_hours' => $regularHours,
            'overtime_hours' => $overtimeHours,
            'base_pay' => $basePay,
            'overtime_pay' => $overtimePay,
            'gross_pay' => $grossPay,
            'deductions' => $deductions,
            'net_pay' => $netPay,
        ];
    }

    public function attendance(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $attendance = Attendance::with('employee.user')
            ->whereDate('date', $date)
            ->orderBy('employee_id')
            ->paginate(25);
        $employees = Employee::with('user')->where('status', 'active')->orderBy('first_name')->get();

        return view('dashboards.hr.attendance', compact('attendance', 'employees', 'date'));
    }

    public function overtime(Request $request)
    {
        $overtimeRecords = Attendance::with('employee.user')
            ->where('overtime_hours', '>', 0)
            ->when($request->date, function ($q) use ($request) {
                $q->whereDate('date', $request->date);
            })
            ->when($request->employee_id, function ($q) use ($request) {
                $q->where('employee_id', $request->employee_id);
            })
            ->orderBy('date', 'desc')
            ->paginate(20);

        $employees = Employee::with('user')->where('status', 'active')->orderBy('first_name')->get();

        return view('dashboards.hr.overtime', compact('overtimeRecords', 'employees'));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'nullable|date|date_format:Y-m-d',
        ]);
        $date = $request->date ?: today()->format('Y-m-d');
        $shift = Shift::where('employee_id', $request->employee_id)->whereDate('shift_date', $date)->first();
        $emp = Employee::with('user')->find($request->employee_id);
        if (! $emp || in_array($emp->status, ['inactive', 'terminated'])) {
            return back()->with('error', 'Employee is not allowed to clock in.');
        }
        if ($emp->user && in_array($emp->user->status, ['inactive', 'suspended'])) {
            return back()->with('error', 'User account is deactivated. Clock-in blocked.');
        }
        $checkIn = now();
        $status = 'present';
        if ($shift && $shift->start_time && $checkIn->gt(\Carbon\Carbon::parse($shift->start_time))) {
            $status = 'late';
        }

        return DB::transaction(function () use ($request, $date, $checkIn, $status, $emp) {
            $activeShift = Attendance::where('employee_id', $request->employee_id)
                ->whereDate('date', $date)
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->lockForUpdate()
                ->orderBy('check_in', 'desc')
                ->first();

            if ($activeShift) {
                return back()->with('error', 'Employee already has an active shift. Please clock out first.');
            }

            Attendance::create([
                'employee_id' => $request->employee_id,
                'date' => $date,
                'check_in' => $checkIn->format('H:i:s'),
                'status' => $status,
            ]);

            \App\Models\AuditLog::log('clock_in', null, null, ['employee_id' => $request->employee_id, 'date' => $date, 'check_in' => $checkIn], ['department' => $emp->department ?? 'N/A']);

            return back()->with('success', 'Clock-in recorded successfully!');
        });

    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'nullable|date|date_format:Y-m-d',
        ]);
        $date = $request->date ?: today()->format('Y-m-d');

        return DB::transaction(function () use ($request, $date) {
            $att = Attendance::where('employee_id', $request->employee_id)
                ->whereDate('date', $date)
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->lockForUpdate()
                ->orderBy('check_in', 'desc')
                ->first();
            if (! $att) {
                return back()->with('error', 'No active shift found for this date.');
            }

            $checkOut = now();
            $checkInTime = \Carbon\Carbon::parse(\Carbon\Carbon::parse($att->date)->toDateString().' '.$att->check_in);
            $workMinutes = $checkInTime->diffInMinutes($checkOut);

            $att->update([
                'check_out' => $checkOut->format('H:i:s'),
                'work_hours' => $workMinutes,
            ]);
            \App\Models\AuditLog::log('clock_out', null, null, ['employee_id' => $request->employee_id, 'date' => $date], ['department' => $att->employee->department ?? 'N/A']);

            return back()->with('success', 'Clock-out recorded successfully!');
        });
    }

    /**
     * Leave Management
     */
    public function leaveRequests(Request $request)
    {
        $leaveRequests = LeaveRequest::with(['employee.user'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->employee_id, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $employees = Employee::with('user')->where('status', 'active')->get();

        return view('dashboards.hr.leave-requests', compact('leaveRequests', 'employees'));
    }

    public function submitLeaveRequest(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:annual,sick,personal,emergency,maternity,paternity',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $employee = Employee::find($request->employee_id);
        if (! $employee || $employee->status !== 'active') {
            return back()->with('error', 'Employee is not active.');
        }

        $daysRequested = \Carbon\Carbon::parse($request->start_date)->diffInDays($request->end_date) + 1;

        // Check leave balance (simplified - you might want more sophisticated logic)
        if ($request->leave_type === 'annual') {
            $annualLeaveUsed = LeaveRequest::where('employee_id', $request->employee_id)
                ->where('leave_type', 'annual')
                ->where('status', 'approved')
                ->whereYear('start_date', now()->year)
                ->sum('days_requested') ?? 0;

            if ($annualLeaveUsed + $daysRequested > 21) { // Assuming 21 days annual leave
                return back()->with('error', 'Insufficient annual leave balance.');
            }
        }

        // Check for overlapping leave requests
        $overlapping = LeaveRequest::where('employee_id', $request->employee_id)
            ->where('status', 'approved')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($overlapping) {
            return back()->with('error', 'Leave request overlaps with existing approved leave.');
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $request->employee_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_requested' => $daysRequested,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        \App\Models\AuditLog::log('submit_leave_request', $leaveRequest, null, $leaveRequest->toArray(), ['department' => $employee->department ?? 'N/A']);

        return redirect()->route('dashboard.hr.leave.requests')
            ->with('success', 'Leave request submitted successfully!');
    }

    public function approveLeaveRequest(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Leave request is not pending.');
        }

        $old = $leaveRequest->getOriginal();
        $leaveRequest->update(['status' => 'approved']);

        // Update attendance for the leave period
        $currentDate = \Carbon\Carbon::parse($leaveRequest->start_date);
        $endDate = \Carbon\Carbon::parse($leaveRequest->end_date);

        while ($currentDate <= $endDate) {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $leaveRequest->employee_id,
                    'date' => $currentDate->format('Y-m-d'),
                ],
                [
                    'status' => 'on_leave',
                    'check_in' => null,
                    'check_out' => null,
                ]
            );
            $currentDate->addDay();
        }

        \App\Models\AuditLog::log('approve_leave_request', $leaveRequest, $old, $leaveRequest->toArray(), ['department' => $leaveRequest->employee->department ?? 'N/A']);

        return back()->with('success', 'Leave request approved successfully!');
    }

    public function rejectLeaveRequest(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Leave request is not pending.');
        }

        $old = $leaveRequest->getOriginal();
        $leaveRequest->update(['status' => 'rejected']);

        \App\Models\AuditLog::log('reject_leave_request', $leaveRequest, $old, $leaveRequest->toArray(), ['department' => $leaveRequest->employee->department ?? 'N/A']);

        return back()->with('success', 'Leave request rejected successfully!');
    }

    public function cancelLeaveRequest(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'approved') {
            return back()->with('error', 'Leave request is not approved.');
        }

        $old = $leaveRequest->getOriginal();
        $leaveRequest->update(['status' => 'cancelled']);

        // Remove attendance records for the leave period
        Attendance::where('employee_id', $leaveRequest->employee_id)
            ->whereBetween('date', [$leaveRequest->start_date, $leaveRequest->end_date])
            ->where('status', 'on_leave')
            ->delete();

        \App\Models\AuditLog::log('cancel_leave_request', $leaveRequest, $old, $leaveRequest->toArray(), ['department' => $leaveRequest->employee->department ?? 'N/A']);

        return back()->with('success', 'Leave request cancelled successfully!');
    }

    /**
     * Performance Reviews
     */
    public function performanceReviews(Request $request)
    {
        $reviews = PerformanceReview::with(['employee.user', 'reviewer'])
            ->when($request->employee_id, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('review_period', 'desc')
            ->paginate(20);

        $employees = Employee::with('user')->where('status', 'active')->get();

        return view('dashboards.hr.performance-reviews', compact('reviews', 'employees'));
    }

    public function createPerformanceReview(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string',
            'reviewer_id' => 'required|exists:employees,id',
            'overall_rating' => 'required|numeric|min:1|max:5',
            'comments' => 'required|string',
        ]);

        $employee = Employee::find($request->employee_id);
        if (! $employee || $employee->status !== 'active') {
            return back()->with('error', 'Employee is not active.');
        }

        $review = PerformanceReview::create([
            'employee_id' => $request->employee_id,
            'reviewer_id' => $request->reviewer_id,
            'review_period' => $request->review_period,
            'overall_rating' => $request->overall_rating,
            'comments' => $request->comments,
            'review_date' => now(),
            'status' => 'completed',
        ]);

        \App\Models\AuditLog::log('create_performance_review', $review, null, $review->toArray(), ['department' => $employee->department ?? 'N/A']);

        return redirect()->route('dashboard.hr.performance.reviews')
            ->with('success', 'Performance review created successfully!');
    }

    /**
     * Recruiting & Hiring
     */
    public function recruiting(Request $request)
    {
        $positions = JobPosition::withCount('applications')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $departments = JobPosition::distinct()->pluck('department');

        return view('dashboards.hr.recruiting', compact('positions', 'departments'));
    }

    public function createJobPosition(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary_range' => 'nullable|string',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'deadline' => 'nullable|date',
        ]);

        $position = JobPosition::create([
            'title' => $request->title,
            'department' => $request->department,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'salary_range' => $request->salary_range,
            'employment_type' => $request->employment_type,
            'deadline' => $request->deadline,
            'status' => 'open',
        ]);

        \App\Models\AuditLog::log('create_job_position', $position, null, $position->toArray(), ['department' => $request->department]);

        return redirect()->route('dashboard.hr.recruiting')
            ->with('success', 'Job position created successfully!');
    }

    public function updateJobPosition(Request $request, JobPosition $position)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary_range' => 'nullable|string',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'deadline' => 'nullable|date',
            'status' => 'required|in:open,closed,on_hold',
        ]);

        $old = $position->getOriginal();
        $position->update($request->all());

        \App\Models\AuditLog::log('update_job_position', $position, $old, $position->toArray(), ['department' => $request->department]);

        return back()->with('success', 'Job position updated successfully!');
    }

    /**
     * Job Applications
     */
    public function jobApplications(Request $request)
    {
        $applications = JobApplication::with(['position'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->position_id, function ($query, $positionId) {
                $query->where('position_id', $positionId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $positions = JobPosition::where('status', 'open')->get();

        return view('dashboards.hr.job-applications', compact('applications', 'positions'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,interview_scheduled,rejected,offered,hired',
            'notes' => 'nullable|string',
        ]);

        $old = $application->getOriginal();
        $application->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        \App\Models\AuditLog::log('update_application_status', $application, $old, $application->toArray(), ['department' => $application->position->department ?? 'N/A']);

        return back()->with('success', 'Application status updated successfully!');
    }

    /**
     * Salary Definitions
     */
    public function salaryDefinitions(Request $request)
    {
        $employees = Employee::with(['user'])
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->when($request->employment_type, function ($query, $type) {
                $query->where('employment_type', $type);
            })
            ->orderBy('department')
            ->orderBy('position')
            ->paginate(20);

        $departments = Employee::distinct()->pluck('department');

        return view('dashboards.hr.salary-definitions', compact('employees', 'departments'));
    }

    public function updateSalaryDefinition(Request $request, Employee $employee)
    {
        $request->validate([
            'hourly_rate' => 'nullable|numeric|min:0',
            'monthly_salary' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'bonus_structure' => 'nullable|string',
            'deduction_structure' => 'nullable|string',
        ]);

        $old = $employee->getOriginal();
        $employee->update($request->only(['hourly_rate', 'monthly_salary', 'overtime_rate', 'bonus_structure', 'deduction_structure']));

        \App\Models\AuditLog::log('update_salary_definition', $employee, $old, $employee->toArray(), ['department' => $employee->department ?? 'N/A']);

        return back()->with('success', 'Salary definition updated successfully!');
    }

    /**
     * Payroll Processing
     */
    public function calculatePayroll(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'request_date' => 'required|date',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'integer|exists:employees,id',
        ]);

        $year = (int) $request->input('year');
        $month = (int) $request->input('month');
        $requestDate = \Carbon\Carbon::parse($request->input('request_date'))->toDateString();

        $monthBase = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfDay();
        $startDate = $monthBase->copy()->startOfMonth();
        $endDate = $monthBase->copy()->endOfMonth()->endOfDay();

        $payPeriod = sprintf('%04d-%02d', $year, $month);

        $employees = Employee::with(['attendance', 'leaveRequests'])
            ->where('status', 'active')
            ->when($request->employee_ids, function ($q) use ($request) {
                $q->whereIn('id', $request->employee_ids);
            })
            ->get();

        $payrollRecords = [];

        if (! Schema::hasColumn('payroll_records', 'breakdown')) {
            return back()->with('error', 'Payroll records table is missing required columns. Please run migrations.');
        }

        foreach ($employees as $employee) {
            $regularHours = 0;
            $overtimeHours = 0;
            $daysWorked = 0;
            $daysAbsent = 0;
            $daysLate = 0;

            // Calculate attendance metrics
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->get();

            $attendanceByDay = $attendance->groupBy(function ($row) {
                if ($row->date instanceof \Carbon\CarbonInterface) {
                    return $row->date->toDateString();
                }

                return (string) $row->date;
            });

            foreach ($attendanceByDay as $day => $records) {
                $regularHours += (int) $records->sum('work_hours');
                $overtimeHours += (int) $records->sum('overtime_hours');

                $statuses = $records->pluck('status')->filter()->unique();

                if ($statuses->contains('absent')) {
                    $daysAbsent++;

                    continue;
                }

                if ($statuses->contains('late')) {
                    $daysLate++;
                    $daysWorked++;

                    continue;
                }

                if ($statuses->contains('present')) {
                    $daysWorked++;
                }
            }

            // Calculate pay
            $monthlySalary = 0.0;
            if (Schema::hasColumn('employees', 'monthly_salary')) {
                $monthlySalary = (float) ($employee->monthly_salary ?? 0);
            }
            if ($monthlySalary <= 0 && Schema::hasColumn('employees', 'salary')) {
                $monthlySalary = (float) ($employee->salary ?? 0);
            }

            $hourlyRate = Schema::hasColumn('employees', 'hourly_rate')
                ? (float) ($employee->hourly_rate ?? 0)
                : 0.0;
            $overtimeRate = Schema::hasColumn('employees', 'overtime_rate')
                ? (float) ($employee->overtime_rate ?? 0)
                : ($hourlyRate > 0 ? $hourlyRate * 1.5 : 0.0);

            $basePay = $monthlySalary > 0 ? $monthlySalary : ($hourlyRate > 0 ? $regularHours * $hourlyRate : 0.0);

            $overtimePay = $overtimeHours * $overtimeRate;
            $grossPay = $basePay + $overtimePay;

            // Calculate deductions
            $deductions = 0;
            if ($daysAbsent > 0 && $monthlySalary > 0) {
                $dailyRate = $monthlySalary / 30;
                $deductions += $daysAbsent * $dailyRate;
            }

            $netPay = $grossPay - $deductions;

            $existing = PayrollRecord::where('employee_id', $employee->id)
                ->where('pay_period', $payPeriod)
                ->first();
            if ($existing && in_array($existing->status, ['approved', 'paid'], true)) {
                continue;
            }

            $payload = [
                'employee_id' => $employee->id,
                'pay_period' => $payPeriod,
                'status' => 'draft',
            ];

            if (Schema::hasColumn('payroll_records', 'base_salary')) {
                $payload['base_salary'] = $basePay;
            } elseif (Schema::hasColumn('payroll_records', 'base_pay')) {
                $payload['base_pay'] = $basePay;
            }

            if (Schema::hasColumn('payroll_records', 'regular_hours')) {
                $payload['regular_hours'] = $regularHours;
            }
            if (Schema::hasColumn('payroll_records', 'overtime_hours')) {
                $payload['overtime_hours'] = $overtimeHours;
            }
            if (Schema::hasColumn('payroll_records', 'overtime_pay')) {
                $payload['overtime_pay'] = $overtimePay;
            }
            if (Schema::hasColumn('payroll_records', 'gross_pay')) {
                $payload['gross_pay'] = $grossPay;
            }
            if (Schema::hasColumn('payroll_records', 'deductions')) {
                $payload['deductions'] = $deductions;
            }
            if (Schema::hasColumn('payroll_records', 'tax_deductions')) {
                $payload['tax_deductions'] = 0;
            }
            if (Schema::hasColumn('payroll_records', 'bonuses')) {
                $payload['bonuses'] = 0;
            }
            if (Schema::hasColumn('payroll_records', 'commissions')) {
                $payload['commissions'] = 0;
            }
            if (Schema::hasColumn('payroll_records', 'net_pay')) {
                $payload['net_pay'] = $netPay;
            }

            if (Schema::hasColumn('payroll_records', 'period_start')) {
                $payload['period_start'] = $startDate->toDateString();
            }
            if (Schema::hasColumn('payroll_records', 'period_end')) {
                $payload['period_end'] = $endDate->toDateString();
            }

            if (Schema::hasColumn('payroll_records', 'breakdown')) {
                $payload['breakdown'] = [
                    'period_start' => $startDate->toDateString(),
                    'period_end' => $endDate->toDateString(),
                    'regular_hours' => $regularHours,
                    'days_worked' => $daysWorked,
                    'days_absent' => $daysAbsent,
                    'days_late' => $daysLate,
                    'month' => (int) $startDate->format('m'),
                    'year' => (int) $startDate->format('Y'),
                ];
            }
            if (Schema::hasColumn('payroll_records', 'processed_by')) {
                $payload['processed_by'] = null;
            }
            if (Schema::hasColumn('payroll_records', 'processed_at')) {
                $payload['processed_at'] = null;
            }

            $payrollRecord = PayrollRecord::updateOrCreate(
                ['employee_id' => $employee->id, 'pay_period' => $payPeriod],
                $payload
            );

            $payrollRecords[] = $payrollRecord;
        }

        \App\Models\AuditLog::log('calculate_payroll', null, null, ['pay_period' => $payPeriod, 'employees_count' => count($payrollRecords)]);

        return redirect()->route('dashboard.hr.payroll', [
            'pay_period' => $payPeriod,
        ])->with('success', 'Payroll calculated for '.count($payrollRecords).' employees!');
    }

    public function submitPayroll(Request $request)
    {
        $request->validate([
            'pay_period' => 'required|string',
        ]);

        $payrollRecords = PayrollRecord::where('pay_period', $request->pay_period)
            ->where('status', 'draft')
            ->get();

        foreach ($payrollRecords as $record) {
            $record->update(['status' => 'approved']);
        }

        \App\Models\AuditLog::log('submit_payroll', null, null, ['pay_period' => $request->pay_period, 'records_count' => $payrollRecords->count()]);

        return redirect()->route('dashboard.hr.payroll')
            ->with('success', 'Payroll submitted to finance for '.$payrollRecords->count().' employees!');
    }

    /**
     * Announcements
     */
    public function announcements(Request $request)
    {
        $announcements = Announcement::with(['author'])
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $departments = Announcement::distinct()->pluck('department');

        return view('dashboards.hr.announcements', compact('announcements', 'departments'));
    }

    public function createAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,urgent,meeting,training',
            'department' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'expires_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'department' => $request->department,
            'priority' => $request->priority,
            'expires_at' => $request->expires_at,
            'author_id' => auth()->id(),
        ]);

        \App\Models\AuditLog::log('create_announcement', $announcement, null, $announcement->toArray(), ['department' => $request->department ?? 'All']);

        return redirect()->route('dashboard.hr.announcements')
            ->with('success', 'Announcement created successfully!');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,urgent,meeting,training',
            'department' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'expires_at' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        $old = $announcement->getOriginal();
        $announcement->update($request->all());

        \App\Models\AuditLog::log('update_announcement', $announcement, $old, $announcement->toArray(), ['department' => $request->department ?? 'All']);

        return back()->with('success', 'Announcement updated successfully!');
    }

    /**
     * Audit Logs
     */
    public function auditLogs(Request $request)
    {
        $logs = AuditLog::with(['user'])
            ->when($request->action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($request->user_id, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $actions = AuditLog::distinct()->pluck('action');
        $departments = AuditLog::distinct()->pluck('department');

        return view('dashboards.hr.audit-logs', compact('logs', 'actions', 'departments'));
    }

    /**
     * Attendance Reports
     */
    public function attendanceReports(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $employeeId = $request->get('employee_id');

        $query = Attendance::with(['employee.user'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $attendance = $query->orderBy('date', 'desc')->paginate(50);
        $employees = Employee::with('user')->where('status', 'active')->get();

        // Calculate summary statistics
        $summary = [
            'total_days' => $attendance->total(),
            'present_days' => $attendance->where('status', 'present')->count(),
            'absent_days' => $attendance->where('status', 'absent')->count(),
            'late_days' => $attendance->where('status', 'late')->count(),
            'on_leave_days' => $attendance->where('status', 'on_leave')->count(),
            'total_work_hours' => $attendance->sum('work_hours'),
            'total_overtime_hours' => $attendance->sum('overtime_hours'),
        ];

        return view('dashboards.hr.attendance-reports', compact('attendance', 'employees', 'startDate', 'endDate', 'summary'));
    }

    /**
     * Payroll Reports
     */
    public function payrollReports(Request $request)
    {
        $payPeriod = $request->get('pay_period', now()->format('Y-m'));
        $employeeId = $request->get('employee_id');

        $query = PayrollRecord::with(['employee.user'])
            ->where('pay_period', $payPeriod);

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $payrollRecords = $query->orderBy('created_at', 'desc')->paginate(50);
        $employees = Employee::with('user')->where('status', 'active')->get();

        // Calculate summary statistics
        $summary = [
            'total_employees' => $payrollRecords->total(),
            'total_gross_pay' => $payrollRecords->sum('gross_pay'),
            'total_deductions' => $payrollRecords->sum('deductions'),
            'total_net_pay' => $payrollRecords->sum('net_pay'),
            'total_regular_hours' => $payrollRecords->sum('regular_hours'),
            'total_overtime_hours' => $payrollRecords->sum('overtime_hours'),
        ];

        return view('dashboards.hr.payroll-reports', compact('payrollRecords', 'employees', 'payPeriod', 'summary'));
    }

    /**
     * Employee Profile
     */
    public function employeeProfile(Employee $employee)
    {
        $employee->load(['user', 'attendance', 'leaveRequests', 'performanceReviews', 'payrollRecords']);

        // Calculate attendance statistics
        $attendanceStats = [
            'total_days' => $employee->attendance()->select('date')->distinct()->count('date'),
            'present_days' => $employee->attendance()->where('status', 'present')->select('date')->distinct()->count('date'),
            'absent_days' => $employee->attendance()->where('status', 'absent')->select('date')->distinct()->count('date'),
            'late_days' => $employee->attendance()->where('status', 'late')->select('date')->distinct()->count('date'),
            'on_leave_days' => $employee->attendance()->where('status', 'on_leave')->select('date')->distinct()->count('date'),
        ];

        // Calculate leave statistics
        $leaveStats = [
            'total_requests' => $employee->leaveRequests()->count(),
            'approved' => $employee->leaveRequests()->where('status', 'approved')->count(),
            'rejected' => $employee->leaveRequests()->where('status', 'rejected')->count(),
            'pending' => $employee->leaveRequests()->where('status', 'pending')->count(),
        ];

        // Get recent performance reviews
        $recentReviews = $employee->performanceReviews()
            ->whereNotNull('overall_rating')
            ->orderBy('review_period', 'desc')
            ->take(5)
            ->get();

        return view('dashboards.hr.employee-profile', compact('employee', 'attendanceStats', 'leaveStats', 'recentReviews'));
    }

    /**
     * Utility Methods
     */
    private function generateEmployeeId()
    {
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        $lastId = $lastEmployee ? intval(substr($lastEmployee->employee_id, 2)) : 0;

        return 'EM'.str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }

    private function assignEmployeeRole($user, $department, $position)
    {
        // Simplified role assignment logic
        $role = 'employee';

        if (str_contains(strtolower($position), 'manager') || str_contains(strtolower($position), 'supervisor')) {
            $role = 'manager';
        }

        if (str_contains(strtolower($department), 'hr') || str_contains(strtolower($position), 'hr')) {
            $role = 'hr_manager';
        }

        if (str_contains(strtolower($department), 'admin') || str_contains(strtolower($position), 'admin')) {
            $role = 'admin';
        }

        // Assign role (you might want to use Laravel's built-in role system)
        $user->update(['role' => $role]);
    }
}
