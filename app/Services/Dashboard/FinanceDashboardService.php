<?php

namespace App\Services\Dashboard;

use App\Models\FinancialTransaction;
use App\Models\Order;
use App\Models\Payout;
use App\Models\User;
use App\Repositories\Contracts\FinancialTransactionRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\StoreRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Finance Dashboard Service
 *
 * Provides financial KPIs, transaction management, and payout approval workflow.
 *
 * @see Requirements 13.1, 13.2, 13.3, 13.5
 */
class FinanceDashboardService
{
    public function __construct(
        protected FinancialTransactionRepositoryInterface $transactionRepository,
        protected OrderRepositoryInterface $orderRepository,
        protected StoreRepositoryInterface $storeRepository,
        protected MetricsService $metricsService,
        protected AuditService $auditService
    ) {}

    /**
     * Get financial KPI metrics
     *
     * @return array Array containing daily_revenue, monthly_revenue, pending_payouts, profit_margin
     *
     * @see Requirements 13.1
     */
    public function getKPIMetrics(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Daily revenue
        $dailyRevenue = $this->orderRepository->getTotalRevenue(
            $today->copy()->startOfDay(),
            $today->copy()->endOfDay()
        );
        $yesterdayRevenue = $this->orderRepository->getTotalRevenue(
            $yesterday->copy()->startOfDay(),
            $yesterday->copy()->endOfDay()
        );
        $dailyGrowth = $this->metricsService->calculateGrowthPercentage($dailyRevenue, $yesterdayRevenue);

        // Monthly revenue
        $monthlyRevenue = $this->orderRepository->getTotalRevenue($currentMonthStart, $currentMonthEnd);
        $previousMonthRevenue = $this->orderRepository->getTotalRevenue($previousMonthStart, $previousMonthEnd);
        $monthlyGrowth = $this->metricsService->calculateGrowthPercentage($monthlyRevenue, $previousMonthRevenue);

        // Delivery collected (delivery fees) when order is delivered/done
        $terminal = (array) config('order_statuses.terminal', ['done']);
        $deliveryStatuses = array_values(array_unique(array_merge(['delivered'], $terminal)));
        $dailyDelivery = \Illuminate\Support\Facades\Schema::hasColumn('orders', 'delivery_cost')
            ? (float) Order::whereIn('status', $deliveryStatuses)->whereBetween('updated_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])->sum('delivery_cost')
            : 0.0;
        $monthlyDelivery = \Illuminate\Support\Facades\Schema::hasColumn('orders', 'delivery_cost')
            ? (float) Order::whereIn('status', $deliveryStatuses)->whereBetween('updated_at', [$currentMonthStart, $currentMonthEnd])->sum('delivery_cost')
            : 0.0;

        // Pending payouts
        $pendingPayouts = Payout::pending()->sum('amount');
        $pendingPayoutsCount = Payout::pending()->count();

        // Calculate profit margin (revenue - expenses - payouts)
        $totalExpenses = $this->transactionRepository->getTotalByType('expense', $currentMonthStart, $currentMonthEnd);
        $totalPayouts = $this->transactionRepository->getTotalByType('payout', $currentMonthStart, $currentMonthEnd);
        $profitMargin = $monthlyRevenue > 0
            ? (($monthlyRevenue - $totalExpenses - $totalPayouts) / $monthlyRevenue) * 100
            : 0;

        $vipUserIds = User::where('tags', 'like', '%VIP%')->pluck('id');
        $vipRevenue = FinancialTransaction::whereIn('user_id', $vipUserIds)
            ->where('type', 'order_payment')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return [
            'daily_revenue' => [
                'value' => $dailyRevenue,
                'formatted' => $this->metricsService->formatCurrency($dailyRevenue),
                'previous' => $yesterdayRevenue,
                'growth' => $this->metricsService->formatPercentage($dailyGrowth),
            ],
            'daily_delivery' => [
                'value' => $dailyDelivery,
                'formatted' => $this->metricsService->formatCurrency($dailyDelivery),
            ],
            'monthly_revenue' => [
                'value' => $monthlyRevenue,
                'formatted' => $this->metricsService->formatCurrency($monthlyRevenue),
                'previous' => $previousMonthRevenue,
                'growth' => $this->metricsService->formatPercentage($monthlyGrowth),
            ],
            'monthly_delivery' => [
                'value' => $monthlyDelivery,
                'formatted' => $this->metricsService->formatCurrency($monthlyDelivery),
            ],
            'vip_revenue' => [
                'value' => $vipRevenue,
                'formatted' => $this->metricsService->formatCurrency($vipRevenue),
            ],
            'pending_payouts' => [
                'value' => $pendingPayouts,
                'formatted' => $this->metricsService->formatCurrency($pendingPayouts),
                'count' => $pendingPayoutsCount,
            ],
            'profit_margin' => [
                'value' => round($profitMargin, 2),
                'formatted' => $this->metricsService->formatPercentage($profitMargin),
            ],
            'total_expenses' => [
                'value' => $totalExpenses,
                'formatted' => $this->metricsService->formatCurrency($totalExpenses),
            ],
        ];
    }

    /**
     * Get transactions with filters
     *
     * @param  array  $filters  Filters including type, status, date_from, date_to, search, per_page
     *
     * @see Requirements 13.2
     */
    public function getTransactions(array $filters = []): LengthAwarePaginator
    {
        return $this->transactionRepository->getAll($filters);
    }

    /**
     * Get transactions by type
     *
     * @param  string  $type  Transaction type: income, expense, payout, refund
     * @param  array  $filters  Additional filters
     *
     * @see Requirements 13.2
     */
    public function getTransactionsByType(string $type, array $filters = []): LengthAwarePaginator
    {
        return $this->transactionRepository->getByType($type, $filters);
    }

    /**
     * Get pending payouts
     *
     * @see Requirements 13.5
     */
    public function getPendingPayouts(): Collection
    {
        return Payout::pending()
            ->with(['store', 'store.owner'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get all payouts with pagination
     *
     * @param  array  $filters  Filters including status, store_id, per_page
     *
     * @see Requirements 13.5
     */
    public function getPayouts(array $filters = []): LengthAwarePaginator
    {
        $query = Payout::with(['store', 'store.owner', 'processor']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['store_id'])) {
            $query->where('store_id', $filters['store_id']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('store', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Approve a payout
     * Creates an immutable audit record
     *
     * @param  int  $payoutId  The payout ID
     * @param  User  $approver  The user approving the payout
     * @return Payout|null The approved payout or null if not found
     *
     * @see Requirements 13.3
     */
    public function approvePayout(int $payoutId, User $approver): ?Payout
    {
        $payout = Payout::find($payoutId);

        if (! $payout || $payout->status !== 'pending') {
            return null;
        }

        DB::beginTransaction();

        try {
            // Update payout status
            $payout->update([
                'status' => 'approved',
                'processed_by' => $approver->id,
                'processed_at' => now(),
            ]);

            // Create corresponding financial transaction (immutable)
            $transaction = $this->transactionRepository->create([
                'store_id' => $payout->store_id,
                'type' => 'payout',
                'amount' => $payout->amount,
                'status' => 'approved',
                'reference' => 'PAYOUT-'.$payout->id,
                'description' => 'Payout to store: '.($payout->store->name ?? 'Unknown'),
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'is_immutable' => true,
            ]);

            // Log the approval action
            $this->auditService->log(
                'approve',
                'payout',
                $payoutId,
                [
                    'new_values' => [
                        'status' => 'approved',
                        'amount' => $payout->amount,
                        'store_id' => $payout->store_id,
                        'transaction_id' => $transaction->id,
                    ],
                ]
            );

            DB::commit();

            return $payout->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject a payout
     *
     * @param  int  $payoutId  The payout ID
     * @param  User  $rejector  The user rejecting the payout
     * @param  string|null  $reason  Rejection reason
     * @return Payout|null The rejected payout or null if not found
     */
    public function rejectPayout(int $payoutId, User $rejector, ?string $reason = null): ?Payout
    {
        $payout = Payout::find($payoutId);

        if (! $payout || $payout->status !== 'pending') {
            return null;
        }

        $payout->update([
            'status' => 'rejected',
            'processed_by' => $rejector->id,
            'processed_at' => now(),
            'notes' => $reason,
        ]);

        // Log the rejection action
        $this->auditService->log(
            'reject',
            'payout',
            $payoutId,
            [
                'new_values' => [
                    'status' => 'rejected',
                    'reason' => $reason,
                ],
            ]
        );

        return $payout->fresh();
    }

    /**
     * Get revenue chart data
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
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    );
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    $values[] = $this->orderRepository->getTotalRevenue(
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
                    $values[] = $this->orderRepository->getTotalRevenue(
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
     * Get expenses chart data
     *
     * @param  string  $period  Period: 'week', 'month', 'year'
     * @return array Chart data with labels and values
     */
    public function getExpensesChartData(string $period = 'month'): array
    {
        $labels = [];
        $values = [];

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    $values[] = $this->transactionRepository->getTotalByType(
                        'expense',
                        $date->copy()->startOfDay(),
                        $date->copy()->endOfDay()
                    );
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    $values[] = $this->transactionRepository->getTotalByType(
                        'expense',
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
                    $values[] = $this->transactionRepository->getTotalByType(
                        'expense',
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
     * Get store settlements (pending and completed payouts to store owners)
     *
     * @param  array  $filters  Filters including status, per_page
     *
     * @see Requirements 13.5
     */
    public function getStoreSettlements(array $filters = []): LengthAwarePaginator
    {
        return $this->getPayouts($filters);
    }

    /**
     * Generate balance sheet data
     *
     * @param  Carbon  $asOfDate  The date for the balance sheet
     * @return array Balance sheet data
     *
     * @see Requirements 13.4
     */
    public function generateBalanceSheet(Carbon $asOfDate): array
    {
        $startOfTime = Carbon::createFromTimestamp(0);

        // Assets
        $totalRevenue = $this->orderRepository->getTotalRevenue($startOfTime, $asOfDate);
        $cashOnHand = $totalRevenue; // Simplified - in real app would track actual cash

        // Liabilities
        $pendingPayouts = Payout::pending()->where('created_at', '<=', $asOfDate)->sum('amount');
        $completedPayouts = Payout::completed()->where('processed_at', '<=', $asOfDate)->sum('amount');

        // Expenses
        $totalExpenses = $this->transactionRepository->getTotalByType('expense', $startOfTime, $asOfDate);

        // Equity
        $netIncome = $totalRevenue - $totalExpenses - $completedPayouts;

        return [
            'as_of_date' => $asOfDate->format('Y-m-d'),
            'assets' => [
                'cash' => $this->metricsService->formatCurrency($cashOnHand - $completedPayouts),
                'total' => $this->metricsService->formatCurrency($cashOnHand - $completedPayouts),
            ],
            'liabilities' => [
                'pending_payouts' => $this->metricsService->formatCurrency($pendingPayouts),
                'total' => $this->metricsService->formatCurrency($pendingPayouts),
            ],
            'equity' => [
                'retained_earnings' => $this->metricsService->formatCurrency($netIncome - $pendingPayouts),
                'total' => $this->metricsService->formatCurrency($netIncome - $pendingPayouts),
            ],
        ];
    }

    /**
     * Generate income statement data
     *
     * @param  Carbon  $startDate  Start date
     * @param  Carbon  $endDate  End date
     * @return array Income statement data
     *
     * @see Requirements 13.4
     */
    public function generateIncomeStatement(Carbon $startDate, Carbon $endDate): array
    {
        $revenue = $this->orderRepository->getTotalRevenue($startDate, $endDate);
        $expenses = $this->transactionRepository->getTotalByType('expense', $startDate, $endDate);
        $payouts = $this->transactionRepository->getTotalByType('payout', $startDate, $endDate);
        $refunds = $this->transactionRepository->getTotalByType('refund', $startDate, $endDate);

        $grossProfit = $revenue - $refunds;
        $operatingExpenses = $expenses;
        $netIncome = $grossProfit - $operatingExpenses - $payouts;

        return [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'revenue' => [
                'gross_sales' => $this->metricsService->formatCurrency($revenue),
                'refunds' => $this->metricsService->formatCurrency($refunds),
                'net_sales' => $this->metricsService->formatCurrency($grossProfit),
            ],
            'expenses' => [
                'operating' => $this->metricsService->formatCurrency($operatingExpenses),
                'store_payouts' => $this->metricsService->formatCurrency($payouts),
                'total' => $this->metricsService->formatCurrency($operatingExpenses + $payouts),
            ],
            'net_income' => $this->metricsService->formatCurrency($netIncome),
            'profit_margin' => $revenue > 0 ? round(($netIncome / $revenue) * 100, 2) : 0,
        ];
    }

    /**
     * Generate cash flow report
     *
     * @param  Carbon  $startDate  Start date
     * @param  Carbon  $endDate  End date
     * @return array Cash flow data
     *
     * @see Requirements 13.4
     */
    public function generateCashFlowReport(Carbon $startDate, Carbon $endDate): array
    {
        $revenue = $this->orderRepository->getTotalRevenue($startDate, $endDate);
        $expenses = $this->transactionRepository->getTotalByType('expense', $startDate, $endDate);
        $payouts = Payout::completed()
            ->whereBetween('processed_at', [$startDate, $endDate])
            ->sum('amount');
        $refunds = $this->transactionRepository->getTotalByType('refund', $startDate, $endDate);

        $operatingCashFlow = $revenue - $expenses - $refunds;
        $financingCashFlow = -$payouts; // Outflow
        $netCashFlow = $operatingCashFlow + $financingCashFlow;

        return [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'operating_activities' => [
                'revenue_received' => $this->metricsService->formatCurrency($revenue),
                'expenses_paid' => $this->metricsService->formatCurrency($expenses),
                'refunds_issued' => $this->metricsService->formatCurrency($refunds),
                'net' => $this->metricsService->formatCurrency($operatingCashFlow),
            ],
            'financing_activities' => [
                'store_payouts' => $this->metricsService->formatCurrency($payouts),
                'net' => $this->metricsService->formatCurrency($financingCashFlow),
            ],
            'net_cash_flow' => $this->metricsService->formatCurrency($netCashFlow),
        ];
    }

    /**
     * Get recent transactions
     *
     * @param  int  $limit  Number of transactions to return
     */
    public function getRecentTransactions(int $limit = 10): Collection
    {
        return FinancialTransaction::with(['store', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get transaction summary by type
     *
     * @param  Carbon  $startDate  Start date
     * @param  Carbon  $endDate  End date
     * @return array Summary by transaction type
     */
    public function getTransactionSummary(Carbon $startDate, Carbon $endDate): array
    {
        $types = ['income', 'expense', 'payout', 'refund'];
        $summary = [];

        foreach ($types as $type) {
            $total = $this->transactionRepository->getTotalByType($type, $startDate, $endDate);
            $count = FinancialTransaction::where('type', $type)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $summary[$type] = [
                'total' => $total,
                'formatted' => $this->metricsService->formatCurrency($total),
                'count' => $count,
            ];
        }

        return $summary;
    }
}
