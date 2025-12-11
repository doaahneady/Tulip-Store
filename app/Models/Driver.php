<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'license_number',
        'vehicle_type',
        'vehicle_plate',
        'status',
        'current_latitude',
        'current_longitude',
        'last_location_update',
        'total_deliveries',
        'rating',
        'is_active',
    ];

    protected $casts = [
        'last_location_update' => 'datetime',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function locations()
    {
        return $this->hasMany(DriverLocation::class);
    }

    public function assignments()
    {
        return $this->hasMany(DeliveryAssignment::class);
    }

    public function activeAssignments()
    {
        return $this->hasMany(DeliveryAssignment::class)
            ->whereIn('status', ['assigned', 'picked_up', 'in_transit']);
    }

    public function updateLocation($latitude, $longitude, $speed = null, $accuracy = null)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'last_location_update' => now(),
        ]);

        $this->locations()->create([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'speed' => $speed,
            'accuracy' => $accuracy,
            'recorded_at' => now(),
        ]);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'available' => 'green',
            'busy' => 'blue',
            'on_break' => 'yellow',
            'offline' => 'gray',
            default => 'gray',
        };
    }
}
