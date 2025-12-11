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
        'recorded_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
