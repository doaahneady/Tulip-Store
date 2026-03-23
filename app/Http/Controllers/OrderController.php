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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class OrderController extends Controller
{
    /**
     * Orders are owned by a logged-in User row (same `users` table) but authenticated via either:
     * - `auth('trader')` (customer/trader login)
     * - or default `auth()` (web)
     *
     * Invoice ownership checks must use the active guard, otherwise customers get 403.
     */
    private function currentOrderUser(): ?\App\Models\User
    {
        $traderUser = Auth::guard('trader')->user();
        if ($traderUser) {
            return $traderUser;
        }

        return Auth::user();
    }

    private function currentOrderUserId(): ?int
    {
        $user = $this->currentOrderUser();
        return $user ? (int) $user->id : null;
    }

    private function orderOwnerOrAdmin(Order $order): bool
    {
        $user = $this->currentOrderUser();
        if (! $user) {
            return false;
        }

        $isAdmin = (bool) ($user->is_admin ?? false);
        if ($isAdmin) {
            return true;
        }

        return $order->user_id !== null && (int) $order->user_id === (int) $user->id;
    }

    private function redirectToLogin()
    {
        if (Route::has('login')) {
            return redirect()->guest(route('login'));
        }
        if (Route::has('trader.login.form')) {
            return redirect()->guest(route('trader.login.form'));
        }
        if (Route::has('employee.login')) {
            return redirect()->guest(route('employee.login'));
        }

        return redirect()->guest('/login');
    }

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
            Log::error('Order creation failed: '.$e->getMessage());

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
        if ($order->user_id && ! $this->orderOwnerOrAdmin($order)) {
            abort(403);
        }

        return view('order-confirmation', compact('order'));
    }

    public function myOrders()
    {
        // If user is logged in, show only their orders
        // Otherwise show all orders (for testing)
        $query = Order::with('items.product');

        $userId = $this->currentOrderUserId();
        if ($userId === null) {
            return $this->redirectToLogin();
        }
        $query->where('user_id', $userId);

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
        if ($order->user_id && ! $this->orderOwnerOrAdmin($order)) {
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
        if (! $this->orderOwnerOrAdmin($order)) {
            if ($this->currentOrderUser() === null) {
                return $this->redirectToLogin();
            }
            abort(403, 'Unauthorized');
        }

        $order = $this->prepareOrderForPdf($order);

        return \App\Services\InvoicePdfService::download(
            'invoices.template-en',
            compact('order'),
            'invoice-'.$order->order_number
        );
    }

    public function viewInvoice($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        // Check if user owns this order or is admin
        if (! $this->orderOwnerOrAdmin($order)) {
            if ($this->currentOrderUser() === null) {
                return $this->redirectToLogin();
            }
            abort(403, 'Unauthorized');
        }

        $order = $this->prepareOrderForPdf($order);

        return view('invoices.template', compact('order'));
    }

    private function prepareOrderForPdf(Order $order): Order
    {
        $order->recipient_name = $this->reshapeArabicForPdf($order->recipient_name);
        $order->village = $this->reshapeArabicForPdf($order->village);
        $order->address_note = $this->reshapeArabicForPdf($order->address_note);

        if ($order->relationLoaded('user') && $order->user) {
            $order->user->name = $this->reshapeArabicForPdf($order->user->name);
        }

        if ($order->relationLoaded('items')) {
            foreach ($order->items as $item) {
                if ($item->relationLoaded('product') && $item->product) {
                    $item->product->name = $this->reshapeArabicForPdf($item->product->name);
                }
                $item->product_name = $this->reshapeArabicForPdf($item->product_name);
            }
        }

        return $order;
    }

    private function reshapeArabicForPdf(?string $text): ?string
    {
        $text = $text === null ? null : trim($text);
        if ($text === null || $text === '') {
            return $text;
        }
        if (! preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
            return $text;
        }

        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($chars) || $chars === []) {
            return $text;
        }

        $forms = [
            "\u{0621}" => ["\u{FE80}", "\u{FE80}", null, null],
            "\u{0622}" => ["\u{FE81}", "\u{FE82}", null, null],
            "\u{0623}" => ["\u{FE83}", "\u{FE84}", null, null],
            "\u{0624}" => ["\u{FE85}", "\u{FE86}", null, null],
            "\u{0625}" => ["\u{FE87}", "\u{FE88}", null, null],
            "\u{0626}" => ["\u{FE89}", "\u{FE8A}", "\u{FE8B}", "\u{FE8C}"],
            "\u{0627}" => ["\u{FE8D}", "\u{FE8E}", null, null],
            "\u{0628}" => ["\u{FE8F}", "\u{FE90}", "\u{FE91}", "\u{FE92}"],
            "\u{0629}" => ["\u{FE93}", "\u{FE94}", null, null],
            "\u{062A}" => ["\u{FE95}", "\u{FE96}", "\u{FE97}", "\u{FE98}"],
            "\u{062B}" => ["\u{FE99}", "\u{FE9A}", "\u{FE9B}", "\u{FE9C}"],
            "\u{062C}" => ["\u{FE9D}", "\u{FE9E}", "\u{FE9F}", "\u{FEA0}"],
            "\u{062D}" => ["\u{FEA1}", "\u{FEA2}", "\u{FEA3}", "\u{FEA4}"],
            "\u{062E}" => ["\u{FEA5}", "\u{FEA6}", "\u{FEA7}", "\u{FEA8}"],
            "\u{062F}" => ["\u{FEA9}", "\u{FEAA}", null, null],
            "\u{0630}" => ["\u{FEAB}", "\u{FEAC}", null, null],
            "\u{0631}" => ["\u{FEAD}", "\u{FEAE}", null, null],
            "\u{0632}" => ["\u{FEAF}", "\u{FEB0}", null, null],
            "\u{0633}" => ["\u{FEB1}", "\u{FEB2}", "\u{FEB3}", "\u{FEB4}"],
            "\u{0634}" => ["\u{FEB5}", "\u{FEB6}", "\u{FEB7}", "\u{FEB8}"],
            "\u{0635}" => ["\u{FEB9}", "\u{FEBA}", "\u{FEBB}", "\u{FEBC}"],
            "\u{0636}" => ["\u{FEBD}", "\u{FEBE}", "\u{FEBF}", "\u{FEC0}"],
            "\u{0637}" => ["\u{FEC1}", "\u{FEC2}", "\u{FEC3}", "\u{FEC4}"],
            "\u{0638}" => ["\u{FEC5}", "\u{FEC6}", "\u{FEC7}", "\u{FEC8}"],
            "\u{0639}" => ["\u{FEC9}", "\u{FECA}", "\u{FECB}", "\u{FECC}"],
            "\u{063A}" => ["\u{FECD}", "\u{FECE}", "\u{FECF}", "\u{FED0}"],
            "\u{0641}" => ["\u{FED1}", "\u{FED2}", "\u{FED3}", "\u{FED4}"],
            "\u{0642}" => ["\u{FED5}", "\u{FED6}", "\u{FED7}", "\u{FED8}"],
            "\u{0643}" => ["\u{FED9}", "\u{FEDA}", "\u{FEDB}", "\u{FEDC}"],
            "\u{0644}" => ["\u{FEDD}", "\u{FEDE}", "\u{FEDF}", "\u{FEE0}"],
            "\u{0645}" => ["\u{FEE1}", "\u{FEE2}", "\u{FEE3}", "\u{FEE4}"],
            "\u{0646}" => ["\u{FEE5}", "\u{FEE6}", "\u{FEE7}", "\u{FEE8}"],
            "\u{0647}" => ["\u{FEE9}", "\u{FEEA}", "\u{FEEB}", "\u{FEEC}"],
            "\u{0648}" => ["\u{FEED}", "\u{FEEE}", null, null],
            "\u{0649}" => ["\u{FEEF}", "\u{FEF0}", null, null],
            "\u{064A}" => ["\u{FEF1}", "\u{FEF2}", "\u{FEF3}", "\u{FEF4}"],
        ];

        $lamAlef = [
            "\u{0622}" => ["\u{FEF5}", "\u{FEF6}"],
            "\u{0623}" => ["\u{FEF7}", "\u{FEF8}"],
            "\u{0625}" => ["\u{FEF9}", "\u{FEFA}"],
            "\u{0627}" => ["\u{FEFB}", "\u{FEFC}"],
        ];

        $isJoiner = function (string $ch) use ($forms): bool {
            return isset($forms[$ch]);
        };
        $canConnectPrev = function (string $ch) use ($forms): bool {
            return isset($forms[$ch]) && $forms[$ch][1] !== null;
        };
        $canConnectNext = function (string $ch) use ($forms): bool {
            return isset($forms[$ch]) && $forms[$ch][2] !== null;
        };
        $isDiacriticOrTatweel = function (string $ch): bool {
            $cp = unpack('N', mb_convert_encoding($ch, 'UCS-4BE', 'UTF-8'))[1] ?? 0;
            return ($cp >= 0x064B && $cp <= 0x065F) || $cp === 0x0640;
        };

        $out = '';
        $len = count($chars);
        for ($i = 0; $i < $len; $i++) {
            $ch = $chars[$i];
            if ($isDiacriticOrTatweel($ch) || ! $isJoiner($ch)) {
                $out .= $ch;
                continue;
            }

            if ($ch === "\u{0644}" && isset($chars[$i + 1]) && isset($lamAlef[$chars[$i + 1]])) {
                $prev = $chars[$i - 1] ?? '';
                $connectPrev = $prev !== '' && $isJoiner($prev) && $canConnectNext($prev) && $canConnectPrev($ch);
                $out .= $connectPrev ? $lamAlef[$chars[$i + 1]][1] : $lamAlef[$chars[$i + 1]][0];
                $i++;
                continue;
            }

            $prev = $chars[$i - 1] ?? '';
            $next = $chars[$i + 1] ?? '';

            $connectPrev = $prev !== '' && $isJoiner($prev) && $canConnectNext($prev) && $canConnectPrev($ch);
            $connectNext = $next !== '' && $isJoiner($next) && ! $isDiacriticOrTatweel($next) && $canConnectNext($ch) && $canConnectPrev($next);

            if ($connectPrev && $connectNext && $forms[$ch][3] !== null) {
                $out .= $forms[$ch][3];
            } elseif ($connectPrev && $forms[$ch][1] !== null) {
                $out .= $forms[$ch][1];
            } elseif ($connectNext && $forms[$ch][2] !== null) {
                $out .= $forms[$ch][2];
            } else {
                $out .= $forms[$ch][0] ?? $ch;
            }
        }

        return "\u{200F}".$out."\u{200F}";
    }
}
