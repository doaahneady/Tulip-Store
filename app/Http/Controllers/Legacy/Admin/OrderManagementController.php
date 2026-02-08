<?php

namespace App\Http\Controllers\Legacy\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $query = Order::with(['user', 'items.product']);

        // Filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%'.$request->search.'%')
                    ->orWhere('recipient_name', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الطلب بنجاح',
        ]);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الدفع بنجاح',
        ]);
    }

    public function addNote(Request $request, $id)
    {
        // Check if user is admin
        if (! auth()->user()->is_admin) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'note' => 'required|string',
        ]);

        $order = Order::findOrFail($id);

        // Add note to order (you might want to create a notes table)
        $notes = json_decode($order->admin_notes ?? '[]', true);
        $notes[] = [
            'note' => $request->note,
            'admin' => auth()->user()->name,
            'date' => now()->format('Y-m-d H:i:s'),
        ];
        $order->admin_notes = json_encode($notes);
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الملاحظة بنجاح',
        ]);
    }
}
