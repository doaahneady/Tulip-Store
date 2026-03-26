@extends('dashboards.layouts.app')
@section('content')
@php $title = 'لوحة المالية'; $subtitle = 'نظرة عامة على الإيرادات والتكاليف والتفصيل'; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-gray-800">سعر الصرف (USD -> SYP)</h3>
            <p class="text-xs text-gray-500">تعديل السعر المستخدم في الواجهة لتحويل العملات</p>
        </div>
        <form method="POST" action="{{ route('dashboard.finance.exchange-rate.update') }}" class="flex items-center gap-2">
            @csrf
            <input
                type="number"
                name="usd_to_syp_rate"
                step="0.01"
                min="1"
                value="{{ \App\Models\SystemSetting::get('usd_to_syp_rate', 117) }}"
                class="form-input w-40"
                required
            >
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i>
                تحديث
            </button>
        </form>
    </div>
</div>

<div class="top-0  bg-white/80 backdrop-blur-sm border-b border-gray-200 mb-6">
    <div class="px-4 py-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-coins text-indigo-600"></i>
            <span class="text-sm text-gray-700">تحكم سريع</span>
        </div>
        <form method="GET" action="{{ route('dashboard.finance.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="date" name="from" value="{{ request('from') }}" class="form-input w-40">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input w-40">
            <select name="period" class="form-select w-36">
                <option value="">الفترة</option>
                <option value="7" @selected(request('period')=='7')>آخر 7 أيام</option>
                <option value="30" @selected(request('period')=='30')>آخر 30 يوم</option>
                <option value="90" @selected(request('period')=='90')>آخر 90 يوم</option>
            </select>
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-filter"></i>
                تصفية
            </button>
            <a class="btn btn-secondary btn-sm" href="{{ route('dashboard.finance.transactions.export', array_merge(request()->query(), ['format' => 'csv'])) }}">
                <i class="fas fa-download"></i>
                تصدير المعاملات
            </a>
        </form>
    </div>
</div>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard.finance.expenses') }}#new-expense" class="inline-flex items-center gap-2 bg-rose-600 text-white px-4 py-2 rounded-xl hover:bg-rose-700 transition">
            <i class="fas fa-plus"></i>
            <span>إضافة مصروف</span>
        </a>
        <a href="{{ route('dashboard.finance.transactions') }}#new-transaction" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-plus"></i>
            <span>إنشاء معاملة</span>
        </a>
        <a href="{{ route('dashboard.administrative-approvals.index') }}" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition">
            <i class="fas fa-clipboard-check"></i>
            <span>الموافقات الإدارية</span>
        </a>
        <a href="{{ route('dashboard.my-attendance.index') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700 transition">
            <i class="fas fa-user-clock"></i>
            <span>حضوري</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
    <a href="{{ route('dashboard.finance.transactions') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="text-sm font-semibold text-gray-900">المعاملات</div>
            <div class="text-xs text-gray-500">قائمة وبحث وتصدير</div>
        </div>
    </a>
    <a href="{{ route('dashboard.finance.approvals') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center"><i class="fas fa-user-check"></i></div>
        <div>
            <div class="text-sm font-semibold text-gray-900">الموافقات</div>
            <div class="text-xs text-gray-500">استردادات / عمولات / رواتب</div>
        </div>
    </a>
    <a href="{{ route('dashboard.finance.payouts') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center"><i class="fas fa-hand-holding-dollar"></i></div>
        <div>
            <div class="text-sm font-semibold text-gray-900">التحويلات</div>
            <div class="text-xs text-gray-500">طلبات المتاجر</div>
        </div>
    </a>
    <a href="{{ route('dashboard.finance.driver-deliveries.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center"><i class="fas fa-truck"></i></div>
        <div>
            <div class="text-sm font-semibold text-gray-900">تسليمات السائقين</div>
            <div class="text-xs text-gray-500">اعتماد الدفع والسجل</div>
        </div>
    </a>
    <a href="{{ route('dashboard.finance.revenue') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center"><i class="fas fa-chart-line"></i></div>
        <div>
            <div class="text-sm font-semibold text-gray-900">الإيرادات</div>
            <div class="text-xs text-gray-500">تحليلات</div>
        </div>
    </a>
    <a href="{{ route('dashboard.finance.expenses') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="text-sm font-semibold text-gray-900">المصروفات</div>
            <div class="text-xs text-gray-500">قائمة وتقارير</div>
        </div>
    </a>
    <a href="{{ route('dashboard.finance.reports') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center"><i class="fas fa-file-invoice-dollar"></i></div>
        <div>
            <div class="text-sm font-semibold text-gray-900">التقارير</div>
            <div class="text-xs text-gray-500">P&L / Cash Flow</div>
        </div>
    </a>
