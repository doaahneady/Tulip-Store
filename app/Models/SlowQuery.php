<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlowQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'execution_time',
        'call_count',
        'severity',
        'database',
        'table_name',
        'is_optimized',
        'optimized_at',
        'optimization_notes',
        'last_seen_at',
    ];

    protected $casts = [
        'execution_time' => 'decimal:3',
        'is_optimized' => 'boolean',
        'optimized_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];
}
