<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    /**
     * Find an order by ID
     */
    public function findById(int $id): ?Order;

    /**
     * Get orders for a specific store with optional filters
     */
    public function getForStore(int $storeId, array $filters = []): LengthAwarePaginator;

    /**
     * Get all orders with optional filters
     */
    public function getAll(array $filters = []): LengthAwarePaginator;

    /**
     * Get total revenue within a date range, optionally filtered by store
     */
    public function getTotalRevenue(Carbon $start, Carbon $end, ?int $storeId = null): float;

    /**
     * Get order count within a date range, optionally filtered by store
     */
    public function getOrderCount(Carbon $start, Carbon $end, ?int $storeId = null): int;

    /**
     * Get orders by status
     */
    public function getByStatus(string $status, array $filters = []): LengthAwarePaginator;

    /**
     * Get recent orders
     */
    public function getRecent(int $limit = 10, ?int $storeId = null): Collection;
}
