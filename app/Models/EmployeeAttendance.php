<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $table = 'employee_attendance';

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'break_minutes',
        'total_hours',
        'status',
        'notes',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'total_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'present' => 'text-green-600 bg-green-100',
            'late' => 'text-yellow-600 bg-yellow-100',
            'half_day' => 'text-orange-600 bg-orange-100',
            'absent' => 'text-red-600 bg-red-100',
            'sick_leave' => 'text-purple-600 bg-purple-100',
            'holiday' => 'text-blue-600 bg-blue-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getFormattedTotalHoursAttribute()
    {
        return $this->total_hours ? number_format($this->total_hours, 2).' hrs' : 'N/A';
    }

    public function calculateTotalHours()
    {
        if (! $this->clock_in || ! $this->clock_out) {
            return 0;
        }

        $totalMinutes = $this->clock_in->diffInMinutes($this->clock_out);
        $totalMinutes -= $this->break_minutes;

        return round($totalMinutes / 60, 2);
    }

    public function getIsLateAttribute()
    {
        if (! $this->clock_in) {
            return false;
        }

        $standardStartTime = $this->clock_in->copy()->setTime(9, 0, 0);

        return $this->clock_in->gt($standardStartTime);
    }
}
