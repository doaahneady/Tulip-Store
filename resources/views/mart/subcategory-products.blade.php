<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $subcategory->name }} - توليب مارت</title>
    <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <link rel="stylesheet" href="/css/store.css?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #059669;
            --primary-light: #10b981;
            --primary-dark: #047857;
            --secondary: #f59e0b;
            --accent: #ef4444;
            --success: #22c55e;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'El Messiri', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            padding: 2rem;
            text-align: center;
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .breadcrumb {
            max-width: 1400px;
            margin: 1.5rem auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #6b7280;
            flex-wrap: wrap;
        }
        
        .breadcrumb a {
            color: #059669;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .breadcrumb a:hover {
            color: #047857;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .product-card {
            background: var(--card);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 2px solid transparent;
            box-shadow: var(--shadow);
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border-color: var(--primary-light);
        }
        
        .product-badges {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            z-index: 5;
        }
        
        .badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }
        
        .badge-sale { background: var(--accent); color: #fff; }
        .badge-new { background: var(--info); color: #fff; }
        .badge-fresh { background: var(--success); color: #fff; }
        
        .product-image {
            aspect-ratio: 1 / 1;
            width: 100%;
            height: auto;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .product-image img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        }
        
        .product-favorite {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d1d5db;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            z-index: 10;
        }
        
        .product-favorite:hover,
        .product-favorite.active {
            color: var(--accent);
            background: #fff;
            transform: scale(1.1);
        }
        
        .product-body {
            padding: 0.8rem;
        }
        
        .product-category {
            font-size: 0.7rem;
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.1rem;
            letter-spacing: 0.5px;
        }
        
        .product-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 0.95rem;
            color: var(--text);
            margin-bottom: 0.1rem;
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .product-origin {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .product-footer {
            display: block;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
        }
        
        .price-info {
            display: flex;
            flex-direction: row;
            align-items: baseline;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }
        
        .price-current {
            font-family:'El Messiri',sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-dark);
        }
        
        .price-old {
            font-size: 0.8rem;
            color: #9ca3af;
            text-decoration: line-through;
        }
        
        .price-unit {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-inline-start: auto;
        }
        
        .add-to-cart {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-family: 'El Messiri', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .add-to-cart:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }
        
        .add-to-cart.added {
            background: var(--success);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        @media (max-width: 1200px) {
            .products-grid { grid-template-columns: repeat(4, 1fr); }
        }
        
        @media (max-width: 992px) {
            .products-grid { grid-template-columns: repeat(3, 1fr); }
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 1.5rem 1rem;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .breadcrumb {
                padding: 0 1rem;
                font-size: 0.8rem;
            }
            
            .container {
                padding: 1rem;
            }
            
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .product-card:hover {
                transform: none;
                box-shadow: var(--shadow);
                border-color: transparent;
            }
        }
        
        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="header">
        <h1>{{ $subcategory->name }}</h1>
    </div>
    
    <div class="breadcrumb">
        <a href="{{ route('mart.index') }}"><i class="fas fa-home"></i> الرئيسية</a>
        <i class="fas fa-chevron-left"></i>
        <a href="{{ route('mart.category', $category->slug) }}">{{ $category->name }}</a>
        <i class="fas fa-chevron-left"></i>
        <span>{{ $subcategory->name }}</span>
    </div>
    
    <div class="container">
        @if($products->count() > 0)
            <div class="products-grid" id="productsGrid">
                <!-- Products will be loaded here by JavaScript -->
            </div>
            
            <script>
                // Pass product IDs to JavaScript
                const productIds = @json($products->pluck('id'));
            </script>
                @foreach($products as $product)
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h2>لا توجد منتجات</h2>
                <p>لم يتم إضافة منتجات لهذا القسم بعد</p>
            </div>
        @endif
    </div>
    
    <script>
        let favoriteIds = new Set();
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const categoryName = "{{ $category->name }}";
        
        // Currency conversion - same as mart main page
        document.addEventListener('DOMContentLoaded', async () => {
            await loadFavoriteIds();
            await loadProducts();
        });
        
        // Load products from API with attributes
        async function loadProducts() {
            try {
                const response = await fetch('/api/products?market=mart&include_attributes=1&subcategory={{ $subcategory->slug }}&category={{ $category->slug }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                const products = Array.isArray(data.data) ? data.data : [];
                
                if (products.length === 0) {
                    document.getElementById('productsGrid').innerHTML = '<div style="grid-column:1/-1; text-align:center; color:#6b7280; padding:4rem;">لا توجد منتجات</div>';
                    return;
                }
                
                document.getElementById('productsGrid').innerHTML = products.map(p => createProductCard(p)).join('');
                updateFavoriteButtons();
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }
        
        function createProductCard(p) {
            const attrs = Array.isArray(p.attributes) ? p.attributes : [];
            const unit = (attrs.find(a => a.name === 'unit')?.value || attrs.find(a => a.name === 'unit')?.value_text) || 'حبة';
            const origin = (attrs.find(a => a.name === 'origin')?.value || attrs.find(a => a.name === 'origin')?.value_text) || 'محلي';
            const price = parseFloat(p.discount_price || p.price || 0);
            const oldPrice = p.discount_price ? parseFloat(p.price || 0) : null;
            const imageUrl = p.primary_image_url || p.image || '/images/tulip_store.jpg';
            const isFav = favoriteIds.has(String(p.id));
            
            return `
                <div class="product-card" data-id="${p.id}">
                    ${p.discount_price ? '<div class="product-badges"><span class="badge badge-sale">عرض</span></div>' : ''}
                    
                    <div class="product-image" style="background: #f3f4f6;">
                        <button class="product-favorite" onclick="toggleFavorite(${p.id}, event)">
                            <i class="${isFav ? 'fas' : 'far'} fa-heart"></i>
                        </button>
                        <img src="${imageUrl}" alt="${p.name}" onerror="this.src='/images/tulip_store.jpg'">
                    </div>
                    
                    <div class="product-body">
                        <div class="product-category">${categoryName}</div>
                        <h3 class="product-name">${p.name}</h3>
                        <div class="product-origin">
                            <i class="fas fa-map-marker-alt"></i>
                            ${origin}
                        </div>
                        <div class="product-footer">
                            <div class="price-info">
                                <span class="price-current">${window.formatMoney ? window.formatMoney(price) : (price.toFixed(2) + ' $')}</span>
                                ${oldPrice ? `<span class="price-old">${window.formatMoney ? window.formatMoney(oldPrice) : (oldPrice.toFixed(2) + ' $')}</span>` : ''}
                                <span class="price-unit">لكل ${unit}</span>
                            </div>
                            <button class="add-to-cart" onclick="addToCart(${p.id}, event)" id="btn-${p.id}">
                                <i class="fas fa-plus"></i>
                                أضف
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
        
        async function loadFavoriteIds() {
            favoriteIds = new Set();
            if (isAuthenticated) {
                try {
                    const r = await fetch('/api/wishlist', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    const d = await r.json();
                    const items = Array.isArray(d.items) ? d.items : [];
                    items.forEach((it) => {
                        if (it && it.id !== undefined && it.id !== null) favoriteIds.add(String(it.id));
                    });
                    return;
                } catch (e) {}
            }
            const items = JSON.parse(localStorage.getItem('favorites') || '[]');
            (Array.isArray(items) ? items : []).forEach((it) => {
                const id = String(it?.id ?? '');
                if (id && !id.startsWith('gift-')) favoriteIds.add(id);
            });
        }
        
        function updateFavoriteButtons() {
            document.querySelectorAll('.product-card').forEach(card => {
                const productId = card.dataset.id;
                const btn = card.querySelector('.product-favorite');
                const icon = btn.querySelector('i');
                const isFav = favoriteIds.has(String(productId));
                
                btn.classList.toggle('active', isFav);
                icon.classList.toggle('far', !isFav);
                icon.classList.toggle('fas', isFav);
            });
        }
        
        function toggleFavorite(productId, event) {
            event?.stopPropagation?.();
            const btn = event.target.closest('.product-favorite');
            const icon = btn.querySelector('i');
            const id = String(productId);
            
            const setIcon = (isFav) => {
                btn.classList.toggle('active', !!isFav);
                icon.classList.toggle('far', !isFav);
                icon.classList.toggle('fas', !!isFav);
            };
            
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
                .then((d) => {
                    if (!d || !d.success) return;
                    if (d.action === 'added') favoriteIds.add(id);
                    if (d.action === 'removed') favoriteIds.delete(id);
                    setIcon(favoriteIds.has(id));
                })
                .catch(() => {});
                return;
            }
            
            // Local storage for guests
            const items = JSON.parse(localStorage.getItem('favorites') || '[]');
            const list = Array.isArray(items) ? items : [];
            const idx = list.findIndex((x) => String(x?.id) === id);
            
            if (idx >= 0) {
                list.splice(idx, 1);
                favoriteIds.delete(id);
            } else {
                list.unshift({ id, type: 'product' });
                favoriteIds.add(id);
            }
            
            localStorage.setItem('favorites', JSON.stringify(list));
            setIcon(favoriteIds.has(id));
        }
        
        async function addToCart(productId, event) {
            if (event) event.stopPropagation();
            
            const btn = document.getElementById(`btn-${productId}`);
            const originalContent = btn.innerHTML;
            
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
                        quantity: 1
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update cart count in navbar
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
                    // Animate cart icon
                    if (typeof window.animateCartIcon === 'function') {
                        window.animateCartIcon();
                    }
                    
                    // Show success
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت';
                    btn.classList.add('added');
                    
                    // Show toast
                    if (typeof window.showToast === 'function') {
                        window.showToast('✓ تم إضافة المنتج إلى السلة');
                    }
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                        btn.classList.remove('added');
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                    alert(data.message || 'حدث خطأ أثناء إضافة المنتج');
                }
            } catch (error) {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = originalContent;
                alert('حدث خطأ أثناء إضافة المنتج');
            }
        }
    </script>
</body>
</html>
