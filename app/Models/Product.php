<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function (self $product) {
            $isTraderProduct = (bool) ($product->trader_id !== null || $product->is_trader_product);
            if (! $isTraderProduct) {
                return;
            }

            if (Schema::hasColumn('products', 'is_trader_product')) {
                $product->is_trader_product = true;
            }
            if (Schema::hasColumn('products', 'status')) {
                $product->status = 'pending';
            }
            if (Schema::hasColumn('products', 'is_active')) {
                $product->is_active = false;
            }
            if (Schema::hasColumn('products', 'reviewed_by')) {
                $product->reviewed_by = null;
            }
            if (Schema::hasColumn('products', 'reviewed_at')) {
                $product->reviewed_at = null;
            }
            if (Schema::hasColumn('products', 'rejection_reason')) {
                $product->rejection_reason = null;
            }
        });
    }

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
        'market',
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

    protected $appends = [
        'primary_image_url',
        'primary_image_srcset',
    ];

    public function getPrimaryImageUrlAttribute(): string
    {
        $raw = null;
        if (Schema::hasColumn('products', 'image')) {
            $raw = $this->getAttribute('image');
        }
        if (! $raw) {
            $raw = $this->getAttribute('photo') ?: $this->getAttribute('image_path');
        }
        if (! $raw && Schema::hasColumn('products', 'images')) {
            $imgs = $this->getAttribute('images');
            if (is_array($imgs) && count($imgs) > 0) {
                $first = $imgs[0];
                if (is_array($first)) {
                    $raw = $first['path'] ?? $first['url'] ?? null;
                } else {
                    $raw = $first;
                }
            }
        }

        $path = str_replace('\\', '/', trim((string) ($raw ?? '')));
        if ($path === '') {
            $market = $this->getAttribute('market') ?: ($this->category ? $this->category->market : 'store');
            if ($market === 'mart') {
                return '/images/tulip_mart.jpg';
            } elseif ($market === 'gift') {
                return '/images/tulip_gift.jpg';
            }
            return '/images/tulip_store.jpg';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, '/storage/')) {
            return $path;
        }
        if (str_starts_with($path, '/images/')) {
            return $path;
        }
        if (str_starts_with($path, '/')) {
            return $path;
        }
        if (str_starts_with($path, 'images/')) {
            return '/'.$path;
        }
        if (file_exists(public_path($path))) {
            return '/'.$path;
        }

        $clean = preg_replace('#^storage/#', '', $path) ?? $path;
        if ($clean !== '') {
            $clean = ltrim($clean, '/');
            return '/storage/'.$clean;
        }

        return '/images/banner1.jpg';
    }

    public function getPrimaryImageSrcsetAttribute(): string
    {
        $url = $this->primary_image_url;
        return "{$url} 1x, {$url} 2x";
    }

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
        }

        if (Schema::hasColumn('products', 'status')) {
            if (Schema::hasColumn('products', 'is_trader_product')) {
                $query->where(function ($q) {
                    $q->where(function ($q2) {
                        $q2->where('is_trader_product', true)
                            ->whereIn('status', ['approved', 'active']);
                    })->orWhere(function ($q2) {
                        $q2->whereNull('is_trader_product')
                            ->orWhere('is_trader_product', false);
                    });
                });
            } else {
                $query->whereIn('status', ['approved', 'active']);
            }
        }

        return $query;
    }

    public function scopeAvailable($query)
    {
        if (Schema::hasColumn('products', 'track_inventory') && Schema::hasColumn('products', 'stock_quantity')) {
            return $query->where(function ($q) {
                $q->where('track_inventory', false)
                    ->orWhere('stock_quantity', '>', 0);
            });
        }

        return $query;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
