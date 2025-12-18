<?php

namespace App\Services\Dashboard;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

/**
 * DataTableService provides server-side operations for data tables
 * including pagination, sorting, search filtering, and date range filtering.
 * 
 * Requirements: 4.1, 4.2, 4.3, 4.4
 */
class DataTableService
{
    /**
     * Valid page sizes as per Requirements 4.1
     */
    protected array $validPageSizes = [10, 25, 50, 100];

    /**
     * Default page size
     */
    protected int $defaultPageSize = 25;

    /**
     * Apply all data table operations to a query builder
     *
     * @param Builder $query The Eloquent query builder
     * @param array $options Options for filtering, sorting, and pagination
     * @return LengthAwarePaginator
     */
    public function apply(Builder $query, array $options = []): LengthAwarePaginator
    {
        // Apply search filter
        if (!empty($options['search']) && !empty($options['searchable_columns'])) {
            $query = $this->applySearch($query, $options['search'], $options['searchable_columns']);
        }

        // Apply date range filter
        if (!empty($options['date_from']) || !empty($options['date_to'])) {
            $dateColumn = $options['date_column'] ?? 'created_at';
            $query = $this->applyDateRange(
                $query,
                $options['date_from'] ?? null,
                $options['date_to'] ?? null,
                $dateColumn
            );
        }

        // Apply sorting
        if (!empty($options['sort_by'])) {
            $sortableColumns = $options['sortable_columns'] ?? [];
            $direction = $options['sort_direction'] ?? 'asc';
            $query = $this->applySort($query, $options['sort_by'], $direction, $sortableColumns);
        }

        // Apply pagination
        $pageSize = $this->validatePageSize($options['per_page'] ?? $this->defaultPageSize);

        return $query->paginate($pageSize);
    }


    /**
     * Apply search filtering to a query
     * 
     * Requirements 4.3: Filter results across searchable columns
     *
     * @param Builder $query The query builder
     * @param string $searchTerm The search term
     * @param array $searchableColumns Columns to search in
     * @return Builder
     */
    public function applySearch(Builder $query, string $searchTerm, array $searchableColumns): Builder
    {
        if (empty($searchTerm) || empty($searchableColumns)) {
            return $query;
        }

        $searchTerm = trim($searchTerm);
        
        if (empty($searchTerm)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($searchTerm, $searchableColumns) {
            foreach ($searchableColumns as $column) {
                $q->orWhere($column, 'like', '%' . $searchTerm . '%');
            }
        });
    }

    /**
     * Apply date range filtering to a query
     * 
     * Requirements 4.4: Return only records within the specified date range inclusive of boundaries
     *
     * @param Builder $query The query builder
     * @param string|Carbon|null $dateFrom Start date (inclusive)
     * @param string|Carbon|null $dateTo End date (inclusive)
     * @param string $dateColumn The column to filter on
     * @return Builder
     */
    public function applyDateRange(
        Builder $query,
        $dateFrom = null,
        $dateTo = null,
        string $dateColumn = 'created_at'
    ): Builder {
        if ($dateFrom !== null) {
            $from = $dateFrom instanceof Carbon ? $dateFrom : Carbon::parse($dateFrom)->startOfDay();
            $query->where($dateColumn, '>=', $from);
        }

        if ($dateTo !== null) {
            $to = $dateTo instanceof Carbon ? $dateTo : Carbon::parse($dateTo)->endOfDay();
            $query->where($dateColumn, '<=', $to);
        }

        return $query;
    }

    /**
     * Apply sorting to a query
     * 
     * Requirements 4.2: Sort data by column in ascending or descending order
     *
     * @param Builder $query The query builder
     * @param string $sortBy Column to sort by
     * @param string $direction Sort direction (asc or desc)
     * @param array $allowedColumns Allowed columns for sorting (empty = all allowed)
     * @return Builder
     */
    public function applySort(
        Builder $query,
        string $sortBy,
        string $direction = 'asc',
        array $allowedColumns = []
    ): Builder {
        // Validate sort direction
        $direction = strtolower($direction);
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Validate column if allowed columns are specified
        if (!empty($allowedColumns) && !in_array($sortBy, $allowedColumns)) {
            return $query;
        }

        return $query->orderBy($sortBy, $direction);
    }

    /**
     * Apply pagination to a query
     * 
     * Requirements 4.1: Provide server-side pagination with configurable page sizes
     *
     * @param Builder $query The query builder
     * @param int $pageSize Items per page
     * @return LengthAwarePaginator
     */
    public function applyPagination(Builder $query, int $pageSize = 25): LengthAwarePaginator
    {
        $pageSize = $this->validatePageSize($pageSize);
        return $query->paginate($pageSize);
    }

    /**
     * Validate and normalize page size
     * 
     * Requirements 4.1: Configurable page sizes of 10, 25, 50, and 100 items
     *
     * @param int $pageSize Requested page size
     * @return int Valid page size
     */
    public function validatePageSize(int $pageSize): int
    {
        if (in_array($pageSize, $this->validPageSizes)) {
            return $pageSize;
        }

        // Return the closest valid page size
        $closest = $this->defaultPageSize;
        $minDiff = PHP_INT_MAX;

        foreach ($this->validPageSizes as $validSize) {
            $diff = abs($validSize - $pageSize);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $validSize;
            }
        }

        return $closest;
    }

    /**
     * Get valid page sizes
     *
     * @return array
     */
    public function getValidPageSizes(): array
    {
        return $this->validPageSizes;
    }

    /**
     * Check if a search term matches any value in the searchable columns
     * 
     * @param object $item The item to check
     * @param string $searchTerm The search term
     * @param array $searchableColumns Columns to search in
     * @return bool
     */
    public function itemMatchesSearch(object $item, string $searchTerm, array $searchableColumns): bool
    {
        $searchTerm = strtolower(trim($searchTerm));
        
        if (empty($searchTerm)) {
            return true;
        }

        foreach ($searchableColumns as $column) {
            $value = $item->$column ?? null;
            if ($value !== null && str_contains(strtolower((string) $value), $searchTerm)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if an item's date falls within a date range
     * 
     * @param object $item The item to check
     * @param string|Carbon|null $dateFrom Start date (inclusive)
     * @param string|Carbon|null $dateTo End date (inclusive)
     * @param string $dateColumn The column to check
     * @return bool
     */
    public function itemInDateRange(
        object $item,
        $dateFrom = null,
        $dateTo = null,
        string $dateColumn = 'created_at'
    ): bool {
        $itemDate = $item->$dateColumn ?? null;
        
        if ($itemDate === null) {
            return false;
        }

        if (!$itemDate instanceof Carbon) {
            $itemDate = Carbon::parse($itemDate);
        }

        if ($dateFrom !== null) {
            $from = $dateFrom instanceof Carbon ? $dateFrom : Carbon::parse($dateFrom)->startOfDay();
            if ($itemDate->lt($from)) {
                return false;
            }
        }

        if ($dateTo !== null) {
            $to = $dateTo instanceof Carbon ? $dateTo : Carbon::parse($dateTo)->endOfDay();
            if ($itemDate->gt($to)) {
                return false;
            }
        }

        return true;
    }
}
