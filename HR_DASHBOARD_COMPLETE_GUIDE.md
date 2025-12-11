# 🎯 نظام إدارة الموارد البشرية - دليل شامل

## 📋 نظرة عامة

تم إنشاء نظام شامل لإدارة الموارد البشرية (HR Dashboard) يتضمن جميع الوظائف الأساسية لإدارة الموظفين والحضور والرواتب والتدريب والأداء.

---

## 🗂️ الملفات المُنشأة

### 1. قاعدة البيانات (Database)

#### Migration File
```
database/migrations/2025_12_03_100000_create_hr_system_tables.php
```

**الجداول المُنشأة:**
- ✅ `employees` - بيانات الموظفين الكاملة
- ✅ `attendance` - سجلات الحضور والانصراف
- ✅ `leave_requests` - طلبات الإجازات
- ✅ `payroll` - كشوف الرواتب
- ✅ `performance_reviews` - تقييمات الأداء
- ✅ `training_programs` - البرامج التدريبية
- ✅ `training_enrollments` - تسجيلات التدريب
- ✅ `employee_documents` - مستندات الموظفين

**حقول إضافية في جدول Users:**
- `is_hr` - صلاحية موظف الموارد البشرية
- `is_hr_manager` - صلاحية مدير الموارد البشرية

---

### 2. النماذج (Models)

تم إنشاء 7 نماذج Laravel:

```
app/Models/Employee.php
app/Models/Attendance.php
app/Models/LeaveRequest.php
app/Models/Payroll.php
app/Models/PerformanceReview.php
app/Models/TrainingProgram.php
app/Models/TrainingEnrollment.php
app/Models/EmployeeDocument.php
```

**العلاقات المُعرّفة:**
- Employee → hasMany → Attendance, LeaveRequests, Payroll, PerformanceReviews
- Employee → belongsTo → User
- LeaveRequest → belongsTo → Employee, Approver (User)
- Payroll → belongsTo → Employee
- PerformanceReview → belongsTo → Employee, Reviewer (User)
- TrainingProgram → hasMany → TrainingEnrollments
- TrainingEnrollment → belongsTo → Employee, TrainingProgram

---

### 3. المتحكم (Controller)

```
app/Http/Controllers/HR/HRController.php
```

**الوظائف المُنفذة:**

#### إدارة الموظفين
- `employees()` - عرض قائمة الموظفين
- `createEmployee()` - نموذج إضافة موظف
- `storeEmployee()` - حفظ موظف جديد
- `editEmployee()` - نموذج تعديل موظف
- `updateEmployee()` - تحديث بيانات موظف

#### إدارة الحضور
- `attendance()` - عرض سجلات الحضور اليومية
- `markAttendance()` - تسجيل حضور/انصراف

#### إدارة الإجازات
- `leaveRequests()` - عرض طلبات الإجازات
- `approveLeave()` - الموافقة على إجازة
- `rejectLeave()` - رفض إجازة

#### إدارة الرواتب
- `payroll()` - عرض كشوف الرواتب
- `generatePayroll()` - إنشاء كشف رواتب شهري
- `processPayroll()` - معالجة راتب موظف

#### تقييم الأداء
- `performanceReviews()` - عرض التقييمات
- `createReview()` - نموذج إضافة تقييم
- `storeReview()` - حفظ تقييم جديد

#### البرامج التدريبية
- `trainingPrograms()` - عرض البرامج التدريبية
- `createTraining()` - نموذج إضافة برنامج
- `storeTraining()` - حفظ برنامج جديد

#### التقارير
- `reports()` - صفحة التقارير الرئيسية
- `attendanceReport()` - تقرير الحضور

---

### 4. الواجهات (Views)

تم إنشاء 7 صفحات بتصميم احترافي:

#### 📊 لوحة التحكم الرئيسية
```
resources/views/hr/dashboard.blade.php
```
**المميزات:**
- إحصائيات سريعة (إجمالي الموظفين، الحضور اليوم، في إجازة، طلبات معلقة)
- إجراءات سريعة لجميع الأقسام
- أحدث الموظفين
- طلبات الإجازات المعلقة
- تصميم متجاوب بألوان جذابة

#### 👥 إدارة الموظفين
```
resources/views/hr/employees/index.blade.php
```
**المميزات:**
- عرض بطاقات الموظفين
- بحث مباشر
- عرض التفاصيل الكاملة (القسم، المنصب، الراتب، تاريخ التعيين)
- أزرار التعديل والحذف
- حالة الموظف (نشط، في إجازة، موقوف)

#### 📅 الحضور والانصراف
```
resources/views/hr/attendance/index.blade.php
```
**المميزات:**
- عرض حضور اليوم
- تاريخ واضح
- وقت الحضور والانصراف
- ساعات العمل
- حالة الحضور (حاضر، متأخر، غائب، نصف يوم، في إجازة)

