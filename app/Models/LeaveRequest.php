<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'days_count',
        'reason',
        'status',
        'approved_by',
        'approval_notes',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
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

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('leave_type', $type);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'approved' => 'text-green-600 bg-green-100',
            'pending' => 'text-yellow-600 bg-yellow-100',
            'rejected' => 'text-red-600 bg-red-100',
            'cancelled' => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getTypeColorAttribute()
    {
        return match ($this->leave_type) {
            'annual' => 'text-blue-600 bg-blue-100',
            'sick' => 'text-red-600 bg-red-100',
            'emergency' => 'text-orange-600 bg-orange-100',
            'unpaid' => 'text-gray-600 bg-gray-100',
            'maternity' => 'text-pink-600 bg-pink-100',
            'paternity' => 'text-indigo-600 bg-indigo-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getFormattedDurationAttribute()
    {
        $days = $this->days_count;

        return $days.' '.($days === 1 ? 'day' : 'days');
    }

    public function getIsActiveAttribute()
    {
        if ($this->status !== 'approved') {
            return false;
        }

        $today = now()->toDateString();

        return $today >= $this->start_date && $today <= $this->end_date;
    }

    public function getIsUpcomingAttribute()
    {
        if ($this->status !== 'approved') {
            return false;
        }

        return now()->toDateString() < $this->start_date;
    }

    public function calculateDays()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function approve($approverId, $notes = null)
    {
        return $this->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
        ]);
    }

    public function reject($approverId, $notes = null)
    {
        return $this->update([
            'status' => 'rejected',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'rejection_reason' => $notes,
        ]);
    }
}
