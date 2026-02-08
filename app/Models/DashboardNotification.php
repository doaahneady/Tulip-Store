<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'dashboard_type',
        'user_type',
        'user_id',
        'type',
        'title',
        'message',
        'action_url',
        'icon',
        'color',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->morphTo();
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForDashboard($query, $dashboardType)
    {
        return $query->where('dashboard_type', $dashboardType);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
