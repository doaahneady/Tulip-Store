@extends('dashboards.layouts.app')
@section('content')
@php $title = 'إضافة سائق'; $subtitle = 'إنشاء سائق جديد وربطه بحساب مستخدم'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    @if($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200">
            <ul class="text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('dashboard.supervisor.drivers.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div class="md:col-span-2 text-sm font-semibold text-gray-800">بيانات الحساب</div>

        <div>
            <label class="text-sm text-gray-600">الاسم</label>
            <input name="name" value="{{ old('name') }}" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">البريد</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">الهاتف</label>
            <input name="phone" value="{{ old('phone') }}" class="w-full mt-1 border rounded-xl px-4 py-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">كلمة المرور</label>
            <input type="password" name="password" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>

        <div class="md:col-span-2 mt-2 text-sm font-semibold text-gray-800">بيانات السائق</div>

        <div>
            <label class="text-sm text-gray-600">رقم الرخصة</label>
            <input name="license_number" value="{{ old('license_number') }}" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">انتهاء الرخصة</label>
            <input type="date" name="license_expiry" value="{{ old('license_expiry') }}" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">نوع المركبة</label>
            <input name="vehicle_type" value="{{ old('vehicle_type') }}" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">رقم اللوحة</label>
            <input name="vehicle_plate" value="{{ old('vehicle_plate') }}" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">الحالة</label>
            <select name="status" class="w-full mt-1 border rounded-xl px-4 py-2" required>
                <option value="active" @selected(old('status','active')==='active')>نشط</option>
                <option value="inactive" @selected(old('status')==='inactive')>غير نشط</option>
                <option value="suspended" @selected(old('status')==='suspended')>موقوف</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-gray-600">التوفر</label>
            <select name="availability" class="w-full mt-1 border rounded-xl px-4 py-2" required>
                <option value="available" @selected(old('availability','available')==='available')>متاح</option>
                <option value="busy" @selected(old('availability')==='busy')>مشغول</option>
                <option value="offline" @selected(old('availability','available')==='offline')>غير متصل</option>
                <option value="on_break" @selected(old('availability')==='on_break')>في استراحة</option>
            </select>
        </div>

        <div class="md:col-span-2 flex items-center justify-end gap-2 mt-2">
            <a href="{{ route('dashboard.supervisor.drivers') }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700">إلغاء</a>
            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">حفظ</button>
        </div>
    </form>
</div>
@endsection

