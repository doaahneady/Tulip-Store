<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>توليب مارت - Tulip Mart</title>
   
      <!-- fav icon -->
        <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
            <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">

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
            /* Match mart/products cart button style */
            --teal: #0f4f55;
            --teal-dark: #0a3b40;
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
          @media (max-width: 768px) {
            .section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
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
          }
        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
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
            padding: 0.8rem;
        }
        .product-category {
            font-size: 0.7rem;
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.1rem;
            letter-spacing: 0.5px;
        }
        .product-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 0.95rem;
            color: var(--text);
            margin-bottom: 0.1rem;
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-origin {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .product-footer {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            padding-top: 0.5rem;
            border-top: 1px solid var(--border);
            margin-top: 0.5rem;
        }
        .price-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .price-current {
            font-family:'El Messiri',sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--primary-dark);
        }
        .price-old { font-size: 0.75rem; color: #94a3b8; text-decoration: line-through; }
        .price-unit { font-size: 0.7rem; color: var(--text-light); }
        /* Modern Add to Cart Button over image */
        .cart-control-wrapper {
            position: absolute;
            bottom: 12px;
            right: 12px;
            left: 12px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            z-index: 10;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .add-btn-circle {
            width: 36px;
            height: 36px;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(15, 79, 85, 0.3);
            transition: all 0.3s ease;
        }

        .add-btn-circle:hover {
            transform: scale(1.1);
            background: var(--teal-dark);
        }

        .counter-control {
            display: none;
            width: 100%;
            background: var(--teal);
            color: #fff;
            border-radius: 25px;
            padding: 4px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(15, 79, 85, 0.4);
            animation: expandWidth 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes expandWidth {
            from { width: 36px; border-radius: 50%; }
            to { width: 100%; border-radius: 25px; }
        }

        .counter-control.active {
            display: flex;
        }

        .counter-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.8rem;
        }

        .counter-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .counter-value {
            font-family: 'Tajawal', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            min-width: 25px;
            text-align: center;
        }

        /* Hide default footer button */
        .product-footer .add-to-cart {
            display: none;
        }
        /* Responsive Design */
        @media (max-width: 1200px) {
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
            .products-grid { grid-template-columns: repeat(3, 1fr); }
            .price-items { grid-template-columns: 1fr; }
            .hero-stats { grid-template-columns: 1fr; gap: 1rem; }
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
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
            .products-grid { 
                grid-template-columns: repeat(2, 1fr) !important; 
                gap: 0.5rem !important;
            }
            .hero-container { padding: 0 1rem; }
            .visual-container { width: 300px; height: 300px; }
            .main-circle { width: 250px; height: 250px; }
            .main-icon { font-size: 5rem; }
        }
        /* Modern Collapsible Sidebar */
        .mart-sidebar {
            position: fixed;
            top: 80px;
            right: 10px;
            width: 80px;
            background: #ffffff;
            border-radius: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 2000; /* Higher than footer's 1001 */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0;
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Layout Wrapper to handle side-by-side on mobile */
        .mart-layout-container {
            display: flex;
            flex-direction: row-reverse;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
        }

        .mart-sidebar:hover {
            width: 240px;
            border-radius: 24px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.4rem 0.8rem;
            text-decoration: none;
            color: var(--text);
            transition: all 0.3s ease;
            white-space: nowrap;
            cursor: pointer;
            position: relative;
        }

        .sidebar-item:hover {
            background: rgba(5, 150, 105, 0.05);
            color: var(--primary);
        }

        .sidebar-item.active {
            background: var(--primary);
            color: #fff !important;
        }

        .sidebar-icon {
            width: 50px;
            height:50px;
            min-width: 50px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            background: #f8fafc;
            transition: all 0.3s ease;
            overflow: hidden;
            /* border: 2px solid #000; */
           /* box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); */
        }

        .sidebar-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-item:hover .sidebar-icon {
            background: var(--primary);
            color: #fff;
            transform: scale(1.1);
        }

        .sidebar-item.active .sidebar-icon {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        .sidebar-text {
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .mart-sidebar:hover .sidebar-text {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
        }

        /* Mobile View for Sidebar */
        @media (max-width: 768px) {
            .mart-layout-container {
                padding: 0 10px;
                gap: 10px;
            }
            .mart-sidebar {
                position: sticky; /* Make it part of the flow instead of fixed/overlay */
                top: 20px;
                right: 0;
                width: 60px !important;
                height: fit-content;
                max-height: calc(100vh - 40px);
                border-radius: 20px;
                padding: 1rem 0;
                flex-shrink: 0;
                z-index: 100;
            }
            .mart-sidebar:hover {
                width: 60px !important;
            }
            .sidebar-text {
                display: none !important;
            }
            .sidebar-item {
                padding: 0.2rem;
                justify-content: center;
            }
            .sidebar-icon {
                width: 32px;
                height: 32px;
                min-width: 32px;
            }
            .main-content {
                margin-right: 0 !important;
                flex: 1;
                width: 0; /* Allow flex to shrink if needed */
            }
        }

        @media (min-width: 769px) {
            .main-content {
                margin-right: 100px;
                width: 100%;
            }
        }
        /* Responsive Utilities */
        @media (max-width: 768px) {
            .hide-mobile { display: none !important; }
            .show-mobile { display: flex !important; }
            .mobile-view-all {
                margin: 2rem auto 0;
                width: fit-content;
                justify-content: center;
            }
        }
        @media (min-width: 769px) {
            .show-mobile { display: none !important; }
        }
    </style>

    <div ><img src="<?php echo e(asset('images/panner_mart.png')); ?>" alt="" style="width: 100%;"></div>
    
    <div class="mart-layout-container">
        <!-- Collapsible Sidebar -->
        <div class="mart-sidebar" id="martSidebar">
            <!-- Will be populated by JS -->
        </div>
        
        <div class="main-content">
           <!-- Fresh Products -->
        <section class="section">
            <div class="section-header" style="display:flex; align-items:center; gap:1rem;">
                <div>
                    <h2 class="section-title"> آخر الإضافات</h2>
                    <p class="section-subtitle">منتجات وصلت اليوم</p>
                </div>
                <a href="/mart/products" class="view-all-btn hide-mobile" style="margin-inline-start:auto; display:inline-flex; align-items:center; gap:.5rem;">
                    <i class="fas fa-arrow-left"></i>
                    عرض المزيد
                </a>
            </div>
            <div class="products-grid" id="freshProducts"></div>
            <!-- Mobile Only View More -->
            <div class="show-mobile mobile-view-all">
                <a href="/mart/products" class="view-all-btn" style="display:inline-flex; align-items:center; gap:.5rem;">
                    <i class="fas fa-arrow-left"></i>
                    عرض المزيد
                </a>
            </div>
        </section>   <!-- Daily Prices Feature -->
        <section class="daily-prices-feature">
            <div class="section-header">
                <h2 class="section-title">أسعار الفواكه والخضروات اليوم</h2>
                <p class="section-subtitle" id="todayDate"></p>
            </div>
            <div class="prices-grid">
                <div class="price-category" style="--category-color: #f97316; --category-border: #F05928; --category-gradient: linear-gradient(90deg, #F05928, #fb923c);">
                    <div class="price-category-header">
                        
                        <h3 class="price-category-title">الفواكه </h3>
                    </div>
                    <div class="price-items" id="fruitsSpecialPrices"></div>
                </div>
                <div class="price-category" style="--category-color: #0D464C; --category-border: #0D464C; --category-gradient: linear-gradient(90deg, #0D464C, #408C94);">
                    <div class="price-category-header">
                      
                        <h3 class="price-category-title">الخضروات </h3>
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

      
    </div>
    </div>
    <!-- Footer -->
   <div style="position:relative; z-index:1001;">
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
    <script>
        let products = {};
        let categories = [];
        let sliderItems = [];
        let allProductsFlat = [];
        const categoryImageBySlug = {};
        const isAuthenticated = <?php echo json_encode(auth()->check(), 15, 512) ?>;
        let favoriteIds = new Set();

        function resolvePublicImage(p) {
            if (!p) return null;
            // Handle if p is an object with path or url
            const path = typeof p === 'object' ? (p.path || p.url || p.image_url || '') : String(p);
            if (!path) return null;
            
            const trimmed = path.trim().replace(/\\/g, '/');
            if (!trimmed || trimmed === '/images/panner_mart.png') return null;
            if (trimmed.startsWith('http://') || trimmed.startsWith('https://')) return trimmed;
            if (trimmed.startsWith('/')) return trimmed;
            return `/storage/${trimmed}`;
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
            const isFeatured = !!p.is_featured;
            const createdAt = p.created_at || p.createdAt || null;

            const attrs = Array.isArray(p.attributes) ? p.attributes : [];
            const unit = (attrs.find(a => a.name === 'unit')?.value) || p.unit || 'حبة';
            const origin = (attrs.find(a => a.name === 'origin')?.value) || p.origin || 'محلي';

            let badge = '';
            if (p.discount_price) badge = 'sale';
            else if (String(origin).includes('محلي')) badge = 'fresh';

            // Try various image fields from the API
            const imagePath = p.primary_image_url || p.image || p.photo || 
                             (Array.isArray(p.images) && p.images.length > 0 ? p.images[0] : null) || '';

            return {
                id: p.id,
                name: p.name || '',
                emoji: guessEmoji(categorySlug, categoryName),
                price,
                oldPrice,
                unit,
                origin,
                badge,
                isFeatured,
                createdAt,
                category: categoryName,
                categorySlug,
                imageUrl: resolvePublicImage(imagePath),
                fallbackImage: '/images/tulip_mart.jpg',
                stock: p.stock || 999999 // Assume 999999 if stock is not provided
            };
        }

        async function loadMartData() {
            const [navRes, productsRes] = await Promise.all([
                fetch('/api/mart/navigation', { headers: { 'Accept': 'application/json' } }),
                fetch('/api/products?market=mart&per_page=200&sort_by=created_at&sort_order=desc&include_attributes=1', { headers: { 'Accept': 'application/json' } }),
            ]);

            const navPayload = await navRes.json().catch(() => ({ data: [] }));
            const productsPayload = await productsRes.json().catch(() => ({ data: [] }));
            const apiCategories = Array.isArray(navPayload.data) ? navPayload.data : [];
            const apiProducts = Array.isArray(productsPayload.data) ? productsPayload.data : [];

            const palette = [
                { color: '#f97316', gradient: 'linear-gradient(135deg, #f97316, #fb923c)' },
                { color: '#0D464C', gradient: 'linear-gradient(135deg, #0D464C, #0D464C)' },
                { color: '#3b82f6', gradient: 'linear-gradient(135deg, #3b82f6, #2563eb)' },
                { color: '#7c3aed', gradient: 'linear-gradient(135deg, #7c3aed, #6d28d9)' },
                { color: '#10b981', gradient: 'linear-gradient(135deg, #10b981, #059669)' },
                { color: '#d97706', gradient: 'linear-gradient(135deg, #d97706, #b45309)' },
            ];

            const countsByCategory = {};
            categories = apiCategories.map((c, i) => {
                const p = palette[i % palette.length];
                const slug = c.slug || String(c.id);
                const image = resolvePublicImage(c.image_url || c.image) || '/images/tulip_mart.jpg';
                categoryImageBySlug[slug] = image;
                const subs = Array.isArray(c.subcategories) ? c.subcategories : [];
                countsByCategory[slug] = subs.reduce((sum, s) => sum + Number(s.products_count || 0), 0);
                return { id: slug, name: c.name || slug, image, color: p.color, gradient: p.gradient, productsCount: countsByCategory[slug] };
            });

            // Slider should show main categories (not subcategories)
            sliderItems = [...categories];

            products = {};
            allProductsFlat = apiProducts.map(normalizeApiProduct);
            allProductsFlat.forEach((p) => {
                const key = p.categorySlug || 'uncategorized';
                if (!products[key]) products[key] = [];
                products[key].push(p);
            });

            window.martProducts = products;
            window.martCategories = categories;
            window.martSubcategories = sliderItems;
        }

        document.addEventListener('DOMContentLoaded', async () => {
            await loadMartData();
            await loadFavoriteIds();
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
            const sidebar = document.getElementById('martSidebar');
            if (!sidebar) return;

            // Limit to 7 items + 1 "View All" = 8 items total as requested
            const displayItems = sliderItems.slice(0, 7);
            const currentUrl = new URL(window.location.href);
            const activeCategory = currentUrl.searchParams.get('category');

            let html = displayItems.map(c => {
                const isActive = activeCategory === c.id;
                return `
                    <div class="sidebar-item ${isActive ? 'active' : ''}" 
                         onclick="openMartSection('${c.id}', '')" 
                         title="${c.name}">
                        <div class="sidebar-icon">
                            <img src="${c.image}" alt="${c.name}" onerror="this.src='/images/tulip_mart.jpg'">
                        </div>
                        <span class="sidebar-text">${c.name}</span>
                    </div>
                `;
            }).join('');

            // Add "View All" button as the 8th item
            html += `
                <div class="sidebar-item" onclick="window.location.href='/mart/products'" title="عرض الكل">
                    <div class="sidebar-icon" style="background: var(--primary); color: #fff;">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <span class="sidebar-text">كل الأقسام</span>
                </div>
            `;

            sidebar.innerHTML = html;
        }

        function openMartSection(categorySlug, subSlug) {
            const cat = encodeURIComponent(String(categorySlug || '').trim());
            const sub = encodeURIComponent(String(subSlug || '').trim());
            window.location.href = sub ? `/mart/products?category=${cat}&subcategory=${sub}` : `/mart/products?category=${cat}`;
        }

        function loadSpecialPrices() {
            const classifySpecialCategory = (c) => {
                const id = String(c?.id || '').toLowerCase();
                const name = String(c?.name || '').toLowerCase();
                if (id.includes('fruit') || name.includes('فواك') || name.includes('فاكه')) return 'fruits';
                if (id.includes('veget') || id.includes('khdro') || id.includes('khodra') || name.includes('خضر') || name.includes('خضار')) return 'vegetables';
                return null;
            };

            const fruitsCat = categories.find(c => classifySpecialCategory(c) === 'fruits') || categories[0];
            const vegetablesCat = categories.find(c => classifySpecialCategory(c) === 'vegetables') || categories[1];

            const fruitsSpecial = fruitsCat ? (products[fruitsCat.id] || []).slice(0, 4) : [];
            document.getElementById('fruitsSpecialPrices').innerHTML = fruitsSpecial.map(p => `
                <div class="price-item" onclick="addToCart('${p.id}', event)" style="--category-color: ${fruitsCat?.color || '#0D464C'};">
                    <div class="price-item-photo" style="background-image: url('${p.fallbackImage}'); background-size: cover; background-position: center; position: relative; width: 60px; height: 60px; border-radius: 12px; overflow: hidden; flex-shrink: 0;">
                        ${p.imageUrl ? `<img src="${p.imageUrl}" alt="${p.name}" style="width: 100%; height: 100%; object-fit: cover;">` : ''}
                    </div>
                    <div class="price-item-info">
                        <div class="price-item-name">${p.name}</div>
                        <div class="price-item-value">${window.formatMoney ? window.formatMoney(p.price) : (p.price + ' ل.س')} ${p.unit ? `لكل ${p.unit}` : ''}</div>
                    </div>
                </div>
            `).join('');

            const vegetablesSpecial = vegetablesCat ? (products[vegetablesCat.id] || []).slice(0, 4) : [];
            document.getElementById('vegetablesSpecialPrices').innerHTML = vegetablesSpecial.map(p => `
                <div class="price-item" onclick="addToCart('${p.id}', event)" style="--category-color: ${vegetablesCat?.color || '#0D464C'};">
                    <div class="price-item-photo" style="background-image: url('${p.fallbackImage}'); background-size: cover; background-position: center; position: relative; width: 60px; height: 60px; border-radius: 12px; overflow: hidden; flex-shrink: 0;">
                        ${p.imageUrl ? `<img src="${p.imageUrl}" alt="${p.name}" style="width: 100%; height: 100%; object-fit: cover;">` : ''}
                    </div>
                    <div class="price-item-info">
                        <div class="price-item-name">${p.name}</div>
                        <div class="price-item-value">${window.formatMoney ? window.formatMoney(p.price) : (p.price + ' ل.س')} ${p.unit ? `لكل ${p.unit}` : ''}</div>
                    </div>
                </div>
            `).join('');
        }

        function loadFeaturedProducts() {
            const featured = allProductsFlat.filter(p => p.isFeatured === true).slice(0, 5);
            document.getElementById('featuredProducts').innerHTML = featured.length > 0
                ? featured.map(p => createProductCard(p)).join('')
                : '<div style="grid-column:1/-1; text-align:center; color:#94a3b8; padding:1.5rem;">لا توجد منتجات مميزة حالياً</div>';
        }

        function loadFreshProducts() {
            const toTs = (d) => {
                const t = Date.parse(String(d || ''));
                return Number.isFinite(t) ? t : 0;
            };

            const sorted = [...allProductsFlat].sort((a, b) => toTs(b.createdAt) - toTs(a.createdAt));
            const picked = [];
            const seen = new Set();

            // Show 10 products for Fresh Products section as requested
            for (const p of sorted) {
                if (picked.length >= 10) break;
                if (p.isFeatured) continue;
                const id = String(p.id);
                if (seen.has(id)) continue;
                seen.add(id);
                picked.push(p);
            }

            if (picked.length < 10) {
                for (const p of sorted) {
                    if (picked.length >= 10) break;
                    const id = String(p.id);
                    if (seen.has(id)) continue;
                    seen.add(id);
                    picked.push(p);
                }
            }

            document.getElementById('freshProducts').innerHTML = picked.map(p => createProductCard(p)).join('');
        }
        function createProductCard(p) {
            const isFav = favoriteIds.has(String(p.id));
            return `
                <div class="product-card" data-id="${p.id}" onclick="window.location.href='/products/${p.id}'">
                    <div class="product-badges">
                        ${p.badge === 'sale' ? '<span class="badge badge-sale">عرض</span>' : ''}
                        ${p.isFeatured ? '<span class="badge badge-new">جديد</span>' : ''}
                    </div>
                    <div class="product-image" style="background-image: url('${p.fallbackImage}'); background-size: cover; background-position: center;">
                        <button class="product-favorite" onclick="toggleFavorite('${p.id}', event)">
                            <i class="${isFav ? 'fas' : 'far'} fa-heart"></i>
                        </button>
                        ${p.imageUrl ? `<img src="${p.imageUrl}" alt="${p.name}">` : ''}
                        
                        <!-- Floating Cart Control -->
                        <div class="cart-control-wrapper" id="cart-wrapper-${p.id}" onclick="event.stopPropagation()">
                            <button class="add-btn-circle" onclick="initCartCounter('${p.id}', event)" id="add-btn-${p.id}">
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="counter-control" id="counter-${p.id}">
                                <button class="counter-btn" onclick="updateQuantity('${p.id}', -1, event)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="counter-value" id="count-${p.id}">1</span>
                                <button class="counter-btn" onclick="updateQuantity('${p.id}', 1, event)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="product-body">
                        <div class="product-category">${p.category}</div>
                        <h3 class="product-name">${p.name}</h3>
                        <div class="product-origin">
                            <i class="fas fa-map-marker-alt"></i>
                            ${p.origin}
                        </div>
                        <div class="product-footer">
                            <div class="price-wrapper">
                                <div class="price-current">
                                    ${window.formatMoney ? window.formatMoney(p.price) : (p.price + ' ل.س')}
                                    ${p.unit ? `<span class="price-unit">لكل ${p.unit}</span>` : ''}
                                </div>
                                ${p.oldPrice ? `<div class="price-old">${window.formatMoney ? window.formatMoney(p.oldPrice) : (p.oldPrice + ' ل.س')}</div>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        function getProductImage(p) {
            if (p.imageUrl) return p.imageUrl;
            return categoryImageBySlug[p.categorySlug] || '';
        }

        async function loadFavoriteIds() {
            favoriteIds = new Set();
            if (isAuthenticated) {
                try {
                    const r = await fetch('/api/wishlist', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    const d = await r.json();
                    const items = Array.isArray(d.items) ? d.items : [];
                    items.forEach((it) => {
                        if (it && it.id !== undefined && it.id !== null) favoriteIds.add(String(it.id));
                    });
                    return;
                } catch (e) {}
            }
            const items = JSON.parse(localStorage.getItem('favorites') || '[]');
            (Array.isArray(items) ? items : []).forEach((it) => {
                const id = String(it?.id ?? '');
                if (id && !id.startsWith('gift-')) favoriteIds.add(id);
            });
        }

        function persistLocalFavorite(productId) {
            const id = String(productId);
            const product = allProductsFlat.find((p) => String(p.id) === id);
            const items = JSON.parse(localStorage.getItem('favorites') || '[]');
            const list = Array.isArray(items) ? items : [];
            const idx = list.findIndex((x) => String(x?.id) === id);
            if (idx >= 0) {
                list.splice(idx, 1);
                favoriteIds.delete(id);
            } else {
                list.unshift({
                    id,
                    type: 'product',
                    name: product?.name || '',
                    price: Number(product?.price || 0),
                    image: getProductImage(product || {}) || '/images/tulip_mart.jpg',
                });
                favoriteIds.add(id);
            }
            localStorage.setItem('favorites', JSON.stringify(list.slice(0, 200)));
            return favoriteIds.has(id);
        }

        function toggleFavorite(productId, event) {
            event?.stopPropagation?.();
            const btn = event.target.closest('.product-favorite');
            const icon = btn.querySelector('i');
            const id = String(productId);
            const setIcon = (isFav) => {
                btn.classList.toggle('active', !!isFav);
                icon.classList.toggle('far', !isFav);
                icon.classList.toggle('fas', !!isFav);
            };

            if (isAuthenticated) {
                fetch('/api/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ product_id: id }),
                })
                    .then(r => r.json())
                    .then((d) => {
                        if (!d || !d.success) {
                            setIcon(persistLocalFavorite(id));
                            return;
                        }
                        if (d.action === 'added') favoriteIds.add(id);
                        if (d.action === 'removed') favoriteIds.delete(id);
                        setIcon(favoriteIds.has(id));
                    })
                    .catch(() => setIcon(persistLocalFavorite(id)));
                return;
            }

            setIcon(persistLocalFavorite(id));
        }

        async function initCartCounter(productId, event) { // Make it async
            if (event) event.stopPropagation();
            
            const addBtn = document.getElementById(`add-btn-${productId}`);
            const counter = document.getElementById(`counter-${productId}`);
            const wrapper = document.getElementById(`cart-wrapper-${productId}`);
            
            if (!addBtn || !counter || !wrapper) return;

            // Initial add to cart (quantity 1)
            const success = await addToCart(productId, 1); // Await the result

            if (success) { // Only update UI if successful
                addBtn.style.display = 'none';
                counter.classList.add('active');
                document.getElementById(`count-${productId}`).textContent = '1'; // Set initial count
            }
        }

        async function updateQuantity(productId, delta, event) {
            if (event) event.stopPropagation();
            
            const countSpan = document.getElementById(`count-${productId}`);
            if (!countSpan) return;

            let currentCount = parseInt(countSpan.textContent);
            let newCount = currentCount + delta;
            
            if (newCount < 1) {
                // Reset to circle button
                const addBtn = document.getElementById(`add-btn-${productId}`);
                const counter = document.getElementById(`counter-${productId}`);
                
                if (counter) counter.classList.remove('active');
                if (addBtn) addBtn.style.display = 'flex';
                countSpan.textContent = '1';
                
                // Remove from cart (quantity 0) instead of sending negative quantity
                await setCartQuantity(productId, 0);
                return;
            }

            // Increase uses /api/cart/add (delta +1), decrease uses /api/cart/update (absolute)
            let ok = true;
            if (delta > 0) {
                ok = await addToCart(productId, 1);
            } else {
                ok = await setCartQuantity(productId, newCount);
            }
            if (ok) countSpan.textContent = String(newCount);
        }

        async function setCartQuantity(productId, quantity) {
            try {
                const response = await fetch('/api/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        item_id: productId,
                        quantity: quantity
                    })
                });

                if (!response.ok) {
                    const errData = await response.json().catch(() => ({ message: 'غير قادر على تحديث السلة' }));
                    if (window.showToast) window.showToast(errData.message || 'غير قادر على تحديث السلة', 'error');
                    return false;
                }

                const data = await response.json();
                if (window.updateCartCount) window.updateCartCount(data.cart_count || data.count || 0);
                return true;
            } catch (error) {
                console.error('Error updating cart:', error);
                return false;
            }
        }

        async function addToCart(productId, quantity = 1, event) {
            if (event) event.stopPropagation();
            
            // Find product
            const product = Object.values(products).flat().find(p => String(p.id) === String(productId));
            if (!product) {
                console.error('Product not found for addToCart:', productId);
                if (window.showToast) window.showToast('خطأ: المنتج غير موجود.', 'error');
                return false; // Return false on product not found
            }
            
            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        product_id: productId,
                        product_type: 'mart',
                        name: product.name,
                        price: product.price,
                        quantity: quantity,
                        image: getProductImage(product),
                        unit: product.unit,
                        emoji: product.emoji
                    })
                });

                if (!response.ok) {
                    const errData = await response.json().catch(() => ({ message: 'غير قادر على تحديث السلة' }));
                    if (window.showToast) {
                        window.showToast(errData.message || 'غير قادر على تحديث السلة', 'error');
                    }
                    return false;
                }

                const data = await response.json();
                
                // Update cart count
                if (window.updateCartCount) {
                    window.updateCartCount(data.count || 0);
                }
                if (window.animateCartIcon) {
                    window.animateCartIcon();
                }

                // Only show success toast if quantity > 0 (not a pure removal operation, which updateQuantity handles)
                if (quantity > 0 && window.showToast) {
                    window.showToast('تم تحديث ' + product.name + ' في السلة', 'success');
                }
                
                return true;
            } catch (error) {
                console.error('Error updating cart:', error);
                if (window.showToast) window.showToast('خطأ في الاتصال بالخادم.', 'error');
                return false; // Return false on network/other error
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
                        <div class="search-result-image" style="background-image: url('${p.fallbackImage}'); background-size: cover; background-position: center; width:42px; height:42px; border-radius:10px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.08); flex-shrink:0;">
                            ${p.imageUrl ? `<img src="${p.imageUrl}" alt="${p.name}" style="width:100%; height:100%; object-fit:cover;">` : ''}
                        </div>
                        <div class="search-result-info" style="flex:1;">
                            <div class="search-result-name" style="font-weight:600;color:#1f2937;margin-bottom:0.3rem;">${p.name}</div>
                            <div style="display:flex;align-items:center;gap:1rem;font-size:0.85rem;color:#6b7280;">
                                <span><i class="fas fa-tag"></i> ${p.category}</span>
                                <span><i class="fas fa-map-marker-alt"></i> ${p.origin}</span>
                            </div>
                            <div class="search-result-price" style="color:#059669;font-weight:700;font-size:1.1rem;margin-top:0.3rem;">${window.formatMoney ? window.formatMoney(p.price) : (p.price + ' ل.س')} ${p.unit ? `لكل ${p.unit}` : ''}</div>
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
<?php /**PATH E:\Tulip-Store\resources\views/mart/index.blade.php ENDPATH**/ ?>