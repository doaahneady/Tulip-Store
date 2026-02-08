@extends('layouts.dashboard')

@section('title', 'سجل التدقيق - الموارد البشرية')

@section('content')
<div class="sticky top-0 z-10 bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-clipboard-list text-indigo-600"></i>
            <span class="text-sm text-gray-700">فلاتر السجل</span>
        </div>
        <form method="GET" action="{{ route('dashboard.hr.audit-logs') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="user_id" value="{{ request('user_id') }}" class="form-input w-32" placeholder="رقم المستخدم">
            <select name="action" class="form-select w-44">
                <option value="">الإجراء</option>
                @foreach(($actions ?? []) as $action)
                    <option value="{{ $action }}" @selected(request('action')===$action)>{{ $action }}</option>
                @endforeach
            </select>
            <select name="model_type" class="form-select w-52">
                <option value="">النموذج</option>
                @foreach(($modelTypes ?? []) as $mt)
                    <option value="{{ $mt }}" @selected(request('model_type')===$mt)>{{ $mt }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-40">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input w-40">
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-filter"></i>
                تطبيق
            </button>
        </form>
    </div>
    <div class="px-4 pb-3">
        <a href="{{ route('dashboard.hr.index') }}" class="text-sm text-indigo-600">عودة للوحة الموارد البشرية</a>
    </div>
    </div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">السجل</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">المستخدم</th>
                    <th class="px-4 py-2 text-left">الإجراء</th>
                    <th class="px-4 py-2 text-left">النموذج</th>
                    <th class="px-4 py-2 text-left">المعرف</th>
                    <th class="px-4 py-2 text-left">الوقت</th>
                    <th class="px-4 py-2 text-left">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $log->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $log->action }}</td>
                        <td class="px-4 py-2">{{ $log->model_type ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $log->model_id ?? '-' }}</td>
                        <td class="px-4 py-2">{{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد سجلات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists(($logs ?? null),'links'))
            {{ $logs->links() }}
        @endif
    </div>
    </div>
@endsection
