<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Trader extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id', // Keeping for backward compatibility during transition
        'name',
        'company_name',
        'contact_email',
        'contact_phone',
        'account_name_en',
        'account_name_ar',
        'email',
        'phone',
        'responsible_name',
        'work_address',
        'activity',
        'password',
        'status',
        'commission_rate',
        'payout_settings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'payout_settings' => 'array',
        'password' => 'hashed',
    ];

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_SUSPENDED = 'suspended';

    public const STATUS_REJECTED = 'rejected';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsTraderAttribute(): bool
    {
        return true;
    }
}
