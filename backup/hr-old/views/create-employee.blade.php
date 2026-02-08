@extends('layouts.dashboard')

@section('title', 'إضافة موظف جديد')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <h2 class="text-xl font-bold text-gray-800">بيانات الموظف الجديد</h2>
        <a href="{{ route('dashboard.hr.employees') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <i class="fas fa-arrow-right ml-1"></i> عودة للقائمة
        </a>
    </div>

    <form action="{{ route('dashboard.hr.employees.create') }}" method="POST">
        @csrf
        
        <!-- Personal Information -->
        <h3 class="text-lg font-semibold text-gray-700 mb-4 bg-gray-50 p-2 rounded">البيانات الشخصية</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input w-full rounded-md border-gray-300" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-input w-full rounded-md border-gray-300" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-input w-full rounded-md border-gray-300">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ التعيين <span class="text-red-500">*</span></label>
                <input type="date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" class="form-input w-full rounded-md border-gray-300" required>
                @error('hire_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Job Details -->
        <h3 class="text-lg font-semibold text-gray-700 mb-4 bg-gray-50 p-2 rounded">بيانات الوظيفة</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">القسم <span class="text-red-500">*</span></label>
                <select name="department" class="form-select w-full rounded-md border-gray-300" required>
                    <option value="">اختر القسم</option>
                    @foreach($departments as $dep)
                        <option value="{{ $dep }}" @selected(old('department') == $dep)>{{ $dep }}</option>
                    @endforeach
                    <option value="IT" @selected(old('department') == 'IT')>تقنية المعلومات</option>
                    <option value="HR" @selected(old('department') == 'HR')>الموارد البشرية</option>
                    <option value="Finance" @selected(old('department') == 'Finance')>المالية</option>
                    <option value="Operations" @selected(old('department') == 'Operations')>العمليات</option>
                    <option value="Sales" @selected(old('department') == 'Sales')>المبيعات</option>
                </select>
                @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">المسمى الوظيفي <span class="text-red-500">*</span></label>
                <input type="text" name="position" value="{{ old('position') }}" class="form-input w-full rounded-md border-gray-300" required>
                @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نوع التوظيف <span class="text-red-500">*</span></label>
                <select name="employment_type" class="form-select w-full rounded-md border-gray-300" required>
                    <option value="full_time" @selected(old('employment_type') == 'full_time')>دوام كامل</option>
                    <option value="part_time" @selected(old('employment_type') == 'part_time')>دوام جزئي</option>
                    <option value="contract" @selected(old('employment_type') == 'contract')>عقد</option>
                    <option value="intern" @selected(old('employment_type') == 'intern')>تدريب</option>
                </select>
                @error('employment_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Compensation -->
        <h3 class="text-lg font-semibold text-gray-700 mb-4 bg-gray-50 p-2 rounded">الراتب والتعويضات</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الراتب الشهري</label>
                <input type="number" step="0.01" name="monthly_salary" value="{{ old('monthly_salary') }}" class="form-input w-full rounded-md border-gray-300">
                @error('monthly_salary') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">أجر الساعة (إن وجد)</label>
                <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate') }}" class="form-input w-full rounded-md border-gray-300">
                @error('hourly_rate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Emergency Contact -->
        <h3 class="text-lg font-semibold text-gray-700 mb-4 bg-gray-50 p-2 rounded">جهة الاتصال للطوارئ</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الاسم <span class="text-red-500">*</span></label>
                <input type="text" name="emergency_contact[name]" value="{{ old('emergency_contact.name') }}" class="form-input w-full rounded-md border-gray-300" required>
                @error('emergency_contact.name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف <span class="text-red-500">*</span></label>
                <input type="text" name="emergency_contact[phone]" value="{{ old('emergency_contact.phone') }}" class="form-input w-full rounded-md border-gray-300" required>
                @error('emergency_contact.phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">صلة القرابة <span class="text-red-500">*</span></label>
                <input type="text" name="emergency_contact[relationship]" value="{{ old('emergency_contact.relationship') }}" class="form-input w-full rounded-md border-gray-300" required>
                @error('emergency_contact.relationship') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t">
            <a href="{{ route('dashboard.hr.employees') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">إلغاء</a>
            <button type="submit" class="px-6 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-save mr-1"></i> حفظ الموظف
            </button>
        </div>
    </form>
</div>
@endsection
