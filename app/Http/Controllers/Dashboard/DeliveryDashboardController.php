<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\AuditService;
use App\Services\Dashboard\DeliveryDashboardService;
use App\Services\Dashboard\ExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Delivery Dashboard Controller
 * 
 * Handles all delivery supervisor dashboard functionality including:
 * - Dashboard overview with delivery KPIs
 * - Driver management and tracking
 * - Delivery assignments
 * - Real-time map tracking
 * 
 * @see Requirements 11.1, 11.2, 11.5
 */
class DeliveryDashboardController extends Controller
{
    public function __construct(
        protected DeliveryDashboardService $deliveryService,
        protected AuditService $auditService,
        protected ExportService $exportService
    ) {
        // Apply delivery supervisor role middleware to all methods
        $this->middleware('dashboard.role:delivery_supervisor,admin');
    }

    /**
     * Display the delivery dashboard overview
     * Shows KPI cards, map with driver locations, and recent activity
     * 
     * @see Requirements 11.1
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'week');

        $data = [
            'kpis' => $this->deliveryService->getKPIMetrics(),
            'activeDrivers' => $this->deliveryService->getActiveDriverLocations(),
            'deliveryChart' => $this->deliveryService->getDeliveryChartData($period),
            'topDrivers' => $this->deliveryService->getTopDrivers(5, $period),
            'recentAssignments' => $this->deliveryService->getRecentAssignments(5),
            'inTransitDeliveries' => $this->deliveryService->getInTransitDeliveries(),
            'pendingCount' => $this->deliveryService->getPendingDeliveries(['per_page' => 1])->total(),
            'period' => $period,
        ];

        return view('dashboard.delivery.index', $data);
    }

    /**
     * Display drivers page
     * Shows paginated list of drivers with filters
     * 
     * @see Requirements 11.1
     */
    public function drivers(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'status' => $request->get('status'),
            'is_active' => $request->has('is_active') ? (bool) $request->get('is_active') : null,
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'name'),
            'sort_direction' => $request->get('sort_direction', 'asc'),
        ];

        $drivers = $this->deliveryService->getDrivers($filters);

        return view('dashboard.delivery.drivers', [
            'drivers' => $drivers,
            'filters' => $filters,
        ]);
    }

    /**
     * Display single driver details
     * 
     * @param int $driverId The driver ID
     */
    public function showDriver(int $driverId)
    {
        $driver = $this->deliveryService->getDriver($driverId);

        if (!$driver) {
            return redirect()->route('dashboard.delivery.drivers')
                ->with('error', __('Driver not found.'));
        }

        $performance = $this->deliveryService->getDriverPerformance($driverId, 'month');
        $locationHistory = $this->deliveryService->getDriverLocationHistory($driverId, Carbon::now()->subDay());

        return view('dashboard.delivery.driver-show', [
            'driver' => $driver,
            'performance' => $performance,
            'locationHistory' => $locationHistory,
        ]);
    }

    /**
     * Update driver status
     */
    public function updateDriverStatus(Request $request, int $driverId)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:available,busy,on_break,offline',
        ]);

        $driver = $this->deliveryService->updateDriverStatus($driverId, $validated['status']);

        if (!$driver) {
            return redirect()->back()->with('error', __('Driver not found.'));
        }

        return redirect()->back()->with('success', __('Driver status updated successfully.'));
    }

    /**
     * Display assignments page
     * Shows delivery assignments with filters
     * 
     * @see Requirements 11.2
     */
    public function assignments(Request $request)
    {
        $filters = [
            'per_page' => $request->get('per_page', 25),
            'driver_id' => $request->get('driver_id'),
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'assigned_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $assignments = $this->deliveryService->getAssignments($filters);
        $pendingDeliveries = $this->deliveryService->getPendingDeliveries(['per_page' => 10]);
        $availableDrivers = $this->deliveryService->getAvailableDrivers();
        $allDrivers = $this->deliveryService->getDrivers(['per_page' => 1000, 'is_active' => true]);

        return view('dashboard.delivery.assignments', [
            'assignments' => $assignments,
            'pendingDeliveries' => $pendingDeliveries,
            'availableDrivers' => $availableDrivers,
            'allDrivers' => $allDrivers,
            'filters' => $filters,
        ]);
    }

    /**
     * Assign a driver to an order
     * 
     * @see Requirements 11.3
     */
    public function assignDriver(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'driver_id' => 'required|integer|exists:drivers,id',
        ]);

        $assignment = $this->deliveryService->assignDriver(
            $validated['order_id'],
            $validated['driver_id'],
            Auth::id()
        );

        if (!$assignment) {
            return redirect()->back()->with('error', __('Failed to assign driver. Order may already be assigned.'));
        }

        return redirect()->back()->with('success', __('Driver assigned successfully.'));
    }

    /**
     * Update assignment status
     * 
     * @see Requirements 11.5
     */
    public function updateAssignmentStatus(Request $request, int $assignmentId)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:assigned,picked_up,in_transit,delivered,failed,cancelled',
            'notes' => 'nullable|string|max:500',
            'customer_signature' => 'nullable|string',
        ]);

        $assignment = $this->deliveryService->updateAssignmentStatus(
            $assignmentId,
            $validated['status'],
            [
                'notes' => $validated['notes'] ?? null,
                'customer_signature' => $validated['customer_signature'] ?? null,
            ]
        );

        if (!$assignment) {
            return redirect()->back()->with('error', __('Assignment not found.'));
        }

        return redirect()->back()->with('success', __('Assignment status updated successfully.'));
    }

    /**
     * Display real-time tracking page
     * Shows map with all active drivers and deliveries
     * 
     * @see Requirements 11.1, 11.5
     */
    public function tracking(Request $request)
    {
        $activeDrivers = $this->deliveryService->getActiveDriverLocations();
        $inTransitDeliveries = $this->deliveryService->getInTransitDeliveries();
        $pendingDeliveries = $this->deliveryService->getPendingDeliveries(['per_page' => 50]);

        return view('dashboard.delivery.tracking', [
            'activeDrivers' => $activeDrivers,
            'inTransitDeliveries' => $inTransitDeliveries,
            'pendingDeliveries' => $pendingDeliveries,
        ]);
    }

    /**
     * API endpoint for getting driver locations (for AJAX polling)
     * 
     * @see Requirements 11.1
     */
    public function getDriverLocations()
    {
        $drivers = $this->deliveryService->getActiveDriverLocations();

        return response()->json([
            'success' => true,
            'drivers' => $drivers,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * API endpoint for getting in-transit deliveries (for AJAX polling)
     * 
     * @see Requirements 11.5
     */
    public function getInTransitDeliveries()
    {
        $deliveries = $this->deliveryService->getInTransitDeliveries();

        return response()->json([
            'success' => true,
            'deliveries' => $deliveries,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Export drivers to CSV
     */
    public function exportDrivers(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'is_active' => $request->has('is_active') ? (bool) $request->get('is_active') : null,
            'per_page' => 10000,
        ];

        $drivers = $this->deliveryService->getDrivers($filters);

        $columns = [
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'vehicle_type' => 'Vehicle Type',
            'vehicle_plate' => 'Vehicle Plate',
            'status' => 'Status',
            'total_deliveries' => 'Total Deliveries',
            'rating' => 'Rating',
            'is_active' => 'Active',
        ];

        $this->auditService->log(
            'export',
            'driver',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $drivers->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $drivers->getCollection(),
            $columns,
            'drivers_' . date('Y-m-d') . '.csv'
        );
    }

    /**
     * Export assignments to CSV
     */
    public function exportAssignments(Request $request)
    {
        $filters = [
            'driver_id' => $request->get('driver_id'),
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => 10000,
        ];

        $assignments = $this->deliveryService->getAssignments($filters);

        $columns = [
            'order.order_number' => 'Order Number',
            'driver.name' => 'Driver Name',
            'status' => 'Status',
            'assigned_at' => 'Assigned At',
            'picked_up_at' => 'Picked Up At',
            'delivered_at' => 'Delivered At',
            'order.recipient_name' => 'Recipient',
            'order.address_note' => 'Address',
        ];

        $this->auditService->log(
            'export',
            'delivery_assignment',
            null,
            [
                'new_values' => [
                    'filters' => $filters,
                    'record_count' => $assignments->total(),
                ],
            ]
        );

        return $this->exportService->exportToCSV(
            $assignments->getCollection(),
            $columns,
            'delivery_assignments_' . date('Y-m-d') . '.csv'
        );
    }
}
