<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>توليب مارت - Tulip Mart</title>
    <link rel="stylesheet" href="/css/store.css?v=999&fix=store&t=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
            font-family: 'El Messiri', sans-serif; 
            background: var(--bg); 
            line-height: 1.6;
            color: var(--text);
        }

        /* Store/Gifts-like Hero Banner */
        .hero {
            position: relative;
            margin: 1.5rem auto 0 auto;
            max-width: 1280px;
            min-height: auto;
            background: transparent;
        }
        .hero::before, .hero::after { display: none; }
        .hero-card {
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        .hero-card-img {
            display: block;
            width: 100%;
            height: auto;
        }
        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .hero-content {
            animation: slideInRight 1s ease-out;
            text-align: center;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.12);
            padding: 0.7rem 1.4rem;
            border-radius: 50px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
        }
        .hero-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 3.6rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }
        .hero-title .highlight {
            color: var(--secondary);
            text-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
        }
        .hero-icons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .hero-icon-item {
            font-size: 3rem;
            animation: float 3s ease-in-out infinite;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.25));
        }
        .hero-icon-item:nth-child(2) { animation-delay: 0.5s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-5deg); }
            50% { transform: translateY(-18px) rotate(5deg); }
        }
        .hero-subtitle {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 3rem;
            line-height: 1.7;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-actions {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        .btn {
            padding: 0.9rem 2.3rem;
            border-radius: 50px;
            font-family:  'El Messiri', sans-serif;
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
            gap: 1.5rem;
        }
        .stat-card {
            text-align: center;
            padding: 1.4rem;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 6px 14px rgba(0,0,0,0.08);
        }
        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            background: rgba(5, 150, 105, 0.12);
            border: 1px solid rgba(5, 150, 105, 0.25);
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }
        .stat-number {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.3rem;
            font-weight: 800;
            color: var(--primary);
            display: block;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: var(--text-light);
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
            width: 480px;
            height: 480px;
        }
        .main-circle {
            width: 330px;
            height: 330px;
            background: linear-gradient(135deg, rgba(255,255,255,0.18), rgba(255,255,255,0.08));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255,255,255,0.25);
            animation: pulse 4s ease-in-out infinite;
        }
        .accent-ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.18);
        }
        .accent-ring.ring-1 { width: 420px; height: 420px; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .accent-ring.ring-2 { width: 470px; height: 470px; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.6; }
        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.05); }
        }
        .main-icon {
            font-size: 6.5rem;
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
            font-size: 2.6rem;
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
            /* padding: 4rem 2rem; */
        }

        /* Section Styles */
        .section {
            margin-bottom: 5rem;
        }
        .section-header {
            text-align: center;
            margin-bottom: 2rem;
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
            padding: 0;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
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
            z-index: 2;
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
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1));
        }
        .category-photo {
            width: 100%;
            aspect-ratio: 1 / 1;
            height: auto;
            object-fit: cover;
            border-radius: 0;
            margin-bottom: 0.75rem;
            display: block;
        }
        .category-info {
            padding: 0 1rem 1.2rem;
        }
        .category-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.2rem;
        }
        .category-count {
            font-size: 0.8rem;
            color: var(--text-light);
            background: var(--bg);
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            display: inline-block;
        }
        /* Daily Prices Feature */
        .daily-prices-feature {
           
            border-radius: 32px;
            padding: 4rem;
            margin: 4rem 0;
            position: relative;
            overflow: hidden;
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
            align-items: stretch;
            gap: 0;
            padding: 0;
            background: #fff;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #eee;
            overflow: hidden;
        }
        .price-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: var(--category-color);
        }
        .price-item-photo {
            width: 85px;
            height: 85px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .price-item-info {
            flex: 1;
            padding: 0.8rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: right;
        }
        .price-item-name {
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.3rem;
            font-size: 1.05rem;
        }
        .price-item-value {
            font-family:'El Messiri',sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--category-color);
        }
        .view-all-prices .view-all-btn{
            background:#F05928 ; 
             color:#fff ;
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }
        .view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            /* background: ; */
            color: #F05928;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .view-all-btn:hover {
            /* background: #F05928; */
            transform: translateY(-3px);
            text-shadow: 0 10px 25px #F05928;
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
            aspect-ratio: 1 / 1;
            width: 100%;
            height: auto;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border-bottom: 2px solid #f1f5f9;
        }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }
        .product-favorite {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 35px;
            height: 35px;
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
            padding: 1rem;
        }
        .product-category {
            font-size: 0.75rem;
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.2rem;
            letter-spacing: 0.5px;
        }
        .product-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            color: var(--text);
            margin-bottom: 0.2rem;
            font-weight: 600;
            line-height: 1.3;
        }
        .product-origin {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
        }
        .price-info {
            display: flex;
            flex-direction: column;
        }
        .price-current {
            font-family:'El Messiri',sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-dark);
        }
        .price-old {
            font-size: 0.8rem;
            color: #9ca3af;
            text-decoration: line-through;
            margin-top: 0.1rem;
        }
        .price-unit {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.1rem;
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
            font-family: 'El Messiri', sans-serif;
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
            .categories-grid { grid-template-columns: repeat(4, 1fr) !important; }
            .products-grid { grid-template-columns: repeat(3, 1fr); }
            .price-items { grid-template-columns: 1fr; }
            .hero-stats { grid-template-columns: 1fr; gap: 1rem; }
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .categories-grid { 
                grid-template-columns: repeat(4, 1fr) !important; 
                gap: 0.8rem !important;
            }
            .category-name { font-size: 0.85rem !important; }
            .category-count { display: none; } /* Hide count on mobile to save space */
            .category-info { padding: 0 0.4rem 0.6rem !important; }
            .category-card { border-radius: 12px !important; }
            .products-grid { 
                grid-template-columns: repeat(2, 1fr) !important; 
                gap: 1rem !important;
            }
            .hero-actions { flex-direction: column; align-items: center; }
            
            /* Daily Prices Mobile Full Width & Grid */
            .daily-prices-feature { 
                padding: 2rem 1rem !important; 
                margin: 2rem -1rem !important; /* Negative margin to go edge-to-edge */
                border-radius: 0 !important;
                width: calc(100% + 2rem) !important;
            }
            .prices-grid { 
                grid-template-columns: 1fr !important; 
                gap: 2rem !important; 
            }
            .price-category {
                padding: 1.5rem 1rem !important;
                border-radius: 16px !important;
            }
            .price-items {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.5rem !important;
            }
            .price-item {
                flex-direction: column !important;
                border-radius: 12px !important;
                text-align: center !important;
            }
            .price-item-photo {
                width: 100% !important;
                aspect-ratio: 1 / 1 !important;
                height: auto !important;
            }
            .price-item-info {
                padding: 0.6rem !important;
                text-align: center !important;
            }
            .price-item-name {
                font-size: 0.9rem !important;
                margin-bottom: 0.2rem !important;
            }
            .price-item-value {
                font-size: 0.8rem !important;
            }

            .main-content { padding: 2rem 1rem; }
            
            /* Disable hover scale on mobile */
            .product-card:hover, .category-card:hover {
                transform: none !important;
                box-shadow: var(--shadow) !important;
                border-color: transparent !important;
            }
        }
        @media (max-width: 480px) {
            .categories-grid { 
                grid-template-columns: repeat(4, 1fr) !important; 
                gap: 0.4rem !important;
            }
            .products-grid { 
                grid-template-columns: repeat(2, 1fr) !important; 
                gap: 0.5rem !important;
            }
            .hero-container { padding: 0 1rem; }
            .visual-container { width: 300px; height: 300px; }
            .main-circle { width: 250px; height: 250px; }
            .main-icon { font-size: 5rem; }
        }
    </style>

    <div ><img src="<?php echo e(asset('images/panner_mart.png')); ?>" alt="" style="width: 100%;"></div>
    <div class="main-content">
        <!-- Categories Section -->
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">تسوق حسب الأقسام</h2>
                <p class="section-subtitle">اختر من مجموعة واسعة من الأقسام المتنوعة لتلبية جميع احتياجاتك اليومية</p>
            </div>
            <div class="categories-grid" id="categoriesGrid">

            </div>
        </section>

        <!-- Daily Prices Feature -->
        <section class="daily-prices-feature">
            <div class="section-header">
                <h2 class="section-title">أسعار الفواكه والخضروات اليوم</h2>
                <p class="section-subtitle" id="todayDate"></p>
            </div>
            <div class="prices-grid">
                <div class="price-category" style="--category-color: #f97316; --category-border: #F05928; --category-gradient: linear-gradient(90deg, #F05928, #fb923c);">
                    <div class="price-category-header">
                        
                        <h3 class="price-category-title">الفواكه الطازجة</h3>
                    </div>
                    <div class="price-items" id="fruitsSpecialPrices"></div>
                </div>
                <div class="price-category" style="--category-color: #0D464C; --category-border: #0D464C; --category-gradient: linear-gradient(90deg, #0D464C, #408C94);">
                    <div class="price-category-header">
                      
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
            <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
                <div>
                    <h2 class="section-title">منتجات مميزة</h2>
                    <p class="section-subtitle">اكتشف أفضل المنتجات المختارة خصيصاً لك بأعلى جودة وأفضل الأسعار</p>
                </div>
                <a href="/mart/products" class="view-all-btn" style="margin-inline-start:auto; display:inline-flex; align-items:center; gap:.5rem;">
                    <i class="fas fa-arrow-left"></i>
                    عرض المزيد
                </a>
            </div>
            <div class="products-grid" id="featuredProducts"></div>
        </section>

        <!-- Fresh Products -->
        <section class="section">
            <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
                <div>
                    <h2 class="section-title">طازج اليوم</h2>
                    <p class="section-subtitle">منتجات طازجة وصلت اليوم مباشرة من المزارع إلى مطبخك</p>
                </div>
                <a href="/mart/products" class="view-all-btn" style="margin-inline-start:auto; display:inline-flex; align-items:center; gap:.5rem;">
                    <i class="fas fa-arrow-left"></i>
                    عرض المزيد
                </a>
            </div>
            <div class="products-grid" id="freshProducts"></div>
        </section>
    </div>
    <!-- Footer -->
   <div style="position:relative; z-index:1001;">
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
    <script>
        let products = {};
        let categories = [];
        let allProductsFlat = [];
        const categoryImageBySlug = {};

        function resolvePublicImage(path) {
            if (!path) return null;
            const p = String(path);
            if (p.startsWith('http://') || p.startsWith('https://')) return p;
            if (p.startsWith('/')) return p;
            return `/storage/${p}`;
        }

        function guessEmoji(slug, name) {
            const s = String(slug || '').toLowerCase();
            const n = String(name || '').toLowerCase();
            if (s.includes('fruit') || n.includes('فواك')) return '';
            if (s.includes('veget') || n.includes('خض')) return '';
            if (s.includes('leaf') || n.includes('ورق')) return '';
            if (s.includes('dairy') || n.includes('لب')) return '';
            if (s.includes('baker') || n.includes('مخب')) return '';
            if (s.includes('groc') || n.includes('بقال')) return '';
            return '';
        }

        function normalizeApiProduct(p) {
            const categoryName = p.category?.name || '';
            const categorySlug = p.category?.slug || 'uncategorized';
            const price = parseFloat(p.discount_price || p.price || 0);
            const oldPrice = p.discount_price ? parseFloat(p.price || 0) : null;

            const attrs = Array.isArray(p.attributes) ? p.attributes : [];
            const unit = (attrs.find(a => a.name === 'unit')?.value) || p.unit || 'حبة';
            const origin = (attrs.find(a => a.name === 'origin')?.value) || p.origin || 'محلي';

            let badge = '';
            if (p.discount_price) badge = 'sale';
            else if (String(origin).includes('محلي')) badge = 'fresh';
            else if (p.is_featured) badge = 'new';

            return {
                id: p.id,
                name: p.name || '',
                emoji: guessEmoji(categorySlug, categoryName),
                price,
                oldPrice,
                unit,
                origin,
                badge,
                category: categoryName,
                categorySlug,
                imageUrl: resolvePublicImage(p.image) || (Array.isArray(p.images) && p.images[0])
                 || null,
            };
        }

        async function loadMartData() {
            const [categoriesRes, productsRes] = await Promise.all([
                fetch('/api/categories?market=mart', { headers: { 'Accept': 'application/json' } }),
                fetch('/api/products?market=mart&per_page=1000&sort_by=created_at&sort_order=desc', { headers: { 'Accept': 'application/json' } }),
            ]);

            const categoriesPayload = await categoriesRes.json().catch(() => ({ data: [] }));
            const productsPayload = await productsRes.json().catch(() => ({ data: [] }));
            const apiCategories = Array.isArray(categoriesPayload.data) ? categoriesPayload.data : [];
            const apiProducts = Array.isArray(productsPayload.data) ? productsPayload.data : [];

            const palette = [
                { color: '#f97316', gradient: 'linear-gradient(135deg, #f97316, #fb923c)' },
                { color: '#0D464C', gradient: 'linear-gradient(135deg, #0D464C, #0D464C)' },
                { color: '#3b82f6', gradient: 'linear-gradient(135deg, #3b82f6, #2563eb)' },
                { color: '#7c3aed', gradient: 'linear-gradient(135deg, #7c3aed, #6d28d9)' },
                { color: '#10b981', gradient: 'linear-gradient(135deg, #10b981, #059669)' },
                { color: '#d97706', gradient: 'linear-gradient(135deg, #d97706, #b45309)' },
            ];

            categories = apiCategories
                .map((c, i) => {
                const p = palette[i % palette.length];
                const slug = c.slug || String(c.id);
                const image = resolvePublicImage(c.image) || '/images/grocery.jpg';
                categoryImageBySlug[slug] = image;
                return { id: slug, name: c.name || slug, image, color: p.color, gradient: p.gradient };
            });

            products = {};
            allProductsFlat = apiProducts.map(normalizeApiProduct);
            allProductsFlat.forEach((p) => {
                const key = p.categorySlug || 'uncategorized';
                if (!products[key]) products[key] = [];
                products[key].push(p);
            });

            window.martProducts = products;
            window.martCategories = categories;
        }

        document.addEventListener('DOMContentLoaded', async () => {
            await loadMartData();
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
                    <img src="${c.image}" alt="${c.name}" class="category-photo">
                    <div class="category-info">
                        <div class="category-name">${c.name}</div>
                        <div class="category-count">${products[c.id]?.length || 0} منتج</div>
                    </div>
                </div>
            `).join('');
        }

        function filterCategory(catId) {
            window.location.href = `/mart/products?category=${catId}`;
        }

        function loadSpecialPrices() {
            const first = categories.find(c => c.id === 'fruits') || categories[0];
            const second = categories.find(c => c.id === 'vegetables') || categories[1];

            const fruitsSpecial = first ? (products[first.id] || []).slice(0, 4) : [];
            document.getElementById('fruitsSpecialPrices').innerHTML = fruitsSpecial.map(p => `
                <div class="price-item" onclick="addToCart('${p.id}', event)" style="--category-color: ${first?.color || '#0D464C'};">
                    <img src="${getProductImage(p)}" alt="${p.name}" class="price-item-photo">
                    <div class="price-item-info">
                        <div class="price-item-name">${p.name}</div>
                        <div class="price-item-value">${p.price} ل.س لكل 1 كغ</div>
                    </div>
                </div>
            `).join('');

            const vegetablesSpecial = second ? (products[second.id] || []).slice(0, 4) : [];
            document.getElementById('vegetablesSpecialPrices').innerHTML = vegetablesSpecial.map(p => `
                <div class="price-item" onclick="addToCart('${p.id}', event)" style="--category-color: ${second?.color || '#0D464C'};">
                    <img src="${getProductImage(p)}" alt="${p.name}" class="price-item-photo">
                    <div class="price-item-info">
                        <div class="price-item-name">${p.name}</div>
                        <div class="price-item-value">${p.price} ل.س لكل 1 كغ</div>
                    </div>
                </div>
            `).join('');
        }

        function loadFeaturedProducts() {
            const featured = allProductsFlat.filter(p => p.badge === 'new').slice(0, 5);
            if (featured.length === 0) {
                featured.push(...allProductsFlat.slice(0, 5));
            }
            document.getElementById('featuredProducts').innerHTML = featured.map(p => createProductCard(p)).join('');
        }

        function loadFreshProducts() {
            const fresh = allProductsFlat.filter(p => p.badge === 'fresh').slice(0, 5);
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
                        <img src="${getProductImage(p)}" alt="${p.name}">
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
                                <span class="price-current">${p.price} ل.س</span>
                                ${p.oldPrice ? `<span class="price-old">${p.oldPrice} ل.س</span>` : ''}
                                <span class="price-unit">لكل 1 كغ</span>
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
        function getProductImage(p) {
            if (p.imageUrl) return p.imageUrl;
            return categoryImageBySlug[p.categorySlug] || '/images/grocery.jpg';
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
            const product = Object.values(products).flat().find(p => String(p.id) === String(productId));
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
                        image: getProductImage(product),
                        unit: product.unit,
                        emoji: product.emoji
                    })
                });
                if (!response.ok) {
                    const errData = await response.json().catch(() => ({}));
                    if (window.showToast) {
                        window.showToast(errData.message || 'غير قادر على إضافة المنتج للسلة');
                    } else {
                        alert(errData.message || 'غير قادر على إضافة المنتج للسلة');
                    }
                    throw new Error(errData.message || 'Add to cart failed');
                }
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
                    // Mart delivery warning
                    setTimeout(() => {
                        window.showToast('تنبيه: منتجات Mart تتوفر للتوصيل فقط إلى (السويداء، عتيل، قنوات)', 4000);
                    }, 1500);
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
                    <div class="search-result-item" onclick="addToCart('${p.id}', event)" style="cursor:pointer;padding:1rem;border-bottom:1px solid #f1f5f9;transition:all 0.3s;display:flex;align-items:center;gap:1rem;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <img src="${getProductImage(p)}" alt="${p.name}" style="width:42px;height:42px;border-radius:10px;object-fit:cover;box-shadow:0 4px 10px rgba(0,0,0,0.08);">
                        <div class="search-result-info" style="flex:1;">
                            <div class="search-result-name" style="font-weight:600;color:#1f2937;margin-bottom:0.3rem;">${p.name}</div>
                            <div style="display:flex;align-items:center;gap:1rem;font-size:0.85rem;color:#6b7280;">
                                <span><i class="fas fa-tag"></i> ${p.category}</span>
                                <span><i class="fas fa-map-marker-alt"></i> ${p.origin}</span>
                            </div>
                            <div class="search-result-price" style="color:#059669;font-weight:700;font-size:1.1rem;margin-top:0.3rem;">${p.price} ل.س لكل 1 كغ</div>
                        </div>
                        <button style="background:#059669;color:#fff;border:none;padding:0.7rem 1.2rem;border-radius:25px;cursor:pointer;font-family: 'El Messiri', sans-serif;font-weight:600;transition:all 0.3s;" onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">
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
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/mart/index.blade.php ENDPATH**/ ?>