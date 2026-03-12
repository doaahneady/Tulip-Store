<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>طلباتي - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*:not(.fa):not(.fas):not(.far):not(.fab):not(.fal){}
body{font-family: 'El Messiri',sans-serif;background:#f5f5f5;margin:0;padding:0}
.orders-table{width:100%;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08)}
.orders-table table{width:100%;border-collapse:collapse;font-family: 'El Messiri',sans-serif}
.orders-table thead{background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);color:#fff}
.orders-table th{padding:1rem;text-align:right;font-weight:700;font-size:0.95rem;font-family:'El Messiri',sans-serif}
.orders-table td{padding:1rem;border-bottom:1px solid #f0f0f0;font-size:0.9rem;font-family:'El Messiri',sans-serif}
.orders-table tbody tr{transition:all 0.3s}
.orders-table tbody tr:hover{background:#f8f9fa}
.status-badge{display:inline-block;padding:0.4rem 0.8rem;border-radius:20px;font-weight:700;font-size:0.8rem;white-space:nowrap}
.status-pending{background:#fff3cd;color:#856404}
.status-confirmed{background:#d1ecf1;color:#0c5460}
.status-processing{background:#cce5ff;color:#004085}
.status-ready{background:#cce5ff;color:#004085}
.status-shipped{background:#d4edda;color:#155724}
.status-out_for_delivery{background:#d4edda;color:#155724}
.status-delivered{background:#d4edda;color:#155724}
.status-done{background:#d4edda;color:#155724}
.status-failed{background:#f8d7da;color:#721c24}
.status-refunded{background:#e2e3e5;color:#383d41}
.status-returned{background:#e2e3e5;color:#383d41}
.status-cancelled{background:#f8d7da;color:#721c24}
.btn-details{background:#2a7080;color:#fff;border:none;padding:0.6rem 1.2rem;border-radius:8px;cursor:pointer;font-family:'El Messiri',sans-serif;font-weight:600;font-size:0.85rem;transition:all 0.3s}
.btn-details:hover{background:#1a5060;transform:translateY(-2px);box-shadow:0 4px 12px rgba(42,112,128,0.3)}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:10000;align-items:center;justify-content:center;padding:2rem;overflow-y:auto}
.modal.show{display:flex!important}
.modal-content{background:#fff;border-radius:20px;max-width:900px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 10px 40px rgba(0,0,0,0.3);animation:modalSlideIn 0.4s ease}
@keyframes modalSlideIn{from{opacity:0;transform:translateY(-50px) scale(0.9)}to{opacity:1;transform:translateY(0) scale(1)}}
.modal-header{background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);padding:1.5rem 2rem;border-radius:20px 20px 0 0;display:flex;justify-content:space-between;align-items:center}
.modal-close{background:rgba(255,255,255,0.2);border:none;color:#fff;width:35px;height:35px;border-radius:50%;cursor:pointer;font-size:1.2rem;transition:all 0.3s}
.modal-close:hover{background:rgba(255,255,255,0.3);transform:rotate(90deg)}
.modal-body{padding:2rem}
.modal-content::-webkit-scrollbar{width:10px}
.modal-content::-webkit-scrollbar-track{background:#fff3e6;border-radius:10px}
.modal-content::-webkit-scrollbar-thumb{background:#ff6b35;border-radius:10px;border:2px solid #fff3e6}
.modal-content::-webkit-scrollbar-thumb:hover{background:#e55a2b}
@media(max-width:768px){.orders-table{overflow-x:auto}.orders-table table{min-width:800px}}
</style>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<?php if(View::exists('components.navbar')): ?>
<?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<section style="background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);padding:3rem 1.5rem;text-align:center">
<h1 style="font-size:2.5rem;font-weight:800;color:#fff;margin:0"><i class="fas fa-shopping-bag" style="margin-left:0.5rem"></i>طلباتي</h1>
<p style="color:#e8f4f8;margin:0.5rem 0 0 0;font-size:1.1rem">تتبع جميع طلباتك من هنا</p>
</section>
<section style="max-width:1200px;margin:2rem auto 4rem;padding:0 1.5rem">
<?php if($orders->count() > 0): ?>
<div class="orders-table">
<table>
<thead>
<tr>
<th>رقم الطلب</th>
<th>التاريخ</th>
<th>الحالة</th>
<th>المبلغ</th>
<th>طريقة الدفع</th>
<th>التوصيل المتوقع</th>
<th>الإجراءات</th>
</tr>
</thead>
<tbody>
<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
<td><strong style="color:#ff6b35"><?php echo e($order->order_number); ?></strong></td>
<td><?php echo e($order->created_at->format('d/m/Y')); ?><br><small style="color:#999"><?php echo e($order->created_at->format('h:i A')); ?></small></td>
<td>
<span class="status-badge status-<?php echo e($order->status); ?>">
<?php switch($order->status):
case ('pending'): ?>
<i class="fas fa-clock"></i> قيد الانتظار
<?php break; ?>
<?php case ('confirmed'): ?>
<i class="fas fa-check-circle"></i> تم التأكيد
<?php break; ?>
<?php case ('processing'): ?>
<i class="fas fa-cog fa-spin"></i> قيد التجهيز
<?php break; ?>
<?php case ('ready'): ?>
<i class="fas fa-box"></i> جاهز
<?php break; ?>
<?php case ('shipped'): ?>
<i class="fas fa-shipping-fast"></i> تم الشحن
<?php break; ?>
<?php case ('out_for_delivery'): ?>
<i class="fas fa-truck"></i> خارج للتوصيل
<?php break; ?>
<?php case ('delivered'): ?>
<i class="fas fa-check-double"></i> تم التوصيل
<?php break; ?>
<?php case ('done'): ?>
<i class="fas fa-check-double"></i> مكتمل
<?php break; ?>
<?php case ('failed'): ?>
<i class="fas fa-exclamation-triangle"></i> فشل
<?php break; ?>
<?php case ('refunded'): ?>
<i class="fas fa-undo"></i> مسترجع
<?php break; ?>
<?php case ('returned'): ?>
<i class="fas fa-reply"></i> مُعاد
<?php break; ?>
<?php case ('cancelled'): ?>
<i class="fas fa-times-circle"></i> ملغي
<?php break; ?>
<?php default: ?>
<?php echo e($order->status); ?>

<?php endswitch; ?>
</span>
</td>
<td><strong style="color:#2a7080;font-size:1.1rem">$<?php echo e(number_format($order->total_amount ?? $order->total ?? 0, 2)); ?></strong></td>
<td>
<?php switch($order->payment_method):
case ('cash'): ?>
<i class="fas fa-money-bill-wave" style="color:#28a745"></i> نقدي
<?php break; ?>
<?php case ('card'): ?>
<i class="fas fa-credit-card" style="color:#007bff"></i> بطاقة
<?php break; ?>
<?php case ('syriatel'): ?>
<i class="fas fa-mobile-alt" style="color:#e31e24"></i> Syriatel
<?php break; ?>
<?php case ('bank'): ?>
<i class="fas fa-university" style="color:#6c757d"></i> تحويل بنكي
<?php break; ?>
<?php case ('payroll'): ?>
<i class="fas fa-file-invoice-dollar" style="color:#0ea5e9"></i> Payroll
<?php break; ?>
<?php default: ?>
<?php echo e($order->payment_method); ?>

<?php endswitch; ?>
</td>
<td>
<?php if($order->estimated_delivery): ?>
<div style="display:flex;flex-direction:column;gap:0.3rem">
<span style="font-weight:600;color:#2a7080"><?php echo e(\Carbon\Carbon::parse($order->estimated_delivery)->format('d/m/Y')); ?></span>
<?php
$daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($order->estimated_delivery), false);
?>
<?php if($daysLeft > 0): ?>
<small style="color:#ff6b35;font-weight:600">
<i class="fas fa-hourglass-half"></i> متبقي <?php echo e($daysLeft); ?> يوم
</small>
<?php elseif($daysLeft == 0): ?>
<small style="color:#28a745;font-weight:600">
<i class="fas fa-shipping-fast"></i> التوصيل اليوم!
</small>
<?php else: ?>
<small style="color:#999">
<i class="fas fa-check"></i> تم التوصيل
</small>
<?php endif; ?>
</div>
<?php else: ?>
<small style="color:#999">قريباً</small>
<?php endif; ?>
</td>
<td>
<div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
<button class="btn-details" onclick="showOrderDetails(<?php echo e($order->id); ?>)"><i class="fas fa-eye"></i> التفاصيل</button>
<a class="btn-details" style="background:#6f42c1;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;" href="<?php echo e(route('order.invoice.download', $order->id)); ?>"><i class="fas fa-file-pdf"></i> تحميل الفاتورة</a>
<?php if(in_array($order->status, ['delivered', 'done'], true) && $order->customer_signature): ?>
<button class="btn-details" style="background:#28a745;" onclick="showDeliveryReceipt(<?php echo e($order->id); ?>)"><i class="fas fa-file-signature"></i> الفاتورة</button>
<?php endif; ?>
</div>
</td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>
</div>
<div style="margin-top:2rem"><?php echo e($orders->links()); ?></div>
<?php else: ?>
<div style="background:#fff;border-radius:16px;padding:4rem 2rem;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.08)">
<i class="fas fa-shopping-bag" style="font-size:5rem;color:#e0e0e0;margin-bottom:1rem"></i>
<h3 style="color:#666;margin:0 0 1rem 0">لا توجد طلبات بعد</h3>
<p style="color:#999;margin:0 0 2rem 0">ابدأ التسوق الآن واطلب منتجاتك المفضلة!</p>
<a href="/" style="display:inline-block;background:#ff6b35;color:#fff;padding:1rem 2rem;border-radius:10px;text-decoration:none;font-weight:700;transition:all 0.3s"><i class="fas fa-shopping-cart" style="margin-left:0.5rem"></i>تصفح المنتجات</a>
</div>
<?php endif; ?>
</section>
<div id="orderModal" class="modal">
<div class="modal-content">
<div class="modal-header">
<h2 style="color:#fff;margin:0;font-size:1.5rem"><i class="fas fa-receipt"></i> تفاصيل الطلب</h2>
<button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
</div>
<div class="modal-body" id="modalBody"></div>
</div>
</div>

<!-- Delivery Receipt Modal -->
<div id="receiptModal" class="modal">
<div class="modal-content" style="max-width:600px;">
<div class="modal-header" style="background:linear-gradient(135deg,#28a745 0%,#1e7e34 100%);">
<h2 style="color:#fff;margin:0;font-size:1.5rem"><i class="fas fa-file-invoice"></i> فاتورة التوصيل</h2>
<button class="modal-close" onclick="closeReceiptModal()"><i class="fas fa-times"></i></button>
</div>
<div class="modal-body" id="receiptBody" style="padding:0;"></div>
<div style="padding:1rem 2rem 2rem;display:flex;gap:1rem;">
<button onclick="closeReceiptModal()" style="flex:1;padding:0.8rem;background:#6c757d;color:#fff;border:none;border-radius:8px;font-family:'El Messiri',sans-serif;font-weight:700;cursor:pointer;"><i class="fas fa-times"></i> إغلاق</button>
<button onclick="printReceipt()" style="flex:1;padding:0.8rem;background:#28a745;color:#fff;border:none;border-radius:8px;font-family:'El Messiri',sans-serif;font-weight:700;cursor:pointer;"><i class="fas fa-print"></i> طباعة</button>
</div>
</div>
</div>

<script>
const ordersData=<?php echo json_encode($orders->items(), 15, 512) ?>;
const statusNames={'pending':'قيد الانتظار','confirmed':'تم التأكيد','processing':'قيد التجهيز','ready':'جاهز','shipped':'تم الشحن','out_for_delivery':'خارج للتوصيل','delivered':'تم التوصيل','done':'مكتمل','failed':'فشل','cancelled':'ملغي','refunded':'مسترجع','returned':'مُعاد'};
const paymentNames={'cash':'الدفع عند الاستلام','card':'بطاقة ائتمان','syriatel':'Syriatel Cash','bank':'تحويل بنكي','payroll':'Payroll'};
const deliveryNames={'normal':'توصيل عادي (7 أيام)','express':'توصيل مستعجل (3 أيام)','instant':'توصيل فوري (24 ساعة)'};
const paymentStatusNames={'pending':'قيد الانتظار','paid':'تم الدفع','failed':'فشل'};

function n(v){const x=Number(v);return Number.isFinite(x)?x:0;}
function money(v){return window.formatMoney?window.formatMoney(n(v)):('$'+n(v).toFixed(2));}
function safeText(v,fallback='—'){return (v===null||v===undefined||String(v).trim()==='')?fallback:String(v);}

function showOrderDetails(orderId){
const order=ordersData.find(o=>o.id===orderId);
if(!order)return;
const effectiveDelivery = n(order.delivery_cost ?? order.shipping_cost ?? 0);
const effectiveSubtotal = n(order.subtotal ?? 0);
const effectiveTotal = n(order.total ?? order.total_amount ?? (effectiveSubtotal + effectiveDelivery));
let html='<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;margin-bottom:2rem">';
html+='<div style="background:#f8f9fa;padding:1.5rem;border-radius:12px;border-right:4px solid #2a7080"><h4 style="margin:0 0 1rem 0;color:#2a7080;font-size:1.1rem"><i class="fas fa-truck"></i> معلومات التوصيل</h4>';
html+='<p style="margin:0 0 0.5rem 0;font-size:0.9rem;color:#555"><strong>المستلم:</strong> '+safeText(order.recipient_name)+'</p>';
html+='<p style="margin:0 0 0.5rem 0;font-size:0.9rem;color:#555"><strong>الهاتف:</strong> '+safeText(order.phone)+'</p>';
html+='<p style="margin:0 0 0.5rem 0;font-size:0.9rem;color:#555"><strong>القرية:</strong> '+safeText(order.village)+'</p>';
if(order.address_note)html+='<p style="margin:0 0 0.5rem 0;font-size:0.9rem;color:#555"><strong>ملاحظة:</strong> '+safeText(order.address_note,'')+'</p>';
html+='<p style="margin:0 0 0.5rem 0;font-size:0.9rem;color:#555"><strong>نوع التوصيل:</strong> '+safeText(deliveryNames[order.delivery_method], safeText(order.delivery_method))+'</p>';
html+='<p style="margin:0;font-size:0.9rem;color:#555"><strong>تاريخ التوصيل المتوقع:</strong> '+(order.estimated_delivery?new Date(order.estimated_delivery).toLocaleDateString('ar-SA',{year:'numeric',month:'long',day:'numeric'}):'—')+'</p></div>';
html+='<div style="background:#f8f9fa;padding:1.5rem;border-radius:12px;border-right:4px solid #ff6b35"><h4 style="margin:0 0 1rem 0;color:#ff6b35;font-size:1.1rem"><i class="fas fa-credit-card"></i> معلومات الدفع</h4>';
html+='<p style="margin:0 0 0.5rem 0;font-size:0.9rem;color:#555"><strong>طريقة الدفع:</strong> '+safeText(paymentNames[order.payment_method], safeText(order.payment_method))+'</p>';
html+='<p style="margin:0 0 0.5rem 0;font-size:0.9rem"><strong>حالة الدفع:</strong> <span style="display:inline-block;padding:0.3rem 0.6rem;border-radius:12px;font-size:0.8rem;font-weight:700;background:'+(order.payment_status==='paid'?'#d4edda':order.payment_status==='failed'?'#f8d7da':'#fff3e6')+';color:'+(order.payment_status==='paid'?'#28a745':order.payment_status==='failed'?'#dc3545':'#ff6b35')+'">'+paymentStatusNames[order.payment_status]+'</span></p>';
html+='<p style="margin:0;font-size:0.9rem;color:#555"><strong>المبلغ الإجمالي:</strong> <span style="color:#ff6b35;font-weight:700;font-size:1.2rem">'+money(effectiveTotal)+'</span></p>';
if(order.payment_method==='bank'&&order.payment_status==='pending'){
html+='<div style="margin-top:1rem;padding:1rem;background:#fff3cd;border-radius:8px;border-right:3px solid #ffc107"><p style="margin:0 0 0.5rem 0;font-size:0.85rem;color:#856404;font-weight:600"><i class="fas fa-info-circle"></i> يرجى تحويل المبلغ وإرفاق إيصال الدفع</p><button onclick="uploadReceipt('+order.id+')" style="background:#28a745;color:#fff;border:none;padding:0.6rem 1rem;border-radius:8px;font-family:\'El Messiri\',sans-serif;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.3s;width:100%"><i class="fas fa-upload" style="margin-left:0.5rem"></i> رفع إيصال الدفع</button></div>';
}
html+='</div></div>';
html+='<div style="background:#f8f9fa;padding:1.5rem;border-radius:12px;margin-bottom:1.5rem"><h4 style="margin:0 0 1rem 0;color:#1a1a1a;font-size:1.1rem"><i class="fas fa-box-open"></i> المنتجات ('+order.items.length+')</h4><div style="display:grid;gap:1rem">';
order.items.forEach(item=>{
const p = item.product || null;
const img = p?.image || p?.primary_image || p?.image_path || null;
const name = item.product_name || p?.name || 'منتج';
const unit = (item.price ?? item.unit_price ?? 0);
const sub = (item.subtotal ?? item.total_price ?? (n(unit) * n(item.quantity)));
html+='<div style="display:flex;align-items:center;gap:1rem;background:#fff;padding:1rem;border-radius:10px">';
if(img)html+='<img src="'+(String(img).startsWith('http')?img:'/storage/'+img)+'" alt="'+name+'" style="width:70px;height:70px;object-fit:cover;border-radius:8px" onerror="this.src=\'/images/gift-placeholder.svg\'">';
else html+='<div style="width:70px;height:70px;background:#e0e0e0;border-radius:8px;display:flex;align-items:center;justify-content:center"><i class="fas fa-image" style="color:#999;font-size:1.5rem"></i></div>';
html+='<div style="flex:1"><p style="margin:0 0 0.3rem 0;font-weight:700;color:#1a1a1a;font-size:1rem">'+name+'</p>';
html+='<p style="margin:0;font-size:0.85rem;color:#666">الكمية: '+safeText(item.quantity,'0')+' × '+money(unit)+'</p></div>';
html+='<div style="text-align:left"><p style="margin:0;font-weight:700;color:#2a7080;font-size:1.1rem">'+money(sub)+'</p></div></div>';
});
html+='</div></div>';
html+='<div style="background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);padding:1.5rem;border-radius:12px;color:#fff">';
html+='<div style="display:flex;justify-content:space-between;margin-bottom:0.6rem"><span>المجموع الفرعي:</span><span style="font-weight:700">'+money(effectiveSubtotal)+'</span></div>';
html+='<div style="display:flex;justify-content:space-between;margin-bottom:0.6rem"><span>تكلفة التوصيل:</span><span style="font-weight:700">'+money(effectiveDelivery)+'</span></div>';
html+='<div style="display:flex;justify-content:space-between;padding-top:0.6rem;border-top:2px solid rgba(255,255,255,0.3);font-size:1.2rem"><span style="font-weight:700">المجموع الكلي:</span><span style="font-weight:700;color:#ffd700">'+money(effectiveTotal)+'</span></div></div>';
document.getElementById('modalBody').innerHTML=html;
document.getElementById('orderModal').classList.add('show');
}
function closeModal(){document.getElementById('orderModal').classList.remove('show')}
document.getElementById('orderModal').addEventListener('click',function(e){if(e.target===this)closeModal()});

function showDeliveryReceipt(orderId){
const order=ordersData.find(o=>o.id===orderId);
if(!order)return;
const effectiveDelivery = n(order.delivery_cost ?? order.shipping_cost ?? 0);
const effectiveSubtotal = n(order.subtotal ?? 0);
const effectiveTotal = n(order.total ?? order.total_amount ?? (effectiveSubtotal + effectiveDelivery));

const confirmedDate = order.confirmed_at ? new Date(order.confirmed_at).toLocaleDateString('ar-SA', {year:'numeric',month:'long',day:'numeric',hour:'2-digit',minute:'2-digit'}) : 'غير محدد';

let html = `
<div id="receiptContent" style="background:#fff;padding:2rem;">
    <!-- Header -->
    <div style="text-align:center;border-bottom:3px solid #28a745;padding-bottom:1.5rem;margin-bottom:1.5rem;">
        <h1 style="color:#28a745;margin:0;font-size:1.8rem;"><i class="fas fa-check-circle"></i> فاتورة التوصيل</h1>
        <p style="color:#666;margin:0.5rem 0 0 0;">تم التوصيل بنجاح</p>
    </div>
    
    <!-- Order Info -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
        <div style="background:#f8f9fa;padding:1rem;border-radius:8px;">
            <p style="margin:0 0 0.5rem 0;color:#666;font-size:0.85rem;">رقم الطلب</p>
            <p style="margin:0;font-weight:700;color:#28a745;font-size:1.1rem;">${order.order_number}</p>
        </div>
        <div style="background:#f8f9fa;padding:1rem;border-radius:8px;">
            <p style="margin:0 0 0.5rem 0;color:#666;font-size:0.85rem;">تاريخ التوصيل</p>
            <p style="margin:0;font-weight:700;color:#1a1a1a;">${confirmedDate}</p>
        </div>
    </div>
    
    <!-- Customer Info -->
    <div style="background:#e8f5e9;padding:1rem;border-radius:8px;margin-bottom:1.5rem;border-right:4px solid #28a745;">
        <h4 style="margin:0 0 0.8rem 0;color:#28a745;"><i class="fas fa-user"></i> معلومات المستلم</h4>
        <p style="margin:0 0 0.3rem 0;"><strong>الاسم:</strong> ${order.recipient_name}</p>
        <p style="margin:0 0 0.3rem 0;"><strong>الهاتف:</strong> ${order.phone}</p>
        <p style="margin:0;"><strong>العنوان:</strong> ${order.village}</p>
    </div>
    
    <!-- Products -->
    <div style="margin-bottom:1.5rem;">
        <h4 style="margin:0 0 0.8rem 0;color:#1a1a1a;"><i class="fas fa-box"></i> المنتجات</h4>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8f9fa;">
                    <th style="padding:0.8rem;text-align:right;border-bottom:2px solid #dee2e6;">المنتج</th>
                    <th style="padding:0.8rem;text-align:center;border-bottom:2px solid #dee2e6;">الكمية</th>
                    <th style="padding:0.8rem;text-align:left;border-bottom:2px solid #dee2e6;">السعر</th>
                </tr>
            </thead>
            <tbody>
                ${order.items.map(item => `
                    <tr>
                        <td style="padding:0.8rem;border-bottom:1px solid #eee;">${item.product_name || item.product?.name || 'منتج'}</td>
                        <td style="padding:0.8rem;text-align:center;border-bottom:1px solid #eee;">${item.quantity}</td>
                        <td style="padding:0.8rem;text-align:left;border-bottom:1px solid #eee;">${window.formatMoney ? window.formatMoney(item.subtotal ?? item.total_price ?? 0) : ('$' + parseFloat(item.subtotal ?? item.total_price ?? 0).toFixed(2))}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    </div>
    
    <!-- Totals -->
    <div style="background:#f8f9fa;padding:1rem;border-radius:8px;margin-bottom:1.5rem;">
        <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
            <span>المجموع الفرعي:</span>
            <span>${window.formatMoney ? window.formatMoney(effectiveSubtotal) : ('$' + parseFloat(effectiveSubtotal).toFixed(2))}</span>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
            <span>تكلفة التوصيل:</span>
            <span>${window.formatMoney ? window.formatMoney(effectiveDelivery) : ('$' + parseFloat(effectiveDelivery).toFixed(2))}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding-top:0.8rem;border-top:2px solid #28a745;font-size:1.2rem;font-weight:700;color:#28a745;">
            <span>المجموع الكلي:</span>
            <span>${window.formatMoney ? window.formatMoney(effectiveTotal) : ('$' + parseFloat(effectiveTotal).toFixed(2))}</span>
        </div>
    </div>
    
    <!-- Signature -->
    <div style="border:2px dashed #28a745;padding:1.5rem;border-radius:12px;text-align:center;">
        <h4 style="margin:0 0 1rem 0;color:#28a745;"><i class="fas fa-signature"></i> توقيع المستلم</h4>
        ${order.customer_signature ? 
            `<img src="${order.customer_signature}" alt="توقيع المستلم" style="max-width:100%;max-height:150px;border:1px solid #ddd;border-radius:8px;background:#fff;">` : 
            `<p style="color:#999;margin:0;">لا يوجد توقيع</p>`
        }
        <p style="margin:1rem 0 0 0;color:#666;font-size:0.85rem;">تم التأكيد بتاريخ: ${confirmedDate}</p>
    </div>
    
    <!-- Footer -->
    <div style="text-align:center;margin-top:1.5rem;padding-top:1rem;border-top:1px solid #eee;">
        <p style="margin:0;color:#28a745;font-weight:700;"><i class="fas fa-check-double"></i> شكراً لتسوقكم معنا!</p>
        <p style="margin:0.5rem 0 0 0;color:#999;font-size:0.85rem;">Tulip Store - توليب ستور</p>
    </div>
</div>
`;

document.getElementById('receiptBody').innerHTML = html;
document.getElementById('receiptModal').classList.add('show');
}

function closeReceiptModal(){
document.getElementById('receiptModal').classList.remove('show');
}

function printReceipt(){
const content = document.getElementById('receiptContent').innerHTML;
const printWindow = window.open('', '_blank');
printWindow.document.write(`
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>فاتورة التوصيل</title>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            * { font-family: "El Messiri", sans-serif; }
            body { padding: 20px; }
            @media print {
                body { padding: 0; }
            }
        </style>
    </head>
    <body>
        ${content}
    </body>
    </html>
`);
printWindow.document.close();
printWindow.onload = function() {
    printWindow.print();
};
}

document.getElementById('receiptModal')?.addEventListener('click',function(e){if(e.target===this)closeReceiptModal()});

function uploadReceipt(orderId){
const input=document.createElement('input');
input.type='file';
input.accept='image/*,.pdf';
input.onchange=function(e){
const file=e.target.files[0];
if(!file)return;
const formData=new FormData();
formData.append('receipt',file);
formData.append('order_id',orderId);
fetch('/api/orders/'+orderId+'/upload-receipt',{
method:'POST',
headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},
body:formData
}).then(r=>r.json()).then(data=>{
if(data.success){
alert('✅ تم رفع الإيصال بنجاح! سيتم مراجعته قريباً');
closeModal();
location.reload();
}else{
alert('❌ حدث خطأ: '+(data.message||'الرجاء المحاولة مرة أخرى'));
}
}).catch(err=>{
console.error(err);
alert('❌ حدث خطأ في رفع الإيصال');
});
};
input.click();
}
</script>
</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/my-orders.blade.php ENDPATH**/ ?>