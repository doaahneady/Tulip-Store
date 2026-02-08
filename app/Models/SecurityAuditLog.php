<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'user_type',
        'user_id',
        'ip_address',
        'user_agent',
        'status',
        'description',
        'metadata',
        'risk_level',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->morphTo();
    }

    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    public function scopeHighRisk($query)
    {
        return $query->whereIn('risk_level', ['high', 'critical']);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
