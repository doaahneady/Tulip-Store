<?php

namespace App\Services\Dashboard;

use App\Models\SystemLog;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\StoreRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected OrderRepositoryInterface $orderRepository,
        protected StoreRepositoryInterface $storeRepository,
        protected MetricsService $metricsService
    ) {}

    /**
     * Get admin KPI metrics
     *
     * @return array Array containing total_users, total_orders, total_revenue, active_stores
     */
    public function getKPIMetrics(): array
    {
        $currentStart = Carbon::now()->startOfMonth();
        $currentEnd = Carbon::now()->endOfMonth();
        $previousStart = Carbon::now()->subMonth()->startOfMonth();
        $previousEnd = Carbon::now()->subMonth()->endOfMonth();

        // Users metrics
        $totalUsers = $this->userRepository->getTotalCount();
        $activeUsers = $this->userRepository->getActiveCount(30);
        $newUsersThisMonth = $this->userRepository->getCreatedBetween($currentStart, $currentEnd)->count();
        $newUsersLastMonth = $this->userRepository->getCreatedBetween($previousStart, $previousEnd)->count();
        $userGrowth = $this->metricsService->calculateGrowthPercentage(
            (float) $newUsersThisMonth,
            (float) $newUsersLastMonth
        );

        // Orders metrics
        $totalOrders = $this->orderRepository->getOrderCount(
            Carbon::createFromTimestamp(0),
            Carbon::now()
        );
        $ordersThisMonth = $this->orderRepository->getOrderCount($currentStart, $currentEnd);
        $ordersLastMonth = $this->orderRepository->getOrderCount($previousStart, $previousEnd);
        $orderGrowth = $this->metricsService->calculateGrowthPercentage(
            (float) $ordersThisMonth,
            (float) $ordersLastMonth
        );

        // Revenue metrics
        $revenueThisMonth = $this->orderRepository->getTotalRevenue($currentStart, $currentEnd);
        $revenueLastMonth = $this->orderRepository->getTotalRevenue($previousStart, $previousEnd);
        $revenueGrowth = $this->metricsService->calculateGrowthPercentage($revenueThisMonth, $revenueLastMonth);

        // Stores metrics
        $totalStores = $this->storeRepository->getTotalCount();
        $activeStores = $this->storeRepository->getActiveCount();

        return [
            'total_users' => [
                'value' => $totalUsers,
                'active' => $activeUsers,
                'new_this_month' => $newUsersThisMonth,
                'growth' => $this->metricsService->formatPercentage($userGrowth),
            ],
            'total_orders' => [
                'value' => $totalOrders,
                'this_month' => $ordersThisMonth,
                'growth' => $this->metricsService->formatPercentage($orderGrowth),
            ],
            'total_revenue' => [
                'value' => $revenueThisMonth,
                'formatted' => $this->metricsService->formatCurrency($revenueThisMonth),
                'previous' => $revenueLastMonth,
                'growth' => $this->metricsService->formatPercentage($revenueGrowth),
            ],
            'active_stores' => [
                'value' => $activeStores,
                'total' => $totalStores,
            ],
        ];
    }

    /**
     * Get chart data for revenue trends
     *
     * @param  string  $period  Period: 'week', 'month', 'year'
     * @return array Chart data with labels and values
     */
    public function getRevenueChartData(string $period = 'month'): array
    {
        $labels = [];
        $values = [];

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    $values[] = $this->orderRepository->getTotalRevenue(
                        $date->startOfDay(),
                        $date->endOfDay()
                    );
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    $values[] = $this->orderRepository->getTotalRevenue(
                        $date->startOfMonth(),
                        $date->endOfMonth()
                    );
                }
                break;

            case 'month':
            default:
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('d');
                    $values[] = $this->orderRepository->getTotalRevenue(
                        $date->startOfDay(),
                        $date->endOfDay()
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
     * Get chart data for order volume
     *
     * @param  string  $period  Period: 'week', 'month', 'year'
     * @return array Chart data with labels and values
     */
    public function getOrderChartData(string $period = 'month'): array
    {
        $labels = [];
        $values = [];

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    $values[] = $this->orderRepository->getOrderCount(
                        $date->startOfDay(),
                        $date->endOfDay()
                    );
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    $values[] = $this->orderRepository->getOrderCount(
                        $date->startOfMonth(),
                        $date->endOfMonth()
                    );
                }
                break;

            case 'month':
            default:
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('d');
                    $values[] = $this->orderRepository->getOrderCount(
                        $date->startOfDay(),
                        $date->endOfDay()
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
     * Search users by name, email, or phone
     *
     * @param  string  $query  Search query
     * @param  array  $filters  Additional filters
     */
    public function searchUsers(string $query, array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->search($query, $filters);
    }

    /**
     * Get all users with pagination and filters
     *
     * @param  array  $filters  Filters including per_page, role, verified, etc.
     */
    public function getUsers(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->getAll($filters);
    }

    /**
     * Get all orders with pagination and filters
     *
     * @param  array  $filters  Filters including per_page, status, search, etc.
     */
    public function getOrders(array $filters = []): LengthAwarePaginator
    {
        return $this->orderRepository->getAll($filters);
    }

    /**
     * Get all stores with pagination and filters
     *
     * @param  array  $filters  Filters including per_page, status, search, etc.
     */
    public function getStores(array $filters = []): LengthAwarePaginator
    {
        return $this->storeRepository->getAll($filters);
    }

    /**
     * Get recent orders
     *
     * @param  int  $limit  Number of orders to return
     */
    public function getRecentOrders(int $limit = 10): Collection
    {
        return $this->orderRepository->getRecent($limit);
    }

    /**
     * Get top performing stores
     *
     * @param  int  $limit  Number of stores to return
     */
    public function getTopStores(int $limit = 10): Collection
    {
        return $this->storeRepository->getTopByRevenue($limit);
    }

    /**
     * Get system alerts (errors and warnings from system logs)
     *
     * @param  int  $limit  Number of alerts to return
     */
    public function getSystemAlerts(int $limit = 10): Collection
    {
        return SystemLog::whereIn('level', ['error', 'critical', 'warning'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Process bulk actions on users with transaction support
     * Rolls back all changes if any action fails
     *
     * @param  string  $action  Action to perform: 'activate', 'deactivate', 'delete', 'verify'
     * @param  array  $userIds  Array of user IDs to process
     * @return array Result with success status and processed count
     *
     * @throws \Exception If any action fails (triggers rollback)
     */
    public function processBulkUserAction(string $action, array $userIds): array
    {
        $processed = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($userIds as $userId) {
                $user = $this->userRepository->findById($userId);

                if (! $user) {
                    throw new \Exception("User with ID {$userId} not found");
                }

                switch ($action) {
                    case 'activate':
                        $user->verified = true;
                        $user->save();
                        break;

                    case 'deactivate':
                        $user->verified = false;
                        $user->save();
                        break;

                    case 'verify':
                        $user->verified = true;
                        $user->email_verified_at = now();
                        $user->save();
                        break;

                    case 'delete':
                        $user->delete();
                        break;

                    default:
                        throw new \Exception("Unknown action: {$action}");
                }

                $processed++;
            }

            DB::commit();

            return [
                'success' => true,
                'processed' => $processed,
                'total' => count($userIds),
                'errors' => [],
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'processed' => 0,
                'total' => count($userIds),
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Process bulk actions on orders with transaction support
     * Rolls back all changes if any action fails
     *
     * @param  string  $action  Action to perform: 'cancel', 'complete', 'process'
     * @param  array  $orderIds  Array of order IDs to process
     * @return array Result with success status and processed count
     *
     * @throws \Exception If any action fails (triggers rollback)
     */
    public function processBulkOrderAction(string $action, array $orderIds): array
    {
        $processed = 0;

        DB::beginTransaction();

        try {
            foreach ($orderIds as $orderId) {
                $order = $this->orderRepository->findById($orderId);

                if (! $order) {
                    throw new \Exception("Order with ID {$orderId} not found");
                }

                switch ($action) {
                    case 'cancel':
                        $order->status = 'cancelled';
                        $order->save();
                        break;

                    case 'complete':
                        $order->status = 'completed';
                        $order->save();
                        break;

                    case 'process':
                        $order->status = 'processing';
                        $order->save();
                        break;

                    default:
                        throw new \Exception("Unknown action: {$action}");
                }

                $processed++;
            }

            DB::commit();

            return [
                'success' => true,
                'processed' => $processed,
                'total' => count($orderIds),
                'errors' => [],
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'processed' => 0,
                'total' => count($orderIds),
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Process bulk actions on stores with transaction support
     * Rolls back all changes if any action fails
     *
     * @param  string  $action  Action to perform: 'approve', 'suspend', 'delete'
     * @param  array  $storeIds  Array of store IDs to process
     * @return array Result with success status and processed count
     *
     * @throws \Exception If any action fails (triggers rollback)
     */
    public function processBulkStoreAction(string $action, array $storeIds): array
    {
        $processed = 0;

        DB::beginTransaction();

        try {
            foreach ($storeIds as $storeId) {
                $store = $this->storeRepository->findById($storeId);

                if (! $store) {
                    throw new \Exception("Store with ID {$storeId} not found");
                }

                switch ($action) {
                    case 'approve':
                        $store->status = 'approved';
                        $store->save();
                        break;

                    case 'suspend':
                        $store->status = 'suspended';
                        $store->save();
                        break;

                    case 'delete':
                        $store->delete();
                        break;

                    default:
                        throw new \Exception("Unknown action: {$action}");
                }

                $processed++;
            }

            DB::commit();

            return [
                'success' => true,
                'processed' => $processed,
                'total' => count($storeIds),
                'errors' => [],
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'processed' => 0,
                'total' => count($storeIds),
                'errors' => [$e->getMessage()],
            ];
        }
    }
}
