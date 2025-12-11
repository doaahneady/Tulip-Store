<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'recipient_name',
        'phone',
        'village',
        'address_note',
        'latitude',
        'longitude',
        'delivery_method',
        'payment_method',
        'status',
        'payment_status',
        'payment_receipt',
        'subtotal',
        'delivery_cost',
        'service_fee',
        'total',
        'estimated_delivery',
        'assigned_driver_id',
        'assigned_at',
        'assigned_by',
        'confirmation_token',
        'confirmed_at',
        'customer_signature',
        'signed_at',
        'delivery_notes'
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'subtotal' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'estimated_delivery' => 'datetime',
        'assigned_at' => 'datetime',
        'confirmed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function assignedDriver()
    {
        return $this->belongsTo(Driver::class, 'assigned_driver_id');
    }
    
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
