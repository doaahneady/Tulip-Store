<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDashboardPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'dashboard_key',
    ];

    protected $casts = [
        'employee_id' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
