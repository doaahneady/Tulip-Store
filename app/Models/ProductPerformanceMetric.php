<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPerformanceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'metric_date',
        'views',
        'cart_additions',
        'purchases',
        'conversion_rate',
        'revenue',
        'average_rating',
        'review_count',
    ];

    protected $casts = [
        'metric_date' => 'date',
        'views' => 'integer',
        'cart_additions' => 'integer',
        'purchases' => 'integer',
        'conversion_rate' => 'decimal:2',
        'revenue' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateConversionRate()
    {
        if ($this->views > 0) {
            $this->conversion_rate = ($this->purchases / $this->views) * 100;
            $this->save();
        }
    }
}
