<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_date',
        'start_time',
        'end_time',
        'actual_start_time',
        'actual_end_time',
        'break_duration',
        'hours_worked',
        'overtime_hours',
        'status',
        'notes',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'break_duration' => 'decimal:2',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateHours()
    {
        if ($this->actual_start_time && $this->actual_end_time) {
            $start = $this->actual_start_time;
            $end = $this->actual_end_time;
            $totalMinutes = $end->diffInMinutes($start);
            $breakMinutes = $this->break_duration * 60;
            $workedMinutes = $totalMinutes - $breakMinutes;
            
            $this->hours_worked = round($workedMinutes / 60, 2);
            $this->overtime_hours = max(0, $this->hours_worked - 8);
            $this->save();
        }
    }
}