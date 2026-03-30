<?php

namespace App\Services\Dashboard;

use App\Models\DeliveryAssignment;
use App\Models\Driver;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Delivery Dashboard Service
 *
 * Provides driver location tracking, delivery assignment, and performance metrics.
 *
 * @see Requirements 11.1, 11.3, 11.4
 */
class DeliveryDashboardService
{
    public function __construct(
        protected MetricsService $metricsService,
        protected AuditService $auditService
    ) {}

    /**
     * Get Delivery KPI metrics
     *
     * @return array Array containing active_drivers, pending_deliveries, completed_today, avg_delivery_time
     *
     * @see Requirements 11.1
     */
    public function getKPIMetrics(): array
    {
        $today = Carbon::today();

        // Active drivers (available or busy)
        $activeDrivers = Driver::where('status', 'active')
            ->whereIn('availability', ['available', 'busy'])
            ->count();

        $totalDrivers = Driver::where('status', 'active')->count();

        // Pending deliveries (orders ready for dispatch)
        $pendingDeliveries = Order::whereIn('status', ['processing', 'ready'])
            ->whereNull('assigned_driver_id')
            ->count();

        // Completed deliveries today
        $completedToday = DeliveryAssignment::whereDate('delivered_at', $today)
            ->where('status', 'delivered')
            ->count();

        // Average delivery time today (in minutes)
        $avgDeliveryTime = DeliveryAssignment::whereDate('delivered_at', $today)
            ->where('status', 'delivered')
            ->whereNotNull('assigned_at')
            ->whereNotNull('delivered_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, assigned_at, delivered_at)) as avg_time')
            ->value('avg_time');

        // In-transit deliveries
        $inTransit = DeliveryAssignment::whereIn('status', ['assigned', 'picked_up', 'in_transit'])
            ->count();

        return [
            'active_drivers' => [
                'value' => $activeDrivers,
                'total' => $totalDrivers,
                'percentage' => $totalDrivers > 0
                    ? round(($activeDrivers / $totalDrivers) * 100, 1)
                    : 0,
            ],
            'pending_deliveries' => [
                'value' => $pendingDeliveries,
            ],
            'completed_today' => [
                'value' => $completedToday,
            ],
            'avg_delivery_time' => [
                'value' => $avgDeliveryTime ? round($avgDeliveryTime) : 0,
                'unit' => 'min',
            ],
            'in_transit' => [
                'value' => $inTransit,
            ],
        ];
    }

