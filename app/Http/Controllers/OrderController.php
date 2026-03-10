<?php

namespace App\Http\Controllers;

use App\Events\Dashboard\DashboardUpdated;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\InventoryMovement;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'village' => 'required|string|max:255',
            'address_note' => 'nullable|string',
            'location' => 'required|array',
            'location.lat' => 'required|numeric',
            'location.lng' => 'required|numeric',
            'delivery_method' => 'required|in:normal,express,instant',
            'payment_method' => 'required|in:cash,card,syriatel,bank',
            'delivery_cost' => 'required|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $cart = session()->get('cart', []);

            if (empty($cart) && Auth::check() && \Illuminate\Support\Facades\Schema::hasTable('carts') && \Illuminate\Support\Facades\Schema::hasTable('cart_items')) {
                $dbCart = Cart::firstOrCreate(['user_id' => Auth::id()]);
                $dbItems = CartItem::where('cart_id', $dbCart->id)->get();
                foreach ($dbItems as $ci) {
                    $cart[$ci->product_id] = ($cart[$ci->product_id] ?? 0) + (int) $ci->quantity;
                }
            }

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'السلة فارغة',
                ], 400);
            }

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($cart as $productId => $quantity) {
                $product = \App\Models\Product::lockForUpdate()->find($productId);

                if (! $product) {
                    throw new \Exception("المنتج غير موجود: ID $productId");
                }

                if (! $product->is_active) {
                    throw new \Exception("المنتج غير متاح حالياً: {$product->name}");
                }

                if ($product->track_inventory && $product->stock_quantity < $quantity) {
                    throw new \Exception("الكمية المطلوبة غير متوفرة للمنتج: {$product->name}. المتوفر: {$product->stock_quantity}");
                }

                $price = $product->discount_price ?? $product->price;
                $subtotal += $price * $quantity;

                $orderItemsData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            $deliveryCost = (float) $validated['delivery_cost'];
            $serviceFee = 0;
            $user = Auth::user();
            $tags = $user?->tags ?? '';
            $isVip = is_string($tags) && stripos($tags, 'vip') !== false;
            if ($isVip) {
                $deliveryCost = 0;
            }
            $total = $subtotal + $deliveryCost;

            // Determine payment status based on payment method
            $paymentStatus = $validated['payment_method'] === 'cash' ? 'pending' : 'pending'; // Default to pending for safety

            // Calculate estimated delivery time
            $deliveryDays = [
                'normal' => 7,
                'express' => 3,
                'instant' => 1,
            ];
            $estimatedDelivery = now()->addDays($deliveryDays[$validated['delivery_method']]);

            $orderData = [
                'order_number' => 'ORD-'.strtoupper(uniqid()),
            ];
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'store_id')) {
                $storeId = null;
                foreach ($orderItemsData as $item) {
                    $pid = $item['product'] ?? null;
                    $candidate = $pid?->store_id ?? null;
                    if ($candidate) {
                        $storeId = $candidate;
                        break;
                    }
                }
                if ($storeId) {
                    $orderData['store_id'] = $storeId;
                }
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'user_id')) {
                $orderData['user_id'] = Auth::id();
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'customer_id')) {
                $orderData['customer_id'] = Auth::id();
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'recipient_name')) {
                $orderData['recipient_name'] = $validated['recipient_name'];
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'phone')) {
                $orderData['phone'] = $validated['phone'];
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'village')) {
                $orderData['village'] = $validated['village'];
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'address_note')) {
                $orderData['address_note'] = $validated['address_note'] ?? null;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'latitude')) {
                $orderData['latitude'] = $validated['location']['lat'];
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'longitude')) {
                $orderData['longitude'] = $validated['location']['lng'];
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'delivery_method')) {
                $orderData['delivery_method'] = $validated['delivery_method'];
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'payment_method')) {
                $orderData['payment_method'] = $validated['payment_method'];
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'subtotal')) {
                $orderData['subtotal'] = $subtotal;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'shipping_cost')) {
                $orderData['shipping_cost'] = $deliveryCost;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'delivery_cost')) {
                $orderData['delivery_cost'] = $deliveryCost;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'service_fee')) {
                $orderData['service_fee'] = $serviceFee;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'discount_amount')) {
                $orderData['discount_amount'] = 0;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'total')) {
                $orderData['total'] = $total;
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'total_amount')) {
                $orderData['total_amount'] = $total;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'status')) {
                $orderData['status'] = 'pending';
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'payment_status')) {
                $orderData['payment_status'] = $paymentStatus;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'estimated_delivery')) {
                $orderData['estimated_delivery'] = $estimatedDelivery;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'shipping_address')) {
                $orderData['shipping_address'] = [
                    'recipient_name' => $validated['recipient_name'],
                    'phone' => $validated['phone'],
                    'village' => $validated['village'],
                    'address_note' => $validated['address_note'] ?? null,
                    'location' => $validated['location'],
                ];
            }
            $order = Order::create($orderData);

            // 3. Process Items & Inventory
            foreach ($orderItemsData as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                $price = $item['price'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total_price' => $price * $quantity,
                ]);

                if ($product->track_inventory) {
                    InventoryMovement::recordMovement(
                        $product,
                        'out',
                        $quantity,
                        'sale',
                        $order->id,
                        'Order '.$order->order_number
                    );
                }
            }

            // 4. Create Financial Transaction
            $ftData = [
                'transaction_id' => 'TXN-'.strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'type' => 'order_payment',
                'status' => 'pending', // Pending until delivered/paid
                'amount' => $total,
                'currency' => 'SYP', // Assuming default currency
                'description' => "Order Payment #{$order->order_number}",
            ];
            if (\Illuminate\Support\Facades\Schema::hasColumn('financial_transactions', 'metadata')) {
                $ftData['metadata'] = [
                    'payment_method' => $validated['payment_method'],
                    'items_count' => count($cart),
                    'vip_order' => $isVip,
                ];
            }
            \App\Models\FinancialTransaction::create($ftData);

            session()->forget('cart');
            if (Auth::check() && \Illuminate\Support\Facades\Schema::hasTable('carts') && \Illuminate\Support\Facades\Schema::hasTable('cart_items')) {
                $dbCart = Cart::where('user_id', Auth::id())->first();
                if ($dbCart) {
                    CartItem::where('cart_id', $dbCart->id)->delete();
                }
            }

            DB::commit();

            event(new DashboardUpdated('admin', [
                'type' => 'order_created',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]));
            event(new DashboardUpdated('finance', [
                'type' => 'order_created',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]));

            if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'user_id')) {
                    $notificationData = [
                        'user_id' => Auth::id(),
                        'type' => 'order_created',
                        'title' => 'Order Created',
                        'message' => 'Your order '.$order->order_number.' has been created',
                    ];
                    if (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'link')) {
                        $notificationData['link'] = '/orders/'.$order->id;
                    }
                    Notification::create($notificationData);
                } elseif (\Illuminate\Support\Facades\Schema::hasColumn('notifications', 'notifiable_type')) {
                    \Illuminate\Support\Facades\DB::table('notifications')->insert([
                        'type' => 'order_created',
                        'notifiable_type' => \App\Models\User::class,
                        'notifiable_id' => Auth::id(),
                        'data' => json_encode([
                            'title' => 'Order Created',
                            'message' => 'Your order '.$order->order_number.' has been created',
                            'link' => '/orders/'.$order->id,
                        ]),
                        'read_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'تم إنشاء الطلب بنجاح',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء الطلب',
                'error' => $e->getMessage(), // Show actual error for debugging (remove in prod)
            ], 400); // Changed to 400 for validation errors
        }
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Check if user owns this order or is guest
        if ($order->user_id && Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('order-confirmation', compact('order'));
    }

    public function myOrders()
    {
        // If user is logged in, show only their orders
        // Otherwise show all orders (for testing)
        $query = Order::with('items.product');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('my-orders', compact('orders'));
    }

    public function uploadReceipt(Request $request, $id)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $order = Order::findOrFail($id);

        // Check if user owns this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح',
            ], 403);
        }

        // Store the receipt
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = 'receipt_'.$order->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $filename, 'public');

            $order->payment_receipt = $path;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الإيصال بنجاح',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'لم يتم رفع أي ملف',
        ], 400);
    }

    public function downloadInvoice($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        // Check if user owns this order or is admin
        if (! Auth::check() || (Auth::id() !== $order->user_id && ! Auth::user()->is_admin)) {
            abort(403, 'Unauthorized');
        }

        $pdf = \PDF::loadView('invoices.template-en', compact('order'));

        return $pdf->download('invoice-'.$order->order_number.'.pdf');
    }

    public function viewInvoice($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        // Check if user owns this order or is admin
        if (! Auth::check() || (Auth::id() !== $order->user_id && ! Auth::user()->is_admin)) {
            abort(403, 'Unauthorized');
        }

        return view('invoices.template', compact('order'));
    }
}
