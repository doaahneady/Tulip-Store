<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'route_date',
        'waypoints',
        'optimized_sequence',
        'total_distance',
        'estimated_duration',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'route_date' => 'date',
        'waypoints' => 'array',
        'optimized_sequence' => 'array',
        'total_distance' => 'decimal:2',
        'estimated_duration' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function assignments()
    {
        return $this->hasMany(DeliveryAssignment::class, 'driver_id', 'driver_id')
                    ->whereDate('assigned_at', $this->route_date);
    }
}