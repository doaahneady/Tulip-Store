<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSavedCard extends Model
{
    protected $fillable = [
        'user_id',
        'brand',
        'last4',
        'expiry',
        'holder_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
