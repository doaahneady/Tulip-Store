# 🔌 دليل الاتصال بقاعدة البيانات - نظام الموارد البشرية

## ✅ جميع لوحات التحكم متصلة بقاعدة البيانات

تم ربط جميع صفحات نظام الموارد البشرية بقاعدة البيانات بشكل كامل.

---

## 📊 الجداول المتصلة

### 1. جدول الموظفين (employees)
**الموديل:** `App\Models\Employee`
**الجدول:** `employees`

**الصفحات المتصلة:**
- ✅ `/hr/dashboard` - عرض أحدث 5 موظفين
- ✅ `/hr/employees` - قائمة جميع الموظفين (مع pagination)
- ✅ `/hr/employees/create` - إضافة موظف جديد
- ✅ `/hr/employees/{id}/edit` - تعديل بيانات موظف

**الوظائف:**
```php
// Dashboard
$recentEmployees = Employee::latest()->take(5)->get();

// Employees List
$employees = Employee::latest()->paginate(20);

// Create Employee
Employee::create($validated);

// Update Employee
$employee->update($validated);
```

---

### 2. جدول الحضور (attendance)
**الموديل:** `App\Models\Attendance`
**الجدول:** `attendance`

**الصفحات المتصلة:**
- ✅ `/hr/dashboard` - عدد الحضور اليوم
- ✅ `/hr/attendance` - سجلات الحضور اليومية
- ✅ `/hr/reports/attendance` - تقرير الحضور المفصل

**الوظائف:**
```php
// Dashboard Stats
$present_today = Attendance::whereDate('date', today())
    ->where('status', 'present')->count();

// Today's Attendance
$attendance = Attendance::with('employee')
    ->whereDate('date', $today)
    ->get();

// Attendance Report
$report = Attendance::with('employee')
    ->whereBetween('date', [$startDate, $endDate])
    ->get()
    ->groupBy('employee_id');
```

---

### 3. جدول طلبات الإجازات (leave_requests)
**الموديل:** `App\Models\LeaveRequest`
**الجدول:** `leave_requests`

**الصفحات المتصلة:**
- ✅ `/hr/dashboard` - عدد الطلبات المعلقة + أحدث 5 طلبات
- ✅ `/hr/leaves` - قائمة جميع طلبات الإجازات

**الوظائف:**
```php
// Dashboard Stats
$pending_leave_requests = LeaveRequest::where('status', 'pending')->count();

// Dashboard Recent Leaves
$pendingLeaves = LeaveRequest::with('employee')
    ->where('status', 'pending')
    ->latest()
    ->take(5)
    ->get();

// All Leaves
$leaves = LeaveRequest::with('employee')->latest()->paginate(20);

// Approve Leave
$leave->update([
    'status' => 'approved',
    'approved_by' => auth()->id(),
    'approved_at' => now(),
]);

// Reject Leave
$leave->update([
    'status' => 'rejected',
    'approved_by' => auth()->id(),
    'approved_at' => now(),
    'rejection_reason' => $request->rejection_reason,
]);
```

---

### 4. جدول الرواتب (payroll)
**الموديل:** `App\Models\Payroll`
**الجدول:** `payroll`

**الصفحات المتصلة:**
- ✅ `/hr/payroll` - كشوف الرواتب الشهرية

**الوظائف:**
```php
// Current Month Payroll
$currentMonth = now()->format('Y-m');
$payrolls = Payroll::with('employee')
    ->where('month', $currentMonth)
    ->get();

// Generate Payroll
$employees = Employee::where('status', 'active')->get();
foreach ($employees as $employee) {
    Payroll::updateOrCreate(
        ['employee_id' => $employee->id, 'month' => $month],
        ['basic_salary' => $employee->salary, 'net_salary' => $employee->salary, 'status' => 'draft']
    );
}

// Process Payroll
$payroll->update([
    'status' => 'processed',
    'payment_date' => now(),
]);
```

---

