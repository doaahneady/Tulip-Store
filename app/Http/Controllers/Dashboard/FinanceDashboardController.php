<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\FinanceDashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FinanceDashboardController extends Controller
{
    public function __construct(
        protected FinanceDashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        return Inertia::render('Dashboard/Finance', [
            'metrics' => $this->dashboardService->getKPIMetrics(),
            'revenueData' => $this->dashboardService->getRevenueChartData($request->get('period', 'month')),
            'recentTransactions' => $this->dashboardService->getRecentTransactions(10),
        ]);
    }
}