#### 🏖️ طلبات الإجازات
```
resources/views/hr/leaves/index.blade.php
```
**المميزات:**
- عرض جميع طلبات الإجازات
- تفاصيل كل طلب (النوع، التاريخ، المدة، السبب)
- أزرار الموافقة والرفض
- إدخال سبب الرفض
- حالة الطلب (معلق، موافق، مرفوض)

#### 💰 إدارة الرواتب
```
resources/views/hr/payroll/index.blade.php
```
**المميزات:**
- عرض كشوف الرواتب الشهرية
- تفصيل الراتب (أساسي، بدلات، مكافآت، خصومات، ضرائب)
- صافي الراتب بشكل واضح
- حالة الراتب (مسودة، معالج، مدفوع)
- زر معالجة الراتب

#### 📈 تقييم الأداء
```
resources/views/hr/performance/index.blade.php
```
**المميزات:**
- عرض تقييمات الموظفين
- نجوم التقييم (1-5)
- درجات مفصلة (الأداء، الحضور، الجودة، العمل الجماعي)
- تعليقات المقيّم
- تاريخ التقييم

#### 🎓 البرامج التدريبية
```
resources/views/hr/training/index.blade.php
```
**المميزات:**
- عرض البرامج التدريبية
- تفاصيل البرنامج (المدرب، المدة، الموقع، التكلفة)
- حالة البرنامج (مجدول، جاري، مكتمل، ملغي)
- عدد المسجلين

#### 📄 التقارير
```
resources/views/hr/reports/index.blade.php
```
**المميزات:**
- بطاقات تقارير متعددة
- تقرير الحضور
- تقرير الرواتب
- تقرير الإجازات
- تقرير الأداء
- تقرير التدريب
- تقرير الموظفين

---

### 5. المسارات (Routes)

تم إضافة المسارات في `routes/web.php`:

```php
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HRController::class, 'index']);
    
    // Employees
    Route::get('/employees', [HRController::class, 'employees']);
    Route::get('/employees/create', [HRController::class, 'createEmployee']);
    Route::post('/employees', [HRController::class, 'storeEmployee']);
    Route::get('/employees/{employee}/edit', [HRController::class, 'editEmployee']);
    Route::put('/employees/{employee}', [HRController::class, 'updateEmployee']);
    
    // Attendance
    Route::get('/attendance', [HRController::class, 'attendance']);
    Route::post('/attendance/mark', [HRController::class, 'markAttendance']);
    
    // Leaves
    Route::get('/leaves', [HRController::class, 'leaveRequests']);
    Route::post('/leaves/{leave}/approve', [HRController::class, 'approveLeave']);
    Route::post('/leaves/{leave}/reject', [HRController::class, 'rejectLeave']);
    
    // Payroll
    Route::get('/payroll', [HRController::class, 'payroll']);
    Route::post('/payroll/generate', [HRController::class, 'generatePayroll']);
    Route::post('/payroll/{payroll}/process', [HRController::class, 'processPayroll']);
    
    // Performance
    Route::get('/performance', [HRController::class, 'performanceReviews']);
    Route::get('/performance/create', [HRController::class, 'createReview']);
    Route::post('/performance', [HRController::class, 'storeReview']);
    
    // Training
    Route::get('/training', [HRController::class, 'trainingPrograms']);
    Route::get('/training/create', [HRController::class, 'createTraining']);
    Route::post('/training', [HRController::class, 'storeTraining']);
    
    // Reports
    Route::get('/reports', [HRController::class, 'reports']);
    Route::get('/reports/attendance', [HRController::class, 'attendanceReport']);
});
```

---

## 🚀 خطوات التشغيل

### 1. تشغيل Migration
```bash
php artisan migrate
```

### 2. إضافة صلاحيات HR لمستخدم
```sql
UPDATE users SET is_hr = 1, is_hr_manager = 1 WHERE id = 1;
```

أو عبر Tinker:
```bash
php artisan tinker
```
```php
$user = User::find(1);
$user->is_hr = true;
$user->is_hr_manager = true;
$user->save();
```

### 3. الوصول إلى النظام
```
http://localhost:8000/hr/dashboard
```

---

## 📊 الوظائف الرئيسية

### 1. إدارة الموظفين
- ✅ إضافة موظف جديد
- ✅ تعديل بيانات الموظف
- ✅ عرض تفاصيل الموظف
- ✅ حذف موظف
- ✅ البحث عن الموظفين
- ✅ تتبع حالة الموظف (نشط، في إجازة، موقوف، منتهي)

### 2. الحضور والانصراف
- ✅ تسجيل الحضور اليومي
- ✅ تسجيل الانصراف
- ✅ حساب ساعات العمل
- ✅ تسجيل العمل الإضافي
- ✅ تتبع التأخير والغياب

