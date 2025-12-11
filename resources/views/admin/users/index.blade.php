<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>إدارة المستخدمين - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{font-family:'El Messiri',sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:#f8fafc;min-height:100vh}

.page-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:3rem 2rem;margin-top:80px;position:relative;overflow:hidden}
.page-header::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")}
.header-content{max-width:1400px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;position:relative;z-index:1}
.header-title{color:#fff}
.header-title h1{font-size:2.5rem;font-weight:800;margin-bottom:0.5rem;display:flex;align-items:center;gap:1rem}
.header-title p{opacity:0.9;font-size:1.1rem}
.back-btn{background:rgba(255,255,255,0.2);color:#fff;padding:0.8rem 1.5rem;border-radius:12px;text-decoration:none;font-weight:600;backdrop-filter:blur(10px);transition:all 0.3s;border:1px solid rgba(255,255,255,0.3)}
.back-btn:hover{background:rgba(255,255,255,0.3);transform:translateY(-2px)}

.container{max-width:1400px;margin:0 auto;padding:2rem}

.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-top:-3rem;margin-bottom:2rem;position:relative;z-index:10}
.stat-box{background:#fff;padding:1.5rem;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.1);text-align:center;transition:all 0.3s}
.stat-box:hover{transform:translateY(-5px)}
.stat-box i{font-size:2rem;margin-bottom:0.8rem;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-value{font-size:2.2rem;font-weight:800;color:#1a1a1a}
.stat-label{color:#666;font-size:0.9rem;margin-top:0.3rem}

.content-card{background:#fff;border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,0.08);overflow:hidden;margin-bottom:2rem}
.card-header{padding:1.5rem 2rem;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.card-title{font-size:1.3rem;font-weight:700;color:#1a1a1a;display:flex;align-items:center;gap:0.8rem}
.card-title i{color:#667eea}

.tabs{display:flex;gap:0.5rem}
.tab-btn{padding:0.6rem 1.2rem;border:none;border-radius:25px;font-weight:600;cursor:pointer;transition:all 0.3s;font-size:0.9rem;background:#f5f5f5;color:#666}
.tab-btn.active{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff}
.tab-btn:hover:not(.active){background:#e0e0e0}

.filters{padding:1.5rem 2rem;background:#f8fafc;display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap}
.filter-group{display:flex;flex-direction:column;flex:1;min-width:200px}
.filter-group label{font-weight:600;color:#555;margin-bottom:0.5rem;font-size:0.85rem}
.filter-input{padding:0.75rem 1rem;border:2px solid #e0e0e0;border-radius:10px;font-size:0.95rem;transition:all 0.3s}
.filter-input:focus{outline:none;border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,0.1)}
.btn-search{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:0.75rem 1.5rem;border:none;border-radius:10px;font-weight:600;cursor:pointer;transition:all 0.3s}
.btn-search:hover{transform:translateY(-2px);box-shadow:0 4px 15px rgba(102,126,234,0.4)}

table{width:100%;border-collapse:collapse}
th{background:#f8fafc;padding:1rem 1.5rem;text-align:right;font-weight:700;color:#555;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px}
td{padding:1rem 1.5rem;border-bottom:1px solid #f5f5f5}
tr:hover{background:#fafafa}

.user-cell{display:flex;align-items:center;gap:1rem}
.user-avatar{width:45px;height:45px;border-radius:12px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem}
.user-name{font-weight:700;color:#1a1a1a}
.user-email{font-size:0.85rem;color:#888}

.badge{display:inline-flex;align-items:center;gap:0.4rem;padding:0.4rem 0.9rem;border-radius:20px;font-size:0.8rem;font-weight:600}
.badge-customer{background:#e3f2fd;color:#1565c0}
.badge-admin{background:#fce4ec;color:#c2185b}
.badge-hr{background:#e8f5e9;color:#2e7d32}
.badge-accountant{background:#fff3e0;color:#ef6c00}
.badge-it{background:#f3e5f5;color:#7b1fa2}
.badge-it_supervisor{background:#ede7f6;color:#512da8}
.badge-driver_supervisor{background:#e0f2f1;color:#00695c}
.badge-cs_agent{background:#fbe9e7;color:#d84315}

.action-btn{width:36px;height:36px;border-radius:10px;border:none;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;justify-content:center;margin:0 0.15rem}
.action-btn.view{background:#e3f2fd;color:#1565c0}
.action-btn.edit{background:#fff3e0;color:#ef6c00}
.action-btn.delete{background:#ffebee;color:#c62828}
.action-btn:hover{transform:scale(1.1);box-shadow:0 4px 12px rgba(0,0,0,0.15)}

.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(5px)}
.modal.show{display:flex}
.modal-content{background:#fff;border-radius:20px;padding:2rem;max-width:500px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3);animation:modalIn 0.3s ease}
@keyframes modalIn{from{opacity:0;transform:scale(0.9)}to{opacity:1;transform:scale(1)}}
.modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid #f0f0f0}
.modal-header h3{font-size:1.4rem;font-weight:700;color:#1a1a1a;display:flex;align-items:center;gap:0.8rem}
.modal-header h3 i{color:#667eea}
.modal-close{background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;transition:all 0.3s}
.modal-close:hover{color:#333;transform:rotate(90deg)}
.form-group{margin-bottom:1.5rem}
.form-group label{display:block;font-weight:600;color:#555;margin-bottom:0.5rem}
.form-group select{width:100%;padding:0.9rem 1rem;border:2px solid #e0e0e0;border-radius:12px;font-size:1rem;transition:all 0.3s}
.form-group select:focus{outline:none;border-color:#667eea}
.btn-save{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:1rem 2rem;border:none;border-radius:12px;font-weight:700;cursor:pointer;width:100%;font-size:1rem;transition:all 0.3s}
.btn-save:hover{box-shadow:0 8px 25px rgba(102,126,234,0.4);transform:translateY(-2px)}

.alert{padding:1rem 1.5rem;border-radius:12px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.8rem}
.alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
.alert-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}

.empty-state{text-align:center;padding:4rem 2rem;color:#999}
.empty-state i{font-size:4rem;margin-bottom:1rem;opacity:0.3}

@media(max-width:1024px){.stats-row{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.stats-row{grid-template-columns:1fr}.header-content{flex-direction:column;text-align:center;gap:1rem}.filters{flex-direction:column}}
</style>
</head>
<body>
@include('components.navbar')

<section class="page-header">
<div class="header-content">
<div class="header-title">
<h1><i class="fas fa-users-cog"></i> إدارة المستخدمين</h1>
<p>إدارة حسابات العملاء والموظفين وصلاحياتهم</p>
</div>
<a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة</a>
</div>
</section>

<div class="container">
@if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

<div class="stats-row">
<div class="stat-box"><i class="fas fa-users"></i><div class="stat-value">{{ $stats['total'] ?? 0 }}</div><div class="stat-label">إجمالي المستخدمين</div></div>
<div class="stat-box"><i class="fas fa-user-tie"></i><div class="stat-value">{{ $stats['workers'] ?? 0 }}</div><div class="stat-label">الموظفين</div></div>
<div class="stat-box"><i class="fas fa-user"></i><div class="stat-value">{{ $stats['customers'] ?? 0 }}</div><div class="stat-label">العملاء</div></div>
<div class="stat-box"><i class="fas fa-user-plus"></i><div class="stat-value">{{ $stats['new_this_month'] ?? 0 }}</div><div class="stat-label">جدد هذا الشهر</div></div>
</div>

<div class="content-card">
<div class="card-header">
<h3 class="card-title"><i class="fas fa-list"></i> قائمة المستخدمين</h3>
<div class="tabs">
<button class="tab-btn {{ !request('type') ? 'active' : '' }}" onclick="location.href='{{ route('admin.users.index') }}'">الكل</button>
<button class="tab-btn {{ request('type')=='workers' ? 'active' : '' }}" onclick="location.href='{{ route('admin.users.index',['type'=>'workers']) }}'">الموظفين</button>
<button class="tab-btn {{ request('type')=='customers' ? 'active' : '' }}" onclick="location.href='{{ route('admin.users.index',['type'=>'customers']) }}'">العملاء</button>
</div>
</div>

<form method="GET" class="filters">
<input type="hidden" name="type" value="{{ request('type') }}">
<div class="filter-group">
<label><i class="fas fa-search"></i> بحث</label>
<input type="text" name="search" class="filter-input" placeholder="الاسم، البريد، الهاتف..." value="{{ request('search') }}">
</div>
<div class="filter-group">
<label><i class="fas fa-user-tag"></i> الدور</label>
<select name="role" class="filter-input">
<option value="">الكل</option>
<option value="customer" {{ request('role')=='customer' ? 'selected' : '' }}>عميل</option>
@foreach($roles as $role)<option value="{{ $role->id }}" {{ request('role')==$role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>@endforeach
</select>
</div>
<button type="submit" class="btn-search"><i class="fas fa-filter"></i> تصفية</button>
</form>

<table>
<thead><tr><th>المستخدم</th><th>الهاتف</th><th>الدور</th><th>الطلبات</th><th>التسجيل</th><th>الإجراءات</th></tr></thead>
<tbody>
@forelse($users as $user)
<tr>
<td><div class="user-cell"><div class="user-avatar">{{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}</div><div><div class="user-name">{{ $user->name ?? 'بدون اسم' }}</div><div class="user-email">{{ $user->email }}</div></div></div></td>
<td>{{ $user->phone ?? $user->mobile ?? '-' }}</td>
<td>@if($user->role)<span class="badge badge-{{ strtolower($user->role->name) }}"><i class="fas fa-user-shield"></i> {{ $user->role->display_name }}</span>@else<span class="badge badge-customer"><i class="fas fa-user"></i> عميل</span>@endif</td>
<td><strong>{{ $user->orders_count ?? 0 }}</strong></td>
<td>{{ $user->created_at->format('Y/m/d') }}</td>
<td>
<button class="action-btn view" onclick="location.href='{{ route('admin.users.show', $user) }}'" title="عرض"><i class="fas fa-eye"></i></button>
@if($user->id !== auth()->id())
<button class="action-btn edit" onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', {{ $user->role_id ?? 'null' }})" title="تعديل الدور"><i class="fas fa-user-cog"></i></button>
<form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('هل أنت متأكد؟')">@csrf @method('DELETE')<button type="submit" class="action-btn delete" title="حذف"><i class="fas fa-trash"></i></button></form>
@endif
</td>
</tr>
@empty
<tr><td colspan="6"><div class="empty-state"><i class="fas fa-users-slash"></i><p>لا يوجد مستخدمين</p></div></td></tr>
@endforelse
</tbody>
</table>
</div>
<div style="margin-top:1rem">{{ $users->appends(request()->query())->links() }}</div>
</div>

<div class="modal" id="roleModal">
<div class="modal-content">
<div class="modal-header"><h3><i class="fas fa-user-cog"></i> تعديل الدور</h3><button class="modal-close" onclick="closeRoleModal()">&times;</button></div>
<p style="color:#666;margin-bottom:1.5rem">المستخدم: <strong id="modalUserName"></strong></p>
<form id="roleForm" method="POST">@csrf @method('PUT')
<div class="form-group"><label>اختر الدور</label><select name="role_id" id="roleSelect"><option value="">عميل عادي</option>@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->display_name }}</option>@endforeach</select></div>
<button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ</button>
</form>
</div>
</div>

<script>
function openRoleModal(id,name,roleId){document.getElementById('modalUserName').textContent=name;document.getElementById('roleForm').action=`/admin/users/${id}/role`;document.getElementById('roleSelect').value=roleId||'';document.getElementById('roleModal').classList.add('show')}
function closeRoleModal(){document.getElementById('roleModal').classList.remove('show')}
document.getElementById('roleModal').addEventListener('click',function(e){if(e.target===this)closeRoleModal()});
</script>
</body>
</html>