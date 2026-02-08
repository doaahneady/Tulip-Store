<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiError extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'method',
        'status_code',
        'error_message',
        'request_data',
        'response_data',
        'user_id',
        'ip_address',
        'user_agent',
        'response_time',
        'occurred_at',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'response_time' => 'decimal:3',
        'occurred_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('occurred_at', today());
    }

    public function scopeByStatusCode($query, $statusCode)
    {
        return $query->where('status_code', $statusCode);
    }

    public function scopeByEndpoint($query, $endpoint)
    {
        return $query->where('endpoint', 'like', "%{$endpoint}%");
    }

    public function getResponseTimeHumanAttribute()
    {
        return number_format($this->response_time, 2).'ms';
    }
}
