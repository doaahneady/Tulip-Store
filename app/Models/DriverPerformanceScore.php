<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverPerformanceScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'period',
        'total_deliveries',
        'on_time_deliveries',
        'on_time_rate',
        'average_delivery_time_minutes',
        'customer_rating',
        'accidents',
        'violations',
        'overall_score',
        'performance_grade',
    ];

    protected $casts = [
        'total_deliveries' => 'integer',
        'on_time_deliveries' => 'integer',
        'on_time_rate' => 'decimal:2',
        'average_delivery_time_minutes' => 'decimal:2',
        'customer_rating' => 'decimal:2',
        'accidents' => 'integer',
        'violations' => 'integer',
        'overall_score' => 'decimal:2',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function calculateGrade()
    {
        if ($this->overall_score >= 90) {
            return 'A';
        }
        if ($this->overall_score >= 80) {
            return 'B';
        }
        if ($this->overall_score >= 70) {
            return 'C';
        }
        if ($this->overall_score >= 60) {
            return 'D';
        }

        return 'F';
    }
}
