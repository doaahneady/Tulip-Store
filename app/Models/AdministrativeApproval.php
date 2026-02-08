<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrativeApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_employee_id',
        'category',
        'amount',
        'start_date',
        'end_date',
        'details',
        'status',
        'decided_by_employee_id',
        'decided_by_role',
        'decided_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'decided_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(Employee::class, 'requester_employee_id');
    }

    public function decidedBy()
    {
        return $this->belongsTo(Employee::class, 'decided_by_employee_id');
    }
}
