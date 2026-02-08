@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تعديل مركبة'; $subtitle = 'تحديث بيانات المركبة وربطها بسائق'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="{{ route('dashboard.supervisor.vehicles.update', $vehicle) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm text-gray-600">نوع المركبة</label>
            <input name="vehicle_type" value="{{ old('vehicle_type', $vehicle->vehicle_type) }}" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">رقم اللوحة</label>
            <input name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" class="w-full mt-1 border rounded-xl px-4 py-2" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">الشركة</label>
            <input name="make" value="{{ old('make', $vehicle->make) }}" class="w-full mt-1 border rounded-xl px-4 py-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">الموديل</label>
            <input name="model" value="{{ old('model', $vehicle->model) }}" class="w-full mt-1 border rounded-xl px-4 py-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">السنة</label>
            <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" class="w-full mt-1 border rounded-xl px-4 py-2" min="1900" max="2100">
        </div>
        <div>
            <label class="text-sm text-gray-600">اللون</label>
            <input name="color" value="{{ old('color', $vehicle->color) }}" class="w-full mt-1 border rounded-xl px-4 py-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">VIN</label>
            <input name="vin" value="{{ old('vin', $vehicle->vin) }}" class="w-full mt-1 border rounded-xl px-4 py-2">
        </div>
        <div>
            <label class="text-sm text-gray-600">الحالة</label>
            <select name="status" class="w-full mt-1 border rounded-xl px-4 py-2" required>
                <option value="active" @selected(old('status', $vehicle->status)==='active')>نشط</option>
                <option value="inactive" @selected(old('status', $vehicle->status)==='inactive')>غير نشط</option>
                <option value="maintenance" @selected(old('status', $vehicle->status)==='maintenance')>صيانة</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="text-sm text-gray-600">تعيين لسائق</label>
            @php $currentDriverId = $vehicle->driver?->id; @endphp
            <select name="driver_id" class="w-full mt-1 border rounded-xl px-4 py-2">
                <option value="">بدون</option>
                @foreach($drivers as $d)
                    @php $dn = optional($d->user)->name ?? optional($d->user)->user_full_name ?? optional($d->user)->email ?? ('Driver #'.$d->id); @endphp
                    <option value="{{ $d->id }}" @selected((string)old('driver_id', $currentDriverId)===(string)$d->id)>{{ $dn }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="text-sm text-gray-600">ملاحظات</label>
            <textarea name="notes" rows="3" class="w-full mt-1 border rounded-xl px-4 py-2">{{ old('notes', $vehicle->notes) }}</textarea>
        </div>

        <div class="md:col-span-2 flex items-center justify-between gap-2 mt-2">
            <button form="delete-vehicle-form" type="submit" onclick="return confirm('حذف المركبة؟');" class="px-4 py-2 rounded-xl border border-red-200 text-red-700">حذف</button>
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.supervisor.vehicles') }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700">إلغاء</a>
                <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white">حفظ</button>
            </div>
        </div>
    </form>
    <form id="delete-vehicle-form" method="POST" action="{{ route('dashboard.supervisor.vehicles.delete', $vehicle) }}">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

