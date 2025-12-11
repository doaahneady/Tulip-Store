<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار الاتصال بقاعدة البيانات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .test-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .test-card h3 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #667eea;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }

        .status-success {
            background: #10b981;
            color: white;
        }

        .status-error {
            background: #ef4444;
            color: white;
        }

        .data-table {
            width: 100%;
            margin-top: 1rem;
        }

        .data-table th {
            background: #f3f4f6;
            padding: 0.75rem;
            font-weight: 600;
        }

        .data-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-card">
            <h1 class="text-center mb-4">
                <i class="fas fa-database"></i> اختبار الاتصال بقاعدة البيانات
            </h1>
            <p class="text-center text-muted">هذه الصفحة تختبر الاتصال بجميع جداول نظام الموارد البشرية</p>
        </div>

        @php
            $tests = [];
            
            // Test Employees
            try {
                $employeesCount = \App\Models\Employee::count();
                $tests['employees'] = [
                    'status' => 'success',
                    'count' => $employeesCount,
                    'sample' => \App\Models\Employee::latest()->take(3)->get()
                ];
            } catch (\Exception $e) {
                $tests['employees'] = [
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }

            // Test Attendance
            try {
                $attendanceCount = \App\Models\Attendance::count();
                $tests['attendance'] = [
                    'status' => 'success',
                    'count' => $attendanceCount,
                    'sample' => \App\Models\Attendance::with('employee')->latest()->take(3)->get()
                ];
            } catch (\Exception $e) {
                $tests['attendance'] = [
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }

            // Test Leave Requests
            try {
                $leavesCount = \App\Models\LeaveRequest::count();
                $tests['leaves'] = [
                    'status' => 'success',
                    'count' => $leavesCount,
                    'sample' => \App\Models\LeaveRequest::with('employee')->latest()->take(3)->get()
                ];
            } catch (\Exception $e) {
                $tests['leaves'] = [
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }

            // Test Payroll
            try {
                $payrollCount = \App\Models\Payroll::count();
                $tests['payroll'] = [
                    'status' => 'success',
                    'count' => $payrollCount,
                    'sample' => \App\Models\Payroll::with('employee')->latest()->take(3)->get()
                ];
            } catch (\Exception $e) {
                $tests['payroll'] = [
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }

            // Test Performance Reviews
            try {
                $reviewsCount = \App\Models\PerformanceReview::count();
                $tests['reviews'] = [
                    'status' => 'success',
                    'count' => $reviewsCount,
                    'sample' => \App\Models\PerformanceReview::with(['employee', 'reviewer'])->latest()->take(3)->get()
                ];
            } catch (\Exception $e) {
                $tests['reviews'] = [
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }

            // Test Training Programs
            try {
                $trainingCount = \App\Models\TrainingProgram::count();
                $tests['training'] = [
                    'status' => 'success',
                    'count' => $trainingCount,
                    'sample' => \App\Models\TrainingProgram::latest()->take(3)->get()
                ];
            } catch (\Exception $e) {
                $tests['training'] = [
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }
        @endphp

        <!-- Employees Test -->
        <div class="test-card">
            <h3><i class="fas fa-users"></i> جدول الموظفين (employees)</h3>
            @if($tests['employees']['status'] === 'success')
                <span class="status-badge status-success">
                    <i class="fas fa-check-circle"></i> متصل - {{ $tests['employees']['count'] }} سجل
                </span>
                @if($tests['employees']['count'] > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الكود</th>
                                <th>الاسم</th>
                                <th>القسم</th>
                                <th>المنصب</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tests['employees']['sample'] as $emp)
                            <tr>
                                <td>{{ $emp->employee_code }}</td>
                                <td>{{ $emp->full_name }}</td>
                                <td>{{ $emp->department }}</td>
                                <td>{{ $emp->position }}</td>
                                <td><span class="badge bg-success">{{ $emp->status }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                <span class="status-badge status-error">
                    <i class="fas fa-times-circle"></i> خطأ: {{ $tests['employees']['error'] }}
                </span>
            @endif
        </div>

        <!-- Attendance Test -->
        <div class="test-card">
            <h3><i class="fas fa-calendar-check"></i> جدول الحضور (attendance)</h3>
            @if($tests['attendance']['status'] === 'success')
                <span class="status-badge status-success">
                    <i class="fas fa-check-circle"></i> متصل - {{ $tests['attendance']['count'] }} سجل
                </span>
                @if($tests['attendance']['count'] > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>التاريخ</th>
                                <th>الحضور</th>
                                <th>الانصراف</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tests['attendance']['sample'] as $att)
                            <tr>
                                <td>{{ $att->employee->full_name }}</td>
                                <td>{{ $att->date->format('Y-m-d') }}</td>
                                <td>{{ $att->check_in }}</td>
                                <td>{{ $att->check_out }}</td>
                                <td><span class="badge bg-info">{{ $att->status }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                <span class="status-badge status-error">
                    <i class="fas fa-times-circle"></i> خطأ: {{ $tests['attendance']['error'] }}
                </span>
            @endif
        </div>

        <!-- Leave Requests Test -->
        <div class="test-card">
            <h3><i class="fas fa-umbrella-beach"></i> جدول الإجازات (leave_requests)</h3>
            @if($tests['leaves']['status'] === 'success')
                <span class="status-badge status-success">
                    <i class="fas fa-check-circle"></i> متصل - {{ $tests['leaves']['count'] }} سجل
                </span>
                @if($tests['leaves']['count'] > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>النوع</th>
                                <th>من</th>
                                <th>إلى</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tests['leaves']['sample'] as $leave)
                            <tr>
                                <td>{{ $leave->employee->full_name }}</td>
                                <td>{{ $leave->leave_type }}</td>
                                <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                                <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                                <td><span class="badge bg-warning">{{ $leave->status }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                <span class="status-badge status-error">
                    <i class="fas fa-times-circle"></i> خطأ: {{ $tests['leaves']['error'] }}
                </span>
            @endif
        </div>

        <!-- Payroll Test -->
        <div class="test-card">
            <h3><i class="fas fa-money-bill-wave"></i> جدول الرواتب (payroll)</h3>
            @if($tests['payroll']['status'] === 'success')
                <span class="status-badge status-success">
                    <i class="fas fa-check-circle"></i> متصل - {{ $tests['payroll']['count'] }} سجل
                </span>
                @if($tests['payroll']['count'] > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>الشهر</th>
                                <th>الراتب الأساسي</th>
                                <th>صافي الراتب</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tests['payroll']['sample'] as $pay)
                            <tr>
                                <td>{{ $pay->employee->full_name }}</td>
                                <td>{{ $pay->month }}</td>
                                <td>{{ number_format($pay->basic_salary, 2) }}</td>
                                <td>{{ number_format($pay->net_salary, 2) }}</td>
                                <td><span class="badge bg-secondary">{{ $pay->status }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                <span class="status-badge status-error">
                    <i class="fas fa-times-circle"></i> خطأ: {{ $tests['payroll']['error'] }}
                </span>
            @endif
        </div>

        <!-- Performance Reviews Test -->
        <div class="test-card">
            <h3><i class="fas fa-chart-line"></i> جدول تقييم الأداء (performance_reviews)</h3>
            @if($tests['reviews']['status'] === 'success')
                <span class="status-badge status-success">
                    <i class="fas fa-check-circle"></i> متصل - {{ $tests['reviews']['count'] }} سجل
                </span>
                @if($tests['reviews']['count'] > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>الفترة</th>
                                <th>التقييم الإجمالي</th>
                                <th>المقيّم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tests['reviews']['sample'] as $review)
                            <tr>
                                <td>{{ $review->employee->full_name }}</td>
                                <td>{{ $review->review_period }}</td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->overall_rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </td>
                                <td>{{ $review->reviewer->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                <span class="status-badge status-error">
                    <i class="fas fa-times-circle"></i> خطأ: {{ $tests['reviews']['error'] }}
                </span>
            @endif
        </div>

        <!-- Training Programs Test -->
        <div class="test-card">
            <h3><i class="fas fa-graduation-cap"></i> جدول البرامج التدريبية (training_programs)</h3>
            @if($tests['training']['status'] === 'success')
                <span class="status-badge status-success">
                    <i class="fas fa-check-circle"></i> متصل - {{ $tests['training']['count'] }} سجل
                </span>
                @if($tests['training']['count'] > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>المدرب</th>
                                <th>المدة</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tests['training']['sample'] as $training)
                            <tr>
                                <td>{{ $training->title }}</td>
                                <td>{{ $training->trainer }}</td>
                                <td>{{ $training->duration_hours }} ساعة</td>
                                <td><span class="badge bg-primary">{{ $training->status }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                <span class="status-badge status-error">
                    <i class="fas fa-times-circle"></i> خطأ: {{ $tests['training']['error'] }}
                </span>
            @endif
        </div>

        <!-- Summary -->
        <div class="test-card">
            <h3><i class="fas fa-clipboard-check"></i> الملخص</h3>
            @php
                $successCount = collect($tests)->where('status', 'success')->count();
                $totalTests = count($tests);
            @endphp
            <div class="alert {{ $successCount === $totalTests ? 'alert-success' : 'alert-warning' }}">
                <h4>
                    <i class="fas fa-info-circle"></i> 
                    نجح {{ $successCount }} من {{ $totalTests }} اختبار
                </h4>
                @if($successCount === $totalTests)
                    <p class="mb-0">✅ جميع الجداول متصلة بنجاح وتحتوي على بيانات!</p>
                @else
                    <p class="mb-0">⚠️ بعض الجداول تحتاج إلى فحص. تأكد من تشغيل Migration والـ Seeder.</p>
                @endif
            </div>

            <div class="mt-3">
                <a href="/hr/dashboard" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i> لوحة الموارد البشرية
                </a>
                <a href="/test-permissions" class="btn btn-secondary">
                    <i class="fas fa-user-shield"></i> اختبار الصلاحيات
                </a>
                <a href="/" class="btn btn-outline-secondary">
                    <i class="fas fa-home"></i> الرئيسية
                </a>
            </div>
        </div>
    </div>
</body>
</html>
