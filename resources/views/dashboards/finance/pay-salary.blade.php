@extends('dashboards.layouts.app')
@section('content')
@php $title = 'صرف راتب'; $subtitle = 'تأكيد الدفع وتوقيع الموظف'; @endphp

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-4 rounded-xl border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">الموظف</div>
            <div class="font-semibold text-gray-900">{{ $employee?->user?->name ?? '#' }}</div>
            <div class="text-xs text-gray-500">{{ $employee?->user?->email ?? '' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">الفترة</div>
            <div class="font-semibold text-gray-900">{{ $payPeriod ?: '-' }}</div>
        </div>
        <div class="p-4 rounded-xl border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">المبلغ</div>
            <div class="font-semibold text-gray-900">{{ number_format((float) ($transaction->amount ?? 0), 2) }} {{ $transaction->currency ?? 'USD' }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('dashboard.finance.payroll.mark-paid', $transaction) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        <div>
            <label class="block text-sm text-gray-700 mb-1">تاريخ الدفع</label>
            <input type="date" name="paid_date" value="{{ old('paid_date', now()->toDateString()) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200" required>
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">اسم المستلم</label>
            <input type="text" name="signed_name" value="{{ old('signed_name', $employee?->user?->name) }}" class="w-full px-4 py-2 rounded-xl border border-gray-200" placeholder="اسم الموظف">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm text-gray-700 mb-2">التوقيع</label>
            <div class="rounded-xl border border-gray-200 p-3">
                <canvas id="sigCanvas" class="w-full" height="160"></canvas>
                <input type="hidden" name="signature_data" id="signature_data">
                <div class="flex items-center justify-between mt-3">
                    <button type="button" id="clearSig" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">مسح</button>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('dashboard.finance.payroll') }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">رجوع</a>
                        <button type="submit" id="submitBtn" class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">تأكيد الدفع</button>
                    </div>
                </div>
            </div>
            <div class="text-xs text-gray-500 mt-2">ارسم توقيع الموظف ثم اضغط تأكيد الدفع.</div>
        </div>
    </form>
</div>

<script>
    (function () {
        const canvas = document.getElementById('sigCanvas');
        const clearBtn = document.getElementById('clearSig');
        const dataInput = document.getElementById('signature_data');
        const submitBtn = document.getElementById('submitBtn');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            const ratio = window.devicePixelRatio || 1;
            const rect = canvas.getBoundingClientRect();
            canvas.width = Math.floor(rect.width * ratio);
            canvas.height = Math.floor(160 * ratio);
            canvas.style.height = '160px';
            ctx.scale(ratio, ratio);
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#111827';
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        let drawing = false;
        let hasStroke = false;

        function pos(e) {
            const rect = canvas.getBoundingClientRect();
            const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
            const y = (e.touches ? e.touches[0].clientY : e.clientY) - rect.top;
            return { x, y };
        }

        function start(e) {
            drawing = true;
            hasStroke = true;
            const p = pos(e);
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            e.preventDefault();
        }

        function move(e) {
            if (!drawing) return;
            const p = pos(e);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
            e.preventDefault();
        }

        function end(e) {
            drawing = false;
            e.preventDefault();
        }

        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('mousemove', move);
        window.addEventListener('mouseup', end);
        canvas.addEventListener('touchstart', start, { passive: false });
        canvas.addEventListener('touchmove', move, { passive: false });
        canvas.addEventListener('touchend', end, { passive: false });

        clearBtn.addEventListener('click', function () {
            const rect = canvas.getBoundingClientRect();
            ctx.clearRect(0, 0, rect.width, rect.height);
            hasStroke = false;
            dataInput.value = '';
        });

        submitBtn.addEventListener('click', function () {
            if (!hasStroke) {
                dataInput.value = '';
                return;
            }
            dataInput.value = canvas.toDataURL('image/png');
        });
    })();
</script>
@endsection

