<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'details',
        'category_id',
        'store_id',
        'trader_id',
        'sku',
        'price',
        'discount_price',
        'cost_price',
        'stock_quantity',
        'low_stock_threshold',
        'track_inventory',
        'image',
        'images',
        'rating',
        'reviews_count',
        'is_featured',
        'is_active',
        'is_trader_product',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'track_inventory' => 'boolean',
        'rating' => 'integer',
        'reviews_count' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_trader_product' => 'boolean',
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function scopeActive($query)
    {
        if (Schema::hasColumn('products', 'is_active')) {
            $query->where('is_active', true);

            if (Schema::hasColumn('products', 'status')) {
                $query->whereIn('status', ['approved', 'active']);
            }

            return $query;
        }

        if (Schema::hasColumn('products', 'status')) {
            return $query->whereIn('status', ['approved', 'active']);
        }

        return $query;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
