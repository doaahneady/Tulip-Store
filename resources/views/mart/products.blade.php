<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>جميع المنتجات - توليب مارت</title>
    
    <!-- fav icon -->
    <link rel="icon" type="image/png" href="/images/fav_icon.png">
    <link rel="stylesheet" href="{{ asset('css/store.min.css') }}?v={{ filemtime(public_path('css/store.min.css')) }}&t={{ time() }}" onerror="this.onerror=null;this.href='{{ asset('css/store.css') }}?fallback=1';">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- CSS Loading Check -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const testElement = document.createElement('div');
            testElement.className = 'tulip-navbar';
            testElement.style.position = 'absolute';
            testElement.style.visibility = 'hidden';
            document.body.appendChild(testElement);
            
            const computedStyle = window.getComputedStyle(testElement);
            const hasStyles = computedStyle.padding !== '0px' || computedStyle.margin !== '0px';
            
            if (!hasStyles) {
                console.warn('CSS may not have loaded properly');
                const link = document.querySelector('link[href*="store.css"]');
                if (link) {
                    const newLink = link.cloneNode();
                    newLink.href = link.href.replace(/[?&]t=\d+/, '') + '&reload=' + Date.now();
                    link.parentNode.insertBefore(newLink, link.nextSibling);
                }
            }
            
            document.body.removeChild(testElement);
        });
    </script>
