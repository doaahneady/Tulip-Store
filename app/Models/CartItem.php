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
        'is_weight_based',
        'weight_grams',
        'price_per_unit',
        'amount_paid',
        'product_type',
        'mart_product_name',
        'mart_product_image',
        'mart_product_unit',
        'mart_product_emoji',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'product_snapshot' => 'array',
        'quantity' => 'integer',
        'is_weight_based' => 'boolean',
        'weight_grams' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'amount_paid' => 'decimal:2',
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
