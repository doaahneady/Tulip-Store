@extends('dashboards.layouts.app')
@section('content')
@php $title = 'خصائص النظام (Feature Toggles)'; $subtitle = 'تفعيل وتعطيل الخصائص'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">خصائص</h3>
    <form method="POST" action="{{ route('dashboard.admin.features.update') }}" class="space-y-3">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($features as $feature)
            <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $feature->key }}</p>
                    <p class="text-xs text-gray-500">القيمة الحالية: {{ $feature->value }}</p>
                </div>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="features[{{ $feature->key }}]" value="1" @checked(($feature->value ?? '') === 'true')>
                    <span class="text-sm text-gray-600">مفعل</span>
                </label>
            </div>
            @empty
            <p class="text-gray-500">لا توجد خصائص مُسجلة</p>
            @endforelse
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">حفظ</button>
    </form>
</div>
@endsection
