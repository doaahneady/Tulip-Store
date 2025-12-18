<?php

namespace App\Services\Dashboard;

use App\Models\AuditLog;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public function __construct(
        protected AuditLogRepositoryInterface $auditLogRepository
    ) {}

    /**
     * Create an audit log entry for a sensitive action
     *
     * @param string $action The action type (create, update, delete, export, approve)
     * @param string $resourceType The type of resource being acted upon
     * @param int|null $resourceId The ID of the resource
     * @param array $metadata Additional metadata (old_values, new_values, etc.)
     * @return AuditLog
     */
    public function log(
        string $action,
        string $resourceType,
        ?int $resourceId = null,
        array $metadata = []
    ): AuditLog {
        $data = [
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $resourceType,
            'model_id' => $resourceId,
            'old_values' => $metadata['old_values'] ?? null,
            'new_values' => $metadata['new_values'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];

        return $this->auditLogRepository->create($data);
    }


    /**
     * Get audit logs with filtering and pagination
     *
     * @param array $filters Filters: user_id, action, resource_type, date_from, date_to
     * @param int $page Current page number
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     */
    public function getAuditLogs(
        array $filters = [],
        int $page = 1,
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->auditLogRepository->getFiltered($filters, $page, $perPage);
    }

    /**
     * Serialize an audit log entry to JSON string
     * Uses consistent field ordering for deterministic output
     *
     * @param AuditLog $entry The audit log entry to serialize
     * @return string JSON string representation
     */
    public function serializeEntry(AuditLog $entry): string
    {
        return $entry->serializeToJson();
    }

    /**
     * Deserialize a JSON string to audit log attributes
     *
     * @param string $json The JSON string to deserialize
     * @return array Audit log attributes array
     */
    public function deserializeEntry(string $json): array
    {
        return AuditLog::deserializeFromJson($json);
    }

    /**
     * Create an AuditLog instance from JSON (without persisting)
     *
     * @param string $json The JSON string to deserialize
     * @return AuditLog
     */
    public function createFromJson(string $json): AuditLog
    {
        return AuditLog::createFromSerializedJson($json);
    }

    /**
     * Log a create action
     */
    public function logCreate(string $resourceType, int $resourceId, array $newValues = []): AuditLog
    {
        return $this->log('create', $resourceType, $resourceId, [
            'new_values' => $newValues,
        ]);
    }

    /**
     * Log an update action
     */
    public function logUpdate(
        string $resourceType,
        int $resourceId,
        array $oldValues = [],
        array $newValues = []
    ): AuditLog {
        return $this->log('update', $resourceType, $resourceId, [
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    /**
     * Log a delete action
     */
    public function logDelete(string $resourceType, int $resourceId, array $oldValues = []): AuditLog
    {
        return $this->log('delete', $resourceType, $resourceId, [
            'old_values' => $oldValues,
        ]);
    }

    /**
     * Log an export action
     */
    public function logExport(string $resourceType, int $recordCount): AuditLog
    {
        return $this->log('export', $resourceType, null, [
            'new_values' => ['record_count' => $recordCount],
        ]);
    }

    /**
     * Log an approve action
     */
    public function logApprove(string $resourceType, int $resourceId): AuditLog
    {
        return $this->log('approve', $resourceType, $resourceId);
    }
}
