<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitLossStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_type',
        'period',
        'total_revenue',
        'cost_of_goods_sold',
        'gross_profit',
        'operating_expenses',
        'operating_profit',
        'other_income',
        'other_expenses',
        'net_profit',
        'tax_expense',
        'net_profit_after_tax',
        'breakdown',
    ];

    protected $casts = [
        'total_revenue' => 'decimal:2',
        'cost_of_goods_sold' => 'decimal:2',
        'gross_profit' => 'decimal:2',
        'operating_expenses' => 'decimal:2',
        'operating_profit' => 'decimal:2',
        'other_income' => 'decimal:2',
        'other_expenses' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'tax_expense' => 'decimal:2',
        'net_profit_after_tax' => 'decimal:2',
        'breakdown' => 'array',
    ];

    public function calculateProfitLoss()
    {
        $this->gross_profit = $this->total_revenue - $this->cost_of_goods_sold;
        $this->operating_profit = $this->gross_profit - $this->operating_expenses;
        $this->net_profit = $this->operating_profit + $this->other_income - $this->other_expenses;
        $this->net_profit_after_tax = $this->net_profit - $this->tax_expense;
        $this->save();
    }
}
