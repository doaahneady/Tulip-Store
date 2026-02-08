<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'dashboard_type',
        'activity_type',
        'action',
        'title',
        'description',
        'actor_type',
        'actor_id',
        'target_type',
        'target_id',
        'metadata',
        'severity',
        'is_read',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_read' => 'boolean',
    ];

    public function actor()
    {
        return $this->morphTo();
    }

    public function target()
    {
        return $this->morphTo();
    }

    public function scopeForDashboard($query, $dashboardType)
    {
        return $query->where('dashboard_type', $dashboardType);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
