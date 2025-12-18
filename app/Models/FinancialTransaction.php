<?php

namespace App\Models;

use App\Exceptions\Dashboard\ImmutableRecordException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'order_id', 'user_id', 'type', 'amount',
        'balance_before', 'balance_after', 'reference', 'description', 'status',
        'approved_by', 'approved_at', 'is_immutable'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'approved_at' => 'datetime',
        'is_immutable' => 'boolean',
    ];

    /**
     * Boot method to prevent updates on immutable records.
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->getOriginal('is_immutable')) {
                throw new ImmutableRecordException('Approved financial records cannot be modified');
            }
        });
    }

    /**
     * Approve the financial transaction and mark it as immutable.
     *
     * @param User $approver The user approving the transaction
     * @return void
     */
    public function approve(User $approver): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'is_immutable' => true,
        ]);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who approved this transaction.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeImmutable($query)
    {
        return $query->where('is_immutable', true);
    }
}
