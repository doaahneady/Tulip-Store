@extends('dashboards.layouts.app')

@section('title', 'إدارة أكواد الخصم')

@section('content')
@php($title = 'إدارة أكواد الخصم')
@php($subtitle = 'إنشاء ومتابعة أكواد الخصم والعروض الترويجية')

<div class="mb-6">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-black text-gray-900">{{ $title }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
        </div>
        <button onclick="showCreateModal()" class="inline-flex items-center gap-2 bg-gradient-to-r from-rose-600 to-pink-600 text-white px-5 py-2.5 rounded-xl hover:from-rose-700 hover:to-pink-700 transition shadow-lg shadow-rose-500/30">
            <i class="fas fa-plus"></i>
            <span class="font-semibold">إنشاء كود خصم جديد</span>
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl p-5 shadow-lg border border-indigo-500">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-indigo-100 text-xs font-semibold mb-2">إجمالي الأكواد</p>
                <h3 class="text-3xl font-black text-white leading-tight" id="totalCoupons">0</h3>
            </div>
            <div class="bg-white/20 rounded-xl p-3">
                <i class="fas fa-tags text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-5 shadow-lg border border-emerald-500">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-emerald-100 text-xs font-semibold mb-2">الأكواد النشطة</p>
                <h3 class="text-3xl font-black text-white leading-tight" id="activeCoupons">0</h3>
            </div>
            <div class="bg-white/20 rounded-xl p-3">
                <i class="fas fa-check-circle text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 shadow-lg border border-blue-500">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-blue-100 text-xs font-semibold mb-2">إجمالي الاستخدامات</p>
                <h3 class="text-3xl font-black text-white leading-tight" id="totalUsages">0</h3>
            </div>
            <div class="bg-white/20 rounded-xl p-3">
                <i class="fas fa-shopping-cart text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-2xl p-5 shadow-lg border border-amber-500">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-amber-100 text-xs font-semibold mb-2">الأكواد المنتهية</p>
                <h3 class="text-3xl font-black text-white leading-tight" id="expiredCoupons">0</h3>
            </div>
            <div class="bg-white/20 rounded-xl p-3">
                <i class="fas fa-clock text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Coupons Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100">
        <h3 class="text-lg font-black text-gray-900">
            <i class="fas fa-list text-rose-600 ml-2"></i>
            قائمة أكواد الخصم
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">الكود</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">نسبة الخصم</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">الغرض</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">الاستخدامات</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">تاريخ الانتهاء</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">تم الإنشاء بواسطة</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="couponsTableBody" class="bg-white divide-y divide-gray-200">
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex justify-center">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-rose-600"></div>
                        </div>
                        <p class="text-gray-500 mt-3">جاري التحميل...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<!-- Create Coupon Modal -->
<div id="createCouponModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-rose-600 to-pink-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">إنشاء كود خصم جديد</h3>
            <button onclick="hideCreateModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="createCouponForm" class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-tag text-rose-600 ml-1"></i>
                    كود الخصم *
                </label>
                <input type="text" name="code" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition uppercase"
                       placeholder="مثال: SUMMER2026">
                <p class="text-xs text-gray-500 mt-1.5">سيتم تحويل الأحرف إلى أحرف كبيرة تلقائياً</p>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-percent text-rose-600 ml-1"></i>
                    نسبة الخصم (%) *
                </label>
                <input type="number" name="discount_percentage" required min="1" max="100" step="0.01"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition"
                       placeholder="مثال: 20">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-align-right text-rose-600 ml-1"></i>
                    الغرض من الكود
                </label>
                <textarea name="purpose" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition resize-none"
                          placeholder="مثال: عرض الصيف للعملاء الجدد"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-hashtag text-rose-600 ml-1"></i>
                        الحد الأقصى للاستخدامات
                    </label>
                    <input type="number" name="max_uses" min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition"
                           placeholder="غير محدود">
                    <p class="text-xs text-gray-500 mt-1.5">اترك فارغاً للسماح باستخدامات غير محدودة</p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-calendar text-rose-600 ml-1"></i>
                        تاريخ الانتهاء
                    </label>
                    <input type="datetime-local" name="expires_at"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition">
                    <p class="text-xs text-gray-500 mt-1.5">اترك فارغاً إذا لم يكن هناك تاريخ انتهاء</p>
                </div>
            </div>
        </form>
        
        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex items-center justify-end gap-3">
            <button onclick="hideCreateModal()" class="px-5 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-semibold">
                إلغاء
            </button>
            <button onclick="createCoupon()" class="px-5 py-2.5 bg-gradient-to-r from-rose-600 to-pink-600 text-white rounded-xl hover:from-rose-700 hover:to-pink-700 transition font-semibold shadow-lg shadow-rose-500/30">
                <i class="fas fa-save ml-2"></i>
                إنشاء الكود
            </button>
        </div>
    </div>
