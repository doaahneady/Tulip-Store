@extends('dashboards.layouts.app')
@section('content')
@php $title = 'الموافقات الإدارية'; $subtitle = 'المالية والموارد البشرية والإجراءات الحرجة'; @endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">المعاملات المالية بانتظار الموافقة</h3>
            <span class="text-xs text-gray-500">عدد: {{ $financialTransactions->total() }}</span>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @forelse($financialTransactions as $tx)
                <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                    <div class="text-sm">
                        <p class="text-gray-800 font-semibold">#{{ $tx->transaction_id }} - {{ $tx->type }} - {{ number_format($tx->amount ?? 0, 2) }}</p>
                        <p class="text-gray-500">الحالة: {{ $tx->status }}</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('dashboard.admin.approvals.transactions.approve', $tx) }}">
                            @csrf
                            <button class="bg-green-600 text-white px-3 py-1 rounded">موافقة</button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.admin.approvals.transactions.reject', $tx) }}">
                            @csrf
                            <button class="bg-red-600 text-white px-3 py-1 rounded">رفض</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500">لا توجد معاملات</p>
                @endforelse
            </div>
        </div>
        <div class="p-6">{{ $financialTransactions->links() }}</div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">طلبات الإجازة بانتظار الموافقة</h3>
            <span class="text-xs text-gray-500">عدد: {{ $leaveRequests->total() }}</span>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @forelse($leaveRequests as $lv)
                <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                    <div class="text-sm">
                        <p class="text-gray-800 font-semibold">{{ $lv->employee->full_name ?? 'موظف' }} - {{ $lv->type ?? 'إجازة' }}</p>
                        <p class="text-gray-500">من {{ $lv->start_date }} إلى {{ $lv->end_date }}</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('dashboard.admin.approvals.leaves.approve', $lv) }}">
                            @csrf
                            <button class="bg-green-600 text-white px-3 py-1 rounded">موافقة</button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.admin.approvals.leaves.reject', $lv) }}">
                            @csrf
                            <button class="bg-red-600 text-white px-3 py-1 rounded">رفض</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500">لا توجد طلبات إجازة</p>
                @endforelse
            </div>
        </div>
        <div class="p-6">{{ $leaveRequests->links() }}</div>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات حرجة</h3>
    <p class="text-sm text-gray-600">يتم تسجيل جميع الإجراءات الحرجة في سجل التدقيق.</p>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">طلبات التجار بانتظار الموافقة</h3>
            <span id="pending-traders-count" class="text-xs text-gray-500">—</span>
        </div>
        <div class="p-6">
            <div id="pending-traders" class="space-y-3"></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">منتجات التجار قيد المراجعة</h3>
            <span id="pending-products-count" class="text-xs text-gray-500">—</span>
        </div>
        <div class="p-6">
            <div id="pending-products" class="space-y-3"></div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
