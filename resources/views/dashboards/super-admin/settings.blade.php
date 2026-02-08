@extends('dashboards.layouts.app')
@section('content')
@php $title = 'إعدادات النظام'; $subtitle = 'تهيئة مفاتيح الإعدادات والميزات'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach(($settings ?? collect())->toArray() as $group => $items)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ is_string($group) ? $group : 'عام' }}</h3>
        <div class="space-y-3">
            @foreach(($items ?? []) as $setting)
            <div class="p-3 border border-gray-100 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $setting['key'] ?? ($setting->key ?? '-') }}</p>
                        <p class="text-xs text-gray-500">{{ $setting['description'] ?? ($setting->description ?? '') }}</p>
                    </div>
                    <div class="text-sm text-gray-700">
                        @php 
                            $val = is_array($setting) ? ($setting['value'] ?? null) : ($setting->value ?? null);
                        @endphp
                        @if(is_array($val))
                            <code>{{ json_encode($val) }}</code>
                        @else
                            <span>{{ (string) $val }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection
