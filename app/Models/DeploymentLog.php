<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'environment',
        'status',
        'deployed_by',
        'started_at',
        'completed_at',
        'changes',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'changes' => 'array',
    ];

    public function deployedBy()
    {
        return $this->belongsTo(User::class, 'deployed_by');
    }

    public function getDurationAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->completed_at->diffInSeconds($this->started_at);
        }

        return null;
    }

    public function getDurationHumanAttribute()
    {
        $duration = $this->duration;
        if (! $duration) {
            return 'N/A';
        }

        $minutes = floor($duration / 60);
        $seconds = $duration % 60;

        if ($minutes > 0) {
            return "{$minutes}m {$seconds}s";
        }

        return "{$seconds}s";
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
