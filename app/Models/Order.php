<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Order extends Model
{
    /**
     * orders.assigned_driver_id is a foreign key to users.id (the driver's login account).
     * Supervisors/APIs often pass drivers.id — convert that to the driver's user id.
     */
    public static function resolveAssignedDriverUserId(?int $driverOrUserId): ?int
    {
        if ($driverOrUserId === null || $driverOrUserId === 0) {
            return null;
        }

        $driver = Driver::find($driverOrUserId);
        if ($driver && $driver->user_id) {
            return (int) $driver->user_id;
        }

        if (User::query()->whereKey($driverOrUserId)->exists()) {
            return (int) $driverOrUserId;
        }

        return null;
    }

    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (! $order->customer_id && $order->user_id) {
                $order->customer_id = $order->user_id;
            }
            if (! $order->user_id && $order->customer_id) {
                $order->user_id = $order->customer_id;
            }

            if ($order->total_amount === null && $order->total !== null) {
                $order->total_amount = $order->total;
            }
            if ($order->total === null && $order->total_amount !== null) {
                $order->total = $order->total_amount;
            }

            if ($order->subtotal === null) {
                $order->subtotal = $order->total_amount ?? $order->total ?? 0;
            }

            if ($order->shipping_address === null) {
                $order->shipping_address = [];
            }
        });

        static::saving(function (Order $order) {
            if ($order->total_amount === null && $order->total !== null) {
                $order->total_amount = $order->total;
            }
            if ($order->total === null && $order->total_amount !== null) {
                $order->total = $order->total_amount;
            }
            if (! $order->customer_id && $order->user_id) {
                $order->customer_id = $order->user_id;
            }
            if (! $order->user_id && $order->customer_id) {
                $order->user_id = $order->customer_id;
            }
            if (Schema::hasColumn('orders', 'shipping_cost') && Schema::hasColumn('orders', 'delivery_cost')) {
                $shipping = $order->shipping_cost;
                $delivery = $order->delivery_cost;
                if (($delivery === null || (float) $delivery == 0.0) && $shipping !== null && (float) $shipping > 0.0) {
                    $order->delivery_cost = $shipping;
                }
                if (($shipping === null || (float) $shipping == 0.0) && $delivery !== null && (float) $delivery > 0.0) {
                    $order->shipping_cost = $delivery;
                }
            }
        });
    }

    protected $fillable = [
        'user_id',
        'customer_id',
        'store_id',
        'order_number',
        'recipient_name',
        'phone',
        'village',
        'address_note',
        'latitude',
        'longitude',
        'delivery_method',
        'payment_method',
        'payment_reference',
        'status',
        'payment_status',
        'payment_receipt',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'delivery_cost',
        'service_fee',
        'total',
        'discount_amount',
        'total_amount',
        'shipping_address',
        'billing_address',
        'estimated_delivery',
        'assigned_driver_id',
        'assigned_at',
        'assigned_by',
        'confirmation_token',
        'confirmed_at',
        'customer_signature',
        'driver_delivery_signature',
        'signed_at',
        'delivery_notes',
        'tracking_number',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'estimated_delivery' => 'datetime',
        'assigned_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    protected $with = ['couponUsage.coupon'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * The user account of the assigned delivery driver (FK: assigned_driver_id → users.id).
     */
    public function assignedDriver()
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    /**
     * Driver profile row when assigned_driver_id holds the driver's user id.
     */
    public function assignedDriverRecord()
    {
        return $this->hasOne(Driver::class, 'user_id', 'assigned_driver_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function deliveryAssignment()
    {
        return $this->hasOne(DeliveryAssignment::class, 'order_id');
    }

    public function deliveryAssignments()
    {
        return $this->hasMany(DeliveryAssignment::class, 'order_id');
    }

    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class, 'order_id');
    }
}
