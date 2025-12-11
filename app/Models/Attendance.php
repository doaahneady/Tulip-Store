<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'employee_id', 'date', 'check_in', 'check_out',
        'work_hours', 'overtime_hours', 'status', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'work_hours' => 'integer',
        'overtime_hours' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
