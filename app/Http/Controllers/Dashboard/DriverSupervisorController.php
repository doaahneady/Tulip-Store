<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverLocation;
use App\Models\DeliveryAssignment;
use App\Models\Order;
use App\Models\DeliveryRoute;
use App\Models\VehicleMaintenance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DriverSupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:driver_supervisor,logistics_coordinator']);
    }

    /**
     * Driver Supervisor Dashboard
     */
    public function index()
    {
        $metrics = $this->getSupervisorMetrics();
        return view('dashboards.supervisor.index', compact('metrics'));
    }

    /**
     * Get supervisor dashboard metrics
     */
    private function getSupervisorMetrics()
    {
        return Cache::remember('supervisor_metrics', 60, function () {
            return [
                // Driver Metrics - Using mock data for now
                'total_drivers' => 45,
                'active_drivers' => 32,
                'available_drivers' => 14,
                'busy_drivers' => 18,
                'offline_drivers' => 13,
                'drivers_on_delivery' => 18,
                
                // Delivery Metrics
                'pending_assignments' => 23,
                'active_deliveries' => 18,
                'completed_today' => 45,
                'failed_deliveries' => 2,
                
                // Order Metrics
                'orders_awaiting_assignment' => 12,
                'orders_in_transit' => 18,
                
                // Performance Metrics
                'avg_delivery_time' => 35.5,
                'on_time_delivery_rate' => 94.2,
                'driver_efficiency' => 87.3,
                
                // Vehicle Metrics
                'vehicles_in_maintenance' => 3,
                'maintenance_due' => 5,
                
                // Recent Activity
                'recent_assignments' => [],
                'active_routes' => [],
                'driver_alerts' => [],
            ];
        });
    }

    /**
     * Live Driver Tracking
     */
    public function liveTracking()
    {
        $drivers = Driver::with(['user', 'currentLocation'])
            ->where('status', 'active')
            ->get()
            ->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'name' => $driver->user->name,
                    'availability' => $driver->availability,
                    'location' => $driver->last_location ? [
                        'lat' => $driver->last_location->getLat(),
                        'lng' => $driver->last_location->getLng(),
                        'updated_at' => $driver->last_location_update,
                        'speed' => $driver->current_speed,
                        'heading' => $driver->current_heading,
                    ] : null,
                    'current_assignment' => $driver->currentAssignment,
                ];
            });

        return view('dashboards.supervisor.live-tracking', compact('drivers'));
    }

    /**
     * Get real-time driver locations (API endpoint)
     */
    public function getDriverLocations()
    {
        $drivers = Driver::with(['user'])
            ->where('status', 'active')
            ->whereNotNull('last_location')
            ->get()
            ->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'name' => $driver->user->name,
                    'availability' => $driver->availability,
                    'lat' => $driver->last_location->getLat(),
                    'lng' => $driver->last_location->getLng(),
                    'speed' => $driver->current_speed,
                    'heading' => $driver->current_heading,
                    'last_update' => $driver->last_location_update,
                    'current_order' => $driver->currentAssignment ? $driver->currentAssignment->order_id : null,
                ];
            });

        return response()->json($drivers);
    }

    /**
     * Driver Management
     */
    public function drivers(Request $request)
    {
        $drivers = Driver::with(['user', 'employee'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->availability, function ($query, $availability) {
                $query->where('availability', $availability);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboards.supervisor.drivers', compact('drivers'));
    }

    /**
     * Update driver status
     */
    public function updateDriverStatus(Request $request, Driver $driver)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended',
            'availability' => 'required|in:available,busy,offline',
            'notes' => 'nullable|string'
        ]);

        $driver->update($request->only(['status', 'availability']));

        // Log status change
        if ($request->notes) {
            // Add to driver notes or activity log
        }

        return response()->json([
            'success' => true,
            'message' => 'Driver status updated successfully'
        ]);
    }

    /**
     * Order Assignment Management
     */
    public function orderAssignment()
    {
        $pendingOrders = Order::with(['customer', 'store'])
            ->where('status', 'confirmed')
            ->whereDoesntHave('deliveryAssignment')
            ->orderBy('created_at', 'asc')
            ->get();

        $availableDrivers = Driver::with(['user'])
            ->where('status', 'active')
            ->where('availability', 'available')
            ->get();

        $activeAssignments = DeliveryAssignment::with(['order', 'driver.user'])
            ->whereIn('status', ['assigned', 'accepted', 'picked_up', 'in_transit'])
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('dashboards.supervisor.order-assignment', compact(
            'pendingOrders', 'availableDrivers', 'activeAssignments'
        ));
    }

    /**
     * Assign order to driver
     */
    public function assignOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'required|exists:drivers,id',
            'delivery_fee' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Check if order is already assigned
            $existingAssignment = DeliveryAssignment::where('order_id', $request->order_id)->first();
            if ($existingAssignment) {
                return response()->json(['success' => false, 'message' => 'Order already assigned']);
            }

            // Check if driver is available
            $driver = Driver::find($request->driver_id);
            if ($driver->availability !== 'available') {
                return response()->json(['success' => false, 'message' => 'Driver not available']);
            }

            // Create assignment
            $assignment = DeliveryAssignment::create([
                'order_id' => $request->order_id,
                'driver_id' => $request->driver_id,
                'assigned_by' => auth()->id(),
                'status' => 'assigned',
                'delivery_fee' => $request->delivery_fee,
                'driver_notes' => $request->notes,
            ]);

            // Update driver status
            $driver->update(['availability' => 'busy']);

            // Update order status
            Order::where('id', $request->order_id)->update(['status' => 'processing']);

            // Create or update delivery route
            $this->updateDeliveryRoute($request->driver_id, $request->order_id);

            DB::commit();

            // Broadcast assignment to driver
            broadcast(new \App\Events\DeliveryAssigned($assignment));

            return response()->json([
                'success' => true,
                'message' => 'Order assigned successfully',
                'assignment' => $assignment->load(['order', 'driver.user'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Assignment failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Route Optimization
     */
    public function routeOptimization()
    {
        $activeRoutes = DeliveryRoute::with(['driver.user'])
            ->where('status', 'active')
            ->whereDate('route_date', today())
            ->get();

        return view('dashboards.supervisor.route-optimization', compact('activeRoutes'));
    }

    /**
     * Optimize delivery routes
     */
    public function optimizeRoutes(Request $request)
    {
        $request->validate([
            'driver_ids' => 'required|array',
            'driver_ids.*' => 'exists:drivers,id'
        ]);

        $optimizedRoutes = [];

        foreach ($request->driver_ids as $driverId) {
            $route = $this->optimizeDriverRoute($driverId);
            if ($route) {
                $optimizedRoutes[] = $route;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Routes optimized successfully',
            'routes' => $optimizedRoutes
        ]);
    }

    /**
     * Vehicle Maintenance Management
     */
    public function vehicleMaintenance()
    {
        $maintenanceRecords = VehicleMaintenance::with(['driver.user'])
            ->orderBy('maintenance_date', 'desc')
            ->paginate(20);

        $upcomingMaintenance = VehicleMaintenance::with(['driver.user'])
            ->where('next_due_date', '<=', now()->addDays(30))
            ->where('status', 'scheduled')
            ->orderBy('next_due_date', 'asc')
            ->get();

        return view('dashboards.supervisor.vehicle-maintenance', compact(
            'maintenanceRecords', 'upcomingMaintenance'
        ));
    }

    /**
     * Log vehicle maintenance
     */
    public function logMaintenance(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'type' => 'required|in:routine,repair,inspection,emergency',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'maintenance_date' => 'required|date',
            'next_due_date' => 'nullable|date|after:maintenance_date',
            'odometer_reading' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ]);

        VehicleMaintenance::create($request->all() + [
            'status' => 'completed'
        ]);

        return redirect()->route('supervisor.vehicle-maintenance')
            ->with('success', 'Maintenance record logged successfully!');
    }

    /**
     * Delivery Proof Review
     */
    public function deliveryProof()
    {
        $completedDeliveries = DeliveryAssignment::with(['order', 'driver.user'])
            ->where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->orderBy('delivered_at', 'desc')
            ->paginate(20);

        return view('dashboards.supervisor.delivery-proof', compact('completedDeliveries'));
    }

    /**
     * Verify delivery
     */
    public function verifyDelivery(Request $request, DeliveryAssignment $assignment)
    {
        $request->validate([
            'verified' => 'required|boolean',
            'notes' => 'nullable|string'
        ]);

        $assignment->update([
            'verified_at' => $request->verified ? now() : null,
            'verified_by' => $request->verified ? auth()->id() : null,
            'verification_notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delivery ' . ($request->verified ? 'verified' : 'rejected') . ' successfully'
        ]);
    }

    /**
     * Helper Methods
     */
    private function getAverageDeliveryTime()
    {
        $avgMinutes = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, assigned_at, delivered_at)) as avg_time')
            ->value('avg_time');

        return $avgMinutes ? round($avgMinutes) : 0;
    }

    private function getOnTimeDeliveryRate()
    {
        $totalDeliveries = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();

        if ($totalDeliveries === 0) return 100;

        $onTimeDeliveries = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->whereRaw('delivered_at <= DATE_ADD(assigned_at, INTERVAL 60 MINUTE)')
            ->count();

        return round(($onTimeDeliveries / $totalDeliveries) * 100, 1);
    }

    private function getDriverEfficiency()
    {
        // Mock calculation - would implement actual efficiency metrics
        return 87.5; // 87.5% efficiency
    }

    private function getRecentAssignments()
    {
        return DeliveryAssignment::with(['order', 'driver.user'])
            ->latest()
            ->take(5)
            ->get();
    }

    private function getActiveRoutes()
    {
        return DeliveryRoute::with(['driver.user'])
            ->where('status', 'active')
            ->whereDate('route_date', today())
            ->get();
    }

    private function getDriverAlerts()
    {
        $alerts = [];

        // Drivers offline for too long
        $offlineDrivers = Driver::where('availability', 'offline')
            ->where('last_location_update', '<', now()->subHours(2))
            ->count();

        if ($offlineDrivers > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$offlineDrivers} drivers have been offline for over 2 hours"
            ];
        }

        // Overdue deliveries
        $overdueDeliveries = DeliveryAssignment::whereIn('status', ['assigned', 'accepted', 'picked_up'])
            ->where('assigned_at', '<', now()->subHours(2))
            ->count();

        if ($overdueDeliveries > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$overdueDeliveries} deliveries are overdue"
            ];
        }

        return $alerts;
    }

    private function updateDeliveryRoute($driverId, $orderId)
    {
        $order = Order::find($orderId);
        $route = DeliveryRoute::firstOrCreate([
            'driver_id' => $driverId,
            'route_date' => today(),
        ], [
            'waypoints' => [],
            'optimized_sequence' => [],
            'status' => 'planned'
        ]);

        // Add order to route waypoints
        $waypoints = $route->waypoints;
        $waypoints[] = [
            'order_id' => $orderId,
            'address' => $order->shipping_address,
            'coordinates' => $this->geocodeAddress($order->shipping_address),
        ];

        $route->update([
            'waypoints' => $waypoints,
            'status' => 'active'
        ]);

        return $route;
    }

    private function optimizeDriverRoute($driverId)
    {
        $route = DeliveryRoute::where('driver_id', $driverId)
            ->where('route_date', today())
            ->first();

        if (!$route || empty($route->waypoints)) {
            return null;
        }

        // Simple optimization - would use actual routing algorithm
        $optimizedSequence = $this->calculateOptimalSequence($route->waypoints);
        
        $route->update([
            'optimized_sequence' => $optimizedSequence,
            'total_distance' => $this->calculateTotalDistance($optimizedSequence),
            'estimated_duration' => $this->calculateEstimatedDuration($optimizedSequence),
        ]);

        return $route;
    }

    private function geocodeAddress($address)
    {
        // Mock geocoding - would integrate with actual geocoding service
        return [
            'lat' => 40.7128 + (rand(-1000, 1000) / 10000),
            'lng' => -74.0060 + (rand(-1000, 1000) / 10000)
        ];
    }

    private function calculateOptimalSequence($waypoints)
    {
        // Simple nearest neighbor algorithm - would use more sophisticated routing
        $sequence = [];
        $remaining = collect($waypoints)->keyBy('order_id');
        $current = ['coordinates' => ['lat' => 40.7128, 'lng' => -74.0060]]; // Starting point

        while ($remaining->isNotEmpty()) {
            $nearest = $remaining->sortBy(function ($waypoint) use ($current) {
                return $this->calculateDistance(
                    $current['coordinates'],
                    $waypoint['coordinates']
                );
            })->first();

            $sequence[] = $nearest['order_id'];
            $current = $nearest;
            $remaining->forget($nearest['order_id']);
        }

        return $sequence;
    }

    private function calculateDistance($point1, $point2)
    {
        // Haversine formula for distance calculation
        $lat1 = deg2rad($point1['lat']);
        $lon1 = deg2rad($point1['lng']);
        $lat2 = deg2rad($point2['lat']);
        $lon2 = deg2rad($point2['lng']);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = 6371 * $c; // Earth's radius in kilometers

        return $distance;
    }

    private function calculateTotalDistance($sequence)
    {
        // Mock calculation - would calculate actual route distance
        return count($sequence) * 5.2; // Average 5.2 km per stop
    }

    private function calculateEstimatedDuration($sequence)
    {
        // Mock calculation - would calculate actual travel time
        return count($sequence) * 15; // Average 15 minutes per stop
    }
}