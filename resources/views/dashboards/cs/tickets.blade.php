@extends('dashboards.layouts.app')
@section('content')
@php $title = 'تذاكر الدعم'; $subtitle = 'البحث والتصفية وإدارة التذاكر'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">تذكرة جديدة</h3>
    </div>
    <form method="POST" action="{{ route('dashboard.cs.tickets.create') }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @csrf
        <div>
            <label class="block text-sm text-gray-700 mb-1">بريد العميل</label>
            <input name="user_email" value="{{ old('user_email') }}" placeholder="customer@example.com" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">User ID (اختياري)</label>
            <input name="user_id" value="{{ old('user_id') }}" placeholder="123" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm text-gray-700 mb-1">الموضوع</label>
            <input name="subject" value="{{ old('subject') }}" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm text-gray-700 mb-1">الوصف</label>
            <textarea name="description" rows="3" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">الأولوية</label>
            <select name="priority" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                @foreach(['low','medium','high','urgent'] as $p)
                    <option value="{{ $p }}" @selected(old('priority','medium') === $p)>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">التصنيف (اختياري)</label>
            <input name="category" value="{{ old('category') }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-plus"></i>
                <span>إنشاء تذكرة</span>
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="{{ route('dashboard.cs.tickets') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="بحث: رقم التذكرة / الموضوع / العميل" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">

        <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">كل الحالات</option>
            @foreach(['open','pending','in_progress','waiting_customer','resolved','closed'] as $st)
                <option value="{{ $st }}" @selected(($filters['status'] ?? '') === $st)>{{ $st }}</option>
            @endforeach
        </select>

        <select name="priority" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">كل الأولويات</option>
            @foreach(['urgent','high','medium','low'] as $pr)
                <option value="{{ $pr }}" @selected(($filters['priority'] ?? '') === $pr)>{{ $pr }}</option>
            @endforeach
        </select>

        <select name="assigned_to" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">الكل</option>
            <option value="{{ auth('employee')->id() }}" @selected(request('assigned_to') == auth('employee')->id())>تذاكري</option>
            <option value="0" @selected(request('assigned_to') === '0')>غير مخصصة</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-filter"></i>
                <span>تصفية</span>
            </button>
            <a href="{{ route('dashboard.cs.tickets') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">قائمة التذاكر</h3>
        <span class="text-sm text-gray-500">{{ $tickets->total() }} تذكرة</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500">
                    <th class="text-right py-2">رقم</th>
                    <th class="text-right py-2">الموضوع</th>
                    <th class="text-right py-2">العميل</th>
                    <th class="text-right py-2">الأولوية</th>
                    <th class="text-right py-2">الحالة</th>
                    <th class="text-right py-2">المسؤول</th>
                    <th class="text-right py-2">تاريخ</th>
                    <th class="text-right py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $t)
                    @php
                        $ticketSubject = $t->subject ?? '';
                        if (is_array($ticketSubject)) {
                            $ticketSubject = $ticketSubject['ar'] ?? ($ticketSubject['en'] ?? '');
                        }
                        $customerName = optional($t->user)->name ?? null;
                        if (is_array($customerName)) {
                            $customerName = $customerName['ar'] ?? ($customerName['en'] ?? '');
                        }
                    @endphp
                    <tr class="border-t border-gray-100 align-top">
                        <td class="py-3 text-gray-900 font-semibold">{{ $t->ticket_number }}</td>
                        <td class="py-3 text-gray-800">
                            <div class="font-semibold">{{ $ticketSubject }}</div>
                            <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($t->description, 70) }}</div>
                        </td>
                        <td class="py-3 text-gray-700">
                            <div class="font-semibold">{{ $customerName ?: ('User #'.$t->user_id) }}</div>
                            <div class="text-xs text-gray-500">{{ optional($t->user)->email }}</div>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">{{ $t->priority }}</span>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs bg-indigo-100 text-indigo-700">{{ $t->status }}</span>
                        </td>
                        <td class="py-3 text-gray-700">
                            {{ optional($t->assignedTo)->full_name ?? ($t->assigned_to ? ('#'.$t->assigned_to) : 'غير مخصص') }}
                        </td>
                        <td class="py-3 text-gray-600">
                            <div>{{ $t->created_at?->format('Y-m-d') }}</div>
                            <div class="text-xs text-gray-400">{{ $t->created_at?->diffForHumans() }}</div>
                        </td>
                        <td class="py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('dashboard.cs.tickets.show', $t->id) }}" class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs text-gray-700 hover:bg-gray-50">فتح</a>

                                @if(empty($t->assigned_to))
                                    <form method="POST" action="{{ route('dashboard.cs.tickets.assign-to-me', $t->id) }}">
                                        @csrf
                                        <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700">تخصيص لي</button>
                                    </form>
                                @endif

                                @if(in_array($t->status, ['open','pending','in_progress','waiting_customer'], true))
                                    <form method="POST" action="{{ route('dashboard.cs.tickets.resolve', $t->id) }}">
                                        @csrf
                                        <button class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700">حل</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-10 text-center text-gray-500">لا توجد نتائج</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $tickets->withQueryString()->links() }}
    </div>
</div>
@endsection
