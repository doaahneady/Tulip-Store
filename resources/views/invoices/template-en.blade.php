<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family:  'El Messiri', sans-serif; margin: 0; padding: 24px; color: #111827; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; gap: 16px; margin-bottom: 18px; }
        .brand { font-weight: 800; letter-spacing: 0.5px; color: #0f766e; font-size: 18px; }
        .muted { color:#6b7280; font-size: 12px; }
        .pill { display:inline-block; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .pill--paid { background:#dcfce7; color:#166534; }
        .pill--pending { background:#fff7ed; color:#9a3412; }
        .pill--failed { background:#fee2e2; color:#b91c1c; }
        .grid { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 14px 0 18px; }
        .card { border: 1px solid #e5e7eb; border-radius: 14px; padding: 14px; }
        .card h3 { margin: 0 0 10px; font-size: 13px; color:#111827; }
        .row { display:flex; justify-content:space-between; gap: 10px; margin: 6px 0; font-size: 12px; }
        table { width:100%; border-collapse: collapse; border:1px solid #e5e7eb; border-radius: 14px; overflow:hidden; }
        th { text-align:left; background:#f9fafb; padding: 10px 12px; font-size: 12px; border-bottom:1px solid #e5e7eb; }
        td { padding: 10px 12px; font-size: 12px; border-bottom:1px solid #f1f5f9; }
        .totals { width: 100%; margin-top: 12px; }
        .totals .row { font-size: 13px; }
        .totals .row strong { font-size: 14px; }
        .right { text-align:right; }
        .footer { margin-top: 18px; padding-top: 12px; border-top:1px dashed #e5e7eb; font-size: 11px; color:#6b7280; }
    </style>
</head>
<body>
    @php
        $total = (float) ($order->total ?? $order->total_amount ?? 0);
        $subtotal = (float) ($order->subtotal ?? 0);
        $delivery = (float) ($order->delivery_cost ?? $order->shipping_cost ?? 0);
        $paymentStatus = (string) ($order->payment_status ?? 'pending');
        $payMethod = (string) ($order->payment_method ?? '-');
        $customer = $order->user?->name ?? $order->recipient_name ?? 'Customer';
    @endphp

    <div class="header">
        <div>
            <div class="brand">TULIP STORE</div>
            <div class="muted">Invoice</div>
        </div>
        <div class="right">
            <div style="font-weight:800; font-size: 16px;">{{ $order->order_number }}</div>
            <div class="muted">{{ optional($order->created_at)->format('Y-m-d H:i') }}</div>
            <div style="margin-top:8px;">
                <span class="pill {{ $paymentStatus === 'paid' ? 'pill--paid' : ($paymentStatus === 'failed' ? 'pill--failed' : 'pill--pending') }}">
                    Payment: {{ ucfirst($paymentStatus) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Customer</h3>
            <div class="row"><span>Name</span><span>{{ $customer }}</span></div>
            <div class="row"><span>Phone</span><span>{{ $order->phone ?? '-' }}</span></div>
            <div class="row"><span>Email</span><span>{{ $order->user?->email ?? '-' }}</span></div>
        </div>
        <div class="card">
            <h3>Delivery</h3>
            <div class="row"><span>Delivery type</span><span>{{ $order->delivery_method ?? '-' }}</span></div>
            <div class="row"><span>Estimated delivery</span><span>{{ $order->estimated_delivery ? \Carbon\Carbon::parse($order->estimated_delivery)->format('Y-m-d') : '-' }}</span></div>
            <div class="row"><span>Address</span><span>{{ $order->village ?? '-' }} {{ $order->address_note ? ' - '.$order->address_note : '' }}</span></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:55%;">Item</th>
                <th style="width:15%;">Qty</th>
                <th style="width:15%;" class="right">Unit</th>
                <th style="width:15%;" class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                @php
                    $unit = (float) ($item->unit_price ?? $item->price ?? 0);
                    $line = (float) ($item->total_price ?? $item->subtotal ?? ($unit * (int) ($item->quantity ?? 0)));
                @endphp
                <tr>
                    <td>{{ $item->product->name ?? $item->product_name ?? ('Product #'.$item->product_id) }}</td>
                    <td>{{ (int) ($item->quantity ?? 0) }}</td>
                    <td class="right">${{ number_format($unit, 2) }}</td>
                    <td class="right">${{ number_format($line, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="row"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
        <div class="row"><span>Delivery fee</span><span>${{ number_format($delivery, 2) }}</span></div>
        <div class="row"><span>Payment method</span><span>{{ $payMethod === 'payroll' ? 'Payroll' : ucfirst($payMethod) }}</span></div>
        <div class="row" style="border-top:1px solid #e5e7eb; padding-top:10px; margin-top:10px;">
            <span><strong>Grand total</strong></span>
            <span><strong>${{ number_format($total, 2) }}</strong></span>
        </div>
    </div>

    <div class="footer">
        This invoice is generated electronically by Tulip Store.
    </div>
</body>
</html>

