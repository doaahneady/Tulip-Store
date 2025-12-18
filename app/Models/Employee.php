<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'employee_code', 'first_name', 'last_name', 'email', 'phone',
        'national_id', 'date_of_birth', 'gender', 'marital_status', 'address',
        'city', 'country', 'department', 'position', 'employment_type',
        'hire_date', 'contract_end_date', 'salary', 'bank_name', 'bank_account',
        'iban', 'emergency_contact_name', 'emergency_contact_phone',
        'emergency_contact_relation', 'status', 'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'contract_end_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function trainingEnrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the driver record associated with this employee (if they are a driver)
     */
    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id', 'user_id');
    }
}
