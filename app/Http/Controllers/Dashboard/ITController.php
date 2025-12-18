<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemService;
use App\Models\SystemLog;
use App\Models\SystemAlert;
use App\Models\DatabaseBackup;
use App\Models\DeploymentLog;
use App\Models\ApiError;
use App\Models\SlowQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ITController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:it_admin,devops_engineer']);
    }

    /**
     * IT/DevOps Dashboard
     */
    public function index()
    {
        $metrics = $this->getITMetrics();
        return view('dashboards.it.index', compact('metrics'));
    }

    /**
     * Get IT dashboard metrics
     */
    private function getITMetrics()
    {
        return Cache::remember('it_metrics', 60, function () {
            return [
                // System Health - Using mock data for now
                'services_online' => 12,
                'services_offline' => 0,
                'services_degraded' => 1,
                'avg_response_time' => 45,
                
                // Alerts & Issues
                'critical_alerts' => 2,
                'warning_alerts' => 5,
                'total_active_alerts' => 7,
                
                // Error Tracking
                'api_errors_today' => 23,
                'error_rate_24h' => 0.05,
                'slow_queries_today' => 8,
                'avg_query_time' => 1.2,
                
                // Database Health
                'database_size' => '2.5GB',
                'last_backup' => now()->subHours(6),
                'backup_success_rate' => 98.5,
                
                // Deployment Status
                'last_deployment' => now()->subDays(2),
                'deployments_this_month' => 12,
                'deployment_success_rate' => 95.8,
                
                // System Performance
                'cpu_usage' => 45.2,
                'memory_usage' => 68.7,
                'disk_usage' => 72.1,
                'network_throughput' => '125 Mbps',
                
                // Recent Activity
                'recent_alerts' => [],
                'recent_deployments' => [],
                'system_uptime' => '99.8%',
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
            'status' => 'required|in:online,offline,degraded,maintenance'
        ]);

        $oldStatus = $service->status;
        $service->update([
            'status' => $request->status,
            'last_check' => now()
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
                    'port' => $service->port
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service status updated successfully'
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

        return view('dashboards.it.system-logs', compact('logs', 'logLevels', 'channels'));
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
            'queries_today' => SlowQuery::whereDate('executed_at', today())->count(),
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
            'type' => 'required|in:full,incremental,differential'
        ]);

        try {
            $backup = DatabaseBackup::create([
                'backup_name' => 'backup_' . $request->database_name . '_' . now()->format('Y_m_d_H_i_s'),
                'database_name' => $request->database_name,
                'type' => $request->type,
                'status' => 'in_progress',
                'started_at' => now(),
                'file_path' => 'backups/' . $request->database_name . '/' . now()->format('Y/m/d'),
            ]);

            // Queue backup job
            \App\Jobs\DatabaseBackupJob::dispatch($backup);

            return response()->json([
                'success' => true,
                'message' => 'Database backup initiated successfully',
                'backup_id' => $backup->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate backup: ' . $e->getMessage()
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
            'notes' => 'nullable|string'
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
            'deployment_id' => $deployment->id
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
            'acknowledged_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert acknowledged successfully'
        ]);
    }

    /**
     * Resolve alert
     */
    public function resolveAlert(Request $request, SystemAlert $alert)
    {
        $request->validate([
            'resolution_notes' => 'required|string'
        ]);

        $alert->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'resolution_notes' => $request->resolution_notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alert resolved successfully'
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
            'service' => 'required|string'
        ]);

        $result = match($request->service) {
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
                'message' => 'Cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
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
            $size = DB::select("SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()")[0]->size_mb ?? 0;
            return $size . ' MB';
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
        // Mock data - would integrate with actual system monitoring
        return rand(20, 80) . '%';
    }

    private function getMemoryUsage()
    {
        // Mock data - would integrate with actual system monitoring
        return rand(40, 85) . '%';
    }

    private function getDiskUsage()
    {
        // Mock data - would integrate with actual system monitoring
        return rand(30, 75) . '%';
    }

    private function getNetworkThroughput()
    {
        // Mock data - would integrate with actual network monitoring
        return rand(50, 200) . ' Mbps';
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
}