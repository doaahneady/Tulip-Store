<?php

namespace App\Services\Dashboard;

use App\Models\SlowQuery;
use App\Models\SystemAlert;
use App\Models\SystemLog;
use App\Models\SystemService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * IT Dashboard Service
 *
 * Provides system metrics collection, log viewing with filters, and cache management.
 *
 * @see Requirements 8.1, 8.2, 8.3
 */
class ITDashboardService
{
    public function __construct(
        protected MetricsService $metricsService,
        protected AuditService $auditService
    ) {}

    /**
     * Get system metrics including CPU, memory, and disk usage
     *
     * @return array Array containing cpu_usage, memory_usage, disk_usage, uptime
     *
     * @see Requirements 8.1
     */
    public function getSystemMetrics(): array
    {
        // Get metrics from system services or calculate from system
        $services = SystemService::all();

        // Calculate average CPU and memory from services
        $avgCpu = $services->avg('cpu_usage') ?? 0;
        $avgMemory = $services->avg('memory_usage') ?? 0;

        // Get disk usage (simulated - in production would use system calls)
        $diskUsage = $this->getDiskUsage();

        // Get system uptime
        $uptime = $this->getSystemUptime();

        return [
            'cpu_usage' => [
                'value' => round($avgCpu, 1),
                'status' => $this->getMetricStatus($avgCpu, 70, 90),
            ],
            'memory_usage' => [
                'value' => round($avgMemory, 1),
                'status' => $this->getMetricStatus($avgMemory, 70, 90),
            ],
            'disk_usage' => [
                'value' => round($diskUsage, 1),
                'status' => $this->getMetricStatus($diskUsage, 70, 90),
            ],
            'uptime' => [
                'value' => $uptime,
                'formatted' => $this->formatUptime($uptime),
            ],
        ];
    }

    /**
     * Get IT dashboard KPI metrics
     *
     * @return array Array containing system health indicators
     *
     * @see Requirements 8.1
     */
    public function getKPIMetrics(): array
    {
        $systemMetrics = $this->getSystemMetrics();

        // Count active services
        $totalServices = SystemService::count();
        $activeServices = SystemService::where('status', 'running')->count();

        // Count unresolved alerts
        $unresolvedAlerts = SystemAlert::where('is_resolved', false)->count();
        $criticalAlerts = SystemAlert::where('is_resolved', false)
            ->where('priority', 'critical')
            ->count();

        // Count recent errors
        $errorsToday = SystemLog::whereIn('level', ['error', 'critical'])
            ->whereDate('created_at', Carbon::today())
            ->count();
        $errorsYesterday = SystemLog::whereIn('level', ['error', 'critical'])
            ->whereDate('created_at', Carbon::yesterday())
            ->count();
        $errorGrowth = $this->metricsService->calculateGrowthPercentage(
            (float) $errorsToday,
            (float) $errorsYesterday
        );

        // Slow queries count
        $slowQueries = SlowQuery::where('is_optimized', false)->count();

        return [
            'system_health' => [
                'cpu' => $systemMetrics['cpu_usage'],
                'memory' => $systemMetrics['memory_usage'],
                'disk' => $systemMetrics['disk_usage'],
                'uptime' => $systemMetrics['uptime'],
            ],
            'services' => [
                'active' => $activeServices,
                'total' => $totalServices,
                'percentage' => $totalServices > 0 ? round(($activeServices / $totalServices) * 100, 1) : 0,
            ],
            'alerts' => [
                'unresolved' => $unresolvedAlerts,
                'critical' => $criticalAlerts,
            ],
            'errors' => [
                'today' => $errorsToday,
                'yesterday' => $errorsYesterday,
                'growth' => $this->metricsService->formatPercentage($errorGrowth),
            ],
            'slow_queries' => [
                'count' => $slowQueries,
            ],
        ];
    }

