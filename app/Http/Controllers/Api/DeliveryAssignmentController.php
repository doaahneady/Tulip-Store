<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryRoute;
use App\Models\Driver;
use App\Models\Notification;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Services\CrossDepartmentFlowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryAssignmentController extends Controller
{
    private function getDriverForUser()
    {
        return Driver::where('user_id', Auth::id())->first();
    }

    private function authorizeAssignment(DeliveryAssignment $assignment)
    {
        $driver = $this->getDriverForUser();
        if (! $driver || $assignment->driver_id !== $driver->id) {
            abort(403);
        }
    }

    public function pickup(Request $request, $id)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $assignment = DeliveryAssignment::findOrFail($id);
        $this->authorizeAssignment($assignment);
        $assignment->updateStatus('picked_up', $validated['notes'] ?? null);

        return response()->json([
            'success' => true,
            'assignment' => $assignment->fresh(),
        ]);
    }

    public function inTransit(Request $request, $id)
    {
        $assignment = DeliveryAssignment::findOrFail($id);
        $this->authorizeAssignment($assignment);
        $assignment->updateStatus('in_transit');

        return response()->json([
            'success' => true,
            'assignment' => $assignment->fresh(),
        ]);
    }

    public function deliver(Request $request, $id)
    {
        $validated = $request->validate([
            'signature' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $assignment = DeliveryAssignment::with('order')->findOrFail($id);
        $this->authorizeAssignment($assignment);

        $assignment->updateStatus('delivered', $validated['notes'] ?? null, $validated['signature'] ?? null);

        $order = $assignment->order;
        if ($order->payment_method === 'cash' && $order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);
        }

        CrossDepartmentFlowService::handleOrderCompletion($order->id, Auth::id());

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_delivered',
            'title' => 'Order Delivered',
            'message' => 'Your order '.$order->order_number.' has been delivered',
            'data' => ['order_id' => $order->id],
        ]);

        return response()->json([
            'success' => true,
            'assignment' => $assignment->fresh(),
            'order' => $order->fresh(),
        ]);
    }

    public function failed(Request $request, $id)
    {
        $validated = $request->validate([
            'failure_reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $assignment = DeliveryAssignment::with('order')->findOrFail($id);
        $this->authorizeAssignment($assignment);

        $assignment->update([
            'status' => 'failed',
            'failure_reason' => $validated['failure_reason'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $order = $assignment->order;
        $order->update([
            'status' => 'cancelled',
            'delivery_notes' => $validated['failure_reason'],
        ]);

        $ticket = SupportTicket::create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'user_id' => $order->user_id,
            'subject' => 'Delivery Attempt Failed for '.$order->order_number,
            'description' => $validated['failure_reason'],
            'priority' => 'high',
            'status' => 'open',
            'category' => 'delivery',
            'tags' => ['order_id:'.$order->id],
        ]);

        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'author_type' => \App\Models\User::class,
            'author_id' => $order->user_id,
            'message' => 'We attempted delivery but could not reach you. Please contact support to reschedule.',
            'is_internal' => false,
        ]);

        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'delivery_failed',
            'title' => 'Delivery Attempt Failed',
            'message' => 'We attempted delivery but could not reach you. Please contact support to reschedule.',
            'data' => ['order_id' => $order->id, 'ticket_id' => $ticket->id],
        ]);

        return response()->json([
            'success' => true,
            'assignment' => $assignment->fresh(),
            'order' => $order->fresh(),
            'ticket' => $ticket->fresh(),
        ]);
    }

    public function completeRoute(Request $request)
    {
        $validated = $request->validate([
            'route_date' => 'nullable|date',
        ]);

        $driver = $this->getDriverForUser();
        if (! $driver) {
            abort(403);
        }

        $routeDate = $validated['route_date'] ?? now()->toDateString();
        $route = DeliveryRoute::where('driver_id', $driver->id)
            ->whereDate('route_date', $routeDate)
            ->first();

        if (! $route) {
            return response()->json(['success' => false, 'message' => 'Route not found'], 404);
        }

        $route->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $driver->update(['availability' => 'available']);

        return response()->json([
            'success' => true,
            'route' => $route->fresh(),
        ]);
    }
}
