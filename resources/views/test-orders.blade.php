<!DOCTYPE html>
<html>
<head>
    <title>Test Orders</title>
</head>
<body>
    <h1>Test Orders Page</h1>
    
    <p>Orders count: {{ $orders->count() }}</p>
    
    @if($orders->count() > 0)
        <ul>
            @foreach($orders as $order)
                <li>{{ $order->order_number }} - {{ $order->status }}</li>
            @endforeach
        </ul>
    @else
        <p>No orders found</p>
    @endif
</body>
</html>