async function loadPendingTraders() {
    try {
        const res = await fetch('/api/support/traders/pending');
        const data = await res.json();
        const list = document.getElementById('pending-traders');
        const count = document.getElementById('pending-traders-count');
        count.textContent = (data.traders?.data?.length ?? 0) + '';
        list.innerHTML = '';
        (data.traders?.data ?? []).forEach(t => {
            const row = document.createElement('div');
            row.className = 'p-3 bg-gray-50 rounded-lg flex items-center justify-between';
            const info = document.createElement('div');
            info.className = 'text-sm';
            const business = t.business || {};
            const bank = t.bank || {};
            const docs = t.documents || {};

            const docsHtml = Object.entries(docs)
                .map(([key, path]) => {
                    if (!path) return '';
                    const strPath = String(path);
                    const url = (strPath.startsWith('http://') || strPath.startsWith('https://'))
                        ? strPath
                        : `/storage/${strPath}`;
                    const label = key.replace(/_/g, ' ');
                    const fileName = strPath.split('/').pop();
                    return `<div class="text-xs text-gray-600 mt-1">
                        <span class="font-medium text-gray-700">${label}:</span>
                        <a href="${url}" target="_blank" class="text-indigo-600 underline">${fileName}</a>
                    </div>`;
                })
                .filter(Boolean)
                .join('');

            const docsSection = docsHtml ? `<div class="mt-2">${docsHtml}</div>` : '';

            info.innerHTML = `
                <p class="text-gray-800 font-semibold">${t.name ?? 'تاجر'}</p>
                <p class="text-gray-500">${t.contact_email ?? ''} · ${t.contact_phone ?? ''}</p>
                <div class="mt-2 text-xs text-gray-700">
                    <p><span class="font-medium text-gray-800">الشركة:</span> ${t.company_name ?? '-'}</p>
                    <p><span class="font-medium text-gray-800">الشخص المسؤول:</span> ${business.contact_person ?? '-'}</p>
                    <p><span class="font-medium text-gray-800">العنوان:</span> ${business.business_address ?? '-'}</p>
                    <p><span class="font-medium text-gray-800">رقم السجل:</span> ${business.registration_number ?? '-'}</p>
                    <p><span class="font-medium text-gray-800">الرقم الضريبي:</span> ${business.tax_id ?? '-'}</p>
                    <p><span class="font-medium text-gray-800">اسم البنك:</span> ${bank.bank_name ?? '-'}</p>
                    <p><span class="font-medium text-gray-800">حامل الحساب:</span> ${bank.account_holder ?? '-'}</p>
                    <p><span class="font-medium text-gray-800">رقم الحساب:</span> ${bank.account_number ?? '-'}</p>
                    <p><span class="font-medium text-gray-800">IBAN:</span> ${bank.iban ?? '-'}</p>
                </div>
                ${docsSection}
            `;
            const actions = document.createElement('div');
            actions.className = 'flex gap-2';
            const btnApprove = document.createElement('button');
            btnApprove.className = 'bg-green-600 text-white px-3 py-1 rounded';
            btnApprove.textContent = 'موافقة';
            btnApprove.onclick = () => actionTrader(t.id, 'approve');
            const btnReject = document.createElement('button');
            btnReject.className = 'bg-red-600 text-white px-3 py-1 rounded';
            btnReject.textContent = 'رفض';
            btnReject.onclick = () => actionTrader(t.id, 'reject', prompt('سبب الرفض؟') || 'غير محدد');
            const btnInfo = document.createElement('button');
            btnInfo.className = 'bg-yellow-500 text-white px-3 py-1 rounded';
            btnInfo.textContent = 'طلب معلومات';
            btnInfo.onclick = () => actionTrader(t.id, 'request-info', prompt('الرسالة؟') || 'يرجى تزويدنا بالمعلومات اللازمة');
            actions.appendChild(btnApprove);
            actions.appendChild(btnReject);
            actions.appendChild(btnInfo);
            row.appendChild(info);
            row.appendChild(actions);
            list.appendChild(row);
        });
    } catch (e) {}
}
async function actionTrader(id, action, payload) {
    let url = `/api/support/traders/${id}/${action}`;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const opts = { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' } };
    if (csrf) opts.headers['X-CSRF-TOKEN'] = csrf;
    if (action === 'reject') opts.body = JSON.stringify({ reason: payload || 'غير محدد' });
    if (action === 'request-info') opts.body = JSON.stringify({ message: payload || '' });
    try {
        const res = await fetch(url, opts);
        await res.json();
        await loadPendingTraders();
    } catch (e) {}
}
async function loadPendingProducts() {
    try {
        const res = await fetch('/api/support/trader-products/pending');
        const data = await res.json();
        const list = document.getElementById('pending-products');
        const count = document.getElementById('pending-products-count');
        count.textContent = (data.products?.data?.length ?? 0) + '';
        list.innerHTML = '';
        (data.products?.data ?? []).forEach(p => {
            const row = document.createElement('div');
            row.className = 'p-3 bg-gray-50 rounded-lg flex items-center justify-between';
            const info = document.createElement('div');
            info.className = 'text-sm';
            info.innerHTML = `<p class="text-gray-800 font-semibold">${p.name ?? 'منتج'}</p><p class="text-gray-500">السعر: ${p.price ?? 0} · الكمية: ${p.stock_quantity ?? 0}</p>`;
            const actions = document.createElement('div');
            actions.className = 'flex gap-2';
            const btnApprove = document.createElement('button');
            btnApprove.className = 'bg-green-600 text-white px-3 py-1 rounded';
            btnApprove.textContent = 'موافقة';
            btnApprove.onclick = () => actionProduct(p.id, 'approve');
            const btnReject = document.createElement('button');
            btnReject.className = 'bg-red-600 text-white px-3 py-1 rounded';
            btnReject.textContent = 'رفض';
            btnReject.onclick = () => actionProduct(p.id, 'reject', prompt('سبب الرفض؟') || 'غير محدد');
            const btnChanges = document.createElement('button');
            btnChanges.className = 'bg-yellow-500 text-white px-3 py-1 rounded';
            btnChanges.textContent = 'طلب تعديلات';
            btnChanges.onclick = () => actionProduct(p.id, 'request-changes', prompt('الرسالة؟') || 'يرجى إجراء التعديلات المطلوبة');
            actions.appendChild(btnApprove);
            actions.appendChild(btnReject);
            actions.appendChild(btnChanges);
            row.appendChild(info);
            row.appendChild(actions);
            list.appendChild(row);
        });
    } catch (e) {}
}
async function actionProduct(id, action, payload) {
    let url = `/api/support/trader-products/${id}/${action}`;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const opts = { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' } };
    if (csrf) opts.headers['X-CSRF-TOKEN'] = csrf;
    if (action === 'reject') opts.body = JSON.stringify({ reason: payload || 'غير محدد' });
    if (action === 'request-changes') opts.body = JSON.stringify({ message: payload || '' });
    try {
        const res = await fetch(url, opts);
        await res.json();
        await loadPendingProducts();
    } catch (e) {}
}
document.addEventListener('DOMContentLoaded', () => {
    loadPendingTraders();
    loadPendingProducts();
});
</script>
@endpush
