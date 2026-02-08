<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPBlacklist extends Model
{
    use HasFactory;

    protected $table = 'ip_blacklists';

    protected $fillable = [
        'ip_address',
        'reason',
        'blocked_by',
        'blocked_at',
        'expires_at',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];
}