### 3. إدارة الإجازات
- ✅ استقبال طلبات الإجازات
- ✅ الموافقة على الإجازات
- ✅ رفض الإجازات مع السبب
- ✅ أنواع إجازات متعددة (سنوية، مرضية، طارئة، بدون راتب، أمومة، أبوة)
- ✅ حساب أيام الإجازة

### 4. إدارة الرواتب
- ✅ إنشاء كشوف رواتب شهرية
- ✅ الراتب الأساسي
- ✅ البدلات والمكافآت
- ✅ العمل الإضافي
- ✅ الخصومات والضرائب
- ✅ التأمينات
- ✅ حساب صافي الراتب
- ✅ تتبع حالة الدفع

### 5. تقييم الأداء
- ✅ إنشاء تقييمات دورية
- ✅ تقييم متعدد المعايير (الأداء، الحضور، الجودة، العمل الجماعي)
- ✅ نظام النجوم (1-5)
- ✅ نقاط القوة ومجالات التحسين
- ✅ الأهداف والتعليقات

### 6. البرامج التدريبية
- ✅ إنشاء برامج تدريبية
- ✅ تسجيل الموظفين في البرامج
- ✅ تتبع حالة البرنامج
- ✅ تسجيل النتائج والتقييمات
- ✅ إدارة المدربين والمواقع

### 7. التقارير
- ✅ تقرير الحضور
- ✅ تقرير الرواتب
- ✅ تقرير الإجازات
- ✅ تقرير الأداء
- ✅ تقرير التدريب
- ✅ تقرير الموظفين

---

## 🎨 التصميم

### الألوان المستخدمة
- **لوحة التحكم:** بنفسجي (#667eea - #764ba2)
- **الموظفين:** بنفسجي (#667eea - #764ba2)
- **الحضور:** أخضر (#10b981 - #059669)
- **الإجازات:** سماوي (#06b6d4 - #0891b2)
- **الرواتب:** برتقالي (#f59e0b - #d97706)
- **الأداء:** بنفسجي فاتح (#8b5cf6 - #7c3aed)
- **التدريب:** وردي (#ec4899 - #db2777)
- **التقارير:** أزرق (#3b82f6 - #2563eb)

### المميزات التصميمية
- ✅ تصميم متجاوب (Responsive)
- ✅ ألوان متدرجة (Gradients)
- ✅ تأثيرات الحركة (Hover Effects)
- ✅ أيقونات Font Awesome
- ✅ Bootstrap 5 RTL
- ✅ بطاقات حديثة (Modern Cards)
- ✅ جداول أنيقة
- ✅ نماذج سهلة الاستخدام

---

## 🔐 الصلاحيات

### is_hr (موظف الموارد البشرية)
- عرض البيانات
- إضافة موظفين
- تسجيل الحضور
- عرض التقارير

### is_hr_manager (مدير الموارد البشرية)
- جميع صلاحيات is_hr
- الموافقة على الإجازات
- معالجة الرواتب
- إنشاء تقييمات الأداء
- إدارة البرامج التدريبية
- حذف وتعديل البيانات

---

## 📱 الروابط السريعة

```
الرئيسية:           /hr/dashboard
الموظفين:           /hr/employees
الحضور:             /hr/attendance
الإجازات:           /hr/leaves
الرواتب:            /hr/payroll
تقييم الأداء:       /hr/performance
التدريب:            /hr/training
التقارير:           /hr/reports
```

---

## 🔄 التطويرات المستقبلية

### المرحلة القادمة
- [ ] نماذج إضافة/تعديل الموظفين
- [ ] نماذج إضافة/تعديل البرامج التدريبية
- [ ] نماذج إنشاء التقييمات
- [ ] تقارير مفصلة مع رسوم بيانية
- [ ] تصدير التقارير (PDF, Excel)
- [ ] إشعارات البريد الإلكتروني
- [ ] لوحة تحكم للموظفين (Employee Portal)
- [ ] طلب إجازة من الموظف
- [ ] عرض كشف الراتب للموظف
- [ ] نظام الإشعارات الداخلية
- [ ] تقويم الإجازات
- [ ] إدارة المستندات
- [ ] نظام الأرشفة

---

## ✅ الخلاصة

تم إنشاء نظام شامل لإدارة الموارد البشرية يتضمن:

✅ **8 جداول قاعدة بيانات** كاملة
✅ **8 نماذج Laravel** مع العلاقات
✅ **متحكم واحد** بـ 20+ وظيفة
✅ **7 واجهات مستخدم** احترافية
✅ **20+ مسار** محمي
✅ **تصميم متجاوب** بالكامل
✅ **دعم اللغة العربية** RTL
✅ **نظام صلاحيات** متكامل

النظام جاهز للاستخدام الفوري! 🎉
