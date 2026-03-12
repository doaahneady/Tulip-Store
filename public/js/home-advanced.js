// ============================================
// TULIP STORE - ADVANCED PERSONALIZATION WITH DATABASE
// ============================================

// Activity Tracker with Database Integration
class DatabaseActivityTracker {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    async track(activityType, data = {}) {
        try {
            await fetch('/api/activity/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    activity_type: activityType,
                    ...data
                })
            });
        } catch (error) {
            console.error('Error tracking activity:', error);
        }
    }

    async getRecommendations() {
        try {
            const response = await fetch('/api/activity/recommendations');
            if (!response.ok) throw new Error('Failed to get recommendations');
            return await response.json();
        } catch (error) {
            console.error('Error getting recommendations:', error);
            return {
                personalized_products: [],
                recommended_categories: {},
                search_suggestions: []
            };
        }
    }

    trackProductView(productId, categoryId) {
        this.track('view', { product_id: productId, category_id: categoryId });
    }

    trackSearch(query) {
        if (query && query.length > 2) {
            this.track('search', { search_query: query });
        }
    }

    trackCartAdd(productId) {
        this.track('cart_add', { product_id: productId });
    }

    trackPurchase(productId) {
        this.track('purchase', { product_id: productId });
    }
}

// Initialize tracker
const activityTracker = new DatabaseActivityTracker();

// ============================================
// MODERN SLIDER
// ============================================

const sliderData = [
    {
        image: 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=900&h=550&fit=crop',
        title: 'أرسل ابتسامتك أينما كنت',
        subtitle: 'تسوق معنا أفضل المنتجات والعروض'
    },
    {
        image: 'https://images.unsplash.com/photo-1607083206869-4c7672e72a8a?w=900&h=550&fit=crop',
        title: 'هدايا فاخرة تعكس ذوقك',
        subtitle: 'لحظات استثنائية تستحق هدايا مميزة'
    },
    {
        image: 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=900&h=550&fit=crop',
        title: 'عروض حصرية لفترة محدودة',
        subtitle: 'وفّر على مجموعة مختارة من الهدايا'
    },
    {
        image: 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=900&h=550&fit=crop',
        title: 'مجوهرات فاخرة',
        subtitle: 'قطع فريدة تضيف لمسة من الأناقة'
    },
    {
        image: 'https://images.unsplash.com/photo-1549298916-c6c5f85fa167?w=900&h=550&fit=crop',
        title: 'وصل حديثاً',
        subtitle: 'اكتشف أحدث المنتجات في متجرنا'
    }
];

let currentModernSlide = 0;

function initializeModernSlider() {
    const container = document.getElementById('modernSlider');
    const dotsContainer = document.getElementById('modernSliderDots');
    
    if (!container || !dotsContainer) return;
    
    // Create slides
    sliderData.forEach((slide, index) => {
        const slideEl = document.createElement('div');
        slideEl.className = 'modern-slide';
        slideEl.innerHTML = `
            <img src="${slide.image}" alt="${slide.title}">
            <div class="modern-slide-content">
                <h2 style="font-family:"El Messiri", sans-serif; font-size:2.5rem; font-weight:900; margin:0 0 1rem 0;">${slide.title}</h2>
                <p style="font-family:"El Messiri", sans-serif; font-size:1.3rem; margin:0;">${slide.subtitle}</p>
            </div>
        `;
        container.appendChild(slideEl);
        
        // Create dot
        const dot = document.createElement('button');
        dot.onclick = () => goToModernSlide(index);
        dot.style.cssText = 'width:14px; height:14px; border-radius:50%; border:2px solid #2a7080; background:transparent; cursor:pointer; transition:all 0.3s;';
        dotsContainer.appendChild(dot);
    });
    
    updateModernSliderPositions();
    
    // Auto-advance
    setInterval(() => changeModernSlide(1), 6000);
}

function updateModernSliderPositions() {
    const slides = document.querySelectorAll('.modern-slide');
    const dots = document.querySelectorAll('#modernSliderDots button');
    const total = slides.length;
    
    slides.forEach((slide, index) => {
        const diff = (index - currentModernSlide + total) % total;
        
        if (diff === 0) {
            slide.className = 'modern-slide active';
        } else if (diff === 1) {
            slide.className = 'modern-slide next';
        } else if (diff === total - 1) {
            slide.className = 'modern-slide prev';
        } else {
            slide.className = 'modern-slide hidden';
        }
    });
    
    dots.forEach((dot, index) => {
        if (index === currentModernSlide) {
            dot.style.background = '#2a7080';
            dot.style.transform = 'scale(1.4)';
        } else {
            dot.style.background = 'transparent';
            dot.style.transform = 'scale(1)';
        }
    });
}

