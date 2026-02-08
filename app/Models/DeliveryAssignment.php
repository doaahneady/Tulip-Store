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
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    // Removed assignedBy relationship as column doesn't exist

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['assigned', 'picked_up', 'in_transit']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'assigned' => 'text-blue-600 bg-blue-100',
            'accepted' => 'text-green-600 bg-green-100',
            'picked_up' => 'text-yellow-600 bg-yellow-100',
            'in_transit' => 'text-orange-600 bg-orange-100',
            'delivered' => 'text-green-600 bg-green-100',
            'failed' => 'text-red-600 bg-red-100',
            'cancelled' => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'assigned' => 'fa-clipboard-list',
            'picked_up' => 'fa-box',
            'in_transit' => 'fa-truck',
            'delivered' => 'fa-check-circle',
            'failed' => 'fa-times-circle',
            'cancelled' => 'fa-ban',
            default => 'fa-question',
        };
    }

    public function getDeliveryTimeAttribute()
    {
        if (! $this->delivered_at || ! $this->assigned_at) {
            return null;
        }

        return $this->assigned_at->diffInMinutes($this->delivered_at);
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->status, ['assigned', 'picked_up', 'in_transit']);
    }

    public function updateStatus($status, $notes = null, $signature = null)
    {
        $updates = ['status' => $status];

        switch ($status) {
            case 'picked_up':
                $updates['picked_up_at'] = now();
                break;
            case 'delivered':
                $updates['delivered_at'] = now();
                if ($signature) {
                    $updates['customer_signature'] = $signature;
                }
                break;
        }

        if ($notes) {
            $updates['notes'] = $notes;
        }

        return $this->update($updates);
    }
}
