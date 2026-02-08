<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_type',
        'server_name',
        'usage_percentage',
        'used_bytes',
        'total_bytes',
        'details',
        'recorded_at',
    ];

    protected $casts = [
        'usage_percentage' => 'decimal:2',
        'used_bytes' => 'integer',
        'total_bytes' => 'integer',
        'details' => 'array',
        'recorded_at' => 'datetime',
    ];

    public function scopeByType($query, $type)
    {
        return $query->where('resource_type', $type);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('recorded_at', '>=', now()->subHours($hours));
    }
}
