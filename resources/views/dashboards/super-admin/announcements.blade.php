@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الإشعارات والإعلانات'; $subtitle = 'نشر الإعلانات للمستخدمين والموظفين'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">الإعلانات</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @forelse($announcements as $a)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm font-semibold text-gray-800">{{ $a->title }}</p>
                    <p class="text-xs text-gray-600">{{ $a->content }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $a->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-center text-gray-500">لا توجد إعلانات</p>
                @endforelse
            </div>
        </div>
        <div class="p-6">{{ $announcements->links() }}</div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">إعلان جديد</h3>
        <form method="POST" action="{{ route('dashboard.admin.announcements.create') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-sm text-gray-600">العنوان</label>
                <input name="title" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div>
                <label class="text-sm text-gray-600">المحتوى</label>
                <textarea name="content" class="w-full px-3 py-2 border rounded-lg" rows="4" required></textarea>
            </div>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg">نشر</button>
        </form>
    </div>
</div>
@endsection
