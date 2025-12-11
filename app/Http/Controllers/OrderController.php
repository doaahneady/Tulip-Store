<?php

namespace App\Http\Controllers;

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
            'delivery_cost' => 'required|numeric',
            'service_fee' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            // Get cart items from session
            $cart = session()->get('cart', []);
            
            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'السلة فارغة'
                ], 400);
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $productId => $quantity) {
                $product = \App\Models\Product::find($productId);
                
                if (!$product) {
                    continue;
                }
                
                $price = $product->discount_price ?? $product->price;
                $subtotal += $price * $quantity;
            }

            $deliveryCost = $validated['delivery_cost'];
            $serviceFee = $validated['service_fee'];
            $total = $subtotal + $deliveryCost + $serviceFee;

            // Determine payment status based on payment method
            $paymentStatus = $validated['payment_method'] === 'cash' ? 'pending' : 'pending';
            
            // Calculate estimated delivery time
            $deliveryDays = [
                'normal' => 7,
                'express' => 3,
                'instant' => 1
            ];
            $estimatedDelivery = now()->addDays($deliveryDays[$validated['delivery_method']]);

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'village' => $validated['village'],
                'address_note' => $validated['address_note'] ?? null,
                'latitude' => $validated['location']['lat'],
                'longitude' => $validated['location']['lng'],
                'delivery_method' => $validated['delivery_method'],
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'delivery_cost' => $deliveryCost,
                'service_fee' => $serviceFee,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => $paymentStatus,
                'estimated_delivery' => $estimatedDelivery
            ]);

            // Create order items
            foreach ($cart as $productId => $quantity) {
                $product = \App\Models\Product::find($productId);
                
                if (!$product) {
                    continue;
                }
                
                $price = $product->discount_price ?? $product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity
                ]);
            }

            // Clear cart
            session()->forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'تم إنشاء الطلب بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في إنشاء الطلب',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
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
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);
        
        $order = Order::findOrFail($id);
        
        // Check if user owns this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح'
            ], 403);
        }
        
        // Store the receipt
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = 'receipt_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $filename, 'public');
            
            $order->payment_receipt = $path;
            $order->save();
            
            return response()->json([
                'success' => true,
                'message' => 'تم رفع الإيصال بنجاح'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'لم يتم رفع أي ملف'
        ], 400);
    }

    public function downloadInvoice($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        
        // Check if user owns this order or is admin
        if (!Auth::check() || (Auth::id() !== $order->user_id && !Auth::user()->is_admin)) {
            abort(403, 'Unauthorized');
        }
        
        $pdf = \PDF::loadView('invoices.template', compact('order'));
        
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
    
    public function viewInvoice($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        
        // Check if user owns this order or is admin
        if (!Auth::check() || (Auth::id() !== $order->user_id && !Auth::user()->is_admin)) {
            abort(403, 'Unauthorized');
        }
        
        return view('invoices.template', compact('order'));
    }
}
