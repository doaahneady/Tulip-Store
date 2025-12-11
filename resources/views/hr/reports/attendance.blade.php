<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الحضور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
            border-bottom: 3px solid #3b82f6;
        }

        .page-header h1 {
            color: #3b82f6;
            font-weight: bold;
        }

        .filter-section {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border-top: 4px solid #3b82f6;
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: bold;
            color: #1f2937;
            margin: 0.5rem 0;
        }

        .stat-card p {
            color: #6b7280;
            margin: 0;
        }

        .report-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .report-table thead th {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 1rem;
            font-weight: 600;
            text-align: right;
            border: none;
        }

        .report-table thead th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .report-table thead th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .report-table tbody tr {
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .report-table tbody tr:hover {
            background: #f3f4f6;
            transform: scale(1.01);
        }

        .report-table tbody td {
            padding: 1rem;
            border: none;
        }

        .report-table tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .report-table tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
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

        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-chart-bar"></i> تقرير الحضور</h1>
                <div>
                    <a href="{{ route('hr.reports') }}" class="btn btn-outline-secondary btn-custom me-2">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                    <button class="btn btn-success btn-custom" onclick="window.print()">
                        <i class="fas fa-print"></i> طباعة
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h3 class="mb-3"><i class="fas fa-filter"></i> تصفية التقرير</h3>
            <form method="GET" action="{{ route('hr.reports.attendance') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-custom w-100">
                            <i class="fas fa-search"></i> عرض التقرير
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-calendar-day" style="font-size: 2rem; color: #3b82f6;"></i>
                <h3>{{ $report->count() }}</h3>
                <p>إجمالي الموظفين</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle" style="font-size: 2rem; color: #10b981;"></i>
                <h3>{{ $report->sum(function($records) { return $records->where('status', 'present')->count(); }) }}</h3>
                <p>أيام الحضور</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-times-circle" style="font-size: 2rem; color: #ef4444;"></i>
                <h3>{{ $report->sum(function($records) { return $records->where('status', 'absent')->count(); }) }}</h3>
                <p>أيام الغياب</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock" style="font-size: 2rem; color: #f59e0b;"></i>
                <h3>{{ $report->sum(function($records) { return $records->where('status', 'late')->count(); }) }}</h3>
                <p>أيام التأخير</p>
            </div>
        </div>

        <!-- Report Table -->
        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>القسم</th>
                        <th>أيام الحضور</th>
                        <th>أيام الغياب</th>
                        <th>أيام التأخير</th>
                        <th>نسبة الحضور</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $employeeId => $records)
                        @php
                            $employee = $records->first()->employee;
                            $totalDays = $records->count();
                            $presentDays = $records->where('status', 'present')->count();
                            $absentDays = $records->where('status', 'absent')->count();
                            $lateDays = $records->where('status', 'late')->count();
                            $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td><strong>{{ $employee->full_name }}</strong></td>
                            <td>{{ $employee->department }}</td>
                            <td><span class="badge bg-success">{{ $presentDays }}</span></td>
                            <td><span class="badge bg-danger">{{ $absentDays }}</span></td>
                            <td><span class="badge bg-warning">{{ $lateDays }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="flex: 1; height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $attendanceRate }}%">
                                            {{ $attendanceRate }}%
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد بيانات للفترة المحددة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Period Info -->
        <div class="mt-4 text-muted text-center">
            <i class="fas fa-calendar"></i> 
            الفترة: من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
