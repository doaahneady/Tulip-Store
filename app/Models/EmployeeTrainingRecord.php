<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTrainingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'training_name',
        'training_type',
        'description',
        'start_date',
        'end_date',
        'expiry_date',
        'status',
        'certificate_number',
        'provider',
        'cost',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'expiry_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