function changeModernSlide(direction) {
    const total = sliderData.length;
    currentModernSlide = (currentModernSlide + direction + total) % total;
    updateModernSliderPositions();
}

function goToModernSlide(index) {
    currentModernSlide = index;
    updateModernSliderPositions();
}

// ============================================
// CATEGORIES SCROLL FUNCTIONS
// ============================================

function scrollCategoriesLeft() {
    document.getElementById('categoriesScroll').scrollBy({left: 300, behavior: 'smooth'});
}

function scrollCategoriesRight() {
    document.getElementById('categoriesScroll').scrollBy({left: -300, behavior: 'smooth'});
}

// ============================================
// PRODUCT CARD FUNCTIONS
// ============================================

function createProductCard(p, style = 'default') {
    const cardStyles = {
        default: { bg: '#fff', borderColor: '#2a7080', priceColor: '#2a7080' },
        trending: { bg: 'rgba(255,255,255,0.98)', borderColor: '#ff6b35', priceColor: '#ff6b35' },
        flash: { bg: '#fff', borderColor: '#ff6b35', priceColor: '#cc0c39' },
        gold: { bg: '#fff', borderColor: '#d4af37', priceColor: '#d4af37' },
        pink: { bg: '#fff', borderColor: '#e91e63', priceColor: '#e91e63' }
    };
    
    const s = cardStyles[style] || cardStyles.default;
    
    return `
        <div onclick="window.location.href='/products/${p.id}'" style="cursor:pointer;background:${s.bg};border:1px solid #e8e8e8;border-radius:16px;overflow:hidden;transition:all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);" onmouseover="this.style.boxShadow='0 12px 30px rgba(0,0,0,0.15)';this.style.borderColor='${s.borderColor}';this.style.transform='translateY(-8px) scale(1.02)'" onmouseout="this.style.boxShadow='none';this.style.borderColor='#e8e8e8';this.style.transform='translateY(0) scale(1)'">
            <div style="aspect-ratio:1/1;overflow:hidden;background:#f8f8f8;position:relative;">
                <img src="${p.image || 'https://via.placeholder.com/350'}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s ease;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            <div style="padding:1.2rem;">
                <h3 style="font-family:"El Messiri", sans-serif;font-size:1rem;font-weight:600;color:#1a1a1a;margin:0 0 0.8rem 0;line-height:1.5;height:3em;overflow:hidden;">${p.name}</h3>
                <div style="font-family:"El Messiri", sans-serif;font-size:1.3rem;font-weight:800;color:${s.priceColor};margin-bottom:1rem;">${p.price}</div>
                <button onclick="event.stopPropagation();addToCart(${p.id},this)" class="add-cart-btn" style="width:100%;background:#ff6b35;color:#fff;border:none;padding:0.8rem;font-size:1rem;font-weight:700;cursor:pointer;font-family:"El Messiri", sans-serif;border-radius:10px;transition:all 0.3s;" onmouseover="this.style.background='#e55a2b';this.style.transform='scale(1.03)'" onmouseout="if(!this.classList.contains('added')){this.style.background='#ff6b35';this.style.transform='scale(1)'}">
                    أضف للسلة
                </button>
            </div>
        </div>
    `;
}

function createDiscountCard(p) {
    const originalPrice = (parseFloat(p.price.replace(/[^\d.]/g, '')) * 1.3).toFixed(2);
    return `
        <div onclick="window.location.href='/products/${p.id}'" style="cursor:pointer;background:#fff;border:1px solid #e8e8e8;border-radius:16px;overflow:hidden;transition:all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);position:relative;" onmouseover="this.style.boxShadow='0 12px 30px rgba(255,107,53,0.25)';this.style.borderColor='#ff6b35';this.style.transform='translateY(-8px) scale(1.02)'" onmouseout="this.style.boxShadow='none';this.style.borderColor='#e8e8e8';this.style.transform='translateY(0) scale(1)'">
            <div style="position:absolute;top:1rem;right:1rem;background:#cc0c39;color:#fff;padding:0.5rem 1rem;border-radius:25px;font-size:0.8rem;font-weight:800;z-index:2;box-shadow:0 6px 15px rgba(204,12,57,0.4);">خصم 30%</div>
            <div style="aspect-ratio:1/1;overflow:hidden;background:#f8f8f8;">
                <img src="${p.image || 'https://via.placeholder.com/350'}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s ease;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            <div style="padding:1.2rem;">
                <h3 style="font-family:"El Messiri", sans-serif;font-size:1rem;font-weight:600;color:#1a1a1a;margin:0 0 0.8rem 0;line-height:1.5;height:3em;overflow:hidden;">${p.name}</h3>
                <div style="display:flex;align-items:center;gap:0.8rem;margin-bottom:1rem;">
                    <div style="font-family:"El Messiri", sans-serif;font-size:1.3rem;font-weight:800;color:#cc0c39;">${p.price}</div>
                    <div style="font-family:"El Messiri", sans-serif;font-size:1rem;color:#999;text-decoration:line-through;">${originalPrice}</div>
                </div>
                <button onclick="event.stopPropagation();addToCart(${p.id},this)" class="add-cart-btn" style="width:100%;background:#ff6b35;color:#fff;border:none;padding:0.8rem;font-size:1rem;font-weight:700;cursor:pointer;font-family:"El Messiri", sans-serif;border-radius:10px;transition:all 0.3s;" onmouseover="this.style.background='#e55a2b';this.style.transform='scale(1.03)'" onmouseout="if(!this.classList.contains('added')){this.style.background='#ff6b35';this.style.transform='scale(1)'}">
                    أضف للسلة
                </button>
            </div>
        </div>
    `;
}

