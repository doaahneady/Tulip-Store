<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlowRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'flow_type',
        'category',
        'amount',
        'description',
        'reference_type',
        'reference_id',
        'balance_after',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function reference()
    {
        return $this->morphTo();
    }

    public function scopeInflow($query)
    {
        return $query->where('flow_type', 'inflow');
    }

    public function scopeOutflow($query)
    {
        return $query->where('flow_type', 'outflow');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
