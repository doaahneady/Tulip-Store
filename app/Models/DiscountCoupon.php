<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'discount_percentage',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_count',
        'user_id',
        'purpose',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'expires_at',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        // Check valid_from and valid_until if they exist
        $now = now();
        if ($this->valid_from && $now->isBefore($this->valid_from)) {
            return false;
        }
        if ($this->valid_until && $now->isAfter($this->valid_until)) {
            return false;
        }

        // Fallback to expires_at for backward compatibility
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Check usage_limit (new field) or max_uses (old field)
        $limit = $this->usage_limit ?? $this->max_uses;
        $count = $this->usage_count ?? $this->used_count ?? 0;
        if ($limit && $count >= $limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedBy($userId)
    {
        if (!$this->isValid()) {
            return false;
        }

        // If coupon is restricted to a specific user, check if it matches
        if ($this->user_id && $this->user_id != $userId) {
            return false;
        }

        // Check if user already used this coupon
        $alreadyUsed = $this->usages()
            ->where('user_id', $userId)
            ->exists();

        return !$alreadyUsed;
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
