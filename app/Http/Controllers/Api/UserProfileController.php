<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class UserProfileController extends Controller
{
    /**
     * Get user's orders
     */
    public function orders(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['orders' => []], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $orders = Order::where('user_id', $user->id)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                $deliveryCost = $order->delivery_cost ?? $order->shipping_cost ?? 0;
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status ?? 'pending',
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'total' => $order->total ?? $order->total_amount ?? 0,
                    'subtotal' => $order->subtotal,
                    'delivery_cost' => $deliveryCost,
                    'items_count' => $order->items->count(),
                    'items' => $order->items->map(function ($item) {
                        $unitPrice = $item->unit_price ?? $item->price ?? 0;
                        $subtotal = $item->total_price ?? $item->subtotal ?? ((float) $unitPrice * (int) ($item->quantity ?? 0));
                        return [
                            'id' => $item->id,
                            'product_name' => $item->product_name ?? $item->product?->name ?? 'منتج',
                            'product_image' => $item->product?->image ?? $item->product?->primary_image ?? $item->product?->image_path ?? null,
                            'quantity' => $item->quantity,
                            'price' => $unitPrice,
                            'subtotal' => $subtotal,
                        ];
                    }),
                    'recipient_name' => $order->recipient_name,
                    'address' => $order->village.' - '.$order->address_note,
                    'created_at' => $order->created_at,
                    'estimated_delivery' => $order->estimated_delivery,
                ];
            });

        return response()->json(['orders' => $orders], 200, [], JSON_UNESCAPED_UNICODE);
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
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:500',
            'currency' => 'sometimes|nullable|in:USD,SYP',
        ]);

        $requiresVerification = false;
        if (array_key_exists('email', $validated) && $validated['email'] !== $user->email) {
            $requiresVerification = true;
            unset($validated['email']); // ignore email in this endpoint; handled via verification flow
        }

        $updates = [];
        if (array_key_exists('name', $validated)) {
            $nameCol = \Illuminate\Support\Facades\Schema::hasColumn('users', 'name') ? 'name' : 'user_full_name';
            $updates[$nameCol] = is_string($validated['name']) ? trim($validated['name']) : $validated['name'];
        }
        if (array_key_exists('phone', $validated)) {
            $phoneCol = \Illuminate\Support\Facades\Schema::hasColumn('users', 'phone') ? 'phone' : 'mobile';
            $updates[$phoneCol] = is_string($validated['phone']) ? trim($validated['phone']) : $validated['phone'];
        }
        if (array_key_exists('address', $validated)) {
            $updates['address'] = is_string($validated['address']) ? trim($validated['address']) : $validated['address'];
        }
        if (array_key_exists('currency', $validated) && \Illuminate\Support\Facades\Schema::hasColumn('users', 'currency')) {
            $updates['currency'] = strtoupper((string) $validated['currency']);
            session(['currency' => $updates['currency']]);
        }
        // Remove keys with null or empty string
        $updates = collect($updates)->filter(function ($v) {
            return ! (is_null($v) || (is_string($v) && trim($v) === ''));
        })->all();

        if (! empty($updates)) {
            $user->fill($updates)->save();
        }

        return response()->json([
            'success' => true,
            'requires_verification' => $requiresVerification,
            'user' => [
                'id' => $user->id,
                'name' => $user->name ?? $user->user_full_name,
                'email' => $user->email,
                'phone' => $user->phone ?? $user->mobile,
                'address' => $user->address,
                'currency' => $user->currency ?? session('currency') ?? 'USD',
            ],
        ], 200);
    }

    /**
     * Request email verification code for changing email
     */
    public function requestEmailVerification(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'new_email' => 'required|email|max:255',
        ]);

        $newEmail = $validated['new_email'];
        if ($newEmail === $user->email) {
            return response()->json(['success' => false, 'message' => 'البريد هو نفسه الحالي'], 422);
        }

        // Ensure email not already taken
        if (\App\Models\User::where('email', $newEmail)->exists()) {
            return response()->json(['success' => false, 'message' => 'البريد مستخدم مسبقاً'], 422);
        }

        $code = random_int(100000, 999999);
        $cacheKey = 'email_change_'.$user->id.'_'.$newEmail;
        Cache::put($cacheKey, $code, now()->addMinutes(10));

        try {
            Mail::raw("رمز التحقق لتغيير البريد الإلكتروني: {$code}", function ($message) use ($newEmail) {
                $message->to($newEmail)->subject('رمز التحقق - Tulip Store');
            });
        } catch (\Throwable $e) {
            // Fallback: don't fail if mail isn't configured; expose code for testing environments
            return response()->json(['success' => true, 'testing_code' => $code, 'message' => 'تم إرسال رمز التحقق'], 200);
        }

        return response()->json(['success' => true, 'message' => 'تم إرسال رمز التحقق'], 200);
    }

    /**
     * Confirm email change with verification code
     */
    public function confirmEmailVerification(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'new_email' => 'required|email|max:255',
            'code' => 'required|digits:6',
        ]);

        $newEmail = $validated['new_email'];
        $cacheKey = 'email_change_'.$user->id.'_'.$newEmail;
        $stored = Cache::get($cacheKey);

        if (! $stored || (int) $stored !== (int) $validated['code']) {
            return response()->json(['success' => false, 'message' => 'رمز غير صحيح أو منتهي'], 422);
        }

        // Apply email change and mark unverified (or set verified_at after confirming)
        $user->email = $newEmail;
        $user->email_verified_at = now();
        $user->save();
        Cache::forget($cacheKey);

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
            return response()->json(['error' => 'Unauthorized'], 401, [], JSON_UNESCAPED_UNICODE);
        }

        $order = Order::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['items.product'])
            ->first();

        if (! $order) {
            return response()->json(['error' => 'Order not found'], 404, [], JSON_UNESCAPED_UNICODE);
        }

        $deliveryCost = $order->delivery_cost ?? $order->shipping_cost ?? 0;
        $payload = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status ?? 'pending',
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'subtotal' => $order->subtotal,
            'delivery_cost' => $deliveryCost,
            'total' => $order->total ?? $order->total_amount ?? 0,
            'recipient_name' => $order->recipient_name,
            'phone' => $order->phone,
            'village' => $order->village,
            'address_note' => $order->address_note,
            'estimated_delivery' => $order->estimated_delivery,
            'created_at' => $order->created_at,
            'items' => $order->items->map(function ($item) {
                $unitPrice = $item->unit_price ?? $item->price ?? 0;
                $subtotal = $item->total_price ?? $item->subtotal ?? ((float) $unitPrice * (int) ($item->quantity ?? 0));
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name ?? $item->product?->name ?? 'منتج',
                    'product_image' => $item->product?->image ?? $item->product?->primary_image ?? $item->product?->image_path ?? null,
                    'quantity' => $item->quantity,
                    'price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];
            })->values(),
        ];

        return response()->json(['order' => $payload], 200, [], JSON_UNESCAPED_UNICODE);
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
