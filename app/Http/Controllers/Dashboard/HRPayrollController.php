<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HRPayrollController extends Controller
{
    // ============================================
    // PAYROLL MANAGEMENT (Flows 21-27)
    // ============================================

    public function index()
    {
        $payrolls = Payroll::with('employee')->orderBy('month', 'desc')->paginate(20);

        $stats = [
            'total_this_month' => Payroll::whereMonth('month', now()->month)->sum('net_salary'),
            'pending' => Payroll::where('status', 'pending')->count(),
            'paid' => Payroll::where('status', 'paid')->count(),
            'employees_count' => Employee::where('status', 'active')->count(),
        ];

        return view('dashboards.hr.payroll.index', compact('payrolls', 'stats'));
    }

    // Flow 21: Define Base Salary (in employee creation/update)

    // Flow 25: Generate Payroll
    public function create()
    {
        $employees = Employee::where('status', 'active')->get();

        return view('dashboards.hr.payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|date|date_format:Y-m-d',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::find($validated['employee_id']);

        // Flow 22: Calculate Overtime
        $overtimePay = $this->calculateOvertime($employee, $validated['month']);

        // Flow 23: Calculate Deductions
        $deductions = $this->calculateDeductions($employee, $validated['month']);

        // Flow 24: Apply Bonuses (from request)
        $bonuses = $validated['bonuses'] ?? 0;

        // Calculate totals
        $grossSalary = $validated['basic_salary'] + ($validated['allowances'] ?? 0) + $bonuses + $overtimePay;
        $totalDeductions = $deductions + ($validated['deductions'] ?? 0);

        // Tax and insurance (5% tax, 2% insurance)
        $tax = $grossSalary * 0.05;
        $insurance = $grossSalary * 0.02;

        $netSalary = $grossSalary - $totalDeductions - $tax - $insurance;

        Payroll::create([
            'employee_id' => $validated['employee_id'],
            'month' => $validated['month'],
            'basic_salary' => $validated['basic_salary'],
            'allowances' => $validated['allowances'] ?? 0,
            'bonuses' => $bonuses,
            'overtime_pay' => $overtimePay,
            'deductions' => $totalDeductions,
            'tax' => $tax,
            'insurance' => $insurance,
            'net_salary' => $netSalary,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard.hr.payroll')->with('success', 'تم إنشاء كشف الراتب بنجاح');
    }

    // Flow 26: Approve Payroll
    public function approve(Payroll $payroll)
    {
        $payroll->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth('employee')->id(),
        ]);

        return back()->with('success', 'تم اعتماد كشف الراتب');
    }

    // Flow 27: Send to Finance
    public function sendToFinance(Payroll $payroll)
    {
        if ($payroll->status !== 'approved') {
            return back()->with('error', 'يجب اعتماد كشف الراتب أولاً');
        }

        $payroll->update([
            'status' => 'sent_to_finance',
            'sent_to_finance_at' => now(),
        ]);

        // Here you would trigger the finance dashboard notification
        // or create a payment request in the finance system

        return back()->with('success', 'تم إرسال كشف الراتب إلى المالية');
    }

    public function markAsPaid(Payroll $payroll)
    {
        $payroll->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return back()->with('success', 'تم تحديث حالة الراتب إلى مدفوع');
    }

    // Flow 22: Calculate Overtime
    private function calculateOvertime($employee, $month)
    {
        $monthDate = Carbon::parse($month);
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $monthDate->year)
            ->whereMonth('date', $monthDate->month)
            ->get();

        $totalOvertimeHours = $attendance->sum('overtime_hours') ?? 0;

        // Overtime rate: 1.5x hourly rate
        $hourlyRate = ($employee->salary ?? 0) / 160; // Assuming 160 hours/month
        $overtimePay = $totalOvertimeHours * $hourlyRate * 1.5;

        return $overtimePay;
    }

    // Flow 23: Calculate Deductions
    private function calculateDeductions($employee, $month)
    {
        $monthDate = Carbon::parse($month);
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $monthDate->year)
            ->whereMonth('date', $monthDate->month)
            ->get();

        $deductions = 0;

        // Deduct for absences
        $absences = $attendance->where('status', 'absent')->count();
        $dailyRate = ($employee->salary ?? 0) / 30;
        $deductions += $absences * $dailyRate;

        // Deduct for late arrivals (e.g., 50 SAR per late)
        $lateCount = $attendance->where('status', 'late')->count();
        $deductions += $lateCount * 50;

        return $deductions;
    }

    // Flow 28: Payroll Reports
    public function reports()
    {
        $monthlyPayroll = Payroll::selectRaw('DATE_FORMAT(month, "%Y-%m") as month, SUM(net_salary) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('dashboards.hr.payroll.reports', compact('monthlyPayroll'));
    }
}
