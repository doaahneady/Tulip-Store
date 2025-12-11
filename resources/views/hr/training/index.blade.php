<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البرامج التدريبية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
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
            border-bottom: 3px solid #ec4899;
        }

        .page-header h1 {
            color: #ec4899;
            font-weight: bold;
        }

        .training-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border-top: 4px solid #ec4899;
        }

        .training-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .training-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .training-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-item i {
            color: #ec4899;
            width: 20px;
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
                <h1><i class="fas fa-graduation-cap"></i> البرامج التدريبية</h1>
                <div>
                    <a href="{{ route('hr.dashboard') }}" class="btn btn-outline-secondary btn-custom me-2">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                    <a href="{{ route('hr.training.create') }}" class="btn btn-primary btn-custom">
                        <i class="fas fa-plus"></i> إضافة برنامج تدريبي
                    </a>
                </div>
            </div>
        </div>

        @forelse($programs as $program)
        <div class="training-card">
            <div class="training-header">
                <div>
                    <h3 class="mb-1">{{ $program->title }}</h3>
                    @if($program->trainer)
                    <span class="text-muted"><i class="fas fa-user-tie"></i> المدرب: {{ $program->trainer }}</span>
                    @endif
                </div>
                <div>
                    @switch($program->status)
                        @case('scheduled')
                            <span class="badge bg-info">مجدول</span>
                            @break
                        @case('ongoing')
                            <span class="badge bg-warning">جاري</span>
                            @break
                        @case('completed')
                            <span class="badge bg-success">مكتمل</span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger">ملغي</span>
                            @break
                    @endswitch
                </div>
            </div>

            @if($program->description)
            <p class="text-muted mb-3">{{ $program->description }}</p>
            @endif

            <div class="training-details">
                <div class="detail-item">
                    <i class="fas fa-calendar-day"></i>
                    <span>من: {{ $program->start_date->format('Y-m-d') }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-calendar-day"></i>
                    <span>إلى: {{ $program->end_date->format('Y-m-d') }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $program->duration_hours }} ساعة</span>
                </div>
                @if($program->location)
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $program->location }}</span>
                </div>
                @endif
                @if($program->cost > 0)
                <div class="detail-item">
                    <i class="fas fa-dollar-sign"></i>
                    <span>{{ number_format($program->cost, 2) }} ريال</span>
                </div>
                @endif
                @if($program->max_participants)
                <div class="detail-item">
                    <i class="fas fa-users"></i>
                    <span>الحد الأقصى: {{ $program->max_participants }} مشارك</span>
                </div>
                @endif
            </div>

            <div class="mt-3">
                <span class="badge bg-primary">{{ $program->enrollments->count() }} مسجل</span>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
            <p class="text-muted">لا توجد برامج تدريبية</p>
        </div>
        @endforelse

        <div class="mt-4">
            {{ $programs->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
