<?php

namespace App\Repositories\Contracts;

use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StoreRepositoryInterface
{
    /**
     * Find a store by ID
     */
    public function findById(int $id): ?Store;

    /**
     * Find a store by owner user ID
     */
    public function findByOwner(int $userId): ?Store;

    /**
     * Get all stores with optional filters
     */
    public function getAll(array $filters = []): LengthAwarePaginator;

    /**
     * Get stores by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get total store count
     */
    public function getTotalCount(): int;

    /**
     * Get active stores count
     */
    public function getActiveCount(): int;

    /**
     * Calculate store revenue within a date range
     */
    public function calculateRevenue(int $storeId, Carbon $start, Carbon $end): float;

    /**
     * Calculate store earnings (revenue minus commission) within a date range
     */
    public function calculateEarnings(int $storeId, Carbon $start, Carbon $end): float;

    /**
     * Get top performing stores by revenue
     */
    public function getTopByRevenue(int $limit = 10, ?Carbon $start = null, ?Carbon $end = null): Collection;
}