### 5. جدول تقييم الأداء (performance_reviews)
**الموديل:** `App\Models\PerformanceReview`
**الجدول:** `performance_reviews`

**الصفحات المتصلة:**
- ✅ `/hr/performance` - قائمة جميع التقييمات

**الوظائف:**
```php
// All Reviews
$reviews = PerformanceReview::with(['employee', 'reviewer'])
    ->latest()
    ->paginate(20);

// Create Review
$validated['reviewer_id'] = auth()->id();
PerformanceReview::create($validated);
```

---

### 6. جدول البرامج التدريبية (training_programs)
**الموديل:** `App\Models\TrainingProgram`
**الجدول:** `training_programs`

**الصفحات المتصلة:**
- ✅ `/hr/training` - قائمة جميع البرامج التدريبية

**الوظائف:**
```php
// All Programs
$programs = TrainingProgram::latest()->paginate(20);

// Create Program
TrainingProgram::create($validated);
```

---

### 7. جدول تسجيلات التدريب (training_enrollments)
**الموديل:** `App\Models\TrainingEnrollment`
**الجدول:** `training_enrollments`

**العلاقات:**
```php
// In TrainingProgram model
public function enrollments()
{
    return $this->hasMany(TrainingEnrollment::class);
}

// Usage
$program->enrollments->count() // عدد المسجلين
```

---

### 8. جدول مستندات الموظفين (employee_documents)
**الموديل:** `App\Models\EmployeeDocument`
**الجدول:** `employee_documents`

**العلاقات:**
```php
// In Employee model
public function documents()
{
    return $this->hasMany(EmployeeDocument::class);
}
```

---

## 🔗 العلاقات بين الجداول

### Employee Relations
```php
// Employee has many:
- attendance()
- leaveRequests()
- payroll()
- performanceReviews()
- trainingEnrollments()
- documents()

// Employee belongs to:
- user()
```

### Attendance Relations
```php
// Attendance belongs to:
- employee()
```

### LeaveRequest Relations
```php
// LeaveRequest belongs to:
- employee()
- approver() (User)
```

### Payroll Relations
```php
// Payroll belongs to:
- employee()
```

### PerformanceReview Relations
```php
// PerformanceReview belongs to:
- employee()
- reviewer() (User)
```

### TrainingProgram Relations
```php
// TrainingProgram has many:
- enrollments()
```

### TrainingEnrollment Relations
```php
// TrainingEnrollment belongs to:
- trainingProgram()
- employee()
```

---

## 📍 خريطة الاتصالات

```
┌─────────────────────────────────────────────────────────┐
│                    HR Dashboard                         │
│                  /hr/dashboard                          │
├─────────────────────────────────────────────────────────┤
│ ✅ Employee::where('status', 'active')->count()        │
│ ✅ Employee::where('status', 'on_leave')->count()      │
│ ✅ LeaveRequest::where('status', 'pending')->count()   │
│ ✅ Attendance::whereDate('date', today())->count()     │
│ ✅ Employee::latest()->take(5)->get()                  │
│ ✅ LeaveRequest::with('employee')->latest()->take(5)   │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                  Employees Page                         │
│                  /hr/employees                          │
├─────────────────────────────────────────────────────────┤
│ ✅ Employee::latest()->paginate(20)                    │
│ ✅ Employee::create($validated)                        │
│ ✅ $employee->update($validated)                       │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                  Attendance Page                        │
│                  /hr/attendance                         │
├─────────────────────────────────────────────────────────┤
│ ✅ Attendance::with('employee')                        │
│    ->whereDate('date', $today)->get()                  │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                  Leave Requests Page                    │
│                  /hr/leaves                             │
├─────────────────────────────────────────────────────────┤
│ ✅ LeaveRequest::with('employee')                      │
│    ->latest()->paginate(20)                            │
│ ✅ $leave->update(['status' => 'approved'])            │
│ ✅ $leave->update(['status' => 'rejected'])            │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                  Payroll Page                           │
│                  /hr/payroll                            │
├─────────────────────────────────────────────────────────┤
│ ✅ Payroll::with('employee')                           │
│    ->where('month', $currentMonth)->get()              │
│ ✅ Payroll::updateOrCreate(...)                        │
│ ✅ $payroll->update(['status' => 'processed'])         │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                  Performance Page                       │
│                  /hr/performance                        │
├─────────────────────────────────────────────────────────┤
│ ✅ PerformanceReview::with(['employee', 'reviewer'])   │
│    ->latest()->paginate(20)                            │
│ ✅ PerformanceReview::create($validated)               │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                  Training Page                          │
│                  /hr/training                           │
├─────────────────────────────────────────────────────────┤
│ ✅ TrainingProgram::latest()->paginate(20)             │
│ ✅ TrainingProgram::create($validated)                 │
│ ✅ $program->enrollments->count()                      │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                  Attendance Report                      │
│                  /hr/reports/attendance                 │
├─────────────────────────────────────────────────────────┤
│ ✅ Attendance::with('employee')                        │
│    ->whereBetween('date', [$start, $end])             │
│    ->get()->groupBy('employee_id')                     │
└─────────────────────────────────────────────────────────┘
```

