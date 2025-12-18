<?php

namespace App\Models;

use App\Exceptions\Dashboard\ImmutableRecordException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',      // resource_type in design
        'model_id',        // resource_id in design
        'old_values',      // metadata - old values
        'new_values',      // metadata - new values
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method to enforce immutability - audit logs cannot be modified or deleted
     */
    protected static function boot()
    {
        parent::boot();

        // Prevent updates to audit logs
        static::updating(function ($model) {
            throw new ImmutableRecordException('Audit logs cannot be modified');
        });

        // Prevent deletion of audit logs
        static::deleting(function ($model) {
            throw new ImmutableRecordException('Audit logs cannot be deleted');
        });
    }

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model (polymorphic relationship)
     */
    public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Alias for model_type to match design document terminology
     */
    public function getResourceTypeAttribute(): ?string
    {
        return $this->model_type;
    }

    /**
     * Alias for model_id to match design document terminology
     */
    public function getResourceIdAttribute(): ?int
    {
        return $this->model_id;
    }

    /**
     * Get combined metadata from old_values and new_values
     */
    public function getMetadataAttribute(): array
    {
        return [
            'old_values' => $this->old_values,
            'new_values' => $this->new_values,
        ];
    }

    /**
     * Serialize the audit log entry to JSON string
     * Uses consistent field ordering for deterministic output
     */
    public function serializeToJson(): string
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'action' => $this->action,
            'resource_type' => $this->model_type,
            'resource_id' => $this->model_id,
            'metadata' => [
                'old_values' => $this->old_values,
                'new_values' => $this->new_values,
            ],
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        // Sort keys for consistent ordering
        ksort($data);
        if (isset($data['metadata'])) {
            ksort($data['metadata']);
        }

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * Deserialize JSON string to AuditLog attributes array
     * Returns an array that can be used to create or compare audit log entries
     */
    public static function deserializeFromJson(string $json): array
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return [
            'id' => $data['id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'action' => $data['action'] ?? null,
            'model_type' => $data['resource_type'] ?? null,
            'model_id' => $data['resource_id'] ?? null,
            'old_values' => $data['metadata']['old_values'] ?? null,
            'new_values' => $data['metadata']['new_values'] ?? null,
            'ip_address' => $data['ip_address'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'created_at' => $data['created_at'] ?? null,
            'updated_at' => $data['updated_at'] ?? null,
        ];
    }

    /**
     * Create an AuditLog instance from serialized JSON data
     */
    public static function createFromSerializedJson(string $json): self
    {
        $attributes = self::deserializeFromJson($json);
        
        $auditLog = new self();
        $auditLog->forceFill($attributes);
        $auditLog->exists = isset($attributes['id']);
        
        return $auditLog;
    }

    /**
     * Static helper method to create audit log entries
     */
    public static function log($action, $model = null, $oldValues = null, $newValues = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
