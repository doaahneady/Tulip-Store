<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'product_id',
        'forecast_period',
        'forecasted_quantity',
        'forecasted_revenue',
        'confidence_score',
        'factors',
    ];

    protected $casts = [
        'forecasted_quantity' => 'integer',
        'forecasted_revenue' => 'decimal:2',
        'confidence_score' => 'decimal:2',
        'factors' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
