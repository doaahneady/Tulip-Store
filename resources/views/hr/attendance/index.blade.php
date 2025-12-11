<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الحضور والانصراف</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container-custom {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #10b981;
        }

        .page-header h1 {
            color: #10b981;
            font-weight: bold;
        }

        .date-display {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 2rem;
        }

        .date-display h2 {
            margin: 0;
            font-size: 2rem;
        }

        .attendance-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .attendance-table thead th {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem;
            font-weight: 600;
            text-align: right;
            border: none;
        }

        .attendance-table thead th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .attendance-table thead th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .attendance-table tbody tr {
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .attendance-table tbody tr:hover {
            background: #f3f4f6;
            transform: scale(1.01);
        }

        .attendance-table tbody td {
            padding: 1rem;
            border: none;
        }

        .attendance-table tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .attendance-table tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-custom {
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-calendar-check"></i> الحضور والانصراف</h1>
                <a href="{{ route('hr.dashboard') }}" class="btn btn-outline-secondary btn-custom">
                    <i class="fas fa-arrow-right"></i> العودة
                </a>
            </div>
        </div>

        <div class="date-display">
            <h2><i class="fas fa-calendar-day"></i> {{ $today->format('Y-m-d') }}</h2>
            <p class="mb-0">{{ $today->translatedFormat('l') }}</p>
        </div>

        <div class="table-responsive">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>القسم</th>
                        <th>وقت الحضور</th>
                        <th>وقت الانصراف</th>
                        <th>ساعات العمل</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendance as $record)
                    <tr>
                        <td><strong>{{ $record->employee->full_name }}</strong></td>
                        <td>{{ $record->employee->department }}</td>
                        <td>{{ $record->check_in ?? '-' }}</td>
                        <td>{{ $record->check_out ?? '-' }}</td>
                        <td>{{ $record->work_hours ? floor($record->work_hours / 60) . ' ساعة' : '-' }}</td>
                        <td>
                            @switch($record->status)
                                @case('present')
                                    <span class="status-badge bg-success">حاضر</span>
                                    @break
                                @case('late')
                                    <span class="status-badge bg-warning">متأخر</span>
                                    @break
                                @case('absent')
                                    <span class="status-badge bg-danger">غائب</span>
                                    @break
                                @case('half_day')
                                    <span class="status-badge bg-info">نصف يوم</span>
                                    @break
                                @case('on_leave')
                                    <span class="status-badge bg-secondary">في إجازة</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">لا توجد سجلات حضور لهذا اليوم</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
