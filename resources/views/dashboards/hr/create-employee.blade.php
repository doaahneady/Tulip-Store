@extends('dashboards.layouts.app')
@section('content')
@php $title = 'إضافة موظف'; $subtitle = 'إنشاء ملف موظف مع تفاصيل العقد'; @endphp
<div class="bg-white rounded-2xl p-6 shadow-sm">
    <form method="POST" action="{{ route('dashboard.hr.employees.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" placeholder="الاسم" class="form-input" required>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="البريد الإلكتروني" class="form-input" required>
        <input type="password" name="password" placeholder="كلمة المرور" class="form-input" required>
        <input type="password" name="password_confirmation" placeholder="تأكيد كلمة المرور" class="form-input" required>
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="الهاتف" class="form-input">
        <input type="text" name="department" value="{{ old('department') }}" placeholder="القسم" class="form-input" required>
        <input type="text" name="position" value="{{ old('position') }}" placeholder="المنصب" class="form-input" required>
        <select name="employment_type" class="form-select" required>
            <option value="">نوع التوظيف</option>
            <option value="full_time">دوام كامل</option>
            <option value="part_time">دوام جزئي</option>
            <option value="contract">عقد</option>
            <option value="intern">متدرب</option>
        </select>
        <input type="date" name="hire_date" value="{{ old('hire_date') }}" class="form-input" required>
        <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate') }}" placeholder="الأجر بالساعة" class="form-input">
        <input type="number" step="0.01" name="monthly_salary" value="{{ old('monthly_salary') }}" placeholder="الراتب الشهري" class="form-input">
        <div class="md:col-span-2">
            <label class="block text-sm text-gray-600 mb-1">مهارات الموظف (دورات / نقاط قوة)</label>
            <select name="skill_ids[]" multiple class="form-select w-full" size="6">
                @foreach(($skills ?? collect())->groupBy('type') as $type => $rows)
                    <optgroup label="{{ $type === 'course' ? 'دورات' : 'نقاط قوة' }}">
                        @foreach($rows as $skill)
                            <option value="{{ $skill->id }}" @selected(in_array($skill->id, (array) old('skill_ids', []), true))>{{ $skill->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" name="emergency_contact[name]" value="{{ old('emergency_contact.name') }}" placeholder="اسم جهة الطوارئ" class="form-input" required>
                <input type="text" name="emergency_contact[phone]" value="{{ old('emergency_contact.phone') }}" placeholder="هاتف جهة الطوارئ" class="form-input" required>
                <input type="text" name="emergency_contact[relationship]" value="{{ old('emergency_contact.relationship') }}" placeholder="العلاقة" class="form-input" required>
            </div>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="btn btn-primary">حفظ</button>
            <a href="{{ route('dashboard.hr.employees') }}" class="btn btn-secondary ml-2">رجوع</a>
        </div>
    </form>
@endsection
