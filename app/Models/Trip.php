<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'driver_id',
        'pickup_address',
        'pickup_latitude',
        'pickup_longitude',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'distance',
        'estimated_time',
        'actual_time',
        'status',
        'payment_amount',
        'payment_status',
        'assigned_at',
        'accepted_at',
        'picked_up_at',
        'delivered_at',
        'cancelled_at',
        'notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'accepted_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'payment_amount' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // Status helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isInProgress()
    {
        return in_array($this->status, ['accepted', 'picked_up', 'in_transit']);
    }

    public function isCompleted()
    {
        return $this->status === 'delivered';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    // Calculate payment based on distance
    public function calculatePayment()
    {
        // Base rate + per km rate
        $baseRate = 10; // Base payment
        $perKmRate = 2; // Per kilometer
        
        $payment = $baseRate + ($this->distance * $perKmRate);
        
        $this->update(['payment_amount' => $payment]);
        
        return $payment;
    }

    // Status color for UI
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'accepted' => 'blue',
            'picked_up' => 'purple',
            'in_transit' => 'indigo',
            'delivered' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    // Status text in Arabic
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'accepted' => 'مقبولة',
            'picked_up' => 'تم الاستلام',
            'in_transit' => 'في الطريق',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغاة',
            default => $this->status,
        };
    }
}