</div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
    <x-dashboard.stat-card title="إيرادات اليوم" :value="app(\App\Services\CurrencyService::class)->formatUsd((float) ($metrics['todays_revenue'] ?? 0))" icon="fas fa-sun" color="orange" />
    <x-dashboard.stat-card title="إيرادات الشهر" :value="app(\App\Services\CurrencyService::class)->formatUsd((float) ($metrics['monthly_revenue'] ?? 0))" icon="fas fa-calendar-alt" color="blue" />
    <x-dashboard.stat-card title="إيراد التوصيل (الشهر)" :value="app(\App\Services\CurrencyService::class)->formatUsd((float) ($metrics['monthly_delivery'] ?? 0))" icon="fas fa-truck" color="teal" />
    <x-dashboard.stat-card title="المدفوعات المعلقة" :value="app(\App\Services\CurrencyService::class)->formatUsd((float) ($metrics['outstanding_payments'] ?? 0))" icon="fas fa-exclamation-circle" color="red" />
    <x-dashboard.stat-card title="الاستردادات المعلقة" :value="app(\App\Services\CurrencyService::class)->formatUsd((float) ($metrics['pending_refunds'] ?? 0))" icon="fas fa-undo" color="purple" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="lg:col-span-2">
        @component('components.dashboard.chart-card', ['title' => 'الإيرادات مقابل المصروفات', 'icon' => 'fas fa-chart-line', 'chartId' => 'revExpChart'])
        @endcomponent
    </div>
    <div>
        @component('components.dashboard.chart-card', ['title' => 'تفصيل طرق الدفع', 'icon' => 'fas fa-chart-pie', 'chartId' => 'paymentMethodChart'])
        @endcomponent
    </div>
    <div>
        @component('components.dashboard.chart-card', ['title' => 'توقعات التدفق النقدي (30 يوم)', 'icon' => 'fas fa-chart-area', 'chartId' => 'cashFlowChart'])
        @endcomponent
    </div>

    <div class="lg:col-span-2">
        <x-dashboard.collapsible title="تقارير تفصيلية" icon="fas fa-layer-group" subtitle="المتاجر / المستخدمون / السائقون / الرواتب">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-black text-gray-900">الأكثر ربحاً (المتاجر)</h3>
                    <span class="text-xs text-gray-500">آخر 30 يوم</span>
                </div>
                <div class="p-4">
            <div class="overflow-auto max-h-[340px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-600">المتجر</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-600">الإيرادات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(($metrics['revenue_by_store'] ?? []) as $store)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2.5 text-sm text-gray-800">{{ is_array(data_get($store,'name')) ? json_encode(data_get($store,'name')) : data_get($store,'name') }}</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-gray-900">@money($store->total_revenue ?? 0)</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-black text-gray-900">الإنفاق حسب المستخدمين</h3>
                    <span class="text-xs text-gray-500">آخر 30 يوم</span>
                </div>
                <div class="p-4">
            <div class="overflow-auto max-h-[340px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-600">المستخدم</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-600">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(($metrics['revenue_by_user'] ?? []) as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2.5 text-sm text-gray-800">{{ is_array(data_get($user,'name')) ? json_encode(data_get($user,'name')) : data_get($user,'name') }}</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-gray-900">@money($user->total_spent ?? 0)</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-black text-gray-900">قيمة الطلبات حسب السائقين</h3>
                    <span class="text-xs text-gray-500">آخر 30 يوم</span>
                </div>
                <div class="p-4">
            <div class="overflow-auto max-h-[340px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-600">السائق</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-600">القيمة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(($metrics['revenue_by_driver'] ?? []) as $driver)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2.5 text-sm text-gray-800">{{ is_array(data_get($driver,'driver_name')) ? json_encode(data_get($driver,'driver_name')) : (data_get($driver,'driver_name') ?? (is_array(data_get($driver,'name')) ? json_encode(data_get($driver,'name')) : (data_get($driver,'name') ?? ('Driver #'.$driver->id)))) }}</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-gray-900">@money($driver->total_delivered_value ?? 0)</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700">
                <div class="p-4 border-b border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-black text-white">تكلفة الرواتب حسب الموظف</h3>
                    <span class="text-xs text-gray-400">الشهر الحالي</span>
                </div>
                <div class="p-4">
            <div class="overflow-auto max-h-[340px]">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">الموظف</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">الصافي</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse(($metrics['money_by_employee'] ?? []) as $employee)
                            <tr class="hover:bg-gray-700">
                                <td class="px-4 py-2.5 text-sm text-white">{{ ($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '') }}</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-white">@money($employee->total_pay ?? 0)</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-4 py-6 text-center text-gray-400">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                </div>
            </div>
            </div>
        </x-dashboard.collapsible>
    </div>
