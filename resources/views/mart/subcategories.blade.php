<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - توليب مارت</title>
    <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
        <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">

    <link rel="stylesheet" href="/css/store.css?v=999&fix=store&t={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'El Messiri', sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            padding: 2rem;
            text-align: center;
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .header p {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        
        .breadcrumb {
            max-width: 1280px;
            margin: 1.5rem auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #6b7280;
        }
        
        .breadcrumb a {
            color: #059669;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .breadcrumb a:hover {
            color: #047857;
        }
        
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .subcategories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .subcategory-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .subcategory-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        
        .subcategory-icon {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            padding: 3rem;
            text-align: center;
            font-size: 4rem;
            color: white;
        }
        
        .subcategory-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f3f4f6;
        }
        
        .subcategory-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .subcategory-info {
            padding: 1.5rem;
            text-align: center;
        }
        
        .subcategory-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .subcategory-count {
            font-size: 0.9rem;
            color: #6b7280;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 1.5rem 1rem;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .header p {
                font-size: 0.9rem;
            }
            
            .breadcrumb {
                padding: 0 1rem;
                font-size: 0.8rem;
            }
            
            .container {
                padding: 1rem;
            }
            
            .subcategories-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .subcategory-icon {
                padding: 2rem;
                font-size: 3rem;
            }
            
            .subcategory-info {
                padding: 1rem;
            }
            
            .subcategory-name {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="header">
            <h1>{{ $category->name }}</h1>
            @if($category->description)
                <p>{{ $category->description }}</p>
            @endif
        </div>
    
    <div class="breadcrumb">
        <a href="{{ route('mart.index') }}"><i class="fas fa-home"></i> الرئيسية</a>
        <i class="fas fa-chevron-left"></i>
        <span>{{ $category->name }}</span>
    </div>
    
    <div class="container">
        @if($subcategories->count() > 0)
            <div class="subcategories-grid">
                @foreach($subcategories as $subcategory)
                    <a href="{{ route('mart.subcategory', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}" class="subcategory-card">
                        @if($subcategory->image_url || $subcategory->image)
                            <div class="subcategory-image">
                                <img src="{{ $subcategory->image_url ?? $subcategory->image }}" alt="{{ $subcategory->name }}" onerror="this.parentElement.innerHTML='<div class=\'subcategory-icon\'><i class=\'fas fa-box\'></i></div>'">
                            </div>
                        @else
                            <div class="subcategory-icon">
                                <i class="fas fa-box"></i>
                            </div>
                        @endif
                        <div class="subcategory-info">
                            <div class="subcategory-name">{{ $subcategory->name }}</div>
                            <div class="subcategory-count">
                                {{ $subcategory->products_count ?? 0 }} منتج
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h2>لا توجد أقسام فرعية</h2>
                <p>لم يتم إضافة أقسام فرعية لهذا القسم بعد</p>
            </div>
        @endif
    </div>
</body>
</html>
