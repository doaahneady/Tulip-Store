@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تنبيهات النظام'; $subtitle = 'أخطاء وفشل من لوحة تقنية المعلومات'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="text-sm text-gray-600">الخطورة</label>
            <select name="severity" class="w-full px-3 py-2 border rounded-lg">
                <option value="">الكل</option>
                <option value="critical" @selected(request('severity')==='critical')>حرج</option>
                <option value="error" @selected(request('severity')==='error')>خطأ</option>
                <option value="warning" @selected(request('severity')==='warning')>تحذير</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">تطبيق</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">التنبيهات النشطة</h3>
    </div>
    <div class="p-6">
        <div class="space-y-3">
            @forelse($alerts as $alert)
            <div class="p-3 bg-gray-50 rounded-lg flex items-start gap-3">
                <div class="w-2 h-2 mt-2 rounded-full {{ $alert->severity === 'critical' ? 'bg-red-600' : ($alert->severity === 'error' ? 'bg-red-400' : ($alert->severity === 'warning' ? 'bg-yellow-500' : 'bg-gray-400')) }}"></div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ $alert->title ?? 'تنبيه' }}</p>
                    <p class="text-xs text-gray-600">{{ \Illuminate\Support\Str::limit($alert->description ?? '', 100) }}</p>
                    <p class="text-xs text-gray-500">{{ $alert->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500">لا توجد تنبيهات</p>
            @endforelse
        </div>
    </div>
    <div class="p-6">{{ $alerts->withQueryString()->links() }}</div>
@endsection
