<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteOptimization extends Model
{
    use HasFactory;

    protected $fillable = [
        'optimization_date',
        'delivery_ids',
        'driver_id',
        'total_distance_km',
        'estimated_duration_minutes',
        'fuel_cost',
        'route_path',
        'status',
        'savings_percentage',
    ];

    protected $casts = [
        'optimization_date' => 'date',
        'delivery_ids' => 'array',
        'total_distance_km' => 'decimal:2',
        'estimated_duration_minutes' => 'integer',
        'fuel_cost' => 'decimal:2',
        'route_path' => 'array',
        'savings_percentage' => 'decimal:2',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
