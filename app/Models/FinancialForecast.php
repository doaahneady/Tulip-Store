<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'forecast_type',
        'period_type',
        'period',
        'forecasted_amount',
        'confidence_level',
        'assumptions',
        'method',
        'created_by',
    ];

    protected $casts = [
        'forecasted_amount' => 'decimal:2',
        'confidence_level' => 'decimal:2',
        'assumptions' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}
