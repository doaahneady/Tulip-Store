<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>الإشعارات - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body{font-family:'El Messiri',sans-serif;background:#f5f5f5;margin:0;padding:0}
.notification-card{background:#fff;border-radius:12px;padding:1.5rem;margin-bottom:1rem;box-shadow:0 2px 8px rgba(0,0,0,0.06);transition:all 0.3s;position:relative;border-right:4px solid transparent}
.notification-card.unread{background:#f8f9fa;border-right-color:#ff6b35}
.notification-card.unread::before{content:'';position:absolute;top:1.5rem;left:1.5rem;width:10px;height:10px;background:#ff6b35;border-radius:50%;box-shadow:0 0 0 3px rgba(255,107,53,0.2)}
.notification-icon{width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0}
.icon-blue{background:#e3f2fd;color:#2196f3}
.icon-green{background:#e8f5e9;color:#4caf50}
.icon-orange{background:#fff3e0;color:#ff9800}
.icon-red{background:#ffebee;color:#f44336}
.mark-read-btn{background:#2a7080;color:#fff;border:none;padding:0.5rem 1rem;border-radius:8px;font-family:'El Messiri',sans-serif;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.3s}
.mark-read-btn:hover{background:#1a5060;transform:translateY(-2px)}
.mark-single-btn{background:#e8f4f8;color:#2a7080;border:none;padding:0.4rem 0.9rem;border-radius:6px;font-family:'El Messiri',sans-serif;font-weight:600;font-size:0.8rem;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:0.3rem}
.mark-single-btn:hover{background:#2a7080;color:#fff;transform:translateY(-1px)}
.mark-single-btn i{font-size:0.75rem}
.notification-link{color:#2a7080;text-decoration:none;font-weight:600;font-size:0.85rem;display:inline-flex;align-items:center;gap:0.3rem;transition:all 0.3s;cursor:pointer}
.notification-link:hover{color:#1a5060}
.notification-details{display:none;margin-top:1rem;padding:1rem;background:#f8f9fa;border-radius:8px;border-right:3px solid #2a7080}
.notification-details.show{display:block;animation:slideDown 0.3s ease}
@keyframes slideDown{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}
.detail-row{display:flex;align-items:start;gap:0.5rem;margin-bottom:0.5rem;font-size:0.9rem}
.detail-row:last-child{margin-bottom:0}
.detail-label{font-weight:700;color:#2a7080;min-width:80px}
.detail-value{color:#666;flex:1}
</style>
</head>
<body>
@if(View::exists('components.navbar'))
@include('components.navbar')
@endif
<section style="background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);padding:3rem 1.5rem;text-align:center">
<h1 style="font-size:2.5rem;font-weight:800;color:#fff;margin:0"><i class="fas fa-bell" style="margin-left:0.5rem"></i>الإشعارات</h1>
<p style="color:#e8f4f8;margin:0.5rem 0 0 0;font-size:1.1rem">جميع التحديثات والأخبار الخاصة بك</p>
</section>
<section style="max-width:900px;margin:2rem auto 4rem;padding:0 1.5rem">
@if($notifications->count() > 0)
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
<p style="font-family:'El Messiri',sans-serif;color:#666;margin:0">
<strong>{{ $notifications->total() }}</strong> إشعار
</p>
<button onclick="markAllAsRead()" class="mark-read-btn">
<i class="fas fa-check-double"></i> تعليم الكل كمقروء
</button>
</div>
@foreach($notifications as $notification)
<div class="notification-card {{ $notification->is_read ? '' : 'unread' }}" id="notification-{{ $notification->id }}">
<div style="display:flex;gap:1rem;align-items:start">
<div class="notification-icon icon-{{ $notification->color }}">
<i class="{{ $notification->icon ?? 'fas fa-bell' }}"></i>
</div>
<div style="flex:1">
<h3 style="font-family:'El Messiri',sans-serif;font-size:1.1rem;font-weight:700;color:#1a1a1a;margin:0 0 0.5rem 0">{{ $notification->title }}</h3>
<p style="font-family:'El Messiri',sans-serif;font-size:0.95rem;color:#666;margin:0 0 0.8rem 0;line-height:1.6">{{ $notification->message }}</p>
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.8rem">
<div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
<small style="color:#999;font-size:0.85rem">
<i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
</small>
@if(!$notification->is_read)
<small style="color:#ff6b35;font-weight:600;font-size:0.85rem">
<i class="fas fa-circle" style="font-size:0.5rem"></i> جديد
</small>
@endif
<span class="notification-link" onclick="toggleDetails({{ $notification->id }})">
<i class="fas fa-chevron-down" id="icon-{{ $notification->id }}"></i>
<span id="text-{{ $notification->id }}">عرض التفاصيل</span>
</span>
</div>
@if(!$notification->is_read)
<button class="mark-single-btn" onclick="markAsRead({{ $notification->id }})">
<i class="fas fa-check"></i>
<span>تعليم كمقروء</span>
</button>
@endif
</div>

<!-- Details Card -->
<div class="notification-details" id="details-{{ $notification->id }}">
<div class="detail-row">
<span class="detail-label">النوع:</span>
<span class="detail-value">
@switch($notification->type)
@case('order_created') إنشاء طلب @break
@case('order_confirmed') تأكيد طلب @break
@case('order_shipped') شحن طلب @break
@case('order_delivered') توصيل طلب @break
@case('order_cancelled') إلغاء طلب @break
@case('welcome') ترحيب @break
@case('promotion') عرض خاص @break
@case('new_product') منتج جديد @break
@default {{ $notification->type }} @endswitch
</span>
</div>
<div class="detail-row">
<span class="detail-label">التاريخ:</span>
<span class="detail-value">{{ $notification->created_at->format('Y-m-d h:i A') }}</span>
</div>
<div class="detail-row">
<span class="detail-label">الحالة:</span>
<span class="detail-value">{{ $notification->is_read ? 'مقروء' : 'غير مقروء' }}</span>
</div>

@if($notification->read_at)
<div class="detail-row">
<span class="detail-label">تم القراءة:</span>
<span class="detail-value">{{ $notification->read_at->diffForHumans() }}</span>
</div>
@endif
</div>

</div>
</div>
</div>
@endforeach
<div style="margin-top:2rem">{{ $notifications->links() }}</div>
@else
<div style="background:#fff;border-radius:16px;padding:4rem 2rem;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.08)">
<i class="fas fa-bell-slash" style="font-size:5rem;color:#e0e0e0;margin-bottom:1rem"></i>
<h3 style="color:#666;margin:0 0 1rem 0">لا توجد إشعارات</h3>
<p style="color:#999;margin:0">سنخبرك عندما يحدث شيء جديد!</p>
</div>
@endif
</section>
<script>
function toggleDetails(id) {
    const details = document.getElementById(`details-${id}`);
    const icon = document.getElementById(`icon-${id}`);
    const text = document.getElementById(`text-${id}`);
    
    if (details.classList.contains('show')) {
        details.classList.remove('show');
        icon.className = 'fas fa-chevron-down';
        text.textContent = 'عرض التفاصيل';
    } else {
        document.querySelectorAll('.notification-details.show').forEach(d => d.classList.remove('show'));
        document.querySelectorAll('[id^="icon-"]').forEach(i => i.className = 'fas fa-chevron-down');
        document.querySelectorAll('[id^="text-"]').forEach(t => t.textContent = 'عرض التفاصيل');
        
        details.classList.add('show');
        icon.className = 'fas fa-chevron-up';
        text.textContent = 'إخفاء التفاصيل';
    }
}

function markAsRead(id) {
    const btn = event.target.closest('.mark-single-btn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
    }
    
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => {
        location.reload();
    }).catch(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> <span>تعليم كمقروء</span>';
        }
    });
}

function markAllAsRead() {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
    
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => {
        location.reload();
    }).catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-double"></i> تعليم الكل كمقروء';
    });
}
</script>
</body>
</html>
