<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>جميع المنتجات - توليب مارت</title>
    
    <!-- fav icon -->
    <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
        <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">

    <link rel="stylesheet" href="{{ asset('css/store.min.css') }}?v={{ filemtime(public_path('css/store.min.css')) }}&t={{ time() }}" onerror="this.onerror=null;this.href='{{ asset('css/store.css') }}?fallback=1';">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Weight Modal Placeholder Functions -->
    <script>
        // Placeholder functions that will be overridden by the modal component
        window.openWeightModal = window.openWeightModal || function(productId) {
            console.log('openWeightModal placeholder called, waiting for modal to load...');
            // Retry after a short delay to allow modal to load
            setTimeout(() => {
                if (window.openWeightModal && window.openWeightModal.toString().includes('placeholder')) {
                    console.error('Weight modal not loaded yet');
                    alert('الرجاء الانتظار قليلاً وإعادة المحاولة');
                } else {
                    window.openWeightModal(productId);
                }
            }, 100);
        };
    </script>
    
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

    <div class="page-header">
        <div class="header-container">
            <div class="breadcrumb" id="breadcrumb">
                <a href="/">الرئيسية</a>
                <span>›</span>
                <a href="/mart">Tulip Mart</a>
                <span id="breadcrumbTail"></span>
            </div>
            <h1 class="page-title" id="pageTitle">منتجات المارت</h1>
            <p class="page-subtitle" id="pageSubtitle">اختر قسمًا ثم تصنيفًا فرعيًا لعرض المنتجات</p>
        </div>
    </div>

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
            position: relative;
            z-index: 1;
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
        .products-area {
            position: relative;
            z-index: 1;
        }
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
            position: relative;
            z-index: 1;
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
            /* عرض أفقي (list view): صورة أكبر + محتوى بجانبها */
            grid-template-columns: 240px 1fr;
            align-items: stretch;
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
        .products-grid.list-view .product-image {
            /* منع قص الصورة في العرض الأفقي */
            aspect-ratio: auto;
            height: 240px;
        }
        .products-grid.list-view .product-image img {
            object-fit: contain;
            background: #fff;
        }

        .products-grid.list-view .add-cart-btn{
            width: auto;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 18px;
            align-self: flex-start;
        }

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

        .add-btn-circle.weight-based {
            background: #f59e0b;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .add-btn-circle:hover {
            transform: scale(1.1);
            background: var(--teal-dark);
        }

        .add-btn-circle.weight-based:hover {
            background: #f97316;
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

        .counter-control.weight-based {
            background: #f59e0b;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
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
        .product-footer .add-cart-btn {
            display: none;
        }

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
            overflow-y: visible;
            padding: 0.5rem 0.2rem;
            scrollbar-width: none;
            margin-bottom: 1rem;
            -ms-overflow-style: none;
            position: relative;
            z-index: 100;
        }
        .mobile-filter-dropdowns::-webkit-scrollbar { display: none; }

        .mobile-filter-item {
            position: static;
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
            position: fixed;
            top: auto;
            right: 1rem;
            left: 1rem;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
            z-index: 200;
            min-width: auto;
            margin-top: 0.5rem;
            padding: 1rem;
            border: 1px solid #eee;
            max-height: 400px;
            overflow-y: auto;
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
            pointer-events: none;
        }
        .filters-overlay.active { 
            display: block;
            pointer-events: none;
        }

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
        @media (max-width: 556px) {

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
                overflow-x: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                z-index: 10000 !important;
                border-radius: 0 !important;
                transition: right 0.3s ease !important;
                box-shadow: -4px 0 24px rgba(0,0,0,0.18) !important;
                padding: 1.25rem !important;
                margin: 0 !important;
                position: fixed !important;
                pointer-events: auto !important;
                background: #ffffff !important;
                overscroll-behavior: contain !important;
            }

            /* عندما يكون الـ drawer مفتوح */
            .filters-sidebar.active {
                right: 0 !important;
                pointer-events: auto !important;
            }
            
            /* Ensure all sidebar children are clickable */
            .filters-sidebar *,
            .filters-sidebar input,
            .filters-sidebar button,
            .filters-sidebar select,
            .filters-sidebar .category-item,
            .filters-sidebar .checkbox-item,
            .filters-sidebar label {
                pointer-events: auto !important;
            }
            
            .filters-sidebar .category-item {
                cursor: pointer !important;
                touch-action: manipulation !important;
                -webkit-tap-highlight-color: rgba(0,0,0,0.1) !important;
            }
            
            /* Prevent body scroll when sidebar is open */
            body.sidebar-open {
                overflow: hidden !important;
                position: fixed !important;
                width: 100% !important;
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

            <!-- Subcategory Filter -->
            <div class="filter-group">
                <div class="filter-group-title">التصنيف الفرعي</div>
                <div class="category-list" id="subcategoryList"></div>
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

                    <div class="mobile-filter-item" id="mobileSubcategoryFilter">
                        <button class="mobile-filter-toggle" onclick="toggleMobileFilter('mobileSubcategoryFilter')">
                            <i class="fas fa-layer-group"></i> التصنيفات
                        </button>
                        <div class="mobile-filter-menu">
                            <div class="category-list" id="m-subcategoryList"></div>
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
        let martNavigation = [];
        let categories = [{ id: 'all', name: 'الكل', emoji: '' }];
        let subcategoriesByCategory = {};
        let selectedCategory = null;
        let selectedSubcategory = null;
        let isGlobalSearchMode = false;
        let globalSearchQuery = '';
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
            return '';
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
                    image: product?.image || '/images/tulip_mart.jpg',
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
            if (slug.includes('fruit') || slug.includes('veget') || name.includes('فوا') || name.includes('خضا') || name.includes('خضر')) return '/images/tulip_mart.jpg';
            if (slug.includes('dairy') || name.includes('ألبان') || name.includes('حليب')) return '/images/tulip_mart.jpg';
            if (slug.includes('bakery') || name.includes('مخب')) return '/images/tulip_mart.jpg';
            return '/images/tulip_mart.jpg';
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
            const getAttrValue = (keys) => {
                const wanted = new Set((Array.isArray(keys) ? keys : [keys]).map((k) => String(k || '').toLowerCase().trim()).filter(Boolean));
                for (const a of attrs) {
                    const n = String(a?.name || '').toLowerCase().trim();
                    const k = String(a?.attribute_key || '').toLowerCase().trim();
                    if (!n && !k) continue;
                    if (wanted.has(n) || wanted.has(k)) {
                        const v = a?.value ?? a?.value_text ?? a?.value_number ?? a?.value_date;
                        const s = String(v ?? '').trim();
                        if (s !== '' && s !== 'null' && s !== 'undefined') return s;
                    }
                }
                return '';
            };
            const unit = getAttrValue(['unit', 'units', 'الوحدة', 'وحدة', 'unit_name']) || String(p.unit || '').trim() || 'حبة';
            const origin = getAttrValue(['origin', 'country', 'source', 'المنشأ', 'بلد المنشأ', 'المصدر']) || String(p.origin || '').trim() || 'محلي';
            const firstImage = Array.isArray(p.images) ? (p.images[0]?.path || p.images[0]?.url || p.images[0]) : null;
            const imageSource = p.primary_image_url || firstImage || p.image || p.photo || '';
            const image = resolveProductImage(imageSource) || martFallbackImage(categorySlug, categoryName);
            let badge = '';
            if (p.discount_price) badge = 'sale';
            else if (String(origin).includes('محلي')) badge = 'fresh';
            else if (p.is_featured) badge = 'new';
            return {
                id: p.id,
                name: p.name || '',
                emoji: guessEmoji(categorySlug, categoryName),
                image,
                price,
                oldPrice,
                unit,
                origin,
                badge,
                category: categoryName,
                categorySlug,
                subcategory: p.subcategory?.name || '',
                subcategorySlug: p.subcategory?.slug || '',
                attributes: attrs,  // Include attributes for modal
                primary_image_url: p.primary_image_url,  // Include for modal
            };
        }

        async function loadMartNavigation() {
            const res = await fetch('/api/mart/navigation', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            const payload = await res.json().catch(() => ({ data: [] }));
            const raw = Array.isArray(payload.data) ? payload.data : [];
            const data = raw.filter((c) => {
                const slug = String(c?.slug || '').trim();
                const subs = Array.isArray(c?.subcategories) ? c.subcategories : [];
                return slug !== '' && subs.length > 0;
            });
            martNavigation = data;
            categories = [{ id: 'all', name: 'الكل', emoji: '' }].concat(
                data.map((c) => ({
                    id: c.slug || String(c.id),
                    apiId: c.id,
                    name: c.name || (c.slug || String(c.id)),
                    emoji: guessEmoji(c.slug, c.name),
                    subcategories: Array.isArray(c.subcategories) ? c.subcategories : [],
                }))
            );
            subcategoriesByCategory = {};
            categories.forEach((c) => {
                if (c.id === 'all') return;
                subcategoriesByCategory[String(c.id)] = Array.isArray(c.subcategories) ? c.subcategories : [];
            });
        }

        async function loadProductsForSelectedSubcategory() {
            const productsGrid = document.getElementById('productsGrid');
            if (!selectedCategory) {
                allProducts = [];
                filteredProducts = [];
                displayProducts();
                loadOrigins();
                updateBreadcrumb();
                return;
            }

            productsGrid.innerHTML = `
                <div class="no-results" style="grid-column: 1 / -1;">
                    <i class="fas fa-spinner fa-spin"></i>
                    <h3>جارٍ تحميل المنتجات</h3>
                    <p>يرجى الانتظار...</p>
                </div>
            `;

            // المطلوب:
            // - "الكل": يعرض كل منتجات المارت بدون فلترة
            // - اختيار قسم: يعرض كل منتجات هذا القسم (بدون الحاجة لاختيار تصنيف فرعي)
            // - اختيار تصنيف فرعي: يفلتر أكثر داخل القسم
            const url = new URL('/api/products', window.location.origin);
            url.searchParams.set('market', 'mart');
            // Performance: avoid loading huge payloads at once
            url.searchParams.set('per_page', '200');
            url.searchParams.set('sort_by', 'created_at');
            url.searchParams.set('sort_order', 'desc');

            if (selectedCategory.id !== 'all') {
                // Prefer numeric id filter (faster/more reliable)
                if (selectedCategory.apiId) {
                    url.searchParams.set('category_id', String(selectedCategory.apiId));
                } else {
                    url.searchParams.set('category', String(selectedCategory.id));
                }
            }
            if (selectedSubcategory) {
                if (selectedSubcategory.apiId) {
                    url.searchParams.set('subcategory_id', String(selectedSubcategory.apiId));
                } else {
                    url.searchParams.set('subcategory', String(selectedSubcategory.id));
                }
            }

            const r = await fetch(url.toString(), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            const d = await r.json().catch(() => ({}));
            const items = Array.isArray(d?.data) ? d.data : [];
            allProducts = items.map(normalizeApiProduct);
            window.martProductsList = allProducts; // Store globally for weight modal
            filteredProducts = [...allProducts];
            currentPage = 1;
            loadOrigins();
            applyFilters();
            updateBreadcrumb();
        }

        async function loadProductsForGlobalSearch(query) {
            const productsGrid = document.getElementById('productsGrid');
            const q = String(query || '').trim();
            if (q.length < 2) {
                isGlobalSearchMode = false;
                globalSearchQuery = '';
                allProducts = [];
                filteredProducts = [];
                currentPage = 1;
                loadOrigins();
                displayProducts();
                updateBreadcrumb();
                return;
            }

            isGlobalSearchMode = true;
            globalSearchQuery = q;
            selectedSubcategory = null;

            if (productsGrid) {
                productsGrid.innerHTML = `
                    <div class="no-results" style="grid-column: 1 / -1;">
                        <i class="fas fa-spinner fa-spin"></i>
                        <h3>جارٍ البحث في توليب مارت</h3>
                        <p>يرجى الانتظار...</p>
                    </div>
                `;
            }

            const url = new URL('/api/products', window.location.origin);
            url.searchParams.set('market', 'mart');
            url.searchParams.set('per_page', '200');
            url.searchParams.set('sort_by', 'created_at');
            url.searchParams.set('sort_order', 'desc');
            url.searchParams.set('search', q);

            const r = await fetch(url.toString(), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            const d = await r.json().catch(() => ({}));
            const items = Array.isArray(d?.data) ? d.data : [];
            allProducts = items.map(normalizeApiProduct);
            window.martProductsList = allProducts; // Store globally for weight modal
            filteredProducts = [...allProducts];
            currentPage = 1;
            loadOrigins();
            applyFilters();
            updateBreadcrumb();
        }

        // State
        let currentView = 'grid';
        let currentPage = 1;
        const itemsPerPage = 12;
        let filteredProducts = [];
        let allProducts = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', async () => {
            await loadMartNavigation();
            await loadFavorites();
            window.martNavigation = martNavigation;
            window.martCategories = categories;
            loadCategories();
            renderSubcategories();
            loadOrigins();
            const handledGlobalSearch = applyURLFilters();
            if (!handledGlobalSearch) {
                // Default to "all" so page shows products without requiring subcategory selection
                if (!selectedCategory) {
                    selectCategory('all');
                } else {
                    loadProductsForSelectedSubcategory();
                }
            }
            initMartSearch();
        });

        function loadCategories() {
            const categoryList = document.getElementById('categoryList');
            const mCategoryList = document.getElementById('m-categoryList');
            const totalAll = Object.values(subcategoriesByCategory)
                .flat()
                .reduce((sum, s) => sum + Number(s?.products_count || 0), 0);
            const html = categories.map(cat => {
                const subs = subcategoriesByCategory[String(cat.id)] || [];
                const count = cat.id === 'all' ? totalAll : subs.reduce((sum, s) => sum + Number(s.products_count || 0), 0);
                return `
                    <div class="category-item" data-category="${cat.id}" onclick="selectCategory('${cat.id}')">
                        <span class="emoji">${cat.emoji}</span>
                        <span>${cat.name}</span>
                    </div>
                `;
            }).join('');
            if (categoryList) categoryList.innerHTML = html;
            if (mCategoryList) mCategoryList.innerHTML = html;
        }

        function renderSubcategories() {
            const subList = document.getElementById('subcategoryList');
            const mSubList = document.getElementById('m-subcategoryList');

            const subs = selectedCategory ? (subcategoriesByCategory[String(selectedCategory.id)] || []) : [];
            const html = subs.length
                ? subs.map((s) => `
                    <div class="category-item" data-subcategory="${s.slug || String(s.id)}" onclick="selectSubcategory('${s.slug || String(s.id)}')">
                        <span class="emoji"> </span>
                        <span>${escapeHtml(s.name || '')}</span>
                    </div>
                `).join('')
                : `<div style="color: var(--muted); font-size: 0.9rem;">اختر قسمًا لعرض التصنيفات الفرعية</div>`;

            if (subList) subList.innerHTML = html;
            if (mSubList) mSubList.innerHTML = html;
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

            const selectedOrigins = new Set(
                Array.from(document.querySelectorAll('#originList input[type="checkbox"]:checked')).map((x) => String(x.value || x.getAttribute('data-origin') || '')).filter(Boolean)
            );
            if (selectedOrigins.size > 0) {
                filtered = filtered.filter((p) => selectedOrigins.has(String(p.origin || '')));
            }

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
            updateBreadcrumb();
        }

        function updateMobileFilterIndicators() {
            const searchVal = document.getElementById('m-searchFilter')?.value || '';
            document.querySelector('#mobileSearchFilter .mobile-filter-toggle')?.classList.toggle('active', searchVal.length > 0);
            document.querySelector('#mobileCategoryFilter .mobile-filter-toggle')?.classList.toggle('active', !!selectedCategory && selectedCategory.id !== 'all');
            document.querySelector('#mobileSubcategoryFilter .mobile-filter-toggle')?.classList.toggle('active', !!selectedSubcategory);
            const min = document.getElementById('m-minPrice')?.value || '';
            const max = document.getElementById('m-maxPrice')?.value || '';
            document.querySelector('#mobilePriceFilter .mobile-filter-toggle')?.classList.toggle('active', min.length > 0 || max.length > 0);
            const sale = document.getElementById('m-filterSale')?.checked;
            const isNew = document.getElementById('m-filterNew')?.checked;
            const fresh = document.getElementById('m-filterFresh')?.checked;
            document.querySelector('#mobileTypeFilter .mobile-filter-toggle')?.classList.toggle('active', sale || isNew || fresh);
        }

        function loadOrigins() {
            const origins = [...new Set(allProducts.map(p => p.origin).filter(Boolean))];
            const originList = document.getElementById('originList');
            originList.innerHTML = origins.length
                ? origins.map((origin) => {
                    const safe = escapeHtml(origin);
                    const id = `origin-${btoa(unescape(encodeURIComponent(origin))).replace(/=+/g, '')}`;
                    return `
                        <label class="checkbox-item">
                            <input type="checkbox" id="${id}" value="${safe}" data-origin="${safe}" onchange="applyFilters()">
                            <label for="${id}">${safe}</label>
                        </label>
                    `;
                }).join('')
                : `<div style="color: var(--muted); font-size: 0.9rem;">${isGlobalSearchMode ? 'لا توجد خيارات مصدر متاحة' : 'لا توجد خيارات مصدر متاحة'}</div>`;
        }

        function applyURLFilters() {
            const urlParams = new URLSearchParams(window.location.search);
            const search = urlParams.get('search');
            if (search) {
                const term = String(search || '').trim();
                if (term) {
                    const s = document.getElementById('searchFilter');
                    const ms = document.getElementById('m-searchFilter');
                    if (s) s.value = term;
                    if (ms) ms.value = term;
                    const allEls = document.querySelectorAll(`[data-category="all"]`);
                    allEls.forEach((el) => el.classList.add('active'));
                    selectedCategory = categories.find((c) => String(c.id) === 'all') || categories[0] || null;
                    selectedSubcategory = null;
                    loadProductsForGlobalSearch(term);
                }
                return true;
            }
            const category = urlParams.get('category');
            if (category) selectCategory(category);
            const subcategory = urlParams.get('subcategory');
            if (subcategory) {
                selectSubcategory(subcategory);
            }
            const filter = urlParams.get('filter');
            if (filter === 'fresh') document.getElementById('filterFresh').checked = true;
            return false;
        }

        function selectCategory(categoryId) {
            isGlobalSearchMode = false;
            globalSearchQuery = '';
            document.querySelectorAll('.category-item').forEach(item => item.classList.remove('active'));
            document.querySelectorAll(`[data-category="${categoryId}"]`).forEach(el => el.classList.add('active'));
            selectedCategory = categories.find((c) => String(c.id) === String(categoryId)) || null;
            selectedSubcategory = null;
            renderSubcategories();
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            if (categoryId && categoryId !== 'all') {
                url.searchParams.set('category', String(categoryId));
            } else {
                url.searchParams.delete('category');
            }
            url.searchParams.delete('subcategory');
            window.history.replaceState({}, '', url.toString());
            loadProductsForSelectedSubcategory();
        }

        function selectSubcategory(subcategoryId) {
            if (!selectedCategory) {
                return;
            }
            isGlobalSearchMode = false;
            globalSearchQuery = '';
            document.querySelectorAll('[data-subcategory]').forEach((item) => item.classList.remove('active'));
            document.querySelectorAll(`[data-subcategory="${subcategoryId}"]`).forEach((el) => el.classList.add('active'));
            const subs = subcategoriesByCategory[String(selectedCategory.id)] || [];
            const found = subs.find((s) => String(s.slug || s.id) === String(subcategoryId));
            selectedSubcategory = found
                ? { id: (found.slug || String(found.id)), apiId: found.id, name: found.name || '' }
                : { id: String(subcategoryId), name: '' };
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.set('category', String(selectedCategory.id));
            url.searchParams.set('subcategory', String(selectedSubcategory.id));
            window.history.replaceState({}, '', url.toString());
            loadProductsForSelectedSubcategory();
        }

        function displayProducts() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageProducts = filteredProducts.slice(startIndex, endIndex);
            const productsGrid = document.getElementById('productsGrid');

            if (pageProducts.length === 0) {
                productsGrid.innerHTML = `
                    <div class="no-results" style="grid-column: 1 / -1;">
                        <i class="fas ${isGlobalSearchMode ? 'fa-search' : 'fa-search'}"></i>
                        <h3>لا توجد نتائج</h3>
                        <p>${isGlobalSearchMode ? 'جرّب كلمة بحث أخرى' : 'جرب تغيير الفلاتر أو البحث عن شيء آخر'}</p>
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
            
            // Check if product is weight-based
            const unit = p.unit || '';
            const unitLower = unit.toLowerCase().trim();
            
            // Debug logging
            console.log('Product:', p.name, 'Unit:', unit, 'Unit Lower:', unitLower);
            
            const isWeightBased = unitLower === 'kilogram' || unitLower === 'gram' || 
                                  unitLower === 'كيلو' || unitLower === 'كيلوغرام' || 
                                  unitLower === 'غرام' || unitLower === 'kg' || unitLower === 'g' ||
                                  unitLower.includes('كيلو') || unitLower.includes('غرام');
            
            console.log('Is Weight Based:', isWeightBased);
            
            // For weight-based products, always display as "per kilogram"
            let displayPrice = p.price;
            let displayUnit = p.unit;
            
            if (isWeightBased) {
                displayUnit = 'كيلو غرام';
            }
            
            const buttonClass = isWeightBased ? 'add-btn-circle weight-based' : 'add-btn-circle';
            const buttonIcon = isWeightBased ? 'fa-balance-scale' : 'fa-plus';
            
            // Generate button HTML differently for weight-based products
            const buttonHTML = isWeightBased 
                ? `<button class="${buttonClass}" onclick="window.openWeightModal('${p.id}')" id="add-btn-${p.id}">
                       <i class="fas ${buttonIcon}"></i>
                   </button>`
                : `<button class="${buttonClass}" onclick="initCartCounter('${p.id}', event)" id="add-btn-${p.id}">
                       <i class="fas ${buttonIcon}"></i>
                   </button>`;
            
            // Build counter control HTML (only for non-weight-based products)
            const counterHTML = !isWeightBased ? `
                <div class="counter-control" id="counter-${p.id}">
                    <button class="counter-btn" onclick="updateQuantity('${p.id}', -1, event)">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="counter-value" id="count-${p.id}">1</span>
                    <button class="counter-btn" onclick="updateQuantity('${p.id}', 1, event)">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            ` : '';
            
            return `
                <div class="product-card" data-id="${p.id}">
                    <div class="product-badges">
                        ${p.badge === 'sale' ? '<span class="badge badge-sale">عرض</span>' : ''}
                        ${p.badge === 'new' ? '<span class="badge badge-new">جديد</span>' : ''}
                    </div>
                    <div class="product-image">
                        <button class="product-favorite" onclick="toggleFavorite('${p.id}', this)">
                            <i class="${fav ? 'fas' : 'far'} fa-heart"></i>
                        </button>
                        <img src="${p.image}" alt="${escapeHtml(p.name)}" loading="lazy" onerror="this.src='/images/tulip_mart.jpg';">
                        
                        <!-- Floating Cart Control -->
                        <div class="cart-control-wrapper" id="cart-wrapper-${p.id}" onclick="event.stopPropagation()">
                            ${buttonHTML}
                            ${counterHTML}
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
                                <span class="price-current">${window.formatMoney ? window.formatMoney(displayPrice) : (displayPrice + ' ل.س')}</span>
                                ${p.oldPrice ? `<span class="price-old">${window.formatMoney ? window.formatMoney(p.oldPrice) : (p.oldPrice + ' ل.س')}</span>` : ''}
                                <span class="price-unit">${displayUnit ? `لكل ${displayUnit}` : ''}</span>
                            </div>
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

            if (selectedCategory && selectedCategory.id !== 'all') {
                filters.push({ type: 'category', label: selectedCategory.name, value: selectedCategory.id });
            }
            if (selectedSubcategory && selectedSubcategory.id) {
                filters.push({ type: 'subcategory', label: selectedSubcategory.name || selectedSubcategory.id, value: selectedSubcategory.id });
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
                case 'subcategory':
                    selectedSubcategory = null;
                    document.querySelectorAll('[data-subcategory]').forEach((item) => item.classList.remove('active'));
                    const url = new URL(window.location.href);
                    url.searchParams.delete('subcategory');
                    window.history.replaceState({}, '', url.toString());
                    loadProductsForSelectedSubcategory();
                    break;
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

        function updateBreadcrumb() {
            const tail = document.getElementById('breadcrumbTail');
            const title = document.getElementById('pageTitle');
            const subtitle = document.getElementById('pageSubtitle');
            if (!tail || !title || !subtitle) return;

            if (isGlobalSearchMode) {
                tail.innerHTML = `<span>›</span><span>نتائج البحث</span>`;
                title.textContent = 'نتائج البحث';
                subtitle.textContent = globalSearchQuery ? `بحث عن: ${globalSearchQuery}` : 'عرض نتائج البحث في توليب مارت';
                return;
            }

            const parts = [];
            if (selectedCategory && selectedCategory.id !== 'all') {
                parts.push(`<span>›</span><a href="/mart/products?category=${encodeURIComponent(String(selectedCategory.id))}">${escapeHtml(selectedCategory.name || '')}</a>`);
            }
            if (selectedSubcategory && selectedSubcategory.id) {
                parts.push(`<span>›</span><span>${escapeHtml(selectedSubcategory.name || '')}</span>`);
            }
            tail.innerHTML = parts.join('');

            if (!selectedCategory || selectedCategory.id === 'all') {
                title.textContent = 'منتجات المارت';
                subtitle.textContent = 'اختر قسمًا ثم تصنيفًا فرعيًا لعرض المنتجات';
                return;
            }
            if (selectedCategory && !selectedSubcategory) {
                title.textContent = selectedCategory.name || 'منتجات المارت';
                subtitle.textContent = 'اختر تصنيفًا فرعيًا لعرض المنتجات';
                return;
            }
            title.textContent = selectedSubcategory?.name ? `${selectedCategory.name} - ${selectedSubcategory.name}` : (selectedCategory.name || 'منتجات المارت');
            subtitle.textContent = `عرض المنتجات ضمن ${selectedCategory.name || ''}${selectedSubcategory?.name ? ' / ' + selectedSubcategory.name : ''}`;
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
                document.body.classList.add('sidebar-open');
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
            } else {
                document.body.classList.remove('sidebar-open');
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.width = '';
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

        async function initCartCounter(productId, event) {
            if (event) event.stopPropagation();
            
            const addBtn = document.getElementById(`add-btn-${productId}`);
            const counter = document.getElementById(`counter-${productId}`);
            const wrapper = document.getElementById(`cart-wrapper-${productId}`);
            
            if (!addBtn || !counter || !wrapper) return;

            const ok = await addToCart(productId, 1);
            if (!ok) return;

            addBtn.style.display = 'none';
            counter.classList.add('active');
            document.getElementById(`count-${productId}`).textContent = '1';
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
                    const errData = await response.json().catch(() => ({}));
                    if (window.showToast) window.showToast(errData.message || 'غير قادر على تحديث السلة');
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
            const e = (event && event.preventDefault) ? event : (window.event || null);
            if (e && e.stopPropagation) e.stopPropagation();

            const product = allProducts.find(p => String(p.id) === String(productId));
            if (!product) {
                if (window.showToast) window.showToast('المنتج غير موجود ضمن النتائج الحالية', 'error');
                return false;
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
                        image: product.image,
                        unit: product.unit,
                        emoji: product.emoji
                    })
                });

                if (!response.ok) {
                    const errData = await response.json().catch(() => ({}));
                    if (window.showToast) {
                        window.showToast(errData.message || 'غير قادر على تحديث السلة');
                    }
                    return false;
                }

                const data = await response.json();
                
                // Update cart count
                if (window.updateCartCount) window.updateCartCount(data.count || 0);
                if (window.animateCartIcon) window.animateCartIcon();

                if (quantity > 0 && window.showToast) {
                    window.showToast('تم تحديث ' + product.name + ' في السلة');
                }
                return true;
            } catch (error) {
                console.error('Error updating cart:', error);
                return false;
            }
        }

        function initMartSearch() {
            const searchInput = document.getElementById('searchFilter');
            const mobileSearchInput = document.getElementById('m-searchFilter');
            let searchTimeout;
            const handle = (value) => {
                const q = String(value || '').trim();
                const useGlobalSearch = (!selectedSubcategory) && (!selectedCategory || selectedCategory.id === 'all');
                if (useGlobalSearch) {
                    const url = new URL(window.location.href);
                    if (q.length >= 2) {
                        url.searchParams.set('search', q);
                        url.searchParams.delete('category');
                        url.searchParams.delete('subcategory');
                        window.history.replaceState({}, '', url.toString());
                        loadProductsForGlobalSearch(q);
                        return;
                    }
                    url.searchParams.delete('search');
                    window.history.replaceState({}, '', url.toString());
                    isGlobalSearchMode = false;
                    globalSearchQuery = '';
                    // Return to normal listing (all products)
                    selectedSubcategory = null;
                    loadProductsForSelectedSubcategory();
                    return;
                }
                applyFilters();
            };

            const onInput = function () {
                clearTimeout(searchTimeout);
                const v = this.value;
                searchTimeout = setTimeout(() => handle(v), 350);
            };

            if (searchInput) searchInput.addEventListener('input', onInput);
            if (mobileSearchInput) mobileSearchInput.addEventListener('input', onInput);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const navSearchInput = document.getElementById('searchInput');
            if (navSearchInput) navSearchInput.placeholder = 'ابحث في توليب مارت...';
            
            // Store products list globally for weight modal
            window.martProductsList = allProducts;
        });
    </script>
    
    @include('components.weight-modal')
    
    <script>
        // Verify weight modal functions are loaded
        console.log('Checking weight modal functions...');
        console.log('openWeightModal exists:', typeof window.openWeightModal);
        console.log('closeWeightModal exists:', typeof window.closeWeightModal);
        console.log('calculateWeight exists:', typeof window.calculateWeight);
        console.log('addWeightBasedToCart exists:', typeof window.addWeightBasedToCart);
    </script>
</body>
</html>
