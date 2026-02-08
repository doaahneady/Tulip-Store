<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'year',
        'allocated_days',
        'used_days',
        'remaining_days',
        'carried_over',
    ];

    protected $casts = [
        'year' => 'integer',
        'allocated_days' => 'decimal:2',
        'used_days' => 'decimal:2',
        'remaining_days' => 'decimal:2',
        'carried_over' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function updateUsedDays()
    {
        $usedDays = \App\Models\LeaveRequest::where('employee_id', $this->employee_id)
            ->where('leave_type', $this->leave_type)
            ->whereYear('start_date', $this->year)
            ->where('status', 'approved')
            ->sum('days_count') ?? 0;

        $this->update([
            'used_days' => $usedDays,
            'remaining_days' => $this->allocated_days - $usedDays,
        ]);
    }
}
