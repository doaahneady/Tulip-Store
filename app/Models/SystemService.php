<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'status',
        'uptime',
        'cpu_usage',
        'memory_usage',
        'port',
        'last_checked_at',
        'error_message',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];
}
