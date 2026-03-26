<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HRDashboardController extends Controller
{
    // ============================================
    // MAIN DASHBOARD
    // ============================================

    public function index()
    {
        $metrics = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'new_hires_month' => Employee::whereMonth('created_at', now()->month)->count(),
            'on_leave_today' => $this->safeQuery(fn () => LeaveRequest::where('status', 'approved')
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())->count(), 0),
            'present_today' => $this->safeQuery(fn () => Attendance::whereDate('date', today())
                ->where('status', 'present')->count(), 0),
            'absent_today' => $this->safeQuery(fn () => Attendance::whereDate('date', today())
                ->where('status', 'absent')->count(), 0),
            'late_today' => $this->safeQuery(fn () => Attendance::whereDate('date', today())
                ->where('status', 'late')->count(), 0),
            'pending_leaves' => $this->safeQuery(fn () => LeaveRequest::where('status', 'pending')->count(), 0),
            'pending_payroll' => $this->safeQuery(fn () => Payroll::where('status', 'pending')->count(), 0),
        ];

        $recentEmployees = Employee::orderBy('created_at', 'desc')->take(5)->get();
        $pendingLeaves = $this->safeQuery(fn () => LeaveRequest::with('employee')->where('status', 'pending')
            ->orderBy('created_at', 'desc')->take(10)->get(), collect());
        $todayAttendance = $this->safeQuery(fn () => Attendance::with('employee')->whereDate('date', today())
            ->orderBy('check_in', 'desc')->take(10)->get(), collect());

        $departments = Employee::select('department', DB::raw('COUNT(*) as count'))
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->groupBy('department')
            ->orderBy('department')
            ->get()
            ->map(fn ($row) => ['name' => $row->department, 'count' => (int) $row->count]);

        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $upcomingBirthdays = $this->safeQuery(function () use ($startOfWeek, $endOfWeek) {
            $candidates = Employee::whereNotNull('date_of_birth')
                ->where(function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->whereMonth('date_of_birth', $startOfWeek->month)
                        ->orWhereMonth('date_of_birth', $endOfWeek->month);
                })->get();

            return $candidates->filter(function ($e) use ($startOfWeek, $endOfWeek) {
                if (! $e->date_of_birth) {
                    return false;
                }
                $d = Carbon::parse($e->date_of_birth)->setYear(now()->year);

                return $d->between($startOfWeek, $endOfWeek);
            })->take(10)->values();
        }, collect());

        $workAnniversaries = $this->safeQuery(function () use ($startOfWeek, $endOfWeek) {
            $candidates = Employee::whereNotNull('hire_date')
                ->where(function ($q) use ($startOfWeek, $endOfWeek) {
                    $q->whereMonth('hire_date', $startOfWeek->month)
                        ->orWhereMonth('hire_date', $endOfWeek->month);
                })->get();

            return $candidates->filter(function ($e) use ($startOfWeek, $endOfWeek) {
                if (! $e->hire_date) {
                    return false;
                }
                $d = Carbon::parse($e->hire_date)->setYear(now()->year);

                return $d->between($startOfWeek, $endOfWeek);
            })->take(10)->values();
        }, collect());

        $scheduledLeaves = $this->safeQuery(function () use ($startOfWeek, $endOfWeek) {
            return LeaveRequest::with('employee')
                ->whereIn('status', ['approved', 'pending'])
                ->whereDate('start_date', '>=', $startOfWeek->toDateString())
                ->whereDate('start_date', '<=', $endOfWeek->toDateString())
                ->orderBy('start_date', 'asc')
                ->take(10)
                ->get();
        }, collect());

        return view('dashboards.hr.index', compact(
            'metrics',
            'recentEmployees',
            'pendingLeaves',
            'todayAttendance',
            'departments',
            'upcomingBirthdays',
            'workAnniversaries',
            'scheduledLeaves'
        ));
    }

    // ============================================
    // EMPLOYEE MANAGEMENT (Flows 1-5)
    // ============================================

    // Flow 1: Create Employee Profile
    public function employees()
    {
        $employees = Employee::orderBy('created_at', 'desc')->paginate(20);

        return view('dashboards.hr.employees.index', compact('employees'));
    }

    public function createEmployee()
    {
        return view('dashboards.hr.employees.create');
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'employment_type' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'contract_end_date' => 'nullable|date',
        ]);

        $validated['password'] = Hash::make('password123');
        $validated['status'] = 'active';
        $validated['employee_code'] = 'EMP-'.str_pad(Employee::count() + 1, 5, '0', STR_PAD_LEFT);

        Employee::create($validated);

        return redirect()->route('dashboard.hr.employees')->with('success', 'تم إضافة الموظف بنجاح');
    }

    // Flow 2: Update Employee Data
    public function editEmployee(Employee $employee)
    {
        return view('dashboards.hr.employees.edit', compact('employee'));
    }

    public function updateEmployee(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
        ]);

        $employee->update($validated);

        return redirect()->route('dashboard.hr.employees')->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    // Flow 3: Assign Department
    public function assignDepartment(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'department' => 'required|string|max:100',
        ]);

        $employee->update($validated);

        return back()->with('success', 'تم تعيين القسم بنجاح');
    }

    // Flow 4: Assign Role
    public function assignRole(Request $request, Employee $employee)
    {
        $employee->update([
            'is_admin' => $request->has('is_admin'),
            'is_it' => $request->has('is_it'),
            'is_hr' => $request->has('is_hr'),
            'is_finance' => $request->has('is_finance'),
            'is_driver_supervisor' => $request->has('is_driver_supervisor'),
            'is_trader' => $request->has('is_trader'),
        ]);

        return back()->with('success', 'تم تعيين الصلاحيات بنجاح');
    }

    // Flow 5: End Contract
    public function endContract(Request $request, Employee $employee)
    {
        $employee->update([
            'status' => 'inactive',
            'contract_end_date' => now(),
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'تم إنهاء العقد بنجاح');
    }

    // ============================================
    // ATTENDANCE MANAGEMENT (Flows 6-10)
    // ============================================

    public function attendance()
    {
        $attendance = $this->safeQuery(fn () => Attendance::with('employee')
            ->orderBy('date', 'desc')->paginate(30), collect());
        $employees = Employee::where('status', 'active')->get();

        $stats = [
            'present_today' => $this->safeQuery(fn () => Attendance::whereDate('date', today())->where('status', 'present')->count(), 0),
            'absent_today' => $this->safeQuery(fn () => Attendance::whereDate('date', today())->where('status', 'absent')->count(), 0),
            'late_today' => $this->safeQuery(fn () => Attendance::whereDate('date', today())->where('status', 'late')->count(), 0),
        ];

        return view('dashboards.hr.attendance.index', compact('attendance', 'employees', 'stats'));
    }

    // Flow 6: Clock-In
    public function clockIn(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $checkIn = now()->format('H:i:s');
        $workStartTime = '09:00:00';
        $status = $checkIn > $workStartTime ? 'late' : 'present';

        Attendance::create([
            'employee_id' => $validated['employee_id'],
            'date' => today(),
            'check_in' => $checkIn,
            'status' => $status,
        ]);

        return back()->with('success', 'تم تسجيل الحضور بنجاح');
    }

    // Flow 7: Clock-Out
    public function clockOut(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $attendance = Attendance::where('employee_id', $validated['employee_id'])
            ->whereDate('date', today())
            ->first();

        if ($attendance) {
            $checkOut = now()->format('H:i:s');
            $checkIn = Carbon::parse($attendance->check_in);
            $checkOutTime = Carbon::parse($checkOut);
            $workHours = $checkIn->diffInHours($checkOutTime);

            $attendance->update([
                'check_out' => $checkOut,
                'work_hours' => $workHours,
            ]);

            return back()->with('success', 'تم تسجيل الانصراف بنجاح');
        }

        return back()->with('error', 'لم يتم العثور على سجل حضور');
    }

    // Flow 8: Late Detection (automatic)
    // Flow 9: Early Leave Detection (automatic)

    // Flow 10: Missing Attendance Handling
    public function recordAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date|date_format:Y-m-d',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,leave',
            'notes' => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            $validated
        );

        return back()->with('success', 'تم تسجيل الحضور بنجاح');
    }

    private function safeQuery($callback, $default)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $default;
        }
    }
}
