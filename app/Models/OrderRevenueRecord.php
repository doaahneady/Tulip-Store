<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRevenueRecord extends Model
{
    protected $fillable = [
        'order_id',
        'financial_transaction_id',
        'amount',
        'currency',
        'recognized_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'recognized_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function financialTransaction()
    {
        return $this->belongsTo(FinancialTransaction::class, 'financial_transaction_id');
    }
}

