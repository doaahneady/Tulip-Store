<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * Find a user by ID
     */
    public function findById(int $id): ?User;

    /**
     * Search users by name, email, or phone
     */
    public function search(string $query, array $filters = []): LengthAwarePaginator;

    /**
     * Get users by role
     */
    public function getByRole(string $role): Collection;

    /**
     * Get total user count
     */
    public function getTotalCount(): int;

    /**
     * Get users with pagination and filters
     */
    public function getAll(array $filters = []): LengthAwarePaginator;

    /**
     * Get active users count (logged in within specified days)
     */
    public function getActiveCount(int $days = 30): int;

    /**
     * Get users created within a date range
     */
    public function getCreatedBetween(\Carbon\Carbon $start, \Carbon\Carbon $end): Collection;
}
