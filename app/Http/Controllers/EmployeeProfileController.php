<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\PayrollRecord;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display the employee's profile
     */
    public function show()
    {
        $employee = Auth::guard('employee')->user();

        $attendanceStats = [
            'present_month' => Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'present')->count(),
            'late_month' => Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'late')->count(),
            'early_leave_month' => Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'early_leave')->count(),
        ];

        $leaveSummary = [
            'pending' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'rejected')->count(),
        ];

        $payrollSummary = [
            'last_period' => PayrollRecord::where('employee_id', $employee->id)->orderBy('pay_period', 'desc')->value('pay_period'),
            'last_net_pay' => PayrollRecord::where('employee_id', $employee->id)->orderBy('pay_period', 'desc')->value('net_pay') ?? 0,
            'approved_count' => PayrollRecord::where('employee_id', $employee->id)->where('status', 'approved')->count(),
        ];

        $upcomingShifts = Shift::where('employee_id', $employee->id)
            ->whereDate('shift_date', '>=', today())
            ->orderBy('shift_date')
            ->take(5)
            ->get();

        return view('employee.profile.show', compact('employee', 'attendanceStats', 'leaveSummary', 'payrollSummary', 'upcomingShifts'));
    }

    /**
     * Show the form for editing the employee's profile
     */
    public function edit()
    {
        $employee = Auth::guard('employee')->user();
        $managers = Employee::where('is_manager', true)
            ->where('id', '!=', $employee->id)
            ->get();

        return view('employee.profile.edit', compact('employee', 'managers'));
    }

    /**
     * Update the employee's profile
     */
    public function update(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'phone' => 'required|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
            'preferred_communication' => 'nullable|in:email,phone,whatsapp,teams',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'email', 'phone', 'bio',
            'address', 'city', 'country', 'emergency_contact_name',
            'emergency_contact_phone', 'emergency_contact_relation',
            'preferred_communication', 'skills', 'languages',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($employee->profile_photo) {
                Storage::disk('public')->delete($employee->profile_photo);
            }

            $path = $request->file('profile_photo')->store('employee-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $employee->update($data);

        return redirect()->route('employee.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update employee password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $employee = Auth::guard('employee')->user();

        if (! Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $employee->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Toggle two-factor authentication
     */
    public function toggleTwoFactor(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $employee->update([
            'two_factor_enabled' => ! $employee->two_factor_enabled,
        ]);

        $status = $employee->two_factor_enabled ? 'enabled' : 'disabled';

        return back()->with('success', "Two-factor authentication {$status} successfully!");
    }

    /**
     * Show all employees (for managers and HR)
     */
    public function index(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        // Check if user can view employee list
        if (! $employee->hasPermission('manage_employees')) {
            abort(403, 'Unauthorized access.');
        }

        $query = Employee::with('manager');

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(20);
        $departments = Employee::distinct()->pluck('department')->filter();

        return view('employee.profile.index', compact('employees', 'departments'));
    }

    /**
     * Show specific employee profile (for managers and HR)
     */
    public function showEmployee(Employee $employee)
    {
        $currentEmployee = Auth::guard('employee')->user();

        // Check if user can view this employee
        if (! $currentEmployee->hasPermission('manage_employees') &&
            $currentEmployee->id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $attendanceStats = [
            'present_month' => Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'present')->count(),
            'late_month' => Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'late')->count(),
            'early_leave_month' => Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'early_leave')->count(),
        ];

        $leaveSummary = [
            'pending' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'pending')->count(),
            'approved' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'approved')->count(),
            'rejected' => LeaveRequest::where('employee_id', $employee->id)->where('status', 'rejected')->count(),
        ];

        $payrollSummary = [
            'last_period' => PayrollRecord::where('employee_id', $employee->id)->orderBy('pay_period', 'desc')->value('pay_period'),
            'last_net_pay' => PayrollRecord::where('employee_id', $employee->id)->orderBy('pay_period', 'desc')->value('net_pay') ?? 0,
            'approved_count' => PayrollRecord::where('employee_id', $employee->id)->where('status', 'approved')->count(),
        ];

        $upcomingShifts = Shift::where('employee_id', $employee->id)
            ->whereDate('shift_date', '>=', today())
            ->orderBy('shift_date')
            ->take(5)
            ->get();

        return view('employee.profile.show-employee', compact('employee', 'attendanceStats', 'leaveSummary', 'payrollSummary', 'upcomingShifts'));
    }

    /**
     * Update employee information (for managers and HR)
     */
    public function updateEmployee(Request $request, Employee $employee)
    {
        $currentEmployee = Auth::guard('employee')->user();

        // Check if user can update this employee
        if (! $currentEmployee->hasPermission('manage_employees')) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'status' => 'required|in:active,inactive,suspended,terminated',
            'salary' => 'nullable|numeric|min:0',
            'manager_id' => 'nullable|exists:employees,id',
            'security_level' => 'required|in:1,2,3,4,5',
            'approval_limit' => 'nullable|numeric|min:0',
            'performance_score' => 'nullable|numeric|between:0,5',
            'qualifications' => 'nullable|array',
            'certifications' => 'nullable|array',
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'email', 'phone', 'department',
            'position', 'employment_type', 'status', 'salary', 'manager_id',
            'security_level', 'approval_limit', 'performance_score',
            'qualifications', 'certifications',
        ]);

        $employee->update($data);

        return redirect()->route('employee.profile.show-employee', $employee)
            ->with('success', 'Employee information updated successfully!');
    }

    /**
     * Get employee statistics for dashboard
     */
    public function getStats()
    {
        $currentEmployee = Auth::guard('employee')->user();

        if (! $currentEmployee->hasPermission('manage_employees')) {
            abort(403, 'Unauthorized access.');
        }

        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'departments' => Employee::distinct()->count('department'),
            'managers' => Employee::where('is_manager', true)->count(),
            'recent_hires' => Employee::where('hire_date', '>=', now()->subDays(30))->count(),
            'pending_reviews' => Employee::where('next_review_date', '<=', now())->count(),
        ];

        return response()->json($stats);
    }
}