</head>
<body>
    @include('components.navbar')

    <style>
        :root {
            --teal: #0f4f55;
            --teal-light: #2a7080;
            --teal-dark: #0f4f55;
            --orange: #ff6f35;
            --yellow: #fbbf24;
            --green: #22c55e;
            --red: #ef4444;
            --bg: #fdf8f3;
            --card: #ffffff;
            --text: #1a1a1a;
            --muted: #64748b;
            --border: #e8e8e8;
        }

        /* ===== RESET & BASE ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* إصلاح: overflow-x فقط بدل overflow: hidden الكلي */
        html {
            overflow-x: hidden;
            max-width: 100%;
        }
        body {
            font-family: 'El Messiri', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            max-width: 100%;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
            padding: 2rem;
            color: #fff;
        }
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .breadcrumb a { color: #fff; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .page-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .page-subtitle { opacity: 0.9; }

        /* Hero */
        .hero { padding: 1.5rem 0; background: transparent; }
        .hero-card { max-width: 1400px; margin: 0 auto; }
        .hero-card-img { width: 100%; height: 280px; object-fit: cover; display: block; }

        /* ===== MAIN LAYOUT ===== */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            width: 100%;
            box-sizing: border-box;
        }

        /* ===== SIDEBAR FILTERS ===== */
        .filters-sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border-radius: 22px;
            padding: 1.25rem;
            height: fit-content;
            position: sticky;
            top: 100px;
            border: 1px solid #eef2f7;
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.08);
            z-index: 1;
            box-sizing: border-box;
            overflow: hidden;
        }
        .filters-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eef2f7;
        }
        .filters-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .filters-title i { color: var(--teal); }
        .filters-actions { display: flex; align-items: center; gap: 0.5rem; }
        .filters-close {
            width: 34px;
            height: 34px;
            display: none;
            align-items: center;
            justify-content: center;
            border: 1px solid #eef2f7;
            background: #fff;
            border-radius: 10px;
            cursor: pointer;
            color: var(--muted);
        }
        .filters-close:hover { color: var(--teal); border-color: rgba(15,79,85,0.35); }
        .clear-filters {
            color: var(--red);
            font-size: 0.85rem;
            cursor: pointer;
            border: none;
            background: transparent;
            font-family: 'El Messiri', sans-serif;
            padding: 0.25rem 0.4rem;
        }
        .clear-filters:hover { text-decoration: underline; }

        .filter-group {
            margin-bottom: 1rem;
            padding: 0.9rem;
            border: 1px solid #eef2f7;
            border-radius: 16px;
            background: #fff;
        }
        .filter-group-title {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
        }

        /* Search Filter */
        .filter-search { position: relative; }
        .filter-search input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            font-family: 'El Messiri', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s;
            background: #fff;
            box-sizing: border-box;
        }
        .filter-search input:focus {
            outline: none;
            border-color: rgba(15,79,85,0.6);
            box-shadow: 0 0 0 4px rgba(15,79,85,0.10);
        }
        .filter-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
        }

        /* Category Filter */
        .category-list { display: flex; flex-direction: column; gap: 0.5rem; }
        .category-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 1rem;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid transparent;
        }
        .category-item:hover { background: #f8fafc; border-color: #eef2f7; }
        .category-item.active {
            background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
            border-color: rgba(15,79,85,0.35);
            color: var(--teal-dark);
        }
        .category-item .emoji { font-size: 1.3rem; }
        .category-item .count {
            margin-right: auto;
            background: var(--border);
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            color: var(--muted);
        }
        .category-item.active .count {
            background: var(--teal);
            color: #fff;
        }

        /* Price Range Filter */
        .price-range {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            max-width: 100%;
            box-sizing: border-box;
        }
        .price-input {
            flex: 1;
            min-width: 0;
            width: 0;
            padding: 0.6rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-family: 'El Messiri', sans-serif;
            font-size: 0.85rem;
            text-align: center;
            box-sizing: border-box;
        }
        .price-input:focus { outline: none; border-color: var(--teal); }
        .price-separator { color: var(--muted); }

        /* Checkbox Filters */
        .checkbox-list { display: flex; flex-direction: column; gap: 0.6rem; }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
        }
        .checkbox-item input {
            width: 18px;
            height: 18px;
            accent-color: var(--teal);
            cursor: pointer;
            flex-shrink: 0;
        }
        .checkbox-item label { cursor: pointer; font-size: 0.9rem; }

        /* ===== SORT SELECT ===== */
        .sort-select {
            padding: 0.7rem 1rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-family: 'El Messiri', sans-serif;
            font-size: 0.9rem;
            background: #fff;
            cursor: pointer;
            /* إصلاح: بدل min-width ثابت */
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
        }
        .sort-select:focus { outline: none; border-color: var(--teal); }

        /* ===== PRODUCTS AREA ===== */
        .products-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .results-count {
            color: var(--muted);
            font-size: 0.95rem;
        }
        .results-count span { color: var(--teal); font-weight: 600; }

        .view-options {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            max-width: 100%;
            box-sizing: border-box;
        }
        .view-btns {
            display: flex;
            gap: 0.3rem;
            background: var(--card);
            padding: 0.3rem;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        .view-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            border-radius: 6px;
            cursor: pointer;
            color: var(--muted);
            transition: all 0.3s;
        }
        .view-btn:hover { color: var(--teal); }
        .view-btn.active { background: var(--teal); color: #fff; }

        /* ===== PRODUCTS GRID ===== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        .products-grid.list-view { grid-template-columns: 1fr; }

        /* ===== PRODUCT CARD ===== */
        .product-card {
            background: var(--card);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
            border: 1px solid var(--border);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
            border-color: var(--teal);
        }
        .products-grid.list-view .product-card {
            display: grid;
            grid-template-columns: 120px 1fr;
        }
        .product-badges {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            z-index: 5;
        }
        .badge {
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
        }
        .badge-sale { background: var(--red); color: #fff; }
        .badge-new { background: var(--teal); color: #fff; }
        .badge-fresh { background: var(--green); color: #fff; }

        .product-image {
            aspect-ratio: 1 / 1;
            width: 100%;
            height: auto;
            background: linear-gradient(135deg, #eaf7f8, #f8f9fa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            position: relative;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .products-grid.list-view .product-image { height: 100%; }

        .product-favorite {
            position: absolute;
            top: 8px;
            left: 8px;
            width: 28px;
            height: 28px;
            background: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            transition: all 0.3s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .product-favorite:hover, .product-favorite.active { color: var(--red); }

        .product-body { padding: 0.8rem; display: flex; flex-direction: column; min-height: auto; }
        .product-category {
            font-size: 0.7rem;
            color: var(--teal);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.1rem;
        }
        .product-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 0.95rem;
            color: var(--text);
            margin-bottom: 0.1rem;
            font-weight: 700;
        }
        .product-origin {
            font-size: 0.75rem;
            color: var(--muted);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.2rem;
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
            font-family: 'El Messiri', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--teal-dark);
        }
        .price-old { font-size: 0.75rem; color: #94a3b8; text-decoration: line-through; }
        .price-unit { font-size: 0.7rem; color: var(--muted); }

        .add-cart-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            padding: 0.5rem 1rem;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-family: 'El Messiri', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(15,79,85,0.15);
            width: 100%;
        }
        .add-cart-btn:hover { background: var(--teal-dark); transform: scale(1.05); }
        .add-cart-btn.added { background: var(--green); }

        /* ===== ACTIVE FILTER TAGS ===== */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
            border: 1px solid var(--teal);
            border-radius: 20px;
            font-size: 0.85rem;
            color: var(--teal-dark);
        }
        .filter-tag button {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--teal-dark);
            padding: 0;
            display: flex;
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        .page-btn {
            width: 40px;
            height: 40px;
            border: 2px solid var(--border);
            background: var(--card);
            border-radius: 10px;
            cursor: pointer;
            font-family: 'El Messiri', sans-serif;
            font-weight: 600;
            color: var(--text);
            transition: all 0.3s;
        }
        .page-btn:hover { border-color: var(--teal); color: var(--teal); }
        .page-btn.active { background: var(--teal); border-color: var(--teal); color: #fff; }

        /* ===== NO RESULTS ===== */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }
        .no-results i { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
        .no-results h3 { font-family: 'El Messiri', sans-serif; color: var(--text); margin-bottom: 0.5rem; }

        /* ===== MOBILE FILTER BUTTON ===== */
        .mobile-filter-btn {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-family: 'El Messiri', sans-serif;
            font-weight: 600;
            width: 100%;
        }

        /* ===== MOBILE DROPDOWN FILTERS ===== */
        .mobile-filter-dropdowns {
            display: none;
            gap: 0.6rem;
            overflow-x: auto;
            padding: 0.5rem 0.2rem;
            scrollbar-width: none;
            margin-bottom: 1rem;
            -ms-overflow-style: none;
        }
        .mobile-filter-dropdowns::-webkit-scrollbar { display: none; }

        .mobile-filter-item {
            position: relative;
            flex-shrink: 0;
        }
        .mobile-filter-toggle {
            background: #fff;
            border: 1px solid var(--border);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-family: 'El Messiri', sans-serif;
            color: var(--teal);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            white-space: nowrap;
        }
        .mobile-filter-toggle.active {
            background: var(--teal);
            color: #fff;
            border-color: var(--teal);
        }
        .mobile-filter-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            z-index: 1000;
            min-width: 220px;
            margin-top: 0.5rem;
            padding: 1rem;
            border: 1px solid #eee;
        }
        .mobile-filter-item.open .mobile-filter-menu { display: block; }

        /* ===== OVERLAY ===== */
        .filters-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
        }
        .filters-overlay.active { display: block; }

        /* ===== HEADER TOP ROW ===== */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        /* ============================================================
           RESPONSIVE — 1200px
        ============================================================ */
        @media (max-width: 1200px) {
            .products-grid { grid-template-columns: repeat(3, 1fr); }
        }

        /* ============================================================
           RESPONSIVE — 992px (تابلت)
        ============================================================ */
        @media (max-width: 992px) {
            .main-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            /* على التابلت: sidebar مخفي والقوائم المنسدلة ظاهرة */
            .filters-sidebar { display: none !important; }
            .mobile-filter-dropdowns { display: flex; }
            .mobile-filter-btn { display: none !important; }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .view-btns { display: none !important; }
            .products-header {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
        }

        /* ============================================================
           RESPONSIVE — 768px
        ============================================================ */
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
            }
            .price-range { gap: 0.4rem; }
            .sort-select { width: 100%; }
        }

        /* ============================================================
           RESPONSIVE — 480px (موبايل صغير)
           → إخفاء القوائم المنسدلة
           → إظهار زر "تصفية النتائج" يفتح sidebar كـ drawer
        ============================================================ */
        @media (max-width: 480px) {

            /* إخفاء شريط الفلاتر المنسدلة */
            .mobile-filter-dropdowns {
                display: none !important;
            }

            /* إظهار زر الفلاتر */
            .mobile-filter-btn {
                display: flex !important;
                margin-bottom: 0.8rem;
            }

            /* الـ sidebar يصبح drawer من جهة اليمين */
            .filters-sidebar {
                display: block !important;
                position: fixed !important;
                top: 0 !important;
                right: -100% !important;
                bottom: 0 !important;
                width: 85vw !important;
                max-width: 320px !important;
                height: 100vh !important;
                overflow-y: auto !important;
                z-index: 9999 !important;
                border-radius: 0 !important;
                transition: right 0.3s ease !important;
                box-shadow: -4px 0 24px rgba(0,0,0,0.18) !important;
                padding: 1.25rem !important;
                margin: 0 !important;
                sticky: unset !important;
            }

            /* عندما يكون الـ drawer مفتوح */
            .filters-sidebar.active {
                right: 0 !important;
            }

            /* إظهار زر الإغلاق داخل الـ sidebar */
            .filters-close {
                display: flex !important;
            }

            /* إخفاء أزرار grid/list view */
            .view-btns {
                display: none !important;
            }

            /* تصغير الـ padding */
            .main-container {
                padding: 0.75rem !important;
                grid-template-columns: 1fr !important;
            }

            /* sort-select كامل العرض */
            .sort-select {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
            }

            /* header يكون عمودي */
            .products-header {
                flex-direction: column !important;
                align-items: stretch !important;
            }
            .header-top {
                justify-content: space-between;
            }
            .view-options {
                width: 100%;
                justify-content: flex-end;
            }

            /* منتجات: عمودين */
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.6rem !important;
            }

            /* تصغير بطاقة المنتج على الشاشات الصغيرة */
            .product-body { padding: 0.6rem; }
            .product-name { font-size: 0.85rem; }
            .price-current { font-size: 0.95rem; }
            .add-cart-btn { font-size: 0.78rem; padding: 0.45rem 0.6rem; }
        }

        /* ============================================================
           RESPONSIVE — 360px (أصغر الشاشات)
        ============================================================ */
        @media (max-width: 360px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.4rem !important;
            }
            .main-container { padding: 0.5rem !important; }
        }
    </style>

    <div class="filters-overlay" id="filtersOverlay" onclick="toggleFilters()"></div>

    <div class="main-container">
        <!-- Filters Sidebar -->
        <aside class="filters-sidebar" id="filtersSidebar">
            <div class="filters-header">
                <h3 class="filters-title"><i class="fas fa-sliders-h"></i> الفلاتر</h3>
                <div class="filters-actions">
                    <button type="button" class="filters-close" onclick="toggleFilters()"><i class="fas fa-times"></i></button>
                    <button type="button" class="clear-filters" onclick="clearAllFilters()">مسح الكل</button>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="filter-group">
                <div class="filter-group-title">البحث</div>
                <div class="filter-search">
                    <input type="text" id="searchFilter" placeholder="ابحث عن منتج...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="filter-group">
                <div class="filter-group-title">القسم</div>
                <div class="category-list" id="categoryList"></div>
            </div>

            <!-- Price Range -->
            <div class="filter-group">
                <div class="filter-group-title">نطاق السعر (ل.س)</div>
                <div class="price-range">
                    <input type="number" class="price-input" id="minPrice" placeholder="من">
                    <span class="price-separator">-</span>
                    <input type="number" class="price-input" id="maxPrice" placeholder="إلى">
                </div>
            </div>

            <!-- Origin Filter -->
            <div class="filter-group">
                <div class="filter-group-title">المصدر</div>
                <div class="checkbox-list" id="originList"></div>
            </div>

            <!-- Badge Filter -->
            <div class="filter-group">
                <div class="filter-group-title">نوع المنتج</div>
                <div class="checkbox-list">
                    <div class="checkbox-item">
                        <input type="checkbox" id="filterSale" onchange="applyFilters()">
                        <label for="filterSale"> عروض</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="filterNew" onchange="applyFilters()">
                        <label for="filterNew"> جديد</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="filterFresh" onchange="applyFilters()">
                        <label for="filterFresh"> طازج</label>
                    </div>
                </div>
            </div>

            <!-- Apply Button inside sidebar (mobile) -->
            <button class="add-cart-btn" style="margin-top:1rem;" onclick="applyFilters(); if(window.innerWidth <= 480) toggleFilters();">
                <i class="fas fa-check"></i> تطبيق الفلاتر
            </button>
        </aside>

        <!-- Products Area -->
        <main class="products-area">
            <div class="products-header">
                <div class="header-top">
                    <span class="results-count">عرض <span id="resultsCount">0</span> منتج</span>
                    <div class="view-options">
                        <select class="sort-select" id="sortSelect" onchange="applyFilters()">
                            <option value="default">الترتيب الافتراضي</option>
                            <option value="price-asc">السعر: من الأقل للأعلى</option>
                            <option value="price-desc">السعر: من الأعلى للأقل</option>
                            <option value="name-asc">الاسم: أ - ي</option>
                            <option value="name-desc">الاسم: ي - أ</option>
                        </select>
                        <div class="view-btns">
                            <button class="view-btn active" id="gridViewBtn" onclick="setView('grid')">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" id="listViewBtn" onclick="setView('list')">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- زر تصفية النتائج — يظهر على 480px فقط -->
                <button class="mobile-filter-btn" onclick="toggleFilters()">
                    <i class="fas fa-sliders-h"></i> تصفية النتائج
                </button>

                <!-- Mobile Dropdown Filters — يظهر بين 480px و 992px -->
                <div class="mobile-filter-dropdowns">
                    <div class="mobile-filter-item" id="mobileSearchFilter">
                        <button class="mobile-filter-toggle" onclick="toggleMobileFilter('mobileSearchFilter')">
                            <i class="fas fa-search"></i> البحث
                        </button>
                        <div class="mobile-filter-menu">
                            <div class="filter-search">
                                <input type="text" id="m-searchFilter" placeholder="ابحث..." oninput="syncFilters('search', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="mobile-filter-item" id="mobileCategoryFilter">
                        <button class="mobile-filter-toggle" onclick="toggleMobileFilter('mobileCategoryFilter')">
                            <i class="fas fa-th-large"></i> الأقسام
                        </button>
                        <div class="mobile-filter-menu">
                            <div class="category-list" id="m-categoryList"></div>
                        </div>
                    </div>

                    <div class="mobile-filter-item" id="mobilePriceFilter">
                        <button class="mobile-filter-toggle" onclick="toggleMobileFilter('mobilePriceFilter')">
                            <i class="fas fa-tags"></i> السعر
                        </button>
                        <div class="mobile-filter-menu">
                            <div class="price-range">
                                <input type="number" class="price-input" id="m-minPrice" placeholder="من" oninput="syncFilters('minPrice', this.value)">
                                <span class="price-separator">-</span>
                                <input type="number" class="price-input" id="m-maxPrice" placeholder="إلى" oninput="syncFilters('maxPrice', this.value)">
                            </div>
                            <button class="add-cart-btn" style="margin-top:1rem;" onclick="applyFilters(); toggleMobileFilter('mobilePriceFilter')">تطبيق</button>
                        </div>
                    </div>

                    <div class="mobile-filter-item" id="mobileTypeFilter">
                        <button class="mobile-filter-toggle" onclick="toggleMobileFilter('mobileTypeFilter')">
                            <i class="fas fa-star"></i> النوع
                        </button>
                        <div class="mobile-filter-menu">
                            <div class="checkbox-list">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="m-filterSale" onchange="syncFilters('sale', this.checked)">
                                    <label for="m-filterSale"> عروض</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="m-filterNew" onchange="syncFilters('new', this.checked)">
                                    <label for="m-filterNew"> جديد</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="m-filterFresh" onchange="syncFilters('fresh', this.checked)">
                                    <label for="m-filterFresh"> طازج</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Filters -->
            <div class="active-filters" id="activeFilters"></div>

            <!-- Products Grid -->
            <div class="products-grid" id="productsGrid"></div>

            <!-- Pagination -->
            <div class="pagination" id="pagination"></div>
        </main>
    </div>

    <script>
        let products = {};
        let categories = [{ id: 'all', name: 'الكل', emoji: '' }];
        const isAuthenticated = @json(auth()->check());
        let favoriteIds = new Set();

        function guessEmoji(slug, name) {
            const s = String(slug || '').toLowerCase();
            const n = String(name || '').toLowerCase();
            if (s.includes('fruit') || n.includes('فواك')) return '';
            if (s.includes('veget') || n.includes('خض')) return '';
            if (s.includes('leaf') || n.includes('ورق')) return '';
            if (s.includes('dairy') || n.includes('لب')) return '';
            if (s.includes('baker') || n.includes('مخب')) return '';
            if (s.includes('groc') || n.includes('بقال')) return '';
            return '🛒';
        }

        function escapeHtml(str) {
            return String(str || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function resolveProductImage(path) {
            const p = String(path || '').trim().replace(/\\/g, '/');
            if (!p) return null;
            if (p.startsWith('http://') || p.startsWith('https://')) return p;
            if (p.startsWith('/')) return `${window.location.origin}${p}`;
            const cleaned = p.replace(/^storage\//, '');
            return `${window.location.origin}/storage/${cleaned}`;
        }

        function persistLocalFavorite(id, product) {
            const items = JSON.parse(localStorage.getItem('favorites') || '[]');
            const list = Array.isArray(items) ? items : [];
            const idx = list.findIndex((x) => String(x?.id) === String(id));
            if (idx >= 0) {
                list.splice(idx, 1);
                favoriteIds.delete(String(id));
            } else {
                list.unshift({
                    id: String(id),
                    name: product?.name || '',
                    price: Number(product?.price || 0),
                    image: product?.image || '/images/panner_mart.png',
                    type: 'product'
                });
                favoriteIds.add(String(id));
            }
            localStorage.setItem('favorites', JSON.stringify(list.slice(0, 200)));
            updateFavoritesCount(favoriteIds.size);
            return favoriteIds.has(String(id));
        }

        function martFallbackImage(categorySlug, categoryName) {
            const slug = String(categorySlug || '').toLowerCase();
            const name = String(categoryName || '').toLowerCase();
            if (slug.includes('fruit') || slug.includes('veget') || name.includes('فوا') || name.includes('خضا') || name.includes('خضر')) return '/images/panner_mart.png';
            if (slug.includes('dairy') || name.includes('ألبان') || name.includes('حليب')) return '/images/panner_mart.png';
            if (slug.includes('bakery') || name.includes('مخب')) return '/images/panner_mart.png';
            return '/images/panner_mart.png';
        }

        function updateFavoritesCount(count) {
            const el = document.getElementById('favoritesCount');
            if (!el) return;
            const c = Number(count || 0);
            el.textContent = c > 99 ? '+99' : String(c);
        }

        async function loadFavorites() {
            favoriteIds = new Set();
            if (isAuthenticated) {
                try {
                    const r = await fetch('/api/wishlist', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    const d = await r.json();
                    const items = Array.isArray(d.items) ? d.items : [];
                    items.forEach((it) => favoriteIds.add(String(it.id)));
                    updateFavoritesCount(d.count || items.length || 0);
                    return;
                } catch (e) {
                    updateFavoritesCount(0);
                }
            }
            const items = JSON.parse(localStorage.getItem('favorites') || '[]');
            if (Array.isArray(items)) {
                items.forEach((it) => {
                    if (it && (it.id !== undefined && it.id !== null)) favoriteIds.add(String(it.id));
                });
            }
            updateFavoritesCount(favoriteIds.size);
        }

        function normalizeApiProduct(p) {
            const categoryName = p.category?.name || '';
            const categorySlug = p.category?.slug || 'uncategorized';
            const price = parseFloat(p.discount_price || p.price || 0);
            const oldPrice = p.discount_price ? parseFloat(p.price || 0) : null;
            const attrs = Array.isArray(p.attributes) ? p.attributes : [];
            const unit = (attrs.find(a => a.name === 'unit')?.value) || p.unit || 'حبة';
            const origin = (attrs.find(a => a.name === 'origin')?.value) || p.origin || 'محلي';
            const firstImage = Array.isArray(p.images) ? (p.images[0]?.path || p.images[0]?.url || p.images[0]) : null;
            const imageSource = p.primary_image_url || firstImage || p.image || p.photo || '';
            const image = resolveProductImage(imageSource) || martFallbackImage(categorySlug, categoryName);
            let badge = '';
            if (p.discount_price) badge = 'sale';
            else if (String(origin).includes('محلي')) badge = 'fresh';
            else if (p.is_featured) badge = 'new';
            return { id: p.id, name: p.name || '', emoji: guessEmoji(categorySlug, categoryName), image, price, oldPrice, unit, origin, badge, category: categoryName, categorySlug };
        }

        async function loadMartData() {
            const [categoriesRes, productsRes] = await Promise.all([
                fetch('/api/categories?market=mart', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' }),
                fetch('/api/products?market=mart&per_page=1000&sort_by=created_at&sort_order=desc', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' }),
            ]);
            const categoriesPayload = await categoriesRes.json().catch(() => ({ data: [] }));
            const productsPayload = await productsRes.json().catch(() => ({ data: [] }));
            const apiCategories = Array.isArray(categoriesPayload.data) ? categoriesPayload.data : [];
            const apiProducts = Array.isArray(productsPayload.data) ? productsPayload.data : [];
            categories = [{ id: 'all', name: 'الكل', emoji: '' }].concat(
                apiCategories.map((c) => ({ id: c.slug || String(c.id), name: c.name || (c.slug || String(c.id)), emoji: guessEmoji(c.slug, c.name) }))
            );
            products = {};
            apiProducts.map(normalizeApiProduct).forEach((p) => {
                const key = p.categorySlug || 'uncategorized';
                if (!products[key]) products[key] = [];
                products[key].push(p);
            });
        }

        // State
        let currentView = 'grid';
        let currentPage = 1;
        const itemsPerPage = 12;
        let filteredProducts = [];
        let allProducts = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', async () => {
            await loadMartData();
            await loadFavorites();
            allProducts = Object.values(products).flat();
            filteredProducts = [...allProducts];
            window.martProducts = products;
            window.martCategories = categories;
            loadCategories();
            loadOrigins();
            applyURLFilters();
            applyFilters();
            initMartSearch();
        });

        function loadCategories() {
            const categoryList = document.getElementById('categoryList');
            const mCategoryList = document.getElementById('m-categoryList');
            const html = categories.map(cat => {
                const count = cat.id === 'all' ? allProducts.length : (products[cat.id]?.length || 0);
                return `
                    <div class="category-item" data-category="${cat.id}" onclick="selectCategory('${cat.id}')">
                        <span class="emoji">${cat.emoji}</span>
                        <span>${cat.name}</span>
                        <span class="count">${count}</span>
                    </div>
                `;
            }).join('');
            if (categoryList) categoryList.innerHTML = html;
            if (mCategoryList) mCategoryList.innerHTML = html;
        }

        function toggleMobileFilter(id) {
            const el = document.getElementById(id);
            const isOpen = el.classList.contains('open');
            document.querySelectorAll('.mobile-filter-item').forEach(item => item.classList.remove('open'));
            if (!isOpen) el.classList.add('open');
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.mobile-filter-item')) {
                document.querySelectorAll('.mobile-filter-item').forEach(item => item.classList.remove('open'));
            }
        });

        function syncFilters(type, value) {
            if (type === 'search') {
                document.getElementById('searchFilter').value = value;
                const mSearch = document.getElementById('m-searchFilter');
                if (mSearch) mSearch.value = value;
            } else if (type === 'minPrice') {
                document.getElementById('minPrice').value = value;
                const mMin = document.getElementById('m-minPrice');
                if (mMin) mMin.value = value;
            } else if (type === 'maxPrice') {
                document.getElementById('maxPrice').value = value;
                const mMax = document.getElementById('m-maxPrice');
                if (mMax) mMax.value = value;
            } else if (['sale', 'new', 'fresh'].includes(type)) {
                const key = type.charAt(0).toUpperCase() + type.slice(1);
                const desktop = document.getElementById('filter' + key);
                const mobile = document.getElementById('m-filter' + key);
                if (desktop) desktop.checked = value;
                if (mobile) mobile.checked = value;
            }
            applyFilters();
        }

        function applyFilters() {
            let filtered = [...allProducts];
            const activeCategory = document.querySelector('.category-item.active')?.dataset.category || 'all';
            if (activeCategory !== 'all') filtered = products[activeCategory] || [];

            const searchTerm = (
                document.getElementById('searchFilter')?.value ||
                document.getElementById('m-searchFilter')?.value || ''
            ).toLowerCase().trim();
            if (searchTerm) {
                filtered = filtered.filter(p =>
                    p.name.toLowerCase().includes(searchTerm) ||
                    p.category.toLowerCase().includes(searchTerm) ||
                    p.origin.toLowerCase().includes(searchTerm)
                );
            }

            const minPrice = parseFloat(document.getElementById('minPrice')?.value || document.getElementById('m-minPrice')?.value || 0) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice')?.value || document.getElementById('m-maxPrice')?.value || Infinity) || Infinity;
            filtered = filtered.filter(p => p.price >= minPrice && p.price <= maxPrice);

            const saleFilter = (document.getElementById('filterSale')?.checked || document.getElementById('m-filterSale')?.checked);
            const newFilter = (document.getElementById('filterNew')?.checked || document.getElementById('m-filterNew')?.checked);
            const freshFilter = (document.getElementById('filterFresh')?.checked || document.getElementById('m-filterFresh')?.checked);

            if (saleFilter || newFilter || freshFilter) {
                filtered = filtered.filter(p => {
                    if (saleFilter && p.badge === 'sale') return true;
                    if (newFilter && p.badge === 'new') return true;
                    if (freshFilter && p.badge === 'fresh') return true;
                    return false;
                });
            }

            const sortBy = document.getElementById('sortSelect').value;
            switch (sortBy) {
                case 'price-asc': filtered.sort((a, b) => a.price - b.price); break;
                case 'price-desc': filtered.sort((a, b) => b.price - a.price); break;
                case 'name-asc': filtered.sort((a, b) => a.name.localeCompare(b.name, 'ar')); break;
                case 'name-desc': filtered.sort((a, b) => b.name.localeCompare(a.name, 'ar')); break;
            }

            filteredProducts = filtered;
            currentPage = 1;
            displayProducts();
            updateActiveFilters();
            updateMobileFilterIndicators();
        }

        function updateMobileFilterIndicators() {
            const searchVal = document.getElementById('m-searchFilter')?.value || '';
            document.querySelector('#mobileSearchFilter .mobile-filter-toggle')?.classList.toggle('active', searchVal.length > 0);
            const activeCategory = document.querySelector('.category-item.active')?.dataset.category || 'all';
            document.querySelector('#mobileCategoryFilter .mobile-filter-toggle')?.classList.toggle('active', activeCategory !== 'all');
            const min = document.getElementById('m-minPrice')?.value || '';
            const max = document.getElementById('m-maxPrice')?.value || '';
            document.querySelector('#mobilePriceFilter .mobile-filter-toggle')?.classList.toggle('active', min.length > 0 || max.length > 0);
            const sale = document.getElementById('m-filterSale')?.checked;
            const isNew = document.getElementById('m-filterNew')?.checked;
            const fresh = document.getElementById('m-filterFresh')?.checked;
            document.querySelector('#mobileTypeFilter .mobile-filter-toggle')?.classList.toggle('active', sale || isNew || fresh);
        }

        function loadOrigins() {
            const origins = [...new Set(allProducts.map(p => p.origin))];
            const originList = document.getElementById('originList');
            originList.innerHTML = origins.map(origin => `
                <label class="checkbox-item">
                    <input type="checkbox" id="origin-${origin}" onchange="applyFilters()">
                    <label for="origin-${origin}">${origin}</label>
                </label>
            `).join('');
        }

        function applyURLFilters() {
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category');
            if (category) selectCategory(category);
            const search = urlParams.get('search');
            if (search) document.getElementById('searchFilter').value = search;
            const filter = urlParams.get('filter');
            if (filter === 'fresh') document.getElementById('filterFresh').checked = true;
        }

        function selectCategory(categoryId) {
            document.querySelectorAll('.category-item').forEach(item => item.classList.remove('active'));
            document.querySelectorAll(`[data-category="${categoryId}"]`).forEach(el => el.classList.add('active'));
            applyFilters();
        }

        function displayProducts() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageProducts = filteredProducts.slice(startIndex, endIndex);
            const productsGrid = document.getElementById('productsGrid');

            if (pageProducts.length === 0) {
                productsGrid.innerHTML = `
                    <div class="no-results" style="grid-column: 1 / -1;">
                        <i class="fas fa-search"></i>
                        <h3>لا توجد منتجات</h3>
                        <p>جرب تغيير الفلاتر أو البحث عن شيء آخر</p>
                    </div>
                `;
            } else {
                productsGrid.innerHTML = pageProducts.map(p => createProductCard(p)).join('');
            }

            document.getElementById('resultsCount').textContent = filteredProducts.length;
            displayPagination();
        }

        function createProductCard(p) {
            const fav = favoriteIds.has(String(p.id));
            return `
                <div class="product-card" data-id="${p.id}">
                    <div class="product-badges">
                        ${p.badge === 'sale' ? '<span class="badge badge-sale">عرض</span>' : ''}
                        ${p.badge === 'new' ? '<span class="badge badge-new">جديد</span>' : ''}
                        ${p.badge === 'fresh' ? '<span class="badge badge-fresh">طازج</span>' : ''}
                    </div>
                    <div class="product-image">
                        <button class="product-favorite" onclick="toggleFavorite('${p.id}', this)">
                            <i class="${fav ? 'fas' : 'far'} fa-heart"></i>
                        </button>
                        <img src="${p.image}" alt="${escapeHtml(p.name)}" loading="lazy" onerror="this.src='/images/panner_mart.png';">
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
                                <span class="price-current">${window.formatMoney ? window.formatMoney(p.price) : (p.price + ' ل.س')}</span>
                                ${p.oldPrice ? `<span class="price-old">${window.formatMoney ? window.formatMoney(p.oldPrice) : (p.oldPrice + ' ل.س')}</span>` : ''}
                                <span class="price-unit">لكل 1 كغ</span>
                            </div>
                            <button class="add-cart-btn" onclick="addToCart('${p.id}', this)" id="btn-${p.id}">
                                <i class="fas fa-plus"></i>
                                أضف
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function displayPagination() {
            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
            const pagination = document.getElementById('pagination');
            if (totalPages <= 1) { pagination.innerHTML = ''; return; }

            let html = '';
            if (currentPage > 1) html += `<button class="page-btn" onclick="goToPage(${currentPage - 1})"><i class="fas fa-chevron-right"></i></button>`;

            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            if (startPage > 1) {
                html += `<button class="page-btn" onclick="goToPage(1)">1</button>`;
                if (startPage > 2) html += `<span style="padding:0 0.5rem;color:var(--muted)">...</span>`;
            }
            for (let i = startPage; i <= endPage; i++) {
                html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += `<span style="padding:0 0.5rem;color:var(--muted)">...</span>`;
                html += `<button class="page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
            }
            if (currentPage < totalPages) html += `<button class="page-btn" onclick="goToPage(${currentPage + 1})"><i class="fas fa-chevron-left"></i></button>`;

            pagination.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            displayProducts();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateActiveFilters() {
            const activeFilters = document.getElementById('activeFilters');
            const filters = [];

            const activeCategory = document.querySelector('.category-item.active');
            if (activeCategory && activeCategory.dataset.category !== 'all') {
                filters.push({ type: 'category', label: activeCategory.textContent.trim().split('\n')[0], value: activeCategory.dataset.category });
            }

            const searchTerm = (document.getElementById('searchFilter')?.value || document.getElementById('m-searchFilter')?.value || '').trim();
            if (searchTerm) filters.push({ type: 'search', label: `البحث: ${searchTerm}`, value: searchTerm });

            const minPrice = document.getElementById('minPrice')?.value || document.getElementById('m-minPrice')?.value;
            const maxPrice = document.getElementById('maxPrice')?.value || document.getElementById('m-maxPrice')?.value;
            if (minPrice || maxPrice) filters.push({ type: 'price', label: `السعر: ${minPrice || '0'} - ${maxPrice || '∞'} ل.س`, value: 'price' });

            if (document.getElementById('filterSale')?.checked || document.getElementById('m-filterSale')?.checked)
                filters.push({ type: 'badge', label: '🏷️ عروض', value: 'sale' });
            if (document.getElementById('filterNew')?.checked || document.getElementById('m-filterNew')?.checked)
                filters.push({ type: 'badge', label: '✨ جديد', value: 'new' });
            if (document.getElementById('filterFresh')?.checked || document.getElementById('m-filterFresh')?.checked)
                filters.push({ type: 'badge', label: '🌿 طازج', value: 'fresh' });

            activeFilters.innerHTML = filters.map(filter => `
                <div class="filter-tag">
                    ${filter.label}
                    <button onclick="removeFilter('${filter.type}', '${filter.value}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }

        function removeFilter(type, value) {
            switch (type) {
                case 'category': selectCategory('all'); break;
                case 'search': syncFilters('search', ''); break;
                case 'price': syncFilters('minPrice', ''); syncFilters('maxPrice', ''); break;
                case 'badge': syncFilters(value, false); break;
            }
            applyFilters();
        }

        function clearAllFilters() {
            selectCategory('all');
            syncFilters('search', '');
            syncFilters('minPrice', '');
            syncFilters('maxPrice', '');
            syncFilters('sale', false);
            syncFilters('new', false);
            syncFilters('fresh', false);
            document.getElementById('sortSelect').value = 'default';
            document.querySelectorAll('#originList input').forEach(cb => cb.checked = false);
            applyFilters();
        }

        function setView(view) {
            currentView = view;
            const productsGrid = document.getElementById('productsGrid');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');
            if (view === 'grid') {
                productsGrid.classList.remove('list-view');
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
            } else {
                productsGrid.classList.add('list-view');
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
            }
        }

        /* ===== toggleFilters: يعمل كـ drawer على 480px ===== */
        function toggleFilters() {
            const sidebar = document.getElementById('filtersSidebar');
            const overlay = document.getElementById('filtersOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');

            /* منع تمرير الصفحة خلف الـ drawer */
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        function toggleFavorite(productId, source) {
            const e = (source && source.preventDefault) ? source : (window.event || null);
            if (e && e.stopPropagation) e.stopPropagation();
            const id = String(productId);
            const btn = (source && source.closest) ? source : (e && e.target ? e.target.closest('.product-favorite') : null);
            const icon = btn ? btn.querySelector('i') : null;

            const setIcon = (isFav) => {
                if (!btn || !icon) return;
                btn.classList.toggle('active', isFav);
                icon.classList.toggle('far', !isFav);
                icon.classList.toggle('fas', isFav);
            };

            const product = allProducts.find(p => String(p.id) === id) || null;

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
                .then(d => {
                    if (!d || !d.success) {
                        const localState = persistLocalFavorite(id, product);
                        setIcon(localState);
                        return;
                    }
                    if (d.action === 'added') favoriteIds.add(id);
                    if (d.action === 'removed') favoriteIds.delete(id);
                    updateFavoritesCount(d.count || favoriteIds.size);
                    setIcon(favoriteIds.has(id));
                    if (window.showToast && product) {
                        window.showToast(d.action === 'added' ? `تمت إضافة ${product.name} للمفضلة` : `تمت إزالة ${product.name} من المفضلة`);
                    }
                })
                .catch(() => {
                    const localState = persistLocalFavorite(id, product);
                    setIcon(localState);
                });
                return;
            }

            const localState = persistLocalFavorite(id, product);
            setIcon(localState);
        }

        async function addToCart(productId, source) {
            const e = (source && source.preventDefault) ? source : (window.event || null);
            if (e && e.stopPropagation) e.stopPropagation();

            const btn = document.getElementById(`btn-${productId}`);
            const originalContent = btn.innerHTML;
            const product = allProducts.find(p => String(p.id) === String(productId));
            if (!product) return;

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
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        product_id: productId,
                        product_type: 'mart',
                        name: product.name,
                        price: product.price,
                        quantity: 1,
                        image: product.image,
                        unit: product.unit,
                        emoji: product.emoji
                    })
                });
                const data = await response.json();
                
                if (!response.ok) {
                    if (window.showToast) {
                        window.showToast(data.message || 'غير قادر على إضافة المنتج للسلة');
                    } else {
                        alert(data.message || 'غير قادر على إضافة المنتج للسلة');
                    }
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    return;
                }

                btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                btn.classList.add('added');
                if (window.updateCartCount) window.updateCartCount(data.count || 0);
                if (window.animateCartIcon) window.animateCartIcon();
                if (window.showToast) {
                    window.showToast('تمت إضافة ' + product.name + ' إلى السلة');
                    setTimeout(() => {
                        window.showToast('تنبيه: منتجات Mart تتوفر للتوصيل فقط إلى (السويداء، عتيل، قنوات)', 4000);
                    }, 1500);
                }
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.classList.remove('added');
                    btn.disabled = false;
                }, 2000);
            } catch (error) {
                console.error('Error adding to cart:', error);
                btn.innerHTML = '<i class="fas fa-times"></i> خطأ';
                setTimeout(() => { btn.innerHTML = originalContent; btn.disabled = false; }, 2000);
            }
        }

        function initMartSearch() {
            const searchInput = document.getElementById('searchFilter');
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => applyFilters(), 300);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const navSearchInput = document.getElementById('searchInput');
            if (navSearchInput) navSearchInput.placeholder = 'ابحث في توليب مارت...';
        });
    </script>
</body>
</html>