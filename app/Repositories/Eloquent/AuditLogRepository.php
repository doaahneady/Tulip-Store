<?php

namespace App\Repositories\Eloquent;

use App\Models\AuditLog;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    public function __construct(
        protected AuditLog $model
    ) {}

    /**
     * Create a new audit log entry
     * This is the only write operation allowed - audit logs are immutable
     */
    public function create(array $data): AuditLog
    {
        return $this->model->create($data);
    }

    public function getFiltered(array $filters, int $page = 1, int $perPage = 25): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('user');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['model_type']) || !empty($filters['resource_type'])) {
            $resourceType = $filters['model_type'] ?? $filters['resource_type'];
            $query->where('model_type', $resourceType);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function getByUser(int $userId, int $page = 1, int $perPage = 25): LengthAwarePaginator
    {
        return $this->getFiltered(['user_id' => $userId], $page, $perPage);
    }

    public function getByAction(string $action, int $page = 1, int $perPage = 25): LengthAwarePaginator
    {
        return $this->getFiltered(['action' => $action], $page, $perPage);
    }

    public function getByResourceType(string $resourceType, int $page = 1, int $perPage = 25): LengthAwarePaginator
    {
        return $this->getFiltered(['resource_type' => $resourceType], $page, $perPage);
    }

    public function getByDateRange(Carbon $start, Carbon $end, int $page = 1, int $perPage = 25): LengthAwarePaginator
    {
        return $this->getFiltered([
            'date_from' => $start,
            'date_to' => $end,
        ], $page, $perPage);
    }

    // Note: No update or delete methods - audit logs are immutable per Requirements 6.2
}