function createJewelryCard(p) {
    return `
        <div onclick="window.location.href='/products/${p.id}'" style="cursor:pointer;background:#fff;border:1px solid #e8e8e8;border-radius:16px;overflow:hidden;transition:all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);" onmouseover="this.style.boxShadow='0 12px 30px rgba(212,175,55,0.25)';this.style.borderColor='#d4af37';this.style.transform='translateY(-8px) scale(1.02)'" onmouseout="this.style.boxShadow='none';this.style.borderColor='#e8e8e8';this.style.transform='translateY(0) scale(1)'">
            <div style="aspect-ratio:1/1;overflow:hidden;background:#f8f8f8;position:relative;">
                <img src="${p.image || 'https://via.placeholder.com/350'}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s ease;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                <div style="position:absolute;top:1rem;right:1rem;background:rgba(212,175,55,0.95);color:#fff;padding:0.4rem 1rem;border-radius:25px;font-size:0.75rem;font-weight:700;box-shadow:0 6px 15px rgba(212,175,55,0.4);"><i class="fas fa-gem"></i> مجوهرات</div>
            </div>
            <div style="padding:1.2rem;">
                <h3 style="font-family:"El Messiri", sans-serif;font-size:1rem;font-weight:600;color:#1a1a1a;margin:0 0 0.8rem 0;line-height:1.5;height:3em;overflow:hidden;">${p.name}</h3>
                <div style="font-family:"El Messiri", sans-serif;font-size:1.3rem;font-weight:800;color:#d4af37;margin-bottom:1rem;">${p.price}</div>
                <button onclick="event.stopPropagation();addToCart(${p.id},this)" class="add-cart-btn" style="width:100%;background:#ff6b35;color:#fff;border:none;padding:0.8rem;font-size:1rem;font-weight:700;cursor:pointer;font-family:"El Messiri", sans-serif;border-radius:10px;transition:all 0.3s;" onmouseover="this.style.background='#e55a2b';this.style.transform='scale(1.03)'" onmouseout="if(!this.classList.contains('added')){this.style.background='#ff6b35';this.style.transform='scale(1)'}">
                    أضف للسلة
                </button>
            </div>
        </div>
    `;
}

