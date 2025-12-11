// Elegant Home Page JavaScript

// Create elegant product card
function createProductCardElegant(product) {
    const hasDiscount = product.discount_price && product.discount_price > product.price;
    return `
        <div class="product-card-elegant" onclick="window.location.href='/products/${product.id}'">
            <div class="product-image-elegant">
                <img src="${product.image || 'https://via.placeholder.com/300'}" alt="${product.name}">
                ${hasDiscount ? '<span class="product-badge">خصم</span>' : ''}
            </div>
            <div class="product-info-elegant">
                <div class="product-name-elegant">${product.name}</div>
                <div class="product-price-elegant">
                    <span class="price-current-elegant">${product.price} ر.س</span>
                    ${hasDiscount ? `<span class="price-old-elegant">${product.discount_price} ر.س</span>` : ''}
                </div>
                <div class="product-actions-elegant">
                    <button class="btn-add-cart-elegant" onclick="event.stopPropagation(); addToCart(${product.id})">
                        <i class="fas fa-shopping-cart"></i> أضف للسلة
                    </button>
                    <button class="btn-view-elegant">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Create elegant category card
function createCategoryCardElegant(category) {
    return `
        <div class="category-card-elegant" onclick="window.location.href='/category/${category.slug}'">
            <img src="${category.image || 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=350&fit=crop'}" alt="${category.name}">
            <div class="category-overlay">
                <h3>${category.name}</h3>
            </div>
        </div>
    `;
}

// Load products
async function loadProducts() {
    try {
        const response = await fetch('/api/products');
        const data = await response.json();
        const products = data.data || [];

        if (products.length > 0) {
            // Best Sellers
            const bestSellers = products.slice(0, 6);
            document.getElementById('bestSellers').innerHTML = bestSellers.map(createProductCardElegant).join('');

            // New Arrivals
            const newArrivals = products.slice(6, 12);
            document.getElementById('newArrivals').innerHTML = newArrivals.map(createProductCardElegant).join('');
        }
    } catch (error) {
        console.error('Error loading products:', error);
    }
}

// Load categories
async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const categories = await response.json();

        if (categories.length > 0) {
            document.getElementById('categoriesGrid').innerHTML = categories.slice(0, 4).map(createCategoryCardElegant).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Add to cart
async function addToCart(productId) {
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
            // Update cart count
            if (window.updateCartCount) {
                window.updateCartCount(data.cart_count || data.count || 0);
            }

            // Show success notification
            showNotification('تم إضافة المنتج إلى السلة بنجاح', 'success');
        } else {
            showNotification('حدث خطأ في إضافة المنتج', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('حدث خطأ في إضافة المنتج', 'error');
    }
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : '#ff6b6b'};
        color: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Load data on page load
window.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    loadCategories();
});

// Scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements
setTimeout(() => {
    document.querySelectorAll('.product-card-elegant, .category-card-elegant, .feature-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
}, 100);
