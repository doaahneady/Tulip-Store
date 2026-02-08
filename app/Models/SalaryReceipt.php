<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_record_id',
        'financial_transaction_id',
        'employee_id',
        'pay_period',
        'amount',
        'currency',
        'paid_date',
        'signed_name',
        'signature_data',
        'signed_at',
        'created_by_employee_id',
    ];

    protected $casts = [
        'paid_date' => 'date',
        'signed_at' => 'datetime',
    ];
}
