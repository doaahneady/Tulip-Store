@extends('dashboards.layouts.app')
@section('content')
@php $title = 'سجلات النظام'; $subtitle = 'عرض السجلات مع تصفية'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">سجل تسجيل الدخول</h3>
        <span class="text-xs text-gray-500">{{ number_format(count($loginLogs ?? [])) }}</span>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الوقت</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">النوع</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">من</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">IP</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الوصف</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($loginLogs ?? []) as $l)
                    @php
                        $who = $l->user?->email ?? $l->user?->name ?? (data_get($l->metadata, 'identifier') ?? '-');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $l->created_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $l->event_type ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $who }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $l->ip_address ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $l->status ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $l->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">لا توجد سجلات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h3 class="text-lg font-semibold text-gray-900">السجلات</h3>
        <form method="GET" action="{{ route('dashboard.it.logs') }}" class="flex flex-wrap items-center gap-2">
            <select name="level" class="form-select w-44">
                <option value="">كل المستويات</option>
                @foreach(($logLevels ?? []) as $lvl)
                    <option value="{{ $lvl }}" @selected(request('level') === $lvl)>{{ strtoupper($lvl) }}</option>
                @endforeach
            </select>
            <select name="channel" class="form-select w-44">
                <option value="">كل القنوات</option>
                @foreach(($channels ?? []) as $ch)
                    <option value="{{ $ch }}" @selected(request('channel') === $ch)>{{ $ch }}</option>
                @endforeach
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث..." class="form-input w-56">
            <button type="submit" class="btn btn-secondary btn-sm">تصفية</button>
            <a href="{{ route('dashboard.it.index') }}" class="btn btn-ghost btn-sm">عودة</a>
        </form>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الوقت</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المستوى</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحدث</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المستخدم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">القناة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الرسالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($logs ?? []) as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ $log->created_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">{{ strtoupper($log->level ?? '-') }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $log->action ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $log->user ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $log->channel ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $log->message ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">لا توجد سجلات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists(($logs ?? null), 'links'))
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
