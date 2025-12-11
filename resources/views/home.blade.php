<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulip Store - Send Smile, Anywhere</title>
    <link rel="stylesheet" href="/css/tulip-store.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo">
                <span class="navbar-logo-t">T</span>
                <span class="navbar-logo-lip">LIP</span>
            </div>
            
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="ابحث عن المنتج الذي تريده" id="searchInput">
            </div>
            
            <div class="navbar-icons">
                <div class="navbar-icon gift" title="الهدايا">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="navbar-icon cart" id="cartIcon" title="سلتي">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="icon-badge" id="cartBadge" style="display: none;">0</span>
                </div>
                <div class="navbar-icon account" id="accountIcon" title="حسابي">
                    <i class="fas fa-user-circle"></i>
                    <span class="icon-badge" id="accountBadge" style="display: none;"></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">Send smile, Anywhere</h1>
                <p class="hero-subtitle">أرسل ابتسامة لأحبائك في أي مكان</p>
                <button class="hero-button" onclick="document.querySelector('.products-section').scrollIntoView({behavior: 'smooth'})">
                    تصفح المنتجات
                </button>
            </div>
            <div class="hero-image">
                <img src="/images/tulip.png" alt="Tulip Flowers" style="max-width: 300px;" onerror="this.src='https://via.placeholder.com/300x300?text=Tulip+Flowers'">
            </div>
        </div>
    </section>

    <!-- Lightning Deals Section -->
    <section class="lightning-deals-section" id="lightningDealsSection">
        <div class="lightning-deals-container">
            <div class="lightning-deals-header">
                <div class="lightning-deals-title">
                    <span class="lightning-icon">⚡</span>
                    <span>عروض البرق</span>
                    <span class="lightning-icon">⚡</span>
                </div>
                <div class="lightning-timer">
                    <span class="timer-label">ينتهي خلال:</span>
                    <span class="timer-box" id="timerHours">00</span>
                    <span class="timer-separator">:</span>
                    <span class="timer-box" id="timerMinutes">00</span>
                    <span class="timer-separator">:</span>
                    <span class="timer-box" id="timerSeconds">00</span>
                </div>
            </div>
            <div class="lightning-deals-grid" id="lightningDealsGrid">
                <!-- Lightning deals will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="categories-container">
            <h2 class="categories-title">التسوق حسب الفئة</h2>
            <div class="categories-grid" id="categoriesGrid">
                <!-- Categories will be loaded here via JavaScript -->
                <div class="category-card" onclick="filterByCategory('flowers')">
                    <div class="category-icon">🌸</div>
                    <div class="category-name">الزهور</div>
                </div>
                <div class="category-card" onclick="filterByCategory('gifts')">
                    <div class="category-icon">🎁</div>
                    <div class="category-name">الهدايا</div>
                </div>
                <div class="category-card" onclick="filterByCategory('chocolates')">
                    <div class="category-icon">🍫</div>
                    <div class="category-name">الشوكولاتة</div>
                </div>
                <div class="category-card" onclick="filterByCategory('balloons')">
                    <div class="category-icon">🎈</div>
                    <div class="category-name">البالونات</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <h2 class="section-title">المنتجات المتاحة</h2>
        <div class="products-grid" id="productsGrid">
            <!-- Products will be loaded here via JavaScript -->
            <div class="product-card" style="text-align: center; padding: 2rem;">
                <p>جاري تحميل المنتجات...</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>عن متجر توليب</h3>
                <p>متجر توليب متخصص في بيع الزهور والهدايا الفاخرة والتسليم السريع في جميع أنحاء العالم.</p>
            </div>
            <div class="footer-section">
                <h3>الروابط السريعة</h3>
                <ul>
                    <li><a href="#about">عن المتجر</a></li>
                    <li><a href="#products">المنتجات</a></li>
                    <li><a href="#contact">اتصل بنا</a></li>
                    <li><a href="#terms">شروط الاستخدام</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>خدمة العملاء</h3>
                <ul>
                    <li><a href="#faq">الأسئلة الشائعة</a></li>
                    <li><a href="#shipping">سياسة التوصيل</a></li>
                    <li><a href="#returns">سياسة الإرجاع</a></li>
                    <li><a href="#privacy">سياسة الخصوصية</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>تابعنا</h3>
                <ul>
                    <li><a href="#facebook">فيسبوك</a></li>
                    <li><a href="#instagram">إنستجرام</a></li>
                    <li><a href="#twitter">تويتر</a></li>
                    <li><a href="#whatsapp">واتس أب</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Tulip Store. All rights reserved. متجر توليب &copy; 2025 جميع الحقوق محفوظة</p>
        </div>
    </footer>

    <script>
        // API base URL
        const API_URL = window.location.origin + '/api';

        // Lightning Deals Timer - Persistent across page refreshes
        const LIGHTNING_DEAL_DURATION = 24 * 60 * 60 * 1000; // 24 hours in milliseconds
        
        function initLightningTimer() {
            let endTime = localStorage.getItem('lightningDealEndTime');
            
            // If no end time or expired, set new one
            if (!endTime || parseInt(endTime) < Date.now()) {
                endTime = Date.now() + LIGHTNING_DEAL_DURATION;
                localStorage.setItem('lightningDealEndTime', endTime.toString());
            }
            
            updateTimer(parseInt(endTime));
            setInterval(() => updateTimer(parseInt(localStorage.getItem('lightningDealEndTime'))), 1000);
        }
        
        function updateTimer(endTime) {
            const now = Date.now();
            let remaining = endTime - now;
            
            if (remaining <= 0) {
                // Reset timer for next 24 hours
                const newEndTime = Date.now() + LIGHTNING_DEAL_DURATION;
                localStorage.setItem('lightningDealEndTime', newEndTime.toString());
                remaining = LIGHTNING_DEAL_DURATION;
            }
            
            const hours = Math.floor(remaining / (1000 * 60 * 60));
            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
            
            document.getElementById('timerHours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('timerMinutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('timerSeconds').textContent = seconds.toString().padStart(2, '0');
        }

        // Cart Animation Function
        function animateCart() {
            const cartIcon = document.getElementById('cartIcon');
            const cartBadge = document.getElementById('cartBadge');
            
            // Add bounce animation to cart icon
            cartIcon.classList.add('cart-bounce');
            
            // Add pop animation to badge
            if (cartBadge) {
                cartBadge.classList.add('badge-pop');
            }
            
            // Remove animation classes after animation completes
            setTimeout(() => {
                cartIcon.classList.remove('cart-bounce');
                if (cartBadge) {
                    cartBadge.classList.remove('badge-pop');
                }
            }, 600);
        }

        // Load Lightning Deals
        async function loadLightningDeals() {
            try {
                const response = await fetch(`${API_URL}/products?limit=4`);
                const data = await response.json();
                
                const dealsGrid = document.getElementById('lightningDealsGrid');
                
                if (data.data && data.data.length > 0) {
                    // Take first 4 products as lightning deals with random discounts
                    const deals = data.data.slice(0, 4).map(product => {
                        const discount = Math.floor(Math.random() * 30) + 20; // 20-50% discount
                        const originalPrice = parseFloat(product.price);
                        const dealPrice = (originalPrice * (100 - discount) / 100).toFixed(2);
                        const claimed = Math.floor(Math.random() * 70) + 20; // 20-90% claimed
                        
                        return { ...product, discount, dealPrice, originalPrice, claimed };
                    });
                    
                    dealsGrid.innerHTML = deals.map(deal => `
                        <div class="lightning-deal-card">
                            <span class="deal-badge">-${deal.discount}%</span>
                            <img src="${deal.image || 'https://via.placeholder.com/200x150?text=Deal'}" 
                                 alt="${deal.name}" class="deal-image">
                            <div class="deal-info">
                                <div class="deal-name">${deal.name}</div>
                                <div class="deal-prices">
                                    <span class="deal-current-price">${deal.dealPrice} SAR</span>
                                    <span class="deal-original-price">${deal.originalPrice} SAR</span>
                                </div>
                                <div class="deal-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: ${deal.claimed}%"></div>
                                    </div>
                                    <div class="progress-text">${deal.claimed}% تم الحجز</div>
                                </div>
                                <button class="deal-add-btn" onclick="addToCart(${deal.id})">
                                    <i class="fas fa-bolt"></i> أضف للسلة
                                </button>
                            </div>
                        </div>
                    `).join('');
                } else {
                    dealsGrid.innerHTML = '<p style="color: white; text-align: center; grid-column: 1/-1;">لا توجد عروض حالياً</p>';
                }
            } catch (error) {
                console.error('Error loading lightning deals:', error);
            }
        }

        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            loadCategories();
            updateCartCount();
            loadLightningDeals();
            initLightningTimer();
        });

        // Load products from API
        async function loadProducts(categoryId = null) {
            try {
                let url = `${API_URL}/products`;
                
                if (categoryId) {
                    url = `${API_URL}/products?category_id=${categoryId}`;
                }

                const response = await fetch(url);
                const data = await response.json();
                
                const productsGrid = document.getElementById('productsGrid');
                
                if (data.data && data.data.length > 0) {
                    productsGrid.innerHTML = data.data.map(product => `
                        <div class="product-card">
                            <img src="${product.image || 'https://via.placeholder.com/220x220?text=No+Image'}" 
                                 alt="${product.name}" class="product-image">
                            <div class="product-info">
                                <div class="product-category">${product.category?.name || 'عام'}</div>
                                <div class="product-name">${product.name}</div>
                                <div class="product-rating">
                                    ${'⭐'.repeat(product.rating || 0)}
                                </div>
                                <div class="product-price">
                                    <span class="product-current-price">${product.price} SAR</span>
                                    ${product.discount_price ? `<span class="product-original-price">${product.discount_price}</span>` : ''}
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(${product.id})">
                                    أضف إلى السلة
                                </button>
                            </div>
                        </div>
                    `).join('');
                } else {
                    productsGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center;">لا توجد منتجات متاحة</p>';
                }
            } catch (error) {
                console.error('Error loading products:', error);
                document.getElementById('productsGrid').innerHTML = '<p style="color: red;">خطأ في تحميل المنتجات</p>';
            }
        }

        // Load categories
        async function loadCategories() {
            try {
                const response = await fetch(`${API_URL}/categories`);
                const categories = await response.json();
                
                // Categories are already shown as static cards, but this loads them from API
                console.log('Categories loaded:', categories);
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        // Filter by category
        function filterByCategory(categoryName) {
            // For demo, just scroll to products
            document.querySelector('.products-section').scrollIntoView({behavior: 'smooth'});
            // In production, filter by category ID from the API
        }

        // Add to cart
        async function addToCart(productId) {
            try {
                const token = localStorage.getItem('auth_token');
                
                if (!token) {
                    alert('يرجى تسجيل الدخول أولاً');
                    window.location.href = '/login';
                    return;
                }

                const response = await fetch(`${API_URL}/cart/add`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                const data = await response.json();
                
                if (response.ok) {
                    // Animate cart icon
                    animateCart();
                    
                    // Update cart badge
                    const cartBadge = document.getElementById('cartBadge');
                    const newCount = data.cart_count || data.count || 1;
                    cartBadge.textContent = newCount;
                    cartBadge.style.display = 'flex';
                    
                    // Show success toast instead of alert
                    showToast('تم إضافة المنتج إلى السلة ✓');
                } else {
                    alert(data.message || 'خطأ في إضافة المنتج');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                alert('خطأ في إضافة المنتج');
            }
        }

        // Update cart count
        async function updateCartCount() {
            try {
                const token = localStorage.getItem('auth_token');
                const cartBadge = document.getElementById('cartBadge');
                
                if (!token) {
                    cartBadge.style.display = 'none';
                    return;
                }

                const response = await fetch(`${API_URL}/cart`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.count > 0) {
                        cartBadge.textContent = data.count;
                        cartBadge.style.display = 'flex';
                    } else {
                        cartBadge.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value;
            
            if (searchTerm.length < 2) {
                loadProducts();
                return;
            }

            // Filter products on the client side or call search API
            loadProductsBySearch(searchTerm);
        });

        async function loadProductsBySearch(searchTerm) {
            try {
                const response = await fetch(`${API_URL}/products?search=${encodeURIComponent(searchTerm)}`);
                const data = await response.json();
                
                const productsGrid = document.getElementById('productsGrid');
                
                if (data.data && data.data.length > 0) {
                    productsGrid.innerHTML = data.data.map(product => `
                        <div class="product-card">
                            <img src="${product.image || 'https://via.placeholder.com/220x220'}" 
                                 alt="${product.name}" class="product-image">
                            <div class="product-info">
                                <div class="product-category">${product.category?.name || 'عام'}</div>
                                <div class="product-name">${product.name}</div>
                                <div class="product-price">
                                    <span class="product-current-price">${product.price} SAR</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(${product.id})">
                                    أضف إلى السلة
                                </button>
                            </div>
                        </div>
                    `).join('');
                } else {
                    productsGrid.innerHTML = '<p>لا توجد نتائج</p>';
                }
            } catch (error) {
                console.error('Error searching:', error);
            }
        }

        // Cart icon click
        document.getElementById('cartIcon').addEventListener('click', function() {
            window.location.href = '/cart';
        });

        // Account icon click
        document.getElementById('accountIcon').addEventListener('click', function() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                window.location.href = '/profile';
            } else {
                window.location.href = '/login';
            }
        });

        // Update cart and account badges
        function updateBadges() {
            const token = localStorage.getItem('auth_token');
            const cartBadge = document.getElementById('cartBadge');
            const accountBadge = document.getElementById('accountBadge');
            
            if (token) {
                // Show user indicator on account icon
                accountBadge.textContent = '✓';
                accountBadge.style.display = 'flex';
            } else {
                accountBadge.style.display = 'none';
            }
        }

        // Update badges on load
        updateBadges();
        window.addEventListener('storage', updateBadges);

        // Toast notification function
        function showToast(message, duration = 2500) {
            // Remove existing toast
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }
            
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Remove after duration
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // Make animateCart globally available
        window.animateCart = animateCart;
        window.showToast = showToast;
    </script>
</body>
</html>
