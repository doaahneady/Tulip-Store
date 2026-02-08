<?php

namespace App\Models;

use App\Exceptions\Dashboard\ImmutableRecordException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $attributes = [
        'is_immutable' => false,
    ];

    protected $fillable = [
        'transaction_id',
        'user_id',
        'order_id',
        'store_id',
        'type',
        'status',
        'amount',
        'currency',
        'description',
        'metadata',
        'hash',
        'is_locked',
        'locked_at',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'is_immutable',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_immutable' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::updating(function (FinancialTransaction $tx) {
            if ((bool) ($tx->getOriginal('is_immutable') ?? false)) {
                throw new ImmutableRecordException('Approved financial records cannot be modified');
            }
        });
    }

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

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approve(User $approver): bool
    {
        if ((bool) ($this->is_immutable ?? false)) {
            throw new ImmutableRecordException('Approved financial records cannot be modified');
        }

        $this->approved_by = $approver->id;
        $this->approved_at = now();
        $this->status = 'approved';
        $this->is_immutable = true;

        return $this->save();
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
        return $query->whereIn('type', ['order_payment', 'commission']);
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
            'order_payment' => 'text-green-600 bg-green-100',
            'refund' => 'text-red-600 bg-red-100',
            'commission' => 'text-blue-600 bg-blue-100',
            'payout' => 'text-purple-600 bg-purple-100',
            'fee' => 'text-orange-600 bg-orange-100',
            'adjustment' => 'text-gray-600 bg-gray-100',
            'payroll' => 'text-indigo-600 bg-indigo-100',
            'expense' => 'text-yellow-600 bg-yellow-100',
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
}
