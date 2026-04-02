<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id', 'owner_id', 'user_id', 'name', 'slug', 'description', 'logo', 'banner',
        'phone', 'email', 'address', 'status', 'commission_rate',
        'total_sales', 'total_commission', 'balance', 'is_featured',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'balance' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function owner()
    {
        if (Schema::hasColumn('stores', 'owner_id')) {
            return $this->belongsTo(Trader::class, 'owner_id');
        }

        return $this->belongsTo(User::class, 'user_id');
    }

    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ownerTrader()
    {
        return $this->belongsTo(Trader::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
