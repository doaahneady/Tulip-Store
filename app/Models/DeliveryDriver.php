<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDriver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_name',
        'phone',
        'vehicle_type',
        'vehicle_plate',
        'license_number',
        'status',
        'current_latitude',
        'current_longitude',
        'last_location_update',
        'total_deliveries',
        'rating',
        'is_active'
    ];

    protected $casts = [
        'last_location_update' => 'datetime',
        'rating' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locationHistory()
    {
        return $this->hasMany(DriverLocationHistory::class, 'driver_id');
    }

    public function assignments()
    {
        return $this->hasMany(DeliveryAssignment::class, 'driver_id');
    }

    public function performance()
    {
        return $this->hasMany(DriverPerformance::class, 'driver_id');
    }

    public function currentAssignment()
    {
        return $this->hasOne(DeliveryAssignment::class, 'driver_id')
            ->whereIn('status', ['assigned', 'picked_up', 'in_transit'])
            ->latest();
    }

    public function updateLocation($latitude, $longitude, $speed = null, $accuracy = null, $battery = null)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'last_location_update' => now()
        ]);

        $this->locationHistory()->create([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'speed' => $speed,
            'accuracy' => $accuracy,
            'battery_level' => $battery,
            'recorded_at' => now()
        ]);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'available' => '#047857',
            'busy' => '#d97706',
            'on_break' => '#6b7280',
            'offline' => '#dc2626',
            default => '#6b7280'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'available' => 'متاح',
            'busy' => 'مشغول',
            'on_break' => 'استراحة',
            'offline' => 'غير متصل',
            default => 'غير معروف'
        };
    }
}
