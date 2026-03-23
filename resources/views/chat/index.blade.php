<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Team Chat - Tulip Store</title>
<!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{font-family:"El Messiri", sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:linear-gradient(135deg,#667eea 0%,#764ba2 50%,#f093fb 100%);min-height:100vh}

.page-header{background:transparent;padding:2rem;margin-top:80px}
.header-content{max-width:1400px;margin:0 auto;display:flex;justify-content:space-between;align-items:center}
.header-title{color:#fff}
.header-title h1{font-size:2.8rem;font-weight:800;margin-bottom:0.5rem;background:linear-gradient(135deg,#fff,#f0f0f0);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:flex;align-items:center;gap:1rem}
.header-title p{color:rgba(255,255,255,0.8);font-size:1.1rem}
.back-btn{background:rgba(255,255,255,0.15);color:#fff;padding:0.9rem 1.8rem;border-radius:50px;text-decoration:none;font-weight:600;backdrop-filter:blur(20px);transition:all 0.4s;border:1px solid rgba(255,255,255,0.2)}
.back-btn:hover{background:rgba(255,255,255,0.25);transform:translateY(-2px);box-shadow:0 10px 30px rgba(0,0,0,0.2)}

.container{max-width:1400px;margin:0 auto;padding:0 2rem 2rem}

.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:2rem}
.stat-box{background:rgba(255,255,255,0.05);padding:2rem;border-radius:20px;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);text-align:center;transition:all 0.4s;position:relative;overflow:hidden}
.stat-box::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#00d9ff,#00ff88)}
.stat-box:hover{transform:translateY(-8px);border-color:rgba(0,217,255,0.3);box-shadow:0 20px 50px rgba(0,217,255,0.15)}
.stat-box i{font-size:2.5rem;margin-bottom:1rem;background:linear-gradient(135deg,#fff,#f0f0f0);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-value{font-size:2.5rem;font-weight:800;color:#fff}
.stat-label{color:rgba(255,255,255,0.6);font-size:0.95rem;margin-top:0.3rem}

.main-grid{display:grid;grid-template-columns:1fr 380px;gap:2rem}

.broadcast-card{background:rgba(255,255,255,0.05);border-radius:24px;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);overflow:hidden;margin-bottom:2rem}
.broadcast-header{background:linear-gradient(135deg,rgba(0,217,255,0.2),rgba(0,255,136,0.2));padding:1.5rem 2rem;border-bottom:1px solid rgba(255,255,255,0.1)}
.broadcast-header h3{font-size:1.3rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:0.8rem}
.broadcast-header h3 i{background:linear-gradient(135deg,#00d9ff,#00ff88);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.broadcast-body{padding:2rem}

.content-card{background:rgba(255,255,255,0.05);border-radius:24px;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);overflow:hidden}
.card-header{padding:1.5rem 2rem;border-bottom:1px solid rgba(255,255,255,0.1);display:flex;justify-content:space-between;align-items:center}
.card-title{font-size:1.3rem;font-weight:700;color:#fff;display:flex;align-items:center;gap:0.8rem}
.card-title i{background:linear-gradient(135deg,#00d9ff,#00ff88);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

.form-group{margin-bottom:1.5rem}
.form-group label{display:block;font-weight:600;color:rgba(255,255,255,0.7);margin-bottom:0.5rem;font-size:0.95rem}
.form-group label i{margin-left:0.4rem;background:linear-gradient(135deg,#00d9ff,#00ff88);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.form-select,.form-textarea{width:100%;padding:1rem 1.2rem;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:14px;font-family:'El Messiri',sans-serif;font-size:1rem;color:#fff;transition:all 0.3s}
.form-select option{background:#1a1a2e;color:#fff}
.form-select:focus,.form-textarea:focus{outline:none;border-color:#00d9ff;box-shadow:0 0 20px rgba(0,217,255,0.2)}
.form-textarea{resize:vertical;min-height:130px}
.form-textarea::placeholder{color:rgba(255,255,255,0.4)}

.checkbox-group{display:flex;align-items:center;gap:0.8rem;margin-bottom:1.5rem;padding:1.2rem;background:rgba(0,217,255,0.08);border-radius:14px;border:1px solid rgba(0,217,255,0.2)}
.checkbox-group input{width:22px;height:22px;accent-color:#00ff88}
.checkbox-group label{font-weight:600;color:rgba(255,255,255,0.8)}

.btn-broadcast{background:linear-gradient(135deg,#00d9ff,#00ff88);color:#1a1a2e;padding:1.1rem 2rem;border:none;border-radius:14px;font-weight:700;cursor:pointer;transition:all 0.4s;display:flex;align-items:center;justify-content:center;gap:0.8rem;font-size:1rem;width:100%}
.btn-broadcast:hover{transform:translateY(-3px);box-shadow:0 15px 40px rgba(0,217,255,0.4)}
.btn-broadcast:disabled{opacity:0.5;cursor:not-allowed;transform:none}

.alert{padding:1rem 1.5rem;border-radius:14px;margin-bottom:1.5rem;display:none;align-items:center;gap:0.8rem}
.alert-success{background:rgba(0,255,136,0.15);color:#00ff88;border:1px solid rgba(0,255,136,0.3)}
.alert-error{background:rgba(255,77,77,0.15);color:#ff4d4d;border:1px solid rgba(255,77,77,0.3)}
.alert.show{display:flex}

.users-list{max-height:calc(100vh - 350px);overflow-y:auto}
.users-list::-webkit-scrollbar{width:6px}
.users-list::-webkit-scrollbar-track{background:rgba(255,255,255,0.05)}
.users-list::-webkit-scrollbar-thumb{background:linear-gradient(135deg,#00d9ff,#00ff88);border-radius:10px}
.user-item{padding:1.3rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.05);cursor:pointer;transition:all 0.3s;display:flex;align-items:center;gap:1rem;text-decoration:none}
.user-item:hover{background:linear-gradient(135deg,rgba(0,217,255,0.1),rgba(0,255,136,0.1))}
.user-avatar{width:55px;height:55px;border-radius:16px;background:linear-gradient(135deg,#00d9ff,#00ff88);display:flex;align-items:center;justify-content:center;color:#1a1a2e;font-weight:700;font-size:1.3rem;flex-shrink:0}
.user-info{flex:1}
.user-name{font-weight:700;color:#fff;margin-bottom:0.3rem;font-size:1.05rem}
.user-role{font-size:0.85rem;color:rgba(255,255,255,0.5);display:flex;align-items:center;gap:0.4rem}
.user-role i{background:linear-gradient(135deg,#00d9ff,#00ff88);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

.welcome-card{background:rgba(255,255,255,0.05);border-radius:24px;backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;flex-direction:column;padding:4rem 2rem;text-align:center;height:100%}
.welcome-card i{font-size:5rem;background:linear-gradient(135deg,#00d9ff,#00ff88);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:1.5rem}
.welcome-card h2{font-size:1.8rem;font-weight:800;color:#fff;margin-bottom:0.5rem}
.welcome-card p{color:rgba(255,255,255,0.5);font-size:1.1rem}

.empty-state{text-align:center;padding:4rem 2rem;color:rgba(255,255,255,0.4)}
.empty-state i{font-size:4rem;margin-bottom:1rem;opacity:0.3}

@media(max-width:1024px){.main-grid{grid-template-columns:1fr}}
@media(max-width:768px){.stats-row{grid-template-columns:1fr}.header-content{flex-direction:column;text-align:center;gap:1rem}}
</style>
</head>
<body>
@include('components.navbar')

<section class="page-header">
<div class="header-content">
<div class="header-title">
<h1><i class="fas fa-comments"></i> Team Chat</h1>
<p>Connect with team members and staff</p>
</div>
<a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
</div>
</section>

<div class="container">
<div class="stats-row">
<div class="stat-box"><i class="fas fa-users"></i><div class="stat-value">{{ $specialUsers->count() }}</div><div class="stat-label">Available Users</div></div>
<div class="stat-box"><i class="fas fa-user-shield"></i><div class="stat-value">{{ \App\Models\Role::count() }}</div><div class="stat-label">User Roles</div></div>
<div class="stat-box"><i class="fas fa-envelope"></i><div class="stat-value">{{ \App\Models\Message::count() }}</div><div class="stat-label">Total Messages</div></div>
</div>

<div class="main-grid">
<div>
@if(auth()->user()->is_admin)
<div class="broadcast-card">
<div class="broadcast-header"><h3><i class="fas fa-bullhorn"></i> إرسال رسالة جماعية</h3></div>
<div class="broadcast-body">
<div id="broadcastAlert" class="alert"></div>
<form id="broadcastForm">
<div class="form-group">
<label><i class="fas fa-user-tag"></i> اختر الدور الوظيفي</label>
<select id="roleSelect" class="form-select">
<option value="">-- اختر الدور --</option>
@php $roles = \App\Models\Role::all(); @endphp
@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->display_name }}</option>@endforeach
</select>
</div>
<div class="checkbox-group"><input type="checkbox" id="sendToAll"><label for="sendToAll">إرسال للجميع (جميع الموظفين)</label></div>
<div class="form-group"><label><i class="fas fa-pen"></i> نص الرسالة</label><textarea id="broadcastMessage" class="form-textarea" placeholder="اكتب رسالتك هنا..."></textarea></div>
<button type="submit" class="btn-broadcast" id="sendBtn"><i class="fas fa-paper-plane"></i> إرسال الرسالة</button>
</form>
</div>
</div>
@endif
<div class="welcome-card"><i class="fas fa-comments"></i><h2>مرحباً بك في المحادثات</h2><p>اختر مستخدم من القائمة لبدء المحادثة</p></div>
</div>

<div class="content-card">
<div class="card-header"><h3 class="card-title"><i class="fas fa-users"></i> قائمة المحادثات</h3></div>
<div class="users-list">
@forelse($specialUsers as $user)
<a href="{{ route('chat.show', $user) }}" class="user-item">
<div class="user-avatar">{{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}</div>
<div class="user-info"><div class="user-name">{{ $user->name ?? $user->email }}</div><div class="user-role"><i class="fas fa-user-shield"></i> {{ $user->role->display_name ?? 'مستخدم' }}</div></div>
</a>
@empty
<div class="empty-state"><i class="fas fa-users-slash"></i><p>لا يوجد مستخدمين متاحين للمحادثة</p></div>
@endforelse
</div>
</div>
</div>
</div>

<script>
document.getElementById('broadcastForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const roleId = document.getElementById('roleSelect').value;
    const sendToAll = document.getElementById('sendToAll').checked;
    const message = document.getElementById('broadcastMessage').value.trim();
    const alertDiv = document.getElementById('broadcastAlert');
    const sendBtn = document.getElementById('sendBtn');
    if (!message) { alertDiv.className = 'alert alert-error show'; alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> يرجى كتابة رسالة'; return; }
    if (!sendToAll && !roleId) { alertDiv.className = 'alert alert-error show'; alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> يرجى اختيار الدور أو تحديد إرسال للجميع'; return; }
    sendBtn.disabled = true; sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
    try {
        const response = await fetch('/api/chat/broadcast', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ role_id: roleId || null, send_to_all: sendToAll, message: message }) });
        const data = await response.json();
        if (data.success) { alertDiv.className = 'alert alert-success show'; alertDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message; document.getElementById('broadcastMessage').value = ''; }
        else { alertDiv.className = 'alert alert-error show'; alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (data.error || 'حدث خطأ أثناء الإرسال'); }
    } catch (error) { alertDiv.className = 'alert alert-error show'; alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> حدث خطأ في الاتصال'; }
    sendBtn.disabled = false; sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال الرسالة';
    setTimeout(() => { alertDiv.classList.remove('show'); }, 5000);
});
document.getElementById('sendToAll')?.addEventListener('change', function() { document.getElementById('roleSelect').disabled = this.checked; });
</script>
</body>
</html>