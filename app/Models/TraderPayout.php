<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraderPayout extends Model
{
    protected $fillable = [
        'trader_id',
        'amount',
        'currency',
        'status',
        'bank_details',
        'processed_by',
        'processed_at',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bank_details' => 'array',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
