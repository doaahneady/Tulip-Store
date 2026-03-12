<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>المفضلة - Tulip Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/store.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #fafafa;
            min-height: 100vh;
        }
        .favorites-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }
        .favorites-header {
            margin-bottom: 3rem;
            text-align: center;
        }
        .favorites-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.5rem;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        .favorites-title i {
            color: #ff4757;
            font-size: 2.2rem;
        }
        .favorites-subtitle {
            color: #666;
            font-size: 1rem;
        }
        .favorites-count {
            display: inline-block;
            background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);
            color: white;
            padding: 0.4rem 1.2rem;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 1rem;
            font-size: 0.85rem;
            box-shadow: 0 2px 8px rgba(255, 71, 87, 0.2);
        }
        .favorites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 2rem;
        }
        .favorite-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .favorite-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .favorite-card-image-wrapper {
            position: relative;
            overflow: hidden;
            height: 260px;
            background: #f5f5f5;
        }
        .favorite-card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .favorite-card:hover .favorite-card-image {
            transform: scale(1.05);
        }
        .remove-favorite-btn {
            position: absolute;
            top: 0.8rem;
            left: 0.8rem;
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .remove-favorite-btn i {
            color: #ff4757;
            font-size: 1rem;
        }
        .remove-favorite-btn:hover {
            background: #ff4757;
            transform: scale(1.1);
        }
        .remove-favorite-btn:hover i {
            color: white;
        }
        .favorite-card-content {
            padding: 1.3rem;
        }
        .favorite-card-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.05rem;
            color: #1a1a1a;
            margin-bottom: 0.7rem;
            font-weight: 600;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.8rem;
        }
        .favorite-card-price {
            font-size: 1.4rem;
            color: #ff4757;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .favorite-card-actions {
            display: flex;
            gap: 0.4rem;
        }
        .favorite-card-btn {
            flex: 1;
            padding: 0.5rem 0.7rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'El Messiri',sans-serif;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-view {
            background: #f5f5f5;
            color: #333;
            border: 1px solid #e0e0e0;
        }
        .btn-view:hover {
            background: #e8e8e8;
            border-color: #d0d0d0;
        }
        .btn-add-cart {
            background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(255, 71, 87, 0.2);
        }
        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 71, 87, 0.3);
        }
        .empty-favorites {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .empty-favorites i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: #ff4757;
            opacity: 0.3;
        }
        .empty-favorites h2 {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.8rem;
            color: #1a1a1a;
            margin-bottom: 0.8rem;
            font-weight: 600;
        }
        .empty-favorites p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
        }
        .btn-browse {
            display: inline-block;
            padding: 0.9rem 2.5rem;
            background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 71, 87, 0.2);
        }
        .btn-browse:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 71, 87, 0.3);
        }
        @media (max-width: 768px) {
            .favorites-title {
                font-size: 2rem;
            }
            .favorites-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="favorites-container">
        <div class="favorites-header">
            <h1 class="favorites-title">
                <i class="fas fa-heart"></i>
                قائمة المفضلة
            </h1>
            <p class="favorites-subtitle">المنتجات المميزة التي اخترتها بعناية</p>
            <div class="favorites-count" id="favoritesCountBadge" style="display: none;">
                <i class="fas fa-heart"></i> <span id="totalFavorites">0</span> منتج
            </div>
        </div>

        <div id="favoritesContent">
            <div class="empty-favorites">
                <i class="fas fa-heart-broken"></i>
                <h2>قائمة المفضلة فارغة</h2>
                <p>ابدأ بإضافة منتجاتك المفضلة لتجدها هنا بسهولة</p>
                <a href="/" class="btn-browse">
                    <i class="fas fa-shopping-bag"></i> تصفح المنتجات
                </a>
            </div>
        </div>
    </div>

    <script>
        const isAuthenticated = <?php echo auth()->check() ? 'true' : 'false'; ?>;
        function loadFavorites() {
            if (isAuthenticated) {
                fetch('/api/wishlist')
                    .then(r => r.json())
                    .then(d => renderFavorites(d.items || []))
                    .catch(() => {
                        const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                        renderFavorites(favorites);
                    });
            } else {
                const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                renderFavorites(favorites);
            }
        }
        function renderFavorites(favorites) {
            const favoritesContent = document.getElementById('favoritesContent');
            const countBadge = document.getElementById('favoritesCountBadge');
            const totalFavorites = document.getElementById('totalFavorites');
            
            updateFavoritesCount();
            
            if (favorites.length === 0) {
                countBadge.style.display = 'none';
                favoritesContent.innerHTML = `
                    <div class="empty-favorites">
                        <i class="fas fa-heart-broken"></i>
                        <h2>قائمة المفضلة فارغة</h2>
                        <p>ابدأ بإضافة منتجاتك المفضلة لتجدها هنا بسهولة</p>
                        <a href="/" class="btn-browse">
                            تصفح المنتجات
                        </a>
                    </div>
                `;
                return;
            }
            
            // Show count badge
            countBadge.style.display = 'inline-block';
            totalFavorites.textContent = favorites.length;
            
            favoritesContent.innerHTML = `
                <div class="favorites-grid">
                    ${favorites.map(product => `
                        <div class="favorite-card" data-product-id="${product.id}">
                            <div class="favorite-card-image-wrapper">
                                <button class="remove-favorite-btn" onclick="removeFromFavorites(${product.id})">
                                    <i class="fas fa-times"></i>
                                </button>
                                <img src="${product.image || 'https://via.placeholder.com/280x280'}" 
                                     alt="${product.name}" 
                                     class="favorite-card-image"
                                     onclick="window.location.href='/products/${product.id}'">
                            </div>
                            <div class="favorite-card-content">
                                <h3 class="favorite-card-name">${product.name}</h3>
                                <div class="favorite-card-price">$${parseFloat(product.price).toFixed(2)}</div>
                                <div class="favorite-card-actions">
                                    <button class="favorite-card-btn btn-view" onclick="window.location.href='/products/${product.id}'">
                                        عرض
                                    </button>
                                    <button class="favorite-card-btn btn-add-cart" onclick="addToCart(${product.id})">
                                        أضف للسلة
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        function removeFromFavorites(productId) {
            if (isAuthenticated) {
                fetch('/api/wishlist/items/' + productId, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                }).then(() => animateRemoval(productId))
                .catch(() => animateRemoval(productId));
            } else {
                let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                favorites = favorites.filter(p => p.id !== productId);
                localStorage.setItem('favorites', JSON.stringify(favorites));
                animateRemoval(productId);
            }
        }
        function animateRemoval(productId) {
            const card = document.querySelector(`[data-product-id="${productId}"]`);
            if (card) {
                card.style.transform = 'scale(0)';
                card.style.opacity = '0';
                setTimeout(() => {
                    loadFavorites();
                }, 300);
            }
        }
        
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
                    // Show success message
                    const btn = event.target.closest('.btn-add-cart');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة';
                    btn.style.background = 'linear-gradient(135deg, #27ae60 0%, #2ecc71 100%)';
                    
                    // Update cart count using global function
                    if (window.updateCartCount) {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.style.background = '';
                    }, 2000);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
            }
        }
        
        function updateFavoritesCount() {
            const countElement = document.getElementById('favoritesCount');
            if (isAuthenticated) {
                fetch('/api/wishlist')
                    .then(r => r.json())
                    .then(d => {
                        if (countElement) {
                            const c = d.count || (d.items ? d.items.length : 0) || 0;
                            countElement.textContent = c > 99 ? '+99' : c;
                        }
                    })
                    .catch(() => {
                        const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                        if (countElement) {
                            countElement.textContent = favorites.length > 99 ? '+99' : favorites.length;
                        }
                    });
            } else {
                const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
                if (countElement) {
                    countElement.textContent = favorites.length > 99 ? '+99' : favorites.length;
                }
            }
        }
        
        window.addEventListener('DOMContentLoaded', loadFavorites);
    </script>
</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/favorites.blade.php ENDPATH**/ ?>