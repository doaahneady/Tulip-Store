# Tulip Driver App - Setup Guide

## Quick Start

### 1. Install Flutter
Download and install Flutter SDK from https://flutter.dev/docs/get-started/install

### 2. Verify Installation
```bash
flutter doctor
```

### 3. Install Dependencies
```bash
cd driver_app
flutter pub get
```

### 4. Configure API URL

Edit these files and change the baseUrl:

**lib/services/api_service.dart**:
```dart
static const String baseUrl = 'http://your-server-url.com';
```

**lib/services/auth_service.dart**:
```dart
static const String baseUrl = 'http://your-server-url.com';
```

### 5. Run the App

For Android:
```bash
flutter run
```

For iOS:
```bash
flutter run -d ios
```

## Backend API Requirements

You need to create these API endpoints in your Laravel backend:

### 1. Driver Login API
**File**: `routes/api.php`
```php
Route::post('/driver/login', function (Request $request) {
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // Find driver by username
    $user = User::where('username', $request->username)->first();
    
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'بيانات الدخول غير صحيحة'
        ], 401);
    }

    // Check if user is a driver
    $employee = $user->employee;
    if (!$employee || $employee->department_id != 5) { // 5 = Delivery department
        return response()->json([
            'success' => false,
            'message' => 'هذا الحساب ليس لسائق'
        ], 403);
    }

    $token = $user->createToken('driver-app')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token,
        'driver' => [
            'id' => $employee->id,
            'name' => $employee->name,
            'username' => $user->username,
        ]
    ]);
});
```

### 2. Get Driver Orders
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/driver/orders', function (Request $request) {
        $user = $request->user();
        $employee = $user->employee;
        
        $status = $request->query('status', 'all');
        
        $query = DeliveryAssignment::where('driver_id', $employee->id)
            ->with(['order.customer', 'order.items.product']);
        
        if ($status != 'all') {
            $query->where('status', $status);
        }
        
        $assignments = $query->orderBy('created_at', 'desc')->get();
        
        $orders = $assignments->map(function ($assignment) {
            $order = $assignment->order;
            return [
                'id' => $order->id,
                'customer_name' => $order->customer->name ?? 'غير محدد',
                'customer_phone' => $order->customer->phone ?? '',
                'delivery_address' => $order->delivery_address,
                'total_amount' => $order->total_amount,
                'status' => $assignment->status,
                'created_at' => $order->created_at->toISOString(),
            ];
        });
        
        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    });
    
    Route::get('/driver/orders/{id}', function (Request $request, $id) {
        $user = $request->user();
        $employee = $user->employee;
        
        $assignment = DeliveryAssignment::where('driver_id', $employee->id)
            ->where('order_id', $id)
            ->with(['order.customer', 'order.items.product'])
            ->firstOrFail();
        
        $order = $assignment->order;
        
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'customer_name' => $order->customer->name ?? 'غير محدد',
                'customer_phone' => $order->customer->phone ?? '',
                'delivery_address' => $order->delivery_address,
                'total_amount' => $order->total_amount,
                'status' => $assignment->status,
                'created_at' => $order->created_at->toISOString(),
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product->name ?? 'منتج',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                }),
            ]
        ]);
    });
    
    Route::post('/driver/orders/{id}/status', function (Request $request, $id) {
        $request->validate([
            'status' => 'required|in:picked_up,delivered',
        ]);
        
        $user = $request->user();
        $employee = $user->employee;
        
        $assignment = DeliveryAssignment::where('driver_id', $employee->id)
            ->where('order_id', $id)
            ->firstOrFail();
        
        $assignment->status = $request->status;
        
        if ($request->status == 'picked_up') {
            $assignment->picked_up_at = now();
        } elseif ($request->status == 'delivered') {
            $assignment->delivered_at = now();
            $assignment->order->status = 'delivered';
            $assignment->order->save();
        }
        
        $assignment->save();
        
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الطلب بنجاح'
        ]);
    });
    
    Route::get('/driver/statistics', function (Request $request) {
        $user = $request->user();
        $employee = $user->employee;
        
        $today = DeliveryAssignment::where('driver_id', $employee->id)
            ->where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();
        
        $week = DeliveryAssignment::where('driver_id', $employee->id)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        
        $total = DeliveryAssignment::where('driver_id', $employee->id)
            ->where('status', 'delivered')
            ->count();
        
        return response()->json([
            'success' => true,
            'statistics' => [
                'today' => $today,
                'week' => $week,
                'total' => $total,
            ]
        ]);
    });
    
    Route::get('/driver/verify', function (Request $request) {
        return response()->json(['success' => true]);
    });
});
```

## Testing

### Test Credentials
Use any driver username and password from your database.

Example:
- Username: `driver1`
- Password: `password123`

### Test Flow
1. Login with driver credentials
2. View assigned orders
3. Click on an order to see details
4. Mark order as "Picked Up"
5. Mark order as "Delivered"
6. Check statistics update

## Building APK

```bash
flutter build apk --release
```

The APK will be in: `build/app/outputs/flutter-apk/app-release.apk`

## Troubleshooting

### Issue: API Connection Failed
- Check if the baseUrl is correct
- Make sure the server is running
- Check if the device/emulator can reach the server

### Issue: Login Failed
- Verify the driver exists in the database
- Check if the user has an employee record
- Verify the employee is in the Delivery department

### Issue: Orders Not Showing
- Check if there are delivery assignments for the driver
- Verify the API endpoint is returning data
- Check the console for error messages

## Next Steps

1. Add push notifications for new orders
2. Implement real-time location tracking
3. Add offline support
4. Implement order history
5. Add delivery proof (photo upload)
6. Add earnings tracking

## Support

For technical support, contact the development team.
