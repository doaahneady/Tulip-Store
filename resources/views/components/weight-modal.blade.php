<div id="weightModal" class="weight-modal" style="display: none;">
    <div class="weight-modal-overlay" onclick="closeWeightModal()"></div>
    <div class="weight-modal-content">
        <button class="weight-modal-close" onclick="closeWeightModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="weight-modal-body">
            <!-- Product Image Section -->
            <div class="weight-product-section">
                <div class="weight-product-image">
                    <img id="weightModalImage" src="" alt="">
                    <div class="weight-badge">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                </div>
                <h3 id="weightModalTitle" class="weight-modal-title"></h3>
                <div class="price-per-unit">
                    <span class="price-label">سعر الكيلو:</span>
                    <span class="price-value" id="pricePerKg">0 ل.س</span>
                </div>
            </div>
            
            <!-- Amount Input Section -->
            <div class="weight-input-section">
                <label for="amountInput">كم تريد أن تشتري؟</label>
                <div class="amount-input-wrapper">
                    <input 
                        type="number" 
                        id="amountInput" 
                        class="amount-input" 
                        placeholder="0" 
                        min="0" 
                        step="500"
                        oninput="calculateWeight()"
                    >
                    <span class="currency-label">ليرة سورية</span>
                </div>
            </div>
            
            <!-- Weight Display -->
            <div class="weight-result">
                <div class="result-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="result-content">
                    <span class="result-label">ستحصل على</span>
                    <span class="result-value" id="calculatedWeight">0 غرام</span>
                </div>
            </div>
            
            <!-- Add to Cart Button -->
            <button class="weight-add-to-cart-btn" onclick="addWeightBasedToCart()">
                <i class="fas fa-shopping-cart"></i>
                <span>إضافة إلى السلة</span>
            </button>
        </div>
    </div>
</div>

