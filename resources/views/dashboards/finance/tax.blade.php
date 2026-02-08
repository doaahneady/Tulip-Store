@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الضرائب والالتزام'; $subtitle = 'ملخص الضرائب والتقارير'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="text-sm text-gray-700">يمكن تصدير تقرير الضرائب من هنا</div>
    <div class="flex gap-2">
        <a href="{{ route('dashboard.finance.tax.export', ['format' => 'csv', 'period' => request('period','Q4-2025')]) }}" class="px-4 py-2 rounded-xl bg-gray-900 text-white hover:bg-black">CSV</a>
        <a href="{{ route('dashboard.finance.tax.export', ['format' => 'pdf', 'period' => request('period','Q4-2025')]) }}" class="px-4 py-2 rounded-xl bg-rose-600 text-white hover:bg-rose-700">PDF</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">VAT Summary</h3>
        <pre class="text-xs bg-gray-50 rounded-xl p-4 overflow-auto">{{ json_encode($taxData['vat_summary'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Tax Breakdown</h3>
        <pre class="text-xs bg-gray-50 rounded-xl p-4 overflow-auto">{{ json_encode($taxData['tax_breakdown'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Compliance Status</h3>
        <pre class="text-xs bg-gray-50 rounded-xl p-4 overflow-auto">{{ json_encode($taxData['compliance_status'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Upcoming Deadlines</h3>
        <pre class="text-xs bg-gray-50 rounded-xl p-4 overflow-auto">{{ json_encode($taxData['upcoming_deadlines'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
</div>
@endsection

