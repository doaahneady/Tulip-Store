<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $category->name }} - Tulip Store</title>

    <!-- fav icon -->
    <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/store.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #0f4f55;
            --primary-light: #1a6b73;
            --accent-color: #ff6b35;
            --text-dark: #1a1a1a;
            --text-muted: #666;
            --bg-light: #f8f9fa;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 15px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 30px rgba(0,0,0,0.12);
            --radius-md: 12px;
            --radius-lg: 24px;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Changa', sans-serif;
            color: var(--text-dark);
        }

        .category-page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            padding: 4rem 2rem;
            text-align: center;
            color: var(--white);
            margin-bottom: 0;
            position: relative;
            overflow: hidden;
        }

        .category-page-header::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: var(--bg-light);
            border-radius: 100% 100% 0 0;
        }

        .category-page-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .category-page-description {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .mobile-filter-trigger {
            display: none;
        }

        .close-sidebar {
            display: none;
        }

        .products-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2.5rem;
            align-items: start;
        }

        /* Sidebar & Filters */
        .filters-sidebar {
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 100px;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .sidebar-header h3 {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .filter-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .filter-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .filter-section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.4rem 0;
            cursor: pointer;
            transition: color 0.2s;
        }

        .filter-option:hover {
            color: var(--primary-light);
        }

        .filter-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .filter-option label {
            font-size: 0.95rem;
            cursor: pointer;
            flex: 1;
        }

        .price-inputs {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .price-input {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Changa', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .price-input:focus {
            border-color: var(--primary-color);
        }

        /* Product Cards Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        /* Reusing Product Card Styles from Home */
        .product-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid #eee;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .product-image-wrapper {
            aspect-ratio: 1 / 1;
            overflow: hidden;
            position: relative;
            background: #f9f9f9;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.1);
        }

        .product-favorite-btn {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 10;
            width: 36px;
            height: 36px;
            background: var(--white);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            transition: all 0.3s;
        }

        .product-favorite-btn.active, .product-favorite-btn:hover {
            color: #ef4444;
            transform: scale(1.1);
        }

        .product-info {
            padding: 1.2rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.8rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 3rem;
        }

        .product-price-wrapper {
            display: flex;
            align-items: baseline;
            gap: 0.8rem;
            margin-bottom: 1.2rem;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .product-old-price {
            font-size: 0.9rem;
            color: #999;
            text-decoration: line-through;
        }

        .product-card-actions {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0.8rem;
            margin-top: auto;
        }

        .product-card-btn {
            padding: 0.7rem 1rem;
            border-radius: 50px;
            font-family: 'El Messiri', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
        }

        .product-card-btn-cart {
            background: var(--primary-color);
            color: var(--white);
        }

        .product-card-btn-cart:hover {
            background: var(--primary-light);
            transform: scale(1.03);
        }

        .product-card-btn-share {
            background: #f0f0f0;
            color: var(--text-dark);
            width: 42px;
            padding: 0;
        }

        .product-card-btn-share:hover {
            background: #e0e0e0;
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            .products-container {
                grid-template-columns: 240px 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .category-page-title { font-size: 2.2rem; }
            .products-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .filters-sidebar {
                position: fixed;
                top: 0;
                right: -100%;
                width: 300px;
                height: 100vh;
                z-index: 10001;
                border-radius: 0;
                transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                overflow-y: auto;
            }

            .filters-sidebar.active {
                right: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(4px);
                z-index: 10000;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .mobile-filter-trigger {
                display: flex;
                align-items: center;
                gap: 0.8rem;
                background: var(--white);
                padding: 0.8rem 1.5rem;
                border-radius: 50px;
                font-family: 'El Messiri', sans-serif;
                font-weight: 700;
                color: var(--primary-color);
                box-shadow: var(--shadow-md);
                border: 1px solid #eee;
                margin-bottom: 1.5rem;
                cursor: pointer;
                width: fit-content;
            }

            .close-sidebar {
                display: block;
                background: none;
                border: none;
                font-size: 1.8rem;
                color: var(--text-muted);
                cursor: pointer;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .product-name {
                font-size: 0.95rem;
                height: 2.6rem;
            }

            .product-price { font-size: 1.1rem; }
            
            .product-card-btn span { display: none; }
            .product-card-btn { width: 42px; padding: 0; }
            .product-card-actions { grid-template-columns: repeat(2, 1fr); }
            .product-card-btn-cart { width: auto; }
            .product-card-btn-cart span { display: inline; }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.6rem;
            }
            .product-info { padding: 0.8rem; }
            .product-card-btn-cart span { display: none; }
        }

        .no-products {
            grid-column: 1/-1;
            text-align: center;
            padding: 5rem 2rem;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        .no-products i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1.5rem;
        }

        .no-products h2 {
            font-family: 'El Messiri', sans-serif;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        /* Floating View Styles */
        .product-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 10002;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        .product-modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .product-floating-view {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -40%);
            width: min(900px, 95vw);
            max-height: 90vh;
            background: var(--white);
            border-radius: var(--radius-lg);
            z-index: 10003;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .product-floating-view.active {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%);
        }

        @media (max-width: 768px) {
            .product-floating-view {
                grid-template-columns: 1fr;
                overflow-y: auto;
            }
        }

        .floating-image-container {
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .floating-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .floating-details {
            padding: 3rem;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 768px) {
            .floating-details { padding: 1.5rem; }
        }

        .floating-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .floating-description {
            font-size: 1.1rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .floating-price-wrapper {
            display: flex;
            align-items: baseline;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .floating-price {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--accent-color);
        }

        .floating-old-price {
            font-size: 1.4rem;
            color: #ccc;
            text-decoration: line-through;
        }

        .floating-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .floating-btn {
            flex: 1;
            padding: 1rem;
            border-radius: 50px;
            font-family: 'El Messiri', sans-serif;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            transition: all 0.3s;
        }

        .floating-btn-cart {
            background: var(--primary-color);
            color: var(--white);
            font-size: 1.1rem;
        }

        .floating-btn-cart:hover {
            background: var(--primary-light);
            transform: scale(1.02);
        }

        .floating-btn-share {
            background: #f0f0f0;
            color: var(--text-dark);
            max-width: 60px;
        }

        .floating-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: var(--white);
            border: none;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .floating-view-details {
            text-align: center;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
            font-family: 'El Messiri', sans-serif;
            margin-top: auto;
        }

        .floating-view-details:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    @if(View::exists('components.navbar'))
    @include('components.navbar')
@endif
    <div class="category-page-header">
        <h1 class="category-page-title">{{ $category->name }}</h1>
        @if($category->description)
            <p class="category-page-description">{{ $category->description }}</p>
        @else
            <p class="category-page-description">اكتشف تشكيلتنا المميزة من {{ $category->name }} المختارة بعناية لتناسب ذوقك</p>
        @endif
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <div class="products-container">
        <!-- Filters Sidebar -->
        <div class="filters-sidebar" id="filtersSidebar">
            <div class="sidebar-header">
                <h3>تصفية النتائج</h3>
                <button class="close-sidebar" onclick="toggleSidebar()">×</button>
            </div>

            @php
                $brands = $products->pluck('brand')->unique()->filter()->sort()->values()->take(12);
            @endphp

            @if($brands->count() > 0)
                <div class="filter-section">
                    <div class="filter-section-title">العلامة التجارية</div>
                    @foreach($brands as $brand)
                        <div class="filter-option">
                            <input type="checkbox" id="brand-{{ $loop->index }}" value="{{ $brand }}" onchange="applyFilters()">
                            <label for="brand-{{ $loop->index }}">{{ $brand }}</label>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="filter-section">
                <div class="filter-section-title">نطاق السعر</div>
                <div class="price-inputs">
                    <input type="number" class="price-input" id="minPrice" placeholder="من">
                    <span>-</span>
                    <input type="number" class="price-input" id="maxPrice" placeholder="إلى">
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-section-title">التوفر</div>
                <div class="filter-option">
                    <input type="checkbox" id="inStock" onchange="toggleAvailabilityFilter('in-stock')">
                    <label for="inStock">متوفر حالياً</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" id="includeOutOfStock" onchange="toggleAvailabilityFilter('include-out')">
                    <label for="includeOutOfStock">عرض غير المتوفر</label>
                </div>
            </div>
        </div>

        <!-- Products Content -->
        <div class="products-content">
            <!-- Mobile Filter Trigger -->
            <button class="mobile-filter-trigger" onclick="toggleSidebar()">
                <i class="fas fa-sliders-h"></i>
                <span>تصفية النتائج</span>
            </button>

            @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                        @php
                            $price = $product->discount_price ?? $product->price;
                            $oldPrice = $product->discount_price ? $product->price : null;
                        @endphp
                        <div class="product-card" data-product-id="{{ $product->id }}" data-product="{{ rawurlencode($product->toJson()) }}">
                            <button class="product-favorite-btn" onclick="event.stopPropagation(); toggleProductFavorite(event, {{ $product->id }})">
                                <i class="far fa-heart"></i>
                            </button>
                            <div class="product-image-wrapper" onclick="openFloatingViewFromCard(this)">
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="product-img" loading="lazy">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <div class="product-price-wrapper">
                                    <span class="product-price">@money($price)</span>
                                    @if($oldPrice)
                                        <span class="product-old-price">@money($oldPrice)</span>
                                    @endif
                                </div>
                                <div class="product-card-actions">
                                    <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(event, {{ $product->id }})">
                                        <i class="fas fa-shopping-basket"></i>
                                        <span>أضف للسلة</span>
                                    </button>
                                    <button class="product-card-btn product-card-btn-share" onclick="event.stopPropagation(); shareProduct(event, '{{ $product->name }}')">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 4rem;">
                    {{ $products->links() }}
                </div>
            @else
                <div class="no-products">
                    <i class="fas fa-search"></i>
                    <h2>لا توجد منتجات</h2>
                    <p>عذراً، لم نجد أي منتجات في هذا القسم حالياً.</p>
                </div>
            @endif
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
            <!-- <div id="floatingRating" class="floating-rating" style="display: none;"></div> -->
            <div class="floating-actions">
                <button id="floatingCartBtn" class="floating-btn floating-btn-cart">
                    <i class="fas fa-shopping-cart"></i> أضف للسلة
                </button>
                <button id="floatingShareBtn" class="floating-btn floating-btn-share">
                    <i class="fas fa-share-alt"></i> مشاركة
                </button>
            </div>
            <a href="#" class="floating-view-details" onclick="viewAllDetails(event)">عرض جميع التفاصيل</a>
        </div>
    </div>

    <script>
        // Load saved theme and cart count
        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if(savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
            }
            loadCartCount();
        });

        // Open floating view
        let currentProductId = null;
        
        function openFloatingView(product) {
            currentProductId = product.id;
            
            document.getElementById('floatingImage').src = product.primary_image_url || product.image || (Array.isArray(product.images) ? product.images[0] : null) || '/images/tulip_store.jpg';
            document.getElementById('floatingName').textContent = product.name;
            document.getElementById('floatingDescription').textContent = product.description || 'منتج رائع من Tulip Store';
            
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
            
            const ratingEl = document.getElementById('floatingRating');
            if (product.rating > 0) {
                ratingEl.innerHTML = '<i class="fas fa-star"></i>'.repeat(product.rating) + `<span>(${product.reviews_count})</span>`;
                ratingEl.style.display = 'flex';
            } else {
                ratingEl.style.display = 'none';
            }
            
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
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeFloatingView();
            }
        });
        
        document.getElementById('floatingCartBtn').addEventListener('click', () => {
            if (currentProductId) {
                addToCartFromFloating(currentProductId);
            }
        });
        
        document.getElementById('floatingShareBtn').addEventListener('click', () => {
            const productName = document.getElementById('floatingName').textContent;
            shareProductFromFloating(productName);
        });

        // Add to cart from floating view
        async function addToCartFromFloating(productId) {
            const btn = document.getElementById('floatingCartBtn');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            btn.disabled = true;
            
            try {
                const response = await fetch('/api/cart/add', {
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
                
                const data = await response.json();
                
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                    btn.style.background = 'linear-gradient(135deg, #27ae60 0%, #2ecc71 100%)';
                    
                    // Update cart count using global function
                    if (window.updateCartCount) {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
                    setTimeout(() => {
                        closeFloatingView();
                        if (typeof openCart === 'function') {
                            openCart();
                        }
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

        // Sidebar Toggle Function
        function toggleSidebar() {
            const sidebar = document.getElementById('filtersSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Add to cart function
        async function addToCart(event, productId) {
            event.stopPropagation();
            
            const btn = event.target.closest('.product-card-btn-cart');
            const originalText = btn.innerHTML;
            
            // Show loading
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            try {
                const response = await fetch('/api/cart/add', {
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
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    btn.style.background = '#27ae60';
                    
                    // Update cart count using global function
                    if (window.updateCartCount) {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
                    // Trigger cart update event
                    localStorage.setItem('cart-updated', Date.now().toString());
                    window.dispatchEvent(new Event('cart-updated'));
                    
                    // Open cart sidebar
                    setTimeout(() => {
                        if (typeof openCart === 'function') {
                            openCart();
                        }
                    }, 500);
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.style.background = '';
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
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }
        }
        
        // Load cart count on page load
        async function loadCartCount() {
            try {
                const response = await fetch('/api/cart');
                const data = await response.json();
                if (window.updateCartCount) {
                    window.updateCartCount(data.count || 0);
                }
            } catch (error) {
                console.error('Error loading cart count:', error);
            }
        }

        // Share product function
        function shareProduct(event, productName) {
            event.stopPropagation();
            
            if (navigator.share) {
                navigator.share({
                    title: productName,
                    text: `تحقق من هذا المنتج الرائع: ${productName}`,
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                // Fallback: copy link to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = event.target.closest('.product-card-btn-share');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                    }, 2000);
                });
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAllProducts();
            }
        });

        // Amazon-style Filter Functions
        let activeFilters = {
            brands: [],
            availability: [],
            minPrice: null,
            maxPrice: null
        };

        function toggleAvailabilityFilter(type) {
            const checkbox = document.getElementById(type === 'in-stock' ? 'inStock' : 'includeOutOfStock');
            
            const index = activeFilters.availability.indexOf(type);
            if (checkbox.checked && index === -1) {
                activeFilters.availability.push(type);
            } else if (!checkbox.checked && index > -1) {
                activeFilters.availability.splice(index, 1);
            }
            applyFilters();
        }

        async function applyFilters() {
            // Get price values
            const minPriceInput = document.getElementById('minPrice');
            const maxPriceInput = document.getElementById('maxPrice');
            
            if (minPriceInput && minPriceInput.value) activeFilters.minPrice = minPriceInput.value;
            if (maxPriceInput && maxPriceInput.value) activeFilters.maxPrice = maxPriceInput.value;
            
            // Collect all checked filters
            activeFilters.brands = Array.from(document.querySelectorAll('[id^="brand-"]:checked')).map(el => el.value);
            
            // Build query string
            const params = new URLSearchParams();
            
            if (activeFilters.minPrice) params.append('min_price', activeFilters.minPrice);
            if (activeFilters.maxPrice) params.append('max_price', activeFilters.maxPrice);
            if (activeFilters.brands.length) params.append('brands', activeFilters.brands.join(','));
            if (activeFilters.availability.length) params.append('availability', activeFilters.availability.join(','));
            
            // Show loading state
            const productsGrid = document.querySelector('.products-grid');
            if (productsGrid) {
                productsGrid.style.opacity = '0.5';
                productsGrid.style.pointerEvents = 'none';
            }
            
            try {
                // Fetch filtered products via AJAX
                const currentUrl = window.location.pathname;
                const response = await fetch(`${currentUrl}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                // Update products grid
                if (data.products && data.products.length > 0) {
                    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                    productsGrid.innerHTML = data.products.map(product => {
                        const isFavorite = favorites.some(p => String(p.id) === String(product.id));
                        const price = product.discount_price || product.price;
                        const oldPrice = product.discount_price ? product.price : null;
                        const formattedPrice = window.formatMoney ? window.formatMoney(price) : ('$' + parseFloat(price).toFixed(2));
                        const formattedOldPrice = oldPrice ? (window.formatMoney ? window.formatMoney(oldPrice) : ('$' + parseFloat(oldPrice).toFixed(2))) : null;
                        
                        return `
                        <div class="product-card" data-product-id="${product.id}" data-product="${encodeURIComponent(JSON.stringify(product))}">
                            <button class="product-favorite-btn ${isFavorite ? 'active' : ''}" onclick="event.stopPropagation(); toggleProductFavorite(event, ${product.id})">
                                <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                            </button>
                            <div class="product-image-wrapper" onclick="openFloatingViewFromCard(this)">
                                <img src="${product.primary_image_url || product.image || '/images/tulip_store.jpg'}" alt="${product.name}" class="product-img" loading="lazy">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">${product.name}</h3>
                                <div class="product-price-wrapper">
                                    <span class="product-price">${formattedPrice}</span>
                                    ${formattedOldPrice ? `<span class="product-old-price">${formattedOldPrice}</span>` : ''}
                                </div>
                                <div class="product-card-actions">
                                    <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(event, ${product.id})">
                                        <i class="fas fa-shopping-basket"></i>
                                        <span>أضف للسلة</span>
                                    </button>
                                    <button class="product-card-btn product-card-btn-share" onclick="event.stopPropagation(); shareProduct(event, '${product.name.replace(/'/g, "\\'")}')">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        `;
                    }).join('');
                } else {
                    productsGrid.innerHTML = `
                        <div class="no-products">
                            <i class="fas fa-search"></i>
                            <h2>لا توجد منتجات</h2>
                            <p>عذراً، لم نجد أي منتجات تطابق الفلاتر المختارة.</p>
                        </div>
                    `;
                }
                
                // Update URL without reload
                const newUrl = params.toString() ? `${currentUrl}?${params.toString()}` : currentUrl;
                window.history.pushState({}, '', newUrl);
                
            } catch (error) {
                console.error('Error filtering products:', error);
            } finally {
                // Remove loading state
                if (productsGrid) {
                    productsGrid.style.opacity = '1';
                    productsGrid.style.pointerEvents = 'auto';
                }
            }
        }

        // Price input listeners with debounce
        let priceTimeout;
        function debouncedApplyFilters() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(applyFilters, 500);
        }
        
        document.getElementById('minPrice').addEventListener('input', debouncedApplyFilters);
        document.getElementById('maxPrice').addEventListener('input', debouncedApplyFilters);

        function decodeProductFromCard(card) {
            const raw = card?.dataset?.product || '';
            if (!raw) return null;
            try {
                return JSON.parse(decodeURIComponent(raw));
            } catch (e) {
                return null;
            }
        }

        function openFloatingViewFromCard(el) {
            const card = el?.closest ? el.closest('.product-card') : null;
            const p = decodeProductFromCard(card);
            if (p) openFloatingView(p);
        }

        // Toggle product favorite
        function toggleProductFavorite(event, productId) {
            event.stopPropagation();
            const btn = event.currentTarget;
            const icon = btn.querySelector('i');
            
            let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const isFavorite = favorites.some(p => String(p.id) === String(productId));
            const card = btn.closest ? btn.closest('.product-card') : null;
            const resolvedProduct = decodeProductFromCard(card) || {};
            
            // Add animation
            btn.classList.add('animating');
            setTimeout(() => btn.classList.remove('animating'), 600);
            
            if (isFavorite) {
                // Remove from favorites
                favorites = favorites.filter(p => String(p.id) !== String(productId));
                btn.classList.remove('active');
                icon.classList.remove('fas');
                icon.classList.add('far');
            } else {
                // Add to favorites
                favorites.push({
                    id: resolvedProduct.id ?? productId,
                    name: resolvedProduct.name || '',
                    price: resolvedProduct.discount_price || resolvedProduct.price || 0,
                    image: resolvedProduct.image || null
                });
                btn.classList.add('active');
                icon.classList.remove('far');
                icon.classList.add('fas');
            }
            
            localStorage.setItem('favorites', JSON.stringify(favorites));
            
            // Update navbar count
            const countElement = document.getElementById('favoritesCount');
            if (countElement) {
                countElement.textContent = favorites.length > 99 ? '+99' : favorites.length;
            }
        }

        // Check favorites on page load
        window.addEventListener('DOMContentLoaded', function() {
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            document.querySelectorAll('.product-card').forEach(card => {
                const productId = String(card.dataset.productId || '');
                const isFavorite = favorites.some(p => String(p.id) === productId);
                if (isFavorite) {
                    const btn = card.querySelector('.product-favorite-btn');
                    const icon = btn.querySelector('i');
                    btn.classList.add('active');
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                }
            });
        });
    </script>
    </div>
    <!-- FOOTER -->
<div style="position:relative; z-index:1001;">
    @include('components.footer')
</div>

</body>
</html>
