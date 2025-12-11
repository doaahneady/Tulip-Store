<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>تفاصيل الطلب {{ $order->order_number }} - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{font-family:'El Messiri',sans-serif}
body{background:#f5f5f5;margin:0;padding:0}
.container{max-width:1200px;margin:0 auto;padding:2rem}
.card{background:#fff;padding:1.5rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-bottom:1.5rem}
.card h3{margin:0 0 1rem 0;color:#2a7080;font-size:1.2rem;border-bottom:2px solid #e8f4f8;padding-bottom:0.8rem}
.info-row{display:flex;justify-content:space-between;padding:0.8rem 0;border-bottom:1px solid #f0f0f0}
.info-row:last-child{border-bottom:none}
.info-label{font-weight:600;color:#666}
.info-value{color:#1a1a1a}
.badge{display:inline-block;padding:0.4rem 0.8rem;border-radius:20px;font-size:0.8rem;font-weight:700}
.badge-pending{background:#fff3cd;color:#856404}
.badge-confirmed{background:#d1ecf1;color:#0c5460}
.badge-processing{background:#cce5ff;color:#004085}
.badge-shipped{background:#d4edda;color:#155724}
.badge-delivered{background:#d4edda;color:#155724}
.badge-cancelled{background:#f8d7da;color:#721c24}
.badge-paid{background:#d4edda;color:#155724}
.badge-failed{background:#f8d7da;color:#721c24}
.btn{padding:0.7rem 1.5rem;border:none;border-radius:8px;font-weight:600;cursor:pointer;transition:all 0.3s;font-size:0.9rem}
.btn-primary{background:#2a7080;color:#fff}
.btn-primary:hover{background:#1a5060}
.btn-success{background:#28a745;color:#fff}
.btn-success:hover{background:#218838}
.btn-danger{background:#dc3545;color:#fff}
.btn-danger:hover{background:#c82333}
.btn-warning{background:#ffc107;color:#000}
.btn-warning:hover{background:#e0a800}
.action-buttons{display:flex;gap:0.8rem;flex-wrap:wrap;margin-bottom:2rem}
.product-item{display:flex;gap:1rem;padding:1rem;background:#f8f9fa;border-radius:8px;margin-bottom:0.8rem}
.product-img{width:60px;height:60px;object-fit:cover;border-radius:6px}
</style>
</head>
<body>
@include('components.navbar')

<section style="background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);padding:2rem 1.5rem">
<div style="max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:center">
<div>
<h1 style="font-size:2rem;font-weight:800;color:#fff;margin:0">
<i class="fas fa-receipt"></i> طلب رقم: {{ $order->order_number }}
</h1>
<p style="color:#e8f4f8;margin:0.5rem 0 0 0">تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i A') }}</p>
</div>
<a href="/admin/orders" class="btn" style="background:#fff;color:#2a7080">
<i class="fas fa-arrow-right"></i> العودة للطلبات
</a>
</div>
</section>

<div class="container">
<!-- Action Buttons -->
<div class="action-buttons">
<a href="{{ route('order.invoice', $order->id) }}" target="_blank" class="btn btn-primary">
<i class="fas fa-eye"></i> عرض الفاتورة
</a>
<a href="{{ route('order.invoice.download', $order->id) }}" class="btn btn-primary">
<i class="fas fa-download"></i> تحميل الفاتورة PDF
</a>
<button onclick="printInvoice()" class="btn btn-primary">
<i class="fas fa-print"></i> طباعة الفاتورة
</button>
<button onclick="printShippingLabel()" class="btn btn-warning">
<i class="fas fa-tag"></i> طباعة بطاقة الشحن
</button>
<button onclick="resendConfirmation()" class="btn btn-success">
<i class="fas fa-envelope"></i> إعادة إرسال التأكيد
</button>
</div>

<!-- Order Status & Payment -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
<div class="card">
<h3><i class="fas fa-info-circle"></i> حالة الطلب</h3>
<select id="orderStatus" class="filter-group select" style="width:100%;padding:0.8rem;border:2px solid #e0e0e0;border-radius:8px;margin-bottom:1rem">
<option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
<option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>تم التأكيد</option>
<option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد التجهيز</option>
<option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
<option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
<option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
</select>
<button onclick="updateOrderStatus()" class="btn btn-primary" style="width:100%">
<i class="fas fa-save"></i> تحديث الحالة
</button>
</div>

<div class="card">
<h3><i class="fas fa-credit-card"></i> حالة الدفع</h3>
<select id="paymentStatus" class="filter-group select" style="width:100%;padding:0.8rem;border:2px solid #e0e0e0;border-radius:8px;margin-bottom:1rem">
<option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
<option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>تم الدفع</option>
<option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>فشل</option>
</select>
<button onclick="updatePaymentStatus()" class="btn btn-success" style="width:100%">
<i class="fas fa-save"></i> تحديث حالة الدفع
</button>
</div>
</div>

<!-- Customer Info -->
<div class="card">
<h3><i class="fas fa-user"></i> معلومات العميل</h3>
<div class="info-row">
<span class="info-label">الاسم:</span>
<span class="info-value">{{ $order->recipient_name }}</span>
</div>
<div class="info-row">
<span class="info-label">الهاتف:</span>
<span class="info-value">{{ $order->phone }}</span>
</div>
<div class="info-row">
<span class="info-label">القرية/المدينة:</span>
<span class="info-value">{{ $order->village }}</span>
</div>
@if($order->address_note)
<div class="info-row">
<span class="info-label">ملاحظة العنوان:</span>
<span class="info-value">{{ $order->address_note }}</span>
</div>
@endif
<div class="info-row">
<span class="info-label">الموقع:</span>
<span class="info-value">
<a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" style="color:#2a7080">
<i class="fas fa-map-marker-alt"></i> عرض على الخريطة
</a>
</span>
</div>
</div>

<!-- Order Items -->
<div class="card">
<h3><i class="fas fa-box-open"></i> المنتجات ({{ $order->items->count() }})</h3>
@foreach($order->items as $item)
<div class="product-item">
@if($item->product->image)
<img src="/storage/{{ $item->product->image }}" class="product-img" alt="{{ $item->product->name }}">
@else
<div class="product-img" style="background:#e0e0e0;display:flex;align-items:center;justify-content:center">
<i class="fas fa-image" style="color:#999"></i>
</div>
@endif
<div style="flex:1">
<h4 style="margin:0 0 0.5rem 0">{{ $item->product->name }}</h4>
<p style="margin:0;color:#666;font-size:0.85rem">الكمية: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
</div>
<div style="text-align:left">
<strong style="color:#2a7080;font-size:1.1rem">${{ number_format($item->subtotal, 2) }}</strong>
</div>
</div>
@endforeach
</div>

<!-- Payment Summary -->
<div class="card">
<h3><i class="fas fa-calculator"></i> ملخص الدفع</h3>
<div class="info-row">
<span class="info-label">المجموع الفرعي:</span>
<span class="info-value">${{ number_format($order->subtotal, 2) }}</span>
</div>
<div class="info-row">
<span class="info-label">تكلفة التوصيل:</span>
<span class="info-value">${{ number_format($order->delivery_cost, 2) }}</span>
</div>
<div class="info-row">
<span class="info-label">رسوم الخدمة (5%):</span>
<span class="info-value">${{ number_format($order->service_fee, 2) }}</span>
</div>
<div class="info-row" style="background:#f8f9fa;padding:1rem;border-radius:8px;margin-top:0.5rem">
<span class="info-label" style="font-size:1.1rem;color:#2a7080">المجموع الكلي:</span>
<span class="info-value" style="font-size:1.3rem;font-weight:800;color:#ff6b35">${{ number_format($order->total, 2) }}</span>
</div>
</div>

<!-- Payment Receipt (if bank transfer) -->
@if($order->payment_method == 'bank' && $order->payment_receipt)
<div class="card">
<h3><i class="fas fa-file-invoice"></i> إيصال الدفع</h3>
<a href="/storage/{{ $order->payment_receipt }}" target="_blank" class="btn btn-primary">
<i class="fas fa-download"></i> عرض الإيصال
</a>
</div>
@endif

<!-- Customer Signature (for cash on delivery) -->
@if($order->payment_method == 'cash')
<div class="card">
<h3><i class="fas fa-signature"></i> توقيع العميل (الدفع عند الاستلام)</h3>
@if($order->customer_signature)
<div style="background:#f8f9fa;padding:1.5rem;border-radius:8px;text-align:center">
<img src="{{ $order->customer_signature }}" alt="توقيع العميل" style="max-width:300px;border:2px solid #e0e0e0;border-radius:8px">
<p style="margin:1rem 0 0 0;color:#666;font-size:0.9rem">
<i class="fas fa-check-circle" style="color:#28a745"></i>
تم التوقيع بتاريخ: {{ $order->signed_at ? \Carbon\Carbon::parse($order->signed_at)->format('Y-m-d H:i') : 'غير محدد' }}
</p>
</div>
@else
<div style="background:#fff3cd;padding:1.5rem;border-radius:8px;text-align:center;color:#856404">
<i class="fas fa-exclamation-triangle" style="font-size:2rem;display:block;margin-bottom:0.5rem"></i>
<p style="margin:0">لم يتم التوقيع بعد - سيتم التوقيع عند استلام الطلب</p>
</div>
@endif
</div>
@endif

<!-- Admin Notes -->
<div class="card">
<h3><i class="fas fa-sticky-note"></i> ملاحظات إدارية</h3>
<textarea id="adminNote" placeholder="أضف ملاحظة..." style="width:100%;padding:1rem;border:2px solid #e0e0e0;border-radius:8px;min-height:100px;margin-bottom:1rem"></textarea>
<button onclick="addNote()" class="btn btn-primary">
<i class="fas fa-plus"></i> إضافة ملاحظة
</button>

@if($order->admin_notes)
<div style="margin-top:1.5rem">
<h4 style="margin:0 0 1rem 0">الملاحظات السابقة:</h4>
@foreach(json_decode($order->admin_notes, true) as $note)
<div style="background:#f8f9fa;padding:1rem;border-radius:8px;margin-bottom:0.8rem;border-right:3px solid #2a7080">
<p style="margin:0 0 0.5rem 0">{{ $note['note'] }}</p>
<small style="color:#999">
<i class="fas fa-user"></i> {{ $note['admin'] }} - 
<i class="fas fa-clock"></i> {{ $note['date'] }}
</small>
</div>
@endforeach
</div>
@endif
</div>
</div>

<script>
const orderId = {{ $order->id }};

function updateOrderStatus() {
    const status = document.getElementById('orderStatus').value;
    
    fetch(`/admin/orders/${orderId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    });
}

function updatePaymentStatus() {
    const payment_status = document.getElementById('paymentStatus').value;
    
    fetch(`/admin/orders/${orderId}/update-payment-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ payment_status })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    });
}

function addNote() {
    const note = document.getElementById('adminNote').value.trim();
    if (!note) {
        alert('الرجاء كتابة ملاحظة');
        return;
    }
    
    fetch(`/admin/orders/${orderId}/add-note`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ note })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    });
}

function printInvoice() {
    window.print();
}

function printShippingLabel() {
    alert('سيتم إضافة ميزة طباعة بطاقة الشحن قريباً');
}

function resendConfirmation() {
    alert('سيتم إرسال رسالة تأكيد للعميل');
}
</script>
</body>
</html>
