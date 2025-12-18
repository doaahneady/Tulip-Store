<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverLocation;
use App\Models\DeliveryAssignment;
use App\Models\Order;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:driver_supervisor,super_admin']);
    }

    /**
     * Display the Driver Supervisor dashboard
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
        return [
            // Driver Overview
            'total_drivers' => Driver::where('status', 'active')->count(),
            'available_drivers' => Driver::where('availability', 'available')->count(),
            'busy_drivers' => Driver::where('availability', 'busy')->count(),
            'offline_drivers' => Driver::where('availability', 'offline')->count(),
            
            // Delivery Metrics
            'pending_deliveries' => DeliveryAssignment::where('status', 'assigned')->count(),
            'in_transit_deliveries' => DeliveryAssignment::where('status', 'in_transit')->count(),
            'completed_today' => DeliveryAssignment::where('status', 'delivered')
                ->whereDate('delivered_at', today())->count(),
            'failed_deliveries' => DeliveryAssignment::where('status', 'failed')
                ->whereDate('created_at', today())->count(),
            
            // Performance Metrics
            'avg_delivery_time' => $this->getAverageDeliveryTime(),
            'on_time_delivery_rate' => $this->getOnTimeDeliveryRate(),
            'driver_efficiency' => $this->getDriverEfficiency(),
            
            // Vehicle Status
            'total_vehicles' => $this->getTotalVehicles(),
            'vehicles_in_use' => $this->getVehiclesInUse(),
            'maintenance_due' => $this->getMaintenanceDue(),
            
            // Route Optimization
            'optimized_routes_today' => $this->getOptimizedRoutesToday(),
            'fuel_savings' => $this->getFuelSavings(),
            'distance_saved' => $this->getDistanceSaved(),
            
            // Real-time Data
            'active_driver_locations' => $this->getActiveDriverLocations(),
            'recent_deliveries' => $this->getRecentDeliveries(),
            'driver_performance' => $this->getDriverPerformance(),
        ];
    }

    /**
     * Driver Management
     */
    public function drivers(Request $request)
    {
        $drivers = Driver::with('user')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->availability, function ($query, $availability) {
                $query->where('availability', $availability);
            })
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('license_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_plate', 'like', "%{$search}%");
            })
            ->orderBy('rating', 'desc')
            ->paginate(20);

        return view('dashboards.supervisor.drivers', compact('drivers'));
    }

    /**
     * Live Location Tracking
     */
    public function liveTracking()
    {
        $activeDrivers = Driver::with(['user', 'latestLocation'])
            ->where('availability', '!=', 'offline')
            ->get();

        $deliveryAssignments = DeliveryAssignment::with(['driver.user', 'order'])
            ->whereIn('status', ['assigned', 'picked_up', 'in_transit'])
            ->get();

        return view('dashboards.supervisor.live-tracking', compact('activeDrivers', 'deliveryAssignments'));
    }

    /**
     * Delivery Assignments
     */
    public function assignments(Request $request)
    {
        $assignments = DeliveryAssignment::with(['driver.user', 'order.customer', 'assignedBy'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->driver_id, function ($query, $driverId) {
                $query->where('driver_id', $driverId);
            })
            ->when($request->date, function ($query, $date) {
                $query->whereDate('assigned_at', $date);
            })
            ->orderBy('assigned_at', 'desc')
            ->paginate(20);

        $drivers = Driver::with('user')->where('status', 'active')->get();
        $pendingOrders = Order::where('status', 'confirmed')
            ->whereDoesntHave('deliveryAssignment')
            ->get();

        return view('dashboards.supervisor.assignments', compact('assignments', 'drivers', 'pendingOrders'));
    }

    /**
     * Vehicle Management
     */
    public function vehicles()
    {
        $vehicles = Driver::with('user')
            ->select('id', 'user_id', 'vehicle_type', 'vehicle_plate', 'vehicle_info', 'status')
            ->where('status', 'active')
            ->get();

        $vehicleStats = [
            'total_vehicles' => $vehicles->count(),
            'in_use' => $vehicles->where('availability', 'busy')->count(),
            'available' => $vehicles->where('availability', 'available')->count(),
            'maintenance' => $this->getMaintenanceDue(),
        ];

        return view('dashboards.supervisor.vehicles', compact('vehicles', 'vehicleStats'));
    }

    /**
     * Maintenance Management
     */
    public function maintenance()
    {
        $maintenanceRecords = $this->getMaintenanceRecords();
        $upcomingMaintenance = $this->getUpcomingMaintenance();
        $maintenanceStats = $this->getMaintenanceStats();

        return view('dashboards.supervisor.maintenance', compact(
            'maintenanceRecords', 
            'upcomingMaintenance', 
            'maintenanceStats'
        ));
    }

    /**
     * Route Optimization
     */
    public function routes()
    {
        $optimizedRoutes = $this->getOptimizedRoutes();
        $routeStats = $this->getRouteStats();

        return view('dashboards.supervisor.routes', compact('optimizedRoutes', 'routeStats'));
    }

    /**
     * Performance Analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30d');
        
        $analytics = [
            'delivery_performance' => $this->getDeliveryPerformanceData($period),
            'driver_performance' => $this->getDriverPerformanceData($period),
            'route_efficiency' => $this->getRouteEfficiencyData($period),
            'customer_satisfaction' => $this->getCustomerSatisfactionData($period),
        ];

        return view('dashboards.supervisor.analytics', compact('analytics'));
    }

    /**
     * Get average delivery time
     */
    private function getAverageDeliveryTime()
    {
        $avgMinutes = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, picked_up_at, delivered_at)) as avg_time')
            ->value('avg_time');

        return round($avgMinutes ?? 0);
    }

    /**
     * Get on-time delivery rate
     */
    private function getOnTimeDeliveryRate()
    {
        $totalDeliveries = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())->count();
        
        $onTimeDeliveries = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->whereRaw('delivered_at <= DATE_ADD(assigned_at, INTERVAL 60 MINUTE)')
            ->count();

        return $totalDeliveries > 0 ? round(($onTimeDeliveries / $totalDeliveries) * 100, 1) : 0;
    }

    /**
     * Get driver efficiency
     */
    private function getDriverEfficiency()
    {
        // Mock calculation - would be based on deliveries per hour, fuel consumption, etc.
        return 87.5;
    }

    /**
     * Get total vehicles
     */
    private function getTotalVehicles()
    {
        return Driver::where('status', 'active')->count();
    }

    /**
     * Get vehicles in use
     */
    private function getVehiclesInUse()
    {
        return Driver::where('availability', 'busy')->count();
    }

    /**
     * Get maintenance due count
     */
    private function getMaintenanceDue()
    {
        // Mock data - would come from maintenance_schedules table
        return 3;
    }

    /**
     * Get optimized routes today
     */
    private function getOptimizedRoutesToday()
    {
        // Mock data - would come from route_optimizations table
        return 15;
    }

    /**
     * Get fuel savings
     */
    private function getFuelSavings()
    {
        // Mock data - calculated from route optimization
        return 12.5; // percentage
    }

    /**
     * Get distance saved
     */
    private function getDistanceSaved()
    {
        // Mock data - calculated from route optimization
        return 45.2; // km
    }

    /**
     * Get active driver locations
     */
    private function getActiveDriverLocations()
    {
        return DriverLocation::with('driver.user')
            ->whereHas('driver', function ($query) {
                $query->where('availability', '!=', 'offline');
            })
            ->where('recorded_at', '>=', now()->subMinutes(5))
            ->latest('recorded_at')
            ->get()
            ->groupBy('driver_id')
            ->map(function ($locations) {
                return $locations->first();
            });
    }

    /**
     * Get recent deliveries
     */
    private function getRecentDeliveries()
    {
        return DeliveryAssignment::with(['driver.user', 'order'])
            ->where('status', 'delivered')
            ->latest('delivered_at')
            ->take(10)
            ->get();
    }

    /**
     * Get driver performance
     */
    private function getDriverPerformance()
    {
        return Driver::with('user')
            ->withCount(['deliveryAssignments as deliveries_today' => function ($query) {
                $query->where('status', 'delivered')
                      ->whereDate('delivered_at', today());
            }])
            ->orderBy('rating', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get maintenance records
     */
    private function getMaintenanceRecords()
    {
        // Mock data - would come from vehicle_maintenance table
        return [];
    }

    /**
     * Get upcoming maintenance
     */
    private function getUpcomingMaintenance()
    {
        // Mock data - would come from maintenance_schedules table
        return [];
    }

    /**
     * Get maintenance statistics
     */
    private function getMaintenanceStats()
    {
        return [
            'overdue' => 2,
            'due_this_week' => 5,
            'completed_this_month' => 12,
            'avg_cost' => 450,
        ];
    }

    /**
     * Get optimized routes
     */
    private function getOptimizedRoutes()
    {
        // Mock data - would come from route_optimizations table
        return [];
    }

    /**
     * Get route statistics
     */
    private function getRouteStats()
    {
        return [
            'routes_optimized_today' => 15,
            'fuel_saved_percentage' => 12.5,
            'time_saved_minutes' => 180,
            'distance_saved_km' => 45.2,
        ];
    }

    /**
     * Get delivery performance data
     */
    private function getDeliveryPerformanceData($period)
    {
        // Mock data - would calculate based on actual delivery records
        return [
            'total_deliveries' => 450,
            'successful_deliveries' => 425,
            'failed_deliveries' => 25,
            'avg_delivery_time' => 35,
            'on_time_rate' => 94.4,
        ];
    }

    /**
     * Get driver performance data
     */
    private function getDriverPerformanceData($period)
    {
        return Driver::with('user')
            ->withCount(['deliveryAssignments as total_deliveries'])
            ->withAvg('deliveryAssignments as avg_rating')
            ->orderBy('total_deliveries', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Get route efficiency data
     */
    private function getRouteEfficiencyData($period)
    {
        // Mock data - would calculate from route optimization records
        return [
            'total_routes' => 120,
            'optimized_routes' => 108,
            'optimization_rate' => 90,
            'fuel_savings' => 15.2,
            'time_savings' => 8.5,
        ];
    }

    /**
     * Get customer satisfaction data
     */
    private function getCustomerSatisfactionData($period)
    {
        // Mock data - would come from delivery ratings
        return [
            'avg_rating' => 4.6,
            'total_ratings' => 380,
            'five_star_percentage' => 68,
            'complaints' => 12,
        ];
    }
}