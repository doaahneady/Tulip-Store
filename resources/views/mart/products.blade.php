<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>جميع المنتجات - توليب مارت</title>
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
            --teal: #0d9488;
            --teal-light: #14b8a6;
            --teal-dark: #0f766e;
            --orange: #f97316;
            --yellow: #fbbf24;
            --green: #22c55e;
            --red: #ef4444;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', sans-serif; background: var(--bg); min-height: 100vh; }

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
</style>
<style>
        /* Main Layout */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
        }

        /* Sidebar Filters */
        .filters-sidebar {
            background: var(--card);
            border-radius: 16px;
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 100px;
            border: 1px solid var(--border);
        }
        .filters-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
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
        .clear-filters {
            color: var(--red);
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
        }
        .clear-filters:hover { text-decoration: underline; }

        .filter-group { margin-bottom: 1.5rem; }
        .filter-group-title {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
        }

        /* Search Filter */
        .filter-search {
            position: relative;
        }
        .filter-search input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .filter-search input:focus {
            outline: none;
            border-color: var(--teal);
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
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .category-item:hover { background: #f1f5f9; }
        .category-item.active {
            background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
            border-color: var(--teal);
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
</style>
<style>
        /* Price Range Filter */
        .price-range {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .price-input {
            flex: 1;
            padding: 0.6rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.85rem;
            text-align: center;
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
        }
        .checkbox-item label { cursor: pointer; font-size: 0.9rem; }

        /* Sort Dropdown */
        .sort-select {
            padding: 0.7rem 1rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.9rem;
            background: #fff;
            cursor: pointer;
            min-width: 180px;
        }
        .sort-select:focus { outline: none; border-color: var(--teal); }

        /* Products Area */
        .products-area { }
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
</style>
<style>
        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        .products-grid.list-view {
            grid-template-columns: 1fr;
        }

        /* Product Card */
        .product-card {
            background: var(--card);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
            border: 1px solid var(--border);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            border-color: var(--teal-light);
        }
        .products-grid.list-view .product-card {
            display: grid;
            grid-template-columns: 150px 1fr;
        }
        .product-badges {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            z-index: 5;
        }
        .badge {
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .badge-sale { background: var(--red); color: #fff; }
        .badge-new { background: var(--teal); color: #fff; }
        .badge-fresh { background: var(--green); color: #fff; }
        .product-image {
            height: 150px;
            background: linear-gradient(135deg, #f0fdfa, #e0f2fe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: relative;
        }
        .products-grid.list-view .product-image { height: 100%; }
        .product-favorite {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 32px;
            height: 32px;
            background: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .product-favorite:hover, .product-favorite.active { color: var(--red); }
        .product-body { padding: 1rem; }
        .product-category {
            font-size: 0.75rem;
            color: var(--teal);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.3rem;
        }
        .product-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            color: var(--text);
            margin-bottom: 0.3rem;
            font-weight: 600;
        }
        .product-origin {
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 0.8rem;
            border-top: 1px solid var(--border);
        }
        .price-wrapper { display: flex; flex-direction: column; }
        .price-current {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--teal-dark);
        }
        .price-old { font-size: 0.8rem; color: #94a3b8; text-decoration: line-through; }
        .price-unit { font-size: 0.7rem; color: var(--muted); }
        .add-cart-btn {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1rem;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .add-cart-btn:hover { background: var(--teal-dark); transform: scale(1.05); }
        .add-cart-btn.added { background: var(--green); }
</style>
<style>
        /* Active Filters Tags */
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

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        .page-btn {
            width: 40px;
            height: 40px;
            border: 2px solid var(--border);
            background: var(--card);
            border-radius: 10px;
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            color: var(--text);
            transition: all 0.3s;
        }
        .page-btn:hover { border-color: var(--teal); color: var(--teal); }
        .page-btn.active { background: var(--teal); border-color: var(--teal); color: #fff; }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }
        .no-results i { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
        .no-results h3 { font-family: 'El Messiri', sans-serif; color: var(--text); margin-bottom: 0.5rem; }

        /* Mobile Filters */
        .mobile-filter-btn {
            display: none;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
        }
        .filters-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .filters-overlay.active { display: block; }

        /* Responsive */
        @media (max-width: 1200px) {
            .products-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 992px) {
            .main-container { grid-template-columns: 1fr; }
            .filters-sidebar {
                position: fixed;
                top: 0;
                right: -320px;
                width: 300px;
                height: 100vh;
                z-index: 1000;
                border-radius: 0;
                overflow-y: auto;
                transition: right 0.3s;
            }
            .filters-sidebar.active { right: 0; }
            .mobile-filter-btn { display: flex; }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 576px) {
            .products-grid { grid-template-columns: 1fr; }
            .products-header { flex-direction: column; align-items: stretch; }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="header-container">
            <div class="breadcrumb">
                <a href="/"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-left"></i>
                <a href="/mart">توليب مارت</a>
                <i class="fas fa-chevron-left"></i>
                <span>جميع المنتجات</span>
            </div>
            <h1 class="page-title">جميع المنتجات</h1>
            <p class="page-subtitle">تصفح مجموعتنا الكاملة من المنتجات الطازجة</p>
        </div>
    </div>

    <div class="filters-overlay" id="filtersOverlay" onclick="toggleFilters()"></div>

    <div class="main-container">
        <!-- Filters Sidebar -->
        <aside class="filters-sidebar" id="filtersSidebar">
            <div class="filters-header">
                <h3 class="filters-title"><i class="fas fa-sliders-h"></i> الفلاتر</h3>
                <a href="#" class="clear-filters" onclick="clearAllFilters()">مسح الكل</a>
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
                <div class="filter-group-title">نطاق السعر (ر.س)</div>
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
                    <label class="checkbox-item">
                        <input type="checkbox" id="filterSale" onchange="applyFilters()">
                        <label for="filterSale">🏷️ عروض</label>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" id="filterNew" onchange="applyFilters()">
                        <label for="filterNew">✨ جديد</label>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" id="filterFresh" onchange="applyFilters()">
                        <label for="filterFresh">🌿 طازج</label>
                    </label>
                </div>
            </div>

            <!-- Apply Button (Mobile) -->
            <button class="mobile-filter-btn" style="width:100%;justify-content:center;margin-top:1rem" onclick="toggleFilters()">
                تطبيق الفلاتر
            </button>
        </aside>

        <!-- Products Area -->
        <main class="products-area">
            <div class="products-header">
                <div>
                    <button class="mobile-filter-btn" onclick="toggleFilters()">
                        <i class="fas fa-filter"></i>
                        الفلاتر
                    </button>
                    <span class="results-count">عرض <span id="resultsCount">0</span> منتج</span>
                </div>
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

            <!-- Active Filters -->
            <div class="active-filters" id="activeFilters"></div>

            <!-- Products Grid -->
            <div class="products-grid" id="productsGrid"></div>

            <!-- Pagination -->
            <div class="pagination" id="pagination"></div>
        </main>
    </div>
    <script>
        // Product Data (same as main page)
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
            { id: 'all', name: 'الكل', emoji: '🛒' },
            { id: 'fruits', name: 'فواكه', emoji: '🍎' },
            { id: 'vegetables', name: 'خضروات', emoji: '🥕' },
            { id: 'leafy', name: 'ورقيات', emoji: '🥬' },
            { id: 'dairy', name: 'ألبان وبيض', emoji: '🥛' },
            { id: 'bakery', name: 'مخبوزات', emoji: '🍞' },
            { id: 'grocery', name: 'بقالة', emoji: '🛒' },
        ];

        // State
        let currentView = 'grid';
        let currentPage = 1;
        const itemsPerPage = 12;
        let filteredProducts = [];
        let allProducts = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            allProducts = Object.values(products).flat();
            filteredProducts = [...allProducts];
            
            loadCategories();
            loadOrigins();
            applyURLFilters();
            applyFilters();
            initMartSearch();
        });
        function loadCategories() {
            const categoryList = document.getElementById('categoryList');
            categoryList.innerHTML = categories.map(cat => {
                const count = cat.id === 'all' ? allProducts.length : (products[cat.id]?.length || 0);
                return `
                    <div class="category-item" data-category="${cat.id}" onclick="selectCategory('${cat.id}')">
                        <span class="emoji">${cat.emoji}</span>
                        <span>${cat.name}</span>
                        <span class="count">${count}</span>
                    </div>
                `;
            }).join('');
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
            
            // Category filter
            const category = urlParams.get('category');
            if (category) {
                selectCategory(category);
            }
            
            // Search filter
            const search = urlParams.get('search');
            if (search) {
                document.getElementById('searchFilter').value = search;
            }
            
            // Filter type
            const filter = urlParams.get('filter');
            if (filter === 'fresh') {
                document.getElementById('filterFresh').checked = true;
            }
        }

        function selectCategory(categoryId) {
            document.querySelectorAll('.category-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`[data-category="${categoryId}"]`).classList.add('active');
            applyFilters();
        }

        function applyFilters() {
            let filtered = [...allProducts];
            
            // Category filter
            const activeCategory = document.querySelector('.category-item.active')?.dataset.category || 'all';
            if (activeCategory !== 'all') {
                filtered = products[activeCategory] || [];
            }
            
            // Search filter
            const searchTerm = document.getElementById('searchFilter').value.toLowerCase().trim();
            if (searchTerm) {
                filtered = filtered.filter(p => 
                    p.name.toLowerCase().includes(searchTerm) ||
                    p.category.toLowerCase().includes(searchTerm) ||
                    p.origin.toLowerCase().includes(searchTerm)
                );
            }
            
            // Price range filter
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
            filtered = filtered.filter(p => p.price >= minPrice && p.price <= maxPrice);
            
            // Origin filter
            const selectedOrigins = Array.from(document.querySelectorAll('#originList input:checked'))
                .map(cb => cb.id.replace('origin-', ''));
            if (selectedOrigins.length > 0) {
                filtered = filtered.filter(p => selectedOrigins.includes(p.origin));
            }
            
            // Badge filters
            const saleFilter = document.getElementById('filterSale').checked;
            const newFilter = document.getElementById('filterNew').checked;
            const freshFilter = document.getElementById('filterFresh').checked;
            
            if (saleFilter || newFilter || freshFilter) {
                filtered = filtered.filter(p => {
                    if (saleFilter && p.badge === 'sale') return true;
                    if (newFilter && p.badge === 'new') return true;
                    if (freshFilter && p.badge === 'fresh') return true;
                    return false;
                });
            }
            
            // Sort
            const sortBy = document.getElementById('sortSelect').value;
            switch (sortBy) {
                case 'price-asc':
                    filtered.sort((a, b) => a.price - b.price);
                    break;
                case 'price-desc':
                    filtered.sort((a, b) => b.price - a.price);
                    break;
                case 'name-asc':
                    filtered.sort((a, b) => a.name.localeCompare(b.name, 'ar'));
                    break;
                case 'name-desc':
                    filtered.sort((a, b) => b.name.localeCompare(a.name, 'ar'));
                    break;
            }
            
            filteredProducts = filtered;
            currentPage = 1;
            displayProducts();
            updateActiveFilters();
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
                            <div class="price-wrapper">
                                <span class="price-current">${p.price} ر.س</span>
                                ${p.oldPrice ? `<span class="price-old">${p.oldPrice} ر.س</span>` : ''}
                                <span class="price-unit">/ ${p.unit}</span>
                            </div>
                            <button class="add-cart-btn" onclick="addToCart('${p.id}', event)" id="btn-${p.id}">
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
            
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }
            
            let paginationHTML = '';
            
            // Previous button
            if (currentPage > 1) {
                paginationHTML += `<button class="page-btn" onclick="goToPage(${currentPage - 1})"><i class="fas fa-chevron-right"></i></button>`;
            }
            
            // Page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                paginationHTML += `<button class="page-btn" onclick="goToPage(1)">1</button>`;
                if (startPage > 2) {
                    paginationHTML += `<span style="padding:0 0.5rem;color:var(--muted)">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHTML += `<span style="padding:0 0.5rem;color:var(--muted)">...</span>`;
                }
                paginationHTML += `<button class="page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
            }
            
            // Next button
            if (currentPage < totalPages) {
                paginationHTML += `<button class="page-btn" onclick="goToPage(${currentPage + 1})"><i class="fas fa-chevron-left"></i></button>`;
            }
            
            pagination.innerHTML = paginationHTML;
        }

        function goToPage(page) {
            currentPage = page;
            displayProducts();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateActiveFilters() {
            const activeFilters = document.getElementById('activeFilters');
            const filters = [];
            
            // Category filter
            const activeCategory = document.querySelector('.category-item.active');
            if (activeCategory && activeCategory.dataset.category !== 'all') {
                filters.push({
                    type: 'category',
                    label: activeCategory.textContent.trim().split('\n')[0],
                    value: activeCategory.dataset.category
                });
            }
            
            // Search filter
            const searchTerm = document.getElementById('searchFilter').value.trim();
            if (searchTerm) {
                filters.push({
                    type: 'search',
                    label: `البحث: ${searchTerm}`,
                    value: searchTerm
                });
            }
            
            // Price range
            const minPrice = document.getElementById('minPrice').value;
            const maxPrice = document.getElementById('maxPrice').value;
            if (minPrice || maxPrice) {
                const priceLabel = `السعر: ${minPrice || '0'} - ${maxPrice || '∞'} ر.س`;
                filters.push({
                    type: 'price',
                    label: priceLabel,
                    value: 'price'
                });
            }
            
            // Badge filters
            if (document.getElementById('filterSale').checked) {
                filters.push({ type: 'badge', label: '🏷️ عروض', value: 'sale' });
            }
            if (document.getElementById('filterNew').checked) {
                filters.push({ type: 'badge', label: '✨ جديد', value: 'new' });
            }
            if (document.getElementById('filterFresh').checked) {
                filters.push({ type: 'badge', label: '🌿 طازج', value: 'fresh' });
            }
            
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
                case 'category':
                    selectCategory('all');
                    break;
                case 'search':
                    document.getElementById('searchFilter').value = '';
                    break;
                case 'price':
                    document.getElementById('minPrice').value = '';
                    document.getElementById('maxPrice').value = '';
                    break;
                case 'badge':
                    document.getElementById(`filter${value.charAt(0).toUpperCase() + value.slice(1)}`).checked = false;
                    break;
            }
            applyFilters();
        }

        function clearAllFilters() {
            // Reset all filters
            selectCategory('all');
            document.getElementById('searchFilter').value = '';
            document.getElementById('minPrice').value = '';
            document.getElementById('maxPrice').value = '';
            document.getElementById('filterSale').checked = false;
            document.getElementById('filterNew').checked = false;
            document.getElementById('filterFresh').checked = false;
            document.getElementById('sortSelect').value = 'default';
            
            // Clear origin checkboxes
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

        function toggleFilters() {
            const sidebar = document.getElementById('filtersSidebar');
            const overlay = document.getElementById('filtersOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
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
            const product = allProducts.find(p => p.id === productId);
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
                }, 2000);
                
            } catch (error) {
                console.error('Error adding to cart:', error);
                btn.innerHTML = '<i class="fas fa-times"></i> خطأ';
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }, 2000);
            }
        }

        // Search functionality
        function initMartSearch() {
            const searchInput = document.getElementById('searchFilter');
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 300);
            });
        }

        // Initialize navbar search for mart
        document.addEventListener('DOMContentLoaded', function() {
            const navSearchInput = document.getElementById('searchInput');
            if (navSearchInput) {
                navSearchInput.placeholder = 'ابحث في توليب مارت...';
            }
        });
    </script>
</body>
</html>