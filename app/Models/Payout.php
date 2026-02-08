<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_id',
        'store_id',
        'requested_by',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_reference',
        'reference_number',
        'bank_details',
        'notes',
        'requested_at',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'requested_at' => 'datetime',
        'bank_details' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
