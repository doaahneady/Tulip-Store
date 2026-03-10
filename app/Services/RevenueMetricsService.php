<?php

namespace App\Services;

use App\Models\FinancialTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RevenueMetricsService
{
    private function expandAliases(array $statuses): array
    {
        $aliases = (array) config('order_statuses.aliases', []);
        $expanded = $statuses;
        foreach ($aliases as $from => $to) {
            if (in_array($to, $statuses, true)) {
                $expanded[] = $from;
            }
        }

        return array_values(array_unique(array_map('strval', $expanded)));
    }

    public function sumProductRevenueForStatuses(array $statuses, $start = null, $end = null, ?int $storeId = null): float
    {
        if (! Schema::hasTable('order_items') || ! Schema::hasTable('orders')) {
            return 0.0;
        }

        $statuses = array_values(array_unique(array_filter(array_map('strval', $statuses))));
        if (! $statuses) {
            return 0.0;
        }

        $q = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', $statuses);

        if ($storeId !== null && Schema::hasTable('products') && Schema::hasColumn('products', 'store_id')) {
            $q->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('products.store_id', $storeId);
        }

        if ($start && $end) {
            $q->whereBetween('orders.updated_at', [$start, $end]);
        }

        $sumCol = Schema::hasColumn('order_items', 'total_price') ? 'order_items.total_price' : null;
        if (! $sumCol) {
            return 0.0;
        }

        return (float) $q->sum($sumCol);
    }

    public function revenueQuery(): Builder
    {
        $query = FinancialTransaction::query()
            ->where('status', 'completed')
            ->whereIn('type', ['order_payment', 'payment']);

        if (Schema::hasColumn('financial_transactions', 'order_id')) {
            $query->whereNotNull('order_id');
        }

        return $query;
    }

    public function sumRevenue($start = null, $end = null, ?int $storeId = null): float
    {
        $q = $this->revenueQuery();
        if ($storeId && Schema::hasColumn('financial_transactions', 'store_id')) {
            $q->where('store_id', $storeId);
        }
        if ($start && $end) {
            $q->whereBetween('created_at', [$start, $end]);
        }

        return (float) $q->sum('amount');
    }

    /**
     * Product-only revenue (excludes delivery/service fees).
     *
     * Sums order_items.total_price for terminal orders, filtered by product.store_id.
     */
    public function sumProductRevenue($start = null, $end = null, ?int $storeId = null): float
    {
        $terminal = (array) config('order_statuses.terminal', ['done']);
        $terminal = $this->expandAliases($terminal);

        return $this->sumProductRevenueForStatuses($terminal, $start, $end, $storeId);
    }

    public function revenueSeriesByDay($start, $end, ?int $storeId = null)
    {
        $q = $this->revenueQuery();
        if ($storeId && Schema::hasColumn('financial_transactions', 'store_id')) {
            $q->where('store_id', $storeId);
        }
        $q->whereBetween('created_at', [$start, $end]);

        return $q->selectRaw('DATE(created_at) as d, SUM(amount) as total')
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->mapWithKeys(fn ($r) => [(string) $r->d => (float) $r->total]);
    }

    public function revenueByStore($start, $end)
    {
        if (! Schema::hasTable('stores') || ! Schema::hasColumn('financial_transactions', 'store_id')) {
            return collect();
        }

        $rows = $this->revenueQuery()
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('store_id')
            ->selectRaw('store_id, SUM(amount) as revenue_total')
            ->groupBy('store_id')
            ->orderByDesc('revenue_total')
            ->get()
            ->keyBy('store_id');

        $stores = DB::table('stores')
            ->select(['id', 'name'])
            ->whereIn('id', $rows->keys())
            ->get()
            ->keyBy('id');

        return $rows->map(function ($r) use ($stores) {
            $s = $stores->get($r->store_id);
            return (object) [
                'store_id' => (int) $r->store_id,
                'store_name' => $s?->name,
                'revenue_total' => (float) $r->revenue_total,
            ];
        })->values();
    }
}
