@extends('dashboards.layouts.app')
@section('content')
@php $title = 'صحة النظام'; $subtitle = 'حالة الخدمات ومؤشرات الأداء'; @endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">الحالة العامة</div>
        <div class="text-2xl font-bold mt-1 {{ ($healthSummary['status'] ?? 'healthy') === 'healthy' ? 'text-emerald-700' : 'text-red-700' }}">
            {{ ($healthSummary['status'] ?? 'healthy') === 'healthy' ? 'سليم' : 'تحذير' }}
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">وقت الاستجابة</div>
        <div class="text-2xl font-bold mt-1 text-gray-900">{{ number_format((float) ($healthSummary['avg_response_time_ms'] ?? 0), 0) }} ms</div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
        <div class="text-gray-500 text-xs">الأخطاء (آخر 24 ساعة)</div>
        <div class="text-2xl font-bold mt-1 text-gray-900">{{ number_format((int) ($healthSummary['errors_last_24h'] ?? 0)) }}</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">الخدمات</h3>
        <a href="{{ route('dashboard.it.index') }}" class="text-sm text-indigo-600">عودة</a>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الخدمة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($services ?? []) as $svc)
                    @php $up = (bool) ($svc['is_up'] ?? false); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $svc['name'] ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs {{ $up ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $up ? 'تعمل' : 'متوقفة' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('dashboard.it.services.update-status', ['service' => $svc['key'] ?? 'unknown']) }}" class="inline-flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $up ? 'down' : 'up' }}">
                                <button type="submit" class="btn btn-secondary btn-sm">{{ $up ? 'إيقاف' : 'تشغيل' }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

