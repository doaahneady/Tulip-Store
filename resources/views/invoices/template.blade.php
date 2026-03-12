<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>فاتورة #{{ $order->order_number }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        * { box-sizing: border-box; }
        body { 
            font-family: 'El Messiri', sans-serif; 
            font-size: 12px; 
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .invoice-box { 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 20px;
            background: #fff;
        }
        .invoice-header { 
            text-align: center; 
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2a7080;
        }
        .invoice-header h1 { 
            color: #2a7080; 
            margin: 0 0 5px 0;
            font-size: 28px;
        }
        .invoice-header .invoice-number {
            font-size: 16px;
            color: #666;
            margin: 5px 0;
        }
        .invoice-header .invoice-date {
            font-size: 12px;
            color: #888;
        }
        .info-section { 
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 20px;
        }
        .info-column { 
            flex: 1;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        .info-column h3 {
            margin: 0 0 10px 0;
            color: #2a7080;
            font-size: 14px;
            border-bottom: 2px solid #2a7080;
            padding-bottom: 5px;
        }
        .info-column p {
            margin: 0;
            line-height: 1.8;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        th { 
            background-color: #2a7080; 
            color: white;
            padding: 12px 10px;
            text-align: right;
            font-weight: bold;
        }
        td { 
            padding: 10px;
            text-align: right;
            border-bottom: 1px solid #e0e0e0;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .totals-table {
            width: 300px;
            margin-right: auto;
            margin-left: 0;
        }
        .totals-table td {
            padding: 8px 10px;
        }
        .total-row { 
            font-weight: bold; 
            font-size: 16px;
            background: #2a7080 !important;
            color: #fff;
        }
        .total-row td {
            border: none;
        }
        .payment-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-cash { background: #fff3cd; color: #856404; }
        .badge-card { background: #d1ecf1; color: #0c5460; }
        .badge-paid { background: #d4edda; color: #155724; }
        .badge-pending { background: #fff3cd; color: #856404; }
        
        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            padding: 20px;
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
        }
        .signature-section h3 {
            margin: 0 0 15px 0;
            color: #2a7080;
            font-size: 14px;
        }
        .signature-box {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }
        .signature-item {
            flex: 1;
            text-align: center;
        }
        .signature-line {
            border-bottom: 2px solid #333;
            height: 60px;
            margin-bottom: 10px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 5px;
        }
        .signature-line img {
            max-height: 50px;
            max-width: 150px;
        }
        .signature-label {
            font-size: 11px;
            color: #666;
        }
        
        .footer { 
            text-align: center; 
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            color: #777;
            font-size: 11px;
        }
        
        /* Print Styles */
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .invoice-box { padding: 0; }
            .no-print { display: none !important; }
        }
        
        /* Print Button */
        .print-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #2a7080;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            z-index: 1000;
        }
        .print-btn:hover {
            background: #1a5060;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">
        🖨️ طباعة الفاتورة
    </button>
    
    <div class="invoice-box">
        <div class="invoice-header">
            <h1>🌷 TULIP STORE</h1>
            <div class="invoice-number">فاتورة رقم: {{ $order->order_number }}</div>
            <div class="invoice-date">التاريخ: {{ $order->created_at->format('Y/m/d - h:i A') }}</div>
        </div>

        <div class="info-section">
            <div class="info-column">
                <h3>📍 معلومات التوصيل</h3>
                <p>
                    <strong>الاسم:</strong> {{ $order->recipient_name }}<br>
                    <strong>الهاتف:</strong> {{ $order->phone }}<br>
                    <strong>العنوان:</strong> {{ $order->village }}<br>
                    @if($order->address_note)
                    <strong>ملاحظة:</strong> {{ $order->address_note }}
                    @endif
                </p>
            </div>
            <div class="info-column">
                <h3>📋 تفاصيل الطلب</h3>
                <p>
                    <strong>رقم الطلب:</strong> {{ $order->order_number }}<br>
                    <strong>طريقة الدفع:</strong> 
                    <span class="payment-badge badge-{{ $order->payment_method == 'cash' ? 'cash' : 'card' }}">
                        @switch($order->payment_method)
                            @case('cash') نقدي عند الاستلام @break
                            @case('card') بطاقة ائتمان @break
                            @case('syriatel') سيرياتيل كاش @break
                            @case('bank') تحويل بنكي @break
                            @case('payroll') Payroll @break
                            @default {{ $order->payment_method }}
                        @endswitch
                    </span><br>
                    <strong>حالة الدفع:</strong> 
                    <span class="payment-badge badge-{{ $order->payment_status }}">
                        @switch($order->payment_status)
                            @case('paid') تم الدفع @break
                            @case('pending') قيد الانتظار @break
                            @case('failed') فشل @break
                            @default {{ $order->payment_status }}
                        @endswitch
                    </span><br>
                    <strong>حالة الطلب:</strong> 
                    @switch($order->status)
                        @case('pending') قيد الانتظار @break
                        @case('confirmed') تم التأكيد @break
                        @case('processing') قيد التجهيز @break
                        @case('shipped') تم الشحن @break
                        @case('delivered') تم التوصيل @break
                        @case('cancelled') ملغي @break
                        @default {{ $order->status }}
                    @endswitch
                </p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:50%">المنتج</th>
                    <th style="width:15%">الكمية</th>
                    <th style="width:15%">السعر</th>
                    <th style="width:20%">المجموع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>@money($item->price)</td>
                    <td>@money($item->subtotal)</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td><strong>المجموع الفرعي:</strong></td>
                <td>@money($order->subtotal)</td>
            </tr>
            <tr>
                <td><strong>تكلفة التوصيل:</strong></td>
                <td>@money($order->delivery_cost)</td>
            </tr>
            <tr class="total-row">
                <td>المجموع الكلي:</td>
                <td>@money($order->total)</td>
            </tr>
        </table>

        @if($order->payment_method == 'cash')
        <div class="signature-section">
            <h3>✍️ التوقيعات (للدفع عند الاستلام)</h3>
            <div class="signature-box">
                <div class="signature-item">
                    <div class="signature-line">
                        @if($order->customer_signature)
                            <img src="{{ $order->customer_signature }}" alt="توقيع العميل">
                        @endif
                    </div>
                    <div class="signature-label">توقيع العميل</div>
                </div>
                <div class="signature-item">
                    <div class="signature-line"></div>
                    <div class="signature-label">توقيع المندوب</div>
                </div>
                <div class="signature-item">
                    <div class="signature-line"></div>
                    <div class="signature-label">المبلغ المستلم: @money($order->total)</div>
                </div>
            </div>
        </div>
        @endif

        <div class="footer">
            <p>شكراً لتسوقكم معنا! 🌷</p>
            <p>Tulip Store - متجركم الموثوق للتسوق الإلكتروني</p>
            <p style="font-size:10px;color:#999">تم إنشاء هذه الفاتورة إلكترونياً</p>
        </div>
    </div>
</body>
</html>
