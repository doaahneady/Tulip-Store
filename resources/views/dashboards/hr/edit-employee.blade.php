@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تعديل موظف'; $subtitle = 'تحديث بيانات الموظف وصلاحيته'; @endphp
<div class="bg-white rounded-2xl p-6 shadow-sm">
    <form method="POST" action="{{ route('dashboard.hr.employees.update', $employee) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')
        <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" placeholder="الاسم الأول" class="form-input" required>
        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" placeholder="الكنية" class="form-input" required>
        <input type="email" name="email" value="{{ old('email', $employee->email) }}" placeholder="البريد الإلكتروني" class="form-input" required>
        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" placeholder="الهاتف" class="form-input">
        <input type="text" name="department" value="{{ old('department', $employee->department) }}" placeholder="القسم" class="form-input" required>
        <input type="text" name="position" value="{{ old('position', $employee->position) }}" placeholder="المنصب" class="form-input" required>
        <select name="employment_type" class="form-select" required>
            <option value="full_time" @selected(old('employment_type', $employee->employment_type) === 'full_time')>دوام كامل</option>
            <option value="part_time" @selected(old('employment_type', $employee->employment_type) === 'part_time')>دوام جزئي</option>
            <option value="contract" @selected(old('employment_type', $employee->employment_type) === 'contract')>عقد</option>
            <option value="intern" @selected(old('employment_type', $employee->employment_type) === 'intern')>متدرب</option>
        </select>
        <select name="status" class="form-select" required>
            <option value="active" @selected(old('status', $employee->status) === 'active')>نشط</option>
            <option value="inactive" @selected(old('status', $employee->status) === 'inactive')>غير نشط</option>
            <option value="on_leave" @selected(old('status', $employee->status) === 'on_leave')>على إجازة</option>
            <option value="terminated" @selected(old('status', $employee->status) === 'terminated')>منتهي العقد</option>
        </select>
        <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate', $employee->hourly_rate) }}" placeholder="الأجر بالساعة" class="form-input">
        <input type="number" step="0.01" name="monthly_salary" value="{{ old('monthly_salary', $employee->monthly_salary) }}" placeholder="الراتب الشهري" class="form-input">
        <input type="date" name="termination_date" value="{{ old('termination_date', $employee->termination_date) }}" class="form-input">
        <div class="md:col-span-2">
            @php $selectedSkillIds = collect(old('skill_ids', ($employee->skillsCatalog ?? collect())->pluck('id')->all()))->map(fn ($v) => (int) $v)->all(); @endphp
            <label class="block text-sm text-gray-600 mb-1">مهارات الموظف (دورات / نقاط قوة)</label>
            <select name="skill_ids[]" multiple class="form-select w-full" size="6">
                @foreach(($skills ?? collect())->groupBy('type') as $type => $rows)
                    <optgroup label="{{ $type === 'course' ? 'دورات' : 'نقاط قوة' }}">
                        @foreach($rows as $skill)
                            <option value="{{ $skill->id }}" @selected(in_array($skill->id, $selectedSkillIds, true))>{{ $skill->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" name="emergency_contact[name]" value="{{ old('emergency_contact.name', data_get($employee->emergency_contact, 'name')) }}" placeholder="اسم جهة الطوارئ" class="form-input">
                <input type="text" name="emergency_contact[phone]" value="{{ old('emergency_contact.phone', data_get($employee->emergency_contact, 'phone')) }}" placeholder="هاتف جهة الطوارئ" class="form-input">
                <input type="text" name="emergency_contact[relationship]" value="{{ old('emergency_contact.relationship', data_get($employee->emergency_contact, 'relationship')) }}" placeholder="العلاقة" class="form-input">
            </div>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="btn btn-primary">حفظ</button>
            <a href="{{ route('dashboard.hr.employees') }}" class="btn btn-secondary ml-2">رجوع</a>
        </div>
    </form>
@endsection
