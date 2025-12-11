<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقييم الأداء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
            border-bottom: 3px solid #8b5cf6;
        }

        .page-header h1 {
            color: #8b5cf6;
            font-weight: bold;
        }

        .review-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .rating-stars {
            color: #fbbf24;
            font-size: 1.5rem;
        }

        .score-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .score-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .score-item .label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .score-item .score {
            font-size: 1.5rem;
            font-weight: bold;
            color: #8b5cf6;
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
                <h1><i class="fas fa-chart-line"></i> تقييم الأداء</h1>
                <div>
                    <a href="{{ route('hr.dashboard') }}" class="btn btn-outline-secondary btn-custom me-2">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                    <a href="{{ route('hr.performance.create') }}" class="btn btn-primary btn-custom">
                        <i class="fas fa-plus"></i> إضافة تقييم جديد
                    </a>
                </div>
            </div>
        </div>

        @forelse($reviews as $review)
        <div class="review-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h3 class="mb-1">{{ $review->employee->full_name }}</h3>
                    <span class="text-muted">{{ $review->employee->department }} - {{ $review->review_period }}</span>
                </div>
                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->overall_rating)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
            </div>

            <div class="score-grid">
                <div class="score-item">
                    <div class="label">الأداء</div>
                    <div class="score">{{ $review->performance_score }}%</div>
                </div>
                <div class="score-item">
                    <div class="label">الحضور</div>
                    <div class="score">{{ $review->attendance_score }}%</div>
                </div>
                <div class="score-item">
                    <div class="label">الجودة</div>
                    <div class="score">{{ $review->quality_score }}%</div>
                </div>
                <div class="score-item">
                    <div class="label">العمل الجماعي</div>
                    <div class="score">{{ $review->teamwork_score }}%</div>
                </div>
            </div>

            @if($review->comments)
            <div class="mt-3">
                <strong>التعليقات:</strong>
                <p class="text-muted mb-0">{{ $review->comments }}</p>
            </div>
            @endif

            <div class="mt-3 text-muted">
                <i class="fas fa-user"></i> المقيّم: {{ $review->reviewer->name }}
                <span class="mx-2">|</span>
                <i class="fas fa-calendar"></i> {{ $review->review_date->format('Y-m-d') }}
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
            <p class="text-muted">لا توجد تقييمات</p>
        </div>
        @endforelse

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
