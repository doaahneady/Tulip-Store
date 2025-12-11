<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DeliveryAssignment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliverySupervisorController extends Controller
{
    public function index()
    {
        // Get driver statistics
        $totalDrivers = Driver::where('is_active', true)->count();
        $availableDrivers = Driver::where('status', 'available')->where('is_active', true)->count();
        $busyDrivers = Driver::where('status', 'busy')->where('is_active', true)->count();
        $offlineDrivers = Driver::where('status', 'offline')->where('is_active', true)->count();
        
        // Get delivery statistics
        $completedToday = DeliveryAssignment::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();
        $pendingDeliveries = DeliveryAssignment::whereIn('status', ['assigned', 'picked_up', 'in_transit'])->count();
        
        // Get all active drivers with their assignments
        $drivers = Driver::where('is_active', true)
            ->with(['activeAssignments.order'])
            ->get();
        
        // Get active deliveries
        $activeDeliveries = DeliveryAssignment::with(['driver', 'order'])
            ->whereIn('status', ['assigned', 'picked_up', 'in_transit'])
            ->get();
        
        // Get orders ready for delivery (paid or cash on delivery)
        $readyOrders = Order::with(['user', 'items.product'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function($query) {
                $query->where('payment_method', 'cash')
                      ->orWhere('payment_status', 'paid');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get available drivers for assignment
        $availableDriversForAssignment = Driver::where('status', 'available')
            ->where('is_active', true)
            ->get();

        return view('delivery.supervisor-dashboard', compact(
            'totalDrivers', 
            'availableDrivers', 
            'busyDrivers', 
            'offlineDrivers',
            'completedToday',
            'pendingDeliveries',
            'drivers',
            'activeDeliveries',
            'readyOrders', 
            'availableDriversForAssignment'
        ));
    }

    public function getDriverLocations()
    {
        $drivers = Driver::where('is_active', true)
            ->with(['activeAssignments.order'])
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->get()
            ->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'phone' => $driver->phone,
                    'status' => $driver->status,
                    'status_color' => $driver->status_color,
                    'latitude' => (float) $driver->current_latitude,
                    'longitude' => (float) $driver->current_longitude,
                    'last_update' => $driver->last_location_update?->diffForHumans(),
                    'vehicle_type' => $driver->vehicle_type,
                    'vehicle_plate' => $driver->vehicle_plate,
                    'rating' => $driver->rating,
                    'total_deliveries' => $driver->total_deliveries,
                    'active_assignments' => $driver->activeAssignments->map(function ($assignment) {
                        return [
                            'id' => $assignment->id,
                            'order_id' => $assignment->order_id,
                            'status' => $assignment->status,
                            'customer_name' => $assignment->order->customer_name ?? 'N/A',
                            'delivery_address' => $assignment->order->delivery_address ?? 'N/A',
                        ];
                    }),
                ];
            });

        return response()->json($drivers);
    }

    public function updateDriverLocation(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0',
            'accuracy' => 'nullable|numeric|min:0',
        ]);

        $driver->updateLocation(
            $validated['latitude'],
            $validated['longitude'],
            $validated['speed'] ?? null,
            $validated['accuracy'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
        ]);
    }

    public function assignDriver(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'order_id' => 'required|exists:orders,id',
        ]);

        $driver = Driver::findOrFail($validated['driver_id']);
        $order = Order::findOrFail($validated['order_id']);

        // Check if driver is available
        if ($driver->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Driver is not available',
            ], 400);
        }

        // Create assignment
        $assignment = DeliveryAssignment::create([
            'driver_id' => $driver->id,
            'order_id' => $order->id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        // Update driver status
        $driver->update(['status' => 'busy']);

        // Update order status
        $order->update(['status' => 'out_for_delivery']);

        return response()->json([
            'success' => true,
            'message' => 'Driver assigned successfully',
            'assignment' => $assignment,
        ]);
    }

    public function updateDeliveryStatus(Request $request, DeliveryAssignment $assignment)
    {
        $validated = $request->validate([
            'status' => 'required|in:picked_up,in_transit,delivered,failed,cancelled',
            'notes' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'picked_up') {
            $updateData['picked_up_at'] = now();
        } elseif ($validated['status'] === 'delivered') {
            $updateData['delivered_at'] = now();
            $updateData['delivery_latitude'] = $validated['latitude'] ?? null;
            $updateData['delivery_longitude'] = $validated['longitude'] ?? null;
            
            // Update driver status to available
            $assignment->driver->update(['status' => 'available']);
            
            // Increment total deliveries
            $assignment->driver->increment('total_deliveries');
            
            // Update order status
            $assignment->order->update(['status' => 'delivered']);
        }

        if (isset($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $assignment->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Delivery status updated successfully',
        ]);
    }

    public function getDriverHistory(Driver $driver)
    {
        $history = $driver->assignments()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($history);
    }

    /**
     * Show driver management page
     */
    public function manageDrivers()
    {
        $drivers = Driver::orderBy('name')->get();
        
        $stats = [
            'total' => Driver::count(),
            'available' => Driver::where('status', 'available')->count(),
            'busy' => Driver::where('status', 'busy')->count(),
            'offline' => Driver::where('status', 'offline')->count(),
        ];
        
        return view('delivery.supervisor.manage-drivers', compact('drivers', 'stats'));
    }

    /**
     * Store a new driver
     */
    public function storeDriver(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:drivers,phone',
            'email' => 'nullable|email|unique:drivers,email',
            'license_number' => 'required|string|unique:drivers,license_number',
            'vehicle_type' => 'nullable|string',
            'vehicle_plate' => 'nullable|string',
        ]);

        $driver = Driver::create(array_merge($validated, [
            'status' => 'offline',
            'is_active' => true,
            'rating' => 5.00,
            'total_deliveries' => 0,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة السائق بنجاح',
            'driver' => $driver,
        ]);
    }

    /**
     * Update driver information
     */
    public function updateDriver(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:drivers,phone,' . $driver->id,
            'email' => 'nullable|email|unique:drivers,email,' . $driver->id,
            'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
            'vehicle_type' => 'nullable|string',
            'vehicle_plate' => 'nullable|string',
            'status' => 'nullable|in:available,busy,offline,on_break',
            'is_active' => 'nullable|boolean',
        ]);

        $driver->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات السائق بنجاح',
            'driver' => $driver,
        ]);
    }

    /**
     * Delete a driver
     */
    public function deleteDriver(Driver $driver)
    {
        $driver->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف السائق بنجاح',
        ]);
    }

    /**
     * Toggle driver active status
     */
    public function toggleDriverStatus(Driver $driver)
    {
        $driver->update(['is_active' => !$driver->is_active]);

        return response()->json([
            'success' => true,
            'message' => $driver->is_active ? 'تم تفعيل السائق' : 'تم إيقاف السائق',
            'driver' => $driver,
        ]);
    }
}
