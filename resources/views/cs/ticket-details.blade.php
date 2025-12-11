<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>تفاصيل التذكرة {{ $ticket->ticket_number }} - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
*{font-family:'Cairo',sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:#f0f4f8;min-height:100vh}
.container{max-width:1400px;margin:0 auto;padding:2rem;margin-top:100px}
.back-btn{display:inline-flex;align-items:center;gap:0.5rem;padding:0.8rem 1.5rem;background:#fff;border:2px solid #667eea;color:#667eea;border-radius:10px;text-decoration:none;font-weight:700;transition:all 0.3s;margin-bottom:2rem}
.back-btn:hover{background:#667eea;color:#fff;transform:translateX(5px)}
.ticket-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:2.5rem;border-radius:18px;color:#fff;margin-bottom:2rem;box-shadow:0 10px 40px rgba(102,126,234,0.3)}
.ticket-number{font-size:2.5rem;font-weight:800;margin-bottom:0.5rem}
.ticket-subject{font-size:1.5rem;margin-bottom:1rem;opacity:0.95}
.ticket-meta{display:flex;gap:2rem;flex-wrap:wrap}
.meta-item{display:flex;align-items:center;gap:0.5rem;font-size:1rem}
.main-grid{display:grid;grid-template-columns:1fr 400px;gap:2rem}
.card{background:#fff;padding:2.5rem;border-radius:18px;box-shadow:0 4px 15px rgba(0,0,0,0.08);margin-bottom:2rem}
.card-title{font-size:1.4rem;font-weight:700;color:#1a1a1a;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.8rem}
.card-title i{color:#667eea;font-size:1.5rem}
.status-badge{display:inline-block;padding:0.5rem 1.2rem;border-radius:25px;font-size:0.9rem;font-weight:700}
.status-open{background:#fff3cd;color:#856404}
.status-in_progress{background:#cfe2ff;color:#084298}
.status-waiting_customer{background:#f8d7da;color:#721c24}
.status-resolved{background:#d1e7dd;color:#0f5132}
.status-closed{background:#e2e3e5;color:#41464b}
.priority-low{background:#d1ecf1;color:#0c5460}
.priority-medium{background:#fff3cd;color:#856404}
.priority-high{background:#f8d7da;color:#721c24}
.priority-urgent{background:#dc3545;color:#fff}
.conversation{max-height:600px;overflow-y:auto;margin-bottom:2rem}
.message{margin-bottom:2rem;display:flex;gap:1rem}
.message.agent{flex-direction:row-reverse}
.message-avatar{width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.2rem;flex-shrink:0}
.message-content{flex:1;max-width:70%}
.message-header{display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem}
.message-author{font-weight:700;color:#1a1a1a}
.message-time{font-size:0.85rem;color:#666}
.message-bubble{background:#f8f9fa;padding:1.5rem;border-radius:15px;border:2px solid #e5e7eb}
.message.agent .message-bubble{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none}
.message.internal{opacity:0.7}
.message.internal .message-bubble{background:#fff3cd;border-color:#fbbf24}
.reply-form{display:flex;flex-direction:column;gap:1rem}
.reply-input{width:100%;padding:1rem;border:2px solid #e5e7eb;border-radius:12px;font-family:'Cairo',sans-serif;font-size:1rem;resize:vertical;min-height:120px}
.reply-input:focus{outline:none;border-color:#667eea}
.btn{padding:0.8rem 2rem;border-radius:10px;border:none;font-weight:700;cursor:pointer;transition:all 0.3s;font-size:1rem;display:inline-flex;align-items:center;gap:0.5rem}
.btn-primary{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(102,126,234,0.4)}
.btn-secondary{background:#6b7280;color:#fff}
.btn-secondary:hover{background:#4b5563}
.info-row{display:flex;justify-content:space-between;padding:1rem 0;border-bottom:1px solid #f0f4f8}
.info-row:last-child{border-bottom:none}
.info-label{color:#666;font-weight:600}
.info-value{color:#1a1a1a;font-weight:700}
.timeline{position:relative;padding-right:2rem}
.timeline-item{position:relative;padding-bottom:2rem}
.timeline-item:last-child{padding-bottom:0}
.timeline-item::before{content:'';position:absolute;right:-2rem;top:0;width:2px;height:100%;background:#e5e7eb}
.timeline-icon{position:absolute;right:-2.75rem;top:0;width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.9rem;z-index:1}
.timeline-content{background:#f8f9fa;padding:1rem 1.5rem;border-radius:10px;border-right:4px solid}
.timeline-title{font-weight:700;color:#1a1a1a;margin-bottom:0.3rem}
.timeline-desc{color:#666;font-size:0.9rem;margin-bottom:0.3rem}
.timeline-time{color:#999;font-size:0.85rem}
.action-group{display:flex;gap:1rem;margin-bottom:1.5rem}
.select-input{padding:0.8rem;border:2px solid #e5e7eb;border-radius:10px;font-family:'Cairo',sans-serif;font-size:1rem;font-weight:600;background:#fff;cursor:pointer}
.select-input:focus{outline:none;border-color:#667eea}
.metric-box{background:#f8f9fa;padding:1.5rem;border-radius:12px;text-align:center;margin-bottom:1rem}
.metric-value{font-size:2rem;font-weight:800;color:#667eea;margin-bottom:0.5rem}
.metric-label{color:#666;font-size:0.9rem;font-weight:600}
.rating-stars{color:#fbbf24;font-size:1.5rem;margin:1rem 0}
</style>
</head>
<body>
@include('components.navbar')

<div class="container">
<a href="/cs/dashboard" class="back-btn">
<i class="fas fa-arrow-right"></i>
العودة إلى لوحة التحكم
</a>

<div class="ticket-header">
<div class="ticket-number">{{ $ticket->ticket_number }}</div>
<div class="ticket-subject">{{ $ticket->subject }}</div>
<div class="ticket-meta">
<div class="meta-item">
<i class="fas fa-user"></i>
<span>{{ $ticket->user->name }}</span>
</div>
<div class="meta-item">
<i class="fas fa-calendar"></i>
<span>{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
</div>
<div class="meta-item">
<i class="fas fa-clock"></i>
<span>{{ $ticket->created_at->diffForHumans() }}</span>
</div>
</div>
</div>

<div class="main-grid">
<!-- Main Content -->
<div>
<!-- Ticket Description -->
<div class="card">
<h3 class="card-title"><i class="fas fa-file-alt"></i> وصف المشكلة</h3>
<p style="color:#333;line-height:1.8;font-size:1.1rem">{{ $ticket->description }}</p>
@if($ticket->order)
<div style="margin-top:1.5rem;padding:1rem;background:#f8f9fa;border-radius:10px;border-right:4px solid #667eea">
<strong style="color:#667eea"><i class="fas fa-shopping-bag"></i> مرتبط بالطلب:</strong>
<span style="margin-right:0.5rem">{{ $ticket->order->order_number }}</span>
</div>
@endif
</div>

<!-- Conversation -->
<div class="card">
<h3 class="card-title"><i class="fas fa-comments"></i> المحادثة ({{ $ticket->replies->count() }} رد)</h3>
<div class="conversation">
@forelse($ticket->replies as $reply)
<div class="message {{ $reply->user_id != $ticket->user_id ? 'agent' : '' }} {{ $reply->is_internal_note ? 'internal' : '' }}">
<div class="message-avatar">
{{ strtoupper(substr($reply->user->name, 0, 1)) }}
</div>
<div class="message-content">
<div class="message-header">
<span class="message-author">{{ $reply->user->name }}</span>
<span class="message-time">{{ $reply->created_at->diffForHumans() }}</span>
@if($reply->is_internal_note)
<span class="status-badge" style="background:#fbbf24;color:#fff;font-size:0.75rem">ملاحظة داخلية</span>
@endif
</div>
<div class="message-bubble">
{{ $reply->message }}
</div>
</div>
</div>
@empty
<div style="text-align:center;padding:3rem;color:#999">
<i class="fas fa-comment-slash" style="font-size:3rem;margin-bottom:1rem;display:block"></i>
<div>لا توجد ردود بعد</div>
</div>
@endforelse
</div>

<!-- Reply Form -->
<form class="reply-form" onsubmit="sendReply(event)">
<textarea class="reply-input" id="replyMessage" placeholder="اكتب ردك هنا..." required></textarea>
<div style="display:flex;gap:1rem;align-items:center">
<button type="submit" class="btn btn-primary">
<i class="fas fa-paper-plane"></i>
إرسال الرد
</button>
<label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
<input type="checkbox" id="isInternal" style="width:18px;height:18px">
<span style="font-weight:600;color:#666">ملاحظة داخلية</span>
</label>
</div>
</form>
</div>
</div>

<!-- Sidebar -->
<div>
<!-- Actions -->
<div class="card">
<h3 class="card-title"><i class="fas fa-cog"></i> الإجراءات</h3>

<div class="action-group">
<select class="select-input" style="flex:1" id="statusSelect" onchange="updateStatus()">
<option value="">تغيير الحالة</option>
<option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>مفتوحة</option>
<option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
<option value="waiting_customer" {{ $ticket->status == 'waiting_customer' ? 'selected' : '' }}>بانتظار العميل</option>
<option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>تم الحل</option>
<option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>مغلقة</option>
</select>
</div>

<div class="action-group">
<select class="select-input" style="flex:1" id="agentSelect" onchange="assignAgent()">
<option value="">تعيين وكيل</option>
@foreach($csAgents as $agent)
<option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
{{ $agent->name }}
</option>
@endforeach
</select>
</div>
</div>

<!-- Ticket Info -->
<div class="card">
<h3 class="card-title"><i class="fas fa-info-circle"></i> معلومات التذكرة</h3>
<div class="info-row">
<span class="info-label">الحالة</span>
<span class="info-value"><span class="status-badge status-{{ $ticket->status }}">{{ $ticket->status }}</span></span>
</div>
<div class="info-row">
<span class="info-label">الأولوية</span>
<span class="info-value"><span class="status-badge priority-{{ $ticket->priority }}">{{ $ticket->priority }}</span></span>
</div>
<div class="info-row">
<span class="info-label">الفئة</span>
<span class="info-value">{{ $ticket->category }}</span>
</div>
<div class="info-row">
<span class="info-label">الوكيل المعين</span>
<span class="info-value">{{ $ticket->assignedAgent->name ?? 'غير معين' }}</span>
</div>
<div class="info-row">
<span class="info-label">تاريخ الإنشاء</span>
<span class="info-value">{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
</div>
</div>

<!-- Metrics -->
@if($responseTime || $resolutionTime)
<div class="card">
<h3 class="card-title"><i class="fas fa-chart-line"></i> المقاييس</h3>
@if($responseTime)
<div class="metric-box">
<div class="metric-value">{{ $responseTime }}</div>
<div class="metric-label">وقت الرد الأول</div>
</div>
@endif
@if($resolutionTime)
<div class="metric-box">
<div class="metric-value">{{ $resolutionTime }}</div>
<div class="metric-label">وقت الحل</div>
</div>
@endif
</div>
@endif

<!-- Satisfaction -->
@if($ticket->satisfaction_rating)
<div class="card">
<h3 class="card-title"><i class="fas fa-star"></i> تقييم العميل</h3>
<div style="text-align:center">
<div class="rating-stars">
@for($i = 1; $i <= 5; $i++)
<i class="fas fa-star{{ $i <= $ticket->satisfaction_rating ? '' : '-o' }}"></i>
@endfor
</div>
<div style="font-size:2rem;font-weight:800;color:#667eea;margin-bottom:0.5rem">
{{ $ticket->satisfaction_rating }}/5
</div>
@if($ticket->satisfaction_comment)
<div style="background:#f8f9fa;padding:1rem;border-radius:10px;margin-top:1rem;font-style:italic;color:#666">
"{{ $ticket->satisfaction_comment }}"
</div>
@endif
</div>
</div>
@endif

<!-- Timeline -->
<div class="card">
<h3 class="card-title"><i class="fas fa-history"></i> الجدول الزمني</h3>
<div class="timeline">
@foreach($timeline as $event)
<div class="timeline-item">
<div class="timeline-icon" style="background:{{ $event['color'] }}">
<i class="fas fa-{{ $event['icon'] }}"></i>
</div>
<div class="timeline-content" style="border-right-color:{{ $event['color'] }}">
<div class="timeline-title">{{ $event['title'] }}</div>
<div class="timeline-desc">{{ $event['description'] }}</div>
<div class="timeline-time">{{ $event['time']->diffForHumans() }}</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</div>
</div>

<script>
const ticketId = {{ $ticket->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function sendReply(event) {
    event.preventDefault();
    
    const message = document.getElementById('replyMessage').value;
    const isInternal = document.getElementById('isInternal').checked;
    
    if (!message.trim()) {
        alert('الرجاء كتابة رسالة');
        return;
    }
    
    fetch(`/cs/tickets/${ticketId}/reply`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            message: message,
            is_internal: isInternal
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('فشل إرسال الرد');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    });
}

function updateStatus() {
    const status = document.getElementById('statusSelect').value;
    
    if (!status) return;
    
    if (!confirm('هل أنت متأكد من تغيير حالة التذكرة؟')) {
        return;
    }
    
    fetch(`/cs/tickets/${ticketId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('فشل تحديث الحالة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    });
}

function assignAgent() {
    const agentId = document.getElementById('agentSelect').value;
    
    if (!agentId) return;
    
    fetch(`/cs/tickets/${ticketId}/assign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ agent_id: agentId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('فشل تعيين الوكيل');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    });
}
</script>
</body>
</html>
