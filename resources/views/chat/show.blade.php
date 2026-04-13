<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <!-- fav icon -->
        <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">
<title>محادثة مع {{ is_array(data_get($user,'name')) ? json_encode(data_get($user,'name')) : (data_get($user,'name') ?? '') }} - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{font-family:'El Messiri',sans-serif}
body{background:#f5f5f5;margin:0;padding:0}
.chat-container{max-width:1200px;margin:0 auto;padding:2rem;margin-top:100px}
.chat-grid{display:grid;grid-template-columns:350px 1fr;gap:2rem;height:calc(100vh - 200px)}
.users-list{background:#fff;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,0.08);overflow:hidden}
.users-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:1.5rem;font-size:1.3rem;font-weight:700}
.user-item{padding:1rem 1.5rem;border-bottom:1px solid #f0f0f0;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;gap:1rem}
.user-item:hover,.user-item.active{background:#f8f9fa}
.user-avatar{width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.2rem}
.user-info{flex:1}
.user-name{font-weight:700;color:#2d3748;margin-bottom:0.25rem}
.user-role{font-size:0.85rem;color:#718096}
.chat-box{background:#fff;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,0.08);display:flex;flex-direction:column;height:100%}
.chat-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:1.5rem;border-radius:15px 15px 0 0;display:flex;align-items:center;gap:1rem}
.chat-messages{flex:1;padding:2rem;overflow-y:auto;max-height:calc(100vh - 400px)}
.message{margin-bottom:1.5rem;display:flex;gap:1rem}
.message.sent{flex-direction:row-reverse}
.message-avatar{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0}
.message.sent .message-avatar{background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%)}
.message-content{max-width:60%;background:#f8f9fa;padding:1rem 1.5rem;border-radius:15px}
.message.sent .message-content{background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);color:#fff}
.message-text{margin:0;line-height:1.6}
.message-time{font-size:0.75rem;color:#718096;margin-top:0.5rem}
.message.sent .message-time{color:rgba(255,255,255,0.8)}
.chat-input{padding:1.5rem;border-top:2px solid #f0f0f0;display:flex;gap:1rem}
.chat-input input{flex:1;padding:1rem;border:2px solid #e2e8f0;border-radius:10px;font-size:1rem;font-family:'El Messiri',sans-serif}
.chat-input input:focus{outline:none;border-color:#667eea}
.btn-send{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:1rem 2rem;border-radius:10px;border:none;cursor:pointer;font-weight:700;transition:all 0.3s}
.btn-send:hover{transform:translateY(-2px);box-shadow:0 4px 15px rgba(102,126,234,0.3)}
</style>
</head>
<body>
@include('components.navbar')

<div class="chat-container">
<div class="chat-grid">
<!-- Users List -->
<div class="users-list">
<div class="users-header">
<i class="fas fa-comments"></i> المحادثات
</div>
<div style="max-height:calc(100vh - 300px);overflow-y:auto">
@foreach($specialUsers as $specialUser)
<a href="{{ route('chat.show', $specialUser) }}" class="user-item {{ $specialUser->id == $user->id ? 'active' : '' }}" style="text-decoration:none">
<div class="user-avatar">
{{ strtoupper(substr((is_array(data_get($specialUser,'name')) ? json_encode(data_get($specialUser,'name')) : (data_get($specialUser,'name') ?? $specialUser->email)), 0, 1)) }}
</div>
<div class="user-info">
<div class="user-name">{{ is_array(data_get($specialUser,'name')) ? json_encode(data_get($specialUser,'name')) : (data_get($specialUser,'name') ?? $specialUser->email) }}</div>
<div class="user-role">
<i class="fas fa-user-shield"></i>
{{ $specialUser->role->display_name ?? 'مستخدم' }}
</div>
</div>
</a>
@endforeach
</div>
</div>

<!-- Chat Box -->
<div class="chat-box">
<div class="chat-header">
<div class="user-avatar">
{{ strtoupper(substr((is_array(data_get($user,'name')) ? json_encode(data_get($user,'name')) : (data_get($user,'name') ?? $user->email)), 0, 1)) }}
</div>
<div>
<div style="font-size:1.2rem;font-weight:700">{{ is_array(data_get($user,'name')) ? json_encode(data_get($user,'name')) : (data_get($user,'name') ?? $user->email) }}</div>
<div style="font-size:0.9rem;opacity:0.9">
<i class="fas fa-user-shield"></i>
{{ $user->role->display_name ?? 'مستخدم' }}
</div>
</div>
</div>

<div class="chat-messages" id="chatMessages">
@forelse($messages as $message)
<div class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
<div class="message-avatar">
{{ strtoupper(substr((is_array(data_get($message->sender,'name')) ? json_encode(data_get($message->sender,'name')) : (data_get($message->sender,'name') ?? $message->sender->email)), 0, 1)) }}
</div>
<div class="message-content">
<p class="message-text">{{ $message->message }}</p>
<div class="message-time">{{ $message->created_at->diffForHumans() }}</div>
</div>
</div>
@empty
<div style="text-align:center;padding:3rem;color:#a0aec0">
<i class="fas fa-comments" style="font-size:3rem;margin-bottom:1rem;display:block"></i>
<div>لا توجد رسائل بعد. ابدأ المحادثة!</div>
</div>
@endforelse
</div>

<form class="chat-input" id="messageForm">
@csrf
<input type="hidden" name="receiver_id" value="{{ $user->id }}">
<input type="text" name="message" id="messageInput" placeholder="اكتب رسالتك هنا..." required>
<button type="submit" class="btn-send">
<i class="fas fa-paper-plane"></i> إرسال
</button>
</form>
</div>
</div>
</div>

<script>
// Auto scroll to bottom
const chatMessages = document.getElementById('chatMessages');
chatMessages.scrollTop = chatMessages.scrollHeight;

// Handle message form submission
document.getElementById('messageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageInput = document.getElementById('messageInput');
    
    try {
        const response = await fetch('{{ route("chat.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                receiver_id: formData.get('receiver_id'),
                message: formData.get('message')
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Add message to chat
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message sent';
            messageDiv.innerHTML = `
                <div class="message-avatar">${'{{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}'}</div>
                <div class="message-content">
                    <p class="message-text">${data.message.message}</p>
                    <div class="message-time">الآن</div>
                </div>
            `;
            chatMessages.appendChild(messageDiv);
            
            // Clear input and scroll
            messageInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('حدث خطأ أثناء إرسال الرسالة');
    }
});

// Auto refresh messages every 5 seconds
setInterval(() => {
    location.reload();
}, 30000);
</script>
</body>
</html>