// ============================================
// LOAD CATEGORIES
// ============================================

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        if (!response.ok) throw new Error('Failed to load categories');
        const categories = await response.json();
        
        if (categories.length === 0) {
            document.getElementById('categoriesGrid').innerHTML = '<div style="padding:3rem; color:#999;">لا توجد فئات متاحة حالياً</div>';
            return;
        }
        
        document.getElementById('categoriesGrid').innerHTML = categories.map(c => `
            <div class="category-card" onclick="window.location.href='/category/${c.slug}'">
                <div class="category-image">
                    <img src="${c.image || 'https://via.placeholder.com/180'}" alt="${c.name}">
                </div>
                <p class="category-name">${c.name}</p>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error loading categories:', error);
        document.getElementById('categoriesGrid').innerHTML = '<div style="padding:3rem; color:#999;">حدث خطأ في تحميل الفئات</div>';
    }
}

// ============================================
// LOAD PRODUCTS WITH PERSONALIZATION
// ============================================

async function loadPersonalizedProducts() {
    try {
        const recommendations = await activityTracker.getRecommendations();
        const personalizedProducts = recommendations.personalized_products || [];
        
        if (personalizedProducts.length === 0) {
            // Fallback to regular products
            const response = await fetch('/api/products');
            if (!response.ok) throw new Error('Failed to load products');
            const data = await response.json();
            const products = (data.data || []).slice(0, 5);
            document.getElementById('personalizedProducts').innerHTML = products.map(p => createProductCard(p, 'default')).join('');
        } else {
            document.getElementById('personalizedProducts').innerHTML = personalizedProducts.slice(0, 5).map(p => createProductCard(p, 'default')).join('');
        }
    } catch (error) {
        console.error('Error loading personalized products:', error);
        document.getElementById('personalizedProducts').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">حدث خطأ في تحميل المنتجات</div>';
    }
}

async function loadTrendingProducts() {
    try {
        const response = await fetch('/api/products');
        if (!response.ok) throw new Error('Failed to load products');
        const data = await response.json();
        const products = (data.data || []).slice(0, 5);
        
        if (products.length === 0) {
            document.getElementById('trendingProducts').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:rgba(255,255,255,0.5);">لا توجد منتجات متاحة حالياً</div>';
            return;
        }
        
        document.getElementById('trendingProducts').innerHTML = products.map(p => createProductCard(p, 'trending')).join('');
    } catch (error) {
        console.error('Error loading trending products:', error);
        document.getElementById('trendingProducts').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:rgba(255,255,255,0.5);">حدث خطأ في تحميل المنتجات</div>';
    }
}

async function loadFlashDeals() {
    try {
        const response = await fetch('/api/products');
        if (!response.ok) throw new Error('Failed to load products');
        const data = await response.json();
        const products = (data.data || []).slice(5, 10);
        
        if (products.length === 0) {
            document.getElementById('flashDeals').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:rgba(255,255,255,0.7);">لا توجد عروض متاحة حالياً</div>';
            return;
        }
        
        document.getElementById('flashDeals').innerHTML = products.map(p => createDiscountCard(p)).join('');
        startFlashTimer();
    } catch (error) {
        console.error('Error loading flash deals:', error);
        document.getElementById('flashDeals').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:rgba(255,255,255,0.7);">حدث خطأ في تحميل العروض</div>';
    }
}

async function loadDiscountItems() {
    try {
        const response = await fetch('/api/products');
        if (!response.ok) throw new Error('Failed to load products');
        const data = await response.json();
        const products = (data.data || []).slice(10, 15);
        
        if (products.length === 0) {
            document.getElementById('discountItems').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">لا توجد عروض متاحة حالياً</div>';
            return;
        }
        
        document.getElementById('discountItems').innerHTML = products.map(createDiscountCard).join('');
    } catch (error) {
        console.error('Error loading discount items:', error);
        document.getElementById('discountItems').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">حدث خطأ في تحميل العروض</div>';
    }
}

async function loadCategoryBestsellers() {
    try {
        const recommendations = await activityTracker.getRecommendations();
        const favoriteCategories = recommendations.recommended_categories || {};
        
        // Get top category
        const topCategoryId = Object.keys(favoriteCategories).sort((a, b) => favoriteCategories[b] - favoriteCategories[a])[0];
        
        if (topCategoryId) {
            // Load products from favorite category
            const response = await fetch(`/api/products/category/${topCategoryId}`);
            if (response.ok) {
                const data = await response.json();
                const products = (data.data || data || []).slice(0, 5);
                
                // Update category name if available
                const categoryResponse = await fetch(`/api/categories/${topCategoryId}`);
                if (categoryResponse.ok) {
                    const category = await categoryResponse.json();
                    document.getElementById('favoriteCategoryName').textContent = category.name || 'فئتك المفضلة';
                }
                
                document.getElementById('categoryBestsellerProducts').innerHTML = products.map(p => createProductCard(p, 'gold')).join('');
                return;
            }
        }
        
        // Fallback
        const response = await fetch('/api/products');
        if (!response.ok) throw new Error('Failed to load products');
        const data = await response.json();
        const products = (data.data || []).slice(15, 20);
        document.getElementById('categoryBestsellerProducts').innerHTML = products.map(p => createProductCard(p, 'gold')).join('');
    } catch (error) {
        console.error('Error loading category bestsellers:', error);
        document.getElementById('categoryBestsellerProducts').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">حدث خطأ في تحميل المنتجات</div>';
    }
}

async function loadRecommendedProducts() {
    try {
        const response = await fetch('/api/products');
        if (!response.ok) throw new Error('Failed to load products');
        const data = await response.json();
        const products = (data.data || []).slice(20, 25);
        
        if (products.length === 0) {
            document.getElementById('recommendedProducts').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">لا توجد توصيات متاحة حالياً</div>';
            return;
        }
        
        document.getElementById('recommendedProducts').innerHTML = products.map(p => createProductCard(p, 'pink')).join('');
    } catch (error) {
        console.error('Error loading recommended products:', error);
        document.getElementById('recommendedProducts').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">حدث خطأ في تحميل التوصيات</div>';
    }
}

async function loadJewelries() {
    try {
        const response = await fetch('/api/products');
        if (!response.ok) throw new Error('Failed to load products');
        const data = await response.json();
        const products = (data.data || []).slice(25, 30);
        
        if (products.length === 0) {
            document.getElementById('jewelries').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">لا توجد مجوهرات متاحة حالياً</div>';
            return;
        }
        
        document.getElementById('jewelries').innerHTML = products.map(createJewelryCard).join('');
    } catch (error) {
        console.error('Error loading jewelries:', error);
        document.getElementById('jewelries').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">حدث خطأ في تحميل المجوهرات</div>';
    }
}

async function loadNewArrivals() {
    try {
        const response = await fetch('/api/products');
        if (!response.ok) throw new Error('Failed to load products');
        const data = await response.json();
        const products = (data.data || []).slice(30, 35);
        
        if (products.length === 0) {
            document.getElementById('newArrivals').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">لا توجد منتجات جديدة حالياً</div>';
            return;
        }
        
        document.getElementById('newArrivals').innerHTML = products.map(p => createProductCard(p, 'default')).join('');
    } catch (error) {
        console.error('Error loading new arrivals:', error);
        document.getElementById('newArrivals').innerHTML = '<div style="grid-column:1/-1; padding:3rem; color:#999;">حدث خطأ في تحميل المنتجات الجديدة</div>';
    }
}

// ============================================
// FLASH TIMER
// ============================================

function startFlashTimer() {
    const timerEl = document.getElementById('flashTimer');
    if (!timerEl) return;
    
    const endTime = Date.now() + (6 * 60 * 60 * 1000);
    
    function updateTimer() {
        const now = Date.now();
        const remaining = Math.max(0, endTime - now);
        
        const hours = Math.floor(remaining / (1000 * 60 * 60));
        const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
        
        const timeStr = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        const display = timerEl.querySelector('div');
        if (display) {
            display.textContent = timeStr;
        }
        
        if (remaining > 0) {
            setTimeout(updateTimer, 1000);
        }
    }
    
    updateTimer();
}

// ============================================
// ADD TO CART
// ============================================

async function addToCart(productId, buttonElement) {
    try {
        // Track activity
        activityTracker.trackCartAdd(productId);
        
        // Change button to green with tick
        if (buttonElement) {
            buttonElement.classList.add('added');
            buttonElement.style.background = '#28a745';
            buttonElement.innerHTML = '<i class="fas fa-check"></i> تمت';
            buttonElement.disabled = true;
        }
        
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        const data = await response.json();
        if (data.success && window.updateCartCount) {
            window.updateCartCount(data.cart_count || data.count || 0);
        }
        
        // Reset button after 1.5 seconds
        if (buttonElement) {
            setTimeout(() => {
                buttonElement.classList.remove('added');
                buttonElement.style.background = '#ff6b35';
                buttonElement.innerHTML = 'أضف للسلة';
                buttonElement.disabled = false;
            }, 1500);
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        if (buttonElement) {
            buttonElement.style.background = '#dc3545';
            buttonElement.innerHTML = '<i class="fas fa-times"></i> خطأ';
            setTimeout(() => {
                buttonElement.classList.remove('added');
                buttonElement.style.background = '#ff6b35';
                buttonElement.innerHTML = 'أضف للسلة';
                buttonElement.disabled = false;
            }, 2000);
        }
    }
}

// ============================================
// INITIALIZE ON PAGE LOAD
// ============================================

window.addEventListener('DOMContentLoaded', () => {
    // Initialize slider
    initializeModernSlider();
    
    // Load all sections
    loadCategories();
    loadPersonalizedProducts();
    loadTrendingProducts();
    loadFlashDeals();
    loadDiscountItems();
    loadCategoryBestsellers();
    loadRecommendedProducts();
    loadJewelries();
    loadNewArrivals();
});

// Track product clicks
document.addEventListener('click', (e) => {
    const productCard = e.target.closest('[onclick*="/products/"]');
    if (productCard) {
        const match = productCard.getAttribute('onclick').match(/\/products\/(\d+)/);
        if (match) {
            activityTracker.trackProductView(parseInt(match[1]));
        }
    }
});
