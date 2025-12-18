<?php

namespace App\Repositories\Contracts;

use App\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AuditLogRepositoryInterface
{
    /**
     * Create a new audit log entry
     * Note: This is the only write operation allowed - audit logs are immutable
     */
    public function create(array $data): AuditLog;

    /**
     * Get filtered audit logs with pagination
     */
    public function getFiltered(array $filters, int $page = 1, int $perPage = 25): LengthAwarePaginator;

    /**
     * Get audit logs for a specific user
     */
    public function getByUser(int $userId, int $page = 1, int $perPage = 25): LengthAwarePaginator;

    /**
     * Get audit logs for a specific action type
     */
    public function getByAction(string $action, int $page = 1, int $perPage = 25): LengthAwarePaginator;

    /**
     * Get audit logs for a specific resource type
     */
    public function getByResourceType(string $resourceType, int $page = 1, int $perPage = 25): LengthAwarePaginator;

    /**
     * Get audit logs within a date range
     */
    public function getByDateRange(\Carbon\Carbon $start, \Carbon\Carbon $end, int $page = 1, int $perPage = 25): LengthAwarePaginator;

    // Note: No update or delete methods - audit logs are immutable per Requirements 6.2
}
