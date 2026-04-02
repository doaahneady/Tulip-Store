<?php

namespace App\Services;

use App\Models\CustomerBalanceAudit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerBalanceService
{
    public function add(User $customer, float $amount, string $type, ?int $supportEmployeeId = null): float
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0');
        }

        return DB::transaction(function () use ($customer, $amount, $type, $supportEmployeeId) {
            $locked = User::query()->lockForUpdate()->findOrFail($customer->id);

            $current = (float) ($locked->balance ?? 0);
            $new = $current + $amount;

            $locked->forceFill(['balance' => $new])->save();

            CustomerBalanceAudit::create([
                'customer_id' => $locked->id,
                'amount' => $amount,
                'type' => $type,
                'support_user_id' => $supportEmployeeId,
                'created_at' => now(),
            ]);

            return $new;
        });
    }

    public function deduct(User $customer, float $amount, string $type, ?int $supportEmployeeId = null): float
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0');
        }

        return DB::transaction(function () use ($customer, $amount, $type, $supportEmployeeId) {
            $locked = User::query()->lockForUpdate()->findOrFail($customer->id);

            $current = (float) ($locked->balance ?? 0);
            if ($current + 1e-9 < $amount) {
                throw new \RuntimeException('Insufficient balance');
            }

            $new = $current - $amount;
            $locked->forceFill(['balance' => $new])->save();

            CustomerBalanceAudit::create([
                'customer_id' => $locked->id,
                'amount' => $amount,
                'type' => $type,
                'support_user_id' => $supportEmployeeId,
                'created_at' => now(),
            ]);

            return $new;
        });
    }
}

