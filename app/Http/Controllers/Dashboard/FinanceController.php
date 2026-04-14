<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ComplianceDocument;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\FinancialTransaction;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Payroll;
use App\Models\PayrollRecord;
use App\Models\SalaryReceipt;
use App\Models\Store;
use App\Models\User;
use App\Services\Dashboard\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Display the Finance dashboard
     */
    public function index(Request $request)
    {
        $metrics = $this->getFinanceMetrics($request);

        return view('dashboards.finance.index', compact('metrics'));
    }

    /**
     * Get finance dashboard metrics
     */
    private function getFinanceMetrics(Request $request)
    {
        $periodDays = (int) ($request->get('period') ?: 30);
        if (! in_array($periodDays, [7, 30, 90], true)) {
            $periodDays = 30;
        }

        $from = $request->get('from');
        $to = $request->get('to');

        $startDate = $from ? \Carbon\Carbon::parse($from)->startOfDay() : now()->subDays($periodDays)->startOfDay();
        $endDate = $to ? \Carbon\Carbon::parse($to)->endOfDay() : now()->endOfDay();

        $seriesDays = min(366, max(1, $startDate->diffInDays($endDate) + 1));

        $revenueService = app(\App\Services\RevenueMetricsService::class);

        $revenueByStore = $revenueService->revenueByStore($startDate, $endDate)->map(function ($r) {
            return (object) [
                'id' => $r->store_id,
                'name' => $r->store_name,
                'total_revenue' => $r->revenue_total,
            ];
        });

        $revenueByUser = collect();
        if (Schema::hasTable('financial_transactions') && Schema::hasColumn('financial_transactions', 'user_id')) {
            $txnRows = \App\Models\FinancialTransaction::query()
                ->where('status', 'completed')
                ->whereIn('type', ['order_payment', 'payment'])
                ->whereNotNull('user_id')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('user_id, SUM(amount) as total_spent')
                ->groupBy('user_id')
                ->orderByDesc('total_spent')
                ->take(10)
                ->get()
                ->keyBy('user_id');

            $users = User::query()
                ->select(['id', 'name', 'email'])
                ->whereIn('id', $txnRows->keys())
                ->get()
                ->keyBy('id');

            $revenueByUser = $txnRows->map(function ($row) use ($users) {
                $u = $users->get($row->user_id);
                return (object) [
                    'id' => $u?->id ?? (int) $row->user_id,
                    'name' => $u?->name,
                    'email' => $u?->email,
                    'total_spent' => (float) $row->total_spent,
                ];
            })->values();
        }

        $revenueByDriver = collect();
        if (Schema::hasTable('financial_transactions') && Schema::hasTable('orders') && Schema::hasColumn('financial_transactions', 'order_id') && Schema::hasColumn('orders', 'assigned_driver_id')) {
            $driverRows = \App\Models\FinancialTransaction::query()
                ->where('financial_transactions.status', 'completed')
                ->whereIn('financial_transactions.type', ['order_payment', 'payment'])
                ->whereBetween('financial_transactions.created_at', [$startDate, $endDate])
                ->join('orders', 'orders.id', '=', 'financial_transactions.order_id')
                ->whereNotNull('orders.assigned_driver_id')
                ->selectRaw('orders.assigned_driver_id as driver_id, SUM(financial_transactions.amount) as total_delivered_value')
                ->groupBy('orders.assigned_driver_id')
                ->orderByDesc('total_delivered_value')
                ->take(10)
                ->get()
                ->keyBy('driver_id');

            // assigned_driver_id on orders references users.id (driver login)
            $drivers = Driver::query()->whereIn('user_id', $driverRows->keys())->with('user')->get()->keyBy('user_id');
            $revenueByDriver = $driverRows->map(function ($row) use ($drivers) {
                $d = $drivers->get($row->driver_id);
                $driverName = $d?->user?->name ?? $d?->name ?? null;
                if (! $driverName) {
                    $driverName = 'Driver user #'.(int) $row->driver_id;
                }

                return (object) [
                    'id' => (int) $row->driver_id,
                    'driver_name' => $driverName,
                    'total_delivered_value' => (float) $row->total_delivered_value,
                ];
            })->values();
        }

        $payPeriod = now()->format('Y-m');
        $moneyByEmployee = Employee::select(['employees.id', 'employees.first_name', 'employees.last_name'])
            ->leftJoin('payroll_records', 'payroll_records.employee_id', '=', 'employees.id')
            ->where('payroll_records.pay_period', $payPeriod)
            ->whereIn('payroll_records.status', ['approved', 'paid'])
            ->selectRaw('COALESCE(SUM(payroll_records.net_pay), 0) as total_pay')
            ->groupBy('employees.id', 'employees.first_name', 'employees.last_name')
            ->orderByDesc('total_pay')
            ->take(10)
            ->get();

        $revenueSeriesMap = $revenueService->revenueSeriesByDay($startDate, $endDate);
        $revenueSeries = [];
        $expenseSeries = [];
        for ($i = 0; $i < $seriesDays; $i++) {
            $d = (clone $startDate)->addDays($i)->format('Y-m-d');
            $revenueSeries[] = [
                'date' => $d,
                'revenue' => (float) ($revenueSeriesMap[$d] ?? 0),
            ];
            $expenseSeries[] = [
                'date' => $d,
                'expenses' => (float) FinancialTransaction::where('type', 'expense')
                    ->where('status', 'completed')
                    ->whereDate('created_at', $d)
                    ->sum('amount'),
            ];
        }

        $pendingRefundsSum = (float) FinancialTransaction::where('type', 'refund')
            ->whereIn('status', ['pending_approval', 'pending'])
            ->sum('amount');

        $pendingRefundsCount = FinancialTransaction::where('type', 'refund')
            ->whereIn('status', ['pending_approval', 'pending'])
            ->count();

        $pendingApprovals = FinancialTransaction::with(['user', 'order', 'store'])
            ->where('status', 'pending_approval')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $pendingPayouts = Payout::with('store')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $revenueService = app(\App\Services\RevenueMetricsService::class);
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $terminalStatuses = (array) config('order_statuses.terminal', ['done']);
        // Delivery fee is effectively collected when the order becomes delivered (before CS marks done).
        $deliveryStatuses = array_values(array_unique(array_merge(['delivered'], $terminalStatuses)));
        $deliverySumCol = Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null;
        $deliveryCollectedToday = 0.0;
        $deliveryCollectedMonth = 0.0;
        $deliveryCollectedTotal = 0.0;
        if ($deliverySumCol) {
            $deliveryCollectedToday = (float) Order::whereIn('status', $deliveryStatuses)
                ->whereBetween('updated_at', [$todayStart, $todayEnd])
                ->sum($deliverySumCol);
            $deliveryCollectedMonth = (float) Order::whereIn('status', $deliveryStatuses)
                ->whereBetween('updated_at', [$monthStart, $monthEnd])
                ->sum($deliverySumCol);
            $deliveryCollectedTotal = (float) Order::whereIn('status', $deliveryStatuses)->sum($deliverySumCol);
        }

        return [
            'todays_revenue' => $revenueService->sumRevenue($todayStart, $todayEnd),
            // Revenue Metrics - Real data
            'total_revenue' => $revenueService->sumRevenue(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'revenue_growth' => $this->getRevenueGrowth(),
            // Delivery revenue (delivery fees collected)
            'todays_delivery' => $deliveryCollectedToday,
            'monthly_delivery' => $deliveryCollectedMonth,
            'total_delivery' => $deliveryCollectedTotal,

            // Transaction Metrics - Real data
            'transactions' => FinancialTransaction::count(),
            'monthly_transactions' => FinancialTransaction::whereMonth('created_at', now()->month)->count(),
            'avg_transaction' => FinancialTransaction::where('status', 'completed')->avg('amount') ?? 0,
            'pending_transactions' => FinancialTransaction::where('status', 'pending')->count(),
            'total_volume' => FinancialTransaction::where('status', 'completed')->sum('amount'),
            'outstanding_payments' => FinancialTransaction::where('status', 'pending')->sum('amount'),
            'pending_refunds' => $pendingRefundsSum,
            'pending_refunds_count' => $pendingRefundsCount,

            // Payout Management - Real data
            'pending_payouts' => Payout::where('status', 'pending')->sum('amount'),
            'payout_requests' => Payout::where('status', 'pending')->count(),
            'approved_payouts' => Payout::where('status', 'approved')->sum('amount'),

            // Profit & Loss - Real data
            'monthly_profit' => $this->getMonthlyProfit(),
            'profit_margin' => $this->getProfitMargin(),
            'total_expenses' => $this->getTotalExpenses(),

            // Tax & Compliance - Real data (would need tax calculation logic)
            'vat_collected' => $this->getVATCollected(),
            'tax_liability' => $this->getTaxLiability(),
            'pending_approvals' => FinancialTransaction::where('status', 'pending_approval')->count(),

            // Commission Tracking - Real data
            'monthly_commission' => FinancialTransaction::where('type', 'commission')
                ->whereMonth('created_at', now()->month)
                ->where('status', 'completed')
                ->sum('amount'),

            // Recent Activity - Real data
            'recent_transactions' => FinancialTransaction::with(['user', 'order'])
                ->latest()
                ->take(5)
                ->get(),
            'pending_approval_items' => FinancialTransaction::where('status', 'pending_approval')
                ->latest()
                ->take(5)
                ->get(),
            'top_earning_stores' => $revenueService->revenueByStore($monthStart, $monthEnd)->take(5),
            // New breakdowns
            'revenue_by_store' => $revenueByStore,
            'revenue_by_user' => $revenueByUser,
            'revenue_by_driver' => $revenueByDriver,
            'money_by_employee' => $moneyByEmployee,
            'payment_method_breakdown' => $this->getPaymentMethodBreakdown(),
            'revenue_series' => $revenueSeries,
            'expense_series' => $expenseSeries,
            'cash_flow_projection' => $this->getCashFlowProjection(30),
            'pending_approvals_list' => $pendingApprovals,
            'pending_payouts_list' => $pendingPayouts,
            'range' => [
                'from' => $startDate->toDateString(),
                'to' => $endDate->toDateString(),
                'days' => $seriesDays,
            ],
        ];
    }

    /**
     * Transaction Management
     */
    public function transactions(Request $request)
    {
        $transactions = FinancialTransaction::with(['user', 'order', 'store'])
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $transactionTypes = FinancialTransaction::distinct()->pluck('type');
        $transactionStatuses = FinancialTransaction::distinct()->pluck('status');

        $stores = Store::select(['id', 'name'])->orderBy('name')->get();
        $employees = Employee::select(['id', 'first_name', 'last_name'])->orderBy('first_name')->orderBy('last_name')->get();

        return view('dashboards.finance.transactions', compact('transactions', 'transactionTypes', 'transactionStatuses', 'stores', 'employees'));
    }

    public function payroll(Request $request)
    {
        $transactions = FinancialTransaction::query()
            ->where('type', 'salary_payment')
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->pay_period, function ($q, $payPeriod) {
                if (Schema::hasColumn('financial_transactions', 'metadata')) {
                    $q->whereJsonContains('metadata->pay_period', $payPeriod);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(30)
            ->withQueryString();

        $employeeIds = [];
        $payrollRecordIds = [];
        foreach ($transactions as $tx) {
            $employeeId = data_get($tx->metadata, 'employee_id');
            if ($employeeId) {
                $employeeIds[] = (int) $employeeId;
            }
            $prId = data_get($tx->metadata, 'payroll_record_id');
            if ($prId) {
                $payrollRecordIds[] = (int) $prId;
            }
        }
        $employeeIds = array_values(array_unique($employeeIds));
        $payrollRecordIds = array_values(array_unique($payrollRecordIds));

        $employees = Employee::with('user')->whereIn('id', $employeeIds)->get()->keyBy('id');
        $payrollRecords = PayrollRecord::whereIn('id', $payrollRecordIds)->get()->keyBy('id');

        $payPeriods = FinancialTransaction::query()
            ->where('type', 'salary_payment')
            ->get()
            ->map(fn ($t) => data_get($t->metadata, 'pay_period'))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        return view('dashboards.finance.payroll', compact('transactions', 'employees', 'payrollRecords', 'payPeriods'));
    }

    public function createTransaction(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'status' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|max:10',
            'description' => 'required|string|max:500',
            'store_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'order_id' => 'nullable|integer',
            'employee_id' => 'nullable|integer',
            'category' => 'nullable|string|max:100',
        ]);

        $allowedTypes = [
            'expense',
            'adjustment',
            'fee',
            'commission',
            'refund',
            'order_payment',
            'payout',
            'payroll',
            'salary_payment',
        ];
        if (! in_array($validated['type'], $allowedTypes, true)) {
            return back()->with('error', 'Invalid transaction type.');
        }

        $txPrefix = match ($validated['type']) {
            'expense' => 'EXP',
            'refund' => 'REF',
            'commission' => 'COM',
            'payout' => 'OUT',
            'fee' => 'FEE',
            'adjustment' => 'ADJ',
            'payroll' => 'PAY',
            'order_payment' => 'PAY',
            default => 'TXN',
        };

        $transaction = FinancialTransaction::create([
            'transaction_id' => $txPrefix.'_'.time().'_'.rand(1000, 9999),
            'type' => $validated['type'],
            'status' => $validated['status'] ?? 'completed',
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'USD',
            'description' => $validated['description'],
            'store_id' => $validated['store_id'] ?? null,
            'user_id' => $validated['user_id'] ?? null,
            'order_id' => $validated['order_id'] ?? null,
            'approval_status' => ($validated['status'] ?? 'completed') === 'pending_approval' ? 'pending' : null,
            'approved_by' => null,
            'approved_at' => null,
        ]);
        if (Schema::hasColumn('financial_transactions', 'metadata')) {
            $transaction->update([
                'metadata' => array_filter([
                    'category' => $validated['category'] ?? null,
                    'employee_id' => $validated['employee_id'] ?? null,
                ], fn ($v) => $v !== null && $v !== ''),
            ]);
        }

        return redirect()->route('dashboard.finance.transactions')->with('success', 'Transaction created: '.$transaction->transaction_id);
    }

    public function exportTransactions(Request $request)
    {
        $format = strtolower((string) $request->get('format', 'csv'));
        if ($format === 'excel' || $format === 'xlsx') {
            $format = 'xls';
        }

        $query = FinancialTransaction::with(['user', 'order', 'store'])
            ->when($request->type, function ($q, $type) {
                $q->where('type', $type);
            })
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($request->date_from, function ($q, $date) {
                $q->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($q, $date) {
                $q->whereDate('created_at', '<=', $date);
            })
            ->when($request->search, function ($q, $search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc');

        $data = $query->limit(5000)->get();

        $columns = [
            'transaction_id' => 'Transaction ID',
            'type' => 'Type',
            'status' => 'Status',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'order.order_number' => 'Order',
            'store.name' => 'Store',
            'user.email' => 'Customer Email',
            'created_at' => 'Created At',
        ];

        $exportService = app(ExportService::class);
        $filename = 'finance_transactions_'.now()->format('Y-m-d_His').'.'.$format;

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $data,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Finance Transactions',
                    'subtitle' => 'Export',
                ]
            );
        }

        if ($format === 'xls') {
            return $exportService->exportToXLS($data, $columns, $filename);
        }

        return $exportService->exportToCSV($data, $columns, $filename);
    }

    public function approvals()
    {
        $pendingTransactions = FinancialTransaction::with(['user', 'order', 'store'])
            ->where('status', 'pending_approval')
            ->orderBy('created_at', 'desc')
            ->paginate(30, ['*'], 'transactions_page');

        $pendingPayouts = Payout::with('store')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(30, ['*'], 'payouts_page');

        return view('dashboards.finance.approvals', compact('pendingTransactions', 'pendingPayouts'));
    }

    public function approveTransaction(FinancialTransaction $transaction, Request $request)
    {
        if ($transaction->status !== 'pending_approval') {
            return back()->with('error', 'Transaction is not pending approval.');
        }

        $transaction->update([
            'status' => 'approved',
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth('employee')->user()->user_id ?? null,
            'approval_notes' => $request->input('notes'),
        ]);

        if ($transaction->type === 'salary_payment') {
            $prId = (int) (data_get($transaction->metadata, 'payroll_record_id') ?? 0);
            if ($prId) {
                PayrollRecord::where('id', $prId)->update(['status' => 'approved']);
            }
        }

        if (Schema::hasTable('dashboard_notifications')) {
            $employeeId = (int) (data_get($transaction->metadata, 'employee_id') ?? 0);
            if (! $employeeId && (int) ($transaction->user_id ?? 0) > 0) {
                $employeeId = (int) (\App\Models\Employee::where('user_id', $transaction->user_id)->value('id') ?? 0);
            }
            if ($employeeId > 0) {
                \App\Models\DashboardNotification::create([
                    'user_type' => \App\Models\Employee::class,
                    'user_id' => $employeeId,
                    'title' => 'تمت الموافقة على طلبك',
                    'message' => ($transaction->type ? 'النوع: '.$transaction->type.' - ' : '').'المبلغ: '.number_format((float) ($transaction->amount ?? 0), 2),
                    'type' => 'success',
                    'is_read' => false,
                    'dashboard_type' => 'finance',
                    'action_url' => url('/dashboard/finance/transactions'),
                ]);
            }
        }

        return back()->with('success', 'Transaction approved.');
    }

    public function rejectTransaction(FinancialTransaction $transaction, Request $request)
    {
        if ($transaction->status !== 'pending_approval') {
            return back()->with('error', 'Transaction is not pending approval.');
        }

        $transaction->update([
            'status' => 'rejected',
            'approval_status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => auth('employee')->user()->user_id ?? null,
            'approval_notes' => $request->input('notes'),
        ]);

        if ($transaction->type === 'salary_payment') {
            $prId = (int) (data_get($transaction->metadata, 'payroll_record_id') ?? 0);
            if ($prId) {
                $pr = PayrollRecord::find($prId);
                if ($pr) {
                    $breakdown = is_array($pr->breakdown) ? $pr->breakdown : [];
                    unset($breakdown['salary_tx_id'], $breakdown['sent_to_finance_at']);
                    $pr->update([
                        'status' => 'draft',
                        'breakdown' => $breakdown,
                    ]);
                }
            }
        }

        if (Schema::hasTable('dashboard_notifications')) {
            $employeeId = (int) (data_get($transaction->metadata, 'employee_id') ?? 0);
            if (! $employeeId && (int) ($transaction->user_id ?? 0) > 0) {
                $employeeId = (int) (\App\Models\Employee::where('user_id', $transaction->user_id)->value('id') ?? 0);
            }
            if ($employeeId > 0) {
                $reason = trim((string) $request->input('notes', ''));
                \App\Models\DashboardNotification::create([
                    'user_type' => \App\Models\Employee::class,
                    'user_id' => $employeeId,
                    'title' => 'تم رفض طلبك',
                    'message' => ($transaction->type ? 'النوع: '.$transaction->type.' - ' : '').'السبب: '.($reason !== '' ? $reason : '—'),
                    'type' => 'error',
                    'is_read' => false,
                    'dashboard_type' => 'finance',
                    'action_url' => url('/dashboard/finance/transactions'),
                ]);
            }
        }

        return back()->with('success', 'Transaction rejected.');
    }

    public function paySalaryForm(FinancialTransaction $transaction)
    {
        if ($transaction->type !== 'salary_payment') {
            abort(404);
        }

        $employeeId = (int) (data_get($transaction->metadata, 'employee_id') ?? 0);
        $payPeriod = (string) (data_get($transaction->metadata, 'pay_period') ?? '');

        $employee = $employeeId ? Employee::with('user')->find($employeeId) : null;

        return view('dashboards.finance.pay-salary', compact('transaction', 'employee', 'payPeriod'));
    }

    public function markSalaryPaid(FinancialTransaction $transaction)
    {
        if ($transaction->type !== 'salary_payment') {
            return back()->with('error', 'Not a salary transaction.');
        }
        if ($transaction->status !== 'approved') {
            return back()->with('error', 'Salary must be approved first.');
        }

        request()->validate([
            'paid_date' => 'required|date|date_format:Y-m-d',
            'signed_name' => 'nullable|string|max:255',
            'signature_data' => 'nullable|string',
        ]);

        $transaction->update([
            'status' => 'completed',
            'is_immutable' => true,
        ]);

        $prId = (int) (data_get($transaction->metadata, 'payroll_record_id') ?? 0);
        if ($prId) {
            $updates = ['status' => 'paid'];
            if (Schema::hasColumn('payroll_records', 'processed_by')) {
                $updates['processed_by'] = auth('employee')->id();
            }
            if (Schema::hasColumn('payroll_records', 'processed_at')) {
                $updates['processed_at'] = now();
            }
            PayrollRecord::where('id', $prId)->update($updates);
        }

        $employeeId = (int) (data_get($transaction->metadata, 'employee_id') ?? 0);
        $payPeriod = (string) (data_get($transaction->metadata, 'pay_period') ?? '');
        $receipt = SalaryReceipt::create([
            'payroll_record_id' => $prId ?: null,
            'financial_transaction_id' => $transaction->id,
            'employee_id' => $employeeId ?: null,
            'pay_period' => $payPeriod ?: null,
            'amount' => (float) ($transaction->amount ?? 0),
            'currency' => (string) ($transaction->currency ?? 'USD'),
            'paid_date' => request()->input('paid_date'),
            'signed_name' => request()->input('signed_name'),
            'signature_data' => request()->input('signature_data'),
            'signed_at' => now(),
            'created_by_employee_id' => auth('employee')->id(),
        ]);

        if (Schema::hasColumn('financial_transactions', 'metadata')) {
            $meta = is_array($transaction->metadata) ? $transaction->metadata : [];
            $meta['salary_receipt_id'] = $receipt->id;
            $transaction->update(['metadata' => $meta]);
        }

        return back()->with('success', 'Salary marked as paid.');
    }

    public function approvePayout(Payout $payout, Request $request)
    {
        if ($payout->status !== 'pending') {
            return back()->with('error', 'Payout is not pending.');
        }

        $payout->update([
            'status' => 'approved',
            'notes' => $request->input('notes'),
            'processed_by' => auth('employee')->user()->user_id ?? null,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Payout approved.');
    }

    public function rejectPayout(Payout $payout, Request $request)
    {
        if ($payout->status !== 'pending') {
            return back()->with('error', 'Payout is not pending.');
        }

        $payout->update([
            'status' => 'rejected',
            'notes' => $request->input('notes'),
            'processed_by' => auth('employee')->user()->user_id ?? null,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Payout rejected.');
    }

    /**
     * Payout Management
     */
    public function payouts(Request $request)
    {
        $payouts = Payout::with(['store', 'requestedBy', 'processedBy'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->store_id, function ($query, $storeId) {
                $query->where('store_id', $storeId);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $payoutStats = [
            'pending_amount' => Payout::where('status', 'pending')->sum('amount'),
            'approved_amount' => Payout::where('status', 'approved')->sum('amount'),
            'processed_amount' => Payout::where('status', 'completed')->sum('amount'),
            'rejected_count' => Payout::where('status', 'rejected')->count(),
        ];

        return view('dashboards.finance.payouts', compact('payouts', 'payoutStats'));
    }

    /**
     * Revenue Analytics
     */
    public function revenue(Request $request)
    {
        $period = $request->get('period', '30d');

        $revenueData = [
            'daily_revenue' => $this->getDailyRevenueData($period),
            'revenue_by_store' => $this->getRevenueByStore($period),
            'revenue_by_category' => $this->getRevenueByCategoryData($period),
            'commission_breakdown' => $this->getCommissionBreakdown($period),
        ];

        $revenueStats = [
            'total_revenue' => $this->getTotalRevenue($period),
            'avg_daily_revenue' => $this->getAverageDailyRevenue($period),
            'growth_rate' => $this->getRevenueGrowth($period),
            'top_revenue_day' => $this->getTopRevenueDay($period),
        ];

        return view('dashboards.finance.revenue', compact('revenueData', 'revenueStats'));
    }

    /**
     * Expense Management
     */
    public function expenses(Request $request)
    {
        $expenses = FinancialTransaction::query()
            ->where('type', 'expense')
            ->with(['store:id,name'])
            ->when($request->category, function ($query, $category) {
                $query->whereJsonContains('metadata->category', $category);
            })
            ->when($request->store_id, function ($query, $storeId) {
                $query->where('store_id', $storeId);
            })
            ->when($request->employee_id, function ($query, $employeeId) {
                if (Schema::hasColumn('financial_transactions', 'metadata')) {
                    $employeeId = (int) $employeeId;
                    $query->where(function ($q) use ($employeeId) {
                        $q->whereJsonContains('metadata->employee_id', $employeeId)
                            ->orWhereJsonContains('metadata->employee_id', (string) $employeeId);
                    });
                }
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $expenseStats = [
            'total_expenses' => $this->getTotalExpenses(),
            'monthly_expenses' => $this->getMonthlyExpenses(),
            'expense_categories' => $this->getExpenseCategories(),
            'avg_expense' => $this->getAverageExpense(),
        ];

        $stores = Store::select(['id', 'name'])->orderBy('name')->get();
        $employees = Employee::select(['id', 'first_name', 'last_name'])->orderBy('first_name')->orderBy('last_name')->get();

        $employeeMap = collect();
        if (Schema::hasColumn('financial_transactions', 'metadata')) {
            $employeeIds = $expenses->getCollection()
                ->map(fn ($tx) => (int) data_get($tx->metadata, 'employee_id'))
                ->filter()
                ->unique()
                ->values();

            if ($employeeIds->isNotEmpty()) {
                $employeeMap = Employee::query()
                    ->whereIn('id', $employeeIds)
                    ->get(['id', 'first_name', 'last_name', 'email'])
                    ->keyBy('id');
            }
        }

        return view('dashboards.finance.expenses', compact('expenses', 'expenseStats', 'stores', 'employees', 'employeeMap'));
    }

    public function createExpense(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|max:10',
            'description' => 'required|string|max:500',
            'category' => 'nullable|string|max:100',
            'store_id' => 'nullable|integer',
            'employee_id' => 'nullable|integer',
        ]);

        $expense = FinancialTransaction::create([
            'transaction_id' => 'EXP_'.time().'_'.rand(1000, 9999),
            'type' => 'expense',
            'status' => 'completed',
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'USD',
            'description' => $validated['description'],
            'store_id' => $validated['store_id'] ?? null,
        ]);
        if (Schema::hasColumn('financial_transactions', 'metadata')) {
            $expense->update([
                'metadata' => array_filter([
                    'category' => $validated['category'] ?? null,
                    'employee_id' => $validated['employee_id'] ?? null,
                ], fn ($v) => $v !== null && $v !== ''),
            ]);
        }

        return redirect()->route('dashboard.finance.expenses')->with('success', 'Expense created: '.$expense->transaction_id);
    }

    /**
     * Financial Reports
     */
    public function reports(Request $request)
    {
        $reportType = $request->get('type', 'pnl');

        $dateFrom = $request->get('date_from') ?: now()->subDays(30)->toDateString();
        $dateTo = $request->get('date_to') ?: now()->toDateString();
        
        $request->validate([
            'date_from' => 'nullable|date|date_format:Y-m-d',
            'date_to' => 'nullable|date|date_format:Y-m-d',
        ]);

        $start = \Carbon\Carbon::parse($dateFrom)->startOfDay();
        $end = \Carbon\Carbon::parse($dateTo)->endOfDay();

        $storeId = $request->get('store_id');
        $employeeId = $request->get('employee_id');
        $groupBy = $request->get('group_by', 'none');

        $revenueQuery = Order::query()->where('payment_status', 'paid')->whereBetween('created_at', [$start, $end]);
        if ($storeId) {
            $revenueQuery->where('store_id', $storeId);
        }
        $revenueTotal = $this->sumOrderTotal($revenueQuery);

        $expenseQuery = FinancialTransaction::query()
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end]);
        if ($storeId) {
            $expenseQuery->where('store_id', $storeId);
        }
        if ($employeeId) {
            $expenseQuery->whereJsonContains('metadata->employee_id', (int) $employeeId);
        }
        $expenseTotal = (float) $expenseQuery->sum('amount');

        $summary = [
            'revenue' => $revenueTotal,
            'expenses' => $expenseTotal,
            'profit' => $revenueTotal - $expenseTotal,
        ];

        $tables = [
            'by_store' => collect(),
            'by_employee' => collect(),
        ];

        $salaryRows = collect();

        if ($groupBy === 'store') {
            $revenueByStore = Order::query()
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('store_id, SUM('.$this->getOrderSumExpression().') as revenue_total')
                ->groupBy('store_id')
                ->pluck('revenue_total', 'store_id');

            $expensesByStore = FinancialTransaction::query()
                ->where('type', 'expense')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->whereNotNull('store_id')
                ->selectRaw('store_id, SUM(amount) as expense_total')
                ->groupBy('store_id')
                ->pluck('expense_total', 'store_id');

            $storesForTable = Store::query()
                ->select(['id', 'name'])
                ->whereIn('id', $revenueByStore->keys()->merge($expensesByStore->keys())->unique()->values())
                ->get()
                ->keyBy('id');

            $rows = $storesForTable->map(function ($store) use ($revenueByStore, $expensesByStore) {
                $revenue = (float) ($revenueByStore[$store->id] ?? 0);
                $expense = (float) ($expensesByStore[$store->id] ?? 0);

                return (object) [
                    'id' => $store->id,
                    'name' => $store->name,
                    'revenue_total' => $revenue,
                    'expense_total' => $expense,
                ];
            })->values();

            $tables['by_store'] = $rows->sortByDesc('revenue_total')->values();
        }

        if ($groupBy === 'employee') {
            $expenseTx = FinancialTransaction::query()
                ->where('type', 'expense')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->get(['amount', 'metadata']);

            $sumByEmployee = [];
            foreach ($expenseTx as $tx) {
                $eid = data_get($tx->metadata, 'employee_id');
                if (! $eid) {
                    continue;
                }
                $eid = (int) $eid;
                $sumByEmployee[$eid] = ($sumByEmployee[$eid] ?? 0) + (float) ($tx->amount ?? 0);
            }

            $employeesForTable = Employee::query()
                ->select(['id', 'first_name', 'last_name'])
                ->whereIn('id', array_keys($sumByEmployee))
                ->get();

            $tables['by_employee'] = $employeesForTable
                ->map(function ($e) use ($sumByEmployee) {
                    return (object) [
                        'id' => $e->id,
                        'first_name' => $e->first_name,
                        'last_name' => $e->last_name,
                        'expense_total' => (float) ($sumByEmployee[(int) $e->id] ?? 0),
                    ];
                })
                ->sortByDesc('expense_total')
                ->values();

            $startMonth = $start->copy()->startOfMonth();
            $endMonth = $end->copy()->startOfMonth();
            $months = [];
            $cursor = $startMonth->copy();
            while ($cursor->lte($endMonth) && count($months) < 36) {
                $months[] = $cursor->format('Y-m');
                $cursor->addMonth();
            }

            if ($months) {
                $salaryTxQuery = FinancialTransaction::query()
                    ->where('type', 'salary_payment')
                    ->whereBetween('created_at', [$start, $end]);

                if ($employeeId && Schema::hasColumn('financial_transactions', 'metadata')) {
                    $salaryTxQuery->whereJsonContains('metadata->employee_id', (int) $employeeId);
                }

                $salaryTx = $salaryTxQuery->get(['amount', 'status', 'metadata', 'created_at']);

                if ($salaryTx->isNotEmpty()) {
                    $priority = [
                        'completed' => 1,
                        'approved' => 2,
                        'pending_approval' => 3,
                        'pending' => 4,
                        'processing' => 5,
                        'failed' => 6,
                        'cancelled' => 7,
                        'rejected' => 8,
                    ];

                    $byKey = [];
                    foreach ($salaryTx as $tx) {
                        $eid = (int) (data_get($tx->metadata, 'employee_id') ?? 0);
                        if (! $eid) {
                            continue;
                        }
                        $month = (string) (data_get($tx->metadata, 'pay_period') ?: ($tx->created_at?->format('Y-m') ?? ''));
                        if ($month === '') {
                            continue;
                        }

                        $key = $eid.'|'.$month;
                        if (! isset($byKey[$key])) {
                            $byKey[$key] = [
                                'employee_id' => $eid,
                                'month' => $month,
                                'net_salary' => 0.0,
                                'status' => (string) ($tx->status ?? ''),
                            ];
                        }

                        $byKey[$key]['net_salary'] += (float) ($tx->amount ?? 0);

                        $current = (string) ($byKey[$key]['status'] ?? '');
                        $incoming = (string) ($tx->status ?? '');
                        if (($priority[$incoming] ?? 999) < ($priority[$current] ?? 999)) {
                            $byKey[$key]['status'] = $incoming;
                        }
                    }

                    $empMap = Employee::query()
                        ->whereIn('id', array_values(array_unique(array_map(fn ($r) => (int) $r['employee_id'], $byKey))))
                        ->get(['id', 'first_name', 'last_name'])
                        ->keyBy('id');

                    $salaryRows = collect(array_values($byKey))
                        ->map(function (array $r) use ($empMap) {
                            $emp = $empMap[(int) $r['employee_id']] ?? null;

                            return (object) [
                                'employee_id' => (int) $r['employee_id'],
                                'first_name' => $emp?->first_name,
                                'last_name' => $emp?->last_name,
                                'month' => $r['month'],
                                'net_salary' => (float) ($r['net_salary'] ?? 0),
                                'status' => $r['status'] ?? null,
                            ];
                        })
                        ->sortByDesc('month')
                        ->values();
                } elseif (Schema::hasTable('payroll')) {
                    $payroll = Payroll::query()
                        ->whereIn('month', $months)
                        ->get(['employee_id', 'month', 'net_salary', 'status']);

                    $empMap = Employee::query()
                        ->whereIn('id', $payroll->pluck('employee_id')->unique()->values())
                        ->get(['id', 'first_name', 'last_name'])
                        ->keyBy('id');

                    $salaryRows = $payroll
                        ->groupBy(function ($p) {
                            return (string) $p->employee_id.'|'.$p->month;
                        })
                        ->map(function ($items) use ($empMap) {
                            $row = $items->first();
                            $emp = $empMap[(int) $row->employee_id] ?? null;

                            return (object) [
                                'employee_id' => $row->employee_id,
                                'first_name' => $emp?->first_name,
                                'last_name' => $emp?->last_name,
                                'month' => $row->month,
                                'net_salary' => (float) $items->sum('net_salary'),
                                'status' => $row->status,
                            ];
                        })
                        ->values()
                        ->sortByDesc('month')
                        ->values();
                }
            }
        }

        $stores = Store::select(['id', 'name'])->orderBy('name')->get();
        $employees = Employee::select(['id', 'first_name', 'last_name'])->orderBy('first_name')->orderBy('last_name')->get();

        return view('dashboards.finance.reports', compact('reportType', 'dateFrom', 'dateTo', 'storeId', 'employeeId', 'groupBy', 'summary', 'tables', 'salaryRows', 'stores', 'employees'));
    }

    private function getOrderSumExpression(): string
    {
        $sumColumn = Schema::hasColumn('orders', 'total_amount')
            ? 'orders.total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'orders.total' : null);

        if ($sumColumn) {
            return $sumColumn;
        }

        $parts = array_filter([
            Schema::hasColumn('orders', 'subtotal') ? 'orders.subtotal' : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'orders.delivery_cost' : null,
            Schema::hasColumn('orders', 'service_fee') ? 'orders.service_fee' : null,
        ]);

        return $parts ? implode(' + ', $parts) : '0';
    }

    /**
     * Tax Management
     */
    public function tax()
    {
        $taxData = [
            'vat_summary' => $this->getVATSummary(),
            'tax_breakdown' => $this->getTaxBreakdown(),
            'compliance_status' => $this->getComplianceStatus(),
            'upcoming_deadlines' => $this->getUpcomingTaxDeadlines(),
        ];

        return view('dashboards.finance.tax', compact('taxData'));
    }

    public function exportTaxReport(Request $request)
    {
        $format = $request->get('format', 'csv');
        $period = $request->get('period', 'Q4-2025');

        $report = $this->getTaxReport($period);

        $columns = [
            'metric' => 'Metric',
            'value' => 'Value',
        ];

        $data = collect([
            ['metric' => 'VAT Collected', 'value' => $report['vat_collected'] ?? 0],
            ['metric' => 'VAT Payable', 'value' => $report['vat_payable'] ?? 0],
            ['metric' => 'Income Tax', 'value' => $report['income_tax'] ?? 0],
            ['metric' => 'Total Tax Liability', 'value' => $report['total_tax_liability'] ?? 0],
        ]);

        $exportService = app(ExportService::class);
        $filename = 'tax_report_'.$period.'_'.now()->format('Y-m-d_His').'.'.$format;
        $path = storage_path('app/compliance/tax_reports/'.$filename);

        $exportService->saveExportToStorage($data, $columns, $format, $path);

        ComplianceDocument::create([
            'doc_type' => 'tax_report',
            'period' => $period,
            'file_url' => 'compliance/tax_reports/'.$filename,
            'filed_by' => auth('employee')->id(),
            'filed_at' => now(),
        ]);

        if ($format === 'pdf') {
            return $exportService->exportToPDF(
                $data,
                $columns,
                'dashboard.exports.pdf-template',
                [
                    'filename' => $filename,
                    'title' => 'Tax Report',
                    'subtitle' => $period,
                ]
            );
        }

        return $exportService->exportToCSV($data, $columns, $filename);
    }

    /**
     * Get total revenue
     */
    private function getTotalRevenue($period = null)
    {
        $query = Order::where('payment_status', 'paid');

        if ($period) {
            $days = $this->getPeriodDays($period);
            $query->where('created_at', '>=', now()->subDays($days));
        }

        return $this->sumOrderTotal($query);
    }

    /**
     * Get monthly revenue
     */
    private function getMonthlyRevenue()
    {
        return $this->sumOrderTotal(
            Order::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
        );
    }

    /**
     * Get revenue growth rate
     */
    private function getRevenueGrowth($period = null)
    {
        $currentMonth = $this->getMonthlyRevenue();
        $lastMonth = $this->sumOrderTotal(
            Order::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->subMonth()->month)
        );

        if ($lastMonth == 0) {
            return 100;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Get average transaction value
     */
    private function getAverageTransactionValue()
    {
        $query = Order::where('payment_status', 'paid');
        if (Schema::hasColumn('orders', 'total_amount')) {
            return round($query->avg('total_amount'), 2);
        }
        if (Schema::hasColumn('orders', 'total')) {
            return round($query->avg('total'), 2);
        }
        $expr = [];
        if (Schema::hasColumn('orders', 'subtotal')) {
            $expr[] = 'subtotal';
        }
        if (Schema::hasColumn('orders', 'delivery_cost')) {
            $expr[] = 'delivery_cost';
        }
        if (Schema::hasColumn('orders', 'service_fee')) {
            $expr[] = 'service_fee';
        }
        if (! $expr) {
            return 0;
        }

        return (float) $query->selectRaw('AVG('.implode(' + ', $expr).') as avg_total')->value('avg_total') ?? 0;
    }

    /**
     * Get monthly profit
     */
    private function getMonthlyProfit()
    {
        $revenue = $this->getMonthlyRevenue();
        $expenses = $this->getMonthlyExpenses();

        return $revenue - $expenses;
    }

    /**
     * Get profit margin
     */
    private function getProfitMargin()
    {
        $revenue = $this->getMonthlyRevenue();
        $profit = $this->getMonthlyProfit();

        return $revenue > 0 ? round(($profit / $revenue) * 100, 1) : 0;
    }

    /**
     * Get total expenses
     */
    private function getTotalExpenses()
    {
        return FinancialTransaction::where('type', 'expense')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get monthly expenses
     */
    private function getMonthlyExpenses()
    {
        return FinancialTransaction::where('type', 'expense')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    /**
     * Get VAT collected
     */
    private function getVATCollected()
    {
        $query = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month);

        if (Schema::hasColumn('orders', 'tax_amount')) {
            return (float) $query->sum('tax_amount');
        }

        $sumColumn = Schema::hasColumn('orders', 'total_amount')
            ? 'total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'total' : null);
        $sumExpr = $sumColumn ? $sumColumn : implode(' + ', array_filter([
            Schema::hasColumn('orders', 'subtotal') ? 'subtotal' : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null,
            Schema::hasColumn('orders', 'service_fee') ? 'service_fee' : null,
        ]));

        if (! $sumExpr) {
            return 0;
        }

        $rate = \App\Models\SystemSetting::get('vat_rate', 15);
        $rateNum = is_numeric($rate) ? (float) $rate : 15;

        $base = (float) ($query->selectRaw('SUM('.$sumExpr.') as s')->value('s') ?? 0);

        return $base * ($rateNum / 100);
    }

    /**
     * Get tax liability
     */
    private function getTaxLiability()
    {
        $profit = $this->getMonthlyProfit();

        return $profit * 0.15;
    }

    /**
     * Get pending approvals count
     */
    private function getPendingApprovals()
    {
        return FinancialTransaction::where('approval_status', 'pending')->count() +
               Payout::where('status', 'pending')->count();
    }

    /**
     * Get average commission rate
     */
    private function getAverageCommissionRate()
    {
        return Store::avg('commission_rate') * 100;
    }

    /**
     * Get monthly commission
     */
    private function getMonthlyCommission()
    {
        return FinancialTransaction::where('type', 'commission')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    /**
     * Get recent transactions
     */
    private function getRecentTransactions()
    {
        return FinancialTransaction::with(['user', 'order', 'store'])
            ->latest()
            ->take(10)
            ->get();
    }

    /**
     * Get pending approval items
     */
    private function getPendingApprovalItems()
    {
        $transactions = FinancialTransaction::where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $payouts = Payout::where('status', 'pending')
            ->with('store')
            ->latest()
            ->take(5)
            ->get();

        return $transactions->merge($payouts)->sortByDesc('created_at');
    }

    /**
     * Get top earning stores
     */
    private function getTopEarningStores()
    {
        return Store::withSum(['orders' => function ($query) {
            $query->where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month);
        }], 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get daily revenue data
     */
    private function getDailyRevenueData($period)
    {
        $days = $this->getPeriodDays($period);

        $query = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days));
        $sumColumn = Schema::hasColumn('orders', 'total_amount')
            ? 'total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'total' : null);
        $sumExpr = $sumColumn ? $sumColumn : implode(' + ', array_filter([
            Schema::hasColumn('orders', 'subtotal') ? 'subtotal' : null,
            Schema::hasColumn('orders', 'delivery_cost') ? 'delivery_cost' : null,
            Schema::hasColumn('orders', 'service_fee') ? 'service_fee' : null,
        ]));

        return $query
            ->selectRaw('DATE(created_at) as date, SUM('.$sumExpr.') as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get revenue by store
     */
    private function getRevenueByStore($period)
    {
        $days = $this->getPeriodDays($period);

        $sumColumn = Schema::hasColumn('orders', 'total_amount') ? 'total_amount' : 'total';

        return Store::withSum(['orders' => function ($query) use ($days) {
            $query->where('payment_status', 'paid')
                ->where('created_at', '>=', now()->subDays($days));
        }], $sumColumn)
            ->orderBy($sumColumn === 'total_amount' ? 'orders_sum_total_amount' : 'orders_sum_total', 'desc')
            ->take(10)
            ->get();
    }

    private function sumOrderTotal($query)
    {
        if (Schema::hasColumn('orders', 'total_amount')) {
            return (float) $query->sum('total_amount');
        }
        if (Schema::hasColumn('orders', 'total')) {
            return (float) $query->sum('total');
        }
        $parts = [];
        if (Schema::hasColumn('orders', 'subtotal')) {
            $parts[] = 'subtotal';
        }
        if (Schema::hasColumn('orders', 'delivery_cost')) {
            $parts[] = 'delivery_cost';
        }
        if (Schema::hasColumn('orders', 'service_fee')) {
            $parts[] = 'service_fee';
        }
        if (! $parts) {
            return 0;
        }

        return (float) $query->selectRaw('SUM('.implode(' + ', $parts).') as agg')->value('agg') ?? 0;
    }

    /**
     * Get revenue by category data
     */
    private function getRevenueByCategoryData($period)
    {
        // Mock data - would need to join with products and categories
        return [
            'Electronics' => 45000,
            'Fashion' => 32000,
            'Home & Garden' => 28000,
            'Sports' => 18000,
            'Books' => 12000,
        ];
    }

    /**
     * Get commission breakdown
     */
    private function getCommissionBreakdown($period)
    {
        $days = $this->getPeriodDays($period);

        return FinancialTransaction::where('type', 'commission')
            ->where('created_at', '>=', now()->subDays($days))
            ->with('store')
            ->selectRaw('store_id, SUM(amount) as total_commission')
            ->groupBy('store_id')
            ->orderBy('total_commission', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Get average daily revenue
     */
    private function getAverageDailyRevenue($period)
    {
        $days = $this->getPeriodDays($period);
        $totalRevenue = $this->getTotalRevenue($period);

        return $days > 0 ? round($totalRevenue / $days, 2) : 0;
    }

    /**
     * Get top revenue day
     */
    private function getTopRevenueDay($period)
    {
        $days = $this->getPeriodDays($period);

        $sumExpr = $this->getOrderSumExpression();

        $row = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM('.$sumExpr.') as revenue')
            ->groupBy('date')
            ->orderByDesc('revenue')
            ->first();

        if (! $row) {
            return '-';
        }

        $date = (string) ($row->date ?? '-');
        $rev = number_format((float) ($row->revenue ?? 0), 2);

        return $date.' ($'.$rev.')';
    }

    /**
     * Get expense categories
     */
    private function getExpenseCategories()
    {
        // Mock data - would extract from metadata
        return [
            'Salaries' => 45000,
            'Infrastructure' => 12000,
            'Marketing' => 8500,
            'Office Supplies' => 3200,
            'Utilities' => 2800,
        ];
    }

    /**
     * Get average expense
     */
    private function getAverageExpense()
    {
        return FinancialTransaction::where('type', 'expense')
            ->where('status', 'completed')
            ->avg('amount');
    }

    private function getPaymentMethodBreakdown()
    {
        $methods = ['card', 'cash', 'mobile_wallet'];
        $result = [];
        foreach ($methods as $method) {
            if (Schema::hasColumn('orders', 'payment_method')) {
                $result[$method] = $this->sumOrderTotal(
                    Order::where('payment_status', 'paid')->where('payment_method', $method)
                );
            } else {
                $result[$method] = 0;
            }
        }

        return $result;
    }

    private function getDailyExpensesData($period)
    {
        $days = $this->getPeriodDays($period);

        return FinancialTransaction::where('type', 'expense')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as expenses')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getCashFlowProjection($days)
    {
        $revData = $this->getDailyRevenueData('30d');
        $expData = $this->getDailyExpensesData('30d');
        $revMap = collect($revData)->keyBy('date');
        $expMap = collect($expData)->keyBy('date');
        $dates = [];
        for ($i = 1; $i <= 30; $i++) {
            $d = now()->subDays(30 - $i)->toDateString();
            $dates[] = $d;
        }
        $netSum = 0;
        $count = 0;
        foreach ($dates as $d) {
            $r = (float) optional($revMap->get($d))->revenue ?? 0;
            $e = (float) optional($expMap->get($d))->expenses ?? 0;
            $netSum += ($r - $e);
            $count++;
        }
        $avgNet = $count > 0 ? $netSum / $count : 0;
        $projection = [];
        for ($i = 1; $i <= $days; $i++) {
            $projection[] = [
                'date' => now()->addDays($i)->toDateString(),
                'net' => round($avgNet, 2),
            ];
        }

        return $projection;
    }

    /**
     * Get P&L report
     */
    private function getProfitLossReport($period)
    {
        return [
            'revenue' => $this->getTotalRevenue(),
            'expenses' => $this->getTotalExpenses(),
            'gross_profit' => $this->getTotalRevenue() - $this->getTotalExpenses(),
            'commission_income' => $this->getMonthlyCommission(),
            'net_profit' => $this->getMonthlyProfit(),
        ];
    }

    /**
     * Get balance sheet report
     */
    private function getBalanceSheetReport($period)
    {
        // Mock data - would calculate actual assets, liabilities, equity
        return [
            'assets' => 250000,
            'liabilities' => 85000,
            'equity' => 165000,
        ];
    }

    /**
     * Get cash flow report
     */
    private function getCashFlowReport($period)
    {
        return [
            'operating_cash_flow' => 45000,
            'investing_cash_flow' => -12000,
            'financing_cash_flow' => 8000,
            'net_cash_flow' => 41000,
        ];
    }

    /**
     * Get tax report
     */
    private function getTaxReport($period)
    {
        return [
            'vat_collected' => $this->getVATCollected(),
            'vat_payable' => $this->getTaxLiability(),
            'income_tax' => 15000,
            'total_tax_liability' => $this->getTaxLiability() + 15000,
        ];
    }

    /**
     * Get VAT summary
     */
    private function getVATSummary()
    {
        return [
            'collected' => $this->getVATCollected(),
            'payable' => $this->getTaxLiability(),
            'rate' => 15, // percentage
            'filing_due' => now()->addDays(15)->format('Y-m-d'),
        ];
    }

    /**
     * Get tax breakdown
     */
    private function getTaxBreakdown()
    {
        return [
            'sales_tax' => 28500,
            'service_tax' => 9500,
            'platform_fee_tax' => 4750,
        ];
    }

    /**
     * Get compliance status
     */
    private function getComplianceStatus()
    {
        return [
            'monthly_vat_filing' => 'completed',
            'quarterly_report' => 'completed',
            'annual_audit' => 'pending',
        ];
    }

    /**
     * Get upcoming tax deadlines
     */
    private function getUpcomingTaxDeadlines()
    {
        return [
            ['type' => 'VAT Filing', 'due_date' => now()->addDays(15)->format('Y-m-d')],
            ['type' => 'Quarterly Report', 'due_date' => now()->addDays(45)->format('Y-m-d')],
            ['type' => 'Annual Audit', 'due_date' => now()->addDays(90)->format('Y-m-d')],
        ];
    }

    /**
     * Convert period string to days
     */
    private function getPeriodDays($period)
    {
        switch ($period) {
            case '7d': return 7;
            case '30d': return 30;
            case '90d': return 90;
            case '1y': return 365;
            default: return 30;
        }
    }

    /**
     * Budget Management
     */
    public function getBudgets(Request $request)
    {
        $budgets = \App\Models\Budget::with('creator')
            ->when($request->category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->when($request->period, function ($query, $period) {
                $query->where('period', $period);
            })
            ->orderBy('period', 'desc')
            ->paginate(20);

        return response()->json($budgets);
    }

    public function createBudget(Request $request)
    {
        $request->validate([
            'budget_name' => 'required|string',
            'category' => 'required|string',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'period' => 'required|string',
            'budgeted_amount' => 'required|numeric|min:0',
        ]);

        $budget = \App\Models\Budget::create($request->only([
            'budget_name', 'category', 'period_type', 'period',
            'budgeted_amount', 'notes',
        ]) + ['created_by' => auth()->id()]);

        // Calculate actual amount and variance
        $budget->calculateVariance();

        return response()->json(['success' => true, 'budget' => $budget]);
    }

    /**
     * Profit & Loss Statements
     */
    public function getProfitLossStatements(Request $request)
    {
        $period = $request->get('period', now()->format('Y-m'));
        $periodType = $request->get('period_type', 'monthly');

        $statement = \App\Models\ProfitLossStatement::where('period_type', $periodType)
            ->where('period', $period)
            ->first();

        if (! $statement) {
            // Generate statement if it doesn't exist
            $statement = $this->generateProfitLossStatement($periodType, $period);
        }

        return response()->json($statement);
    }

    private function generateProfitLossStatement($periodType, $period)
    {
        // Calculate based on period
        $query = Order::where('payment_status', 'paid');

        if ($periodType === 'monthly') {
            $query->whereYear('created_at', substr($period, 0, 4))
                ->whereMonth('created_at', substr($period, 5, 2));
        }

        $sumColumn = Schema::hasColumn('orders', 'total_amount')
            ? 'total_amount'
            : (Schema::hasColumn('orders', 'total') ? 'total' : null);
        $totalRevenue = $sumColumn ? $query->sum($sumColumn) : 0;
        $costOfGoodsSold = $this->calculateCOGS($periodType, $period);
        $grossProfit = $totalRevenue - $costOfGoodsSold;
        $operatingExpenses = $this->getTotalExpenses();
        $operatingProfit = $grossProfit - $operatingExpenses;
        $taxExpense = $operatingProfit * 0.15;
        $netProfitAfterTax = $operatingProfit - $taxExpense;

        $statement = \App\Models\ProfitLossStatement::create([
            'period_type' => $periodType,
            'period' => $period,
            'total_revenue' => $totalRevenue,
            'cost_of_goods_sold' => $costOfGoodsSold,
            'gross_profit' => $grossProfit,
            'operating_expenses' => $operatingExpenses,
            'operating_profit' => $operatingProfit,
            'tax_expense' => $taxExpense,
            'net_profit_after_tax' => $netProfitAfterTax,
        ]);

        $statement->calculateProfitLoss();

        return $statement;
    }

    private function calculateCOGS(string $periodType, string $period): float
    {
        if (! Schema::hasTable('order_items') || ! Schema::hasTable('products') || ! Schema::hasTable('orders')) {
            // Fallback if tables are missing
            $ordersQuery = Order::where('payment_status', 'paid');
            if ($periodType === 'monthly') {
                $ordersQuery->whereYear('created_at', substr($period, 0, 4))
                    ->whereMonth('created_at', substr($period, 5, 2));
            }
            $sumColumn = Schema::hasColumn('orders', 'total_amount')
                ? 'total_amount'
                : (Schema::hasColumn('orders', 'total') ? 'total' : null);
            $totalRevenue = $sumColumn ? $ordersQuery->sum($sumColumn) : 0;

            return round($totalRevenue * 0.60, 2);
        }

        $ordersDateFilter = '';
        $bindings = [];
        if ($periodType === 'monthly') {
            $year = substr($period, 0, 4);
            $month = substr($period, 5, 2);
            $ordersDateFilter = 'AND YEAR(orders.created_at) = ? AND MONTH(orders.created_at) = ?';
            $bindings[] = $year;
            $bindings[] = $month;
        }

        $sql = "
            SELECT SUM(order_items.quantity * COALESCE(products.cost_price, 0)) AS cogs
            FROM order_items
            INNER JOIN orders ON order_items.order_id = orders.id
            INNER JOIN products ON order_items.product_id = products.id
            WHERE orders.payment_status = 'paid' {$ordersDateFilter}
        ";

        $result = DB::selectOne($sql, $bindings);

        return round((float) ($result?->cogs ?? 0), 2);
    }

    /**
     * Cash Flow Tracking
     */
    public function getCashFlow(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = now()->subDays($days);

        $cashFlow = \App\Models\CashFlowRecord::where('transaction_date', '>=', $startDate)
            ->orderBy('transaction_date', 'desc')
            ->get();

        $summary = [
            'total_inflow' => $cashFlow->where('flow_type', 'inflow')->sum('amount'),
            'total_outflow' => $cashFlow->where('flow_type', 'outflow')->sum('amount'),
            'net_cash_flow' => $cashFlow->where('flow_type', 'inflow')->sum('amount') -
                              $cashFlow->where('flow_type', 'outflow')->sum('amount'),
            'by_category' => $cashFlow->groupBy('category')
                ->map(function ($records) {
                    return [
                        'inflow' => $records->where('flow_type', 'inflow')->sum('amount'),
                        'outflow' => $records->where('flow_type', 'outflow')->sum('amount'),
                    ];
                }),
        ];

        return response()->json([
            'cash_flow' => $cashFlow,
            'summary' => $summary,
        ]);
    }

    /**
     * Financial Forecasting
     */
    public function getForecasts(Request $request)
    {
        $forecasts = \App\Models\FinancialForecast::with('creator')
            ->when($request->forecast_type, function ($query, $type) {
                $query->where('forecast_type', $type);
            })
            ->when($request->period, function ($query, $period) {
                $query->where('period', $period);
            })
            ->orderBy('period', 'desc')
            ->paginate(20);

        return response()->json($forecasts);
    }

    public function createForecast(Request $request)
    {
        $request->validate([
            'forecast_type' => 'required|in:revenue,expense,profit',
            'period_type' => 'required|in:monthly,quarterly,yearly',
            'period' => 'required|string',
            'forecasted_amount' => 'required|numeric',
            'confidence_level' => 'required|numeric|min:0|max:100',
            'method' => 'required|string',
        ]);

        $forecast = \App\Models\FinancialForecast::create($request->only([
            'forecast_type', 'period_type', 'period', 'forecasted_amount',
            'confidence_level', 'assumptions', 'method',
        ]) + ['created_by' => auth()->id()]);

        return response()->json(['success' => true, 'forecast' => $forecast]);
    }

    /**
     * Get dashboard metrics API
     */
    public function getDashboardMetrics()
    {
        return response()->json($this->getFinanceMetrics(request()));
    }

    private function orderMoney(Order $order): float
    {
        return (float) ($order->total_amount ?? $order->total ?? 0);
    }

    /**
     * Finance: drivers cash collection after they mark orders delivered.
     * Shows pending delivered orders grouped by driver with a "Complete" action.
     */
    public function driverDeliveries(Request $request)
    {
        $validatedFilters = $request->validate([
            'driver_name' => 'nullable|string|max:255',
            'date_from' => ['nullable', 'date_format:Y-m-d', 'regex:/^\d{4}-\d{2}-\d{2}$/'],
            'date_to' => ['nullable', 'date_format:Y-m-d', 'regex:/^\d{4}-\d{2}-\d{2}$/'],
        ]);

        $driverUserIdFilter = $request->query('driver_id');
        $driverUserIdFilter = is_numeric($driverUserIdFilter) ? (int) $driverUserIdFilter : null;
        $historyDriverNameFilter = trim((string) ($validatedFilters['driver_name'] ?? ''));
        $historyDateFrom = $validatedFilters['date_from'] ?? null;
        $historyDateTo = $validatedFilters['date_to'] ?? null;

        $pendingOrdersQuery = Order::query()
            ->where('status', 'delivered')
            ->whereNotNull('assigned_driver_id')
            ->with([
                'deliveryAssignments' => function ($q) {
                    $q->orderByDesc('id');
                },
            ])
            ->orderByDesc('updated_at');

        if ($driverUserIdFilter !== null) {
            $pendingOrdersQuery->where('assigned_driver_id', $driverUserIdFilter);
        }

        // Keep it bounded; finance can filter by driver via query string.
        $pendingOrders = $pendingOrdersQuery->limit(400)->get();

        $driverIds = $pendingOrders->pluck('assigned_driver_id')->unique()->values();
        $driversByUserId = Driver::query()
            ->with('user')
            ->whereIn('user_id', $driverIds)
            ->get()
            ->keyBy('user_id');

        $pendingDrivers = $pendingOrders
            ->groupBy('assigned_driver_id')
            ->map(function ($orders, $driverUserId) use ($driversByUserId) {
                $driver = $driversByUserId->get($driverUserId);
                $driverName = $driver?->user?->name ?? $driver?->name ?? ('Driver user #'.(int) $driverUserId);

                $cashDue = (float) $orders
                    // المطلوب كاش = مجموع إجمالي الطلبات التي طريقة دفعها cash (بدون الاعتماد على payment_status)
                    ->filter(fn ($o) => ($o->payment_method ?? null) === 'cash')
                    ->sum(fn ($o) => $this->orderMoney($o));

                $ordersPayload = $orders->map(function ($o) {
                    $assignment = $o->deliveryAssignments->first();
                    $deliveredAt = $assignment?->delivered_at;

                    return (object) [
                        'id' => (int) $o->id,
                        'order_number' => $o->order_number ?? '#'.$o->id,
                        'recipient_name' => $o->recipient_name ?? '—',
                        'payment_method' => $o->payment_method ?? '—',
                        'payment_status' => $o->payment_status ?? '—',
                        'total' => $this->orderMoney($o),
                        'delivered_at' => $deliveredAt,
                    ];
                })->values();

                return (object) [
                    'driverUserId' => (int) $driverUserId,
                    'driverName' => $driverName,
                    'cashDue' => $cashDue,
                    'orders' => $ordersPayload,
                ];
            })
            ->values();

        // History: completed orders by drivers
        $historyOrdersQuery = Order::query()
            ->where('status', 'done')
            ->whereNotNull('assigned_driver_id')
            ->with([
                'deliveryAssignments' => function ($q) {
                    $q->orderByDesc('id');
                },
            ])
            ->orderByDesc('updated_at');

        if ($driverUserIdFilter !== null) {
            $historyOrdersQuery->where('assigned_driver_id', $driverUserIdFilter);
        }

        if ($historyDriverNameFilter !== '') {
            $matchingDriverUserIds = Driver::query()
                ->whereHas('user', function ($q) use ($historyDriverNameFilter) {
                    $q->where('name', 'like', "%{$historyDriverNameFilter}%")
                        ->orWhere('username', 'like', "%{$historyDriverNameFilter}%");
                })
                ->pluck('user_id')
                ->filter()
                ->values();

            if ($matchingDriverUserIds->isEmpty()) {
                $historyOrdersQuery->whereRaw('1 = 0');
            } else {
                $historyOrdersQuery->whereIn('assigned_driver_id', $matchingDriverUserIds);
            }
        }

        if ($historyDateFrom) {
            $historyOrdersQuery->where(function ($q) use ($historyDateFrom) {
                $q->whereDate('completed_at', '>=', $historyDateFrom)
                    ->orWhere(function ($qq) use ($historyDateFrom) {
                        $qq->whereNull('completed_at')
                            ->whereDate('updated_at', '>=', $historyDateFrom);
                    });
            });
        }

        if ($historyDateTo) {
            $historyOrdersQuery->where(function ($q) use ($historyDateTo) {
                $q->whereDate('completed_at', '<=', $historyDateTo)
                    ->orWhere(function ($qq) use ($historyDateTo) {
                        $qq->whereNull('completed_at')
                            ->whereDate('updated_at', '<=', $historyDateTo);
                    });
            });
        }

        $historyOrders = $historyOrdersQuery->limit(100)->get();

        $historyDriverIds = $historyOrders->pluck('assigned_driver_id')->unique()->values();
        $historyDriversByUserId = Driver::query()
            ->with('user')
            ->whereIn('user_id', $historyDriverIds)
            ->get()
            ->keyBy('user_id');

        $historyOrders->transform(function (Order $o) use ($historyDriversByUserId) {
            $driver = $historyDriversByUserId->get($o->assigned_driver_id);
            $o->driver_name = $driver?->user?->name ?? $driver?->name ?? ('Driver user #'.(int) $o->assigned_driver_id);

            $assignment = $o->deliveryAssignments->first();
            $o->delivered_at = $assignment?->delivered_at;

            // `completed_at` may not be cast on the model, so parse it safely for the view.
            $o->completed_at_display = null;
            if (! empty($o->completed_at)) {
                try {
                    $o->completed_at_display = \Carbon\Carbon::parse($o->completed_at);
                } catch (\Throwable $e) {
                    $o->completed_at_display = null;
                }
            }

            return $o;
        });

        return view('dashboards.finance.driver-deliveries', compact(
            'pendingDrivers',
            'historyOrders',
            'driverUserIdFilter',
            'historyDriverNameFilter',
            'historyDateFrom',
            'historyDateTo'
        ));
    }

    /**
     * Finance action: mark driver's delivered orders as paid+done.
     * This is the "Complete" button replacing CS completion for delivered orders.
     */
    public function completeDriverDeliveries(int $driverUserId, Request $request)
    {
        $employee = auth('employee')->user();

        $orders = Order::query()
            ->where('status', 'delivered')
            ->where('assigned_driver_id', $driverUserId)
            ->with(['deliveryAssignments'])
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'لا توجد طلبات تسليم بانتظار اعتماد السائق.');
        }

        DB::transaction(function () use ($orders, $employee) {
            foreach ($orders as $order) {
                if (($order->payment_status ?? null) !== 'paid') {
                    $order->update(['payment_status' => 'paid']);
                }

                if (($order->status ?? null) !== 'done') {
                    \App\Services\StatusTransitionService::transition($order->refresh(), 'status', 'done', $employee?->id);
                }
            }
        });

        return redirect()
            ->route('dashboard.finance.driver-deliveries.index', ['driver_id' => $driverUserId])
            ->with('success', 'تم اعتماد تسليمات السائق وتحديث الحالة والدفع.');
    }
}
