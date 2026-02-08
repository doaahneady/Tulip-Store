<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnhancedAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo('model');
    }

    public function scopeByUser($query, $userId, $userType = null)
    {
        $query->where('user_id', $userId);
        if ($userType && \Illuminate\Support\Facades\Schema::hasColumn('enhanced_audit_logs', 'user_type')) {
            $query->where('user_type', $userType);
        }

        return $query;
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getUserNameAttribute()
    {
        if (! $this->user) {
            return 'System';
        }

        if ($this->user_type === 'App\\Models\\Employee') {
            return $this->user->first_name.' '.$this->user->last_name;
        }

        return $this->user->name ?? 'Unknown User';
    }

    public function getActionColorAttribute()
    {
        return match ($this->action) {
            'create' => 'text-green-600 bg-green-100',
            'update' => 'text-blue-600 bg-blue-100',
            'delete' => 'text-red-600 bg-red-100',
            'login' => 'text-purple-600 bg-purple-100',
            'logout' => 'text-gray-600 bg-gray-100',
            'view' => 'text-indigo-600 bg-indigo-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getActionIconAttribute()
    {
        return match ($this->action) {
            'create' => 'fa-plus',
            'update' => 'fa-edit',
            'delete' => 'fa-trash',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'view' => 'fa-eye',
            'password_change' => 'fa-key',
            'profile_update' => 'fa-user-edit',
            default => 'fa-history',
        };
    }

    public function getChangesAttribute()
    {
        if (! $this->old_values || ! $this->new_values) {
            return [];
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    public static function logActivity($action, $model = null, $oldValues = null, $newValues = null, $metadata = [])
    {
        $user = auth()->user();

        $data = [
            'user_id' => $user?->id,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('enhanced_audit_logs', 'user_type')) {
            $data['user_type'] = $user ? get_class($user) : null;
        }

        return static::create($data);
    }
}