</div>

<x-dashboard.collapsible title="طلبات قيد الانتظار" icon="fas fa-hourglass-half" subtitle="الموافقات والتحويلات">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700">
        <div class="p-4 border-b border-gray-700 flex items-center justify-between">
            <h3 class="text-sm font-black text-white">طلبات الاسترداد / الموافقات</h3>
            <a href="{{ route('dashboard.finance.approvals') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">عرض الكل</a>
        </div>
        <div class="p-4">
            <div class="overflow-auto max-h-[360px]">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">النوع</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">المبلغ</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">الطلب</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">تاريخ</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse(($metrics['pending_approvals_list'] ?? []) as $t)
                            <tr class="hover:bg-gray-700">
                                <td class="px-4 py-2.5 text-sm text-white">{{ $t->type }}</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-white">@money($t->amount ?? 0)</td>
                                <td class="px-4 py-2.5 text-sm text-gray-300">{{ optional($t->order)->order_number ?? '-' }}</td>
                                <td class="px-4 py-2.5 text-sm text-gray-400">{{ $t->created_at?->diffForHumans() }}</td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('dashboard.finance.approvals.transactions.approve', $t->id) }}">
                                            @csrf
                                            <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700">موافقة</button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.finance.approvals.transactions.reject', $t->id) }}">
                                            @csrf
                                            <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">رفض</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">لا توجد طلبات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700">
        <div class="p-4 border-b border-gray-700 flex items-center justify-between">
            <h3 class="text-sm font-black text-white">طلبات تحويل المتاجر</h3>
            <a href="{{ route('dashboard.finance.payouts') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">عرض الكل</a>
        </div>
        <div class="p-4">
            <div class="overflow-auto max-h-[360px]">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">المتجر</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">المبلغ</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">تاريخ</th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-300">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse(($metrics['pending_payouts_list'] ?? []) as $p)
                            <tr class="hover:bg-gray-700">
                                <td class="px-4 py-2.5 text-sm text-white">{{ optional($p->store)->name ?? ('Store #'.$p->store_id) }}</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-white">@money($p->amount ?? 0)</td>
                                <td class="px-4 py-2.5 text-sm text-gray-400">{{ $p->created_at?->diffForHumans() }}</td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('dashboard.finance.approvals.payouts.approve', $p->id) }}">
                                            @csrf
                                            <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs hover:bg-emerald-700">موافقة</button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.finance.approvals.payouts.reject', $p->id) }}">
                                            @csrf
                                            <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">رفض</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">لا توجد طلبات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    </div>
