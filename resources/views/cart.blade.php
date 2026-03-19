<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سلة التسوق - Tulip Store</title>
    <!-- fav icon -->
    <link rel="icon" type="image/png" href="/images/fav_icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/store.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f4f5 100%);
        }
        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .cart-header {
            background-image: url('/images/footer.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 2.5rem 2rem;
            border-radius: 16px 16px 0 0;
            margin-bottom: 0;
            box-shadow: 0 4px 12px rgba(15, 79, 85, 0.15);
            position: relative;
        }
        .cart-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(15, 79, 85, 0.7);
            border-radius: 16px 16px 0 0;
            z-index: 0;
        }
        .cart-header h1,
        .cart-header p {
            position: relative;
            z-index: 1;
        }
        .cart-header h1 {
            font-family: 'El Messiri', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .cart-header p {
            font-family: "El Messiri", sans-serif;
            font-size: 1rem;
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
        }
        .cart-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            align-items: start;
        }
        .cart-items {
            background: white;
            border-radius: 0 0 16px 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(15, 79, 85, 0.08);
        }
        .cart-items-header {
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e8f4f5;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .items-count {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: #0f4f55;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .continue-shopping-link {
            color: #0f4f55;
            text-decoration: none;
            font-family: "El Messiri", sans-serif;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border: 2px solid #e8f4f5;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .continue-shopping-link:hover {
            background: #f0f9fa;
            border-color: #0f4f55;
        }
        .cart-items-list {
            padding: 0;
        }
        .cart-item {
            display: grid;
            grid-template-columns: 150px 1fr auto;
            gap: 1.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 12px;
            background: #fafbfc;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .cart-item:hover {
            background: white;
            border-color: #e8f4f5;
            box-shadow: 0 4px 12px rgba(15, 79, 85, 0.08);
        }
        .cart-item-image {
            width: 150px;
            height: 150px;
            overflow: hidden;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }
        .cart-item-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .cart-item-details {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        .cart-item-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f4f55;
            margin: 0;
            line-height: 1.4;
            cursor: pointer;
        }
        .cart-item-name:hover {
            color: #1a6b73;
        }
        .cart-item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-family: "El Messiri", sans-serif;
            font-size: 0.875rem;
            color: #7f8c8d;
        }
        .cart-item-meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .cart-item-meta-item i {
            font-size: 0.75rem;
            color: #0f4f55;
        }
        .cart-item-stock {
            font-family:"El Messiri", sans-serif;
            font-size: 0.875rem;
            color: #27ae60;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .cart-item-stock i {
            font-size: 0.75rem;
        }
        .cart-item-stock.out {
            color: #e74c3c;
        }
        .cart-item-price-section {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-top: auto;
        }
        .cart-item-price {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f4f55;
        }
        .cart-item-price-old {
            font-size: 1rem;
            color: #95a5a6;
            text-decoration: line-through;
            margin-left: 0.5rem;
            font-weight: 400;
        }
        .cart-item-savings {
            font-family: "El Messiri", sans-serif;
            font-size: 0.875rem;
            color: #27ae60;
            font-weight: 600;
        }
        .cart-item-subtotal {
            font-family: "El Messiri", sans-serif;
            font-size: 0.875rem;
            color: #7f8c8d;
        }
        .cart-item-actions-column {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1rem;
            justify-content: space-between;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: white;
            border-radius: 10px;
            padding: 0.4rem;
            border: 2px solid #e8f4f5;
            box-shadow: 0 2px 6px rgba(15, 79, 85, 0.08);
        }
        .quantity-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #f0f9fa;
            color: #0f4f55;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-btn:hover {
            background: #0f4f55;
            color: white;
            transform: scale(1.05);
        }
        .quantity-btn:active {
            transform: scale(0.95);
        }
        .quantity-value {
            min-width: 40px;
            text-align: center;
            font-family: 'El Messiri', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: #0f4f55;
        }
        .remove-btn {
            background: #fee;
            border: 2px solid #ffdddd;
            color: #e74c3c;
            cursor: pointer;
            font-size: 0.875rem;
            font-family: "El Messiri", sans-serif;
            font-weight: 600;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .remove-btn:hover {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }
        .cart-summary {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(15, 79, 85, 0.08);
            height: fit-content;
            position: sticky;
            top: 2rem;
            border: 2px solid #e8f4f5;
        }
        .summary-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f4f55;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e8f4f5;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            font-family: "El Messiri", sans-serif;
            font-size: 0.95rem;
            color: #2c3e50;
        }
        .summary-row.total {
            border-top: 2px solid #e8f4f5;
            margin-top: 1rem;
            padding-top: 1.5rem;
            font-size: 1.3rem;
            font-weight: 700;
            color: #0f4f55;
            font-family: 'El Messiri', sans-serif;
        }
        .summary-value {
            font-weight: 700;
            color: #0f4f55;
        }
        .summary-value.free {
            color: #27ae60;
        }
        .summary-value.discount {
            color: #e74c3c;
        }
        .summary-info {
            font-size: 0.8rem;
            color: #7f8c8d;
            font-style: italic;
        }
        .checkout-btn {
            width: 100%;
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%);
            color: white;
            border: none;
            padding: 1.2rem;
            border-radius: 12px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 6px 16px rgba(15, 79, 85, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 79, 85, 0.4);
        }
        .checkout-btn:active {
            transform: translateY(0);
        }
        .security-badges {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e8f4f5;
        }
        .security-badge {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: "El Messiri", sans-serif;
            font-size: 0.875rem;
            color: #7f8c8d;
        }
        .security-badge i {
            color: #27ae60;
            font-size: 1rem;
        }
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .empty-cart-icon {
            font-size: 5rem;
            color: #c7c7c7;
            margin-bottom: 1.5rem;
        }
        .empty-cart h2 {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.5rem;
            color: #0f0f0f;
            margin-bottom: 0.5rem;
            font-weight: 400;
        }
        .empty-cart p {
            font-family:"El Messiri", sans-serif;
            color: #565959;
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        .shop-now-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 1rem 2.5rem;
            text-decoration: none;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 6px 16px rgba(15, 79, 85, 0.3);
        }
        .shop-now-btn:hover {
            background: linear-gradient(135deg, #1a6b73 0%, #0f4f55 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 79, 85, 0.4);
        }
        .shop-now-btn:active {
            transform: translateY(0);
        }
        
        /* Delete Confirmation Modal */
        .delete-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }
        .delete-modal.show {
            display: flex;
        }
        .delete-modal-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            max-width: 450px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
            position: relative;
        }
        .delete-modal-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #fee 0%, #ffdddd 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: #e74c3c;
            animation: bounce 0.6s ease;
        }
        .delete-modal-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.6rem;
            color: #0f4f55;
            margin-bottom: 0.8rem;
            font-weight: 700;
        }
        .delete-modal-text {
            color: #7f8c8d;
            font-family:"El Messiri", sans-serif;
            font-size: 1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .delete-modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .delete-modal-btn {
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'El Messiri', sans-serif;
            min-width: 120px;
        }
        .delete-modal-btn.confirm {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        .delete-modal-btn.confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        .delete-modal-btn.cancel {
            background: #f5f8f9;
            color: #2c3e50;
            border: 2px solid #e8f4f5;
        }
        .delete-modal-btn.cancel:hover {
            background: white;
            border-color: #0f4f55;
            color: #0f4f55;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        @media (max-width: 1024px) {
            .cart-content {
                grid-template-columns: 1fr;
            }
            .cart-summary {
                position: static;
            }
        }
        @media (max-width: 768px) {
            .cart-container {
                padding: 1rem;
            }
            .cart-header {
                padding: 1.5rem 1rem;
            }
            .cart-header h1 {
                font-size: 1.4rem;
            }
            .cart-items {
                padding: 1rem;
            }
            .cart-item {
                grid-template-columns: 50px 1fr auto;
                gap: 0.5rem;
                padding: 0.5rem;
                align-items: center;
                height: 50px;
                overflow: hidden;
            }
            .cart-item-image {
                width: 40px;
                height: 40px;
            }
            .cart-item-details {
                gap: 0.1rem;
                overflow: hidden;
            }
            .cart-item-name {
                font-size: 0.8rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .cart-item-meta, .cart-item-stock, .cart-item-savings, .cart-item-subtotal {
                display: none;
            }
            .cart-item-price-section {
                margin: 0;
                flex-direction: row;
                align-items: center;
                gap: 0.3rem;
            }
            .cart-item-price {
                font-size: 0.85rem;
            }
            .cart-item-price-old {
                font-size: 0.7rem;
            }
            .cart-item-actions-column {
                flex-direction: row;
                gap: 0.4rem;
                align-items: center;
            }
            .quantity-control {
                padding: 0.1rem;
                gap: 0.2rem;
            }
            .quantity-btn {
                width: 22px;
                height: 22px;
                font-size: 0.7rem;
            }
            .quantity-value {
                min-width: 20px;
                font-size: 0.8rem;
            }
            .remove-btn {
                padding: 0.3rem;
                font-size: 0.7rem;
                background: none;
                border: none;
            }
            .remove-btn span {
                display: none;
            }
            .cart-summary {
                padding: 1rem;
                margin-top: 1rem;
            }
            .summary-title {
                font-size: 1.1rem;
                margin-bottom: 0.8rem;
                padding-bottom: 0.5rem;
            }
            .summary-row {
                padding: 0.4rem 0;
                font-size: 0.85rem;
            }
            .summary-row.total {
                font-size: 1.1rem;
                padding-top: 0.8rem;
            }
            .checkout-btn {
                padding: 0.8rem;
                font-size: 0.95rem;
                margin-top: 1rem;
            }
            .security-badges {
                gap: 0.4rem;
                margin-top: 0.8rem;
                padding-top: 0.8rem;
            }
            .security-badge {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="cart-container">
        <div class="cart-header">
            <h1><i class="fas fa-shopping-cart"></i> سلة التسوق</h1>
            <p>راجع منتجاتك وأكمل عملية الشراء بأمان</p>
        </div>

        <div id="cartContent">
            <!-- Cart items will be loaded here -->
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="delete-modal" id="deleteModal">
        <div class="delete-modal-content">
            <div class="delete-modal-icon">
                <i class="fas fa-trash-alt"></i>
            </div>
            <h2 class="delete-modal-title">حذف المنتج</h2>
            <p class="delete-modal-text">هل أنت متأكد من حذف هذا المنتج من سلة التسوق؟</p>
            <div class="delete-modal-buttons">
                <button class="delete-modal-btn cancel" onclick="closeDeleteModal()">إلغاء</button>
                <button class="delete-modal-btn confirm" onclick="confirmDelete()">حذف</button>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';

        // Load cart on page load
        window.addEventListener('DOMContentLoaded', () => {
            loadCart();
            loadCartCount();
        });

        // Reload cart when items are added
        window.addEventListener('cart-updated', () => {
            loadCart();
            loadCartCount();
        });

        // Also listen for storage events (for cross-tab updates)
        window.addEventListener('storage', (e) => {
            if (e.key === 'cart-updated') {
                loadCart();
                loadCartCount();
            }
        });

        // Check for cart updates when page becomes visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                loadCart();
                loadCartCount();
            }
        });

        async function loadCart() {
            try {
                const response = await fetch(`${API_BASE}/cart`, { credentials: 'same-origin' });
                const data = await response.json();
                
                displayCart(data);
            } catch (error) {
                console.error('Error loading cart:', error);
                document.getElementById('cartContent').innerHTML = `
                    <div class="empty-cart">
                        <div class="empty-cart-icon"><i class="fas fa-exclamation-circle"></i></div>
                        <h2>حدث خطأ</h2>
                        <p>لم نتمكن من تحميل سلة التسوق</p>
                    </div>
                `;
            }
        }

        function displayCart(data) {
            const cartContent = document.getElementById('cartContent');
            
            if (!data.items || data.items.length === 0) {
                cartContent.innerHTML = `
                    <div class="empty-cart">
                        <div class="empty-cart-icon"><i class="fas fa-shopping-cart"></i></div>
                        <h2>سلة التسوق فارغة</h2>
                        <p>لم تقم بإضافة أي منتجات بعد</p>
                        <a href="/store" class="shop-now-btn">
                            <i class="fas fa-shopping-bag"></i>
                            تسوق الآن
                        </a>
                    </div>
                `;
                return;
            }

            const itemsHTML = data.items.map(item => {
                const price = parseFloat(item.product.discount_price || item.product.price);
                const oldPrice = item.product.discount_price ? parseFloat(item.product.price) : null;
                const savings = oldPrice ? (oldPrice - price) * item.quantity : 0;
                const itemTotal = price * item.quantity;
                const placeholderImage = '/images/tulip_store.jpg';
                
                // Check if this is a custom gift/bouquet (string ID)
                const isCustom = typeof item.id === 'string' && (item.id.startsWith('custom_gift_') || item.id.startsWith('custom_bouquet_'));
                // Check if this is a mart product
                const isMart = item.type === 'mart' || (typeof item.id === 'string' && item.id.startsWith('m'));
                
                const itemIdParam = (isCustom || isMart) ? `'${item.id}'` : item.id;
                
                // For custom items or Mart items with emojis, show emoji/icon preview
                let imageContent = '';
                if (isCustom) {
                    imageContent = `<div style="font-size:4rem;display:flex;align-items:center;justify-content:center;height:100%;">${item.id.startsWith('custom_bouquet_') ? '💐' : '🎁'}</div>`;
                } else if (isMart && item.product.emoji) {
                    imageContent = `<div style="font-size:4rem;display:flex;align-items:center;justify-content:center;height:100%;">${item.product.emoji}</div>`;
                } else {
                    let img = item.product.image || '';
                    if (img && !img.startsWith('http') && !img.startsWith('/')) {
                        img = '/storage/' + img.replace(/^storage\//, '');
                    }
                    imageContent = `<img src="${img || placeholderImage}" alt="${item.product.name}" onerror="this.src='${placeholderImage}'">`;
                }
                
                // For custom items, hide quantity controls (quantity is always 1)
                const quantityControls = isCustom 
                    ? `<div class="quantity-control" style="opacity:0.5;pointer-events:none;"><span class="quantity-value">1</span></div>`
                    : `<div class="quantity-control">
                            <button class="quantity-btn" onclick="updateQuantity(${itemIdParam}, ${item.quantity - 1})"><i class="fas fa-minus"></i></button>
                            <span class="quantity-value">${item.quantity}</span>
                            <button class="quantity-btn" onclick="updateQuantity(${itemIdParam}, ${item.quantity + 1})"><i class="fas fa-plus"></i></button>
                       </div>`;
                
                // Product ID display
                let productIdDisplay = '';
                if (isCustom) {
                    productIdDisplay = `<div class="cart-item-meta-item"><i class="fas fa-gift"></i> هدية مخصصة</div>`;
                } else if (isMart) {
                    productIdDisplay = `
                        <div class="cart-item-meta-item"><i class="fas fa-store"></i> توليب مارت</div>
                        <div class="cart-item-meta-item"><i class="fas fa-box"></i> رقم المنتج: #${item.id}</div>
                    `;
                } else {
                    productIdDisplay = `<div class="cart-item-meta-item"><i class="fas fa-box"></i> رقم المنتج: #${item.product.id}</div>`;
                }
                
                return `
                <div class="cart-item" data-item-id="${item.id}">
                    <div class="cart-item-image">
                        ${imageContent}
                    </div>
                    <div class="cart-item-details">
                        <h3 class="cart-item-name">${item.product.name}</h3>
                        <div class="cart-item-meta">
                            ${item.product.brand ? `<div class="cart-item-meta-item"><i class="fas fa-tag"></i> ${item.product.brand}</div>` : ''}
                            ${productIdDisplay}
                        </div>
                        <div class="cart-item-stock"><i class="fas fa-check-circle"></i> متوفر في المخزون</div>
                        <div class="cart-item-price-section">
                            <div class="cart-item-price">
                                $${price.toFixed(2)}
                                ${oldPrice ? `<span class="cart-item-price-old">$${oldPrice.toFixed(2)}</span>` : ''}
                            </div>
                            ${savings > 0 ? `<div class="cart-item-savings">وفّر $${savings.toFixed(2)}</div>` : ''}
                            <div class="cart-item-subtotal">المجموع الفرعي: $${itemTotal.toFixed(2)}</div>
                        </div>
                    </div>
                    <div class="cart-item-actions-column">
                        ${quantityControls}
                        <button class="remove-btn" onclick="showDeleteModal(${itemIdParam})">
                            <i class="fas fa-trash-alt"></i> حذف
                        </button>
                    </div>
                </div>
            `}).join('');

            cartContent.innerHTML = `
                <div class="cart-content">
                    <div class="cart-items">
                        <div class="cart-items-header">
                            <div class="items-count">
                                <i class="fas fa-shopping-bag"></i>
                                ${data.count} ${data.count === 1 ? 'منتج' : 'منتجات'} في السلة
                            </div>
                            <a href="/store" class="continue-shopping-link">
                                <i class="fas fa-arrow-right"></i>
                                متابعة التسوق
                            </a>
                        </div>
                        <div class="cart-items-list">
                            ${itemsHTML}
                        </div>
                    </div>
                    <div class="cart-summary">
                        <div class="summary-title">
                            <i class="fas fa-receipt"></i>
                            ملخص الطلب
                        </div>
                        <div class="summary-row">
                            <span>المجموع الفرعي (${data.count} ${data.count === 1 ? 'منتج' : 'منتجات'})</span>
                            <span class="summary-value">$${data.subtotal.toFixed(2)}</span>
                        </div>
                        <div class="summary-row total">
                            <span>الإجمالي</span>
                            <span class="summary-value">$${data.total.toFixed(2)}</span>
                        </div>
                        <button class="checkout-btn" onclick="checkout()">
                            <i class="fas fa-lock"></i>
                            إتمام الطلب بأمان
                        </button>
                        <div class="security-badges">
                            <div class="security-badge">
                                <i class="fas fa-shield-alt"></i>
                                معاملات آمنة ومشفرة 100%
                            </div>
                            <div class="security-badge">
                                <i class="fas fa-truck"></i>
                                شحن سريع وموثوق
                            </div>
                            <div class="security-badge">
                                <i class="fas fa-undo"></i>
                                إرجاع مجاني خلال 30 يوم
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) {
                showDeleteModal(itemId);
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/cart/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: newQuantity
                    })
                });

                const data = await response.json();
                
                if (response.ok && data.success) {
                    loadCart();
                    updateCartCount(data.cart_count);
                    // Trigger update event
                    window.dispatchEvent(new Event('cart-updated'));
                } else {
                    // Show error message (e.g., stock limit)
                    if (window.showToast) {
                        window.showToast(data.message || 'حدث خطأ أثناء تحديث الكمية');
                    } else {
                        alert(data.message || 'حدث خطأ أثناء تحديث الكمية');
                    }
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
            }
        }

        let itemToDelete = null;

        function showDeleteModal(itemId) {
            itemToDelete = itemId;
            document.getElementById('deleteModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            document.body.style.overflow = '';
            itemToDelete = null;
        }

        async function confirmDelete() {
            if (!itemToDelete) return;

            try {
                const response = await fetch(`${API_BASE}/cart/remove`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        item_id: itemToDelete
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    closeDeleteModal();
                    loadCart();
                    updateCartCount(data.cart_count);
                    // Trigger update event
                    window.dispatchEvent(new Event('cart-updated'));
                }
            } catch (error) {
                console.error('Error removing item:', error);
                closeDeleteModal();
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') {
                closeDeleteModal();
            }
        });

        function checkout() {
            // Redirect to checkout page
            window.location.href = '/checkout';
        }

        // Update cart count in navbar
        function updateCartCount(count) {
            const cartBadge = document.getElementById('cartBadge');
            if (cartBadge) {
                if (count > 0) {
                    cartBadge.textContent = count > 99 ? '99+' : count;
                    cartBadge.style.display = 'flex';
                } else {
                    cartBadge.style.display = 'none';
                }
            }
        }

        // Load cart count
        async function loadCartCount() {
            try {
                const response = await fetch(`${API_BASE}/cart`, { credentials: 'same-origin' });
                const data = await response.json();
                updateCartCount(data.count || 0);
            } catch (error) {
                console.error('Error loading cart count:', error);
            }
        }
    </script>
</body>
</html>
