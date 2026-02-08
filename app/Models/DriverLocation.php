<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'latitude',
        'longitude',
        'speed',
        'accuracy',
        'address',
        'recorded_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'speed' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function scopeRecent($query, $minutes = 30)
    {
        return $query->where('recorded_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeByDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function getIsRecentAttribute()
    {
        return $this->recorded_at->diffInMinutes(now()) <= 5;
    }

    public function getDistanceFromAttribute()
    {
        return function ($latitude, $longitude) {
            return $this->calculateDistance($this->latitude, $this->longitude, $latitude, $longitude);
        };
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public static function updateLocation($driverId, $latitude, $longitude, $metadata = [])
    {
        return static::create([
            'driver_id' => $driverId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'accuracy' => $metadata['accuracy'] ?? null,
            'speed' => $metadata['speed'] ?? null,
            'address' => $metadata['address'] ?? null,
            'recorded_at' => now(),
        ]);
    }
}
