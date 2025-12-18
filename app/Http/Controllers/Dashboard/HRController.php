<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\PayrollRecord;
use App\Models\PerformanceReview;
use App\Models\JobPosition;
use App\Models\JobApplication;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Driver;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HRController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:hr_manager,hr_coordinator']);
    }

    /**
     * HR Dashboard
     */
    public function index()
    {
        $metrics = $this->getHRMetrics();
        return view('dashboards.hr.index', compact('metrics'));
    }

    /**
     * Get HR dashboard metrics
     */
    private function getHRMetrics()
    {
        return [
            // Employee Metrics - Using mock data for now
            'total_employees' => 156,
            'active_employees' => 142,
            'new_hires_month' => 8,
            'employees_on_leave' => 6,
            
            // Department Breakdown
            'department_breakdown' => [
                'IT' => 25,
                'HR' => 8,
                'Finance' => 12,
                'Operations' => 45,
                'Customer Service' => 32,
                'Marketing' => 15,
                'Sales' => 20
            ],
            
            // Shift Management
            'scheduled_shifts_today' => 45,
            'active_shifts' => 18,
            'completed_shifts_today' => 32,
            'missed_shifts_today' => 2,
            
            // Driver-specific metrics
            'total_drivers' => 45,
            'active_drivers' => 32,
            'drivers_on_shift' => 18,
            
            // Payroll Metrics
            'pending_payroll' => 5,
            'monthly_payroll_cost' => 485000,
            'overtime_hours_month' => 156,
            
            // Performance & Reviews
            'pending_reviews' => 12,
            'overdue_reviews' => 3,
            'avg_performance_rating' => 4.2,
            
            // Recruiting
            'open_positions' => 8,
            'pending_applications' => 23,
            'interviews_scheduled' => 6,
            
            // Recent Activity
            'recent_hires' => [],
            'upcoming_reviews' => [],
            'shift_alerts' => [],
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
            
            return redirect()->route('hr.employees')
                ->with('success', 'Employee created successfully! Default password: password123');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create employee: ' . $e->getMessage());
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

        $employee->update($request->all());

        return redirect()->route('hr.employees')
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
            'notes' => 'nullable|string'
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

        Shift::create($request->all() + ['status' => 'scheduled']);

        return redirect()->route('hr.shifts')
            ->with('success', 'Shift scheduled successfully!');
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

    /**
     * Calculate payroll for period
     */
    public function calculatePayroll(Request $request)
    {
        $request->validate([
            'pay_period' => 'required|string', // Format: YYYY-MM
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start'
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
                $grossPay = $regularPay + $overtimePay;
                $netPay = $grossPay * 0.85; // 15% deductions (taxes, etc.)

                $payrollRecord = PayrollRecord::create([
                    'employee_id' => $employee->id,
                    'pay_period' => $request->pay_period,
                    'regular_hours' => $regularHours,
                    'overtime_hours' => $overtimeHours,
                    'regular_pay' => $regularPay,
                    'overtime_pay' => $overtimePay,
                    'gross_pay' => $grossPay,
                    'net_pay' => $netPay,
                    'status' => 'draft'
                ]);

                $payrollRecords[] = $payrollRecord;
            }

            DB::commit();
            
            return redirect()->route('hr.payroll')
                ->with('success', 'Payroll calculated for ' . count($payrollRecords) . ' employees.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to calculate payroll: ' . $e->getMessage());
        }
    }

    /**
     * Submit payroll to Finance
     */
    public function submitPayroll(Request $request)
    {
        $request->validate([
            'pay_period' => 'required|string'
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
                    'transaction_id' => 'payroll_' . $record->id . '_' . time(),
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
                        'gross_pay' => $record->gross_pay
                    ]
                ]);
            }

            DB::commit();

            // Notify Finance team
            broadcast(new \App\Events\PayrollSubmitted($request->pay_period, $payrollRecords->sum('net_pay')));
            
            return redirect()->route('hr.payroll')
                ->with('success', 'Payroll submitted to Finance for processing.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to submit payroll: ' . $e->getMessage());
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
            'overall_rating' => 'required|numeric|min:1|max:5'
        ]);

        PerformanceReview::create($request->all() + [
            'reviewer_id' => auth()->id(),
            'status' => 'draft'
        ]);

        return redirect()->route('hr.performance-reviews')
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

    /**
     * Create job position
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
            'application_deadline' => 'nullable|date|after:today'
        ]);

        JobPosition::create($request->all() + [
            'hiring_manager_id' => auth()->id(),
            'status' => 'active'
        ]);

        return redirect()->route('hr.recruiting')
            ->with('success', 'Job position created successfully!');
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:applied,screening,interview_scheduled,interviewed,offer_made,hired,rejected',
            'notes' => 'nullable|string'
        ]);

        $application->update([
            'status' => $request->status,
            'interview_notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully'
        ]);
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
            'expires_at' => 'nullable|date|after:published_at'
        ]);

        Announcement::create($request->all() + [
            'created_by' => auth()->id()
        ]);

        return redirect()->route('hr.announcements')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Helper Methods
     */
    private function generateEmployeeId()
    {
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;
        return 'EMP' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
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
                'message' => "{$missedShifts} employees missed their shifts today"
            ];
        }

        // Overtime alerts
        $overtimeShifts = Shift::whereDate('shift_date', today())
            ->where('hours_worked', '>', 10)
            ->count();

        if ($overtimeShifts > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$overtimeShifts} employees worked overtime today"
            ];
        }

        return $alerts;
    }
}