</x-dashboard.collapsible>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const revExpCtx = document.getElementById('revExpChart')?.getContext('2d');
if (revExpCtx) {
    const revLabels = @json(collect($metrics['revenue_series'] ?? [])->pluck('date'));
    const revData = @json(collect($metrics['revenue_series'] ?? [])->pluck('revenue'));
    const expData = @json(collect($metrics['expense_series'] ?? [])->pluck('expenses'));
    new Chart(revExpCtx, {
        type: 'line',
        data: {
            labels: revLabels,
            datasets: [
                {
                    label: 'الإيرادات',
                    data: revData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'المصروفات',
                    data: expData,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { mode: 'index', intersect: false }
            },
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

const pmCtx = document.getElementById('paymentMethodChart')?.getContext('2d');
if (pmCtx) {
    const pmDataObj = @json($metrics['payment_method_breakdown'] ?? []);
    const pmLabels = Object.keys(pmDataObj);
    const pmData = Object.values(pmDataObj);
    new Chart(pmCtx, {
        type: 'doughnut',
        data: {
            labels: pmLabels.map(l => {
                if (l === 'card') return 'بطاقة';
                if (l === 'cash') return 'نقد';
                if (l === 'mobile_wallet') return 'محفظة رقمية';
                return l;
            }),
            datasets: [{
                data: pmData,
                backgroundColor: ['#6366f1', '#f59e0b', '#10b981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

const cfCtx = document.getElementById('cashFlowChart')?.getContext('2d');
if (cfCtx) {
    const cfLabels = @json(collect($metrics['cash_flow_projection'] ?? [])->pluck('date'));
    const cfData = @json(collect($metrics['cash_flow_projection' ] ?? [])->pluck('net'));
    new Chart(cfCtx, {
        type: 'bar',
        data: {
            labels: cfLabels,
            datasets: [{
                label: 'صافي التدفق المتوقع',
                data: cfData,
                backgroundColor: 'rgba(99, 102, 241, 0.6)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}
</script>
<script>
async function loadTraderPayouts() {
    try {
        const res = await fetch('/api/finance/trader-payouts');
        const data = await res.json();
        const container = document.getElementById('trader-payouts-list');
        const rows = (data.payouts?.data ?? []).map(p => {
            return `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-800">#${p.id}</td>
                <td class="px-4 py-3 text-sm text-gray-800">${p.trader_id}</td>
                <td class="px-4 py-3 text-sm text-gray-900 font-semibold">${window.formatMoney ? window.formatMoney(p.amount ?? 0) : ('$' + Number(p.amount ?? 0).toFixed(2))}</td>
                <td class="px-4 py-3 text-sm text-gray-800">${p.status}</td>
                <td class="px-4 py-3 text-sm text-gray-800">${p.reference_number ?? '-'}</td>
                <td class="px-4 py-3 text-sm">
                    <button onclick="approvePayout(${p.id})" class="bg-green-600 text-white px-3 py-1 rounded">موافقة</button>
                    <button onclick="completePayout(${p.id})" class="bg-indigo-600 text-white px-3 py-1 rounded">إتمام</button>
                </td>
            </tr>`;
        }).join('');
        container.innerHTML = rows || `<tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد مدفوعات</td></tr>`;
    } catch (e) {}
}
async function approvePayout(id) {
    try {
        const res = await fetch(`/api/finance/trader-payouts/${id}/approve`, { method: 'POST' });
        await res.json();
        await loadTraderPayouts();
    } catch (e) {}
}
async function completePayout(id) {
    const ref = prompt('مرجع العملية البنكية؟') || '';
    if (!ref) return;
    try {
        const res = await fetch(`/api/finance/trader-payouts/${id}/complete`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ transaction_reference: ref }) });
        await res.json();
        await loadTraderPayouts();
    } catch (e) {}
}
document.addEventListener('DOMContentLoaded', () => loadTraderPayouts());
</script>
@endpush
@push('content_after')
<div class="bg-white rounded-2xl shadow-sm mt-8">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">مدفوعات التجار</h3>
        <span class="text-xs text-gray-500">واجهة قابلة للنقر</span>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">#</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">التاجر</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المبلغ</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">المرجع</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="trader-payouts-list" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>
    </div>
</div>
@endpush
