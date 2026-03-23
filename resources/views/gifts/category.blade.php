<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <title>{{ $categoryName }} - هدايا توليب</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'El Messiri', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .category-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .breadcrumb {
            background: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .breadcrumb a {
            color: #2a7080;
            text-decoration: none;
            margin: 0 0.5rem;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .category-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .category-header h1 {
            font-size: 3rem;
            color: #2a7080;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .category-header p {
            font-size: 1.2rem;
            color: #666;
        }

        .gifts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .gift-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
            cursor: pointer;
        }

        .gift-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .gift-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gift-content {
            padding: 1.5rem;
        }

        .gift-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .gift-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .gift-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .gift-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .gift-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2a7080;
        }

        /* .gift-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ffa500;
        } */

        .no-gifts {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .no-gifts i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .no-gifts p {
            font-size: 1.2rem;
            color: #666;
        }

        .back-to-gifts {
            display: inline-block;
            background: linear-gradient(135deg, #2a7080, #1a5060);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 1rem;
            transition: all 0.3s;
        }

        .back-to-gifts:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(42, 112, 128, 0.3);
        }

        @media (max-width: 768px) {
            .category-header h1 {
                font-size: 2rem;
            }

            .gifts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
      @include('components.navbar')

    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('gifts.index') }}"><i class="fas fa-gift"></i> الهدايا</a>
            <i class="fas fa-chevron-left"></i>
            <span>{{ $categoryName }}</span>
        </div>

        <!-- Category Header -->
        <div class="category-header">
            <h1>
                <i class="fas fa-heart"></i>
                هدايا {{ $categoryName }}
            </h1>
            <p>اختر من مجموعتنا المميزة من هدايا {{ $categoryName }}</p>
        </div>

        <!-- Gifts Grid -->
        @if($gifts->count() > 0)
            <div class="gifts-grid">
                @foreach($gifts as $gift)
                    <div class="gift-card" onclick="window.location.href='{{ route('gifts.show', $gift) }}'">
                        <img src="{{ $gift->main_image }}" alt="{{ $gift->name }}" class="gift-image">
                        <div class="gift-content">
                            <span class="gift-category {{ $gift->category_color }}">
                                {{ $gift->category_name }}
                            </span>
                            <h3 class="gift-name">{{ $gift->name }}</h3>
                            <p class="gift-description">{{ Str::limit($gift->description, 100) }}</p>
                            <div class="gift-footer">
                                <span class="gift-price">@money($gift->price)</span>
                                <!-- <div class="gift-rating">
                                    <i class="fas fa-star"></i>
                                    <span>{{ number_format($gift->rating, 1) }}</span>
                                </div> -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $gifts->links() }}
            </div>
        @else
            <div class="no-gifts">
                <i class="fas fa-gift"></i>
                <p>لا توجد هدايا متاحة حالياً في فئة {{ $categoryName }}</p>
                <a href="{{ route('gifts.index') }}" class="back-to-gifts">
                    <i class="fas fa-arrow-right"></i> العودة لجميع الهدايا
                </a>
            </div>
        @endif
    </div>
</body>
</html>