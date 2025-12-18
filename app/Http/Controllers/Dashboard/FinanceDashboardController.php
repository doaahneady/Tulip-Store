<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\ExportService;
use App\Services\Dashboard\FinanceDashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Finance Dashboard Controller
 * 
 * Handles all finance dashboard functionality including:
 * - Dashboard overview with financial KPIs
 * - Transaction management
 * - Payout approval workflow
 * - Financial reports generation
 * 
 * @see Requirements 13.1, 13.2, 13.4
 */
class FinanceDashboardController extends Controller
{
    public function __construct(
        protected FinanceDashboardService $financeService,
        protected AuditService $auditService,
        protected ExportService $exportService
    ) {
        // Apply finance role middleware to all methods
        $this->middleware('dashboard.role:finance,admin');
    }

    /**
     * Display the finance dashboard overview
     * Shows KPI cards, charts, and recent activity
     * 
     * @see Requirements 13.1
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');

        $data = [
            'kpis' => $this->financeService->getKPIMetrics(),
            'revenueChart' => $this->financeService->getRevenueChartData($period),
            'expensesChart' => $this->financeService->getExpensesChartData($period),
            'recentTransactions' => $this->financeService->getRecentTransactions(10),
            'pendingPayouts' => $this->financeService->getPendingPayouts(),
            'transactionSummary' => $this->financeService->getTransactionSummary(
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ),
            'period' => $period,
        ];

        return view('dashboard.finance.index', $data);
    }


    /**
     * Display transactions page
     * Shows paginated list of transactions with filters
     * 
     * @see Requirements 13.2
     */
    public function transactions(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'type' => $request->get('type'),
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $transactions = $this->financeService->getTransactions($filters);

        return view('dashboard.finance.transactions', [
            'transactions' => $transactions,
            'filters' => $filters,
        ]);
    }

    /**
     * Display payouts management page
     * Shows pending and completed payouts to store owners
     * 
     * @see Requirements 13.5
     */
    public function payouts(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $payouts = $this->financeService->getPayouts($filters);
        $pendingPayouts = $this->financeService->getPendingPayouts();

        return view('dashboard.finance.payouts', [
            'payouts' => $payouts,
            'pendingPayouts' => $pendingPayouts,
            'filters' => $filters,
        ]);
    }

    /**
     * Approve a payout
     * Creates an immutable audit record
     * 
     * @see Requirements 13.3
     */
    public function approvePayout(Request $request, int $payoutId)
    {
        $request->validate([
            'payout_id' => 'sometimes|integer|exists:payouts,id',
        ]);

        $payout = $this->financeService->approvePayout($payoutId, Auth::user());

        if (!$payout) {
            return redirect()->back()->with('error', __('Payout not found or already processed.'));
        }

        return redirect()->back()->with('success', __('Payout approved successfully.'));
    }

    /**
     * Reject a payout
     */
    public function rejectPayout(Request $request, int $payoutId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $payout = $this->financeService->rejectPayout(
            $payoutId,
            Auth::user(),
            $request->input('reason')
        );

        if (!$payout) {
            return redirect()->back()->with('error', __('Payout not found or already processed.'));
        }

        return redirect()->back()->with('success', __('Payout rejected.'));
    }

    /**
     * Display financial reports page
     * 
     * @see Requirements 13.4
     */
    public function reports(Request $request)
    {
        $reportType = $request->get('type', 'income_statement');
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date')) 
            : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') 
            ? Carbon::parse($request->get('end_date')) 
            : Carbon::now()->endOfMonth();

        $reportData = [];

        switch ($reportType) {
            case 'balance_sheet':
                $reportData = $this->financeService->generateBalanceSheet($endDate);
                break;
            case 'cash_flow':
                $reportData = $this->financeService->generateCashFlowReport($startDate, $endDate);
                break;
            case 'income_statement':
            default:
                $reportData = $this->financeService->generateIncomeStatement($startDate, $endDate);
                break;
        }

        return view('dashboard.finance.reports', [
            'reportType' => $reportType,
            'reportData' => $reportData,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    /**
     * Export transactions to CSV
     */
    public function exportTransactions(Request $request)
    {
        $filters = [
            'type' => $request->get('type'),
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => 10000, // Get all for export
        ];

        $transactions = $this->financeService->getTransactions($filters);

        $columns = [
            'id' => 'ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'status' => 'Status',
            'reference' => 'Reference',
            'description' => 'Description',
            'created_at' => 'Date',
        ];

        // Log the export action
        $this->auditService->log(
            'export',
            'financial_transaction',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $transactions->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $transactions->getCollection(),
            $columns,
            'transactions_' . date('Y-m-d') . '.csv'
        );
    }

    /**
     * Export payouts to CSV
     */
    public function exportPayouts(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => 10000, // Get all for export
        ];

        $payouts = $this->financeService->getPayouts($filters);

        $columns = [
            'id' => 'ID',
            'store.name' => 'Store',
            'amount' => 'Amount',
            'status' => 'Status',
            'payment_method' => 'Payment Method',
            'payment_reference' => 'Reference',
            'created_at' => 'Created',
            'processed_at' => 'Processed',
        ];

        // Log the export action
        $this->auditService->log(
            'export',
            'payout',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $payouts->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $payouts->getCollection(),
            $columns,
            'payouts_' . date('Y-m-d') . '.csv'
        );
    }
}
