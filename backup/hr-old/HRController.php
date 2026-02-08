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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HRController extends Controller
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

        $leaveStats = [
            'pending' => LeaveRequest::where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('status', 'rejected')->count(),
            'this_month' => LeaveRequest::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        $attendanceStats = [
            'present_today' => Attendance::whereDate('date', today())->where('status', 'present')->count(),
            'absent_today' => Attendance::whereDate('date', today())->where('status', 'absent')->count(),
            'late_today' => Attendance::whereDate('date', today())->where('status', 'late')->count(),
            'on_leave_today' => Attendance::whereDate('date', today())->where('status', 'on_leave')->count(),
        ];

        $payrollStats = [
            'total_this_month' => PayrollRecord::where('pay_period', now()->format('Y-m'))->sum('net_pay') ?? 0,
            'pending_payments' => PayrollRecord::where('pay_period', now()->format('Y-m'))->where('status', 'pending')->count(),
            'paid_this_month' => PayrollRecord::where('pay_period', now()->format('Y-m'))->where('status', 'paid')->count(),
        ];

        return view('dashboards.hr.index', compact('metrics', 'leaveStats', 'attendanceStats', 'payrollStats'));
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

        return view('dashboards.hr.create-employee', compact('departments'));
    }

    /**
     * Create new employee
     */
    public function createEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'hire_date' => 'required|date',
            'hourly_rate' => 'nullable|numeric|min:0',
            'monthly_salary' => 'nullable|numeric|min:0',
            'emergency_contact' => 'required|array',
            'emergency_contact.name' => 'required|string',
            'emergency_contact.phone' => 'required|string',
            'emergency_contact.relationship' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make('password123'), // Default password
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Create employee record
            $employee = Employee::create([
                'user_id' => $user->id,
                'employee_id' => $this->generateEmployeeId(),
                'department' => $request->department,
                'position' => $request->position,
                'employment_type' => $request->employment_type,
                'hire_date' => $request->hire_date,
                'hourly_rate' => $request->hourly_rate,
                'monthly_salary' => $request->monthly_salary,
                'emergency_contact' => $request->emergency_contact,
                'status' => 'active',
            ]);

            // Assign appropriate role based on department/position
            $this->assignEmployeeRole($user, $request->department, $request->position);

            DB::commit();

            \App\Models\AuditLog::log('create_employee', $employee, null, $employee->toArray(), ['department' => $employee->department, 'position' => $employee->position]);

            return redirect()->route('dashboard.hr.employees')
                ->with('success', 'Employee created successfully! Default password: password123');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to create employee: '.$e->getMessage());
        }
    }

    /**
     * Update employee
     */
    public function updateEmployee(Request $request, Employee $employee)
    {
        $request->validate([
            'department' => 'required|string',
            'position' => 'required|string',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'status' => 'required|in:active,inactive,on_leave,terminated',
            'hourly_rate' => 'nullable|numeric|min:0',
            'monthly_salary' => 'nullable|numeric|min:0',
            'termination_date' => 'nullable|date',
        ]);

        $old = $employee->getOriginal();
        $employee->update($request->all());
        \App\Models\AuditLog::log('update_employee', $employee, $old, $employee->toArray());

        return redirect()->route('dashboard.hr.employees')
            ->with('success', 'Employee updated successfully!');
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
        $payrollRecords = PayrollRecord::with(['employee.user'])
            ->when($request->pay_period, function ($query, $period) {
                $query->where('pay_period', $period);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('pay_period', 'desc')
            ->paginate(20);

        $payPeriods = PayrollRecord::distinct()->pluck('pay_period')->sort()->reverse();

        return view('dashboards.hr.payroll', compact('payrollRecords', 'payPeriods'));
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
        if (! $request->employee_id && auth()->check()) {
            $request->merge(['employee_id' => auth()->id()]);
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'nullable|date',
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
        Attendance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $date],
            ['check_in' => $checkIn, 'status' => $status]
        );
        \App\Models\AuditLog::log('clock_in', null, null, ['employee_id' => $request->employee_id, 'date' => $date, 'status' => $status]);

        return back()->with('success', 'Clock-in recorded');
    }

    public function clockOut(Request $request)
    {
        if (! $request->employee_id && auth()->check()) {
            $request->merge(['employee_id' => auth()->id()]);
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'nullable|date',
        ]);
        $date = $request->date ?: today()->format('Y-m-d');
        $emp = Employee::with('user')->find($request->employee_id);
        if (! $emp || in_array($emp->status, ['inactive', 'terminated'])) {
            return back()->with('error', 'Employee is not allowed to clock out.');
        }
        if ($emp->user && in_array($emp->user->status, ['inactive', 'suspended'])) {
            return back()->with('error', 'User account is deactivated. Clock-out blocked.');
        }
        $attendance = Attendance::firstOrCreate(['employee_id' => $request->employee_id, 'date' => $date]);
        $checkOut = now();
        $attendance->check_out = $checkOut;
        $workHours = null;
        if ($attendance->check_in) {
            $workHours = \Carbon\Carbon::parse($attendance->check_in)->diffInMinutes($checkOut) / 60.0;
        }
        $shift = Shift::where('employee_id', $request->employee_id)->whereDate('shift_date', $date)->first();
        $status = $attendance->status ?: 'present';
        if ($shift && $shift->end_time && $checkOut->lt(\Carbon\Carbon::parse($shift->end_time))) {
            $status = 'early_leave';
        }
        $overtime = 0;
        if ($workHours !== null) {
            $overtime = max($workHours - 8, 0);
        }
        $attendance->work_hours = $workHours;
        $attendance->overtime_hours = $overtime;
        $attendance->status = $status;
        $attendance->save();
        \App\Models\AuditLog::log('clock_out', null, null, ['employee_id' => $request->employee_id, 'date' => $date, 'status' => $status, 'work_hours' => $workHours, 'overtime_hours' => $overtime]);

        return back()->with('success', 'Clock-out recorded');
    }

    public function finalizeAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);
        $date = $request->date;
        $employees = Employee::where('status', 'active')->pluck('id');
        foreach ($employees as $empId) {
            $exists = Attendance::where('employee_id', $empId)->whereDate('date', $date)->exists();
            if (! $exists) {
                Attendance::create([
                    'employee_id' => $empId,
                    'date' => $date,
                    'status' => 'absent',
                ]);
            }
        }
        \App\Models\AuditLog::log('finalize_attendance', null, null, ['date' => $date]);

        return back()->with('success', 'Attendance finalized');
    }

    /**
     * Calculate payroll for period
     */
    public function calculatePayroll(Request $request)
    {
        $request->validate([
            'pay_period' => 'required|string', // Format: YYYY-MM
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
        ]);

        DB::beginTransaction();
        try {
            // Check if payroll already exists for this period
            $existingPayroll = PayrollRecord::where('pay_period', $request->pay_period)->exists();
            if ($existingPayroll) {
                return back()->with('error', 'Payroll already calculated for this period.');
            }

            $employees = Employee::where('status', 'active')->get();
            $payrollRecords = [];

            foreach ($employees as $employee) {
                $shifts = Shift::where('employee_id', $employee->id)
                    ->whereBetween('shift_date', [$request->period_start, $request->period_end])
                    ->where('status', 'completed')
                    ->get();

                $regularHours = $shifts->sum(function ($shift) {
                    return min($shift->hours_worked ?? 0, 8); // Max 8 regular hours per day
                });

                $overtimeHours = $shifts->sum(function ($shift) {
                    return max(($shift->hours_worked ?? 0) - 8, 0); // Hours over 8 per day
                });

                $hourlyRate = $employee->hourly_rate ?? ($employee->monthly_salary / 160); // 160 hours per month
                $regularPay = $regularHours * $hourlyRate;
                $overtimePay = $overtimeHours * $hourlyRate * 1.5;
                $bonusAdjustments = \App\Models\PayrollAdjustment::where('employee_id', $employee->id)
                    ->where('pay_period', $request->pay_period)
                    ->whereIn('type', ['bonus', 'overtime_bonus'])
                    ->sum('amount');
                $deductionAdjustments = \App\Models\PayrollAdjustment::where('employee_id', $employee->id)
                    ->where('pay_period', $request->pay_period)
                    ->whereIn('type', ['deduction', 'penalty'])
                    ->sum('amount');
                $grossPay = $regularPay + $overtimePay + $bonusAdjustments;
                $netPay = $grossPay - $deductionAdjustments;

                $payrollRecord = PayrollRecord::create([
                    'employee_id' => $employee->id,
                    'pay_period' => $request->pay_period,
                    'regular_hours' => $regularHours,
                    'overtime_hours' => $overtimeHours,
                    'regular_pay' => $regularPay,
                    'overtime_pay' => $overtimePay,
                    'bonuses' => $bonusAdjustments,
                    'deductions' => $deductionAdjustments,
                    'gross_pay' => $grossPay,
                    'net_pay' => $netPay,
                    'status' => 'draft',
                ]);

                $payrollRecords[] = $payrollRecord;
            }

            DB::commit();

            \App\Models\AuditLog::log('calculate_payroll', null, null, ['pay_period' => $request->pay_period, 'count' => count($payrollRecords)]);

            return redirect()->route('dashboard.hr.payroll')
                ->with('success', 'Payroll calculated for '.count($payrollRecords).' employees.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to calculate payroll: '.$e->getMessage());
        }
    }

    /**
     * Submit payroll to Finance
     */
    public function submitPayroll(Request $request)
    {
        $request->validate([
            'pay_period' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $payrollRecords = PayrollRecord::where('pay_period', $request->pay_period)
                ->where('status', 'draft')
                ->get();

            if ($payrollRecords->isEmpty()) {
                return back()->with('error', 'No draft payroll records found for this period.');
            }

            // Update payroll status
            PayrollRecord::where('pay_period', $request->pay_period)
                ->where('status', 'draft')
                ->update(['status' => 'approved', 'approved_by' => auth()->id(), 'approved_at' => now()]);

            // Create financial transactions for Finance team to process
            foreach ($payrollRecords as $record) {
                FinancialTransaction::create([
                    'transaction_id' => 'payroll_'.$record->id.'_'.time(),
                    'user_id' => $record->employee->user_id,
                    'type' => 'payroll',
                    'amount' => $record->net_pay,
                    'status' => 'pending',
                    'approval_status' => 'pending',
                    'description' => "Payroll for {$record->pay_period}",
                    'metadata' => [
                        'payroll_record_id' => $record->id,
                        'employee_id' => $record->employee_id,
                        'pay_period' => $record->pay_period,
                        'regular_hours' => $record->regular_hours,
                        'overtime_hours' => $record->overtime_hours,
                        'gross_pay' => $record->gross_pay,
                    ],
                ]);
            }

            DB::commit();

            // Notify Finance team
            broadcast(new \App\Events\PayrollSubmitted($request->pay_period, $payrollRecords->sum('net_pay')));

            \App\Models\AuditLog::log('submit_payroll', null, null, ['pay_period' => $request->pay_period, 'total_amount' => $payrollRecords->sum('net_pay')]);

            return redirect()->route('dashboard.hr.payroll')
                ->with('success', 'Payroll submitted to Finance for processing.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to submit payroll: '.$e->getMessage());
        }
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
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->orderBy('review_date', 'desc')
            ->paginate(20);

        $employees = Employee::with(['user'])->where('status', 'active')->get();

        return view('dashboards.hr.performance-reviews', compact('reviews', 'employees'));
    }

    /**
     * Create performance review
     */
    public function createPerformanceReview(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:quarterly,annual,probation,special',
            'review_period' => 'required|string',
            'review_date' => 'required|date',
            'ratings' => 'required|array',
            'strengths' => 'nullable|string',
            'areas_for_improvement' => 'nullable|string',
            'goals' => 'nullable|string',
            'overall_rating' => 'required|numeric|min:1|max:5',
        ]);

        PerformanceReview::create($request->all() + [
            'reviewer_id' => auth()->id(),
            'status' => 'draft',
        ]);

        \App\Models\AuditLog::log('create_performance_review', null, null, $request->all());

        return redirect()->route('dashboard.hr.performance-reviews')
            ->with('success', 'Performance review created successfully!');
    }

    /**
     * Recruiting Management
     */
    public function recruiting()
    {
        $positions = JobPosition::with(['hiringManager'])
            ->orderBy('created_at', 'desc')
            ->get();

        $applications = JobApplication::with(['position'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recruitingStats = [
            'open_positions' => JobPosition::where('status', 'active')->count(),
            'total_applications' => JobApplication::count(),
            'pending_applications' => JobApplication::where('status', 'applied')->count(),
            'interviews_this_week' => JobApplication::where('status', 'interview_scheduled')
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        return view('dashboards.hr.recruiting', compact('positions', 'applications', 'recruitingStats'));
    }

    public function leaves(Request $request)
    {
        $leaves = LeaveRequest::with('employee.user')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $employees = Employee::with('user')->where('status', 'active')->get();

        return view('dashboards.hr.leaves', compact('leaves', 'employees'));
    }

    public function payrollAdjustments(Request $request)
    {
        $adjustments = \App\Models\PayrollAdjustment::with('employee.user')
            ->when($request->pay_period, fn ($q, $p) => $q->where('pay_period', $p))
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $employees = Employee::with('user')->where('status', 'active')->get();

        return view('dashboards.hr.payroll-adjustments', compact('adjustments', 'employees'));
    }

    public function createPayrollAdjustment(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'pay_period' => 'required|string',
            'type' => 'required|in:bonus,overtime_bonus,deduction,penalty',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
        ]);
        \App\Models\PayrollAdjustment::create([
            'employee_id' => $request->employee_id,
            'pay_period' => $request->pay_period,
            'type' => $request->type,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        \App\Models\AuditLog::log('create_payroll_adjustment', null, null, $request->all());

        return back()->with('success', 'Adjustment created');
    }

    public function salaryDefinitions(Request $request)
    {
        $employees = Employee::with('user')
            ->orderBy('hire_date', 'desc')
            ->paginate(20);

        return view('dashboards.hr.salary-definitions', compact('employees'));
    }

    public function updateSalaryDefinition(Request $request, Employee $employee)
    {
        $request->validate([
            'hourly_rate' => 'nullable|numeric|min:0',
            'monthly_salary' => 'nullable|numeric|min:0',
        ]);
        $old = $employee->getOriginal();
        $employee->update($request->only(['hourly_rate', 'monthly_salary']));
        \App\Models\AuditLog::log('update_salary', $employee, $old, $employee->only(['hourly_rate', 'monthly_salary']));

        return back()->with('success', 'Salary updated');
    }

    public function employeeProfile(Employee $employee)
    {
        $employee->load(['user']);
        $documents = \App\Models\EmployeeDocument::where('employee_id', $employee->id)->get();

        return view('dashboards.hr.employee-profile', compact('employee', 'documents'));
    }

    public function submitLeave(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'reason' => 'nullable|string',
        ]);
        LeaveRequest::create([
            'employee_id' => $request->employee_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
        \App\Models\AuditLog::log('submit_leave', null, null, $request->all());

        return back()->with('success', 'Leave request submitted');
    }

    public function updateLeave(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'reason' => 'nullable|string',
        ]);
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be modified');
        }
        $old = $leave->getOriginal();
        $leave->update($request->only(['start_date', 'end_date', 'type', 'reason']));
        \App\Models\AuditLog::log('update_leave', $leave, $old, $leave->toArray());

        return back()->with('success', 'Leave request updated');
    }

    public function approveLeaveHR(LeaveRequest $leave)
    {
        $leave->update(['status' => 'approved', 'approved_at' => now()]);
        $start = \Carbon\Carbon::parse($leave->start_date)->startOfDay();
        $end = \Carbon\Carbon::parse($leave->end_date)->endOfDay();
        $date = $start->copy();
        while ($date->lte($end)) {
            Attendance::updateOrCreate(
                ['employee_id' => $leave->employee_id, 'date' => $date->format('Y-m-d')],
                ['status' => 'on_leave']
            );
            $date->addDay();
        }
        Shift::where('employee_id', $leave->employee_id)
            ->whereBetween('shift_date', [$leave->start_date, $leave->end_date])
            ->update(['status' => 'missed']);
        \App\Models\AuditLog::log('approve_leave', $leave, null, ['approved' => true]);

        return back()->with('success', 'Leave approved');
    }

    public function rejectLeaveHR(LeaveRequest $leave)
    {
        $leave->update(['status' => 'rejected']);
        \App\Models\AuditLog::log('reject_leave', $leave, null, ['rejected' => true]);

        return back()->with('success', 'Leave rejected');
    }

    public function cancelLeave(LeaveRequest $leave)
    {
        if (in_array($leave->status, ['approved', 'pending'])) {
            $leave->update(['status' => 'cancelled']);
            \App\Models\AuditLog::log('cancel_leave', $leave, null, ['cancelled' => true]);

            return back()->with('success', 'Leave cancelled');
        }

        return back()->with('error', 'Unable to cancel');
    }

    public function endContract(Employee $employee, Request $request)
    {
        $old = $employee->getOriginal();
        $employee->update([
            'status' => 'terminated',
            'termination_date' => $request->get('termination_date', now()->format('Y-m-d')),
        ]);
        \App\Models\AuditLog::log('end_contract', $employee, $old, $employee->toArray());

        return redirect()->route('dashboard.hr.employees')->with('success', 'Contract ended');
    }

    /**
     * Create job position
     */
    /**
     * Job Positions Management
     */
    public function positions()
    {
        $positions = JobPosition::orderBy('created_at', 'desc')->paginate(15);
        $departments = Employee::distinct()->whereNotNull('department')->pluck('department');

        return view('dashboards.hr.positions', compact('positions', 'departments'));
    }

    /**
     * Create new job position
     */
    public function createJobPosition(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'required|array',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'application_deadline' => 'nullable|date|after:today',
        ]);

        JobPosition::create($request->all() + [
            'hiring_manager_id' => auth()->id(),
            'status' => 'active',
        ]);

        return redirect()->route('dashboard.hr.recruiting')
            ->with('success', 'Job position created successfully!');
    }

    /**
     * Applications Management
     */
    public function applications()
    {
        $applications = JobApplication::with('position')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboards.hr.applications', compact('applications'));
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:applied,screening,interview_scheduled,interviewed,offer_made,hired,rejected',
            'notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => $request->status,
            'interview_notes' => $request->notes,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Application status updated successfully',
            ]);
        }

        return back()->with('success', 'Application status updated successfully');
    }

    /**
     * Announcements
     */
    public function announcements()
    {
        $announcements = Announcement::with(['createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboards.hr.announcements', compact('announcements'));
    }

    /**
     * Create announcement
     */
    public function createAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,policy,event,urgent,celebration',
            'target_audience' => 'required|in:all,department,role,specific_users',
            'target_criteria' => 'nullable|array',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ]);

        Announcement::create($request->all() + [
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard.hr.announcements')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Helper Methods
     */
    private function generateEmployeeId()
    {
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;

        return 'EMP'.str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    private function assignEmployeeRole($user, $department, $position)
    {
        $roleMapping = [
            'IT' => 'it_admin',
            'Human Resources' => 'hr_coordinator',
            'Logistics' => 'logistics_coordinator',
            'Finance' => 'accountant',
            'Driver' => 'driver',
        ];

        $roleName = $roleMapping[$department] ?? 'employee';

        $role = \App\Models\Role::where('name', $roleName)->first();
        if ($role) {
            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'assigned_at' => now(),
                'assigned_by' => auth()->id(),
                'is_active' => true,
            ]);
        }
    }

    private function getDriversOnShift()
    {
        return Shift::whereDate('shift_date', today())
            ->where('status', 'in_progress')
            ->whereHas('employee.driver')
            ->count();
    }

    private function getRecentHires()
    {
        return Employee::with(['user'])
            ->where('hire_date', '>=', now()->subDays(30))
            ->orderBy('hire_date', 'desc')
            ->take(5)
            ->get();
    }

    private function getUpcomingReviews()
    {
        return PerformanceReview::with(['employee.user'])
            ->where('review_date', '>=', today())
            ->where('review_date', '<=', now()->addDays(7))
            ->where('status', 'draft')
            ->orderBy('review_date', 'asc')
            ->get();
    }

    private function getShiftAlerts()
    {
        $alerts = [];

        // Missed shifts today
        $missedShifts = Shift::whereDate('shift_date', today())
            ->where('status', 'missed')
            ->count();

        if ($missedShifts > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$missedShifts} employees missed their shifts today",
            ];
        }

        // Overtime alerts
        $overtimeShifts = Shift::whereDate('shift_date', today())
            ->where('hours_worked', '>', 10)
            ->count();

        if ($overtimeShifts > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$overtimeShifts} employees worked overtime today",
            ];
        }

        return $alerts;
    }

    /**
     * Employee Onboarding Management
     */
    public function getOnboardingTasks(Request $request)
    {
        $tasks = \App\Models\OnboardingTask::with(['employee.user', 'assignedTo.user'])
            ->when($request->employee_id, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('order')
            ->get();

        return response()->json($tasks);
    }

    public function createOnboardingTask(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'task_name' => 'required|string',
            'category' => 'required|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        $task = \App\Models\OnboardingTask::create($request->only([
            'employee_id', 'task_name', 'description', 'category',
            'due_date', 'assigned_to', 'order',
        ]));

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function completeOnboardingTask(\App\Models\OnboardingTask $task)
    {
        $task->complete();

        return response()->json(['success' => true]);
    }

    /**
     * Employee Engagement Surveys
     */
    public function getEngagementSurveys(Request $request)
    {
        $surveys = \App\Models\EmployeeEngagementSurvey::with('employee.user')
            ->when($request->employee_id, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($request->period, function ($query, $period) {
                $query->where('survey_period', $period);
            })
            ->orderBy('survey_period', 'desc')
            ->paginate(20);

        // Calculate average engagement scores
        $averageScores = \App\Models\EmployeeEngagementSurvey::select(
            DB::raw('AVG(overall_score) as avg_score'),
            DB::raw('AVG(job_satisfaction) as avg_job_satisfaction'),
            DB::raw('AVG(work_life_balance) as avg_work_life_balance'),
            DB::raw('AVG(management_rating) as avg_management')
        )
            ->whereNotNull('submitted_at')
            ->first();

        return response()->json([
            'surveys' => $surveys,
            'average_scores' => $averageScores,
        ]);
    }

    public function createEngagementSurvey(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'survey_period' => 'required|string',
            'job_satisfaction' => 'required|integer|min:1|max:5',
            'work_life_balance' => 'required|integer|min:1|max:5',
            'management_rating' => 'required|integer|min:1|max:5',
            'team_collaboration' => 'required|integer|min:1|max:5',
            'career_growth' => 'required|integer|min:1|max:5',
        ]);

        $survey = \App\Models\EmployeeEngagementSurvey::create($request->only([
            'employee_id', 'survey_period', 'job_satisfaction',
            'work_life_balance', 'management_rating', 'team_collaboration',
            'career_growth', 'comments',
        ]) + ['submitted_at' => now()]);

        return response()->json(['success' => true, 'survey' => $survey]);
    }

    /**
     * Training Records Management
     */
    public function getTrainingRecords(Request $request)
    {
        $records = \App\Models\EmployeeTrainingRecord::with('employee.user')
            ->when($request->employee_id, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->expiring_soon, function ($query) {
                $query->expiringSoon(30);
            })
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        // Get expiring soon count
        $expiringSoon = \App\Models\EmployeeTrainingRecord::expiringSoon(30)->count();
        $expired = \App\Models\EmployeeTrainingRecord::expired()->count();

        return response()->json([
            'records' => $records,
            'expiring_soon' => $expiringSoon,
            'expired' => $expired,
        ]);
    }

    public function createTrainingRecord(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'training_name' => 'required|string',
            'training_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'expiry_date' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $record = \App\Models\EmployeeTrainingRecord::create($request->only([
            'employee_id', 'training_name', 'training_type', 'description',
            'start_date', 'end_date', 'expiry_date', 'certificate_number',
            'provider', 'cost', 'notes', 'status',
        ]));

        return response()->json(['success' => true, 'record' => $record]);
    }

    /**
     * Leave Balance Management
     */
    public function getLeaveBalances(Request $request)
    {
        $balances = \App\Models\LeaveBalance::with('employee.user')
            ->when($request->employee_id, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($request->year, function ($query, $year) {
                $query->where('year', $year);
            })
            ->get();

        return response()->json($balances);
    }

    public function updateLeaveBalance(Request $request, \App\Models\LeaveBalance $balance)
    {
        $request->validate([
            'allocated_days' => 'required|numeric|min:0',
        ]);

        $balance->update([
            'allocated_days' => $request->allocated_days,
            'remaining_days' => $request->allocated_days - $balance->used_days,
        ]);

        return response()->json(['success' => true, 'balance' => $balance]);
    }

    public function syncLeaveBalances(Request $request)
    {
        $employees = Employee::all();
        $year = $request->get('year', now()->year);

        foreach ($employees as $employee) {
            $leaveTypes = ['annual', 'sick', 'personal', 'emergency'];

            foreach ($leaveTypes as $leaveType) {
                $balance = \App\Models\LeaveBalance::firstOrNew([
                    'employee_id' => $employee->id,
                    'leave_type' => $leaveType,
                    'year' => $year,
                ]);

                if (! $balance->exists) {
                    // Default allocation based on leave type
                    $allocated = match ($leaveType) {
                        'annual' => 21,
                        'sick' => 10,
                        'personal' => 5,
                        'emergency' => 3,
                        default => 0,
                    };
                    $balance->allocated_days = $allocated;
                    $balance->remaining_days = $allocated;
                }

                $balance->updateUsedDays();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Leave balances synchronized successfully',
        ]);
    }

    public function auditLogs(Request $request)
    {
        $logs = AuditLog::with(['user'])
            ->when($request->user_id, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($request->action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($request->model_type, function ($query, $modelType) {
                $query->where('model_type', $modelType);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $actions = AuditLog::distinct()->pluck('action');
        $modelTypes = AuditLog::distinct()->pluck('model_type');

        return view('dashboards.hr.audit-logs', compact('logs', 'actions', 'modelTypes'));
    }

    public function attendanceReports(Request $request)
    {
        $attendance = Attendance::with('employee.user')
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('date', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('date', '<=', $date);
            })
            ->when($request->employee_id, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->orderBy('date', 'desc')
            ->paginate(25);

        $employees = Employee::with('user')->where('status', 'active')->orderBy('hire_date', 'desc')->get();

        $summary = [
            'present' => Attendance::when($request->date_from, fn ($q) => $q->whereDate('date', '>=', $request->date_from))
                ->when($request->date_to, fn ($q) => $q->whereDate('date', '<=', $request->date_to))
                ->where('status', 'present')->count(),
            'late' => Attendance::when($request->date_from, fn ($q) => $q->whereDate('date', '>=', $request->date_from))
                ->when($request->date_to, fn ($q) => $q->whereDate('date', '<=', $request->date_to))
                ->where('status', 'late')->count(),
            'early_leave' => Attendance::when($request->date_from, fn ($q) => $q->whereDate('date', '>=', $request->date_from))
                ->when($request->date_to, fn ($q) => $q->whereDate('date', '<=', $request->date_to))
                ->where('status', 'early_leave')->count(),
            'absent' => Attendance::when($request->date_from, fn ($q) => $q->whereDate('date', '>=', $request->date_from))
                ->when($request->date_to, fn ($q) => $q->whereDate('date', '<=', $request->date_to))
                ->where('status', 'absent')->count(),
        ];

        return view('dashboards.hr.attendance-reports', compact('attendance', 'employees', 'summary'));
    }

    public function payrollReports(Request $request)
    {
        $records = PayrollRecord::with('employee.user')
            ->when($request->pay_period, function ($query, $period) {
                $query->where('pay_period', $period);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('pay_period', 'desc')
            ->paginate(25);

        $payPeriods = PayrollRecord::distinct()->pluck('pay_period')->sort()->reverse();

        $totals = [
            'gross' => PayrollRecord::when($request->pay_period, fn ($q, $p) => $q->where('pay_period', $p))
                ->when($request->status, fn ($q, $s) => $q->where('status', $s))
                ->sum('gross_pay') ?? 0,
            'net' => PayrollRecord::when($request->pay_period, fn ($q, $p) => $q->where('pay_period', $p))
                ->when($request->status, fn ($q, $s) => $q->where('status', $s))
                ->sum('net_pay') ?? 0,
            'count' => PayrollRecord::when($request->pay_period, fn ($q, $p) => $q->where('pay_period', $p))
                ->when($request->status, fn ($q,$s) => $q->where('status',$s))
                ->count(),
        ];

        return view('dashboards.hr.payroll-reports', compact('records', 'payPeriods', 'totals'));
    }
}
