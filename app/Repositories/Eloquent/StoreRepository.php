<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\Store;
use App\Repositories\Contracts\StoreRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class StoreRepository implements StoreRepositoryInterface
{
    public function __construct(
        protected Store $model
    ) {}

    public function findById(int $id): ?Store
    {
        return $this->model->with('owner')->find($id);
    }

    public function findByOwner(int $userId): ?Store
    {
        $ownerColumn = Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id';

        return $this->model->where($ownerColumn, $userId)->first();
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('owner');

        return $this->applyFilters($query, $filters)
            ->paginate($filters['per_page'] ?? 25);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function getTotalCount(): int
    {
        return $this->model->count();
    }

    public function getActiveCount(): int
    {
        return $this->model->where('status', 'approved')->count();
    }

    public function calculateRevenue(int $storeId, Carbon $start, Carbon $end): float
    {
        $terminal = (array) config('order_statuses.terminal', ['delivered', 'done']);
        return (float) Order::query()
            ->whereHas('items.product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', $terminal)
            ->when(true, function ($q) {
                $sumColumn = Schema::hasColumn('orders', 'total_amount')
                    ? 'total_amount'
                    : (Schema::hasColumn('orders', 'total') ? 'total' : null);
                if ($sumColumn) {
                    return $q->sum($sumColumn);
                }
                $parts = array_filter([
                    Schema::hasColumn('orders', 'subtotal') ? 'subtotal' : null,
                    Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null,
                    Schema::hasColumn('orders', 'service_fee') ? 'service_fee' : null,
                ]);
                if (! $parts) {
                    return 0;
                }

                return (float) ($q->selectRaw('SUM('.implode(' + ', $parts).') as agg')->value('agg') ?? 0);
            });
    }

    public function calculateEarnings(int $storeId, Carbon $start, Carbon $end): float
    {
        $store = $this->findById($storeId);

        if (! $store) {
            return 0.0;
        }

        $revenue = $this->calculateRevenue($storeId, $start, $end);
        $commissionRate = $store->commission_rate ?? 0;

        return $revenue * (1 - $commissionRate / 100);
    }

    public function getTopByRevenue(int $limit = 10, ?Carbon $start = null, ?Carbon $end = null): Collection
    {
        $start = $start ?? Carbon::now()->startOfMonth();
        $end = $end ?? Carbon::now()->endOfMonth();
        $ownerColumn = Schema::hasColumn('stores', 'owner_id') ? 'owner_id' : 'user_id';
        $terminal = (array) config('order_statuses.terminal', ['delivered', 'done']);

        return $this->model->newQuery()
            ->select([
                'stores.id',
                'stores.'.$ownerColumn,
                'stores.name',
                'stores.slug',
                'stores.description',
                'stores.logo',
                'stores.banner',
                'stores.phone',
                'stores.email',
                'stores.address',
                'stores.status',
                'stores.commission_rate',
                'stores.total_sales',
                'stores.total_commission',
                'stores.balance',
                'stores.is_featured',
                'stores.created_at',
                'stores.updated_at',
                'stores.deleted_at',
            ])
            ->selectRaw((function () {
                $expr = Schema::hasColumn('orders', 'total_amount')
                    ? 'orders.total_amount'
                    : (Schema::hasColumn('orders', 'total') ? 'orders.total' : (Schema::hasColumn('order_items', 'subtotal') ? 'order_items.subtotal' : null));

                return $expr ? 'COALESCE(SUM('.$expr.'), 0) as total_revenue' : '0 as total_revenue';
            })())
            ->leftJoin('products', 'products.store_id', '=', 'stores.id')
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('orders', function ($join) use ($start, $end, $terminal) {
                $join->on('orders.id', '=', 'order_items.order_id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->whereIn('orders.status', $terminal);
            })
            ->groupBy([
                'stores.id',
                'stores.'.$ownerColumn,
                'stores.name',
                'stores.slug',
                'stores.description',
                'stores.logo',
                'stores.banner',
                'stores.phone',
                'stores.email',
                'stores.address',
                'stores.status',
                'stores.commission_rate',
                'stores.total_sales',
                'stores.total_commission',
                'stores.balance',
                'stores.is_featured',
                'stores.created_at',
                'stores.updated_at',
                'stores.deleted_at',
            ])
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }

    protected function applyFilters($query, array $filters)
    {
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['is_featured'])) {
            $query->where('is_featured', true);
        }

        if (! empty($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }
}
