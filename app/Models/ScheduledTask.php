<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'command',
        'schedule',
        'schedule_time',
        'status',
        'last_run_at',
        'next_run_at',
        'run_count',
        'failure_count',
        'last_output',
        'is_enabled',
    ];

    protected $casts = [
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'is_enabled' => 'boolean',
    ];
}