<style>
    .weight-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.25s ease;
        padding: 1rem;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .weight-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }
    
    .weight-modal-content {
        position: relative;
        background: #ffffff;
        border-radius: 24px;
        max-width: 480px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(40px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .weight-modal-close {
        position: absolute;
        top: 16px;
        left: 16px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: rgba(0, 0, 0, 0.05);
        color: #6b7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        z-index: 10;
        font-size: 1rem;
    }
    
    .weight-modal-close:hover {
        background: #ef4444;
        color: white;
        transform: scale(1.1);
    }
    
    .weight-modal-body {
        padding: 2rem 2rem 2.5rem;
    }
    
    /* Product Section */
    .weight-product-section {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .weight-product-image {
        position: relative;
        width: 180px;
        height: 180px;
        margin: 0 auto 1.5rem;
        border-radius: 20px;
        overflow: hidden;
        background: #f9fafb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .weight-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .weight-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    
    .weight-modal-title {
        font-family: 'El Messiri', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1rem;
        line-height: 1.4;
    }
    
    .price-per-unit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
    }
    
    .price-label {
        font-family: 'El Messiri', sans-serif;
        font-size: 0.95rem;
        color: #92400e;
        font-weight: 600;
    }
    
    .price-value {
        font-family: 'El Messiri', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: #b45309;
    }
    
    /* Input Section */
    .weight-input-section {
        margin-bottom: 1.5rem;
    }
    
    .weight-input-section label {
        display: block;
        font-family: 'El Messiri', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        text-align: center;
    }
    
    .amount-input-wrapper {
        position: relative;
    }
    
    .amount-input {
        width: 100%;
        padding: 1.25rem 1.5rem;
        font-family: 'El Messiri', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        outline: none;
        transition: all 0.2s;
        text-align: center;
        background: #f9fafb;
        color: #111827;
        direction: ltr;
    }
    
    .amount-input:focus {
        border-color: #f59e0b;
        background: white;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    }
    
    .currency-label {
        display: block;
        text-align: center;
        margin-top: 0.5rem;
        font-family: 'El Messiri', sans-serif;
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
    }
    
    /* Weight Result */
    .weight-result {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 2px solid #86efac;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .result-icon {
        margin-bottom: 0.75rem;
    }
    
    .result-icon i {
        font-size: 1.5rem;
        color: #16a34a;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    
    .result-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .result-label {
        font-family: 'El Messiri', sans-serif;
        font-size: 0.95rem;
        color: #166534;
        font-weight: 600;
    }
    
    .result-value {
        font-family: 'El Messiri', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: #15803d;
    }
    
    /* Add to Cart Button */
    .weight-add-to-cart-btn {
        width: 100%;
        padding: 1.25rem;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
        border: none;
        border-radius: 14px;
        font-family: 'El Messiri', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .weight-add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    }
    
    .weight-add-to-cart-btn:active {
        transform: translateY(0);
    }
    
    .weight-add-to-cart-btn i {
        font-size: 1.2rem;
    }
    
    @media (max-width: 768px) {
        .weight-modal-content {
            max-width: 95%;
            border-radius: 20px;
        }
        
        .weight-modal-body {
            padding: 1.5rem 1.25rem 2rem;
        }
        
        .weight-product-image {
            width: 150px;
            height: 150px;
        }
        
        .weight-modal-title {
            font-size: 1.25rem;
        }
        
        .amount-input {
            font-size: 1.75rem;
            padding: 1rem 1.25rem;
        }
        
        .result-value {
            font-size: 1.75rem;
        }
    }
</style>

<script>
    let currentWeightProduct = null;
    
    // Make functions globally accessible
    window.openWeightModal = function(productId) {
        console.log('=== WEIGHT MODAL DEBUG ===');
        console.log('Product ID:', productId);
        console.log('martProductsList length:', window.martProductsList?.length);
        
        const product = (window.martProductsList || []).find(p => String(p.id) === String(productId));
        if (!product) {
            console.error('Product not found:', productId);
            alert('Product not found! ID: ' + productId);
            return;
        }
        
        console.log('Found product object:', product);
        console.log('Product.price:', product.price);
        console.log('Product.oldPrice:', product.oldPrice);
        console.log('Product.unit:', product.unit);
        
        currentWeightProduct = product;
        
        const modal = document.getElementById('weightModal');
        const image = document.getElementById('weightModalImage');
        const title = document.getElementById('weightModalTitle');
        const pricePerKg = document.getElementById('pricePerKg');
        const amountInput = document.getElementById('amountInput');
        
        // Get product image with proper fallback
        let productImage = product.image || product.primary_image_url || product.imageUrl || '/images/tulip_mart.jpg';
        
        // If image doesn't start with http or /, prepend /storage/
        if (productImage && !productImage.startsWith('http') && !productImage.startsWith('/')) {
            productImage = '/storage/' + productImage.replace(/^storage\//, '');
        }
        
        image.src = productImage;
        image.onerror = function() {
            this.src = '/images/tulip_mart.jpg';
        };
        
        title.textContent = product.name;
        
        // Use the EXACT same price as the product card (in USD)
        let priceInUsd = product.price;
        
        // Convert to Syrian Pounds using the same conversion rate
        const USD_TO_SYP = window.TULIP_USD_TO_SYP || 13100;
        let pricePerKilo = Math.round(priceInUsd * USD_TO_SYP);
        
        console.log('Price in USD:', priceInUsd);
        console.log('USD_TO_SYP rate:', USD_TO_SYP);
        console.log('Price in SYP:', pricePerKilo);
        
        // Store the price per kilo for calculations
        currentWeightProduct.pricePerKilo = pricePerKilo;
        
        // Display using formatMoney for consistency
        if (window.formatMoney) {
            pricePerKg.textContent = window.formatMoney(priceInUsd);
        } else {
            pricePerKg.textContent = pricePerKilo.toLocaleString('ar-SY') + ' ل.س';
        }
        
        amountInput.value = '';
        document.getElementById('calculatedWeight').textContent = '0 غرام';
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => amountInput.focus(), 300);
    };
    
    window.closeWeightModal = function() {
        const modal = document.getElementById('weightModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
        currentWeightProduct = null;
    };
    
    window.calculateWeight = function() {
        if (!currentWeightProduct) return;
        
        const amountInput = document.getElementById('amountInput');
        const amount = parseFloat(amountInput.value) || 0;
        
        // Use the stored pricePerKilo that was calculated in openWeightModal
        const pricePerKg = currentWeightProduct.pricePerKilo || 0;
        
        if (pricePerKg <= 0 || amount <= 0) {
            document.getElementById('calculatedWeight').textContent = '0 غرام';
            return;
        }
        
        const weightKg = amount / pricePerKg;
        const weightGrams = weightKg * 1000;
        
        if (weightGrams >= 1000) {
            document.getElementById('calculatedWeight').textContent = weightKg.toFixed(2) + ' كيلو';
        } else {
            document.getElementById('calculatedWeight').textContent = Math.round(weightGrams) + ' غرام';
        }
    };
    
    window.addWeightBasedToCart = async function() {
        if (!currentWeightProduct) return;
        
        const amountInput = document.getElementById('amountInput');
        const amount = parseFloat(amountInput.value) || 0;
        
        if (amount <= 0) {
            if (window.showToast) {
                window.showToast('الرجاء إدخال مبلغ صحيح');
            } else {
                alert('الرجاء إدخال مبلغ صحيح');
            }
            return;
        }
        
        // Calculate weight
        const pricePerKg = currentWeightProduct.pricePerKilo || 0;
        const weightGrams = (amount / pricePerKg) * 1000;
        
        try {
            const response = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: currentWeightProduct.id,
                    product_type: 'mart',
                    name: currentWeightProduct.name,
                    price: currentWeightProduct.price,
                    image: currentWeightProduct.image,
                    unit: 'كيلو غرام',
                    emoji: currentWeightProduct.emoji,
                    quantity: 1,
                    is_weight_based: true,
                    amount_paid: amount,
                    weight_grams: weightGrams,
                    price_per_unit: pricePerKg,
                })
            });
            
            if (!response.ok) {
                const errData = await response.json().catch(() => ({}));
                if (window.showToast) {
                    window.showToast(errData.message || 'غير قادر على إضافة المنتج');
                } else {
                    alert(errData.message || 'غير قادر على إضافة المنتج');
                }
                return;
            }
            
            const data = await response.json();
            
            if (window.updateCartCount) window.updateCartCount(data.count || 0);
            if (window.animateCartIcon) window.animateCartIcon();
            
            if (window.showToast) {
                window.showToast('تم إضافة ' + currentWeightProduct.name + ' إلى السلة');
            }
            
            window.closeWeightModal();
            
        } catch (error) {
            console.error('Error adding to cart:', error);
            if (window.showToast) {
                window.showToast('حدث خطأ أثناء إضافة المنتج');
            }
        }
    };
    
    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.getElementById('weightModal')?.style.display === 'flex') {
            window.closeWeightModal();
        }
    });
    
    console.log('Weight modal functions loaded');
</script>
