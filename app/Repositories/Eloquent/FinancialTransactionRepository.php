<?php

namespace App\Repositories\Eloquent;

use App\Models\FinancialTransaction;
use App\Repositories\Contracts\FinancialTransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FinancialTransactionRepository implements FinancialTransactionRepositoryInterface
{
    public function __construct(
        protected FinancialTransaction $model
    ) {}

    public function findById(int $id): ?FinancialTransaction
    {
        return $this->model->with(['store', 'order', 'user'])->find($id);
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['store', 'order', 'user']);

        return $this->applyFilters($query, $filters)
            ->paginate($filters['per_page'] ?? 25);
    }

    public function getForStore(int $storeId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->where('store_id', $storeId)
            ->with(['order', 'user']);

        return $this->applyFilters($query, $filters)
            ->paginate($filters['per_page'] ?? 25);
    }

    public function getByType(string $type, array $filters = []): LengthAwarePaginator
    {
        $filters['type'] = $type;

        return $this->getAll($filters);
    }

    public function getByStatus(string $status, array $filters = []): LengthAwarePaginator
    {
        $filters['status'] = $status;

        return $this->getAll($filters);
    }

    public function getTotalByType(string $type, Carbon $start, Carbon $end, ?int $storeId = null): float
    {
        $query = $this->model->newQuery()
            ->where('type', $type)
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed');

        if ($storeId !== null) {
            $query->where('store_id', $storeId);
        }

        return (float) $query->sum('amount');
    }

    public function getPendingPayouts(): Collection
    {
        return $this->model->newQuery()
            ->where('type', 'payout')
            ->where('status', 'pending')
            ->with(['store', 'user'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getByDateRange(Carbon $start, Carbon $end, array $filters = []): LengthAwarePaginator
    {
        $filters['date_from'] = $start;
        $filters['date_to'] = $end;

        return $this->getAll($filters);
    }

    public function create(array $data): FinancialTransaction
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ?FinancialTransaction
    {
        $transaction = $this->findById($id);

        if (! $transaction) {
            return null;
        }

        // Check if transaction is immutable (approved)
        if ($transaction->is_immutable ?? false) {
            throw new \App\Exceptions\Dashboard\ImmutableRecordException(
                'Approved financial records cannot be modified'
            );
        }

        $transaction->update($data);

        return $transaction->fresh();
    }

    protected function applyFilters($query, array $filters)
    {
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['store_id'])) {
            $query->where('store_id', $filters['store_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'desc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }
}
