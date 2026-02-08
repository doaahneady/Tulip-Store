@extends('dashboards.layouts.app')
@section('content')
@php $title = 'حضوري'; $subtitle = 'تسجيل الدخول والخروج وعرض السجل'; @endphp
@php
    $formatDuration = function ($date, $in, $out) {
        if (! $in || ! $out) {
            return '-';
        }
        try {
            $start = \Carbon\Carbon::parse(\Carbon\Carbon::parse($date)->toDateString().' '.$in);
            $end = \Carbon\Carbon::parse(\Carbon\Carbon::parse($date)->toDateString().' '.$out);
            $mins = $start->diffInMinutes($end);
            $h = intdiv($mins, 60);
            $m = $mins % 60;
            return $h.':'.str_pad((string) $m, 2, '0', STR_PAD_LEFT);
        } catch (\Throwable $e) {
            return '-';
        }
    };
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 lg:col-span-1">
        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-calendar-day text-indigo-600 ml-2"></i>اليوم</h3>

        @if(($todayAttendances ?? collect())->count() > 0)
            <div class="space-y-2 text-sm text-gray-700">
                <div><span class="text-gray-500">عدد الورديات:</span> {{ number_format(($todayAttendances ?? collect())->count()) }}</div>
                <div><span class="text-gray-500">الوردية الحالية:</span> {{ $activeShift ? 'قيد العمل' : 'لا توجد' }}</div>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500">
                            <th class="text-right py-2">دخول</th>
                            <th class="text-right py-2">خروج</th>
                            <th class="text-right py-2">دقائق</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($todayAttendances ?? collect()) as $t)
                            <tr class="border-t border-gray-100">
                                <td class="py-2 text-gray-700">{{ $t->check_in ? \Carbon\Carbon::parse($t->check_in)->format('H:i') : '-' }}</td>
                                <td class="py-2 text-gray-700">{{ $t->check_out ? \Carbon\Carbon::parse($t->check_out)->format('H:i') : '-' }}</td>
                                <td class="py-2 text-gray-700">{{ $formatDuration($t->date, $t->check_in, $t->check_out) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-600">لم يتم تسجيل الحضور اليوم</p>
        @endif

        <div class="mt-5 flex flex-wrap gap-2">
            <form method="POST" action="{{ route('dashboard.my-attendance.check-in') }}">
                @csrf
                <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700" @disabled($activeShift)>تسجيل دخول</button>
            </form>
            <form method="POST" action="{{ route('dashboard.my-attendance.check-out') }}">
                @csrf
                <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700" @disabled(! $activeShift)>تسجيل خروج</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 lg:col-span-2">
        <h3 class="text-lg font-bold text-gray-900 mb-4"><i class="fas fa-list text-indigo-600 ml-2"></i>سجل الحضور</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500">
                        <th class="text-right py-2">التاريخ</th>
                        <th class="text-right py-2">دخول</th>
                        <th class="text-right py-2">خروج</th>
                        <th class="text-right py-2">المدة</th>
                        <th class="text-right py-2">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendance as $a)
                        <tr class="border-t border-gray-100">
                            <td class="py-3 text-gray-900 font-semibold">{{ optional($a->date)->format('Y-m-d') ?? $a->date }}</td>
                            <td class="py-3 text-gray-700">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}</td>
                            <td class="py-3 text-gray-700">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : '-' }}</td>
                            <td class="py-3 text-gray-700">{{ $formatDuration($a->date, $a->check_in, $a->check_out) }}</td>
                            <td class="py-3 text-gray-700">{{ $a->status ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-500">لا يوجد سجل</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $attendance->links() }}
        </div>
    </div>
</div>
@endsection
