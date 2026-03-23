<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDashboardOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'dashboard_key',
        'is_override',
        'can_view',
        'can_edit',
        'sections',
        'actions',
        'can_view_sensitive',
    ];

    protected $casts = [
        'is_override' => 'boolean',
        'can_view' => 'boolean',
        'can_edit' => 'boolean',
        'sections' => 'array',
        'actions' => 'array',
        'can_view_sensitive' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

