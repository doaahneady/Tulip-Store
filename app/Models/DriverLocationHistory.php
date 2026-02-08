<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLocationHistory extends Model
{
    use HasFactory;

    protected $table = 'driver_location_history';

    protected $fillable = [
        'driver_id',
        'latitude',
        'longitude',
        'speed',
        'accuracy',
        'battery_level',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
