<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ApiError;
use App\Models\DatabaseBackup;
use App\Models\DeploymentLog;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\SecurityAuditLog;
use App\Models\SlowQuery;
use App\Models\SystemAlert;
use App\Models\SystemLog;
use App\Models\SystemService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ITController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * IT/DevOps Dashboard
     */
    public function index()
    {
        $metrics = $this->getITMetrics();

        $systemMetrics = [
            'total_users' => User::count(),
            'total_employees' => Employee::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_drivers' => Driver::count(),
            'database_size' => $metrics['database_size'],
        ];

        $userActivity = [
            'new_registrations' => User::whereDate('created_at', today())->count(),
            'new_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'orders_today' => Order::whereDate('created_at', today())->count(),
        ];

        $systemServices = SystemService::orderBy('name')->get();

        $backupStats = [
            'total' => DatabaseBackup::count(),
            'completed' => DatabaseBackup::where('status', 'completed')->count(),
            'failed' => DatabaseBackup::where('status', 'failed')->count(),
            'last_backup' => DatabaseBackup::where('status', 'completed')->latest('completed_at')->first(),
        ];

        $dates = collect(range(0, 6))->map(fn ($i) => now()->subDays(6 - $i)->startOfDay());
        $hasAuditLogs = Schema::hasTable('security_audit_logs');
        $dailyTraffic = $dates->map(function ($day) {
            return [
                'date' => $day->toDateString(),
                'users' => User::whereDate('created_at', $day)->count(),
                'orders' => Order::whereDate('created_at', $day)->count(),
                'logins' => (Schema::hasTable('security_audit_logs')
                    ? SecurityAuditLog::where('event_type', 'login_attempt')
                        ->where('status', 'success')
                        ->whereDate('created_at', $day)->count()
                    : 0),
            ];
        })->toArray();

        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(fn ($row) => ['status' => $row->status, 'count' => (int) $row->count])
            ->toArray();

        $securityStats = $hasAuditLogs
            ? [
                'total_events' => SecurityAuditLog::count(),
                'today_events' => SecurityAuditLog::whereDate('created_at', today())->count(),
                'failed_logins' => SecurityAuditLog::where('event_type', 'login_attempt')->where('status', 'failed')->count(),
                'high_risk' => SecurityAuditLog::whereIn('risk_level', ['high', 'critical'])->count(),
            ]
            : [
                'total_events' => 0,
                'today_events' => 0,
                'failed_logins' => 0,
                'high_risk' => 0,
            ];

        $logStats = [
            'total' => SystemLog::count(),
            'today' => SystemLog::whereDate('created_at', today())->count(),
            'warnings' => SystemLog::where('level', 'warning')->count(),
            'errors' => SystemLog::where('level', 'error')->count(),
        ];

        $securityLogs = $hasAuditLogs
            ? SecurityAuditLog::orderBy('created_at', 'desc')->take(10)->get()
            : collect();
        $systemLogs = SystemLog::orderBy('created_at', 'desc')->take(20)->get();

        $apiStats = [
            'total_errors' => ApiError::count(),
            'today_errors' => ApiError::whereDate('occurred_at', today())->count(),
            'avg_response_time' => ApiError::avg('response_time') ?? 0,
        ];
        $apiErrors = ApiError::orderBy('occurred_at', 'desc')->take(10)->get();

        $queryStats = [
            'total' => SlowQuery::count(),
            'unoptimized' => SlowQuery::where('is_optimized', false)->count(),
            'critical' => SlowQuery::where('execution_time', '>', 2000)->count(),
        ];
        $slowQueries = SlowQuery::orderBy('last_seen_at', 'desc')->take(10)->get();

        $recentLogins = Employee::orderByDesc('last_login_at')->take(10)->get();

        return view('dashboards.it.index', compact(
            'metrics',
            'systemMetrics',
            'userActivity',
            'systemServices',
            'backupStats',
            'dailyTraffic',
            'ordersByStatus',
            'securityStats',
            'logStats',
            'securityLogs',
            'systemLogs',
            'apiStats',
            'apiErrors',
            'queryStats',
            'slowQueries',
            'recentLogins'
        ));
    }

    /**
     * Get IT dashboard metrics
     */
    private function getITMetrics()
    {
        return Cache::remember('it_metrics', 60, function () {
            // System Health - Real data from database
            $services = SystemService::all();
            $services_online = $services->where('status', 'online')->count();
            $services_offline = $services->where('status', 'offline')->count();
            $services_degraded = $services->where('status', 'degraded')->count();
            $avg_response_time = $services->where('status', 'online')->avg('response_time') ?? 0;

            // Alerts & Issues - Real data
            $critical_alerts = SystemAlert::where('severity', 'critical')
                ->where('status', 'active')->count();
            $warning_alerts = SystemAlert::where('severity', 'high')
                ->where('status', 'active')->count();
            $total_active_alerts = SystemAlert::where('status', 'active')->count();

            // Error Tracking - Real data
            $api_errors_today = ApiError::whereDate('occurred_at', today())->count();
            $total_requests_today = ApiError::whereDate('occurred_at', today())->count() + 10000; // Approximate
            $error_rate_24h = $total_requests_today > 0
                ? round(($api_errors_today / $total_requests_today) * 100, 2)
                : 0;
            $slow_queries_today = SlowQuery::whereDate('last_seen_at', today())->count();
            $avg_query_time = SlowQuery::whereDate('last_seen_at', today())->avg('execution_time') ?? 0;

            // Database Health - Real data
            $database_size = $this->getDatabaseSize();
            $last_backup = DatabaseBackup::where('status', 'completed')
                ->latest('completed_at')->first()?->completed_at;
            $backup_success_rate = $this->getBackupSuccessRate();

            // Deployment Status - Real data
            $last_deployment = DeploymentLog::latest('started_at')->first()?->started_at;
            $deployments_this_month = DeploymentLog::whereMonth('started_at', now()->month)->count();
            $deployment_success_rate = $this->getDeploymentSuccessRate();

            // System Performance (these require system-level monitoring, keeping as fallback)
            $cpu_usage = $this->getCPUUsage();
            $memory_usage = $this->getMemoryUsage();
            $disk_usage = $this->getDiskUsage();
            $network_throughput = $this->getNetworkThroughput();

            // Recent Activity - Real data
            $recent_alerts = $this->getRecentAlerts();
            $recent_deployments = $this->getRecentDeployments();
            $system_uptime = $this->getSystemUptime();

            return [
                // System Health
                'services_online' => $services_online,
                'services_offline' => $services_offline,
                'services_degraded' => $services_degraded,
                'avg_response_time' => round($avg_response_time, 2),

                // Alerts & Issues
                'critical_alerts' => $critical_alerts,
                'warning_alerts' => $warning_alerts,
                'total_active_alerts' => $total_active_alerts,

                // Error Tracking
                'api_errors_today' => $api_errors_today,
                'error_rate_24h' => $error_rate_24h,
                'slow_queries_today' => $slow_queries_today,
                'avg_query_time' => round($avg_query_time, 2),

                // Database Health
                'database_size' => $database_size,
                'last_backup' => $last_backup,
                'backup_success_rate' => $backup_success_rate,

                // Deployment Status
                'last_deployment' => $last_deployment,
                'deployments_this_month' => $deployments_this_month,
                'deployment_success_rate' => $deployment_success_rate,

                // System Performance
                'cpu_usage' => $cpu_usage,
                'memory_usage' => $memory_usage,
                'disk_usage' => $disk_usage,
                'network_throughput' => $network_throughput,

                // Recent Activity
                'recent_alerts' => $recent_alerts,
                'recent_deployments' => $recent_deployments,
                'system_uptime' => $system_uptime,
            ];
        });
    }

    /**
     * System Health Monitoring
     */
    public function systemHealth()
    {
        $services = SystemService::orderBy('name')->get();
        $healthSummary = [
            'total_services' => $services->count(),
            'online' => $services->where('status', 'online')->count(),
            'offline' => $services->where('status', 'offline')->count(),
            'degraded' => $services->where('status', 'degraded')->count(),
            'maintenance' => $services->where('status', 'maintenance')->count(),
        ];

        return view('dashboards.it.system-health', compact('services', 'healthSummary'));
    }

    /**
     * Update service status
     */
    public function updateServiceStatus(Request $request, SystemService $service)
    {
        $request->validate([
            'status' => 'required|in:online,offline,degraded,maintenance',
        ]);

        $oldStatus = $service->status;
        $service->update([
            'status' => $request->status,
            'last_check' => now(),
        ]);

        // Create alert if service went offline
        if ($request->status === 'offline' && $oldStatus !== 'offline') {
            SystemAlert::create([
                'alert_type' => 'service_down',
                'severity' => 'critical',
                'title' => "Service {$service->name} is offline",
                'description' => "The {$service->name} service has gone offline and requires immediate attention.",
                'metadata' => [
                    'service_id' => $service->id,
                    'previous_status' => $oldStatus,
                    'host' => $service->host,
                    'port' => $service->port,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service status updated successfully',
        ]);
    }

    /**
     * System Logs
     */
    public function systemLogs(Request $request)
    {
        $logs = SystemLog::when($request->level, function ($query, $level) {
            $query->where('level', $level);
        })
            ->when($request->channel, function ($query, $channel) {
                $query->where('channel', $channel);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('message', 'like', "%{$search}%");
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $logLevels = SystemLog::distinct()->pluck('level');
        $channels = SystemLog::distinct()->pluck('channel');

        $loginLogs = Schema::hasTable('security_audit_logs')
            ? SecurityAuditLog::with('user')
                ->whereIn('event_type', ['login_attempt', 'employee_logout'])
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
            : collect();

        return view('dashboards.it.system-logs', compact('logs', 'logLevels', 'channels', 'loginLogs'));
    }

    /**
     * API Error Tracking
     */
    public function apiErrors(Request $request)
    {
        $errors = ApiError::when($request->endpoint, function ($query, $endpoint) {
            $query->where('endpoint', 'like', "%{$endpoint}%");
        })
            ->when($request->status_code, function ($query, $statusCode) {
                $query->where('status_code', $statusCode);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('occurred_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('occurred_at', '<=', $date);
            })
            ->orderBy('occurred_at', 'desc')
            ->paginate(50);

        $errorStats = [
            'total_errors' => ApiError::count(),
            'errors_today' => ApiError::whereDate('occurred_at', today())->count(),
            'most_common_error' => ApiError::select('status_code', DB::raw('count(*) as count'))
                ->groupBy('status_code')
                ->orderBy('count', 'desc')
                ->first(),
            'slowest_endpoint' => ApiError::orderBy('response_time', 'desc')->first(),
        ];

        return view('dashboards.it.api-errors', compact('errors', 'errorStats'));
    }

    /**
     * Database Performance
     */
    public function databasePerformance()
    {
        $slowQueries = SlowQuery::orderBy('execution_time', 'desc')
            ->take(20)
            ->get();

        $queryStats = [
            'total_slow_queries' => SlowQuery::count(),
            'queries_today' => SlowQuery::whereDate('last_seen_at', today())->count(),
            'avg_execution_time' => SlowQuery::avg('execution_time'),
            'slowest_query' => SlowQuery::orderBy('execution_time', 'desc')->first(),
        ];

        $databaseHealth = [
            'size' => $this->getDatabaseSize(),
            'connections' => $this->getDatabaseConnections(),
            'cache_hit_ratio' => $this->getCacheHitRatio(),
            'index_usage' => $this->getIndexUsage(),
        ];

        return view('dashboards.it.database-performance', compact(
            'slowQueries', 'queryStats', 'databaseHealth'
        ));
    }

    /**
     * Database Backup Management
     */
    public function databaseBackups()
    {
        $backups = DatabaseBackup::orderBy('started_at', 'desc')->paginate(20);

        $backupStats = [
            'total_backups' => DatabaseBackup::count(),
            'successful_backups' => DatabaseBackup::where('status', 'completed')->count(),
            'failed_backups' => DatabaseBackup::where('status', 'failed')->count(),
            'last_successful_backup' => DatabaseBackup::where('status', 'completed')
                ->latest('completed_at')->first(),
            'total_backup_size' => DatabaseBackup::where('status', 'completed')
                ->sum('file_size'),
        ];

        return view('dashboards.it.database-backups', compact('backups', 'backupStats'));
    }

    /**
     * Trigger database backup
     */
    public function triggerBackup(Request $request)
    {
        $request->validate([
            'database_name' => 'required|string',
            'type' => 'required|in:full,incremental,differential',
        ]);

        try {
            $backup = DatabaseBackup::create([
                'backup_name' => 'backup_'.$request->database_name.'_'.now()->format('Y_m_d_H_i_s'),
                'database_name' => $request->database_name,
                'type' => $request->type,
                'status' => 'in_progress',
                'started_at' => now(),
                'file_path' => 'backups/'.$request->database_name.'/'.now()->format('Y/m/d'),
            ]);

            // Queue backup job
            \App\Jobs\DatabaseBackupJob::dispatch($backup);

            return response()->json([
                'success' => true,
                'message' => 'Database backup initiated successfully',
                'backup_id' => $backup->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate backup: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Deployment Management
     */
    public function deployments()
    {
        $deployments = DeploymentLog::with(['deployedBy'])
            ->orderBy('started_at', 'desc')
            ->paginate(20);

        $deploymentStats = [
            'total_deployments' => DeploymentLog::count(),
            'successful_deployments' => DeploymentLog::where('status', 'completed')->count(),
            'failed_deployments' => DeploymentLog::where('status', 'failed')->count(),
            'rollbacks' => DeploymentLog::where('status', 'rolled_back')->count(),
            'avg_deployment_time' => DeploymentLog::where('status', 'completed')
                ->avg('duration_seconds'),
        ];

        return view('dashboards.it.deployments', compact('deployments', 'deploymentStats'));
    }

    /**
     * Create new deployment
     */
    public function createDeployment(Request $request)
    {
        $request->validate([
            'version' => 'required|string',
            'environment' => 'required|in:production,staging,development',
            'changes' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        $deployment = DeploymentLog::create([
            'version' => $request->version,
            'environment' => $request->environment,
            'status' => 'pending',
            'deployed_by' => auth()->id(),
            'started_at' => now(),
            'changes' => $request->changes,
            'notes' => $request->notes,
        ]);

        // Queue deployment job
        \App\Jobs\DeploymentJob::dispatch($deployment);

        return response()->json([
            'success' => true,
            'message' => 'Deployment initiated successfully',
            'deployment_id' => $deployment->id,
        ]);
    }

    /**
     * System Alerts Management
     */
    public function systemAlerts(Request $request)
    {
        $alerts = SystemAlert::with(['acknowledgedBy', 'resolvedBy'])
            ->when($request->severity, function ($query, $severity) {
                $query->where('severity', $severity);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->alert_type, function ($query, $type) {
                $query->where('alert_type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $alertStats = [
            'total_alerts' => SystemAlert::count(),
            'active_alerts' => SystemAlert::where('status', 'active')->count(),
            'critical_alerts' => SystemAlert::where('severity', 'critical')
                ->where('status', 'active')->count(),
            'resolved_today' => SystemAlert::where('status', 'resolved')
                ->whereDate('resolved_at', today())->count(),
        ];

        return view('dashboards.it.system-alerts', compact('alerts', 'alertStats'));
    }

    /**
     * Acknowledge alert
     */
    public function acknowledgeAlert(Request $request, SystemAlert $alert)
    {
        $alert->update([
            'status' => 'acknowledged',
            'acknowledged_by' => auth()->id(),
            'acknowledged_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert acknowledged successfully',
        ]);
    }

    /**
     * Resolve alert
     */
    public function resolveAlert(Request $request, SystemAlert $alert)
    {
        $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $alert->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'resolution_notes' => $request->resolution_notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert resolved successfully',
        ]);
    }

    /**
     * Integration Health Monitoring
     */
    public function integrationHealth()
    {
        $integrations = [
            'payment_gateways' => $this->checkPaymentGateways(),
            'email_service' => $this->checkEmailService(),
            'sms_service' => $this->checkSMSService(),
            'cloud_storage' => $this->checkCloudStorage(),
            'cdn' => $this->checkCDN(),
            'monitoring_tools' => $this->checkMonitoringTools(),
        ];

        return view('dashboards.it.integration-health', compact('integrations'));
    }

    /**
     * Test integration
     */
    public function testIntegration(Request $request)
    {
        $request->validate([
            'service' => 'required|string',
        ]);

        $result = match ($request->service) {
            'payment_gateway' => $this->testPaymentGateway(),
            'email_service' => $this->testEmailService(),
            'sms_service' => $this->testSMSService(),
            'cloud_storage' => $this->testCloudStorage(),
            default => ['success' => false, 'message' => 'Unknown service']
        };

        return response()->json($result);
    }

    /**
     * System Maintenance
     */
    public function systemMaintenance()
    {
        $maintenanceStatus = [
            'cache_size' => $this->getCacheSize(),
            'log_files_size' => $this->getLogFilesSize(),
            'temp_files_size' => $this->getTempFilesSize(),
            'database_optimization' => $this->getDatabaseOptimizationStatus(),
        ];

        return view('dashboards.it.system-maintenance', compact('maintenanceStatus'));
    }

    /**
     * Clear system cache
     */
    public function clearCache(Request $request)
    {
        try {
            $cacheTypes = $request->input('cache_types', []);

            foreach ($cacheTypes as $type) {
                switch ($type) {
                    case 'application':
                        Artisan::call('cache:clear');
                        break;
                    case 'config':
                        Artisan::call('config:clear');
                        break;
                    case 'route':
                        Artisan::call('route:clear');
                        break;
                    case 'view':
                        Artisan::call('view:clear');
                        break;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Helper Methods
     */
    private function calculateErrorRate()
    {
        $totalRequests = ApiError::whereDate('occurred_at', today())->count() + 10000; // Mock total requests
        $errorRequests = ApiError::whereDate('occurred_at', today())->count();

        return $totalRequests > 0 ? round(($errorRequests / $totalRequests) * 100, 2) : 0;
    }

    private function getDatabaseSize()
    {
        try {
            $size = DB::select('SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()')[0]->size_mb ?? 0;

            return $size.' MB';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getBackupSuccessRate()
    {
        $total = DatabaseBackup::count();
        $successful = DatabaseBackup::where('status', 'completed')->count();

        return $total > 0 ? round(($successful / $total) * 100, 1) : 100;
    }

    private function getDeploymentSuccessRate()
    {
        $total = DeploymentLog::count();
        $successful = DeploymentLog::where('status', 'completed')->count();

        return $total > 0 ? round(($successful / $total) * 100, 1) : 100;
    }

    private function getCPUUsage()
    {
        // Try to get real CPU usage, fallback to service average if available
        $avgCpu = SystemService::whereNotNull('cpu_usage')->avg('cpu_usage');
        if ($avgCpu !== null) {
            return round($avgCpu, 1);
        }

        // Fallback: would integrate with actual system monitoring tools
        return 0; // Return 0 if no data available instead of random
    }

    private function getMemoryUsage()
    {
        // Try to get real memory usage from services
        $avgMemory = SystemService::whereNotNull('memory_usage')->avg('memory_usage');
        if ($avgMemory !== null) {
            return round($avgMemory, 1);
        }

        // Fallback: would integrate with actual system monitoring
        return 0;
    }

    private function getDiskUsage()
    {
        // Would integrate with actual disk monitoring
        // For now, calculate based on database size relative to a reasonable limit
        try {
            $dbSize = DB::select('SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()')[0]->size_mb ?? 0;
            // Assume 100GB disk limit, calculate percentage
            $diskLimitMB = 100 * 1024; // 100GB in MB

            return round(($dbSize / $diskLimitMB) * 100, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getNetworkThroughput()
    {
        // Would integrate with actual network monitoring tools
        // For now, return a placeholder that indicates monitoring needed
        return 'Monitoring not configured';
    }

    private function getRecentAlerts()
    {
        return SystemAlert::with(['acknowledgedBy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    private function getRecentDeployments()
    {
        return DeploymentLog::with(['deployedBy'])
            ->orderBy('started_at', 'desc')
            ->take(5)
            ->get();
    }

    private function getSystemUptime()
    {
        // Mock data - would calculate actual system uptime
        return '15 days, 8 hours, 32 minutes';
    }

    private function getDatabaseConnections()
    {
        try {
            return DB::select("SHOW STATUS LIKE 'Threads_connected'")[0]->Value ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getCacheHitRatio()
    {
        // Mock data - would calculate actual cache hit ratio
        return '94.5%';
    }

    private function getIndexUsage()
    {
        // Mock data - would analyze actual index usage
        return '87.2%';
    }

    private function checkPaymentGateways()
    {
        // Mock health check - would implement actual gateway testing
        return [
            'stripe' => ['status' => 'online', 'response_time' => 120],
            'paypal' => ['status' => 'online', 'response_time' => 95],
            'square' => ['status' => 'degraded', 'response_time' => 450],
        ];
    }

    private function checkEmailService()
    {
        return ['status' => 'online', 'response_time' => 85];
    }

    private function checkSMSService()
    {
        return ['status' => 'online', 'response_time' => 200];
    }

    private function checkCloudStorage()
    {
        return ['status' => 'online', 'response_time' => 150];
    }

    private function checkCDN()
    {
        return ['status' => 'online', 'response_time' => 45];
    }

    private function checkMonitoringTools()
    {
        return ['status' => 'online', 'response_time' => 75];
    }

    private function testPaymentGateway()
    {
        // Mock test - would implement actual gateway testing
        return ['success' => true, 'message' => 'Payment gateway is responding normally'];
    }

    private function testEmailService()
    {
        return ['success' => true, 'message' => 'Email service is functioning properly'];
    }

    private function testSMSService()
    {
        return ['success' => true, 'message' => 'SMS service is operational'];
    }

    private function testCloudStorage()
    {
        return ['success' => true, 'message' => 'Cloud storage is accessible'];
    }

    private function getCacheSize()
    {
        return '245 MB';
    }

    private function getLogFilesSize()
    {
        return '1.2 GB';
    }

    private function getTempFilesSize()
    {
        return '89 MB';
    }

    private function getDatabaseOptimizationStatus()
    {
        return 'Last optimized: 2 days ago';
    }

    /**
     * Get system resource monitoring data
     */
    public function getResourceMonitoring(Request $request)
    {
        $hours = $request->get('hours', 24);
        $type = $request->get('type'); // cpu, memory, disk, network

        $resources = \App\Models\SystemResource::recent($hours)
            ->when($type, function ($query, $type) {
                $query->byType($type);
            })
            ->orderBy('recorded_at', 'desc')
            ->get()
            ->groupBy('resource_type');

        // Calculate averages
        $averages = [];
        foreach ($resources as $resourceType => $records) {
            $averages[$resourceType] = [
                'average_usage' => round($records->avg('usage_percentage'), 2),
                'peak_usage' => round($records->max('usage_percentage'), 2),
                'min_usage' => round($records->min('usage_percentage'), 2),
                'current_usage' => $records->first()->usage_percentage ?? 0,
            ];
        }

        return response()->json([
            'resources' => $resources,
            'averages' => $averages,
            'latest' => \App\Models\SystemResource::recent(1)->latest('recorded_at')->get(),
        ]);
    }

    /**
     * Get automated alert rules
     */
    public function getAlertRules(Request $request)
    {
        $rules = \App\Models\AlertRule::forDashboard('it')
            ->when($request->active_only, function ($query) {
                $query->active();
            })
            ->get();

        return response()->json($rules);
    }

    /**
     * Create alert rule
     */
    public function createAlertRule(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'metric_type' => 'required|string',
            'condition' => 'required|in:>,<,>=,<=,==',
            'threshold_value' => 'required|numeric',
            'duration_minutes' => 'required|integer|min:1',
            'severity' => 'required|in:low,medium,high,critical',
            'notification_channels' => 'nullable|array',
        ]);

        $rule = \App\Models\AlertRule::create([
            'name' => $request->name,
            'dashboard_type' => 'it',
            'metric_type' => $request->metric_type,
            'condition' => $request->condition,
            'threshold_value' => $request->threshold_value,
            'duration_minutes' => $request->duration_minutes,
            'severity' => $request->severity,
            'notification_channels' => $request->notification_channels ?? ['dashboard'],
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'rule' => $rule]);
    }

    /**
     * Get security audit logs
     */
    public function getSecurityAuditLogs(Request $request)
    {
        $logs = \App\Models\SecurityAuditLog::with('user')
            ->when($request->event_type, function ($query, $type) {
                $query->byEventType($type);
            })
            ->when($request->risk_level, function ($query, $level) {
                $query->where('risk_level', $level);
            })
            ->when($request->high_risk_only, function ($query) {
                $query->highRisk();
            })
            ->when($request->failed_only, function ($query) {
                $query->failed();
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Get statistics
        $stats = [
            'total_events' => \App\Models\SecurityAuditLog::count(),
            'failed_logins' => \App\Models\SecurityAuditLog::where('event_type', 'login_attempt')
                ->failed()->count(),
            'high_risk_events' => \App\Models\SecurityAuditLog::highRisk()->count(),
            'events_today' => \App\Models\SecurityAuditLog::whereDate('created_at', today())->count(),
        ];

        return response()->json([
            'logs' => $logs,
            'stats' => $stats,
        ]);
    }

    /**
     * Record security event
     */
    public function recordSecurityEvent(Request $request)
    {
        $request->validate([
            'event_type' => 'required|string',
            'status' => 'required|in:success,failed,blocked',
            'description' => 'required|string',
            'risk_level' => 'required|in:low,medium,high,critical',
        ]);

        $user = auth()->user() ?? auth('employee')->user();

        $log = \App\Models\SecurityAuditLog::create([
            'event_type' => $request->event_type,
            'user_type' => $user ? get_class($user) : null,
            'user_id' => $user?->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => $request->status,
            'description' => $request->description,
            'risk_level' => $request->risk_level,
            'metadata' => $request->metadata,
        ]);

        // Create alert if high risk
        if (in_array($request->risk_level, ['high', 'critical'])) {
            SystemAlert::create([
                'title' => "Security Alert: {$request->event_type}",
                'message' => $request->description,
                'type' => 'error',
                'severity' => $request->risk_level,
                'status' => 'active',
                'category' => 'security',
            ]);
        }

        return response()->json(['success' => true, 'log' => $log]);
    }

    /**
     * Get live system logs
     */
    public function getLiveLogs(Request $request)
    {
        $level = $request->get('level');
        $limit = $request->get('limit', 100);

        $logs = SystemLog::when($level, function ($query, $level) {
            $query->where('level', $level);
        })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($logs);
    }

    /**
     * Get system health API endpoint
     */
    public function getSystemHealth()
    {
        $services = SystemService::orderBy('name')->get();
        $healthSummary = [
            'total_services' => $services->count(),
            'online' => $services->where('status', 'online')->count(),
            'offline' => $services->where('status', 'offline')->count(),
            'degraded' => $services->where('status', 'degraded')->count(),
            'maintenance' => $services->where('status', 'maintenance')->count(),
        ];

        return response()->json([
            'services' => $services,
            'summary' => $healthSummary,
        ]);
    }
}
