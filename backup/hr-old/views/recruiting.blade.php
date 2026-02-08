@extends('layouts.dashboard')

@section('title', 'التوظيف والتعيين')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">الوظائف المفتوحة</h3>
        <div class="space-y-2">
            @forelse($positions as $pos)
            <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between">
                <div>
                    <p class="font-semibold text-gray-800">{{ $pos->title }}</p>
                    <p class="text-sm text-gray-500">{{ $pos->department }}</p>
                </div>
                <span class="text-xs text-indigo-600">{{ $pos->status }}</span>
            </div>
            @empty
            <p class="text-center text-gray-500">لا توجد وظائف</p>
            @endforelse
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">أحدث الطلبات</h3>
        <div class="space-y-2">
            @forelse($applications as $app)
            <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between">
                <div>
                    <p class="font-semibold text-gray-800">{{ $app->position->title ?? 'وظيفة' }}</p>
                    <p class="text-sm text-gray-500">{{ $app->status }}</p>
                </div>
                <span class="text-xs text-gray-500">{{ $app->created_at->format('Y-m-d') }}</span>
            </div>
            @empty
            <p class="text-center text-gray-500">لا توجد طلبات</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

