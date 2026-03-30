<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryRoute;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Services\CrossDepartmentFlowService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $driver = $this->getDriverForUser();
        if (! $driver) {
            abort(403);
        }
        $status = $request->query('status');
        $query = DeliveryAssignment::with(['order'])->where('driver_id', $driver->id);
        if ($status) {
            $query->where('status', $status);
        }
        $assignments = $query->orderBy('assigned_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'assignments' => $assignments,
        ]);
    }
    private function getDriverForUser()
    {
        $user = Auth::user();
        $userId = null;
        if ($user instanceof Employee) {
            $userId = $user->user_id;
        } else {
            $userId = $user?->id;
        }
        if (! $userId) {
            return null;
        }
        return Driver::where('user_id', $userId)->first();
    }

    private function getActorUserIdForFlow(): ?int
    {
        $user = Auth::user();
        if ($user instanceof Employee) {
            return $user->user_id ? (int) $user->user_id : null;
        }
        return Auth::id();
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
        if ($assignment->status === 'picked_up') {
            return response()->json([
                'success' => true,
                'assignment' => $assignment->fresh(),
            ]);
        }
        $assignment->updateStatus('picked_up', $validated['notes'] ?? null);
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'user_id')) {
            $order = $assignment->order ?? $assignment->loadMissing('order')->order;
            if ($order?->user_id) {
                $payload = [
                    'user_id' => $order->user_id,
                ];
                if (Schema::hasColumn('notifications', 'type')) {
                    $payload['type'] = 'order_picked_up';
                }
                if (Schema::hasColumn('notifications', 'title')) {
                    $payload['title'] = 'Order Picked Up';
                }
                if (Schema::hasColumn('notifications', 'message')) {
                    $payload['message'] = 'Your order '.$order->order_number.' has been picked up';
                }
                if (Schema::hasColumn('notifications', 'icon')) {
                    $payload['icon'] = 'fa-box';
                }
                if (Schema::hasColumn('notifications', 'color')) {
                    $payload['color'] = 'blue';
                }
                if (Schema::hasColumn('notifications', 'link')) {
                    $payload['link'] = '/profile';
                }
                if (Schema::hasColumn('notifications', 'data')) {
                    $payload['data'] = json_encode(['order_id' => $order->id, 'assignment_id' => $assignment->id]);
                }
                \DB::table('notifications')->insert($payload + ['created_at' => now(), 'updated_at' => now()]);
            }
        }
        if (class_exists(\App\Events\Dashboard\DashboardUpdated::class)) {
            $orderId = $assignment->order_id;
            foreach (['admin', 'it', 'finance', 'cs', 'hr', 'supervisor', 'vendor'] as $dash) {
                event(new \App\Events\Dashboard\DashboardUpdated($dash, [
                    'type' => 'delivery_assignment_status_changed',
                    'order_id' => $orderId,
                    'assignment_id' => $assignment->id,
                    'status' => 'picked_up',
                    'timestamp' => now()->toIso8601String(),
                ]));
            }
        }

        return response()->json([
            'success' => true,
            'assignment' => $assignment->fresh(),
        ]);
    }

    public function inTransit(Request $request, $id)
    {
        $assignment = DeliveryAssignment::findOrFail($id);
        $this->authorizeAssignment($assignment);
        if ($assignment->status === 'in_transit') {
            return response()->json([
                'success' => true,
                'assignment' => $assignment->fresh(),
            ]);
        }
        $assignment->updateStatus('in_transit');
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'user_id')) {
            $order = $assignment->order ?? $assignment->loadMissing('order')->order;
            if ($order?->user_id) {
                $payload = [
                    'user_id' => $order->user_id,
                ];
                if (Schema::hasColumn('notifications', 'type')) {
                    $payload['type'] = 'order_in_transit';
                }
                if (Schema::hasColumn('notifications', 'title')) {
                    $payload['title'] = 'Order In Transit';
                }
                if (Schema::hasColumn('notifications', 'message')) {
                    $payload['message'] = 'Your order '.$order->order_number.' is in transit';
                }
                if (Schema::hasColumn('notifications', 'icon')) {
                    $payload['icon'] = 'fa-truck';
                }
                if (Schema::hasColumn('notifications', 'color')) {
                    $payload['color'] = 'blue';
                }
                if (Schema::hasColumn('notifications', 'link')) {
                    $payload['link'] = '/profile';
                }
                if (Schema::hasColumn('notifications', 'data')) {
                    $payload['data'] = json_encode(['order_id' => $order->id, 'assignment_id' => $assignment->id]);
                }
                \DB::table('notifications')->insert($payload + ['created_at' => now(), 'updated_at' => now()]);
            }
        }
        if (class_exists(\App\Events\Dashboard\DashboardUpdated::class)) {
            $orderId = $assignment->order_id;
            foreach (['admin', 'it', 'finance', 'cs', 'hr', 'supervisor', 'vendor'] as $dash) {
                event(new \App\Events\Dashboard\DashboardUpdated($dash, [
                    'type' => 'delivery_assignment_status_changed',
                    'order_id' => $orderId,
                    'assignment_id' => $assignment->id,
                    'status' => 'in_transit',
                    'timestamp' => now()->toIso8601String(),
                ]));
            }
        }

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
        if ($assignment->status === 'delivered') {
            return response()->json([
                'success' => true,
                'assignment' => $assignment->fresh(),
                'order' => $assignment->order?->fresh(),
            ]);
        }

        $assignment->updateStatus('delivered', $validated['notes'] ?? null, $validated['signature'] ?? null);

        $order = $assignment->order;
        if ($order->payment_method === 'cash' && $order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);
        }

        $actorUserId = $this->getActorUserIdForFlow();
        CrossDepartmentFlowService::handleOrderCompletion($order->id, $actorUserId ?? 0);

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
