<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'license_number',
        'license_expiry',
        'vehicle_type',
        'vehicle_plate',
        'vehicle_info',
        'status',
        'availability',
        'working_hours',
        'last_location',
        'last_location_update',
        'total_deliveries',
        'rating',
        'current_speed',
        'current_heading',
    ];

    protected $casts = [
        'last_location_update' => 'datetime',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function employee()
    {
        // Link via user_id: employees.user_id = drivers.user_id (employees table has no driver_id)
        return $this->hasOne(Employee::class, 'user_id', 'user_id');
    }

    public function currentLocation()
    {
        return $this->hasOne(DriverLocation::class)->latestOfMany();
    }

    public function latestLocation()
    {
        return $this->hasOne(DriverLocation::class)->latestOfMany();
    }

    public function deliveryAssignments()
    {
        return $this->hasMany(DeliveryAssignment::class);
    }

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

    public function updateLocation($latitude, $longitude, $speed = null, $accuracy = null, $heading = null)
    {
        $updates = [];

        if (Schema::hasColumn('drivers', 'last_location') && Schema::getConnection()->getDriverName() === 'mysql') {
            $lng = (float) $longitude;
            $lat = (float) $latitude;
            $updates['last_location'] = DB::raw("ST_GeomFromText('POINT({$lng} {$lat})')");
        }
        if (Schema::hasColumn('drivers', 'last_location_update')) {
            $updates['last_location_update'] = now();
        }
        if (Schema::hasColumn('drivers', 'current_speed')) {
            $updates['current_speed'] = $speed;
        }
        if (Schema::hasColumn('drivers', 'current_heading')) {
            $updates['current_heading'] = $heading;
        }

        if (! empty($updates)) {
            $this->forceFill($updates)->save();
        }

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
        return match ($this->availability) {
            'available' => 'green',
            'busy' => 'blue',
            'on_break' => 'yellow',
            'offline' => 'gray',
            default => 'gray',
        };
    }
}