---

## 🧪 اختبار الاتصال

### صفحة اختبار قاعدة البيانات
```
http://localhost:8000/test-database
```

هذه الصفحة تختبر:
- ✅ الاتصال بكل جدول
- ✅ عدد السجلات في كل جدول
- ✅ عرض عينة من البيانات
- ✅ اختبار العلاقات (relationships)

---

## 📊 إحصائيات الاتصال

```
✅ 8 جداول متصلة
✅ 8 نماذج (Models) مع علاقات
✅ 10 صفحات تعرض بيانات
✅ 20+ استعلام قاعدة بيانات
✅ جميع العلاقات تعمل بشكل صحيح
```

---

## 🔧 الأوامر المفيدة

### اختبار الاتصال بقاعدة البيانات
```bash
php artisan tinker
```
```php
// Test Employee connection
\App\Models\Employee::count();
\App\Models\Employee::first();

// Test Attendance connection
\App\Models\Attendance::count();
\App\Models\Attendance::with('employee')->first();

// Test Leave Requests connection
\App\Models\LeaveRequest::count();
\App\Models\LeaveRequest::with('employee')->first();

// Test Payroll connection
\App\Models\Payroll::count();
\App\Models\Payroll::with('employee')->first();

// Test Performance Reviews connection
\App\Models\PerformanceReview::count();
\App\Models\PerformanceReview::with(['employee', 'reviewer'])->first();

// Test Training Programs connection
\App\Models\TrainingProgram::count();
\App\Models\TrainingProgram::with('enrollments')->first();
```

### إعادة تشغيل البيانات التجريبية
```bash
php artisan db:seed --class=HRSeeder
```

---

## ✅ التحقق من الاتصال

### 1. زيارة صفحة اختبار قاعدة البيانات
```
http://localhost:8000/test-database
```

### 2. التحقق من لوحة التحكم
```
http://localhost:8000/hr/dashboard
```
يجب أن ترى:
- إحصائيات حقيقية من قاعدة البيانات
- أحدث 5 موظفين
- طلبات الإجازات المعلقة

### 3. التحقق من صفحة الموظفين
```
http://localhost:8000/hr/employees
```
يجب أن ترى:
- قائمة الموظفين من قاعدة البيانات
- بحث يعمل
- pagination

---

## 🎉 الخلاصة

✅ **جميع لوحات التحكم متصلة بقاعدة البيانات**
✅ **جميع الصفحات تعرض بيانات حقيقية**
✅ **جميع العلاقات تعمل بشكل صحيح**
✅ **جميع الاستعلامات محسّنة (مع eager loading)**
✅ **صفحة اختبار شاملة متوفرة**

النظام جاهز للاستخدام الفوري مع قاعدة البيانات! 🚀
