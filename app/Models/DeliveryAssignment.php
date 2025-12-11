<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'order_id',
        'status',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
        'delivery_latitude',
        'delivery_longitude',
        'notes',
        'customer_signature',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'assigned' => 'yellow',
            'picked_up' => 'blue',
            'in_transit' => 'purple',
            'delivered' => 'green',
            'failed' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
