<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnhancedFinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'user_id',
        'store_id',
        'type',
        'amount',
        'currency',
        'status',
        'payment_method',
        'gateway',
        'gateway_transaction_id',
        'description',
        'metadata',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function processor()
    {
        return $this->belongsTo(Employee::class, 'processed_by');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRevenue($query)
    {
        return $query->whereIn('type', ['payment']);
    }

    public function scopeRefunds($query)
    {
        return $query->where('type', 'refund');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'completed' => 'text-green-600 bg-green-100',
            'pending' => 'text-yellow-600 bg-yellow-100',
            'processing' => 'text-blue-600 bg-blue-100',
            'failed' => 'text-red-600 bg-red-100',
            'cancelled' => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getTypeColorAttribute()
    {
        return match ($this->type) {
            'payment' => 'text-green-600 bg-green-100',
            'refund' => 'text-red-600 bg-red-100',
            'commission' => 'text-blue-600 bg-blue-100',
            'payout' => 'text-purple-600 bg-purple-100',
            'fee' => 'text-orange-600 bg-orange-100',
            'adjustment' => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2).' '.$this->currency;
    }

    public static function generateTransactionId($type = null)
    {
        $prefix = match ($type) {
            'payment' => 'PAY',
            'refund' => 'REF',
            'commission' => 'COM',
            'payout' => 'OUT',
            'fee' => 'FEE',
            'adjustment' => 'ADJ',
            default => 'TXN',
        };

        return $prefix.'_'.time().'_'.rand(1000, 9999);
    }

    public function process($processorId = null)
    {
        return $this->update([
            'status' => 'completed',
            'processed_by' => $processorId,
            'processed_at' => now(),
        ]);
    }

    public function fail($reason = null)
    {
        $metadata = $this->metadata ?? [];
        if ($reason) {
            $metadata['failure_reason'] = $reason;
        }

        return $this->update([
            'status' => 'failed',
            'metadata' => $metadata,
        ]);
    }
}
