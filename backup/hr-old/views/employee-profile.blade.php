@extends('layouts.dashboard')

@section('title', 'ملف الموظف')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm lg:col-span-2">
        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $employee->user->name ?? ($employee->first_name.' '.$employee->last_name) }}</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">القسم</p>
                <p class="font-semibold text-gray-800">{{ $employee->department ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">الوظيفة</p>
                <p class="font-semibold text-gray-800">{{ $employee->position ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">الراتب الشهري</p>
                <p class="font-semibold text-gray-800">{{ number_format($employee->monthly_salary ?? 0, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">الأجر بالساعة</p>
                <p class="font-semibold text-gray-800">{{ number_format($employee->hourly_rate ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">الوثائق والعقود</h3>
        <div class="space-y-2">
            @forelse($documents as $doc)
            <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between">
                <div>
                    <p class="font-semibold text-gray-800">{{ $doc->document_type ?? 'وثيقة' }}</p>
                    <p class="text-xs text-gray-500">{{ $doc->created_at->format('Y-m-d') }}</p>
                </div>
                @if(!empty($doc->file_path))
                <a href="{{ $doc->file_path }}" class="text-indigo-600 text-sm">عرض</a>
                @endif
            </div>
            @empty
            <p class="text-center text-gray-500">لا توجد وثائق</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

