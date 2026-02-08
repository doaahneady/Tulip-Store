<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dashboard_type',
        'metric_type',
        'condition',
        'threshold_value',
        'duration_minutes',
        'severity',
        'is_active',
        'notification_channels',
        'description',
    ];

    protected $casts = [
        'threshold_value' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
        'notification_channels' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDashboard($query, $dashboardType)
    {
        return $query->where('dashboard_type', $dashboardType);
    }

    public function checkCondition($currentValue)
    {
        return match ($this->condition) {
            '>' => $currentValue > $this->threshold_value,
            '<' => $currentValue < $this->threshold_value,
            '>=' => $currentValue >= $this->threshold_value,
            '<=' => $currentValue <= $this->threshold_value,
            '==' => $currentValue == $this->threshold_value,
            default => false,
        };
    }
}
