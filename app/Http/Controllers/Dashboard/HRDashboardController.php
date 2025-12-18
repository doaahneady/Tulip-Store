<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\ExportService;
use App\Services\Dashboard\HRDashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * HR Dashboard Controller
 * 
 * Handles all HR dashboard functionality including:
 * - Dashboard overview with employee KPIs
 * - Employee management
 * - Attendance tracking
 * - Leave management
 * - Payroll processing
 * 
 * @see Requirements 10.1, 10.2, 10.5
 */
class HRDashboardController extends Controller
{
    public function __construct(
        protected HRDashboardService $hrService,
        protected AuditService $auditService,
        protected ExportService $exportService
    ) {
        // Apply HR role middleware to all methods
        $this->middleware('dashboard.role:hr,admin');
    }

    /**
     * Display the HR dashboard overview
     * Shows KPI cards, attendance trends, and recent activity
     * 
     * @see Requirements 10.1
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'week');

        $data = [
            'kpis' => $this->hrService->getKPIMetrics(),
            'todayAttendance' => $this->hrService->getTodayAttendance(),
            'attendanceChart' => $this->hrService->getAttendanceChartData($period),
            'departmentStats' => $this->hrService->getDepartmentStats(),
            'pendingLeaveRequests' => $this->hrService->getPendingLeaveRequests(),
            'recentLeaveRequests' => $this->hrService->getRecentLeaveRequests(5),
            'payrollSummary' => $this->hrService->getPayrollSummary(Carbon::now()->format('Y-m')),
            'period' => $period,
        ];

        return view('dashboard.hr.index', $data);
    }


    /**
     * Display employees page
     * Shows paginated list of employees with filters
     * 
     * @see Requirements 10.1
     */
    public function employees(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'status' => $request->get('status'),
            'department' => $request->get('department'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $employees = $this->hrService->getEmployees($filters);
        $departments = $this->hrService->getDepartments();

        return view('dashboard.hr.employees', [
            'employees' => $employees,
            'departments' => $departments,
            'filters' => $filters,
        ]);
    }

    /**
     * Display single employee details
     * 
     * @param int $employeeId The employee ID
     */
    public function showEmployee(int $employeeId)
    {
        $employee = $this->hrService->getEmployee($employeeId);

        if (!$employee) {
            return redirect()->route('dashboard.hr.employees')
                ->with('error', __('Employee not found.'));
        }

        $leaveBalances = [];
        foreach (['annual', 'sick', 'emergency'] as $leaveType) {
            $leaveBalances[$leaveType] = $this->hrService->getLeaveBalance($employeeId, $leaveType);
        }

        return view('dashboard.hr.employee-show', [
            'employee' => $employee,
            'leaveBalances' => $leaveBalances,
        ]);
    }

    /**
     * Store a new employee
     */
    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => 'required|string|unique:employees,employee_code',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'employment_type' => 'sometimes|string|in:full-time,part-time,contract',
            'national_id' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $employee = $this->hrService->createEmployee($validated);

        return redirect()->route('dashboard.hr.employees')
            ->with('success', __('Employee created successfully.'));
    }

