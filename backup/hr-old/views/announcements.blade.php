@extends('layouts.dashboard')

@section('title', 'إعلانات الموارد البشرية')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm lg:col-span-2">
        <h3 class="text-lg font-bold text-gray-800 mb-4">الإعلانات</h3>
        <div class="space-y-2">
            @forelse($announcements as $a)
            <div class="p-3 bg-gray-50 rounded-xl">
                <p class="font-semibold text-gray-800">{{ $a->title }}</p>
                <p class="text-sm text-gray-600">{{ $a->content }}</p>
                <p class="text-xs text-gray-500">{{ $a->created_at->format('Y-m-d H:i') }}</p>
            </div>
            @empty
            <p class="text-center text-gray-500">لا توجد إعلانات</p>
            @endforelse
        </div>
        <div class="mt-4">
            @if(method_exists(($announcements ?? null),'links'))
                {{ $announcements->links() }}
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4">إعلان جديد</h3>
        <form method="POST" action="{{ route('dashboard.hr.announcements.create') }}" class="space-y-3">
            @csrf
            <input type="text" name="title" class="form-input w-full" placeholder="العنوان">
            <textarea name="content" class="form-textarea w-full" rows="4" placeholder="المحتوى"></textarea>
            <select name="type" class="form-select w-full">
                <option value="general">عام</option>
                <option value="policy">سياسة</option>
                <option value="event">حدث</option>
                <option value="urgent">عاجل</option>
                <option value="celebration">احتفال</option>
            </select>
            <select name="target_audience" class="form-select w-full">
                <option value="all">الجميع</option>
                <option value="department">قسم</option>
                <option value="role">دور</option>
                <option value="specific_users">مستخدمون محددون</option>
            </select>
            <button class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg">نشر</button>
        </form>
    </div>
</div>
@endsection

