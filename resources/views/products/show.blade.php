<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} - Tulip Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/store.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        body {
            background: #f5f5f5;
        }
        .product-container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .product-grid {
            display: grid;
            grid-template-columns: 1fr 440px;
            gap: 2rem;
            background: #f8f8f8;
            padding: 2rem;
            border-radius: 20px;
        }
        
        /* Left Side - Images */
        .product-images {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .main-image-container {
            position: relative;
            background: #e8e8e8;
            border-radius: 20px;
            overflow: hidden;
            height: 320px;
        }
        .main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .favorite-btn-large {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        .favorite-btn-large i {
            color: #666;
            font-size: 1.1rem;
        }
        .favorite-btn-large:hover i {
            color: #ff4757;
        }
        .favorite-btn-large.active i {
            color: #ff4757;
        }
        .thumbnails {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.8rem;
        }
        .thumbnail {
            background: #e8e8e8;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            height: 80px;
        }
        .thumbnail.active {
            border-color: #666;
        }
        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Right Side - Details */
        .product-details {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
        }
        .product-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 0.8rem;
            font-weight: 600;
            text-align: right;
        }
        .product-rating {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.3rem;
            margin-bottom: 1.2rem;
        }
        .stars {
            display: flex;
            gap: 0.2rem;
        }
        .stars i {
            font-size: 0.95rem;
            color: #ffc107;
        }
        .stars i.empty {
            color: #ddd;
        }
        .price-section {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.8rem;
            margin-bottom: 1.2rem;
        }
        .discount-badge {
            background: #ff4444;
            color: white;
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .old-price {
            color: #999;
            text-decoration: line-through;
            font-size: 0.95rem;
        }
        .current-price {
            font-size: 1.6rem;
            font-weight: 700;
            color: #333;
        }
        .viewers-info {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.5rem;
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }
        .viewers-info i {
            font-size: 0.95rem;
        }
        
        /* Sizes */
        .section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 0.7rem;
            text-align: right;
        }
        .sizes-grid {
            display: flex;
            gap: 0.6rem;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }
        .size-option {
            width: 45px;
            height: 45px;
            border: 1px solid #ddd;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;
        }
        .size-option.active {
            background: #ff6b6b;
            color: white;
            border-color: #ff6b6b;
        }
        
        /* Colors */
        .colors-grid {
            display: flex;
            gap: 0.7rem;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }
        .color-option {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            position: relative;
        }
        .color-option.active::after {
            content: '';
            position: absolute;
            inset: -5px;
            border: 2px solid #ff6b6b;
            border-radius: 50%;
        }
        
        /* Quantity and Add to Cart */
        .quantity-cart-section {
            display: flex;
            gap: 0.8rem;
            margin-bottom: 1.2rem;
            flex-direction: row-reverse;
        }
        .add-to-cart-btn {
            flex: 1;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #ff6b35 0%, #ff8555 100%);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }
        .quantity-selector {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 20px;
            overflow: hidden;
            background: white;
        }
        .quantity-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: white;
            cursor: pointer;
            font-size: 1.1rem;
            color: #666;
            transition: all 0.2s ease;
        }
        .quantity-btn:hover {
            background: #f5f5f5;
        }
        .quantity-input {
            width: 45px;
            text-align: center;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        /* Share Button */
        .share-btn {
            width: 100%;
            padding: 0.75rem;
            background: white;
            color: #0f4f55;
            border: 1px solid #0f4f55;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        .share-btn:hover {
            background: #0f4f55;
            color: white;
        }
        
        /* Shipping Info */
        .shipping-info {
            border-top: 1px solid #eee;
            padding-top: 1.2rem;
        }
        .shipping-item {
            display: flex;
            align-items: start;
            gap: 0.8rem;
            padding: 0.6rem 0;
            text-align: right;
        }
        .shipping-item i {
            color: #666;
            font-size: 1.1rem;
            margin-top: 0.1rem;
        }
        .shipping-text {
            flex: 1;
        }
        .shipping-text strong {
            display: block;
            color: #333;
            margin-bottom: 0.2rem;
            font-size: 0.85rem;
        }
        .shipping-text span {
            color: #666;
            font-size: 0.8rem;
        }
        
        @media (max-width: 1024px) {
            .product-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('components.navbar')
    <div class="product-container">
        <div class="product-grid">
            <!-- Left Side - Product Details -->
            <div class="product-details">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <div class="product-rating">
                    <div class="stars">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star {{ $i < 4 ? '' : 'empty' }}"></i>
                        @endfor
                    </div>
                </div>

                <div class="price-section">
                    @if($product->discount_price)
                        @php
                            $discount = round((($product->price - $product->discount_price) / $product->price) * 100);
                        @endphp
                        <span class="discount-badge">SAVE {{ $discount }}%</span>
                    @endif
                    @if($product->discount_price)
                        <span class="old-price">${{ number_format($product->price, 2) }}</span>
                    @endif
                    <span class="current-price">${{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                </div>

                <div class="viewers-info">
                    <span>24 شخصاً يشاهدون هذا الآن</span>
                    <i class="fas fa-eye"></i>
                </div>

                <div>
                    <h3 class="section-title">: المقاس</h3>
                    <div class="sizes-grid">
                        <button class="size-option">XL</button>
                        <button class="size-option">L</button>
                        <button class="size-option active">M</button>
                        <button class="size-option">S</button>
                    </div>
                </div>

                <div>
                    <h3 class="section-title">: اللون</h3>
                    <div class="colors-grid">
                        <div class="color-option active" style="background-color: #FFB6C1;" onclick="selectColor(this)"></div>
                        <div class="color-option" style="background-color: #87CEEB;" onclick="selectColor(this)"></div>
                        <div class="color-option" style="background-color: #000000;" onclick="selectColor(this)"></div>
                    </div>
                </div>

                <div class="quantity-cart-section">
                    <button id="addToCartBtn" class="add-to-cart-btn" onclick="addToCart()">
                        أضف إلى السلة
                    </button>
                    <div class="quantity-selector">
                        <button class="quantity-btn" onclick="increaseQty()">+</button>
                        <input type="text" id="quantity" value="1" class="quantity-input" readonly>
                        <button class="quantity-btn" onclick="decreaseQty()">-</button>
                    </div>
                </div>

                <button class="share-btn" onclick="shareProduct()">
                    شاركه الآن
                </button>

                <div class="shipping-info">
                    <div class="shipping-item">
                        <div class="shipping-text">
                            <strong>يتم الشحن خلال Jul 30 - Aug 03</strong>
                        </div>
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="shipping-item">
                        <div class="shipping-text">
                            <span>شحن وإرجاع مجاني على جميع الطلبات التي تزيد عن 75 دولاراً</span>
                        </div>
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>

            <!-- Right Side - Product Images -->
            <div class="product-images">
                <div class="main-image-container">
                    <button class="favorite-btn-large" id="favoriteBtn" onclick="toggleFavorite()">
                        <i class="far fa-heart"></i>
                    </button>
                    <img id="mainImage" src="{{ $product->image ?? 'https://via.placeholder.com/500x500' }}" alt="{{ $product->name }}" class="main-image">
                </div>
                
                <div class="thumbnails">
                    <div class="thumbnail active" onclick="changeImage(this, '{{ $product->image ?? 'https://via.placeholder.com/500x500' }}')">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/150x150' }}" alt="صورة 1">
                    </div>
                    <div class="thumbnail" onclick="changeImage(this, '{{ $product->image ?? 'https://via.placeholder.com/500x500' }}')">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/150x150' }}" alt="صورة 2">
                    </div>
                    <div class="thumbnail" onclick="changeImage(this, '{{ $product->image ?? 'https://via.placeholder.com/500x500' }}')">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/150x150' }}" alt="صورة 3">
                    </div>
                    <div class="thumbnail" onclick="changeImage(this, '{{ $product->image ?? 'https://via.placeholder.com/500x500' }}')">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/150x150' }}" alt="صورة 4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const productId = {{ $product->id }};
        const productData = {
            id: {{ $product->id }},
            name: "{{ $product->name }}",
            price: {{ $product->discount_price ?? $product->price }},
            image: "{{ $product->image ?? 'https://via.placeholder.com/250' }}"
        };
        
        // Check if product is in favorites on page load
        window.addEventListener('DOMContentLoaded', function() {
            checkFavoriteStatus();
            updateFavoritesCount();
        });
        
        function checkFavoriteStatus() {
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const isFavorite = favorites.some(p => p.id === productId);
            const favoriteBtn = document.getElementById('favoriteBtn');
            
            if (isFavorite) {
                favoriteBtn.classList.add('active');
                favoriteBtn.querySelector('i').classList.remove('far');
                favoriteBtn.querySelector('i').classList.add('fas');
            }
        }
        
        function toggleFavorite() {
            let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const favoriteBtn = document.getElementById('favoriteBtn');
            const icon = favoriteBtn.querySelector('i');
            const isFavorite = favorites.some(p => p.id === productId);
            
            if (isFavorite) {
                // Remove from favorites
                favorites = favorites.filter(p => p.id !== productId);
                favoriteBtn.classList.remove('active');
                icon.classList.remove('fas');
                icon.classList.add('far');
            } else {
                // Add to favorites
                favorites.push(productData);
                favoriteBtn.classList.add('active');
                icon.classList.remove('far');
                icon.classList.add('fas');
            }
            
            localStorage.setItem('favorites', JSON.stringify(favorites));
            updateFavoritesCount();
        }
        
        function updateFavoritesCount() {
            const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
            const countElement = document.getElementById('favoritesCount');
            if (countElement) {
                countElement.textContent = favorites.length > 99 ? '+99' : favorites.length;
            }
        }
        
        function changeImage(thumbnail, imageUrl) {
            document.getElementById('mainImage').src = imageUrl;
            document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
            thumbnail.classList.add('active');
        }

        function selectColor(colorElement) {
            document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));
            colorElement.classList.add('active');
        }

        document.querySelectorAll('.size-option').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.size-option').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        function increaseQty() {
            const qtyInput = document.getElementById('quantity');
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }

        function decreaseQty() {
            const qtyInput = document.getElementById('quantity');
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }
        
        async function addToCart() {
            const btn = document.getElementById('addToCartBtn');
            const originalText = btn.innerHTML;
            const quantity = parseInt(document.getElementById('quantity').value);
            
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
                        quantity: quantity
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة بنجاح';
                    btn.style.background = 'linear-gradient(135deg, #27ae60 0%, #2ecc71 100%)';
                    
                    // Update cart count using global function
                    if (window.updateCartCount) {
                        window.updateCartCount(data.cart_count || data.count || 0);
                    }
                    
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
                btn.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }
        }
        
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: productData.name,
                    text: `تحقق من هذا المنتج الرائع: ${productData.name}`,
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = event.target.closest('.share-btn');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> تم نسخ الرابط';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                    }, 2000);
                });
            }
        }
    </script>
</body>
</html>
