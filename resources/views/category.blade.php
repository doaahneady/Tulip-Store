<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $category->name }} - Tulip Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/store.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .category-header {
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%);
            padding: 2rem;
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }
        .category-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .category-description {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        .products-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 260px;
            gap: 2rem;
        }
        .filters-sidebar {
            background: #ffffff;
            padding: 0;
            border-radius: 12px;
            height: fit-content;
            width: 260px;
            box-shadow: 0 2px 8px rgba(15, 79, 85, 0.08);
            overflow: hidden;
        }
        .filter-section {
            border-bottom: 1px solid #e8f4f5;
            padding: 1.2rem 0;
        }
        .filter-section:first-child {
            padding-top: 1.2rem;
        }
        .filter-section:last-child {
            border-bottom: none;
        }
        .filter-section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f4f55;
            margin-bottom: 0.9rem;
            padding: 0 1.2rem;
        }
        .filter-option {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.5rem 1.2rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .filter-option:hover {
            background: #f0f9fa;
        }
        .filter-option input[type="checkbox"] {
            width: 17px;
            height: 17px;
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
            border: 2px solid #0f4f55;
            border-radius: 3px;
            accent-color: #0f4f55;
        }
        .filter-option input[type="checkbox"]:checked {
            background: #0f4f55;
        }
        .filter-option label {
            cursor: pointer;
            font-family:"El Messiri", sans-serif;
            font-size: 0.9rem;
            color: #2c3e50;
            line-height: 1.5;
            flex: 1;
            font-weight: 400;
        }
        .filter-option label:hover {
            color: #0f4f55;
        }
        .filter-see-more {
            color: #0f4f55;
            font-family:"El Messiri", sans-serif;
            font-size: 0.875rem;
            cursor: pointer;
            padding: 0.5rem 1.2rem;
            display: inline-block;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .filter-see-more:hover {
            color: #1a6b73;
            text-decoration: underline;
        }
        .price-inputs {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 0.5rem;
            padding: 0 1.2rem;
            margin-bottom: 0.8rem;
        }
        .price-input-wrapper {
            flex: 1;
            position: relative;
        }
        .price-input {
            width: 100%;
            padding: 0.5rem 0.4rem;
            border: 2px solid #d1e7e9;
            border-radius: 8px;
            font-family:"El Messiri", sans-serif;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            background: #fafafa;
            text-align: center;
        }
        .price-input::placeholder {
            font-size: 0.75rem;
        }
        .price-input:focus {
            outline: none;
            border-color: #0f4f55;
            background: white;
            box-shadow: 0 0 0 3px rgba(15, 79, 85, 0.1);
        }
        .price-separator {
            color: #7f8c8d;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .filters-sidebar,
        .filter-section,
        .price-inputs,
        .price-input-wrapper,
        .price-input {
            box-sizing: border-box;
        }
        .price-inputs { max-width: 100%; }
        .price-input-wrapper { min-width: 0; }
        .price-input { min-width: 0; }
        .products-content {
            min-width: 0;
        }
        @media (max-width: 768px) {
            .products-container {
                grid-template-columns: 1fr;
                padding: 1rem;
                gap: 1rem;
            }
            .filters-sidebar {
                display: none;
            }
            .mobile-filters-wrapper {
                display: block;
                margin-bottom: 1.5rem;
                width: 100%;
                overflow-x: auto;
                white-space: nowrap;
                padding: 0.5rem 0;
                scrollbar-width: none; /* Firefox */
                -ms-overflow-style: none;  /* IE and Edge */
            }
            .mobile-filters-wrapper::-webkit-scrollbar {
                display: none; /* Hide scrollbar for Chrome, Safari and Opera */
            }
            .mobile-filters-list {
                display: flex;
                gap: 0.6rem;
                padding: 0 0.5rem;
            }
            .mobile-filter-dropdown {
                position: relative;
                display: inline-block;
            }
            .mobile-filter-btn {
                background: white;
                border: 1px solid #d1e7e9;
                padding: 0.5rem 1rem;
                border-radius: 50px;
                font-family: 'El Messiri', sans-serif;
                font-size: 0.85rem;
                color: #0f4f55;
                display: flex;
                align-items: center;
                gap: 0.4rem;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                white-space: nowrap;
            }
            .mobile-filter-btn.active {
                background: #0f4f55;
                color: white;
                border-color: #0f4f55;
            }
            .mobile-filter-content {
                display: none;
                position: absolute;
                top: 100%;
                right: 0;
                background: white;
                min-width: 200px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                border-radius: 12px;
                z-index: 1000;
                margin-top: 0.5rem;
                padding: 0.8rem 0;
            }
            .mobile-filter-dropdown.active .mobile-filter-content {
                display: block;
            }
            .mobile-filter-option {
                display: flex;
                align-items: center;
                gap: 0.8rem;
                padding: 0.6rem 1.2rem;
                cursor: pointer;
            }
            .mobile-filter-option:hover {
                background: #f0f9fa;
            }
            .mobile-filter-option input {
                width: 18px;
                height: 18px;
                accent-color: #0f4f55;
            }
            .mobile-filter-option label {
                font-size: 0.9rem;
                color: #2c3e50;
                font-family: 'El Messiri', sans-serif;
            }
            .mobile-price-filter {
                padding: 1rem;
                min-width: 250px;
            }
            .mobile-price-inputs {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
        }
        @media (min-width: 769px) {
            .mobile-filters-wrapper {
                display: none;
            }
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
        }
        
        @media (max-width: 1400px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        @media (max-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem 1rem;
            }
        }
        .no-products {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        .no-products i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
            }
            .category-title {
                font-size: 1.5rem;
            }
        }
        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.5rem !important;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="products-container">
        <!-- Mobile Filters -->
        <div class="mobile-filters-wrapper">
            <div class="mobile-filters-list">
                @php
                    $brands = $products->pluck('brand')->unique()->filter()->sort()->values()->take(12);
                @endphp
                
                @if($brands->count() > 0)
                <div class="mobile-filter-dropdown" id="brandDropdown">
                    <button class="mobile-filter-btn" onclick="toggleMobileDropdown('brandDropdown')">
                        <span>العلامة التجارية</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mobile-filter-content">
                        @foreach($brands as $brand)
                        <div class="mobile-filter-option">
                            <input type="checkbox" id="m-brand-{{ $loop->index }}" value="{{ $brand }}" onchange="applyFilters(); syncDesktopFilters('brand-{{ $loop->index }}', this.checked)">
                            <label for="m-brand-{{ $loop->index }}">{{ $brand }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mobile-filter-dropdown" id="priceDropdown">
                    <button class="mobile-filter-btn" onclick="toggleMobileDropdown('priceDropdown')">
                        <span>السعر</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mobile-filter-content mobile-price-filter">
                        <div class="mobile-price-inputs">
                            <input type="number" class="price-input" id="m-minPrice" placeholder="من" oninput="syncDesktopPrice('minPrice', this.value)">
                            <span class="price-separator">-</span>
                            <input type="number" class="price-input" id="m-maxPrice" placeholder="إلى" oninput="syncDesktopPrice('maxPrice', this.value)">
                        </div>
                        <button class="btn-primary" style="margin-top: 1rem; width: 100%; padding: 0.6rem; font-size: 0.85rem;" onclick="applyFilters(); toggleMobileDropdown('priceDropdown')">تطبيق</button>
                    </div>
                </div>

                <div class="mobile-filter-dropdown" id="stockDropdown">
                    <button class="mobile-filter-btn" onclick="toggleMobileDropdown('stockDropdown')">
                        <span>التوفر</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mobile-filter-content">
                        <div class="mobile-filter-option">
                            <input type="checkbox" id="m-inStock" onchange="toggleAvailabilityFilter('in-stock'); syncDesktopFilters('inStock', this.checked)">
                            <label for="m-inStock">متوفر</label>
                        </div>
                        <div class="mobile-filter-option">
                            <input type="checkbox" id="m-includeOutOfStock" onchange="toggleAvailabilityFilter('include-out'); syncDesktopFilters('includeOutOfStock', this.checked)">
                            <label for="m-includeOutOfStock">تضمين غير المتوفر</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Content -->
        <div class="products-content">
            <h2 style="font-family: 'El Messiri', sans-serif; font-size: 2rem; color: #0f4f55; margin: 0 0 2rem 0;">{{ $category->name }}</h2>
            @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                        <div class="product-card" data-product-id="{{ $product->id }}" data-product="{{ rawurlencode($product->toJson()) }}">
                            <button class="product-favorite-btn" onclick="event.stopPropagation(); toggleProductFavorite(event, {{ $product->id }})">
                                <i class="far fa-heart"></i>
                            </button>
                            <div class="product-image-wrapper" onclick="openFloatingViewFromCard(this)">
                                <img src="{{ $product->primary_image_url }}" srcset="{{ $product->primary_image_srcset }}" sizes="(max-width: 768px) 50vw, 25vw" alt="{{ $product->name }}" class="product-img" loading="lazy" width="320" height="320" onerror="this.src='/images/gift-placeholder.svg'">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <div class="product-price-rating-wrapper">
                                    <div class="product-price-wrapper">
                                        <span class="product-price">${{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                                        @if($product->discount_price)
                                            <span class="product-old-price">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="product-card-actions">
                                <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(event, {{ $product->id }})">
                                    إضافة للسلة
                                </button>
                                <button class="product-card-btn product-card-btn-share" onclick="event.stopPropagation(); shareProduct(event, '{{ $product->name }}')">
                                    شاركه الآن
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 3rem; text-align: center;">
                    {{ $products->links() }}
                </div>
            @else
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <h2>لا توجد منتجات في هذا القسم حالياً</h2>
                    <p>نعمل على إضافة منتجات جديدة قريباً</p>
                </div>
            @endif
        </div>

        <!-- Filters Sidebar -->
        <div class="filters-sidebar">
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
                <div class="filter-section-title">نطاق السعر (ل.س)</div>
                <div class="price-inputs">
                    <div class="price-input-wrapper">
                        <input type="number" class="price-input" id="minPrice" placeholder="من" min="0">
                    </div>
                    <span class="price-separator">-</span>
                    <div class="price-input-wrapper">
                        <input type="number" class="price-input" id="maxPrice" placeholder="إلى" min="0">
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-section-title">التوفر</div>
                <div class="filter-option">
                    <input type="checkbox" id="inStock" onchange="toggleAvailabilityFilter('in-stock')">
                    <label for="inStock">متوفر</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" id="includeOutOfStock" onchange="toggleAvailabilityFilter('include-out')">
                    <label for="includeOutOfStock">تضمين غير المتوفر</label>
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
            
            document.getElementById('floatingImage').src = product.primary_image_url || product.image || (Array.isArray(product.images) ? product.images[0] : null) || '/images/gift-placeholder.svg';
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

        // Mobile Filter Logic
        function toggleMobileDropdown(id) {
            const dropdown = document.getElementById(id);
            const isActive = dropdown.classList.contains('active');
            
            // Close all dropdowns
            document.querySelectorAll('.mobile-filter-dropdown').forEach(d => {
                d.classList.remove('active');
                d.querySelector('.mobile-filter-btn').classList.remove('active');
            });

            if (!isActive) {
                dropdown.classList.add('active');
                dropdown.querySelector('.mobile-filter-btn').classList.add('active');
            }
        }

        // Close mobile dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.mobile-filter-dropdown')) {
                document.querySelectorAll('.mobile-filter-dropdown').forEach(d => {
                    d.classList.remove('active');
                    d.querySelector('.mobile-filter-btn').classList.remove('active');
                });
            }
        });

        function syncDesktopFilters(desktopId, isChecked) {
            const desktopEl = document.getElementById(desktopId);
            if (desktopEl) {
                desktopEl.checked = isChecked;
                // applyFilters is usually called from the onchange of the mobile input
            }
        }

        function syncDesktopPrice(desktopId, value) {
            const desktopEl = document.getElementById(desktopId);
            if (desktopEl) {
                desktopEl.value = value;
            }
        }

        // Add to cart function
        async function addToCart(event, productId) {
            event.stopPropagation();
            
            const btn = event.target.closest('.product-btn-cart');
            const originalText = btn.innerHTML;
            
            // Show loading
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
                    // Show success
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
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
                btn.innerHTML = '<i class="fas fa-times"></i> فشلت الإضافة';
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
                updateCartCount(data.count || 0);
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
                    const btn = event.target.closest('.product-btn-share');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> تم النسخ';
                    
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
                        return `
                        <div class="product-card" data-product-id="${product.id}" data-product="${encodeURIComponent(JSON.stringify(product))}">
                            <button class="product-favorite-btn ${isFavorite ? 'active' : ''}" onclick="event.stopPropagation(); toggleProductFavorite(event, ${product.id})">
                                <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                            </button>
                            <div class="product-image-wrapper" onclick="openFloatingViewFromCard(this)">
                                <img src="${product.primary_image_url || product.image || (Array.isArray(product.images) ? product.images[0] : null) || '/images/gift-placeholder.svg'}" srcset="${product.primary_image_srcset || ''}" sizes="(max-width: 768px) 50vw, 25vw" alt="${product.name}" class="product-img" loading="lazy" width="320" height="320" onerror="this.src='/images/gift-placeholder.svg'">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">${product.name}</h3>
                                <div class="product-price-rating-wrapper">
                                    <div class="product-price-wrapper">
                                        <span class="product-price">${window.formatMoney ? window.formatMoney(product.discount_price || product.price) : ('$' + parseFloat(product.discount_price || product.price).toFixed(2))}</span>
                                        ${product.discount_price ? 
                                            `<span class="product-old-price">${window.formatMoney ? window.formatMoney(product.price) : ('$' + parseFloat(product.price).toFixed(2))}</span>` : ''
                                        }
                                    </div>
                                    <div class="product-rating">
                                        ${'<i class="fas fa-star"></i>'.repeat(5)}
                                    </div>
                                </div>
                            </div>
                            <div class="product-card-actions">
                                <button class="product-card-btn product-card-btn-cart" onclick="event.stopPropagation(); addToCart(event, ${product.id})">
                                    إضافة للسلة
                                </button>
                                <button class="product-card-btn product-card-btn-share" onclick="event.stopPropagation(); shareProduct(event, '${product.name}')">
                                    شاركه الآن
                                </button>
                            </div>
                        </div>
                        `;
                    }).join('');
                } else {
                    productsGrid.innerHTML = `
                        <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #999;">
                            <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                            <p>لا توجد منتجات تطابق الفلاتر المحددة</p>
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
        
        const mMinPrice = document.getElementById('m-minPrice');
        const mMaxPrice = document.getElementById('m-maxPrice');
        if (mMinPrice) mMinPrice.addEventListener('input', debouncedApplyFilters);
        if (mMaxPrice) mMaxPrice.addEventListener('input', debouncedApplyFilters);

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
</body>
</html>
