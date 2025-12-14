<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>إنشاء تذكرة جديدة - Tulip Store</title>
<link rel="stylesheet" href="/css/store.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* {
    font-family: 'Cairo', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    color: #1e293b;
}

.page-header {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    padding: 2rem;
    margin-top: 80px;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
}

.header-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-title h1 {
    color: white;
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.header-title p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
    font-weight: 500;
}

.btn-header {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
}

.btn-header:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.form-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.form-header {
    background: #f8fafc;
    padding: 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.form-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.form-title i {
    color: #3b82f6;
    font-size: 1.8rem;
}

.form-body {
    padding: 2rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.3s;
    background: white;
    color: #1e293b;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.priority-badges {
    display: flex;
    gap: 0.8rem;
    flex-wrap: wrap;
}

.priority-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.priority-badge.low {
    background: #dbeafe;
    color: #2563eb;
}

.priority-badge.medium {
    background: #fef3c7;
    color: #d97706;
}

.priority-badge.high {
    background: #fee2e2;
    color: #dc2626;
}

.priority-badge.urgent {
    background: #fce7f3;
    color: #be185d;
}

.priority-badge.selected {
    border-color: currentColor;
    transform: scale(1.05);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.btn {
    padding: 0.8rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
}

.btn-secondary {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #e2e8f0;
    color: #475569;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.alert-success {
    background: #dcfce7;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.alert-error {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .container {
        padding: 1rem;
    }
}
</style>
</head>
<body>
@include('components.navbar')

<section class="page-header">
<div class="header-content">
<div class="header-title">
<h1><i class="fas fa-plus-circle"></i> إنشاء تذكرة جديدة</h1>
<p>إنشاء تذكرة دعم فني جديدة للعملاء</p>
</div>
<a href="{{ route('cs.dashboard') }}" class="btn-header">
<i class="fas fa-arrow-right"></i> العودة للوحة التحكم
</a>
</div>
</section>

<div class="container">
@if(session('success'))
<div class="alert alert-success">
<i class="fas fa-check-circle"></i>
{{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-error">
<i class="fas fa-exclamation-triangle"></i>
يرجى تصحيح الأخطاء التالية:
<ul style="margin: 0.5rem 0 0 1rem;">
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<div class="form-card">
<div class="form-header">
<h2 class="form-title">
<i class="fas fa-ticket-alt"></i>
بيانات التذكرة الجديدة
</h2>
</div>

<form action="{{ route('cs.tickets.store') }}" method="POST" class="form-body">
@csrf

<div class="form-grid">
<div class="form-group">
<label class="form-label" for="user_id">العميل *</label>
<select name="user_id" id="user_id" class="form-select" required>
<option value="">اختر العميل</option>
@foreach($customers as $customer)
<option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
{{ $customer->name }} ({{ $customer->email }})
</option>
@endforeach
</select>
</div>

<div class="form-group">
<label class="form-label" for="assigned_to">تعيين إلى وكيل</label>
<select name="assigned_to" id="assigned_to" class="form-select">
<option value="">لم يتم التعيين بعد</option>
@foreach($csAgents as $agent)
<option value="{{ $agent->id }}" {{ old('assigned_to') == $agent->id ? 'selected' : '' }}>
{{ $agent->name }}
</option>
@endforeach
</select>
</div>
</div>

<div class="form-group">
<label class="form-label" for="subject">موضوع التذكرة *</label>
<input type="text" name="subject" id="subject" class="form-input" 
       value="{{ old('subject') }}" placeholder="أدخل موضوع التذكرة" required>
</div>

<div class="form-group">
<label class="form-label" for="description">وصف المشكلة *</label>
<textarea name="description" id="description" class="form-textarea" 
          placeholder="اشرح المشكلة بالتفصيل..." required>{{ old('description') }}</textarea>
</div>

<div class="form-grid">
<div class="form-group">
<label class="form-label" for="category">الفئة *</label>
<select name="category" id="category" class="form-select" required>
<option value="">اختر الفئة</option>
<option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>دعم فني</option>
<option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>الفواتير والمدفوعات</option>
<option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>استفسار عام</option>
<option value="complaint" {{ old('category') == 'complaint' ? 'selected' : '' }}>شكوى</option>
<option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>طلب ميزة</option>
</select>
</div>

<div class="form-group">
<label class="form-label">الأولوية *</label>
<div class="priority-badges">
@foreach($priorities as $priority)
<div class="priority-badge {{ $priority }} {{ old('priority') == $priority ? 'selected' : '' }}" 
     data-priority="{{ $priority }}">
{{ ucfirst($priority) }}
</div>
@endforeach
</div>
<input type="hidden" name="priority" id="priority" value="{{ old('priority', 'medium') }}">
</div>
</div>

<div class="form-actions">
<button type="button" onclick="history.back()" class="btn btn-secondary">
<i class="fas fa-times"></i> إلغاء
</button>
<button type="submit" class="btn btn-primary">
<i class="fas fa-save"></i> إنشاء التذكرة
</button>
</div>
</form>
</div>
</div>

<script>
// Priority selection
document.querySelectorAll('.priority-badge').forEach(badge => {
    badge.addEventListener('click', function() {
        // Remove selected class from all badges
        document.querySelectorAll('.priority-badge').forEach(b => b.classList.remove('selected'));
        
        // Add selected class to clicked badge
        this.classList.add('selected');
        
        // Update hidden input
        document.getElementById('priority').value = this.dataset.priority;
    });
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['user_id', 'subject', 'description', 'category', 'priority'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
        if (!input || !input.value.trim()) {
            isValid = false;
            if (input) {
                input.style.borderColor = '#dc2626';
            }
        } else if (input) {
            input.style.borderColor = '#e2e8f0';
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة');
    }
});

// Real-time validation
document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(input => {
    input.addEventListener('input', function() {
        if (this.value.trim()) {
            this.style.borderColor = '#10b981';
        } else {
            this.style.borderColor = '#e2e8f0';
        }
    });
});
</script>

</body>
</html>