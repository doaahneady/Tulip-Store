<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnhancedSupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'subject',
        'description',
        'priority',
        'status',
        'category',
        'tags',
        'first_response_at',
        'resolved_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(EnhancedSupportTicketReply::class, 'ticket_id');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'waiting_customer']);
    }

    public function scopeClosed($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    public function scopeAssignedTo($query, $employeeId)
    {
        return $query->where('assigned_to', $employeeId);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'open' => 'text-red-600 bg-red-100',
            'in_progress' => 'text-yellow-600 bg-yellow-100',
            'waiting_customer' => 'text-blue-600 bg-blue-100',
            'resolved' => 'text-green-600 bg-green-100',
            'closed' => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            'low' => 'text-green-600 bg-green-100',
            'medium' => 'text-yellow-600 bg-yellow-100',
            'high' => 'text-orange-600 bg-orange-100',
            'urgent' => 'text-red-600 bg-red-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getResponseTimeAttribute()
    {
        if (! $this->first_response_at) {
            return null;
        }

        return $this->created_at->diffInMinutes($this->first_response_at);
    }

    public function getResolutionTimeAttribute()
    {
        if (! $this->resolved_at) {
            return null;
        }

        return $this->created_at->diffInHours($this->resolved_at);
    }

    public function getIsOverdueAttribute()
    {
        if (in_array($this->status, ['resolved', 'closed'])) {
            return false;
        }

        $slaHours = match ($this->priority) {
            'urgent' => 2,
            'high' => 8,
            'medium' => 24,
            'low' => 72,
            default => 24,
        };

        return $this->created_at->addHours($slaHours)->isPast();
    }

    public static function generateTicketNumber()
    {
        $prefix = 'TKT';
        $timestamp = now()->format('ymd');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix.'-'.$timestamp.'-'.$random;
    }

    public function assign($employeeId)
    {
        return $this->update([
            'assigned_to' => $employeeId,
            'status' => 'in_progress',
        ]);
    }

    public function resolve($notes = null)
    {
        $updates = [
            'status' => 'resolved',
            'resolved_at' => now(),
        ];

        if ($notes) {
            // Add resolution notes as a reply
            $this->replies()->create([
                'author_type' => 'App\\Models\\Employee',
                'author_id' => auth()->id(),
                'message' => $notes,
                'is_internal' => false,
            ]);
        }

        return $this->update($updates);
    }

    public function close()
    {
        return $this->update(['status' => 'closed']);
    }
}
