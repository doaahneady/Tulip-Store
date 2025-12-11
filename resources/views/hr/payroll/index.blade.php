<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الرواتب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
            border-bottom: 3px solid #f59e0b;
        }

        .page-header h1 {
            color: #f59e0b;
            font-weight: bold;
        }

        .month-selector {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .payroll-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-right: 5px solid #f59e0b;
            transition: all 0.3s ease;
        }

        .payroll-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .payroll-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .salary-breakdown {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
        }

        .salary-item {
            text-align: center;
        }

        .salary-item .label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.3rem;
        }

        .salary-item .value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #1f2937;
        }

        .net-salary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .net-salary h3 {
            margin: 0;
            font-size: 2rem;
        }

        .btn-custom {
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-money-bill-wave"></i> إدارة الرواتب</h1>
                <div>
                    <a href="{{ route('hr.dashboard') }}" class="btn btn-outline-secondary btn-custom me-2">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                    <form action="{{ route('hr.payroll.generate') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-custom">
                            <i class="fas fa-plus"></i> إنشاء كشف رواتب
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="month-selector">
            <h3 class="mb-0"><i class="fas fa-calendar"></i> شهر: {{ $currentMonth }}</h3>
        </div>

        @forelse($payrolls as $payroll)
        <div class="payroll-card">
            <div class="payroll-header">
                <div>
                    <h3 class="mb-1">{{ $payroll->employee->full_name }}</h3>
                    <span class="text-muted">{{ $payroll->employee->employee_code }} - {{ $payroll->employee->department }}</span>
                </div>
                <div>
                    @switch($payroll->status)
                        @case('draft')
                            <span class="badge bg-secondary">مسودة</span>
                            @break
                        @case('processed')
                            <span class="badge bg-info">معالج</span>
                            @break
                        @case('paid')
                            <span class="badge bg-success">مدفوع</span>
                            @break
                    @endswitch
                </div>
            </div>

            <div class="salary-breakdown">
                <div class="salary-item">
                    <div class="label">الراتب الأساسي</div>
                    <div class="value">{{ number_format($payroll->basic_salary, 2) }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">البدلات</div>
                    <div class="value text-success">+{{ number_format($payroll->allowances, 2) }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">المكافآت</div>
                    <div class="value text-success">+{{ number_format($payroll->bonuses, 2) }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">العمل الإضافي</div>
                    <div class="value text-success">+{{ number_format($payroll->overtime_pay, 2) }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">الخصومات</div>
                    <div class="value text-danger">-{{ number_format($payroll->deductions, 2) }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">الضرائب</div>
                    <div class="value text-danger">-{{ number_format($payroll->tax, 2) }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">التأمين</div>
                    <div class="value text-danger">-{{ number_format($payroll->insurance, 2) }}</div>
                </div>
            </div>

            <div class="net-salary">
                <p class="mb-1">صافي الراتب</p>
                <h3>{{ number_format($payroll->net_salary, 2) }} ريال</h3>
            </div>

            @if($payroll->status === 'draft')
            <form action="{{ route('hr.payroll.process', $payroll) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-custom">
                    <i class="fas fa-check"></i> معالجة الراتب
                </button>
            </form>
            @endif

            @if($payroll->payment_date)
            <div class="text-muted mt-2">
                <i class="fas fa-calendar-check"></i> تاريخ الدفع: {{ $payroll->payment_date->format('Y-m-d') }}
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
            <p class="text-muted">لا توجد رواتب لهذا الشهر</p>
        </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
