<?php

namespace App\Services\Dashboard;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * HR Dashboard Service
 * 
 * Provides employee KPIs, attendance tracking, leave management, and payroll calculation.
 * 
 * @see Requirements 10.1, 10.2, 10.3, 10.4
 */
class HRDashboardService
{
    public function __construct(
        protected MetricsService $metricsService,
        protected AuditService $auditService
    ) {}

    /**
     * Get HR KPI metrics
     * 
     * @return array Array containing total_employees, present_today, on_leave, pending_requests
     * @see Requirements 10.1
     */
    public function getKPIMetrics(): array
    {
        $today = Carbon::today();
        
        // Total employees
        $totalEmployees = Employee::where('status', '!=', 'terminated')->count();
        $activeEmployees = Employee::where('status', 'active')->count();
        
        // Present today
        $presentToday = Attendance::whereDate('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();
        
        // On leave today
        $onLeaveToday = LeaveRequest::where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();
        
        // Pending leave requests
        $pendingRequests = LeaveRequest::where('status', 'pending')->count();
        
        // Absent today (employees who should be present but aren't)
        $absentToday = Attendance::whereDate('date', $today)
            ->where('status', 'absent')
            ->count();

        return [
            'total_employees' => [
                'value' => $totalEmployees,
                'active' => $activeEmployees,
            ],
            'present_today' => [
                'value' => $presentToday,
                'percentage' => $activeEmployees > 0 
                    ? round(($presentToday / $activeEmployees) * 100, 1) 
                    : 0,
            ],
            'on_leave' => [
                'value' => $onLeaveToday,
            ],
            'pending_requests' => [
                'value' => $pendingRequests,
            ],
            'absent_today' => [
                'value' => $absentToday,
            ],
        ];
    }


    /**
     * Get employees with pagination and filters
     * 
     * @param array $filters Filters including status, department, search, per_page
     * @return LengthAwarePaginator
     * @see Requirements 10.1
     */
    public function getEmployees(array $filters = []): LengthAwarePaginator
    {
        $query = Employee::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get attendance records with filters
     * 
     * @param array $filters Filters including employee_id, date_from, date_to, status, per_page
     * @return LengthAwarePaginator
     * @see Requirements 10.2
     */
    public function getAttendance(array $filters = []): LengthAwarePaginator
    {
        $query = Attendance::with('employee');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortBy = $filters['sort_by'] ?? 'date';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get today's attendance summary
     * 
     * @return array
     * @see Requirements 10.2
     */
    public function getTodayAttendance(): array
    {
        $today = Carbon::today();
        
        $attendance = Attendance::with('employee')
            ->whereDate('date', $today)
            ->get();

        return [
            'date' => $today->format('Y-m-d'),
            'present' => $attendance->where('status', 'present')->count(),
            'late' => $attendance->where('status', 'late')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'on_leave' => $attendance->where('status', 'on_leave')->count(),
            'half_day' => $attendance->where('status', 'half_day')->count(),
            'records' => $attendance,
        ];
    }

    /**
     * Record attendance for an employee
     * 
     * @param int $employeeId
     * @param array $data
     * @return Attendance
     */
    public function recordAttendance(int $employeeId, array $data): Attendance
    {
        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'date' => $data['date'] ?? Carbon::today()->format('Y-m-d'),
            ],
            [
                'check_in' => $data['check_in'] ?? null,
                'check_out' => $data['check_out'] ?? null,
                'work_hours' => $data['work_hours'] ?? null,
                'overtime_hours' => $data['overtime_hours'] ?? 0,
                'status' => $data['status'] ?? 'present',
                'notes' => $data['notes'] ?? null,
            ]
        );

        return $attendance;
    }


    /**
     * Get leave requests with filters
     * 
     * @param array $filters Filters including employee_id, status, leave_type, per_page
     * @return LengthAwarePaginator
     * @see Requirements 10.3
     */
    public function getLeaveRequests(array $filters = []): LengthAwarePaginator
    {
        $query = LeaveRequest::with(['employee', 'approver']);

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['leave_type'])) {
            $query->where('leave_type', $filters['leave_type']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('start_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('end_date', '<=', $filters['date_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get pending leave requests
     * 
     * @return Collection
     * @see Requirements 10.3
     */
    public function getPendingLeaveRequests(): Collection
    {
        return LeaveRequest::with('employee')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get employee's leave balance
     * 
     * @param int $employeeId
     * @param string $leaveType
     * @param int $year
     * @return array
     * @see Requirements 10.3
     */
    public function getLeaveBalance(int $employeeId, string $leaveType = 'annual', int $year = null): array
    {
        $year = $year ?? Carbon::now()->year;
        
        // Default annual leave allowance (can be configured per employee/company)
        $defaultAllowances = [
            'annual' => 21,
            'sick' => 14,
            'emergency' => 5,
            'unpaid' => 30,
            'maternity' => 90,
            'paternity' => 5,
        ];

        $totalAllowance = $defaultAllowances[$leaveType] ?? 0;

        // Calculate used leave days
        $usedDays = LeaveRequest::where('employee_id', $employeeId)
            ->where('leave_type', $leaveType)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum('days_count');

        // Calculate pending leave days
        $pendingDays = LeaveRequest::where('employee_id', $employeeId)
            ->where('leave_type', $leaveType)
            ->where('status', 'pending')
            ->whereYear('start_date', $year)
            ->sum('days_count');

        $remainingDays = $totalAllowance - $usedDays;

        return [
            'leave_type' => $leaveType,
            'year' => $year,
            'total_allowance' => $totalAllowance,
            'used_days' => (int) $usedDays,
            'pending_days' => (int) $pendingDays,
            'remaining_days' => max(0, $remainingDays),
        ];
    }

    /**
     * Approve a leave request and adjust leave balance
     * 
     * @param int $leaveRequestId
     * @param User $approver
     * @return LeaveRequest|null
     * @see Requirements 10.3
     */
    public function approveLeaveRequest(int $leaveRequestId, User $approver): ?LeaveRequest
    {
        $leaveRequest = LeaveRequest::find($leaveRequestId);

        if (!$leaveRequest || $leaveRequest->status !== 'pending') {
            return null;
        }

        DB::beginTransaction();

        try {
            // Update leave request status
            $leaveRequest->update([
                'status' => 'approved',
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);

            // Create attendance records for leave days
            $startDate = Carbon::parse($leaveRequest->start_date);
            $endDate = Carbon::parse($leaveRequest->end_date);

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Skip weekends (Friday and Saturday for Middle East, or Saturday and Sunday)
                if ($date->isWeekend()) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'employee_id' => $leaveRequest->employee_id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => 'on_leave',
                        'notes' => ucfirst($leaveRequest->leave_type) . ' leave',
                    ]
                );
            }

            // Log the approval action
            $this->auditService->log(
                'approve',
                'leave_request',
                $leaveRequestId,
                [
                    'new_values' => [
                        'status' => 'approved',
                        'employee_id' => $leaveRequest->employee_id,
                        'days_count' => $leaveRequest->days_count,
                        'leave_type' => $leaveRequest->leave_type,
                    ],
                ]
            );

            DB::commit();

            return $leaveRequest->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject a leave request
     * 
     * @param int $leaveRequestId
     * @param User $rejector
     * @param string|null $reason
     * @return LeaveRequest|null
     */
    public function rejectLeaveRequest(int $leaveRequestId, User $rejector, ?string $reason = null): ?LeaveRequest
    {
        $leaveRequest = LeaveRequest::find($leaveRequestId);

        if (!$leaveRequest || $leaveRequest->status !== 'pending') {
            return null;
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => $rejector->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Log the rejection action
        $this->auditService->log(
            'reject',
            'leave_request',
            $leaveRequestId,
            [
                'new_values' => [
                    'status' => 'rejected',
                    'reason' => $reason,
                ],
            ]
        );

        return $leaveRequest->fresh();
    }


    /**
     * Get payroll records with filters
     * 
     * @param array $filters Filters including employee_id, month, status, per_page
     * @return LengthAwarePaginator
     * @see Requirements 10.4
     */
    public function getPayroll(array $filters = []): LengthAwarePaginator
    {
        $query = Payroll::with('employee');

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (!empty($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Calculate payroll for an employee
     * 
     * Net salary = base_salary + bonuses - deductions - (absent_days * daily_rate)
     * 
     * @param int $employeeId
     * @param string $month Format: 'YYYY-MM'
     * @param array $adjustments Optional adjustments (bonuses, deductions, allowances)
     * @return Payroll
     * @see Requirements 10.4
     */
    public function calculatePayroll(int $employeeId, string $month, array $adjustments = []): Payroll
    {
        $employee = Employee::findOrFail($employeeId);
        
        // Parse month to get date range
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get basic salary
        $basicSalary = (float) $employee->salary;

        // Calculate daily rate (assuming 30 days per month)
        $dailyRate = $basicSalary / 30;

        // Count absent days (excluding weekends and approved leaves)
        $absentDays = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->where('status', 'absent')
            ->count();

        // Calculate overtime pay
        $totalOvertimeMinutes = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('overtime_hours');
        
        // Overtime rate: 1.5x hourly rate
        $hourlyRate = $dailyRate / 8;
        $overtimePay = ($totalOvertimeMinutes / 60) * $hourlyRate * 1.5;

        // Get adjustments
        $allowances = (float) ($adjustments['allowances'] ?? 0);
        $bonuses = (float) ($adjustments['bonuses'] ?? 0);
        $deductions = (float) ($adjustments['deductions'] ?? 0);
        $tax = (float) ($adjustments['tax'] ?? 0);
        $insurance = (float) ($adjustments['insurance'] ?? 0);

        // Calculate absent deduction
        $absentDeduction = $absentDays * $dailyRate;

        // Calculate net salary
        // Formula: base_salary + allowances + bonuses + overtime_pay - deductions - tax - insurance - absent_deduction
        $netSalary = $basicSalary + $allowances + $bonuses + $overtimePay - $deductions - $tax - $insurance - $absentDeduction;

        // Create or update payroll record
        $payroll = Payroll::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'month' => $month,
            ],
            [
                'basic_salary' => $basicSalary,
                'allowances' => $allowances,
                'bonuses' => $bonuses,
                'overtime_pay' => round($overtimePay, 2),
                'deductions' => $deductions + $absentDeduction,
                'tax' => $tax,
                'insurance' => $insurance,
                'net_salary' => round(max(0, $netSalary), 2),
                'status' => 'draft',
                'notes' => $absentDays > 0 ? "Absent days: {$absentDays}" : null,
            ]
        );

        return $payroll;
    }

    /**
     * Generate payroll for all active employees
     * 
     * @param string $month Format: 'YYYY-MM'
     * @return array
     * @see Requirements 10.4
     */
    public function generateMonthlyPayroll(string $month): array
    {
        $employees = Employee::where('status', 'active')->get();
        $generated = [];
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($employees as $employee) {
                try {
                    $payroll = $this->calculatePayroll($employee->id, $month);
                    $generated[] = $payroll;
                } catch (\Exception $e) {
                    $errors[] = [
                        'employee_id' => $employee->id,
                        'employee_name' => $employee->full_name,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
                'generated_count' => count($generated),
                'error_count' => count($errors),
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process payroll (mark as processed)
     * 
     * @param int $payrollId
     * @return Payroll|null
     */
    public function processPayroll(int $payrollId): ?Payroll
    {
        $payroll = Payroll::find($payrollId);

        if (!$payroll || $payroll->status !== 'draft') {
            return null;
        }

        $payroll->update([
            'status' => 'processed',
        ]);

        $this->auditService->log(
            'update',
            'payroll',
            $payrollId,
            [
                'new_values' => ['status' => 'processed'],
            ]
        );

        return $payroll->fresh();
    }

    /**
     * Mark payroll as paid
     * 
     * @param int $payrollId
     * @param Carbon|null $paymentDate
     * @return Payroll|null
     */
    public function markPayrollPaid(int $payrollId, ?Carbon $paymentDate = null): ?Payroll
    {
        $payroll = Payroll::find($payrollId);

        if (!$payroll || $payroll->status === 'paid') {
            return null;
        }

        $payroll->update([
            'status' => 'paid',
            'payment_date' => $paymentDate ?? Carbon::today(),
        ]);

        $this->auditService->log(
            'update',
            'payroll',
            $payrollId,
            [
                'new_values' => [
                    'status' => 'paid',
                    'payment_date' => $payroll->payment_date,
                ],
            ]
        );

        return $payroll->fresh();
    }


    /**
     * Get attendance chart data
     * 
     * @param string $period Period: 'week', 'month'
     * @return array
     */
    public function getAttendanceChartData(string $period = 'week'): array
    {
        $labels = [];
        $present = [];
        $absent = [];
        $late = [];

        $days = $period === 'week' ? 7 : 30;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format($period === 'week' ? 'D' : 'd');

            $dayAttendance = Attendance::whereDate('date', $date)->get();
            $present[] = $dayAttendance->where('status', 'present')->count();
            $absent[] = $dayAttendance->where('status', 'absent')->count();
            $late[] = $dayAttendance->where('status', 'late')->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Present', 'data' => $present, 'color' => 'green'],
                ['label' => 'Absent', 'data' => $absent, 'color' => 'red'],
                ['label' => 'Late', 'data' => $late, 'color' => 'yellow'],
            ],
        ];
    }

    /**
     * Get department statistics
     * 
     * @return Collection
     */
    public function getDepartmentStats(): Collection
    {
        return Employee::where('status', '!=', 'terminated')
            ->select('department')
            ->selectRaw('COUNT(*) as employee_count')
            ->selectRaw('AVG(salary) as avg_salary')
            ->groupBy('department')
            ->get();
    }

    /**
     * Get recent leave requests
     * 
     * @param int $limit
     * @return Collection
     */
    public function getRecentLeaveRequests(int $limit = 10): Collection
    {
        return LeaveRequest::with('employee')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get payroll summary for a month
     * 
     * @param string $month Format: 'YYYY-MM'
     * @return array
     */
    public function getPayrollSummary(string $month): array
    {
        $payrolls = Payroll::where('month', $month)->get();

        return [
            'month' => $month,
            'total_employees' => $payrolls->count(),
            'total_basic_salary' => $payrolls->sum('basic_salary'),
            'total_allowances' => $payrolls->sum('allowances'),
            'total_bonuses' => $payrolls->sum('bonuses'),
            'total_overtime' => $payrolls->sum('overtime_pay'),
            'total_deductions' => $payrolls->sum('deductions'),
            'total_tax' => $payrolls->sum('tax'),
            'total_insurance' => $payrolls->sum('insurance'),
            'total_net_salary' => $payrolls->sum('net_salary'),
            'draft_count' => $payrolls->where('status', 'draft')->count(),
            'processed_count' => $payrolls->where('status', 'processed')->count(),
            'paid_count' => $payrolls->where('status', 'paid')->count(),
        ];
    }

    /**
     * Get employee by ID
     * 
     * @param int $employeeId
     * @return Employee|null
     */
    public function getEmployee(int $employeeId): ?Employee
    {
        return Employee::with(['attendance', 'leaveRequests', 'payroll', 'performanceReviews'])
            ->find($employeeId);
    }

    /**
     * Create a new employee
     * 
     * @param array $data
     * @return Employee
     */
    public function createEmployee(array $data): Employee
    {
        $employee = Employee::create($data);

        $this->auditService->log(
            'create',
            'employee',
            $employee->id,
            [
                'new_values' => $data,
            ]
        );

        return $employee;
    }

    /**
     * Update an employee
     * 
     * @param int $employeeId
     * @param array $data
     * @return Employee|null
     */
    public function updateEmployee(int $employeeId, array $data): ?Employee
    {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return null;
        }

        $oldValues = $employee->toArray();
        $employee->update($data);

        $this->auditService->log(
            'update',
            'employee',
            $employeeId,
            [
                'old_values' => $oldValues,
                'new_values' => $data,
            ]
        );

        return $employee->fresh();
    }

    /**
     * Get all departments
     * 
     * @return Collection
     */
    public function getDepartments(): Collection
    {
        return Employee::distinct()
            ->pluck('department')
            ->filter()
            ->values();
    }
}
