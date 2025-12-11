<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\PerformanceReview;
use App\Models\TrainingProgram;
use App\Models\TrainingEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HRController extends Controller
{
    public function index()
    {
        $stats = [
            'total_employees' => Employee::where('status', 'active')->count(),
            'on_leave' => Employee::where('status', 'on_leave')->count(),
            'pending_leave_requests' => LeaveRequest::where('status', 'pending')->count(),
            'present_today' => Attendance::whereDate('date', today())
                ->where('status', 'present')->count(),
        ];

        $recentEmployees = Employee::latest()->take(5)->get();
        $pendingLeaves = LeaveRequest::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('hr.dashboard', compact('stats', 'recentEmployees', 'pendingLeaves'));
    }

    // Employee Management
    public function employees()
    {
        $employees = Employee::latest()->paginate(20);
        return view('hr.employees.index', compact('employees'));
    }

    public function createEmployee()
    {
        return view('hr.employees.create');
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        $validated['employee_code'] = 'EMP' . str_pad(Employee::count() + 1, 5, '0', STR_PAD_LEFT);
        
        Employee::create($validated);

        return redirect()->route('hr.employees')->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function editEmployee(Employee $employee)
    {
        return view('hr.employees.edit', compact('employee'));
    }

    public function updateEmployee(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'salary' => 'required|numeric|min:0',
        ]);

        $employee->update($validated);

        return redirect()->route('hr.employees')->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    // Attendance Management
    public function attendance()
    {
        $today = today();
        $attendance = Attendance::with('employee')
            ->whereDate('date', $today)
            ->get();

        return view('hr.attendance.index', compact('attendance', 'today'));
    }

    public function markAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,half_day,on_leave',
        ]);

        Attendance::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date' => $validated['date']
            ],
            $validated
        );

        return back()->with('success', 'تم تسجيل الحضور بنجاح');
    }

    // Leave Management
    public function leaveRequests()
    {
        $leaves = LeaveRequest::with('employee')->latest()->paginate(20);
        return view('hr.leaves.index', compact('leaves'));
    }

    public function approveLeave(LeaveRequest $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'تم الموافقة على الإجازة');
    }

    public function rejectLeave(Request $request, LeaveRequest $leave)
    {
        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'تم رفض الإجازة');
    }

    // Payroll Management
    public function payroll()
    {
        $currentMonth = now()->format('Y-m');
        $payrolls = Payroll::with('employee')
            ->where('month', $currentMonth)
            ->get();

        return view('hr.payroll.index', compact('payrolls', 'currentMonth'));
    }

    public function generatePayroll(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $employees = Employee::where('status', 'active')->get();

        foreach ($employees as $employee) {
            Payroll::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'month' => $month
                ],
                [
                    'basic_salary' => $employee->salary,
                    'net_salary' => $employee->salary,
                    'status' => 'draft'
                ]
            );
        }

        return back()->with('success', 'تم إنشاء كشف الرواتب');
    }

    public function processPayroll(Payroll $payroll)
    {
        $payroll->update([
            'status' => 'processed',
            'payment_date' => now(),
        ]);

        return back()->with('success', 'تم معالجة الراتب');
    }

    // Performance Reviews
    public function performanceReviews()
    {
        $reviews = PerformanceReview::with(['employee', 'reviewer'])
            ->latest()
            ->paginate(20);

        return view('hr.performance.index', compact('reviews'));
    }

    public function createReview()
    {
        $employees = Employee::where('status', 'active')->get();
        return view('hr.performance.create', compact('employees'));
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string',
            'review_date' => 'required|date',
            'performance_score' => 'required|integer|min:0|max:100',
            'attendance_score' => 'required|integer|min:0|max:100',
            'quality_score' => 'required|integer|min:0|max:100',
            'teamwork_score' => 'required|integer|min:0|max:100',
            'overall_rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['reviewer_id'] = auth()->id();

        PerformanceReview::create($validated);

        return redirect()->route('hr.performance')->with('success', 'تم إضافة التقييم بنجاح');
    }

    // Training Programs
    public function trainingPrograms()
    {
        $programs = TrainingProgram::latest()->paginate(20);
        return view('hr.training.index', compact('programs'));
    }

    public function createTraining()
    {
        return view('hr.training.create');
    }

    public function storeTraining(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trainer' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration_hours' => 'required|integer|min:1',
        ]);

        TrainingProgram::create($validated);

        return redirect()->route('hr.training')->with('success', 'تم إضافة البرنامج التدريبي بنجاح');
    }

    // Reports
    public function reports()
    {
        return view('hr.reports.index');
    }

    public function attendanceReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $report = Attendance::with('employee')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy('employee_id');

        return view('hr.reports.attendance', compact('report', 'startDate', 'endDate'));
    }
}
