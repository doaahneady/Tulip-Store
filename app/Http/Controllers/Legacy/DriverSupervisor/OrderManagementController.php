<?php

namespace App\Http\Controllers\Legacy\DriverSupervisor;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use App\Models\DeliveryAssignment;
use App\Services\CrossDepartmentFlowService;
use Illuminate\Support\Facades\DB;
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
            ->where(function ($query) {
                $query->where('payment_method', 'cash')
                    ->orWhere('payment_status', 'paid');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all active and available drivers
        $drivers = Driver::where('status', 'active')
            ->where('availability', 'available')
            ->get();

        // Get drivers as JSON for map
        $driversJson = $drivers->map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'phone' => $driver->phone,
                'status' => $driver->status,
                'lat' => $driver->current_latitude,
                'lng' => $driver->current_longitude,
                'vehicle_type' => $driver->vehicle_type,
                'vehicle_plate' => $driver->vehicle_plate,
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
                'delivery_notes' => 'nullable|string',
            ]);

            // Verify driver exists
            $driver = Driver::find($request->driver_id);
            if (! $driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'السائق غير موجود',
                ], 404);
            }

            // Do not allow assigning if driver is not available or already has active assignments
            $hasActiveAssignments = $driver->activeAssignments()->exists();
            if ($driver->availability !== 'available' || $hasActiveAssignments) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن تعيين طلب لسائق غير متاح أو لديه طلبات قيد التنفيذ',
                ], 422);
            }

            $order = Order::findOrFail($orderId);

            DB::beginTransaction();

            // Generate confirmation token
            $token = Str::random(32);

            if ($order->status === 'pending') {
                $order->update(['status' => 'confirmed']);
            }

            // Create delivery assignment through the central flow service so that
            // all dashboards and status pipelines stay in sync.
            $flowResult = CrossDepartmentFlowService::handleDriverAssignment(
                $order->id,
                $driver->id,
                auth('employee')->id() ?? auth()->id(),
                auth('employee')->id() ?? auth()->id()
            );

            /** @var DeliveryAssignment $assignment */
            $assignment = $flowResult['assignment'] ?? null;

            $order->update([
                'assigned_driver_id' => $driver->id,
                'assigned_at' => now(),
                'assigned_by' => auth()->check() ? auth()->id() : null,
                'status' => 'shipped',
                'confirmation_token' => $token,
                'delivery_notes' => $request->delivery_notes ?? '',
            ]);

            // Update driver availability to busy (cannot take more orders)
            $driver->update(['availability' => 'busy']);

            DB::commit();

            // Generate confirmation link
            $confirmationLink = url("/order/confirm/{$order->id}/{$token}");

            return response()->json([
                'success' => true,
                'message' => 'تم تعيين السائق بنجاح',
                'confirmation_link' => $confirmationLink,
                'assignment_id' => $assignment?->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: '.$e->getMessage(),
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