    /**
     * Get system logs with filters
     *
     * @param  array  $filters  Filters including level, search, date_from, date_to, per_page
     *
     * @see Requirements 8.2
     */
    public function getLogs(array $filters = []): LengthAwarePaginator
    {
        $query = SystemLog::query();

        // Filter by level
        if (! empty($filters['level'])) {
            if (is_array($filters['level'])) {
                $query->whereIn('level', $filters['level']);
            } else {
                $query->where('level', $filters['level']);
            }
        }

        // Search in message and action
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('user', 'like', "%{$search}%");
            });
        }

        // Date range filters
        if (! empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get logs by level
     *
     * @param  string  $level  Log level: info, warning, error, critical
     * @param  int  $limit  Number of logs to return
     *
     * @see Requirements 8.2
     */
    public function getLogsByLevel(string $level, int $limit = 50): Collection
    {
        return SystemLog::where('level', $level)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent logs
     *
     * @param  int  $limit  Number of logs to return
     */
    public function getRecentLogs(int $limit = 20): Collection
    {
        return SystemLog::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get log statistics
     *
     * @param  Carbon  $startDate  Start date
     * @param  Carbon  $endDate  End date
     * @return array Statistics by log level
     */
    public function getLogStatistics(Carbon $startDate, Carbon $endDate): array
    {
        $levels = ['info', 'warning', 'error', 'critical'];
        $stats = [];

        foreach ($levels as $level) {
            $count = SystemLog::where('level', $level)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $stats[$level] = [
                'count' => $count,
                'color' => $this->getLevelColor($level),
            ];
        }

        return $stats;
    }

    /**
     * Clear application caches
     *
     * @param  User  $user  The user performing the action
     * @return array Result with success status and cleared caches
     *
     * @see Requirements 8.3
     */
    public function clearCache(User $user): array
    {
        $clearedCaches = [];
        $errors = [];

        try {
            // Clear application cache
            Cache::flush();
            $clearedCaches[] = 'application';
        } catch (\Exception $e) {
            $errors[] = 'application: '.$e->getMessage();
        }

        // Log the cache clear action
        $this->auditService->log(
            'cache_clear',
            'system',
            null,
            [
                'new_values' => [
                    'cleared_caches' => $clearedCaches,
                    'errors' => $errors,
                ],
            ]
        );

        return [
            'success' => empty($errors),
            'cleared' => $clearedCaches,
            'errors' => $errors,
        ];
    }

    /**
     * Get security alerts (failed login attempts and suspicious activity)
     *
     * @param  array  $filters  Filters including priority, is_resolved, per_page
     *
     * @see Requirements 8.4
     */
    public function getSecurityAlerts(array $filters = []): LengthAwarePaginator
    {
        $query = SystemAlert::where('type', 'security');

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['is_resolved'])) {
            $query->where('is_resolved', $filters['is_resolved']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get all system alerts
     *
     * @param  array  $filters  Filters including type, priority, is_resolved, per_page
     */
    public function getAlerts(array $filters = []): LengthAwarePaginator
    {
        $query = SystemAlert::with('resolver');

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['is_resolved'])) {
            $query->where('is_resolved', $filters['is_resolved']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get failed login attempts
     *
     * @param  int  $limit  Number of attempts to return
     *
     * @see Requirements 8.4
     */
    public function getFailedLoginAttempts(int $limit = 50): Collection
    {
        return SystemLog::where('action', 'login_failed')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get database health information
     *
     * @return array Database health metrics
     *
     * @see Requirements 8.5
     */
    public function getDatabaseHealth(): array
    {
        $health = [
            'connection_status' => 'unknown',
            'query_performance' => [],
            'table_sizes' => [],
        ];

        try {
            // Test connection
            DB::connection()->getPdo();
            $health['connection_status'] = 'connected';

            // Get slow queries
            $slowQueries = SlowQuery::where('is_optimized', false)
                ->orderBy('execution_time', 'desc')
                ->limit(10)
                ->get();

            $health['query_performance'] = [
                'slow_queries_count' => SlowQuery::where('is_optimized', false)->count(),
                'avg_execution_time' => round(SlowQuery::avg('execution_time') ?? 0, 3),
                'top_slow_queries' => $slowQueries,
            ];

            // Get table sizes (simplified - actual implementation would query information_schema)
            $health['table_sizes'] = $this->getTableSizes();

        } catch (\Exception $e) {
            $health['connection_status'] = 'error';
            $health['error'] = $e->getMessage();
        }

        return $health;
    }

    /**
     * Get system services status
     */
    public function getServices(): Collection
    {
        return SystemService::orderBy('name')->get();
    }

    /**
     * Get service by ID
     *
     * @param  int  $serviceId  Service ID
     */
    public function getService(int $serviceId): ?SystemService
    {
        return SystemService::find($serviceId);
    }

    /**
     * Update service status
     *
     * @param  int  $serviceId  Service ID
     * @param  string  $status  New status
     * @param  User  $user  User performing the action
     */
    public function updateServiceStatus(int $serviceId, string $status, User $user): ?SystemService
    {
        $service = SystemService::find($serviceId);

        if (! $service) {
            return null;
        }

        $oldStatus = $service->status;
        $service->update([
            'status' => $status,
            'last_checked_at' => now(),
        ]);

        // Log the action
        $this->auditService->log(
            'update',
            'system_service',
            $serviceId,
            [
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['status' => $status],
            ]
        );

        return $service->fresh();
    }

    /**
     * Resolve a system alert
     *
     * @param  int  $alertId  Alert ID
     * @param  User  $user  User resolving the alert
     * @param  string|null  $notes  Resolution notes
     */
    public function resolveAlert(int $alertId, User $user, ?string $notes = null): ?SystemAlert
    {
        $alert = SystemAlert::find($alertId);

        if (! $alert || $alert->is_resolved) {
            return null;
        }

        $alert->update([
            'is_resolved' => true,
            'resolved_by' => $user->id,
            'resolved_at' => now(),
            'resolution_notes' => $notes,
        ]);

        // Log the action
        $this->auditService->log(
            'resolve',
            'system_alert',
            $alertId,
            [
                'new_values' => [
                    'is_resolved' => true,
                    'resolution_notes' => $notes,
                ],
            ]
        );

        return $alert->fresh();
    }

    /**
     * Get performance metrics chart data
     *
     * @param  string  $metric  Metric type: cpu, memory, errors
     * @param  string  $period  Period: 'day', 'week', 'month'
     * @return array Chart data with labels and values
     */
    public function getPerformanceChartData(string $metric, string $period = 'day'): array
    {
        $labels = [];
        $values = [];

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    $values[] = $this->getMetricValue($metric, $date->startOfDay(), $date->endOfDay());
                }
                break;

            case 'month':
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('d');
                    $values[] = $this->getMetricValue($metric, $date->startOfDay(), $date->endOfDay());
                }
                break;

            case 'day':
            default:
                for ($i = 23; $i >= 0; $i--) {
                    $date = Carbon::now()->subHours($i);
                    $labels[] = $date->format('H:00');
                    $values[] = $this->getMetricValue(
                        $metric,
                        $date->copy()->startOfHour(),
                        $date->copy()->endOfHour()
                    );
                }
                break;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'metric' => $metric,
            'period' => $period,
        ];
    }

    /**
     * Get metric value for a specific time range
     *
     * @param  string  $metric  Metric type
     * @param  Carbon  $start  Start time
     * @param  Carbon  $end  End time
     */
    protected function getMetricValue(string $metric, Carbon $start, Carbon $end): float
    {
        switch ($metric) {
            case 'cpu':
                return SystemService::whereBetween('last_checked_at', [$start, $end])
                    ->avg('cpu_usage') ?? 0;

            case 'memory':
                return SystemService::whereBetween('last_checked_at', [$start, $end])
                    ->avg('memory_usage') ?? 0;

            case 'errors':
                return SystemLog::whereIn('level', ['error', 'critical'])
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

            default:
                return 0;
        }
    }

    /**
     * Get disk usage percentage
     */
    protected function getDiskUsage(): float
    {
        // In production, this would use disk_total_space() and disk_free_space()
        // For now, return a simulated value or from system services
        $service = SystemService::where('name', 'disk')->first();

        return $service ? $service->memory_usage : 45.0;
    }

    /**
     * Get system uptime in seconds
     */
    protected function getSystemUptime(): int
    {
        // In production, this would read from /proc/uptime on Linux
        // For now, return a simulated value
        $service = SystemService::orderBy('created_at', 'asc')->first();
        if ($service && $service->created_at) {
            return Carbon::now()->diffInSeconds($service->created_at);
        }

        return 86400 * 7; // Default 7 days
    }

    /**
     * Format uptime seconds to human readable string
     *
     * @param  int  $seconds  Uptime in seconds
     */
    protected function formatUptime(int $seconds): string
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = [];
        if ($days > 0) {
            $parts[] = $days.'d';
        }
        if ($hours > 0) {
            $parts[] = $hours.'h';
        }
        if ($minutes > 0 || empty($parts)) {
            $parts[] = $minutes.'m';
        }

        return implode(' ', $parts);
    }

    /**
     * Get metric status based on thresholds
     *
     * @param  float  $value  Current value
     * @param  float  $warningThreshold  Warning threshold
     * @param  float  $criticalThreshold  Critical threshold
     * @return string Status: healthy, warning, critical
     */
    protected function getMetricStatus(float $value, float $warningThreshold, float $criticalThreshold): string
    {
        if ($value >= $criticalThreshold) {
            return 'critical';
        }
        if ($value >= $warningThreshold) {
            return 'warning';
        }

        return 'healthy';
    }

    /**
     * Get color for log level
     *
     * @param  string  $level  Log level
     * @return string Color class
     */
    protected function getLevelColor(string $level): string
    {
        return match ($level) {
            'critical' => 'error',
            'error' => 'error',
            'warning' => 'warning',
            'info' => 'info',
            default => 'default',
        };
    }

    /**
     * Get table sizes (simplified implementation)
     */
    protected function getTableSizes(): array
    {
        // In production, this would query information_schema.tables
        // For now, return counts as a proxy for size
        return [
            'users' => User::count(),
            'system_logs' => SystemLog::count(),
            'system_alerts' => SystemAlert::count(),
            'slow_queries' => SlowQuery::count(),
        ];
    }
}
