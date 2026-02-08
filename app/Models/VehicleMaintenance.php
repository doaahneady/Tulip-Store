<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMaintenance extends Model
{
    use HasFactory;

    protected $table = 'vehicle_maintenance';

    protected $fillable = [
        'driver_id',
        'type',
        'description',
        'cost',
        'maintenance_date',
        'next_due_date',
        'odometer_reading',
        'status',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'maintenance_date' => 'date',
        'next_due_date' => 'date',
        'odometer_reading' => 'integer',
        'attachments' => 'array',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('next_due_date', '<=', now()->addDays($days))
            ->where('status', 'scheduled');
    }

    public function scopeOverdue($query)
    {
        return $query->where('next_due_date', '<', now())
            ->where('status', 'scheduled');
    }
}
