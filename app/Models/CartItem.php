<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'product_snapshot',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'product_snapshot' => 'array',
        'quantity' => 'integer',
    ];

    protected $appends = [
        'price',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPriceAttribute()
    {
        return $this->unit_price;
    }

    public function setPriceAttribute($value): void
    {
        $this->setUnitPriceAttribute($value);
    }

    public function setUnitPriceAttribute($value): void
    {
        $this->attributes['unit_price'] = $value;
        $qty = (int) ($this->attributes['quantity'] ?? 0);
        $this->attributes['total_price'] = $value === null ? null : ((float) $value * $qty);
    }

    public function setQuantityAttribute($value): void
    {
        $qty = (int) $value;
        $this->attributes['quantity'] = $qty;
        $unit = $this->attributes['unit_price'] ?? null;
        $this->attributes['total_price'] = $unit === null ? null : ((float) $unit * $qty);
    }
}
