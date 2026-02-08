<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>توليب مارت - Tulip Mart</title>
    <link rel="stylesheet" href="/css/store.css?v=999&fix=store&t={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('components.navbar')
    <style>
        :root {
            --primary: #059669;
            --primary-light: #10b981;
            --primary-dark: #047857;
            --secondary: #f59e0b;
            --accent: #ef4444;
            --success: #22c55e;
            --warning: #f59e0b;
            --info: #3b82f6;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Tajawal', sans-serif; 
            background: var(--bg); 
            line-height: 1.6;
            color: var(--text);
        }

        /* Modern Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        }
        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .hero-content {
            animation: slideInRight 1s ease-out;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.15);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            color: #fef3c7;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .hero-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 4rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }
        .hero-title .highlight {
            color: var(--secondary);
            text-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
        }
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 3rem;
            line-height: 1.7;
            max-width: 500px;
        }
        .hero-actions {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        .btn {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-family: inherit;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            border: 2px solid transparent;
        }
        .btn-primary {
            background: var(--secondary);
            color: var(--text);
            border-color: var(--secondary);
        }
        .btn-primary:hover {
            background: transparent;
            color: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }
        .btn-outline {
            background: transparent;
            color: #fff;
            border-color: rgba(255,255,255,0.3);
        }
        .btn-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.6);
            transform: translateY(-3px);
        }
        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        .stat-card {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .stat-number {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--secondary);
            display: block;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: rgba(255,255,255,0.8);
            font-size: 0.95rem;
        }

        /* Hero Visual */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            animation: slideInLeft 1s ease-out;
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .visual-container {
            position: relative;
            width: 500px;
            height: 500px;
        }
        .main-circle {
            width: 350px;
            height: 350px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255,255,255,0.2);
            animation: pulse 4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.05); }
        }
        .main-icon {
            font-size: 8rem;
            animation: bounce 3s ease-in-out infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        .floating-element {
            position: absolute;
            font-size: 3rem;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
        }
        .floating-element:nth-child(1) { top: 10%; right: 20%; animation-delay: 0s; }
        .floating-element:nth-child(2) { top: 20%; left: 10%; animation-delay: 1s; }
        .floating-element:nth-child(3) { bottom: 20%; right: 10%; animation-delay: 2s; }
        .floating-element:nth-child(4) { bottom: 10%; left: 20%; animation-delay: 3s; }
        .floating-element:nth-child(5) { top: 50%; right: 5%; animation-delay: 4s; }
        .floating-element:nth-child(6) { top: 50%; left: 5%; animation-delay: 5s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(5deg); }
            66% { transform: translateY(10px) rotate(-5deg); }
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        /* Section Styles */
        .section {
            margin-bottom: 5rem;
        }
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1rem;
            position: relative;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }
        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Categories Grid */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }
        .category-card {
            background: var(--card);
            border-radius: 24px;
            padding: 2.5rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--cat-gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        .category-card:hover::before {
            transform: scaleX(1);
        }
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: var(--cat-color);
        }
        .category-icon {
            font-size: 4.5rem;
            margin-bottom: 1.5rem;
            display: block;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1));
        }
        .category-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        .category-count {
            font-size: 0.9rem;
            color: var(--text-light);
            background: var(--bg);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            display: inline-block;
        }
        /* Daily Prices Feature */
        .daily-prices-feature {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 32px;
            padding: 4rem;
            margin: 4rem 0;
            position: relative;
            overflow: hidden;
        }
        .daily-prices-feature::before {
            content: '📊';
            position: absolute;
            top: -50px;
            right: -50px;
            font-size: 15rem;
            opacity: 0.1;
        }
        .prices-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .prices-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 1rem;
        }
        .prices-subtitle {
            font-size: 1.1rem;
            color: #a16207;
            margin-bottom: 2rem;
        }
        .prices-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3rem;
        }
        .price-category {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem;
            border: 3px solid var(--category-border);
            position: relative;
            overflow: hidden;
        }
        .price-category::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--category-gradient);
        }
        .price-category-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .price-category-icon {
            font-size: 3.5rem;
        }
        .price-category-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--category-color);
        }
        .price-items {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        .price-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .price-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: var(--category-color);
        }
        .price-item-icon {
            font-size: 3rem;
        }
        .price-item-info {
            flex: 1;
        }
        .price-item-name {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        .price-item-value {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--category-color);
        }
        .view-all-prices {
            text-align: center;
            margin-top: 3rem;
        }
        .view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            background: #92400e;
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .view-all-btn:hover {
            background: #78350f;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(146, 64, 14, 0.3);
        }

        /* Products Section */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }
        .product-card {
            background: var(--card);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 2px solid transparent;
            box-shadow: var(--shadow);
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border-color: var(--primary-light);
        }
        .product-badges {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            z-index: 5;
        }
        .badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }
        .badge-sale { background: var(--accent); color: #fff; }
        .badge-new { background: var(--info); color: #fff; }
        .badge-fresh { background: var(--success); color: #fff; }
        .product-image {
            height: 180px;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            position: relative;
        }
        .product-favorite {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d1d5db;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .product-favorite:hover,
        .product-favorite.active {
            color: var(--accent);
            background: #fff;
            transform: scale(1.1);
        }
        .product-body {
            padding: 1.5rem;
        }
        .product-category {
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }
        .product-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem;
            color: var(--text);
            margin-bottom: 0.5rem;
            font-weight: 600;
            line-height: 1.3;
        }
        .product-origin {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 2px solid var(--border);
        }
        .price-info {
            display: flex;
            flex-direction: column;
        }
        .price-current {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-dark);
        }
        .price-old {
            font-size: 0.9rem;
            color: #9ca3af;
            text-decoration: line-through;
            margin-top: 0.2rem;
        }
        .price-unit {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 0.2rem;
        }
        .add-to-cart {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .add-to-cart:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }
        .add-to-cart.added {
            background: var(--success);
        }
        /* Responsive Design */
        @media (max-width: 1200px) {
            .categories-grid { grid-template-columns: repeat(4, 1fr); }
            .products-grid { grid-template-columns: repeat(4, 1fr); }
            .prices-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 992px) {
            .hero-container { 
                grid-template-columns: 1fr; 
                text-align: center; 
                gap: 3rem; 
            }
            .hero-visual { order: -1; }
            .hero-title { font-size: 3rem; }
            .categories-grid { grid-template-columns: repeat(3, 1fr); }
            .products-grid { grid-template-columns: repeat(3, 1fr); }
            .price-items { grid-template-columns: 1fr; }
            .hero-stats { grid-template-columns: 1fr; gap: 1rem; }
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .categories-grid { grid-template-columns: repeat(2, 1fr); }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-actions { flex-direction: column; align-items: center; }
            .daily-prices-feature { padding: 2rem; }
            .main-content { padding: 2rem 1rem; }
        }
        @media (max-width: 480px) {
            .categories-grid { grid-template-columns: 1fr; }
            .products-grid { grid-template-columns: 1fr; }
            .hero-container { padding: 0 1rem; }
            .visual-container { width: 300px; height: 300px; }
            .main-circle { width: 250px; height: 250px; }
            .main-icon { font-size: 5rem; }
        }
    </style>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-bolt"></i>
                    توصيل سريع خلال ساعة
                </div>
                <h1 class="hero-title">
                    مرحباً بك في<br>
                    <span class="highlight">توليب مارت</span>
                </h1>
                <p class="hero-subtitle">
                    سوبرماركتك الذكي للمنتجات الطازجة والعالية الجودة. نوفر لك تجربة تسوق استثنائية مع أفضل الأسعار وأسرع خدمة توصيل.
                </p>
                <div class="hero-actions">
                    <a href="/mart/products" class="btn btn-primary">
                        <i class="fas fa-shopping-basket"></i>
                        ابدأ التسوق
                    </a>
                    <a href="/mart/daily-prices" class="btn btn-outline">
                        <i class="fas fa-chart-line"></i>
                        أسعار اليوم
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="stat-card">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">منتج متوفر</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">خدمة مستمرة</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">طازج يومياً</span>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="visual-container">
                    <div class="main-circle">
                        <span class="main-icon">🛒</span>
                    </div>
                    <div class="floating-elements">
                        <span class="floating-element">🍎</span>
                        <span class="floating-element">🥬</span>
                        <span class="floating-element">🥛</span>
                        <span class="floating-element">🍞</span>
                        <span class="floating-element">🧀</span>
                        <span class="floating-element">🥕</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="main-content">
        <!-- Categories Section -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">تسوق حسب الأقسام</h2>
                <p class="section-subtitle">اختر من مجموعة واسعة من الأقسام المتنوعة لتلبية جميع احتياجاتك اليومية</p>
            </div>
            <div class="categories-grid" id="categoriesGrid"></div>
        </section>

        <!-- Daily Prices Feature -->
        <section class="daily-prices-feature">
            <div class="prices-header">
                <h2 class="prices-title">أسعار الفواكه والخضروات اليوم</h2>
                <p class="prices-subtitle" id="todayDate"></p>
            </div>
            <div class="prices-grid">
                <div class="price-category" style="--category-color: #f97316; --category-border: #f97316; --category-gradient: linear-gradient(90deg, #f97316, #fb923c);">
                    <div class="price-category-header">
                        <span class="price-category-icon">🍎</span>
                        <h3 class="price-category-title">الفواكه الطازجة</h3>
                    </div>
                    <div class="price-items" id="fruitsSpecialPrices"></div>
                </div>
                <div class="price-category" style="--category-color: #22c55e; --category-border: #22c55e; --category-gradient: linear-gradient(90deg, #22c55e, #16a34a);">
                    <div class="price-category-header">
                        <span class="price-category-icon">🥕</span>
                        <h3 class="price-category-title">الخضروات الطازجة</h3>
                    </div>
                    <div class="price-items" id="vegetablesSpecialPrices"></div>
                </div>
            </div>
            <div class="view-all-prices">
                <a href="/mart/daily-prices" class="view-all-btn">
                    <i class="fas fa-chart-bar"></i>
                    عرض جميع الأسعار
                </a>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">منتجات مميزة</h2>
                <p class="section-subtitle">اكتشف أفضل المنتجات المختارة خصيصاً لك بأعلى جودة وأفضل الأسعار</p>
            </div>
            <div class="products-grid" id="featuredProducts"></div>
        </section>

        <!-- Fresh Products -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">طازج اليوم</h2>
                <p class="section-subtitle">منتجات طازجة وصلت اليوم مباشرة من المزارع إلى مطبخك</p>
            </div>
            <div class="products-grid" id="freshProducts"></div>
        </section>
    </div>
    <!-- Footer -->
    <footer style="background:#0D464C; padding:1.8rem 3rem 2rem; position:relative;">
        <style>
            /* Responsive overrides (use !important to override inline styles) */
            footer { padding:1.4rem 1rem 1.6rem !important; box-sizing:border-box; }
            footer > div { max-width:1400px; margin:0 auto; padding:0 1rem; box-sizing:border-box; }
            footer > img { /* background image subtle */ width:100%; height:100%; object-fit:cover; opacity:0.03; pointer-events:none; }

            /* Grid container (first inner div) - center everything */
            footer > div > div:first-of-type {
                display:grid !important;
                grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)) !important;
                gap:2.5rem !important;
                margin-bottom:2rem !important;
                align-items:start;
                justify-items:center !important; /* center columns content */
                text-align:center !important;     /* center text inside columns */
            }

            footer h2 { margin-top:0.6rem !important; margin-bottom:0.8rem !important; font-size:1rem !important; text-align:center !important; }
            footer p { font-size:0.95rem !important; line-height:1.6 !important; text-align:center !important; }

            /* Logo & social icons */
            footer > div > div:first-of-type > div:first-of-type img { height:110px !important; margin-bottom:0.6rem !important; display:block; margin-left:auto; margin-right:auto; }
            footer > div > div:first-of-type > div:first-of-type .social-wrap { display:flex; gap:0.9rem; flex-wrap:wrap; margin-top:0.6rem; justify-content:center; }

            /* Make links inline-block to allow centered spacing and hover padding */
            footer > div > div:first-of-type a { display:inline-block; text-align:center; }

            /* Bottom row centered */
            footer > div > div:last-of-type {
                padding-top:1.4rem !important;
                border-top:1px solid rgba(255,255,255,0.1) !important;
                display:flex !important;
                justify-content:center !important;
                align-items:center !important;
                gap:1rem !important;
                flex-wrap:wrap;
                text-align:center;
            }
            footer > div > div:last-of-type p { margin:0 !important; font-size:0.9rem !important; color:rgba(255,255,255,0.55) !important; text-align:center !important; }

            footer > div > div:last-of-type .payments { display:flex; gap:1.2rem; align-items:center; flex-wrap:wrap; justify-content:center; }

            footer img.payment-icon { height:26px !important; opacity:0.6 !important; }

            /* Responsive breakpoints */
            @media (max-width:1200px) {
                footer > div > div:first-of-type { grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)) !important; gap:1.4rem !important; }
                footer > div > div:first-of-type > div:first-of-type img { height:95px !important; }
            }

            @media (max-width:800px) {
                footer { padding:1rem 0.8rem 1rem !important; }
                footer > div > div:first-of-type { grid-template-columns:1fr !important; gap:1rem !important; }
                footer > div > div:first-of-type > div { text-align:center !important; }
                footer > div > div:first-of-type > div:not(:first-of-type) a { display:inline-block !important; }
                /* center social icons */
                footer > div > div:first-of-type > div:first-of-type .social-wrap { justify-content:center; margin:0.6rem auto 0; }
                /* bottom row stack */
                footer > div > div:last-of-type { flex-direction:column !important; align-items:center !important; text-align:center !important; gap:0.8rem !important; }
                footer > div > div:last-of-type .payments { justify-content:center; }
            }

            @media (max-width:420px) {
                footer h2 { font-size:0.95rem !important; }
                footer p { font-size:0.9rem !important; }
                footer > div > div:first-of-type > div:first-of-type img { height:78px !important; }
                footer img.payment-icon { height:22px !important; }
            }
        </style>

        <img src="/images/footer.jpg" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.03;">
        
        <div style="max-width:1400px; margin:0 auto; position:relative;">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:2.5rem; margin-bottom:2rem; justify-items:center; text-align:center;">
                <div>
                    <img src="/images/white_orange_logo.png" style="height:130px;margin-bottom:0.8rem; display:block; margin-left:auto; margin-right:auto;">
                    <p style="color:rgba(255,255,255,0.7); line-height:1.8; font-size:1rem; margin-bottom:1rem; max-width:480px; margin-left:auto; margin-right:auto;">
                        متجر فاخر للهدايا والمنتجات المميزة. نساعدك في إرسال ابتسامتك لأحبائك أينما كانوا.
                    </p>
                    <div class="social-wrap" style="display:flex; gap:0.9rem; justify-content:center; margin-top:0.6rem;">
                        <a href="#" style="width:42px; height:42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s;" onmouseover="this.style.background='#2a7080'; this.style.borderColor='#2a7080'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" style="width:42px; height:42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s;" onmouseover="this.style.background='#2a7080'; this.style.borderColor='#2a7080'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" style="width:42px; height:42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s;" onmouseover="this.style.background='#2a7080'; this.style.borderColor='#2a7080'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                            <i class="fab fa-x"></i>
                        </a>
                        <a href="#" style="width:42px; height:42px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s;" onmouseover="this.style.background='#2a7080'; this.style.borderColor='#2a7080'; this.style.color='#fff'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.7)'">
                            <i class="fab fa-snapchat"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">روابط سريعة</h2>
                    <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                        <a href="/mart" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">توليب مارت</a>
                        <a href="/gifts" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">هدايا توليب</a>
                        <a href="#" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">من نحن؟</a>
                        <a href="#" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">تواصل معنا</a>
                    </div>
                </div>
                
                <div>
                    <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">الدعم التقني</h2>
                    <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                        <a href="#" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">الأسئلة الشائعة</a>
                        <a href="#" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الشحن</a>
                        <a href="#" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الإرجاع</a>
                        <a href="#" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">سياسة الخصوصية</a>
                    </div>
                </div>
                
                <div>
                    <h2 style="color:#ff6b35; font-weight:800; margin-bottom:1rem;margin-top:1rem ">الأقسام الخاصة</h2>
                    <div style="display:flex; flex-direction:column; gap:1.1rem; align-items:center;">
                        <a href="/mart/daily-prices" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">أسعار اليوم</a>
                        <a href="/mart/products" style="color:rgba(255,255,255,0.7); text-decoration:none; transition:all 0.3s; font-size:0.95rem; font-weight:400;" onmouseover="this.style.color='#fff'; this.style.paddingRight='8px'; this.style.fontWeight='700'" onmouseout="this.style.color='rgba(255,255,255,0.7)'; this.style.paddingRight='0'; this.style.fontWeight='400'">جميع المنتجات</a>
                    </div>
                </div>
            </div>
            
            <div style="padding-top:2rem; border-top:1px solid rgba(255,255,255,0.1); display:flex; justify-content:center; align-items:center; gap:1.2rem; flex-wrap:wrap;">
                <p style="color:rgba(255,255,255,0.5); margin:0; font-size:0.95rem;">© 2025 Tulip Store. جميع الحقوق محفوظة</p>
                <div class="payments" style="display:flex; gap:1.2rem; align-items:center; justify-content:center;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" class="payment-icon" style="height:30px; opacity:0.5;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="payment-icon" style="height:30px; opacity:0.5;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="payment-icon" style="height:30px; opacity:0.5;">
                    <img src="https://i.ibb.co/Q32tLdZg/Syriatel-Cash.png" class="payment-icon" style="height:30px; opacity:0.5;" alt="syriatelCash">
                </div>
            </div>
        </div>
    </footer>
    <script>
        // Enhanced Product Data with more variety
        const products = {
            fruits: [
                { id: 'm1', name: 'تفاح أحمر', emoji: '🍎', price: 8.5, oldPrice: 10, unit: 'كيلو', origin: 'تركيا', badge: 'sale', category: 'فواكه' },
                { id: 'm2', name: 'موز', emoji: '🍌', price: 6, oldPrice: null, unit: 'كيلو', origin: 'الإكوادور', badge: 'fresh', category: 'فواكه' },
                { id: 'm3', name: 'برتقال', emoji: '🍊', price: 5.5, oldPrice: 7, unit: 'كيلو', origin: 'مصر', badge: 'sale', category: 'فواكه' },
                { id: 'm4', name: 'عنب أحمر', emoji: '🍇', price: 15, oldPrice: null, unit: 'كيلو', origin: 'تشيلي', badge: 'new', category: 'فواكه' },
                { id: 'm5', name: 'مانجو', emoji: '🥭', price: 12, oldPrice: 14, unit: 'كيلو', origin: 'باكستان', badge: 'sale', category: 'فواكه' },
                { id: 'm6', name: 'فراولة', emoji: '🍓', price: 18, oldPrice: null, unit: 'علبة', origin: 'محلي', badge: 'fresh', category: 'فواكه' },
                { id: 'm27', name: 'كيوي', emoji: '🥝', price: 20, oldPrice: null, unit: 'كيلو', origin: 'نيوزيلندا', badge: 'new', category: 'فواكه' },
                { id: 'm28', name: 'أناناس', emoji: '🍍', price: 25, oldPrice: 30, unit: 'حبة', origin: 'كوستاريكا', badge: 'sale', category: 'فواكه' },
            ],
            vegetables: [
                { id: 'm7', name: 'طماطم', emoji: '🍅', price: 4, oldPrice: 5, unit: 'كيلو', origin: 'محلي', badge: 'sale', category: 'خضروات' },
                { id: 'm8', name: 'خيار', emoji: '🥒', price: 3.5, oldPrice: null, unit: 'كيلو', origin: 'محلي', badge: 'fresh', category: 'خضروات' },
                { id: 'm9', name: 'جزر', emoji: '🥕', price: 4.5, oldPrice: null, unit: 'كيلو', origin: 'محلي', badge: 'fresh', category: 'خضروات' },
                { id: 'm10', name: 'بطاطس', emoji: '🥔', price: 3, oldPrice: 3.5, unit: 'كيلو', origin: 'محلي', badge: 'sale', category: 'خضروات' },
                { id: 'm11', name: 'بصل', emoji: '🧅', price: 2.5, oldPrice: null, unit: 'كيلو', origin: 'محلي', badge: 'fresh', category: 'خضروات' },
                { id: 'm12', name: 'فلفل ألوان', emoji: '🫑', price: 12, oldPrice: null, unit: 'كيلو', origin: 'هولندا', badge: 'new', category: 'خضروات' },
                { id: 'm29', name: 'باذنجان', emoji: '🍆', price: 5, oldPrice: null, unit: 'كيلو', origin: 'محلي', badge: 'fresh', category: 'خضروات' },
                { id: 'm30', name: 'كوسا', emoji: '🥒', price: 4, oldPrice: null, unit: 'كيلو', origin: 'محلي', badge: 'fresh', category: 'خضروات' },
            ],
            leafy: [
                { id: 'm13', name: 'خس', emoji: '🥬', price: 3, oldPrice: null, unit: 'حبة', origin: 'محلي', badge: 'fresh', category: 'ورقيات' },
                { id: 'm14', name: 'سبانخ', emoji: '🥬', price: 4, oldPrice: 5, unit: 'ربطة', origin: 'محلي', badge: 'sale', category: 'ورقيات' },
                { id: 'm15', name: 'بقدونس', emoji: '🌿', price: 1.5, oldPrice: null, unit: 'ربطة', origin: 'محلي', badge: 'fresh', category: 'ورقيات' },
                { id: 'm16', name: 'نعناع', emoji: '🌿', price: 2, oldPrice: null, unit: 'ربطة', origin: 'محلي', badge: 'fresh', category: 'ورقيات' },
                { id: 'm31', name: 'جرجير', emoji: '🥬', price: 3.5, oldPrice: null, unit: 'ربطة', origin: 'محلي', badge: 'fresh', category: 'ورقيات' },
                { id: 'm32', name: 'كزبرة', emoji: '🌿', price: 2, oldPrice: null, unit: 'ربطة', origin: 'محلي', badge: 'fresh', category: 'ورقيات' },
            ],
            dairy: [
                { id: 'm17', name: 'حليب طازج', emoji: '🥛', price: 6, oldPrice: null, unit: 'لتر', origin: 'محلي', badge: 'fresh', category: 'ألبان' },
                { id: 'm18', name: 'لبن', emoji: '🥛', price: 5, oldPrice: 5.5, unit: 'لتر', origin: 'محلي', badge: 'sale', category: 'ألبان' },
                { id: 'm19', name: 'جبنة بيضاء', emoji: '🧀', price: 25, oldPrice: null, unit: 'كيلو', origin: 'محلي', badge: 'fresh', category: 'ألبان' },
                { id: 'm20', name: 'بيض', emoji: '🥚', price: 18, oldPrice: 20, unit: 'طبق', origin: 'محلي', badge: 'sale', category: 'ألبان' },
                { id: 'm33', name: 'زبدة', emoji: '🧈', price: 15, oldPrice: null, unit: 'علبة', origin: 'محلي', badge: 'fresh', category: 'ألبان' },
                { id: 'm34', name: 'كريمة طبخ', emoji: '🥛', price: 8, oldPrice: null, unit: 'علبة', origin: 'محلي', badge: 'new', category: 'ألبان' },
            ],
            bakery: [
                { id: 'm21', name: 'خبز عربي', emoji: '🫓', price: 2, oldPrice: null, unit: 'ربطة', origin: 'محلي', badge: 'fresh', category: 'مخبوزات' },
                { id: 'm22', name: 'توست', emoji: '🍞', price: 5, oldPrice: null, unit: 'كيس', origin: 'محلي', badge: 'fresh', category: 'مخبوزات' },
                { id: 'm23', name: 'كرواسون', emoji: '🥐', price: 3, oldPrice: null, unit: 'حبة', origin: 'محلي', badge: 'new', category: 'مخبوزات' },
                { id: 'm35', name: 'كعك', emoji: '🧁', price: 4, oldPrice: null, unit: 'حبة', origin: 'محلي', badge: 'fresh', category: 'مخبوزات' },
                { id: 'm36', name: 'دونات', emoji: '🍩', price: 6, oldPrice: null, unit: 'حبة', origin: 'محلي', badge: 'new', category: 'مخبوزات' },
            ],
            grocery: [
                { id: 'm24', name: 'أرز بسمتي', emoji: '🍚', price: 35, oldPrice: 40, unit: '5 كيلو', origin: 'الهند', badge: 'sale', category: 'بقالة' },
                { id: 'm25', name: 'زيت زيتون', emoji: '🫒', price: 45, oldPrice: null, unit: 'لتر', origin: 'سوريا', badge: 'fresh', category: 'بقالة' },
                { id: 'm26', name: 'سكر', emoji: '🧂', price: 8, oldPrice: null, unit: 'كيلو', origin: 'محلي', badge: 'fresh', category: 'بقالة' },
                { id: 'm37', name: 'ملح', emoji: '🧂', price: 3, oldPrice: null, unit: 'كيلو', origin: 'محلي', badge: 'fresh', category: 'بقالة' },
                { id: 'm38', name: 'معكرونة', emoji: '🍝', price: 7, oldPrice: 8, unit: 'علبة', origin: 'إيطاليا', badge: 'sale', category: 'بقالة' },
            ]
        };

        const categories = [
            { id: 'fruits', name: 'فواكه', emoji: '🍎', color: '#f97316', gradient: 'linear-gradient(135deg, #f97316, #fb923c)' },
            { id: 'vegetables', name: 'خضروات', emoji: '🥕', color: '#22c55e', gradient: 'linear-gradient(135deg, #22c55e, #16a34a)' },
            { id: 'leafy', name: 'ورقيات', emoji: '🥬', color: '#10b981', gradient: 'linear-gradient(135deg, #10b981, #059669)' },
            { id: 'dairy', name: 'ألبان وبيض', emoji: '🥛', color: '#3b82f6', gradient: 'linear-gradient(135deg, #3b82f6, #2563eb)' },
            { id: 'bakery', name: 'مخبوزات', emoji: '🍞', color: '#d97706', gradient: 'linear-gradient(135deg, #d97706, #b45309)' },
            { id: 'grocery', name: 'بقالة', emoji: '🛒', color: '#7c3aed', gradient: 'linear-gradient(135deg, #7c3aed, #6d28d9)' },
        ];

        // Store products globally for search
        window.martProducts = products;
        window.martCategories = categories;

        // Initialize page
        document.addEventListener('DOMContentLoaded', () => {
            loadTodayDate();
            loadCategories();
            loadSpecialPrices();
            loadFeaturedProducts();
            loadFreshProducts();
            initMartSearch();
            initScrollAnimations();
        });

        function loadTodayDate() {
            const today = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            document.getElementById('todayDate').textContent = 'آخر تحديث: ' + today.toLocaleDateString('ar-SA', options);
        }

        function loadCategories() {
            document.getElementById('categoriesGrid').innerHTML = categories.map(c => `
                <div class="category-card" style="--cat-color: ${c.color}; --cat-gradient: ${c.gradient}" onclick="filterCategory('${c.id}')">
                    <span class="category-icon">${c.emoji}</span>
                    <div class="category-name">${c.name}</div>
                    <div class="category-count">${products[c.id]?.length || 0} منتج</div>
                </div>
            `).join('');
        }

        function filterCategory(catId) {
            window.location.href = `/mart/products?category=${catId}`;
        }

        function loadSpecialPrices() {
            // Load fruits special prices (top 4)
            const fruitsSpecial = products.fruits.slice(0, 4);
            document.getElementById('fruitsSpecialPrices').innerHTML = fruitsSpecial.map(p => `
                <div class="price-item" onclick="addToCart('${p.id}', event)" style="--category-color: #f97316;">
                    <span class="price-item-icon">${p.emoji}</span>
                    <div class="price-item-info">
                        <div class="price-item-name">${p.name}</div>
                        <div class="price-item-value">${p.price} ر.س / ${p.unit}</div>
                    </div>
                </div>
            `).join('');

            // Load vegetables special prices (top 4)
            const vegetablesSpecial = products.vegetables.slice(0, 4);
            document.getElementById('vegetablesSpecialPrices').innerHTML = vegetablesSpecial.map(p => `
                <div class="price-item" onclick="addToCart('${p.id}', event)" style="--category-color: #22c55e;">
                    <span class="price-item-icon">${p.emoji}</span>
                    <div class="price-item-info">
                        <div class="price-item-name">${p.name}</div>
                        <div class="price-item-value">${p.price} ر.س / ${p.unit}</div>
                    </div>
                </div>
            `).join('');
        }

        function loadFeaturedProducts() {
            const featured = Object.values(products).flat().slice(0, 5);
            document.getElementById('featuredProducts').innerHTML = featured.map(p => createProductCard(p)).join('');
        }

        function loadFreshProducts() {
            const fresh = Object.values(products).flat().filter(p => p.badge === 'fresh').slice(0, 5);
            document.getElementById('freshProducts').innerHTML = fresh.map(p => createProductCard(p)).join('');
        }
        function createProductCard(p) {
            return `
                <div class="product-card" data-id="${p.id}">
                    <div class="product-badges">
                        ${p.badge === 'sale' ? '<span class="badge badge-sale">عرض</span>' : ''}
                        ${p.badge === 'new' ? '<span class="badge badge-new">جديد</span>' : ''}
                        ${p.badge === 'fresh' ? '<span class="badge badge-fresh">طازج</span>' : ''}
                    </div>
                    <div class="product-image">
                        <button class="product-favorite" onclick="toggleFavorite('${p.id}', event)">
                            <i class="far fa-heart"></i>
                        </button>
                        ${p.emoji}
                    </div>
                    <div class="product-body">
                        <div class="product-category">${p.category}</div>
                        <h3 class="product-name">${p.name}</h3>
                        <div class="product-origin">
                            <i class="fas fa-map-marker-alt"></i>
                            ${p.origin}
                        </div>
                        <div class="product-footer">
                            <div class="price-info">
                                <span class="price-current">${p.price} ر.س</span>
                                ${p.oldPrice ? `<span class="price-old">${p.oldPrice} ر.س</span>` : ''}
                                <span class="price-unit">/ ${p.unit}</span>
                            </div>
                            <button class="add-to-cart" onclick="addToCart('${p.id}', event)" id="btn-${p.id}">
                                <i class="fas fa-plus"></i>
                                أضف
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function toggleFavorite(productId, event) {
            event.stopPropagation();
            const btn = event.target.closest('.product-favorite');
            const icon = btn.querySelector('i');
            btn.classList.toggle('active');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
        }

        async function addToCart(productId, event) {
            if (event) event.stopPropagation();
            
            const btn = document.getElementById(`btn-${productId}`);
            const originalContent = btn.innerHTML;
            
            // Find product
            const product = Object.values(products).flat().find(p => p.id === productId);
            if (!product) return;
            
            // Show loading
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        product_type: 'mart',
                        name: product.name,
                        price: product.price,
                        quantity: 1,
                        image: product.emoji,
                        unit: product.unit
                    })
                });
                
                const data = await response.json();
                
                // Show success
                btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                btn.classList.add('added');
                
                // Update cart count
                if (window.updateCartCount) {
                    window.updateCartCount(data.count || 0);
                }
                if (window.animateCartIcon) {
                    window.animateCartIcon();
                }
                if (window.showToast) {
                    window.showToast('تمت إضافة ' + product.name + ' إلى السلة');
                }
                
                // Reset button after delay
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.classList.remove('added');
                    btn.disabled = false;
                }, 2500);
                
            } catch (error) {
                console.error('Error adding to cart:', error);
                btn.innerHTML = '<i class="fas fa-times"></i> خطأ';
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }, 2000);
            }
        }

        // Initialize scroll animations
        function initScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all sections
            document.querySelectorAll('.section, .daily-prices-feature').forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = 'all 0.6s ease';
                observer.observe(section);
            });
        }

        // Initialize Mart-specific search
        function initMartSearch() {
            const searchInput = document.getElementById('searchInput');
            const searchDropdown = document.getElementById('searchDropdown');
            const searchResults = document.getElementById('searchResults');
            const dropdownTitle = document.getElementById('dropdownTitle');
            const recentChips = document.getElementById('recentChips');
            
            if (!searchInput) return;
            
            // Override search behavior for mart
            searchInput.removeEventListener('input', searchInput._martHandler);
            
            const martSearchHandler = function(e) {
                const query = this.value.trim().toLowerCase();
                
                if (query.length < 2) {
                    if (recentChips) recentChips.style.display = 'flex';
                    if (searchResults) searchResults.style.display = 'none';
                    if (dropdownTitle) dropdownTitle.textContent = 'ابحث في توليب مارت';
                    return;
                }
                
                // Search in mart products
                const allProducts = Object.values(window.martProducts).flat();
                const results = allProducts.filter(p => 
                    p.name.toLowerCase().includes(query) || 
                    p.category.toLowerCase().includes(query) ||
                    p.origin.toLowerCase().includes(query)
                );
                
                if (recentChips) recentChips.style.display = 'none';
                if (searchResults) {
                    searchResults.style.display = 'flex';
                    searchResults.style.maxHeight = '400px';
                    searchResults.style.overflowY = 'auto';
                }
                if (dropdownTitle) dropdownTitle.textContent = `نتائج البحث في توليب مارت (${results.length})`;
                
                if (results.length === 0) {
                    searchResults.innerHTML = `
                        <div style="text-align:center;color:#999;padding:2rem;">
                            <i class="fas fa-search" style="font-size:2rem;margin-bottom:1rem;opacity:0.3;"></i>
                            <p>لا توجد نتائج في توليب مارت</p>
                            <a href="/mart/products" style="color:#059669;text-decoration:none;font-weight:600;">تصفح جميع المنتجات</a>
                        </div>
                    `;
                    return;
                }
                
                searchResults.innerHTML = results.slice(0, 8).map(p => `
                    <div class="search-result-item" onclick="addToCart('${p.id}', event)" style="cursor:pointer;padding:1rem;border-bottom:1px solid #f1f5f9;transition:all 0.3s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <span style="font-size:2.5rem;margin-left:1rem">${p.emoji}</span>
                        <div class="search-result-info" style="flex:1;">
                            <div class="search-result-name" style="font-weight:600;color:#1f2937;margin-bottom:0.3rem;">${p.name}</div>
                            <div style="display:flex;align-items:center;gap:1rem;font-size:0.85rem;color:#6b7280;">
                                <span><i class="fas fa-tag"></i> ${p.category}</span>
                                <span><i class="fas fa-map-marker-alt"></i> ${p.origin}</span>
                            </div>
                            <div class="search-result-price" style="color:#059669;font-weight:700;font-size:1.1rem;margin-top:0.3rem;">${p.price} ر.س / ${p.unit}</div>
                        </div>
                        <button style="background:#059669;color:#fff;border:none;padding:0.7rem 1.2rem;border-radius:25px;cursor:pointer;font-family:inherit;font-weight:600;transition:all 0.3s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
                            <i class="fas fa-plus"></i> أضف
                        </button>
                    </div>
                `).join('') + `
                    <div style="padding:1rem;text-align:center;border-top:2px solid #f1f5f9;">
                        <a href="/mart/products?search=${encodeURIComponent(query)}" style="color:#059669;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem;">
                            <span>عرض جميع النتائج (${results.length})</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                `;
            };
            
            searchInput._martHandler = martSearchHandler;
            searchInput.addEventListener('input', martSearchHandler);
            
            // Update placeholder
            searchInput.placeholder = 'ابحث في توليب مارت...';
            
            // Handle Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        window.location.href = '/mart/products?search=' + encodeURIComponent(query);
                    }
                }
            });
            
            // Keep dropdown open when clicking inside
            if (searchDropdown) {
                searchDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        }
    </script>
</body>
</html>