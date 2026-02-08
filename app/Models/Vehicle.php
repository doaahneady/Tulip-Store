<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_type',
        'plate_number',
        'make',
        'model',
        'year',
        'color',
        'vin',
        'status',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'year' => 'integer',
        'metadata' => 'array',
    ];

    public function driver()
    {
        return $this->hasOne(Driver::class, 'vehicle_id');
    }
}
