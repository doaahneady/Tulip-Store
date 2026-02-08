<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'pay_period',
        'base_salary',
        'overtime_hours',
        'overtime_rate',
        'overtime_pay',
        'bonuses',
        'commissions',
        'deductions',
        'gross_pay',
        'tax_deductions',
        'net_pay',
        'status',
        'processed_by',
        'processed_at',
        'breakdown',
    ];

    protected $casts = [
        'overtime_hours' => 'decimal:2',
        'base_salary' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'commissions' => 'decimal:2',
        'deductions' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'tax_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'processed_at' => 'datetime',
        'breakdown' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'processed_by');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('pay_period', $period);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'paid' => 'text-green-600 bg-green-100',
            'approved' => 'text-blue-600 bg-blue-100',
            'draft' => 'text-yellow-600 bg-yellow-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getFormattedPeriodAttribute()
    {
        return date('F Y', strtotime($this->pay_period.'-01'));
    }

    public function getFormattedGrossPayAttribute()
    {
        return '$'.number_format($this->gross_pay, 2);
    }

    public function getFormattedNetPayAttribute()
    {
        return '$'.number_format($this->net_pay, 2);
    }

    public function calculateGrossPay()
    {
        return $this->base_salary + $this->overtime_pay + $this->bonuses + $this->commissions;
    }

    public function calculateNetPay()
    {
        return $this->gross_pay - $this->deductions - $this->tax_deductions;
    }

    public function calculateTaxDeductions()
    {
        // Simple tax calculation - 15% of gross pay
        return $this->gross_pay * 0.15;
    }

    public function approve($processorId)
    {
        return $this->update([
            'status' => 'approved',
            'processed_by' => $processorId,
            'processed_at' => now(),
        ]);
    }

    public function markAsPaid()
    {
        return $this->update([
            'status' => 'paid',
        ]);
    }

    public static function generateForEmployee($employeeId, $period)
    {
        $employee = Employee::find($employeeId);
        if (! $employee) {
            return null;
        }

        // Calculate attendance-based salary
        $attendance = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereMonth('date', date('m', strtotime($period.'-01')))
            ->whereYear('date', date('Y', strtotime($period.'-01')))
            ->get();

        $totalHours = $attendance->sum('total_hours');
        $overtimeHours = max(0, $totalHours - 160); // Assuming 160 regular hours per month
        $regularHours = min($totalHours, 160);

        $baseSalary = $employee->salary ?? 3000;
        $hourlyRate = $baseSalary / 160;
        $overtimeRate = $hourlyRate * 1.5;
        $overtimePay = $overtimeHours * $overtimeRate;

        $grossPay = $baseSalary + $overtimePay;
        $taxDeductions = $grossPay * 0.15;
        $netPay = $grossPay - $taxDeductions;

        return static::create([
            'employee_id' => $employeeId,
            'pay_period' => $period,
            'base_salary' => $baseSalary,
            'overtime_hours' => $overtimeHours,
            'overtime_rate' => $overtimeRate,
            'overtime_pay' => $overtimePay,
            'bonuses' => 0,
            'commissions' => 0,
            'deductions' => 0,
            'gross_pay' => $grossPay,
            'tax_deductions' => $taxDeductions,
            'net_pay' => $netPay,
            'status' => 'draft',
        ]);
    }
}
