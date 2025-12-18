<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\ExportService;
use App\Services\Dashboard\ITDashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * IT Dashboard Controller
 * 
 * Handles all IT dashboard functionality including:
 * - Dashboard overview with system metrics
 * - System logs viewing with filters
 * - Security alerts management
 * - Performance metrics and database health
 * 
 * @see Requirements 8.1, 8.2, 8.4, 8.5
 */
class ITDashboardController extends Controller
{
    public function __construct(
        protected ITDashboardService $itService,
        protected AuditService $auditService,
        protected ExportService $exportService
    ) {
        // Apply IT role middleware to all methods
        $this->middleware('dashboard.role:it,admin');
    }

    /**
     * Display the IT dashboard overview
     * Shows system metrics, service status, and recent alerts
     * 
     * @see Requirements 8.1
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'day');

        $data = [
            'kpis' => $this->itService->getKPIMetrics(),
            'services' => $this->itService->getServices(),
            'recentLogs' => $this->itService->getRecentLogs(10),
            'cpuChart' => $this->itService->getPerformanceChartData('cpu', $period),
            'memoryChart' => $this->itService->getPerformanceChartData('memory', $period),
            'errorsChart' => $this->itService->getPerformanceChartData('errors', $period),
            'logStats' => $this->itService->getLogStatistics(
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            ),
            'period' => $period,
        ];

        return view('dashboard.it.index', $data);
    }


    /**
     * Display system logs page
     * Shows paginated list of logs with filters by level
     * 
     * @see Requirements 8.2
     */
    public function logs(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'level' => $request->get('level'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $logs = $this->itService->getLogs($filters);
        $logStats = $this->itService->getLogStatistics(
            Carbon::now()->subDays(7),
            Carbon::now()
        );

        return view('dashboard.it.logs', [
            'logs' => $logs,
            'filters' => $filters,
            'logStats' => $logStats,
        ]);
    }

    /**
     * Display security alerts page
     * Shows failed login attempts and suspicious activity
     * 
     * @see Requirements 8.4
     */
    public function security(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'priority' => $request->get('priority'),
            'is_resolved' => $request->has('is_resolved') ? (bool) $request->get('is_resolved') : null,
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        // Remove null values from filters
        $filters = array_filter($filters, fn($value) => $value !== null);

        $alerts = $this->itService->getSecurityAlerts($filters);
        $failedLogins = $this->itService->getFailedLoginAttempts(20);

        return view('dashboard.it.security', [
            'alerts' => $alerts,
            'failedLogins' => $failedLogins,
            'filters' => $filters,
        ]);
    }

    /**
     * Display performance metrics page
     * Shows database health, query performance, and system metrics
     * 
     * @see Requirements 8.5
     */
    public function performance(Request $request)
    {
        $period = $request->get('period', 'day');

        $data = [
            'databaseHealth' => $this->itService->getDatabaseHealth(),
            'systemMetrics' => $this->itService->getSystemMetrics(),
            'cpuChart' => $this->itService->getPerformanceChartData('cpu', $period),
            'memoryChart' => $this->itService->getPerformanceChartData('memory', $period),
            'errorsChart' => $this->itService->getPerformanceChartData('errors', $period),
            'services' => $this->itService->getServices(),
            'period' => $period,
        ];

        return view('dashboard.it.performance', $data);
    }

    /**
     * Display all system alerts
     */
    public function alerts(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'type' => $request->get('type'),
            'priority' => $request->get('priority'),
            'is_resolved' => $request->has('is_resolved') ? (bool) $request->get('is_resolved') : null,
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        // Remove null values from filters
        $filters = array_filter($filters, fn($value) => $value !== null);

        $alerts = $this->itService->getAlerts($filters);

        return view('dashboard.it.alerts', [
            'alerts' => $alerts,
            'filters' => $filters,
        ]);
    }

    /**
     * Resolve a system alert
     */
    public function resolveAlert(Request $request, int $alertId)
    {
        $request->validate([
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $alert = $this->itService->resolveAlert(
            $alertId,
            Auth::user(),
            $request->input('resolution_notes')
        );

        if (!$alert) {
            return redirect()->back()->with('error', __('Alert not found or already resolved.'));
        }

        return redirect()->back()->with('success', __('Alert resolved successfully.'));
    }

    /**
     * Clear application caches
     * 
     * @see Requirements 8.3
     */
    public function clearCache(Request $request)
    {
        $result = $this->itService->clearCache(Auth::user());

        if ($result['success']) {
            return redirect()->back()->with('success', __('Cache cleared successfully.'));
        }

        return redirect()->back()->with('error', __('Failed to clear some caches: ') . implode(', ', $result['errors']));
    }

    /**
     * Update service status
     */
    public function updateServiceStatus(Request $request, int $serviceId)
    {
        $request->validate([
            'status' => 'required|string|in:running,stopped,maintenance',
        ]);

        $service = $this->itService->updateServiceStatus(
            $serviceId,
            $request->input('status'),
            Auth::user()
        );

        if (!$service) {
            return redirect()->back()->with('error', __('Service not found.'));
        }

        return redirect()->back()->with('success', __('Service status updated successfully.'));
    }

    /**
     * Export system logs to CSV
     */
    public function exportLogs(Request $request)
    {
        $filters = [
            'level' => $request->get('level'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => 10000, // Get all for export
        ];

        $logs = $this->itService->getLogs($filters);

        $columns = [
            'id' => 'ID',
            'level' => 'Level',
            'action' => 'Action',
            'message' => 'Message',
            'user' => 'User',
            'ip_address' => 'IP Address',
            'created_at' => 'Date',
        ];

        // Log the export action
        $this->auditService->log(
            'export',
            'system_log',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $logs->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $logs->getCollection(),
            $columns,
            'system_logs_' . date('Y-m-d') . '.csv'
        );
    }

    /**
     * Run database health check
     * 
     * @see Requirements 8.5
     */
    public function runHealthCheck(Request $request)
    {
        $health = $this->itService->getDatabaseHealth();

        // Log the health check
        $this->auditService->log(
            'health_check',
            'database',
            null,
            [
                'new_values' => [
                    'connection_status' => $health['connection_status'],
                    'slow_queries_count' => $health['query_performance']['slow_queries_count'] ?? 0,
                ],
            ]
        );

        if ($request->wantsJson()) {
            return response()->json($health);
        }

        return redirect()->back()->with('health_check', $health);
    }
}
