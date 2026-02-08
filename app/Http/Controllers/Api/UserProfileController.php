<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Get user's orders
     */
    public function orders(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['orders' => []], 200);
        }

        $orders = Order::where('user_id', $user->id)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status ?? 'pending',
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'total' => $order->total,
                    'subtotal' => $order->subtotal,
                    'delivery_cost' => $order->delivery_cost,
                    'items_count' => $order->items->count(),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_name' => $item->product->name ?? 'منتج',
                            'product_image' => $item->product->image ?? null,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'subtotal' => $item->subtotal,
                        ];
                    }),
                    'recipient_name' => $order->recipient_name,
                    'address' => $order->village.' - '.$order->address_note,
                    'created_at' => $order->created_at,
                    'estimated_delivery' => $order->estimated_delivery,
                ];
            });

        return response()->json(['orders' => $orders], 200);
    }

    /**
     * Get user's notifications
     */
    public function notifications(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['notifications' => []], 200);
        }

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type ?? 'system',
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'icon' => $notif->icon,
                    'color' => $notif->color,
                    'link' => $notif->link,
                    'read' => $notif->is_read,
                    'created_at' => $notif->created_at,
                ];
            });

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ], 200);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true], 200);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:500',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
            ],
        ], 200);
    }

    /**
     * Get single order details
     */
    public function orderDetails(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['items.product'])
            ->first();

        if (! $order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json(['order' => $order], 200);
    }

    public function addresses(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $items = Address::where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'items' => $items,
            'count' => $items->count(),
        ], 200);
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'line1' => 'required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        $makeDefault = (bool) ($validated['is_default'] ?? false);
        if (! Address::where('user_id', $user->id)->exists()) {
            $makeDefault = true;
        }

        if ($makeDefault) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address = Address::create(array_merge($validated, [
            'user_id' => $user->id,
            'is_default' => $makeDefault,
        ]));

        return response()->json([
            'success' => true,
            'data' => $address,
        ], 201);
    }

    public function updateAddress(Request $request, int $id)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $address = Address::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'line1' => 'sometimes|required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        if (array_key_exists('is_default', $validated) && $validated['is_default']) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
            $validated['is_default'] = true;
        } else {
            unset($validated['is_default']);
        }

        $address->update($validated);

        return response()->json([
            'success' => true,
            'data' => $address->fresh(),
        ], 200);
    }

    public function deleteAddress(Request $request, int $id)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $address = Address::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        $wasDefault = (bool) $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = Address::where('user_id', $user->id)->orderByDesc('created_at')->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return response()->json(['success' => true], 200);
    }

    public function setDefaultAddress(Request $request, int $id)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $address = Address::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        Address::where('user_id', $user->id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'data' => $address->fresh(),
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid current password'], 422);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json(['success' => true], 200);
    }
}
