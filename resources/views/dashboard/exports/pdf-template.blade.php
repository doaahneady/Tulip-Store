<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{ $metadata['title'] ?? 'Export Report' }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: "El Messiri", sans-serif; color: #111827; margin: 24px; }
        .header { margin-bottom: 16px; }
        .title { font-size: 20px; font-weight: 700; margin: 0 0 4px 0; }
        .subtitle { font-size: 12px; color: #6B7280; margin: 0 0 8px 0; }
        .meta { font-size: 11px; color: #6B7280; margin: 0 0 16px 0; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #F9FAFB; border-bottom: 1px solid #E5E7EB; padding: 8px 10px; font-size: 12px; text-align: right; color: #374151; }
        tbody td { border-bottom: 1px solid #F3F4F6; padding: 8px 10px; font-size: 12px; text-align: right; color: #111827; }
        tbody tr:nth-child(even) { background: #FAFAFA; }
        .count { font-size: 11px; color: #374151; margin-top: 4px; }
    </style>
</head>
<body>
    @php
        $columnsKeys = array_keys($columns ?? []);
        $isKey = fn($name) => in_array($name, $columnsKeys, true);
        $sumNumeric = function($key) use ($data) {
            $total = 0;
            foreach ($data as $row) {
                $value = data_get($row, $key);
                if (is_numeric($value)) { $total += $value; }
            }
            return $total;
        };
        $countBy = function($key) use ($data) {
            $map = [];
            foreach ($data as $row) {
                $value = data_get($row, $key);
                $label = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value ?? 'غير محدد');
                $map[$label] = ($map[$label] ?? 0) + 1;
            }
            ksort($map);
            return $map;
        };
    @endphp

    <div class="header">
        <h1 class="title">{{ $metadata['title'] ?? 'Export Report' }}</h1>
        @if(!empty($metadata['subtitle']))
            <p class="subtitle">{{ $metadata['subtitle'] }}</p>
        @endif
        <p class="meta">
            {{ $metadata['company_name'] ?? config('app.name', 'Tulip Store') }}
            • {{ $metadata['generated_at'] ?? now()->format('Y-m-d H:i:s') }}
            @if(!empty($metadata['generated_by'])) • بواسطة: {{ $metadata['generated_by'] }} @endif
        </p>
        <div class="count">عدد السجلات: {{ $data->count() }}</div>
        @if(!empty($metadata['date_from']) || !empty($metadata['date_to']))
            <div class="meta">
                نطاق التاريخ: {{ $metadata['date_from'] ?? '—' }} إلى {{ $metadata['date_to'] ?? '—' }}
            </div>
        @endif
        @if(!empty($metadata['filters']) && is_array($metadata['filters']))
            <div class="meta">
                عوامل التصفية:
                @foreach($metadata['filters'] as $k => $v)
                    @if($v !== null && $v !== '')
                        <span>{{ $k }}: {{ is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v }}</span>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <div style="margin: 12px 0;">
        @if($isKey('amount'))
            <div class="count">إجمالي المبالغ: ${{ number_format($sumNumeric('amount'), 2) }}</div>
        @endif
        @if($isKey('price'))
            <div class="count">إجمالي الأسعار: ${{ number_format($sumNumeric('price'), 2) }}</div>
        @endif
        @if($isKey('salary'))
            <div class="count">إجمالي الرواتب: ${{ number_format($sumNumeric('salary'), 2) }}</div>
        @endif
    </div>

    @if($isKey('status'))
        @php $statusCounts = $countBy('status'); @endphp
        <table style="margin-bottom: 16px;">
            <thead>
                <tr><th>الحالة</th><th>عدد السجلات</th></tr>
            </thead>
            <tbody>
                @foreach($statusCounts as $status => $cnt)
                    <tr><td>{{ $status }}</td><td>{{ $cnt }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($isKey('action'))
        @php $actionCounts = $countBy('action'); @endphp
        <table style="margin-bottom: 16px;">
            <thead>
                <tr><th>الإجراء</th><th>عدد السجلات</th></tr>
            </thead>
            <tbody>
                @foreach($actionCounts as $action => $cnt)
                    <tr><td>{{ $action }}</td><td>{{ $cnt }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($isKey('type'))
        @php $typeCounts = $countBy('type'); @endphp
        <table style="margin-bottom: 16px;">
            <thead>
                <tr><th>النوع</th><th>عدد السجلات</th></tr>
            </thead>
            <tbody>
                @foreach($typeCounts as $type => $cnt)
                    <tr><td>{{ $type }}</td><td>{{ $cnt }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table>
        <thead>
            <tr>
                @foreach($columns as $key => $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($columns as $key => $label)
                        @php
                            $value = data_get($row, $key);
                            if (is_array($value)) { $value = json_encode($value, JSON_UNESCAPED_UNICODE); }
                        @endphp
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
