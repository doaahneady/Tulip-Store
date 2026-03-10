<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\FinancialTransaction;
use App\Models\Order;
use App\Models\OrderRevenueRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderFinalizationService
{
    public function __construct(
        protected OrderStatusManager $statusManager
    ) {}

    public function finalizeIfNeeded(Order $order): void
    {
        $status = $this->statusManager->normalize((string) ($order->status ?? 'pending'));
        if (! $this->statusManager->shouldFinalizeOnStatus($status)) {
            return;
        }

        if (! Schema::hasTable('order_revenue_records') || ! Schema::hasTable('financial_transactions')) {
            $this->markCompleted($order);
            return;
        }

        DB::transaction(function () use ($order) {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();
            if (! $locked) {
                return;
            }

            $existing = OrderRevenueRecord::query()->where('order_id', $locked->id)->first();
            if ($existing) {
                $this->markCompleted($locked);
                return;
            }

            $amount = $this->calculateOrderTotal($locked);
            $currency = Schema::hasColumn('financial_transactions', 'currency') ? 'USD' : 'USD';

            $txn = FinancialTransaction::query()
                ->where('order_id', $locked->id)
                ->whereIn('type', ['order_payment', 'payment'])
                ->orderByDesc('id')
                ->first();

            if ($txn) {
                $old = [
                    'status' => $txn->status,
                    'amount' => $txn->amount,
                    'currency' => $txn->currency,
                ];
                try {
                    if ((bool) ($txn->is_immutable ?? false) && ($txn->status ?? null) !== 'completed') {
                        $txn = $this->createRevenueTransaction($locked, $amount, $currency);
                    } elseif (($txn->status ?? null) !== 'completed') {
                        $txn->update([
                            'status' => 'completed',
                            'amount' => $amount,
                            'currency' => $currency,
                        ]);
                    } elseif ((float) $txn->amount !== (float) $amount || (string) $txn->currency !== (string) $currency) {
                        $txn->update([
                            'amount' => $amount,
                            'currency' => $currency,
                        ]);
                    }
                } catch (\Throwable $e) {
                    $txn = $this->createRevenueTransaction($locked, $amount, $currency);
                }

                if ($txn && Schema::hasTable('audit_logs')) {
                    AuditLog::log('financial_transaction_updated', $txn, $old, [
                        'status' => $txn->status,
                        'amount' => $txn->amount,
                        'currency' => $txn->currency,
                    ], [
                        'source' => 'order_finalization',
                        'order_id' => $locked->id,
                    ]);
                }
            } else {
                $txn = $this->createRevenueTransaction($locked, $amount, $currency);
            }

            $record = OrderRevenueRecord::create([
                'order_id' => $locked->id,
                'financial_transaction_id' => $txn?->id,
                'amount' => $amount,
                'currency' => $currency,
                'recognized_at' => now(),
            ]);

            if (Schema::hasTable('audit_logs')) {
                AuditLog::log('order_revenue_recorded', $locked, null, [
                    'order_id' => $locked->id,
                    'amount' => $record->amount,
                    'currency' => $record->currency,
                    'financial_transaction_id' => $record->financial_transaction_id,
                ]);
            }

            if (Schema::hasTable('stores') && Schema::hasColumn('stores', 'total_sales') && $locked->store_id) {
                DB::table('stores')->where('id', $locked->store_id)->increment('total_sales', $amount);
                if (Schema::hasTable('audit_logs')) {
                    AuditLog::log('store_sales_incremented', $locked, null, [
                        'store_id' => $locked->store_id,
                        'amount' => $amount,
                        'source' => 'order_finalization',
                        'order_id' => $locked->id,
                    ]);
                }
            }

            $this->markCompleted($locked);
        });
    }

    private function markCompleted(Order $order): void
    {
        $updates = [];
        if (Schema::hasColumn('orders', 'is_completed')) {
            $updates['is_completed'] = true;
        }
        if (Schema::hasColumn('orders', 'completed_at') && ! $order->completed_at) {
            $updates['completed_at'] = now();
        }
        if (Schema::hasColumn('orders', 'revenue_recognized_at') && ! $order->revenue_recognized_at) {
            $updates['revenue_recognized_at'] = now();
        }

        if ($updates) {
            Order::withoutEvents(function () use ($order, $updates) {
                $old = [];
                foreach (array_keys($updates) as $k) {
                    $old[$k] = $order->getAttribute($k);
                }
                $order->forceFill($updates)->save();
                if (Schema::hasTable('audit_logs')) {
                    AuditLog::log('order_marked_completed', $order, $old, $updates, [
                        'source' => 'order_finalization',
                    ]);
                }
            });
        }
    }

    private function calculateOrderTotal(Order $order): float
    {
        $v = null;
        if (Schema::hasColumn('orders', 'total_amount')) {
            $v = $order->total_amount;
        }
        if ($v === null && Schema::hasColumn('orders', 'total')) {
            $v = $order->total;
        }
        if ($v === null && Schema::hasColumn('orders', 'subtotal')) {
            $parts = [(float) ($order->subtotal ?? 0)];
            if (Schema::hasColumn('orders', 'delivery_cost')) {
                $parts[] = (float) ($order->delivery_cost ?? 0);
            }
            if (Schema::hasColumn('orders', 'service_fee')) {
                $parts[] = (float) ($order->service_fee ?? 0);
            }
            $v = array_sum($parts);
        }

        if ($v === null) {
            try {
                $order->loadMissing('items');
                $v = (float) $order->items->sum(function ($i) {
                    return (float) ($i->total_price ?? $i->subtotal ?? 0);
                });
            } catch (\Throwable $e) {
                $v = 0;
            }
        }

        return max(0, round((float) $v, 2));
    }

    private function createRevenueTransaction(Order $order, float $amount, string $currency): ?FinancialTransaction
    {
        $type = 'order_payment';
        $payload = [
            'transaction_id' => FinancialTransaction::generateTransactionId('payment'),
            'user_id' => Schema::hasColumn('financial_transactions', 'user_id') ? ($order->customer_id ?? $order->user_id) : null,
            'order_id' => $order->id,
            'store_id' => $order->store_id,
            'type' => $type,
            'status' => 'completed',
            'amount' => $amount,
            'currency' => $currency,
            'description' => 'Order revenue recognized',
            'metadata' => [
                'source' => 'order_finalization',
                'status' => $order->status,
            ],
        ];

        try {
            return FinancialTransaction::create($payload);
        } catch (\Throwable $e) {
            try {
                $payload['type'] = 'payment';
                return FinancialTransaction::create($payload);
            } catch (\Throwable $e2) {
                return null;
            }
        }
    }
}
