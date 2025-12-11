<?php

namespace App\Http\Controllers\DriverSupervisor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderManagementController extends Controller
{
    // Show orders ready for delivery
    public function index()
    {
        $orders = Order::with(['user', 'items.product', 'assignedDriver'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereIn('payment_status', ['paid', 'pending']) // paid or cash (pending)
            ->where(function($query) {
                $query->where('payment_method', 'cash')
                      ->orWhere('payment_status', 'paid');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all active drivers with their locations
        $drivers = Driver::where('is_active', true)->get();
        
        // Get drivers as JSON for map
        $driversJson = $drivers->map(function($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'phone' => $driver->phone,
                'status' => $driver->status,
                'lat' => $driver->current_latitude,
                'lng' => $driver->current_longitude,
                'vehicle_type' => $driver->vehicle_type,
                'vehicle_plate' => $driver->vehicle_plate
            ];
        });
        
        return view('driver-supervisor.orders', compact('orders', 'drivers', 'driversJson'));
    }
    
    // Assign order to driver
    public function assignDriver(Request $request, $orderId)
    {
        try {
            $request->validate([
                'driver_id' => 'required|numeric',
                'delivery_notes' => 'nullable|string'
            ]);
            
            // Verify driver exists
            $driver = Driver::find($request->driver_id);
            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'السائق غير موجود'
                ], 404);
            }
            
            $order = Order::findOrFail($orderId);
            
            // Generate confirmation token
            $token = Str::random(32);
            
            // Disable foreign key checks temporarily
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Update order (use 'shipped' status as it's available in the enum)
            \DB::table('orders')->where('id', $orderId)->update([
                'assigned_driver_id' => $request->driver_id,
                'assigned_at' => now(),
                'assigned_by' => auth()->check() ? auth()->id() : null,
                'status' => 'shipped',
                'confirmation_token' => $token,
                'delivery_notes' => $request->delivery_notes ?? '',
                'updated_at' => now()
            ]);
            
            // Re-enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            // Update driver status to busy
            $driver->update(['status' => 'busy']);
            
            // Generate confirmation link
            $confirmationLink = url("/order/confirm/{$order->id}/{$token}");
            
            return response()->json([
                'success' => true,
                'message' => 'تم تعيين السائق بنجاح',
                'confirmation_link' => $confirmationLink
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Get order details
    public function getOrderDetails($orderId)
    {
        $order = Order::with(['user', 'items.product', 'assignedDriver'])
            ->findOrFail($orderId);
        
        return response()->json($order);
    }
}