    /**
     * Update an employee
     */
    public function updateEmployee(Request $request, int $employeeId)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:employees,email,' . $employeeId,
            'phone' => 'sometimes|string|max:20',
            'department' => 'sometimes|string|max:255',
            'position' => 'sometimes|string|max:255',
            'salary' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:active,on_leave,suspended,terminated',
            'employment_type' => 'sometimes|string|in:full-time,part-time,contract',
        ]);

        $employee = $this->hrService->updateEmployee($employeeId, $validated);

        if (!$employee) {
            return redirect()->back()->with('error', __('Employee not found.'));
        }

        return redirect()->back()->with('success', __('Employee updated successfully.'));
    }


    /**
     * Display attendance page
     * Shows attendance records with filters
     * 
     * @see Requirements 10.2
     */
    public function attendance(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'employee_id' => $request->get('employee_id'),
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort_by' => $request->get('sort_by', 'date'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $attendance = $this->hrService->getAttendance($filters);
        $todayAttendance = $this->hrService->getTodayAttendance();
        $employees = $this->hrService->getEmployees(['per_page' => 1000]);

        return view('dashboard.hr.attendance', [
            'attendance' => $attendance,
            'todayAttendance' => $todayAttendance,
            'employees' => $employees,
            'filters' => $filters,
        ]);
    }

    /**
     * Record attendance for an employee
     */
    public function recordAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:present,absent,late,half_day,on_leave',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance = $this->hrService->recordAttendance(
            $validated['employee_id'],
            $validated
        );

        return redirect()->back()->with('success', __('Attendance recorded successfully.'));
    }

    /**
     * Display leave requests page
     * Shows leave requests with filters
     * 
     * @see Requirements 10.3
     */
    public function leaves(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'employee_id' => $request->get('employee_id'),
            'status' => $request->get('status'),
            'leave_type' => $request->get('leave_type'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $leaveRequests = $this->hrService->getLeaveRequests($filters);
        $pendingRequests = $this->hrService->getPendingLeaveRequests();
        $employees = $this->hrService->getEmployees(['per_page' => 1000]);

        return view('dashboard.hr.leaves', [
            'leaveRequests' => $leaveRequests,
            'pendingRequests' => $pendingRequests,
            'employees' => $employees,
            'filters' => $filters,
        ]);
    }

    /**
     * Approve a leave request
     * 
     * @see Requirements 10.3
     */
    public function approveLeave(int $leaveRequestId)
    {
        $leaveRequest = $this->hrService->approveLeaveRequest($leaveRequestId, Auth::user());

        if (!$leaveRequest) {
            return redirect()->back()->with('error', __('Leave request not found or already processed.'));
        }

        return redirect()->back()->with('success', __('Leave request approved successfully.'));
    }

    /**
     * Reject a leave request
     */
    public function rejectLeave(Request $request, int $leaveRequestId)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $leaveRequest = $this->hrService->rejectLeaveRequest(
            $leaveRequestId,
            Auth::user(),
            $validated['rejection_reason'] ?? null
        );

        if (!$leaveRequest) {
            return redirect()->back()->with('error', __('Leave request not found or already processed.'));
        }

        return redirect()->back()->with('success', __('Leave request rejected.'));
    }


    /**
     * Display payroll page
     * Shows payroll records with filters
     * 
     * @see Requirements 10.4
     */
    public function payroll(Request $request)
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $selectedMonth = $request->get('month', $currentMonth);

        $filters = [
            'per_page' => $request->get('per_page', 25),
            'employee_id' => $request->get('employee_id'),
            'month' => $selectedMonth,
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $payroll = $this->hrService->getPayroll($filters);
        $payrollSummary = $this->hrService->getPayrollSummary($selectedMonth);
        $employees = $this->hrService->getEmployees(['per_page' => 1000]);

        return view('dashboard.hr.payroll', [
            'payroll' => $payroll,
            'payrollSummary' => $payrollSummary,
            'employees' => $employees,
            'selectedMonth' => $selectedMonth,
            'filters' => $filters,
        ]);
    }

    /**
     * Calculate payroll for an employee
     * 
     * @see Requirements 10.4
     */
    public function calculatePayroll(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'month' => 'required|date_format:Y-m',
            'allowances' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'insurance' => 'nullable|numeric|min:0',
        ]);

        $adjustments = [
            'allowances' => $validated['allowances'] ?? 0,
            'bonuses' => $validated['bonuses'] ?? 0,
            'deductions' => $validated['deductions'] ?? 0,
            'tax' => $validated['tax'] ?? 0,
            'insurance' => $validated['insurance'] ?? 0,
        ];

        $payroll = $this->hrService->calculatePayroll(
            $validated['employee_id'],
            $validated['month'],
            $adjustments
        );

        return redirect()->back()->with('success', __('Payroll calculated successfully.'));
    }

    /**
     * Generate payroll for all employees
     * 
     * @see Requirements 10.4
     */
    public function generatePayroll(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $result = $this->hrService->generateMonthlyPayroll($validated['month']);

        if ($result['success']) {
            return redirect()->back()->with('success', 
                __('Payroll generated for :count employees.', ['count' => $result['generated_count']])
            );
        }

        return redirect()->back()->with('error', __('Failed to generate payroll.'));
    }

    /**
     * Process payroll (mark as processed)
     */
    public function processPayroll(int $payrollId)
    {
        $payroll = $this->hrService->processPayroll($payrollId);

        if (!$payroll) {
            return redirect()->back()->with('error', __('Payroll not found or already processed.'));
        }

        return redirect()->back()->with('success', __('Payroll processed successfully.'));
    }

    /**
     * Mark payroll as paid
     */
    public function markPayrollPaid(Request $request, int $payrollId)
    {
        $validated = $request->validate([
            'payment_date' => 'nullable|date',
        ]);

        $paymentDate = isset($validated['payment_date']) 
            ? Carbon::parse($validated['payment_date']) 
            : null;

        $payroll = $this->hrService->markPayrollPaid($payrollId, $paymentDate);

        if (!$payroll) {
            return redirect()->back()->with('error', __('Payroll not found or already paid.'));
        }

        return redirect()->back()->with('success', __('Payroll marked as paid.'));
    }


    /**
     * Export employees to CSV
     */
    public function exportEmployees(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'department' => $request->get('department'),
            'per_page' => 10000, // Get all for export
        ];

        $employees = $this->hrService->getEmployees($filters);

        $columns = [
            'employee_code' => 'Employee Code',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'department' => 'Department',
            'position' => 'Position',
            'hire_date' => 'Hire Date',
            'salary' => 'Salary',
            'status' => 'Status',
        ];

        // Log the export action
        $this->auditService->log(
            'export',
            'employee',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $employees->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $employees->getCollection(),
            $columns,
            'employees_' . date('Y-m-d') . '.csv'
        );
    }

    /**
     * Export attendance to CSV
     */
    public function exportAttendance(Request $request)
    {
        $filters = [
            'employee_id' => $request->get('employee_id'),
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => 10000, // Get all for export
        ];

        $attendance = $this->hrService->getAttendance($filters);

        $columns = [
            'employee.employee_code' => 'Employee Code',
            'employee.full_name' => 'Employee Name',
            'date' => 'Date',
            'check_in' => 'Check In',
            'check_out' => 'Check Out',
            'work_hours' => 'Work Hours',
            'overtime_hours' => 'Overtime',
            'status' => 'Status',
        ];

        // Log the export action
        $this->auditService->log(
            'export',
            'attendance',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $attendance->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $attendance->getCollection(),
            $columns,
            'attendance_' . date('Y-m-d') . '.csv'
        );
    }

    /**
     * Export payroll to CSV
     */
    public function exportPayroll(Request $request)
    {
        $filters = [
            'month' => $request->get('month'),
            'status' => $request->get('status'),
            'per_page' => 10000, // Get all for export
        ];

        $payroll = $this->hrService->getPayroll($filters);

        $columns = [
            'employee.employee_code' => 'Employee Code',
            'employee.full_name' => 'Employee Name',
            'month' => 'Month',
            'basic_salary' => 'Basic Salary',
            'allowances' => 'Allowances',
            'bonuses' => 'Bonuses',
            'overtime_pay' => 'Overtime Pay',
            'deductions' => 'Deductions',
            'tax' => 'Tax',
            'insurance' => 'Insurance',
            'net_salary' => 'Net Salary',
            'status' => 'Status',
            'payment_date' => 'Payment Date',
        ];

        // Log the export action
        $this->auditService->log(
            'export',
            'payroll',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $payroll->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $payroll->getCollection(),
            $columns,
            'payroll_' . date('Y-m-d') . '.csv'
        );
    }
}
