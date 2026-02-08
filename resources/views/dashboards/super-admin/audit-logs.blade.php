@extends('dashboards.layouts.app')
@section('content')
@php $title = 'سجل التدقيق'; $subtitle = 'نشاط النظام والعمليات'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm text-gray-700 mb-1">المستخدم</label>
            <input type="text" name="user_id" value="{{ request('user_id') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">الإجراء</label>
            <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">الكل</option>
                @foreach(($actions ?? []) as $action)
                    <option value="{{ $action }}" @selected(request('action')===$action)>{{ $action }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">النموذج</label>
            <select name="model_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">الكل</option>
                @foreach(($modelTypes ?? []) as $mt)
                    <option value="{{ $mt }}" @selected(request('model_type')===$mt)>{{ $mt }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">تصفية</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الوقت</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">المستخدم</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">الإجراء</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">النموذج</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500">تفاصيل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($logs ?? []) as $log)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $log->created_at }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $log->user->name ?? 'غير معروف' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $log->action }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $log->model_type ?? '-' }}</td>
                    <td class="px-4 py-2 text-xs text-gray-600">
                        @if(is_array($log->new_values))
                            <code>{{ json_encode($log->new_values) }}</code>
                        @elseif(is_string($log->new_values))
                            <code>{{ $log->new_values }}</code>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">لا توجد سجلات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        @if(method_exists(($logs ?? null),'links'))
            {{ $logs->links() }}
        @endif
    </div>
</div>
@endsection