</div>


<!-- Usage Details Modal -->
<div id="usageModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">
                تفاصيل استخدام الكود: <span id="usageModalCode" class="font-mono"></span>
            </h3>
            <button onclick="hideUsageModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">المستخدم</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">رقم الطلب</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">قيمة الخصم</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">إجمالي الطلب</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">تاريخ الاستخدام</th>
                        </tr>
                    </thead>
                    <tbody id="usageTableBody" class="divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex items-center justify-end">
            <button onclick="hideUsageModal()" class="px-5 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-semibold">
                إغلاق
            </button>
        </div>
    </div>
</div>

<script>
let coupons = [];

document.addEventListener('DOMContentLoaded', () => {
    loadCoupons();
});

function showCreateModal() {
    document.getElementById('createCouponModal').classList.remove('hidden');
}

function hideCreateModal() {
    document.getElementById('createCouponModal').classList.add('hidden');
    document.getElementById('createCouponForm').reset();
}

function hideUsageModal() {
    document.getElementById('usageModal').classList.add('hidden');
}


async function loadCoupons() {
    try {
        const response = await fetch('/api/support/coupons', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        coupons = data.coupons;
        renderCoupons();
        updateStatistics();
    } catch (error) {
        console.error('Error loading coupons:', error);
        showToast('فشل تحميل الأكواد', 'error');
    }
}

function renderCoupons() {
    const tbody = document.getElementById('couponsTableBody');
    
    if (coupons.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-tags text-gray-300 text-5xl mb-3"></i>
                        <p class="text-gray-500 font-semibold">لا توجد أكواد خصم</p>
                        <p class="text-gray-400 text-sm mt-1">ابدأ بإنشاء كود خصم جديد</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = coupons.map(coupon => {
        const isExpired = coupon.expires_at && new Date(coupon.expires_at) < new Date();
        const isMaxedOut = coupon.max_uses && coupon.used_count >= coupon.max_uses;
        
        let statusBadge = '';
        if (!coupon.is_active) {
            statusBadge = '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700"><i class="fas fa-pause ml-1"></i> غير نشط</span>';
        } else if (isExpired) {
            statusBadge = '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><i class="fas fa-clock ml-1"></i> منتهي</span>';
        } else if (isMaxedOut) {
            statusBadge = '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700"><i class="fas fa-check-circle ml-1"></i> مكتمل</span>';
        } else {
            statusBadge = '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700"><i class="fas fa-check-circle ml-1"></i> نشط</span>';
        }
        
        return `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-mono font-bold text-gray-900 bg-gray-100 px-3 py-1.5 rounded-lg">${coupon.code}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 font-bold">
                        <i class="fas fa-percent ml-1"></i>
                        ${coupon.discount_percentage}%
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-700">${coupon.purpose || '-'}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-900">${coupon.used_count}</span>
                        ${coupon.max_uses ? `<span class="text-sm text-gray-500">/ ${coupon.max_uses}</span>` : '<span class="text-sm text-gray-500">/ ∞</span>'}
                        ${coupon.used_count > 0 ? `<button onclick="showUsageDetails(${coupon.id})" class="text-blue-600 hover:text-blue-800 text-xs font-semibold underline">عرض</button>` : ''}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    ${coupon.expires_at ? new Date(coupon.expires_at).toLocaleDateString('ar-SA', {year: 'numeric', month: 'short', day: 'numeric'}) : '<span class="text-gray-400">بدون انتهاء</span>'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    ${coupon.creator?.name || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <button onclick="toggleCouponStatus(${coupon.id}, ${!coupon.is_active})" 
                                class="p-2 rounded-lg ${coupon.is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'} transition" 
                                title="${coupon.is_active ? 'إيقاف' : 'تفعيل'}">
                            <i class="fas fa-${coupon.is_active ? 'pause' : 'play'}"></i>
                        </button>
                        <button onclick="deleteCoupon(${coupon.id})" 
                                class="p-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition" 
                                title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}


function updateStatistics() {
    document.getElementById('totalCoupons').textContent = coupons.length;
    document.getElementById('activeCoupons').textContent = coupons.filter(c => c.is_active).length;
    document.getElementById('totalUsages').textContent = coupons.reduce((sum, c) => sum + c.used_count, 0);
    
    const expired = coupons.filter(c => {
        if (!c.expires_at) return false;
        return new Date(c.expires_at) < new Date();
    }).length;
    document.getElementById('expiredCoupons').textContent = expired;
}

async function createCoupon() {
    const form = document.getElementById('createCouponForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Remove empty values
    Object.keys(data).forEach(key => {
        if (data[key] === '') delete data[key];
    });
    
    console.log('Sending coupon data:', data);
    
    try {
        const response = await fetch('/api/support/coupons', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        console.log('Server response:', result);
        
        if (response.ok && result.success) {
            showToast('تم إنشاء كود الخصم بنجاح', 'success');
            hideCreateModal();
            loadCoupons();
        } else {
            showToast(result.message || 'فشل إنشاء الكود', 'error');
            console.error('Server error:', result);
        }
    } catch (error) {
        console.error('Error creating coupon:', error);
        showToast('حدث خطأ أثناء إنشاء الكود', 'error');
    }
}

async function toggleCouponStatus(id, newStatus) {
    try {
        const response = await fetch(`/api/support/coupons/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_active: newStatus })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('تم تحديث حالة الكود بنجاح', 'success');
            loadCoupons();
        } else {
            showToast(result.message || 'فشل تحديث الحالة', 'error');
        }
    } catch (error) {
        console.error('Error updating coupon:', error);
        showToast('حدث خطأ أثناء تحديث الحالة', 'error');
    }
}

