<?php

namespace App\Models;

use App\Exceptions\Dashboard\ImmutableRecordException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

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
        return $this->morphTo('user', 'user_type', 'user_id');
    }

    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    public function scopeByUser($query, $userId, $userType = null)
    {
        $query->where('user_id', $userId);

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

    public function getActionColorAttribute()
    {
        return match ($this->action) {
            'create', 'created' => 'text-green-600 bg-green-100',
            'update', 'updated' => 'text-blue-600 bg-blue-100',
            'delete', 'deleted' => 'text-red-600 bg-red-100',
            'login' => 'text-purple-600 bg-purple-100',
            'logout' => 'text-gray-600 bg-gray-100',
            default => 'text-indigo-600 bg-indigo-100',
        };
    }

    public function getActionIconAttribute()
    {
        return match ($this->action) {
            'create', 'created' => 'fa-plus',
            'update', 'updated' => 'fa-edit',
            'delete', 'deleted' => 'fa-trash',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'view' => 'fa-eye',
            'download' => 'fa-download',
            default => 'fa-cog',
        };
    }

    public static function log($action, $model = null, $oldValues = null, $newValues = null, $metadata = null)
    {
        $webUser = Auth::guard('web')->user();
        $employeeUser = Auth::guard('employee')->user();
        $actor = $webUser ?? $employeeUser;

        $userId = $actor?->id;

        $meta = is_array($metadata) ? $metadata : (is_null($metadata) ? [] : ['value' => $metadata]);
        if (! $webUser && $employeeUser) {
            $meta = array_merge([
                'actor' => array_filter([
                    'guard' => 'employee',
                    'employee_id' => $employeeUser->id ?? null,
                    'employee_email' => $employeeUser->email ?? null,
                    'employee_code' => $employeeUser->employee_code ?? null,
                ], fn ($v) => $v !== null && $v !== ''),
            ], $meta);
        }

        $data = [
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => empty($meta) ? null : $meta,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('audit_logs', 'user_type')) {
            $data['user_type'] = $actor ? get_class($actor) : null;
        }

        return static::create($data);
    }

    protected static function sortArrayRecursive($value)
    {
        if (! is_array($value)) {
            return $value;
        }
        ksort($value);
        foreach ($value as $k => $v) {
            $value[$k] = static::sortArrayRecursive($v);
        }

        return $value;
    }

    public function serializeToJson(): string
    {
        $payload = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'action' => $this->action,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'old_values' => static::sortArrayRecursive($this->old_values),
            'new_values' => static::sortArrayRecursive($this->new_values),
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
        ];

        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function deserializeFromJson(string $json): array
    {
        return json_decode($json, true) ?? [];
    }

    public static function createFromSerializedJson(string $json): self
    {
        $data = static::deserializeFromJson($json);

        return static::findOrFail($data['id']);
    }

    public function update(array $attributes = [], array $options = [])
    {
        if ($this->exists) {
            throw new ImmutableRecordException('Audit logs cannot be modified');
        }

        return parent::update($attributes, $options);
    }

    public function delete()
    {
        throw new ImmutableRecordException('Audit logs cannot be deleted');
    }
}
