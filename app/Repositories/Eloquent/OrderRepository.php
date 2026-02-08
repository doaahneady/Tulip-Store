<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        protected Order $model
    ) {}

    public function findById(int $id): ?Order
    {
        return $this->model->with(['user', 'items'])->find($id);
    }

    public function getForStore(int $storeId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->whereHas('items.product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->with(['user', 'items']);

        return $this->applyFilters($query, $filters)
            ->paginate($filters['per_page'] ?? 25);
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['user', 'items']);

        return $this->applyFilters($query, $filters)
            ->paginate($filters['per_page'] ?? 25);
    }

    public function getTotalRevenue(Carbon $start, Carbon $end, ?int $storeId = null): float
    {
        $query = $this->model->newQuery()
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed');

        if ($storeId !== null) {
            $query->whereHas('items.product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            });
        }

        $sumColumn = Schema::hasColumn('orders', 'total_amount')
            ? 'total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'total' : null);
        if ($sumColumn) {
            return (float) $query->sum($sumColumn);
        }
        $parts = array_filter([
            Schema::hasColumn('orders', 'subtotal') ? 'subtotal' : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null,
            Schema::hasColumn('orders', 'service_fee') ? 'service_fee' : null,
        ]);
        if (! $parts) {
            return 0.0;
        }

        return (float) ($query->selectRaw('SUM('.implode(' + ', $parts).') as agg')->value('agg') ?? 0);
    }

    public function getOrderCount(Carbon $start, Carbon $end, ?int $storeId = null): int
    {
        $query = $this->model->newQuery()
            ->whereBetween('created_at', [$start, $end]);

        if ($storeId !== null) {
            $query->whereHas('items.product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            });
        }

        return $query->count();
    }

    public function getByStatus(string $status, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->where('status', $status)
            ->with(['user', 'items']);

        return $this->applyFilters($query, $filters)
            ->paginate($filters['per_page'] ?? 25);
    }

    public function getRecent(int $limit = 10, ?int $storeId = null): Collection
    {
        $query = $this->model->newQuery()
            ->with(['user', 'items'])
            ->orderBy('created_at', 'desc');

        if ($storeId !== null) {
            $query->whereHas('items.product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            });
        }

        return $query->limit($limit)->get();
    }

    protected function applyFilters($query, array $filters)
    {
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
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
