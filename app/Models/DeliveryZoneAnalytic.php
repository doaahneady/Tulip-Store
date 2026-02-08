<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZoneAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_name',
        'analytics_date',
        'total_deliveries',
        'completed_deliveries',
        'failed_deliveries',
        'average_delivery_time_minutes',
        'average_delivery_cost',
        'customer_satisfaction_score',
        'peak_hours',
    ];

    protected $casts = [
        'analytics_date' => 'date',
        'total_deliveries' => 'integer',
        'completed_deliveries' => 'integer',
        'failed_deliveries' => 'integer',
        'average_delivery_time_minutes' => 'decimal:2',
        'average_delivery_cost' => 'decimal:2',
        'customer_satisfaction_score' => 'decimal:2',
        'peak_hours' => 'array',
    ];
}
