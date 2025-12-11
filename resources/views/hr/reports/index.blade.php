<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير</title>
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

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .report-card {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border-top: 4px solid #3b82f6;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .report-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: white;
        }

        .report-card h3 {
            color: #1f2937;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .report-card p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .btn-custom {
            padding: 0.8rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
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
                <h1><i class="fas fa-file-alt"></i> التقارير</h1>
                <a href="{{ route('hr.dashboard') }}" class="btn btn-outline-secondary btn-custom" style="width: auto;">
                    <i class="fas fa-arrow-right"></i> العودة
                </a>
            </div>
        </div>

        <div class="reports-grid">
            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>تقرير الحضور</h3>
                <p>تقرير شامل عن حضور وغياب الموظفين</p>
                <a href="{{ route('hr.reports.attendance') }}" class="btn btn-primary btn-custom">
                    <i class="fas fa-eye"></i> عرض التقرير
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3>تقرير الرواتب</h3>
                <p>ملخص الرواتب والمدفوعات الشهرية</p>
                <button class="btn btn-primary btn-custom" onclick="alert('قريباً')">
                    <i class="fas fa-eye"></i> عرض التقرير
                </button>
            </div>

            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <h3>تقرير الإجازات</h3>
                <p>إحصائيات الإجازات والأرصدة المتبقية</p>
                <button class="btn btn-primary btn-custom" onclick="alert('قريباً')">
                    <i class="fas fa-eye"></i> عرض التقرير
                </button>
            </div>

            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>تقرير الأداء</h3>
                <p>تحليل تقييمات أداء الموظفين</p>
                <button class="btn btn-primary btn-custom" onclick="alert('قريباً')">
                    <i class="fas fa-eye"></i> عرض التقرير
                </button>
            </div>

            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>تقرير التدريب</h3>
                <p>إحصائيات البرامج التدريبية والمشاركين</p>
                <button class="btn btn-primary btn-custom" onclick="alert('قريباً')">
                    <i class="fas fa-eye"></i> عرض التقرير
                </button>
            </div>

            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>تقرير الموظفين</h3>
                <p>بيانات شاملة عن جميع الموظفين</p>
                <button class="btn btn-primary btn-custom" onclick="alert('قريباً')">
                    <i class="fas fa-eye"></i> عرض التقرير
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
