<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialTransaction;
use App\Models\Payout;
use App\Models\Order;
use App\Models\PayrollRecord;
use App\Models\Store;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:finance_manager,accountant,super_admin']);
    }

    /**
     * Display the Finance dashboard
     */
    public function index()
    {
        $metrics = $this->getFinanceMetrics();
        
        return view('dashboards.finance.index', compact('metrics'));
    }

    /**
     * Get finance dashboard metrics
     */
    private function getFinanceMetrics()
    {
        return [
            // Revenue Metrics - Using mock data for now
            'total_revenue' => 2847500,
            'monthly_revenue' => 285000,
            'revenue_growth' => 18.5,
            
            // Transaction Metrics
            'transactions' => 1247,
            'monthly_transactions' => 156,
            'avg_transaction' => 2283,
            'pending_transactions' => 23,
            'total_volume' => 2847500,
            
            // Payout Management
            'pending_payouts' => 125000,
            'payout_requests' => 23,
            'approved_payouts' => 85000,
            
            // Profit & Loss
            'monthly_profit' => 125000,
            'profit_margin' => 15.8,
            'total_expenses' => 45000,
            
            // Tax & Compliance
            'vat_collected' => 35000,
            'tax_liability' => 28000,
            'pending_approvals' => 12,
            
            // Commission Tracking
            'commission_rate' => 5.5,
            'monthly_commission' => 15675,
            
            // Recent Activity
            'recent_transactions' => [],
            'pending_approval_items' => [],
            'top_earning_stores' => [],
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

        return view('dashboards.finance.transactions', compact('transactions', 'transactionTypes', 'transactionStatuses'));
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
        $expenses = FinancialTransaction::where('type', 'expense')
            ->when($request->category, function ($query, $category) {
                $query->whereJsonContains('metadata->category', $category);
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

        return view('dashboards.finance.expenses', compact('expenses', 'expenseStats'));
    }

    /**
     * Financial Reports
     */
    public function reports(Request $request)
    {
        $reportType = $request->get('type', 'pnl');
        $period = $request->get('period', 'monthly');
        
        $reports = [
            'profit_loss' => $this->getProfitLossReport($period),
            'balance_sheet' => $this->getBalanceSheetReport($period),
            'cash_flow' => $this->getCashFlowReport($period),
            'tax_report' => $this->getTaxReport($period),
        ];

        return view('dashboards.finance.reports', compact('reports', 'reportType', 'period'));
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
        
        return $query->sum('total');
    }

    /**
     * Get monthly revenue
     */
    private function getMonthlyRevenue()
    {
        return Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('total');
    }

    /**
     * Get revenue growth rate
     */
    private function getRevenueGrowth($period = null)
    {
        $currentMonth = $this->getMonthlyRevenue();
        $lastMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('total');
        
        if ($lastMonth == 0) return 100;
        
        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Get average transaction value
     */
    private function getAverageTransactionValue()
    {
        return round(Order::where('payment_status', 'paid')->avg('total_amount'), 2);
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
        return Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('tax_amount');
    }

    /**
     * Get tax liability
     */
    private function getTaxLiability()
    {
        // Mock calculation - would be based on actual tax rules
        return $this->getVATCollected() * 0.85;
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
        
        return Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
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
        
        return Store::withSum(['orders' => function ($query) use ($days) {
                $query->where('payment_status', 'paid')
                      ->where('created_at', '>=', now()->subDays($days));
            }], 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->take(10)
            ->get();
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
        
        return Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('revenue', 'desc')
            ->first();
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
}