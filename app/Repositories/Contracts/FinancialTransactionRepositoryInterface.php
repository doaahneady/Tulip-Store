<?php

namespace App\Repositories\Contracts;

use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FinancialTransactionRepositoryInterface
{
    /**
     * Find a transaction by ID
     */
    public function findById(int $id): ?FinancialTransaction;

    /**
     * Get all transactions with optional filters
     */
    public function getAll(array $filters = []): LengthAwarePaginator;

    /**
     * Get transactions for a specific store
     */
    public function getForStore(int $storeId, array $filters = []): LengthAwarePaginator;

    /**
     * Get transactions by type
     */
    public function getByType(string $type, array $filters = []): LengthAwarePaginator;

    /**
     * Get transactions by status
     */
    public function getByStatus(string $status, array $filters = []): LengthAwarePaginator;

    /**
     * Get total amount by type within a date range
     */
    public function getTotalByType(string $type, Carbon $start, Carbon $end, ?int $storeId = null): float;

    /**
     * Get pending payouts
     */
    public function getPendingPayouts(): Collection;

    /**
     * Get transactions within a date range
     */
    public function getByDateRange(Carbon $start, Carbon $end, array $filters = []): LengthAwarePaginator;

    /**
     * Create a new transaction
     */
    public function create(array $data): FinancialTransaction;

    /**
     * Update a transaction (only if not immutable)
     */
    public function update(int $id, array $data): ?FinancialTransaction;
}
