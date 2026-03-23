<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DriverDashboardController extends Controller
{
    private function ensureDriverOwnsOrder(Order $order): Driver
    {
        $employee = auth('employee')->user();
        $driver = $employee ? Driver::where('user_id', $employee->user_id)->first() : null;
        if (! $driver || (int) $order->assigned_driver_id !== (int) $driver->user_id) {
            abort(403, 'This order is not assigned to you');
        }

        return $driver;
    }

    public function index()
    {
        $employee = auth('employee')->user();
        $driver = $employee ? Driver::where('user_id', $employee->user_id)->first() : null;

        $orders = collect();
        if ($driver && $driver->user_id) {
            $orders = Order::query()
                ->where('assigned_driver_id', $driver->user_id)
                ->orderByDesc('created_at')
                ->limit(50)
                ->get();
        }

        return view('dashboards.driver.index', [
            'driver' => $driver,
            'orders' => $orders,
        ]);
    }

    public function receiveOrder(Request $request, Order $order)
    {
        $employee = auth('employee')->user();
        $driver = $employee ? Driver::where('user_id', $employee->user_id)->first() : null;
        if (! $driver) {
            return back()->with('error', 'Driver profile not found');
        }
        if ((int) $order->assigned_driver_id !== (int) $driver->user_id) {
            abort(403, 'This order is not assigned to you');
        }

        $status = (string) ($order->status ?? '');
        if (! in_array($status, ['out_for_delivery', 'delivered', 'done', 'cancelled', 'failed', 'refunded'], true)) {
            $order->status = 'out_for_delivery';
            $order->save();
        }

        return back()->with('success', 'Order marked as received');
    }

    public function markDelivered(Request $request, Order $order)
    {
        $employee = auth('employee')->user();
        $driver = $employee ? Driver::where('user_id', $employee->user_id)->first() : null;
        if (! $driver) {
            return back()->with('error', 'Driver profile not found');
        }
        if ((int) $order->assigned_driver_id !== (int) $driver->user_id) {
            abort(403, 'This order is not assigned to you');
        }

        $validated = $request->validate([
            'driver_signature' => 'required|string|max:700000',
            'customer_signature' => 'required|string|max:700000',
        ]);

        $update = [
            'status' => 'delivered',
            'customer_signature' => $validated['customer_signature'],
        ];
        if (Schema::hasColumn('orders', 'driver_delivery_signature')) {
            $update['driver_delivery_signature'] = $validated['driver_signature'];
        }
        if (Schema::hasColumn('orders', 'signed_at')) {
            $update['signed_at'] = now();
        }

        $order->update($update);

        $assignment = $order->deliveryAssignments()->orderByDesc('id')->first();
        if ($assignment instanceof DeliveryAssignment) {
            $assignment->update([
                'status' => 'delivered',
                'delivered_at' => now(),
            ]);
        }

        if (Schema::hasColumn('drivers', 'availability')) {
            $driver->update(['availability' => 'available']);
        }

        return redirect()->route('dashboard.driver.orders.show', $order)->with('success', 'تم حفظ التوقيعات وتسجيل التسليم، وتم إرسال الطلب للمالية للاعتماد.');
    }

    public function showOrder(Order $order)
    {
        $driver = $this->ensureDriverOwnsOrder($order);

        $order->load(['customer', 'user', 'store', 'items.product', 'deliveryAssignments.driver']);

        $delivery = $order->deliveryAssignments->sortByDesc('created_at')->first();
        $customerLat = $order->latitude ?? ($order->shipping_address['lat'] ?? $order->shipping_address['latitude'] ?? null);
        $customerLng = $order->longitude ?? ($order->shipping_address['lng'] ?? $order->shipping_address['longitude'] ?? null);
        if (! $customerLat && $delivery) {
            $customerLat = $delivery->delivery_latitude ?? null;
        }
        if (! $customerLng && $delivery) {
            $customerLng = $delivery->delivery_longitude ?? null;
        }

        $googleMapsUrl = ($customerLat && $customerLng)
            ? 'https://www.google.com/maps?q='.((float) $customerLat).','.((float) $customerLng)
            : null;

        return view('dashboards.driver.order', compact('order', 'driver', 'customerLat', 'customerLng', 'googleMapsUrl'));
    }
}