    /**
     * Get all drivers with their current status and location
     *
     * @param  array  $filters  Filters including status, search, per_page
     *
     * @see Requirements 11.1
     */
    public function getDrivers(array $filters = []): LengthAwarePaginator
    {
        $query = Driver::query();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['is_active'])) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'is_active')) {
                $query->where('is_active', $filters['is_active']);
            } else {
                $query->where('status', $filters['is_active'] ? 'active' : 'inactive');
            }
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('vehicle_plate', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get active drivers with their current locations for map display
     *
     * @see Requirements 11.1
     */
    public function getActiveDriverLocations(): Collection
    {
        $query = Driver::query();
        if (\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'is_active')) {
            $query->where('is_active', true);
        }
        $query->whereIn('availability', ['available', 'busy'])
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->select([
                'id',
                'name',
                'phone',
                'status',
                'vehicle_type',
                'vehicle_plate',
                'current_latitude',
                'current_longitude',
                'last_location_update',
            ])
            ->with(['activeAssignments.order:id,order_number,recipient_name,address_note'])
            ->get();
    }

    /**
     * Get driver by ID with full details
     */
    public function getDriver(int $driverId): ?Driver
    {
        return Driver::with(['assignments' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(20);
        }, 'assignments.order'])
            ->find($driverId);
    }

    /**
     * Update driver location
     *
     * @see Requirements 11.1
     */
    public function updateDriverLocation(
        int $driverId,
        float $latitude,
        float $longitude,
        ?float $speed = null,
        ?float $accuracy = null
    ): ?Driver {
        $driver = Driver::find($driverId);

        if (! $driver) {
            return null;
        }

        $driver->updateLocation($latitude, $longitude, $speed, $accuracy);

        return $driver->fresh();
    }

    /**
     * Get driver location history
     */
    public function getDriverLocationHistory(int $driverId, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $query = DriverLocation::where('driver_id', $driverId);

        if ($from) {
            $query->where('recorded_at', '>=', $from);
        }

        if ($to) {
            $query->where('recorded_at', '<=', $to);
        }

        return $query->orderBy('recorded_at', 'desc')->limit(100)->get();
    }

    /**
     * Get pending deliveries (orders ready for dispatch)
     *
     * @see Requirements 11.2
     */
    public function getPendingDeliveries(array $filters = []): LengthAwarePaginator
    {
        $query = Order::whereIn('status', ['processing', 'ready'])
            ->whereNull('assigned_driver_id')
            ->with(['user:id,name,phone', 'items']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Get delivery assignments with filters
     *
     * @see Requirements 11.2
     */
    public function getAssignments(array $filters = []): LengthAwarePaginator
    {
        $query = DeliveryAssignment::with(['driver:id,name,phone,status', 'order:id,order_number,recipient_name,phone,address_note,total']);

        if (! empty($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['date_from'])) {
            $query->where('assigned_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('assigned_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'assigned_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $filters['per_page'] ?? 25;

        return $query->paginate($perPage);
    }

    /**
     * Assign a driver to an order
     *
     * @param  int  $assignedBy  User ID who made the assignment
     *
     * @see Requirements 11.3
     */
    public function assignDriver(int $orderId, int $driverId, int $assignedBy): ?DeliveryAssignment
    {
        $order = Order::find($orderId);
        $driver = Driver::find($driverId);

        if (! $order || ! $driver) {
            return null;
        }

        if ($order->assigned_driver_id) {
            return null; // Already assigned
        }

        DB::beginTransaction();

        try {
            // Create delivery assignment - only include assigned_by if it's a valid user
            $assignmentData = [
                'driver_id' => $driverId,
                'order_id' => $orderId,
                'status' => 'assigned',
                'assigned_at' => now(),
                'delivery_latitude' => $order->latitude,
                'delivery_longitude' => $order->longitude,
            ];
            
            // Only add assigned_by if the user exists in the users table
            if ($assignedBy && \App\Models\User::find($assignedBy)) {
                $assignmentData['assigned_by'] = $assignedBy;
            }
            
            $assignment = DeliveryAssignment::create($assignmentData);

            // orders.assigned_driver_id references users.id (driver login), not drivers.id
            $driverUserId = $driver->user_id;
            if (! $driverUserId) {
                DB::rollBack();

                return null;
            }

            // Update order with driver assignment
            $order->update([
                'assigned_driver_id' => $driverUserId,
                'assigned_at' => now(),
                'assigned_by' => $assignedBy,
                'status' => 'assigned',
            ]);

            // Update driver availability to busy
            if (Schema::hasColumn('drivers', 'availability')) {
                $driver->update(['availability' => 'busy']);
            }

            // Log the assignment
            $this->auditService->log(
                'create',
                'delivery_assignment',
                $assignment->id,
                [
                    'new_values' => [
                        'order_id' => $orderId,
                        'driver_id' => $driverId,
                        'assigned_by' => $assignedBy,
                    ],
                ]
            );

            DB::commit();

            return $assignment->fresh(['driver', 'order']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update delivery assignment status
     *
     * @see Requirements 11.5
     */
    public function updateAssignmentStatus(int $assignmentId, string $status, array $additionalData = []): ?DeliveryAssignment
    {
        $assignment = DeliveryAssignment::find($assignmentId);

        if (! $assignment) {
            return null;
        }

        $updateData = ['status' => $status];

        switch ($status) {
            case 'picked_up':
                $updateData['picked_up_at'] = now();
                break;
            case 'delivered':
                $updateData['delivered_at'] = now();
                if (isset($additionalData['customer_signature'])) {
                    $updateData['customer_signature'] = $additionalData['customer_signature'];
                }
                break;
        }

        if (isset($additionalData['notes'])) {
            $updateData['notes'] = $additionalData['notes'];
        }

        DB::beginTransaction();

        try {
            $oldStatus = $assignment->status;
            $assignment->update($updateData);

            // Update order status
            if ($assignment->order) {
                $orderStatus = match ($status) {
                    'picked_up' => 'picked_up',
                    'in_transit' => 'in_transit',
                    'delivered' => 'delivered',
                    'failed', 'cancelled' => 'failed',
                    default => $assignment->order->status,
                };
                $assignment->order->update(['status' => $orderStatus]);
            }

            // Update driver status if delivery completed or failed
            if (in_array($status, ['delivered', 'failed', 'cancelled'])) {
                $driver = $assignment->driver;
                if ($driver) {
                    // Check if driver has other active assignments
                    $activeAssignments = DeliveryAssignment::where('driver_id', $driver->id)
                        ->whereIn('status', ['assigned', 'picked_up', 'in_transit'])
                        ->count();

                    if ($activeAssignments === 0) {
                        $driver->update(['status' => 'available']);
                    }

                    // Update driver stats for completed deliveries
                    if ($status === 'delivered') {
                        $driver->increment('total_deliveries');
                    }
                }
            }

            // Log the status change
            $this->auditService->log(
                'update',
                'delivery_assignment',
                $assignmentId,
                [
                    'old_values' => ['status' => $oldStatus],
                    'new_values' => ['status' => $status],
                ]
            );

            DB::commit();

            return $assignment->fresh(['driver', 'order']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get driver performance metrics
     *
     * @param  int|null  $driverId  Specific driver or null for all
     * @param  string  $period  'week', 'month', 'year'
     *
     * @see Requirements 11.4
     */
    public function getDriverPerformance(?int $driverId = null, string $period = 'month'): array
    {
        $startDate = match ($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };

        $query = DeliveryAssignment::where('assigned_at', '>=', $startDate);

        if ($driverId) {
            $query->where('driver_id', $driverId);
        }

        $assignments = $query->get();

        $completed = $assignments->where('status', 'delivered');
        $failed = $assignments->whereIn('status', ['failed', 'cancelled']);

        // Calculate average delivery time
        $avgDeliveryTime = $completed
            ->filter(fn ($a) => $a->assigned_at && $a->delivered_at)
            ->avg(fn ($a) => $a->assigned_at->diffInMinutes($a->delivered_at));

        return [
            'total_assignments' => $assignments->count(),
            'completed' => $completed->count(),
            'failed' => $failed->count(),
            'success_rate' => $assignments->count() > 0
                ? round(($completed->count() / $assignments->count()) * 100, 1)
                : 0,
            'avg_delivery_time' => $avgDeliveryTime ? round($avgDeliveryTime) : 0,
            'period' => $period,
        ];
    }

    /**
     * Get delivery chart data
     *
     * @param  string  $period  'week' or 'month'
     */
    public function getDeliveryChartData(string $period = 'week'): array
    {
        $labels = [];
        $completed = [];
        $failed = [];

        $days = $period === 'week' ? 7 : 30;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format($period === 'week' ? 'D' : 'd');

            $dayAssignments = DeliveryAssignment::whereDate('assigned_at', $date)->get();
            $completed[] = $dayAssignments->where('status', 'delivered')->count();
            $failed[] = $dayAssignments->whereIn('status', ['failed', 'cancelled'])->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Completed', 'data' => $completed, 'color' => 'green'],
                ['label' => 'Failed', 'data' => $failed, 'color' => 'red'],
            ],
        ];
    }

    /**
     * Get top performing drivers
     *
     * @see Requirements 11.4
     */
    public function getTopDrivers(int $limit = 5, string $period = 'month'): Collection
    {
        $startDate = match ($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };

        return Driver::where('status', 'active')
            ->withCount(['assignments as completed_deliveries' => function ($query) use ($startDate) {
                $query->where('status', 'delivered')
                    ->where('delivered_at', '>=', $startDate);
            }])
            ->orderByDesc('completed_deliveries')
            ->limit($limit)
            ->get();
    }

    /**
     * Update driver status
     */
    public function updateDriverStatus(int $driverId, string $status): ?Driver
    {
        $driver = Driver::find($driverId);

        if (! $driver) {
            return null;
        }

        $oldStatus = $driver->status;
        $driver->update(['status' => $status]);

        $this->auditService->log(
            'update',
            'driver',
            $driverId,
            [
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['status' => $status],
            ]
        );

        return $driver->fresh();
    }

    /**
     * Get available drivers for assignment
     */
    public function getAvailableDrivers(): Collection
    {
        return Driver::where('status', 'active')
            ->where('availability', 'available')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get recent assignments
     */
    public function getRecentAssignments(int $limit = 10): Collection
    {
        return DeliveryAssignment::with(['driver:id,name,phone', 'order:id,order_number,recipient_name'])
            ->orderBy('assigned_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get in-transit deliveries
     */
    public function getInTransitDeliveries(): Collection
    {
        return DeliveryAssignment::with(['driver:id,name,phone,current_latitude,current_longitude', 'order:id,order_number,recipient_name,address_note,latitude,longitude'])
            ->whereIn('status', ['assigned', 'picked_up', 'in_transit'])
            ->orderBy('assigned_at', 'asc')
            ->get();
    }
}
