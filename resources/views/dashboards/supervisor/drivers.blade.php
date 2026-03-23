@extends('dashboards.layouts.app')
@section('content')
@php $title = 'إدارة السائقين'; $subtitle = 'قائمة السائقين وتحديث الحالة'; @endphp

@if(session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between gap-3 mb-4">
        <a href="{{ route('dashboard.supervisor.drivers.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-plus"></i>
            <span>إضافة سائق</span>
        </a>
    </div>
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" name="search" value="{{ request('search') }}" class="border rounded-xl px-4 py-2" placeholder="بحث بالاسم أو البريد">
        <select name="status" class="border rounded-xl px-4 py-2">
            <option value="">الحالة</option>
            <option value="active" @selected(request('status')==='active')>نشط</option>
            <option value="inactive" @selected(request('status')==='inactive')>غير نشط</option>
            <option value="suspended" @selected(request('status')==='suspended')>موقوف</option>
        </select>
        <select name="availability" class="border rounded-xl px-4 py-2">
            <option value="">التوفر</option>
            <option value="available" @selected(request('availability')==='available')>متاح</option>
            <option value="busy" @selected(request('availability')==='busy')>مشغول</option>
            <option value="offline" @selected(request('availability')==='offline')>غير متصل</option>
            <option value="on_break" @selected(request('availability')==='on_break')>في استراحة</option>
        </select>
        <div class="md:col-span-3">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">تصفية</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">السائق</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">البريد</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الهاتف</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الحالة</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">التوفر</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">إجراء</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($drivers as $driver)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ optional($driver->user)->name ?? optional($driver->user)->user_full_name ?? optional($driver->user)->email ?? ('Driver #'.$driver->id) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ optional($driver->user)->email ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ optional($driver->user)->phone ?? optional($driver->user)->mobile ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($driver->status==='active') bg-green-100 text-green-800
                            @elseif($driver->status==='inactive') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $driver->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($driver->availability==='available') bg-green-100 text-green-800
                            @elseif($driver->availability==='busy') bg-blue-100 text-blue-800
                            @elseif($driver->availability==='on_break') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $driver->availability }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-y-2">
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <a href="{{ route('dashboard.supervisor.drivers.edit', $driver) }}" class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 text-white px-3 py-1.5 text-xs font-semibold hover:bg-indigo-700">
                                <i class="fas fa-pen"></i> تعديل البيانات
                            </a>
                            <form method="POST" action="{{ route('dashboard.supervisor.drivers.delete', $driver) }}" class="inline" onsubmit="return confirm('حذف هذا السائق نهائياً؟ لا يمكن التراجع.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-white text-rose-700 px-3 py-1.5 text-xs font-semibold hover:bg-rose-50">
                                    <i class="fas fa-trash-alt"></i> حذف
                                </button>
                            </form>
                        </div>
                        <form method="POST" action="{{ route('dashboard.supervisor.drivers.update-status', $driver) }}" class="flex flex-wrap items-center justify-end gap-2 border-t border-gray-100 pt-2">
                            @csrf
                            <select name="status" class="border rounded-xl px-2 py-1 text-xs">
                                <option value="active" @selected($driver->status==='active')>نشط</option>
                                <option value="inactive" @selected($driver->status==='inactive')>غير نشط</option>
                                <option value="suspended" @selected($driver->status==='suspended')>موقوف</option>
                            </select>
                            <select name="availability" class="border rounded-xl px-2 py-1 text-xs">
                                <option value="available" @selected($driver->availability==='available')>متاح</option>
                                <option value="busy" @selected($driver->availability==='busy')>مشغول</option>
                                <option value="offline" @selected($driver->availability==='offline')>غير متصل</option>
                                <option value="on_break" @selected($driver->availability==='on_break')>في استراحة</option>
                            </select>
                            <input type="text" name="notes" placeholder="ملاحظة" class="border rounded-xl px-2 py-1 text-xs">
                            <button class="px-3 py-1 bg-indigo-600 text-white rounded-md text-xs">تحديث</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @if($drivers->count()===0)
                <tr><td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">لا توجد بيانات</td></tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">
        {{ $drivers->links() }}
    </div>
</div>
@endsection
