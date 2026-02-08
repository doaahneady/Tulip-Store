<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $model
    ) {}

    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        $builder = $this->model->newQuery()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('mobile', 'like', "%{$query}%")
                    ->orWhere('user_full_name', 'like', "%{$query}%");
            });

        return $this->applyFilters($builder, $filters)
            ->paginate($filters['per_page'] ?? 25);
    }

    public function getByRole(string $role): Collection
    {
        $roleField = $this->getRoleField($role);

        if ($roleField === null) {
            return collect();
        }

        return $this->model->newQuery()
            ->where($roleField, true)
            ->get();
    }

    public function getTotalCount(): int
    {
        return $this->model->count();
    }

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        return $this->applyFilters($query, $filters)
            ->paginate($filters['per_page'] ?? 25);
    }

    public function getActiveCount(int $days = 30): int
    {
        return $this->model->newQuery()
            ->where('updated_at', '>=', Carbon::now()->subDays($days))
            ->count();
    }

    public function getCreatedBetween(Carbon $start, Carbon $end): Collection
    {
        return $this->model->newQuery()
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }

    protected function applyFilters($query, array $filters)
    {
        if (! empty($filters['role'])) {
            $roleField = $this->getRoleField($filters['role']);
            if ($roleField !== null) {
                $query->where($roleField, true);
            }
        }

        if (! empty($filters['verified'])) {
            $query->where('verified', $filters['verified'] === 'true' || $filters['verified'] === true);
        }

        if (! empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    protected function getRoleField(string $role): ?string
    {
        $roleMap = [
            'admin' => 'is_admin',
            'trader' => 'is_trader',
            'store_owner' => 'is_trader',
            'it' => 'is_it',
            'it_super' => 'is_it_super',
            'hr' => 'is_hr',
            'cs' => 'is_cs',
            'finance' => 'is_finance',
            'accountant' => 'is_accountant',
            'driver_supervisor' => 'is_driver_supervisor',
        ];

        return $roleMap[$role] ?? null;
    }
}
