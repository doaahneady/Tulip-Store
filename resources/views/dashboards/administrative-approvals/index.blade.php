@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الموافقات الإدارية'; $subtitle = 'تقديم طلبات (مبلغ / إجازة / أخرى) ومتابعة حالتها'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800">طلب جديد</h3>
        @if($canManage)
            <a href="{{ route('dashboard.administrative-approvals.manage') }}" class="btn btn-secondary btn-sm">
                عرض الطلبات المعلقة
            </a>
        @endif
    </div>

    @if($requests === null)
        <div class="text-center text-gray-500 py-8">هذه الميزة غير متاحة حالياً</div>
    @else
        <form method="POST" action="{{ route('dashboard.administrative-approvals.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4" id="approvalForm">
            @csrf
            <div>
                <label class="block text-sm text-gray-700 mb-1">نوع الطلب</label>
                <select name="category" class="form-select w-full" required id="approvalCategory">
                    <option value="money" @selected(old('category') === 'money')>مبلغ / سلفة</option>
                    <option value="day_off" @selected(old('category') === 'day_off')>إجازة / يوم عطلة</option>
                    <option value="other" @selected(old('category') === 'other')>أخرى</option>
                </select>
            </div>
            <div id="approvalAmountWrap">
                <label class="block text-sm text-gray-700 mb-1">المبلغ (اختياري)</label>
                <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" class="form-input w-full" placeholder="مثال: 250.00" id="approvalAmount">
            </div>
            <div id="approvalStartWrap">
                <label class="block text-sm text-gray-700 mb-1">تاريخ البداية (اختياري)</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-input w-full" id="approvalStartDate">
            </div>
            <div id="approvalEndWrap">
                <label class="block text-sm text-gray-700 mb-1">تاريخ النهاية (اختياري)</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-input w-full" id="approvalEndDate">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-700 mb-1">تفاصيل الطلب</label>
                <textarea name="details" rows="3" class="form-textarea w-full" required>{{ old('details') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-secondary btn-sm">
                    إرسال الطلب
                </button>
            </div>
        </form>
        <script>
            (function () {
                const category = document.getElementById('approvalCategory');
                const amountWrap = document.getElementById('approvalAmountWrap');
                const startWrap = document.getElementById('approvalStartWrap');
                const endWrap = document.getElementById('approvalEndWrap');
                const amount = document.getElementById('approvalAmount');
                const start = document.getElementById('approvalStartDate');
                const end = document.getElementById('approvalEndDate');

                function setVisible(el, visible) {
                    if (!el) return;
                    el.style.display = visible ? '' : 'none';
                }

                function apply() {
                    const v = category?.value || 'other';
                    if (v === 'money') {
                        setVisible(amountWrap, true);
                        setVisible(startWrap, false);
                        setVisible(endWrap, false);
                        if (amount) amount.required = true;
                        if (start) start.required = false;
                        if (end) end.required = false;
                    } else if (v === 'day_off') {
                        setVisible(amountWrap, false);
                        setVisible(startWrap, true);
                        setVisible(endWrap, true);
                        if (amount) amount.required = false;
                        if (start) start.required = true;
                        if (end) end.required = true;
                    } else {
                        setVisible(amountWrap, false);
                        setVisible(startWrap, false);
                        setVisible(endWrap, false);
                        if (amount) amount.required = false;
                        if (start) start.required = false;
                        if (end) end.required = false;
                    }
                }

                category?.addEventListener('change', apply);
                apply();
            })();
        </script>
    @endif
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">طلباتي</h3>
        <a href="{{ route('dashboard.main') }}" class="text-sm text-indigo-600">عودة</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-2 text-right">النوع</th>
                    <th class="px-4 py-2 text-right">المبلغ</th>
                    <th class="px-4 py-2 text-right">الفترة</th>
                    <th class="px-4 py-2 text-right">الحالة</th>
                    <th class="px-4 py-2 text-right">تاريخ الطلب</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @if($requests === null)
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @else
                    @forelse($requests as $r)
                        @php
                            $categoryName = match ($r->category) {
                                'money' => 'مبلغ',
                                'day_off' => 'إجازة',
                                default => 'أخرى',
                            };
                            $statusColor = match ($r->status) {
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                default => 'bg-amber-100 text-amber-700',
                            };
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-gray-800 font-semibold">{{ $categoryName }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $r->amount !== null ? number_format((float) $r->amount, 2) : '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                @if($r->start_date || $r->end_date)
                                    {{ optional($r->start_date)->format('Y-m-d') ?? '-' }} → {{ optional($r->end_date)->format('Y-m-d') ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $statusColor }}">{{ $r->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ optional($r->created_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr class="bg-gray-50/50">
                            <td colspan="5" class="px-4 py-3 text-xs text-gray-600">
                                {{ $r->details }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد طلبات</td>
                        </tr>
                    @endforelse
                @endif
            </tbody>
        </table>
    </div>
    <div class="p-4">
        @if(method_exists(($requests ?? null), 'links'))
            {{ $requests->links() }}
        @endif
    </div>
</div>
@endsection
