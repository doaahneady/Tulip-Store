<?php

namespace App\Services\Dashboard;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\StoreRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Store Owner Dashboard Service
 *
 * Provides store-scoped data queries, revenue/earnings calculations,
 * and product analytics for store owners.
 *
 * All queries are scoped to the owner's store to ensure data isolation.
 *
 * @see Requirements 12.1, 12.2, 12.3, 12.5
 */
class StoreOwnerDashboardService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected StoreRepositoryInterface $storeRepository,
        protected MetricsService $metricsService
    ) {}

    /**
     * Get the store for a given user
     *
     * @param  User|object  $user  The store owner user (or any object with an id property)
     * @return Store|object|null The store or null if not found
     */
    public function getStoreForUser($user)
    {
        return $this->storeRepository->findByOwner($user->id);
    }

    /**
     * Get KPI metrics for a store owner's dashboard
     * All data is scoped to the owner's store
     *
     * @param  int  $storeId  The store ID
     * @return array Array containing revenue, orders, products, and earnings metrics
     *
     * @see Requirements 12.1, 12.3
     */
    public function getKPIMetrics(int $storeId): array
    {
        $currentStart = Carbon::now()->startOfMonth();
        $currentEnd = Carbon::now()->endOfMonth();
        $previousStart = Carbon::now()->subMonth()->startOfMonth();
        $previousEnd = Carbon::now()->subMonth()->endOfMonth();

        // Revenue metrics (scoped to store)
        $revenueThisMonth = $this->storeRepository->calculateRevenue($storeId, $currentStart, $currentEnd);
        $revenueLastMonth = $this->storeRepository->calculateRevenue($storeId, $previousStart, $previousEnd);
        $revenueGrowth = $this->metricsService->calculateGrowthPercentage($revenueThisMonth, $revenueLastMonth);

        // Earnings metrics (revenue minus platform commission)
        $earningsThisMonth = $this->storeRepository->calculateEarnings($storeId, $currentStart, $currentEnd);
        $earningsLastMonth = $this->storeRepository->calculateEarnings($storeId, $previousStart, $previousEnd);
        $earningsGrowth = $this->metricsService->calculateGrowthPercentage($earningsThisMonth, $earningsLastMonth);

        // Orders metrics (scoped to store)
        $ordersThisMonth = $this->orderRepository->getOrderCount($currentStart, $currentEnd, $storeId);
        $ordersLastMonth = $this->orderRepository->getOrderCount($previousStart, $previousEnd, $storeId);
        $ordersGrowth = $this->metricsService->calculateGrowthPercentage(
            (float) $ordersThisMonth,
            (float) $ordersLastMonth
        );

        // Products metrics
        $store = $this->storeRepository->findById($storeId);
        $totalProducts = $store ? $store->products()->count() : 0;
        $activeProducts = $store ? $store->products()->where('is_active', true)->count() : 0;

        return [
            'revenue' => [
                'value' => $revenueThisMonth,
                'formatted' => $this->metricsService->formatCurrency($revenueThisMonth),
                'previous' => $revenueLastMonth,
                'growth' => $this->metricsService->formatPercentage($revenueGrowth),
            ],
            'earnings' => [
                'value' => $earningsThisMonth,
                'formatted' => $this->metricsService->formatCurrency($earningsThisMonth),
                'previous' => $earningsLastMonth,
                'growth' => $this->metricsService->formatPercentage($earningsGrowth),
            ],
            'orders' => [
                'value' => $ordersThisMonth,
                'previous' => $ordersLastMonth,
                'growth' => $this->metricsService->formatPercentage($ordersGrowth),
            ],
            'products' => [
                'total' => $totalProducts,
                'active' => $activeProducts,
            ],
        ];
    }

    /**
     * Get revenue chart data for a store
     *
     * @param  int  $storeId  The store ID
     * @param  string  $period  Period: 'week', 'month', 'year'
     * @return array Chart data with labels and values
     *
     * @see Requirements 12.5
     */
    public function getRevenueChartData(int $storeId, string $period = 'month'): array
    {
        $labels = [];
        $values = [];

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    $values[] = $this->storeRepository->calculateRevenue(
                        $storeId,
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    );
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    $values[] = $this->storeRepository->calculateRevenue(
                        $storeId,
                        $date->copy()->startOfMonth(),
                        $date->copy()->endOfMonth()
                    );
                }
                break;

            case 'month':
            default:
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('d');
                    $values[] = $this->storeRepository->calculateRevenue(
                        $storeId,
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    );
                }
                break;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /**
     * Get orders chart data for a store
     *
     * @param  int  $storeId  The store ID
     * @param  string  $period  Period: 'week', 'month', 'year'
     * @return array Chart data with labels and values
     *
     * @see Requirements 12.5
     */
    public function getOrdersChartData(int $storeId, string $period = 'month'): array
    {
        $labels = [];
        $values = [];

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    $values[] = $this->orderRepository->getOrderCount(
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay(),
                        $storeId
                    );
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    $values[] = $this->orderRepository->getOrderCount(
                        $date->copy()->startOfMonth(),
                        $date->copy()->endOfMonth(),
                        $storeId
                    );
                }
                break;

            case 'month':
            default:
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('d');
                    $values[] = $this->orderRepository->getOrderCount(
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay(),
                        $storeId
                    );
                }
                break;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /**
     * Get orders for a store with pagination and filters
     * All orders are scoped to the store
     *
     * @param  int  $storeId  The store ID
     * @param  array  $filters  Filters including per_page, status, search, etc.
     *
     * @see Requirements 12.2
     */
    public function getOrders(int $storeId, array $filters = []): LengthAwarePaginator
    {
        return $this->orderRepository->getForStore($storeId, $filters);
    }

    /**
     * Get recent orders for a store
     *
     * @param  int  $storeId  The store ID
     * @param  int  $limit  Number of orders to return
     *
     * @see Requirements 12.2
     */
    public function getRecentOrders(int $storeId, int $limit = 10): Collection
    {
        return $this->orderRepository->getRecent($limit, $storeId);
    }

    /**
     * Get products for a store with pagination and filters
     * All products are scoped to the store
     *
     * @param  int  $storeId  The store ID
     * @param  array  $filters  Filters including per_page, search, is_active, etc.
     *
     * @see Requirements 12.4
     */
    public function getProducts(int $storeId, array $filters = []): LengthAwarePaginator
    {
        $query = Product::where('store_id', $storeId)
            ->with('category');

        // Apply search filter
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply active filter
        if (isset($filters['is_active'])) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_active')) {
                $query->where('is_active', $filters['is_active']);
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('products', 'status')) {
                if ((bool) $filters['is_active'] === true) {
                    $query->where('status', 'active');
                } else {
                    $query->where('status', '!=', 'active');
                }
            }
        }

        // Apply category filter
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get top selling products for a store
     *
     * @param  int  $storeId  The store ID
     * @param  int  $limit  Number of products to return
     * @param  Carbon|null  $start  Start date for the period
     * @param  Carbon|null  $end  End date for the period
     *
     * @see Requirements 12.5
     */
    public function getTopSellingProducts(int $storeId, int $limit = 10, ?Carbon $start = null, ?Carbon $end = null): Collection
    {
        $start = $start ?? Carbon::now()->startOfMonth();
        $end = $end ?? Carbon::now()->endOfMonth();

        return Product::where('store_id', $storeId)
            ->select('products.*')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->selectRaw('COALESCE(SUM(order_items.quantity * order_items.price), 0) as total_revenue')
            ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('orders', function ($join) use ($start, $end) {
                $join->on('orders.id', '=', 'order_items.order_id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->where('orders.status', 'completed');
            })
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    /**
     * Get product analytics for a store
     *
     * @param  int  $storeId  The store ID
     * @return array Analytics data including category breakdown, stock status, etc.
     *
     * @see Requirements 12.5
     */
    public function getProductAnalytics(int $storeId): array
    {
        $store = $this->storeRepository->findById($storeId);

        if (! $store) {
            return [
                'by_category' => [],
                'stock_status' => [],
                'rating_distribution' => [],
            ];
        }

        // Products by category
        $byCategory = Product::where('store_id', $storeId)
            ->select('category_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name ?? 'Uncategorized',
                    'count' => $item->count,
                ];
            });

        // Stock status
        $stockStatus = [
            'in_stock' => Product::where('store_id', $storeId)->where('stock', '>', 10)->count(),
            'low_stock' => Product::where('store_id', $storeId)->whereBetween('stock', [1, 10])->count(),
            'out_of_stock' => Product::where('store_id', $storeId)->where('stock', '<=', 0)->count(),
        ];

        // Rating distribution
        $ratingDistribution = Product::where('store_id', $storeId)
            ->select('rating')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();

        return [
            'by_category' => $byCategory,
            'stock_status' => $stockStatus,
            'rating_distribution' => $ratingDistribution,
        ];
    }

    /**
     * Calculate store revenue for a given period
     * Revenue is the total of completed orders containing the store's products
     *
     * @param  int  $storeId  The store ID
     * @param  Carbon  $start  Start date
     * @param  Carbon  $end  End date
     * @return float Total revenue
     *
     * @see Requirements 12.3
     */
    public function calculateRevenue(int $storeId, Carbon $start, Carbon $end): float
    {
        return $this->storeRepository->calculateRevenue($storeId, $start, $end);
    }

    /**
     * Calculate store earnings for a given period
     * Earnings = Revenue - Platform Commission
     *
     * @param  int  $storeId  The store ID
     * @param  Carbon  $start  Start date
     * @param  Carbon  $end  End date
     * @return float Net earnings after platform fees
     *
     * @see Requirements 12.3
     */
    public function calculateEarnings(int $storeId, Carbon $start, Carbon $end): float
    {
        return $this->storeRepository->calculateEarnings($storeId, $start, $end);
    }

    /**
     * Get the commission rate for a store
     *
     * @param  int  $storeId  The store ID
     * @return float Commission rate as a percentage
     */
    public function getCommissionRate(int $storeId): float
    {
        $store = $this->storeRepository->findById($storeId);

        return $store ? (float) $store->commission_rate : 0.0;
    }

    /**
     * Create a new product for a store
     *
     * @param  int  $storeId  The store ID
     * @param  array  $data  Product data
     *
     * @see Requirements 12.4
     */
    public function createProduct(int $storeId, array $data): Product
    {
        $data['store_id'] = $storeId;

        return Product::create($data);
    }

    /**
     * Update a product belonging to a store
     *
     * @param  int  $storeId  The store ID
     * @param  int  $productId  The product ID
     * @param  array  $data  Product data to update
     * @return Product|null Updated product or null if not found/not owned
     *
     * @see Requirements 12.4
     */
    public function updateProduct(int $storeId, int $productId, array $data): ?Product
    {
        $product = Product::where('store_id', $storeId)
            ->where('id', $productId)
            ->first();

        if (! $product) {
            return null;
        }

        // Ensure store_id cannot be changed
        unset($data['store_id']);

        $product->update($data);

        return $product->fresh();
    }

    /**
     * Delete a product belonging to a store
     *
     * @param  int  $storeId  The store ID
     * @param  int  $productId  The product ID
     * @return bool True if deleted, false if not found/not owned
     *
     * @see Requirements 12.4
     */
    public function deleteProduct(int $storeId, int $productId): bool
    {
        $product = Product::where('store_id', $storeId)
            ->where('id', $productId)
            ->first();

        if (! $product) {
            return false;
        }

        return $product->delete();
    }
}
