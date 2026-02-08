<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_name',
        'category',
        'period_type',
        'period',
        'budgeted_amount',
        'actual_amount',
        'variance',
        'variance_percentage',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'variance_percentage' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function calculateVariance()
    {
        $this->variance = $this->actual_amount - $this->budgeted_amount;

        if ($this->budgeted_amount > 0) {
            $this->variance_percentage = ($this->variance / $this->budgeted_amount) * 100;
        } else {
            $this->variance_percentage = 0;
        }

        $this->save();
    }
}