async function deleteCoupon(id) {
    if (!confirm('هل أنت متأكد من حذف هذا الكود؟ لا يمكن التراجع عن هذا الإجراء.')) return;
    
    try {
        const response = await fetch(`/api/support/coupons/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('تم حذف الكود بنجاح', 'success');
            loadCoupons();
        } else {
            showToast(result.message || 'فشل حذف الكود', 'error');
        }
    } catch (error) {
        console.error('Error deleting coupon:', error);
        showToast('حدث خطأ أثناء حذف الكود', 'error');
    }
}


async function showUsageDetails(id) {
    try {
        const response = await fetch(`/api/support/coupons/${id}/usage`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        document.getElementById('usageModalCode').textContent = data.coupon.code;
        
        const tbody = document.getElementById('usageTableBody');
        
        if (data.usages.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        لا توجد استخدامات لهذا الكود
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = data.usages.map(usage => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">${usage.user?.name || 'غير معروف'}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="font-mono text-blue-600">#${usage.order_id || '-'}</span>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-emerald-600">${usage.discount_amount} $</td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">${usage.order_total} $</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${new Date(usage.used_at).toLocaleString('ar-SA')}</td>
                </tr>
            `).join('');
        }
        
        document.getElementById('usageModal').classList.remove('hidden');
    } catch (error) {
        console.error('Error loading usage details:', error);
        showToast('فشل تحميل تفاصيل الاستخدام', 'error');
    }
}

function showToast(message, type = 'info') {
    const colors = {
        success: 'bg-emerald-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        info: 'fa-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 left-4 ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl z-[100] flex items-center gap-3 animate-slide-in-left`;
    toast.innerHTML = `
        <i class="fas ${icons[type]} text-xl"></i>
        <span class="font-semibold">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-slide-out-left');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}
</script>

<style>
@keyframes slide-in-left {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out-left {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(-100%);
        opacity: 0;
    }
}

.animate-slide-in-left {
    animation: slide-in-left 0.3s ease-out;
}

.animate-slide-out-left {
    animation: slide-out-left 0.3s ease-in;
}
</style>

@endsection
