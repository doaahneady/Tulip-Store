@extends('dashboards.layouts.app')
@section('content')
@php $title = 'مؤشرات الأقسام'; $subtitle = 'HR و Finance و Support و Drivers'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-user-tie text-purple-500 ml-2"></i>الموارد البشرية</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 bg-purple-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-purple-600">{{ number_format($kpis['hr']['total_employees']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">إجمالي الموظفين</p>
                <p class="text-xs text-green-600 mt-1">نشطون: {{ number_format($kpis['hr']['total_employees']['active'] ?? 0) }}</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-600">{{ number_format($kpis['hr']['present_today']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">حضور اليوم</p>
                <p class="text-xs text-blue-600 mt-1">{{ ($kpis['hr']['present_today']['percentage'] ?? 0) }}%</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($kpis['hr']['on_leave']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">على إجازة</p>
            </div>
            <div class="p-4 bg-orange-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-orange-600">{{ number_format($kpis['hr']['pending_requests']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">طلبات معلّقة</p>
            </div>
            <div class="p-4 bg-red-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-red-600">{{ number_format($kpis['hr']['absent_today']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">غياب اليوم</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-coins text-emerald-500 ml-2"></i>المالية</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 bg-emerald-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-emerald-600">{{ data_get($kpis,'finance.daily_revenue.formatted','0') }}</p>
                <p class="text-xs text-gray-500">إيراد اليوم</p>
                <p class="text-xs mt-1 text-{{ data_get($kpis,'finance.daily_revenue.growth.color','gray') }}-600">{{ data_get($kpis,'finance.daily_revenue.growth.value', '0%') }}</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-600">{{ data_get($kpis,'finance.monthly_revenue.formatted','0') }}</p>
                <p class="text-xs text-gray-500">إيراد الشهر</p>
                <p class="text-xs mt-1 text-{{ data_get($kpis,'finance.monthly_revenue.growth.color','gray') }}-600">{{ data_get($kpis,'finance.monthly_revenue.growth.value', '0%') }}</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ data_get($kpis,'finance.pending_payouts.formatted','0') }}</p>
                <p class="text-xs text-gray-500">مدفوعات معلّقة</p>
                <p class="text-xs text-yellow-600 mt-1">عدد: {{ number_format($kpis['finance']['pending_payouts']['count'] ?? 0) }}</p>
            </div>
            <div class="p-4 bg-indigo-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-indigo-600 text-{{ data_get($kpis,'finance.profit_margin.formatted.color','gray') }}-600">{{ data_get($kpis,'finance.profit_margin.formatted.value','0%') }}</p>
                <p class="text-xs text-gray-500">هامش الربح</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-gray-700">{{ data_get($kpis,'finance.total_expenses.formatted','0') }}</p>
                <p class="text-xs text-gray-500">إجمالي المصروفات</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-headset text-amber-500 ml-2"></i>خدمة العملاء</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 bg-amber-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-amber-600">{{ number_format($kpis['support']['open_tickets']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">تذاكر مفتوحة</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($kpis['support']['pending_tickets']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">تذاكر معلّقة</p>
            </div>
            <div class="p-4 bg-green-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($kpis['support']['resolved_today']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">تم حلها اليوم</p>
                <p class="text-xs mt-1 text-{{ data_get($kpis,'support.resolved_today.growth.color','gray') }}-600">{{ data_get($kpis,'support.resolved_today.growth.value', data_get($kpis,'support.resolved_today.growth','0%')) }}</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-600">{{ data_get($kpis,'support.avg_response_time.formatted.value', data_get($kpis,'support.avg_response_time.formatted','0')) }} {{ data_get($kpis,'support.avg_response_time.formatted.unit','ساعة') }}</p>
                <p class="text-xs text-gray-500">متوسط زمن الاستجابة</p>
            </div>
            <div class="p-4 bg-indigo-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ number_format($kpis['support']['tickets_this_month']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">تذاكر هذا الشهر</p>
                <p class="text-xs mt-1 text-{{ data_get($kpis,'support.tickets_this_month.growth.color','gray') }}-600">{{ data_get($kpis,'support.tickets_this_month.growth.value', data_get($kpis,'support.tickets_this_month.growth','0%')) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-truck text-orange-500 ml-2"></i>السائقون والتوصيل</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-4 bg-orange-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-orange-600">{{ number_format($kpis['drivers']['active_drivers']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">سائقون نشطون</p>
                <p class="text-xs text-orange-600 mt-1">{{ number_format($kpis['drivers']['active_drivers']['percentage'] ?? 0, 1) }}%</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($kpis['drivers']['pending_deliveries']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">طلبات بانتظار التعيين</p>
            </div>
            <div class="p-4 bg-green-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($kpis['drivers']['completed_today']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">تم التسليم اليوم</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-600">{{ number_format($kpis['drivers']['in_transit']['value'] ?? 0) }}</p>
                <p class="text-xs text-gray-500">قيد التوصيل الآن</p>
            </div>
            <div class="p-4 bg-indigo-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ number_format($kpis['drivers']['avg_delivery_time']['value'] ?? 0) }} {{ data_get($kpis,'drivers.avg_delivery_time.unit','min') }}</p>
                <p class="text-xs text-gray-500">متوسط زمن التوصيل</p>
            </div>
        </div>
    </div>
</div>
@endsection
