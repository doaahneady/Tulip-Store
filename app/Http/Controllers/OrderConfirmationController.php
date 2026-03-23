<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderConfirmationController extends Controller
{
    public function show($orderId, $token)
    {
        $order = Order::with(['items.product', 'assignedDriver'])
            ->where('id', $orderId)
            ->where('confirmation_token', $token)
            ->firstOrFail();

        // Check if already confirmed
        if ($order->confirmed_at) {
            return view('order-confirmed-already', compact('order'));
        }

        return view('order-confirmation', compact('order'));
    }

    public function confirm(Request $request, $orderId, $token)
    {
        try {
            $request->validate([
                'signature' => 'required|string',
            ]);

            $order = Order::where('id', $orderId)
                ->where('confirmation_token', $token)
                ->firstOrFail();

            // Check if already confirmed
            if ($order->confirmed_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم تأكيد هذا الطلب مسبقاً',
                ]);
            }

            // Get the assigned driver ID before updating
            $driverId = $order->assigned_driver_id;

            $order->update([
                'confirmed_at' => now(),
                'customer_signature' => $request->signature,
                'status' => 'delivered',
            ]);

            // Update driver status back to 'available' (assigned_driver_id is users.id)
            if ($driverId) {
                Driver::where('user_id', $driverId)->update([
                    'status' => 'available',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تأكيد استلام الطلب بنجاح',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: '.$e->getMessage(),
            ], 500);
        }
    }
}
