@extends('dashboards.layouts.app')
@section('content')
@php $title = 'صيانة المركبات'; $subtitle = 'سجل الصيانة والمهام القادمة'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">سجل الصيانة</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">السائق</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">النوع</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">التاريخ</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">الحالة</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($maintenanceRecords as $rec)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ optional(optional($rec->driver)->user)->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $rec->type }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ optional($rec->maintenance_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $rec->status }}</span>
                        </td>
                    </tr>
                @endforeach
                @if($maintenanceRecords->count()===0)
                    <tr><td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">لا توجد سجلات</td></tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="px-2 py-4">
            {{ $maintenanceRecords->links() }}
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">صيانة قادمة</h3>
        <ul class="divide-y divide-gray-200">
            @foreach($upcomingMaintenance as $m)
                <li class="py-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-900">{{ optional(optional($m->driver)->user)->name ?? '—' }}</div>
                            <div class="text-sm text-gray-500">الاستحقاق: {{ optional($m->next_due_date)->format('Y-m-d') }}</div>
                        </div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">مجدولة</span>
                    </div>
                </li>
            @endforeach
            @if($upcomingMaintenance->count()===0)
                <li class="py-3 text-center text-sm text-gray-500">لا يوجد</li>
            @endif
        </ul>
        <div class="mt-6">
            <form method="POST" action="{{ route('dashboard.supervisor.vehicle-maintenance.log') }}" class="space-y-3">
                @csrf
                <select name="driver_id" class="border rounded-xl px-3 py-2 w-full">
                    @foreach(\App\Models\Driver::where('status','active')->get() as $d)
                        <option value="{{ $d->id }}">{{ optional($d->user)->name ?? $d->name }}</option>
                    @endforeach
                </select>
                <select name="type" class="border rounded-xl px-3 py-2 w-full">
                    <option value="routine">دورية</option>
                    <option value="repair">إصلاح</option>
                    <option value="inspection">فحص</option>
                    <option value="emergency">طارئة</option>
                </select>
                <input type="date" name="maintenance_date" class="border rounded-xl px-3 py-2 w-full">
                <input type="date" name="next_due_date" class="border rounded-xl px-3 py-2 w-full">
                <input type="number" step="0.01" name="cost" placeholder="التكلفة" class="border rounded-xl px-3 py-2 w-full">
                <input type="number" name="odometer_reading" placeholder="عداد" class="border rounded-xl px-3 py-2 w-full">
                <input type="text" name="description" placeholder="الوصف" class="border rounded-xl px-3 py-2 w-full">
                <input type="text" name="notes" placeholder="ملاحظات" class="border rounded-xl px-3 py-2 w-full">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-md w-full">تسجيل الصيانة</button>
            </form>
        </div>
    </div>
</div>
@endsection
