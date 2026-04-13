<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة،
     مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">
    <title>Tulip Store</title>
    <!-- Google Fonts: El Messiri and Changa (same as auth pages) -->
     <!-- fav icon -->
     <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
<meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/store.css?v=999&fix=store&t={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Force main content styling */
        .main-content {
            max-width: 1400px !important;
            margin: 0 auto !important;
            padding: 2rem !important;
        }
        
        /* Product grid styling */
        .products-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
            margin-top: 2rem !important;
        }

        @media (max-width: 768px) {
            .products-grid { gap: 0.75rem !important; }
        }
        
        /* Product card styling */
        .product-card {
            background: #fff !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
            border-radius: 15px !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: row !important;
            align-items: stretch !important;
            gap: 0 !important;
            min-width: 0 !important;
        }

        .product-image-wrapper {
            aspect-ratio: 1 / 1 !important;
            width: 100% !important;
            overflow: hidden !important;
            background: #f5f5f5;
        }

        .product-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        
        .product-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        /* New card design (mart-style) for store page */
        .store-section-cards .product-card {
            border-radius: 24px;
            border: 1px solid #e8e8e8;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
            background: #fff;
            cursor: pointer;
            display: flex;
            flex-direction: row !important;
            align-items: stretch;
        }
        .store-section-cards .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
            border-color: #2a7080;
        }
        .store-section-cards .product-image,
        .store-section-cards .product-image-wrapper {
            width: 260px;
            flex: 0 0 260px;
            aspect-ratio: 1 / 1;
            background: linear-gradient(135deg, #eaf7f8, #f8f9fa);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        @media (max-width: 1024px) {
            .store-section-cards .product-image,
            .store-section-cards .product-image-wrapper {
                width: 200px;
                flex-basis: 200px;
            }
        }
        @media (max-width: 768px) {
            .store-section-cards .product-image,
            .store-section-cards .product-image-wrapper {
                width: 140px;
                flex-basis: 140px;
            }
        }
        @media (max-width: 480px) {
            .store-section-cards .product-image,
            .store-section-cards .product-image-wrapper {
                width: 120px;
                flex-basis: 120px;
            }
        }
        .store-section-cards .product-image img,
        .store-section-cards .product-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .store-section-cards .product-body,
        .store-section-cards .product-info {
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            min-height: auto;
            flex: 1 1 auto;
            min-width: 0;
        }
        .store-section-cards .product-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.2rem;
            font-family: 'El Messiri', sans-serif;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .store-section-cards .product-meta {
            margin-top: 0.25rem;
            color: #475569;
            font-size: 0.82rem;
            line-height: 1.25rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .store-section-cards .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 0.35rem;
            border-top: 1px solid #e8e8e8;
            margin-top: 0.35rem;
            margin-top: auto;
        }
        .store-section-cards .price-wrapper {
            display: flex;
            flex-direction: column;
        }
        .store-section-cards .price-current,
        .store-section-cards .product-price {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f4f55;
            font-family: 'El Messiri', sans-serif;
        }
        .store-section-cards .price-old,
        .store-section-cards .product-old-price {
            font-size: 0.75rem;
            color: #94a3b8;
            text-decoration: line-through;
        }
        .store-section-cards .add-cart-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            padding: 0.4rem 0.8rem;
            background: #0f4f55;
            color: #fff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-family: 'El Messiri', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(15,79,85,0.15);
        }
        .store-section-cards .add-cart-btn:hover:not(:disabled) {
            background: #0d464c;
            transform: scale(1.05);
        }
        .store-section-cards .product-favorite-btn {
            position: absolute;
            top: 0.6rem;
            right: 0.6rem;
            z-index: 2;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.95);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
   
    <!-- NAVBAR -->
    @include('components.navbar')

    
    <!-- PRODUCTS SECTION -->
    <div class="products-container" style="max-width: 100%; margin: 2rem auto; padding: 0 2rem;">
        <!-- Products Content -->
        <div class="products-content">
            <h2 id="pageTitle" style="font-family: 'El Messiri', sans-serif; font-size: 2rem; color: #0f4f55; margin: 0 0 2rem 0;">جميع المنتجات</h2>
            @if(isset($trader))
                @php
                    $avg = (float) ($traderAverageRating ?? 0);
                    if ($avg < 0) { $avg = 0; }
                    if ($avg > 5) { $avg = 5; }
                    $filled = (int) floor($avg);
                    $hasHalf = ($avg - $filled) >= 0.5 && $filled < 5;
                @endphp
                <div style="background:#fff; border:1px solid #e8e8e8; border-radius:16px; padding:1rem 1.25rem; margin:-0.75rem 0 1.5rem 0; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                    <div style="display:flex; flex-direction:column; gap:0.35rem;">
                        <div style="font-family:'El Messiri',sans-serif; font-weight:800; color:#1a1a1a; font-size:1.1rem;">{{ $trader->name }}</div>
                        <div style="display:flex; align-items:center; gap:0.4rem; color:#f59e0b; font-size:0.95rem;">
                            @for($i = 1; $i <= 5; $i++)
                                @php
                                    $icon = $i <= $filled ? 'fas' : 'far';
                                    if ($hasHalf && $i === $filled + 1) { $icon = 'fas'; }
                                @endphp
                                <i class="{{ $icon }} fa-star"></i>
                            @endfor
                            <span style="color:#64748b; font-size:0.85rem; font-weight:700;">{{ number_format($avg, 1) }}/5</span>
                        </div>
                    </div>
                </div>
            @endif
            <div class="store-section-cards" style="padding: 0;">
                <div class="products-grid" id="productsGrid">
                    <!-- Products will be loaded here -->
                </div>
            </div>
            <div id="ratingsSection" style="display:none; background:#fff; border:1px solid #e8e8e8; border-radius:16px; padding:1rem 1.25rem; margin-top:1.25rem;"></div>
            <div id="loadingProducts" style="text-align: center; padding: 3rem; color: #999;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem;"></i>
                <p style="margin-top: 1rem;">جاري تحميل المنتجات...</p>
            </div>
        </div>

        <!-- Filters Sidebar (shown only for search results) -->
        <div class="filters-sidebar" id="filtersSidebar" style="display: none; position: fixed; left: 2rem; top: 200px; background: #fafafa; height: fit-content; width: 260px; z-index: 100; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden;">
            <div style="background: white; padding: 1.2rem 1.5rem; border-bottom: 1px solid #e8e8e8; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-family: 'El Messiri', sans-serif; font-size: 1.1rem; color: #1a1a1a; font-weight: 600;">
                    <i class="fas fa-filter"></i> الفلاتر
                </span>
                <span onclick="clearStoreFilters()" style="font-size: 0.8rem; color: #ff4757; cursor: pointer; font-weight: 500; padding: 0.3rem 0.8rem; border: 1px solid #ff4757; border-radius: 4px; transition: all 0.2s ease;">مسح</span>
            </div>

            <div style="padding: 1.5rem;">
                <!-- Price Filter -->
                <div class="filter-group">
                    <div class="filter-group-title">
                        <i class="fas fa-dollar-sign"></i>
                        السعر
                    </div>
                    <div class="price-range">
                        <input type="number" class="price-input" id="minPriceStore" placeholder="من" min="0">
                        <span class="price-separator">-</span>
                        <input type="number" class="price-input" id="maxPriceStore" placeholder="إلى" min="0">
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="filter-group" id="brandFilterGroup" style="display: none;">
                    <div class="filter-group-title">
                        <i class="fas fa-tag"></i>
                        العلامة التجارية
                    </div>
                    <div id="brandFilters"></div>
                </div>

                <!-- Rating Filter -->
                <div class="filter-group">
                    <div class="filter-group-title">
                        <i class="fas fa-star"></i>
                        التقييم
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="rating5Store" onchange="applyStoreFilters()">
                        <label for="rating5Store">
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                        </label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="rating4Store" onchange="applyStoreFilters()">
                        <label for="rating4Store">
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="far fa-star" style="color: #ddd; font-size: 0.8rem;"></i>
                            <span style="font-size: 0.75rem; margin-right: 0.3rem;">فأكثر</span>
                        </label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="rating3Store" onchange="applyStoreFilters()">
                        <label for="rating3Store">
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            <i class="far fa-star" style="color: #ddd; font-size: 0.8rem;"></i>
                            <i class="far fa-star" style="color: #ddd; font-size: 0.8rem;"></i>
                            <span style="font-size: 0.75rem; margin-right: 0.3rem;">فأكثر</span>
                        </label>
                    </div>
                </div>

                <!-- Availability Filter -->
                <div class="filter-group">
                    <div class="filter-group-title">
                        <i class="fas fa-box"></i>
                        التوفر
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="inStockStore" onchange="applyStoreFilters()">
                        <label for="inStockStore">متوفر</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="outOfStockStore" onchange="applyStoreFilters()">
                        <label for="outOfStockStore">غير متوفر</label>
                    </div>
                </div>

                <!-- Condition Filter -->
                <div class="filter-group" id="conditionFilterGroup" style="display: none;">
                    <div class="filter-group-title">
                        <i class="fas fa-certificate"></i>
                        الحالة
                    </div>
                    <div id="conditionFilters"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Product View -->
    <div class="product-modal-backdrop" id="productModalBackdrop" onclick="closeFloatingView()"></div>
    <div class="product-floating-view" id="productFloatingView">
        <button class="floating-close" onclick="closeFloatingView()">×</button>
        <div class="floating-image-container">
            <img id="floatingImage" class="floating-image" src="" alt="">
        </div>
        <div class="floating-details">
            <h2 id="floatingName" class="floating-name"></h2>
            <p id="floatingDescription" class="floating-description"></p>
            <div class="floating-price-wrapper">
                <span id="floatingOldPrice" class="floating-old-price" style="display: none;"></span>
                <span id="floatingPrice" class="floating-price"></span>
            </div>
            <div id="floatingRating" class="floating-rating" style="display: none;"></div>
            <div class="floating-actions">
                <button id="floatingCartBtn" class="floating-btn floating-btn-cart">
                    <i class="fas fa-shopping-cart"></i> أضف للسلة
                </button>
                <button id="floatingShareBtn" class="floating-btn floating-btn-share" style=" font-family: 'El Messiri', sans-serif;"> 
                    <i class="fas fa-share-alt"></i> مشاركة
                </button>
            </div>
            <a href="#" class="floating-view-details" onclick="viewAllDetails(event)">عرض جميع التفاصيل</a>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';
        const isAuthenticated = {!! auth()->check() ? 'true' : 'false' !!};
        window.__productsById = {};
        const FIXED_TRADER_ID = {!! isset($trader) ? (int) $trader->id : 'null' !!};

        async function syncFavoritesFromServer() {
            if (!isAuthenticated) {
                return;
            }

            try {
                const res = await fetch('/api/wishlist', { headers: { 'Accept': 'application/json' } });
                if (!res.ok) {
                    return;
                }
                const data = await res.json();
                if (Array.isArray(data.items)) {
                    localStorage.setItem('favorites', JSON.stringify(data.items));
                    const countElement = document.getElementById('favoritesCount');
                    if (countElement) {
                        const c = data.count || data.items.length || 0;
                        countElement.textContent = c > 99 ? '+99' : c;
                    }
                }
            } catch (e) {
            }
        }

        // Load all products or search results on page load
        function loadProducts() {
            const productsGrid = document.getElementById('productsGrid');
            const loadingProducts = document.getElementById('loadingProducts');
            const pageTitle = document.getElementById('pageTitle');
            
            // Check if there's a search query in URL
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search');
            const traderIdParam = urlParams.get('trader_id');
            const traderId = FIXED_TRADER_ID !== null ? FIXED_TRADER_ID : (traderIdParam ? parseInt(traderIdParam, 10) : null);
            
            let apiUrl = `${API_BASE}/products`;
            if (searchQuery) {
                apiUrl = `${API_BASE}/products/search?q=${encodeURIComponent(searchQuery)}`;
                if (traderId) {
                    apiUrl += `&trader_id=${encodeURIComponent(traderId)}`;
                }
                if (pageTitle) {
                    pageTitle.textContent = `نتائج البحث عن: "${searchQuery}"`;
                }
            } else {
                if (pageTitle) {
                    pageTitle.textContent = traderId ? 'منتجات التاجر' : 'جميع المنتجات';
                }
                if (traderId) {
                    apiUrl += `?trader_id=${encodeURIComponent(traderId)}`;
                }
            }

            fetch(apiUrl)
                .then(res => res.json())
                .then(data => {
                    loadingProducts.style.display = 'none';
                    
                    const products = data.data || [];
                    window.__productsById = {};
                    products.forEach(p => { window.__productsById[p.id] = p; });
                    
                    if (products.length > 0) {
                        const escapeHtml = (s) => String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                        const imgUrl = (p) => {
                            const u = p.primary_image_url || p.image || (Array.isArray(p.images) && p.images[0]) || '';
                            const s = String(u || '').trim();
                            if (!s) return '/images/tulip_store.jpg';
                            if (s.startsWith('http://') || s.startsWith('https://')) return s;
                            return s.startsWith('/') ? s : ('/storage/' + s.replace(/^storage\//, ''));
                        };
                        productsGrid.innerHTML = products.map(product => {
                            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                            const isFavorite = favorites.some(p => p.id === product.id);
                            const stock = parseInt(product.stock_quantity ?? 0);
                            const isOutOfStock = !!product.track_inventory && stock <= 0;
                            const price = parseFloat(product.discount_price || product.price || 0);
                            const oldPrice = parseFloat(product.price || 0);
                            const priceStr = window.formatMoney ? window.formatMoney(price) : ('$' + price.toFixed(2));
                            const oldPriceStr = window.formatMoney ? window.formatMoney(oldPrice) : ('$' + oldPrice.toFixed(2));
                            const trader = product.trader || null;
                            const traderId = trader && trader.id ? trader.id : null;
                            const traderName = trader ? (trader.company_name || trader.name || trader.account_name_ar || trader.account_name_en || '') : '';
                            const traderLine = traderName
                                ? `<div style="margin-top:0.15rem; font-size:0.8rem; color:#64748b;">التاجر: ${traderId ? `<a href="/traders/${traderId}/products" onclick="event.stopPropagation();" style="color:#2a7080; font-weight:700; text-decoration:none;">${escapeHtml(traderName)}</a>` : escapeHtml(traderName)}</div>`
                                : '';
                            const normalize = (s) => String(s ?? '').replace(/\s+/g, ' ').trim();
                            const truncate = (s, n) => (s.length > n ? (s.slice(0, Math.max(0, n - 1)) + '…') : s);
                            const description = normalize(product.description);
                            let infoText = description;
                            if (!infoText) {
                                const categoryName = normalize(product?.category?.name);
                                const parts = [
                                    categoryName,
                                    normalize(product.brand),
                                    normalize(product.size),
                                    normalize(product.color),
                                    normalize(product.material),
                                    normalize(product.condition),
                                    normalize(product.author),
                                    normalize(product.genre),
                                ].filter(Boolean);
                                infoText = parts.join(' • ');
                            }
                            const infoLine = infoText ? `<div class="product-meta">${escapeHtml(truncate(infoText, 140))}</div>` : '';
                            const rating = Math.max(0, Math.min(5, parseInt(product.rating ?? 0, 10) || 0));
                            const reviewsCount = parseInt(product.reviews_count ?? 0, 10) || 0;
                            const stars = Array.from({ length: 5 }).map((_, i) => i < rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>').join('');
                            const ratingLine = (rating > 0 || reviewsCount > 0)
                                ? `<div style="margin-top:0.35rem; display:flex; align-items:center; gap:0.45rem; color:#f59e0b; font-size:0.85rem;">
                                        <span style="display:inline-flex; gap:0.15rem;">${stars}</span>
                                        <span style="color:#64748b; font-weight:700; font-size:0.8rem;">(${reviewsCount})</span>
                                   </div>`
                                : '';
                            return `
                            <div class="product-card" data-product-id="${product.id}" onclick="window.location.href='/products/${product.id}'">
                                <button class="product-favorite-btn ${isFavorite ? 'active' : ''}" onclick="event.stopPropagation(); toggleProductFavorite(event, ${product.id})">
                                    <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                                </button>
                                <div class="product-image">
                                    <img src="${imgUrl(product)}" alt="${escapeHtml(product.name)}" class="product-img" loading="lazy" width="320" height="320" onerror="this.onerror=null; this.src='/images/tulip_store.jpg';">
                                </div>
                                <div class="product-body">
                                    <h3 class="product-name">${escapeHtml(product.name)}</h3>
                                    ${traderLine}
                                    ${infoLine}
                                    ${ratingLine}
                                    <div class="product-footer">
                                        <div class="price-wrapper">
                                            <span class="price-current">${priceStr}</span>
                                            ${product.discount_price ? `<span class="price-old">${oldPriceStr}</span>` : ''}
                                        </div>
                                        <button type="button" class="add-cart-btn" onclick="event.stopPropagation(); addToCart(event, ${product.id})" data-product-id="${product.id}" ${isOutOfStock ? 'disabled' : ''} style="${isOutOfStock ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
                                            <i class="fas fa-plus"></i> أضف
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `}).join('');

                        const ratingsSection = document.getElementById('ratingsSection');
                        if (ratingsSection) {
                            if (traderId) {
                                const dist = [0, 0, 0, 0, 0, 0];
                                products.forEach(p => {
                                    const r = Math.max(0, Math.min(5, parseInt(p.rating ?? 0, 10) || 0));
                                    if (r >= 1 && r <= 5) dist[r] += 1;
                                });
                                const ratedTotal = dist.slice(1).reduce((a, b) => a + b, 0);
                                const rows = [5, 4, 3, 2, 1].map(stars => {
                                    const count = dist[stars] || 0;
                                    const pct = ratedTotal > 0 ? Math.round((count / ratedTotal) * 100) : 0;
                                    return `
                                        <div style="display:flex; align-items:center; gap:0.75rem;">
                                            <div style="min-width:70px; color:#334155; font-weight:800; font-size:0.9rem; display:flex; align-items:center; gap:0.35rem;">
                                                <span>${stars}</span><i class="fas fa-star" style="color:#f59e0b;"></i>
                                            </div>
                                            <div style="flex:1; height:10px; background:#e2e8f0; border-radius:999px; overflow:hidden;">
                                                <div style="height:100%; width:${pct}%; background:#f59e0b;"></div>
                                            </div>
                                            <div style="min-width:42px; text-align:left; color:#64748b; font-weight:800;">${count}</div>
                                        </div>
                                    `;
                                }).join('');
                                ratingsSection.innerHTML = `
                                    <div style="font-family:'El Messiri',sans-serif; font-weight:900; color:#0f4f55; font-size:1.1rem; margin-bottom:0.75rem;">تقييمات منتجات هذا التاجر</div>
                                    ${rows}
                                `;
                                ratingsSection.style.display = 'block';
                            } else {
                                ratingsSection.style.display = 'none';
                                ratingsSection.innerHTML = '';
                            }
                        }
                    } else {
                        productsGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #999;"><i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i><p>لا توجد نتائج</p></div>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    loadingProducts.style.display = 'none';
                    productsGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #e74c3c;">حدث خطأ في تحميل المنتجات</div>';
                });
        }

        // Open floating view
        let currentProductId = null;
        
        function openFloatingView(product) {
            currentProductId = product.id;
            
            // Set product data
            document.getElementById('floatingImage').src = product.primary_image_url || product.image || '/images/tulip_store.jpg';
            document.getElementById('floatingName').textContent = product.name;
            document.getElementById('floatingDescription').textContent = product.description || 'منتج رائع من Tulip Store';
            
            // Set price
            const priceEl = document.getElementById('floatingPrice');
            const oldPriceEl = document.getElementById('floatingOldPrice');
            
            if (product.discount_price) {
                oldPriceEl.textContent = window.formatMoney ? window.formatMoney(product.price) : ('$' + parseFloat(product.price).toFixed(2));
                oldPriceEl.style.display = 'inline';
                priceEl.textContent = window.formatMoney ? window.formatMoney(product.discount_price) : ('$' + parseFloat(product.discount_price).toFixed(2));
            } else {
                oldPriceEl.style.display = 'none';
                priceEl.textContent = window.formatMoney ? window.formatMoney(product.price) : ('$' + parseFloat(product.price).toFixed(2));
            }
            
            // Set rating
            const ratingEl = document.getElementById('floatingRating');
            if (product.rating > 0) {
                ratingEl.innerHTML = '<i class="fas fa-star"></i>'.repeat(product.rating) + `<span>(${product.reviews_count})</span>`;
                ratingEl.style.display = 'flex';
            } else {
                ratingEl.style.display = 'none';
            }

            const stock = parseInt(product.stock_quantity ?? 0);
            const isOutOfStock = !!product.track_inventory && stock <= 0;
            const cartBtn = document.getElementById('floatingCartBtn');
            if (isOutOfStock) {
                cartBtn.disabled = true;
                cartBtn.innerHTML = '<i class="fas fa-times-circle"></i> غير متوفر';
                cartBtn.style.opacity = '0.6';
                cartBtn.style.cursor = 'not-allowed';
            } else {
                cartBtn.disabled = false;
                cartBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> أضف للسلة';
                cartBtn.style.opacity = '';
                cartBtn.style.cursor = '';
            }
            
            // Show modal
            document.getElementById('productModalBackdrop').classList.add('active');
            document.getElementById('productFloatingView').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeFloatingView() {
            document.getElementById('productModalBackdrop').classList.remove('active');
            document.getElementById('productFloatingView').classList.remove('active');
            document.body.style.overflow = '';
            currentProductId = null;
        }
        
        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeFloatingView();
            }
        });
        
        // Floating view cart button
        document.getElementById('floatingCartBtn').addEventListener('click', () => {
            if (currentProductId) {
                addToCartFromFloating(currentProductId);
            }
        });
        
        // Floating view share button
        document.getElementById('floatingShareBtn').addEventListener('click', () => {
            const productName = document.getElementById('floatingName').textContent;
            shareProductFromFloating(productName);
        });

        // Add to cart function (for card buttons)
        async function addToCart(event, productId) {
            event.stopPropagation();
            
            const btn = event.target.closest('.product-card-btn-cart') || event.target.closest('.add-cart-btn');
            const product = window.__productsById ? window.__productsById[productId] : null;
            const stock = parseInt(product?.stock_quantity ?? 0);
            const isOutOfStock = !!product?.track_inventory && stock <= 0;
            if (btn.disabled || isOutOfStock) {
                if (window.showToast) {
                    window.showToast('هذا المنتج غير متوفر في المخزون');
                } else {
                    alert('هذا المنتج غير متوفر في المخزون');
                }
                return;
            }
            
            // Show loading
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE}/cart/add`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
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
                
                if (data.success) {
                    // Update localStorage
                    const cartItems = JSON.parse(localStorage.getItem('cart_items') || '[]');
                    if (!cartItems.includes(productId)) {
                        cartItems.push(productId);
                        localStorage.setItem('cart_items', JSON.stringify(cartItems));
                    }
                    
                    // Change button to green with checkmark
                    btn.style.transition = 'all 0.3s ease';
                    btn.style.background = '#10b981'; // Bright green
                    btn.style.boxShadow = '0 4px 12px rgba(16, 185, 129, 0.4)';
                    btn.innerHTML = '<i class="fas fa-check" style="font-size: 1.2rem;"></i>';
                    btn.classList.add('added');
                    
                    // Update cart count in navbar using global function
                    if (window.updateCartCount) {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
                    // Mart delivery warning if applicable
                    const product = window.__productsById ? window.__productsById[productId] : null;
                    const isMart = product && (product.store_id === 1 || (product.store && product.store.name && product.store.name.toLowerCase().includes('mart')));
                    if (isMart && window.showToast) {
                        setTimeout(() => {
                            window.showToast('تنبيه: منتجات Mart تتوفر للتوصيل فقط إلى (السويداء، عتيل، قنوات)', 4000);
                        }, 1500);
                    }
                    
                    // Revert back to button label after 2 seconds (أضف for new cards)
                    setTimeout(() => {
                        btn.classList.remove('added');
                        btn.style.background = '';
                        btn.style.boxShadow = '';
                        btn.innerHTML = btn.classList.contains('add-cart-btn') ? '<i class="fas fa-plus"></i> أضف' : '<i class="fas fa-shopping-cart" style="font-size: 1.2rem;"></i>';
                        btn.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'فشلت الإضافة');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                btn.innerHTML = '<i class="fas fa-times"></i>';
                btn.style.background = '#e74c3c';
                
                setTimeout(() => {
                    btn.innerHTML = btn.classList.contains('add-cart-btn') ? '<i class="fas fa-plus"></i> أضف' : '<i class="fas fa-shopping-cart" style="font-size: 1.2rem;"></i>';
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }
        }

        // Add to cart from floating view
        async function addToCartFromFloating(productId) {
            const btn = document.getElementById('floatingCartBtn');
            const originalText = btn.innerHTML;
            const product = window.__productsById ? window.__productsById[productId] : null;
            const stock = parseInt(product?.stock_quantity ?? 0);
            const isOutOfStock = !!product?.track_inventory && stock <= 0;
            if (isOutOfStock) {
                if (window.showToast) {
                    window.showToast('هذا المنتج غير متوفر في المخزون');
                } else {
                    alert('هذا المنتج غير متوفر في المخزون');
                }
                return;
            }
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            btn.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE}/cart/add`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
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
                
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                    btn.style.background = 'linear-gradient(135deg, #27ae60 0%, #2ecc71 100%)';
                    
                    // Update cart count using global function
                    if (window.updateCartCount) {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
                    // Mart delivery warning
                    const isMart = product && (product.store_id === 1 || (product.store && product.store.name && product.store.name.toLowerCase().includes('mart')));
                    if (isMart && window.showToast) {
                        setTimeout(() => {
                            window.showToast('تنبيه: منتجات Mart تتوفر للتوصيل فقط إلى (السويداء، عتيل، قنوات)', 4000);
                        }, 1500);
                    }
                    
                    setTimeout(() => {
                        closeFloatingView();
                        openCart();
                    }, 800);
                } else {
                    throw new Error(data.message || 'فشلت الإضافة');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                btn.innerHTML = '<i class="fas fa-times"></i> فشلت الإضافة';
                btn.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }
        }
        
        // Share product from floating view
        function shareProductFromFloating(productName) {
            if (navigator.share) {
                navigator.share({
                    title: productName,
                    text: `تحقق من هذا المنتج الرائع: ${productName}`,
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = document.getElementById('floatingShareBtn');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> تم النسخ';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                    }, 2000);
                });
            }
        }
        
        // View all details function
        function viewAllDetails(event) {
            event.preventDefault();
            if (currentProductId) {
                window.location.href = `/products/${currentProductId}`;
            }
        }
        
        // Toggle product favorite
        async function toggleProductFavorite(event, productId) {
            event.stopPropagation();
            const btn = event.currentTarget;
            const icon = btn.querySelector('i');
            
            let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const isFavorite = favorites.some(p => p.id === productId);
            const product = window.__productsById[productId];
            
            // Add animation
            btn.classList.add('animating');
            setTimeout(() => btn.classList.remove('animating'), 600);

            if (isAuthenticated) {
                try {
                    const res = await fetch('/api/wishlist/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ product_id: productId })
                    });
                    const data = await res.json();
                    if (data.action === 'added') {
                        btn.classList.add('active');
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        if (product) {
                            favorites = favorites.filter(p => p.id !== productId);
                            favorites.push({
                                id: product.id,
                                name: product.name,
                                price: product.discount_price || product.price,
                                image: product.image
                            });
                        }
                    } else if (data.action === 'removed') {
                        btn.classList.remove('active');
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        favorites = favorites.filter(p => p.id !== productId);
                    }

                    localStorage.setItem('favorites', JSON.stringify(favorites));
                    const countElement = document.getElementById('favoritesCount');
                    if (countElement) {
                        const c = data.count || favorites.length || 0;
                        countElement.textContent = c > 99 ? '+99' : c;
                    }
                } catch (e) {
                }
            } else {
                if (isFavorite) {
                    favorites = favorites.filter(p => p.id !== productId);
                    btn.classList.remove('active');
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                } else if (product) {
                    favorites.push({
                        id: product.id,
                        name: product.name,
                        price: product.discount_price || product.price,
                        image: product.image
                    });
                    btn.classList.add('active');
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                }

                localStorage.setItem('favorites', JSON.stringify(favorites));
                const countElement = document.getElementById('favoritesCount');
                if (countElement) {
                    countElement.textContent = favorites.length > 99 ? '+99' : favorites.length;
                }
            }
        }
        
        // Check and update cart icons for products
        async function updateCartIcons() {
            try {
                const response = await fetch(`${API_BASE}/cart`);
                const data = await response.json();
                
                if (data.items) {
                    const cartItems = data.items
                        .map(item => item.product_id ?? item.product?.id)
                        .map(v => parseInt(v))
                        .filter(v => Number.isFinite(v));
                    localStorage.setItem('cart_items', JSON.stringify(cartItems));
                    
                    // Update all product check icons
                    cartItems.forEach(productId => {
                        const btn = document.querySelector(`.product-card-btn-cart[data-product-id="${productId}"]`);
                        if (btn) {
                            const checkIcon = btn.querySelector('.cart-check-icon');
                            if (checkIcon) {
                                checkIcon.classList.add('in-cart');
                                checkIcon.style.color = '#27ae60';
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading cart items:', error);
            }
        }

        // Load products and cart count when page loads
        window.addEventListener('DOMContentLoaded', () => {
            syncFavoritesFromServer().finally(() => loadProducts());
            loadCartCount();
            // Wait a bit for products to load, then update icons
            setTimeout(updateCartIcons, 500);
        });

        const searchBar = document.getElementById('searchBar');
        const searchInput = document.getElementById('searchInput');
        const searchPanel = document.getElementById('searchPanel');
        const searchRecent = document.getElementById('searchRecent');
        const searchResults = document.getElementById('searchResults');
        const searchCategories = document.getElementById('searchCategories');
        const searchClear = document.getElementById('searchClear');
        const searchMenu = document.getElementById('searchMenu');
        const searchPanelTitle = document.getElementById('searchPanelTitle');

        // Position search dropdown exactly under the search bar (same x + width)
        function positionSearchPanel() {
            if (!searchBar || !searchPanel) return;
            const barRect = searchBar.getBoundingClientRect();
            const shellEl = document.querySelector('.tulip-navbar') || document.querySelector('.navbar-wrapper');
            const shellRect = shellEl ? shellEl.getBoundingClientRect() : barRect;

            const leftWithinShell = barRect.left - shellRect.left;
            searchPanel.style.left = leftWithinShell + 'px';
            searchPanel.style.width = barRect.width + 'px';
        }

        // initial position + on resize
        window.addEventListener('load', positionSearchPanel);
        window.addEventListener('resize', positionSearchPanel);

        function openPanel() {
            positionSearchPanel();
            searchPanel.classList.add('open');
        }

        function closePanel() {
            searchPanel.classList.remove('open');
        }

        searchInput.addEventListener('focus', () => {
            // default state: recent searches
            searchPanelTitle.textContent = 'أكثر ما تم البحث عنه مؤخراً';
            searchRecent.style.display = 'flex';
            searchResults.style.display = 'none';
            searchCategories.style.display = 'none';
            openPanel();
        });

        async function performSearch(value) {
            const term = value.trim();
            if (term.length < 2) {
                searchPanelTitle.textContent = 'أكثر ما تم البحث عنه مؤخراً';
                searchRecent.style.display = 'flex';
                searchResults.style.display = 'none';
                searchCategories.style.display = 'none';
                return;
            }

            searchPanelTitle.textContent = 'اسم المنتج';
            searchRecent.style.display = 'none';
            searchCategories.style.display = 'none';
            searchResults.style.display = 'flex';
            searchResults.innerHTML = '<div style="padding:0.5rem 0; text-align:center; font-size:0.8rem; color:#888;">جاري البحث...</div>';

            try {
                const response = await fetch(`${API_BASE}/products/search?q=${encodeURIComponent(term)}`);
                const data = await response.json();
                const products = data.data || [];

                if (products.length === 0) {
                    searchResults.innerHTML = '<div style="padding:0.5rem 0; text-align:center; font-size:0.8rem; color:#888;">لا توجد نتائج</div>';
                    return;
                }

                searchResults.innerHTML = products.map(p => `
                    <div class="search-result-item">
                        <div class="search-result-meta">
                            <span class="search-result-title">${p.name}</span>
                            <span class="search-result-category">${p.category ? p.category.name : ''}</span>
                        </div>
                        <img src="${p.image || 'https://via.placeholder.com/36'}" class="search-result-thumb" alt="${p.name}">
                    </div>
                `).join('');
            } catch (err) {
                console.error(err);
                searchResults.innerHTML = '<div style="padding:0.5rem 0; text-align:center; font-size:0.8rem; color:#e74c3c;">لا توجد نتائج، يرجى المحاولة مرة أخرى</div>';
            }
        }

        searchInput.addEventListener('input', (e) => {
            performSearch(e.target.value);
        });
        // Clear button
        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            searchInput.focus();
            searchPanelTitle.textContent = 'أكثر ما تم البحث عنه مؤخراً';
            searchRecent.style.display = 'flex';
            searchResults.style.display = 'none';
            searchCategories.style.display = 'none';
        });

        // Chips (recent searches) -> fill input and search
        document.querySelectorAll('.chip').forEach(chip => {
            chip.addEventListener('click', () => {
                const text = chip.textContent.trim();
                searchInput.value = text;
                searchInput.focus();
                openPanel();
                performSearch(text);
            });
        });

        // Menu icon -> show categories
        searchMenu.addEventListener('click', async () => {
            openPanel();
            searchPanelTitle.textContent = 'أقسام رئيسية';
            searchRecent.style.display = 'none';
            searchResults.style.display = 'none';
            searchCategories.style.display = 'flex';

            if (!searchCategories.dataset.loaded) {
                searchCategories.innerHTML = '<div style="padding:0.5rem 0; text-align:center; font-size:0.8rem; color:#888;">جاري تحميل الأقسام...</div>';
                try {
                    const res = await fetch(`${API_BASE}/categories`);
                    const payload = await res.json();
                    const cats = Array.isArray(payload) ? payload : (payload.data || []);
                    searchCategories.innerHTML = (Array.isArray(cats) ? cats : []).map(c => `
                        <div class="category-item">${c.name}</div>
                    `).join('');
                    searchCategories.dataset.loaded = '1';
                } catch (err) {
                    console.error(err);
                    searchCategories.innerHTML = '<div style="padding:0.5rem 0; text-align:center; font-size:0.8rem; color:#e74c3c;">تعذر تحميل الأقسام</div>';
                }
            }
        });

        // Close panel when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchBar.contains(e.target) && !searchPanel.contains(e.target)) {
                closePanel();
            }
        });

        // Make icon hovers clickable
        document.querySelectorAll('.gift-wrapper').forEach(w => {
            w.addEventListener('click', () => {
                // TODO: link to gifts page
                console.log('Gift icon clicked');
            });
        });
        document.querySelectorAll('.cart-wrapper').forEach(w => {
            w.addEventListener('click', () => {
                window.location.href = '/cart';
            });
        });
        // Account dropdown toggle (click only, no hover)
        @auth
        const accountWrapper = document.getElementById('accountWrapper');
        const accountPill = document.getElementById('accountPill');
        const accountDropdown = document.getElementById('accountDropdown');
        
        if(accountPill && accountDropdown) {
            // Toggle on click only
            accountPill.addEventListener('click', (e) => {
                e.stopPropagation();
                accountDropdown.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if(!accountWrapper.contains(e.target)) {
                    accountDropdown.classList.remove('show');
                }
            });
        }
        
        function handleLogout() {
            document.getElementById('logoutModal').classList.add('show');
            document.getElementById('accountDropdown').classList.remove('show');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('show');
        }

        function confirmLogout() {
            fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                }
            }).then(() => {
                window.location.href = '/ar-login';
            });
        }

        // Close modal when clicking outside
        document.getElementById('logoutModal')?.addEventListener('click', (e) => {
            if(e.target.id === 'logoutModal') {
                closeLogoutModal();
            }
        });

        // Theme toggle functionality
        function toggleTheme() {
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            const themeText = document.getElementById('themeText');
            const currentTheme = localStorage.getItem('theme') || 'light';
            
            if(currentTheme === 'light') {
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
                themeIcon.className = 'fas fa-sun';
                themeText.textContent = 'الوضع الفاتح';
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
                themeIcon.className = 'fas fa-moon';
                themeText.textContent = 'الوضع الداكن';
            }
        }

        // Load saved theme on page load
        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.getElementById('themeIcon');
            const themeText = document.getElementById('themeText');
            
            if(savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
                if(themeIcon) themeIcon.className = 'fas fa-sun';
                if(themeText) themeText.textContent = 'الوضع الفاتح';
            }
        });
        @endauth
    </script>

    <!-- FOOTER -->
 

</body>
</html>
