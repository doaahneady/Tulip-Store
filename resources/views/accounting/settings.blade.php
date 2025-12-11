@extends('layouts.accounting')

@section('title', 'الإعدادات')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-cog"></i> إعدادات النظام المحاسبي</h1>
    <p>إدارة إعدادات وتفضيلات النظام المحاسبي</p>
</div>

<!-- Company Information -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-building"></i>
        <span>معلومات الشركة</span>
        <div style="margin-right: auto;">
            <button class="btn btn-primary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-save"></i> حفظ التغييرات
            </button>
        </div>
    </div>
    <div style="padding: 2rem;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem;">اسم الشركة</label>
                <input type="text" value="شركة المنارة التجارية" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
            </div>
            <div>
                <label style="display: block; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem;">الرقم الضريبي</label>
                <input type="text" value="300123456789003" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; font-family: monospace;">
            </div>
            <div>
                <label style="display: block; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem;">السجل التجاري</label>
                <input type="text" value="1010123456" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; font-family: monospace;">
            </div>
            <div>
                <label style="display: block; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem;">رقم الهاتف</label>
                <input type="text" value="+966 11 234 5678" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem;">العنوان</label>
                <textarea rows="2" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">الرياض، المملكة العربية السعودية</textarea>
            </div>
        </div>
    </div>
</div>

<!-- Fiscal Year Settings -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-calendar-alt"></i>
        <span>إعدادات السنة المالية</span>
    </div>
    <div style="padding: 2rem;">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem;">بداية السنة المالية</label>
                <input type="date" value="2025-01-01" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
            </div>
            <div>
                <label style="display: block; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem;">نهاية السنة المالية</label>
                <input type="date" value="2025-12-31" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
            </div>
            <div>
                <label style="display: block; font-weight: 700; color: #1e3a8a; margin-bottom: 0.5rem;">العملة الأساسية</label>
                <select style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                    <option value="SAR">ريال سعودي (SAR)</option>
                    <option value="USD">دولار أمريكي (USD)</option>
                    <option value="EUR">يورو (EUR)</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Accounting Preferences -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-sliders-h"></i>
        <span>التفضيلات المحاسبية</span>
    </div>
    <div style="padding: 2rem;">
        <div style="display: grid; gap: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">طريقة تقييم المخزون</div>
                    <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem;">اختر طريقة تقييم المخزون (FIFO, LIFO, المتوسط المرجح)</div>
                </div>
                <select style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; min-width: 200px;">
                    <option value="fifo">FIFO - الوارد أولاً صادر أولاً</option>
                    <option value="lifo">LIFO - الوارد أخيراً صادر أولاً</option>
                    <option value="avg">المتوسط المرجح</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">طريقة الاستهلاك</div>
                    <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem;">طريقة احتساب استهلاك الأصول الثابتة</div>
                </div>
                <select style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; min-width: 200px;">
                    <option value="straight">القسط الثابت</option>
                    <option value="declining">القسط المتناقص</option>
                    <option value="units">وحدات الإنتاج</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">نسبة الضريبة الافتراضية</div>
                    <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem;">نسبة ضريبة القيمة المضافة</div>
                </div>
                <input type="number" value="15" step="0.01" style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; min-width: 200px; text-align: center; font-family: monospace;">
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">عدد الأرقام العشرية</div>
                    <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem;">عدد الأرقام العشرية في العرض</div>
                </div>
                <select style="padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; min-width: 200px;">
                    <option value="0">0 (بدون كسور)</option>
                    <option value="2" selected>2 (مثال: 100.50)</option>
                    <option value="3">3 (مثال: 100.500)</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Journal Entry Settings -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-book"></i>
        <span>إعدادات القيود</span>
    </div>
    <div style="padding: 2rem;">
        <div style="display: grid; gap: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">السماح بتعديل القيود المرحلة</div>
                    <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem;">السماح بتعديل القيود بعد ترحيلها</div>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #dc2626; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">الترحيل التلقائي للقيود</div>
                    <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem;">ترحيل القيود تلقائياً عند الحفظ</div>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #dc2626; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">طلب وصف تفصيلي للقيود</div>
                    <div style="font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem;">جعل حقل الوصف إلزامياً</div>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                    <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #047857; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
        </div>
    </div>
</div>

<!-- Backup & Security -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-shield-alt"></i>
        <span>النسخ الاحتياطي والأمان</span>
    </div>
    <div style="padding: 2rem;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div style="padding: 1.5rem; background: #eff6ff; border-radius: 8px; border-right: 4px solid #1e3a8a;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <i class="fas fa-database" style="font-size: 2rem; color: #1e3a8a;"></i>
                    <div>
                        <div style="font-weight: 700; color: #1e3a8a; font-size: 1.1rem;">النسخ الاحتياطي التلقائي</div>
                        <div style="font-size: 0.9rem; color: #6b7280;">آخر نسخة: 2025-12-02 03:00 AM</div>
                    </div>
                </div>
                <button class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-download"></i> إنشاء نسخة احتياطية الآن
                </button>
            </div>

            <div style="padding: 1.5rem; background: #d1fae5; border-radius: 8px; border-right: 4px solid #047857;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <i class="fas fa-lock" style="font-size: 2rem; color: #047857;"></i>
                    <div>
                        <div style="font-weight: 700; color: #047857; font-size: 1.1rem;">سجل التدقيق</div>
                        <div style="font-size: 0.9rem; color: #6b7280;">تتبع جميع العمليات</div>
                    </div>
                </div>
                <button class="btn btn-secondary" style="width: 100%;">
                    <i class="fas fa-eye"></i> عرض سجل التدقيق
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Settings -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-bell"></i>
        <span>إعدادات الإشعارات</span>
    </div>
    <div style="padding: 2rem;">
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">إشعارات القيود غير المتوازنة</div>
                </div>
                <input type="checkbox" checked style="width: 20px; height: 20px;">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">إشعارات المخزون المنخفض</div>
                </div>
                <input type="checkbox" checked style="width: 20px; height: 20px;">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">إشعارات الفواتير المستحقة</div>
                </div>
                <input type="checkbox" checked style="width: 20px; height: 20px;">
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 700; color: #1e3a8a;">تقارير نهاية الشهر التلقائية</div>
                </div>
                <input type="checkbox" style="width: 20px; height: 20px;">
            </div>
        </div>
    </div>
</div>
@endsection
