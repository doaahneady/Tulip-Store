@extends('layouts.app')

@section('content')
@include('components.weight-modal')

<script>
// Add to existing JavaScript in the page
document.addEventListener('DOMContentLoaded', () => {
    // Override createProductCard function to support weight-based products
    const originalCreateProductCard = window.createProductCard;
    
    window.createProductCard = function(p) {
        const attrs = Array.isArray(p.attributes) ? p.attributes : [];
        const unit = (attrs.find(a => a.name === 'unit')?.value || attrs.find(a => a.name === 'unit')?.value_text) || 'حبة';
        const origin = (attrs.find(a => a.name === 'origin')?.value || attrs.find(a => a.name === 'origin')?.value_text) || 'محلي';
        const price = parseFloat(p.discount_price || p.price || 0);
        const oldPrice = p.discount_price ? parseFloat(p.price || 0) : null;
        const imageUrl = p.primary_image_url || p.image || '/images/tulip_store.jpg';
        const isFav = window.favoriteIds ? window.favoriteIds.has(String(p.id)) : false;
        
        // Check if product is weight-based (kilogram or gram)
        const isWeightBased = unit.toLowerCase() === 'kilogram' || unit.toLowerCase() === 'gram' || 
                              unit.toLowerCase() === 'كيلو' || unit.toLowerCase() === 'كيلوغرام' || 
                              unit.toLowerCase() === 'غرام';
        
        const buttonClass = isWeightBased ? 'add-btn-circle weight-based' : 'add-btn-circle';
        const buttonIcon = isWeightBased ? 'fa-balance-scale' : 'fa-plus';
        const buttonAction = isWeightBased ? `openWeightModal('${p.id}')` : `initCartCounter('${p.id}', event)`;
        
        return `
            <div class="product-card" data-id="${p.id}">
                ${p.discount_price ? '<div class="product-badges"><span class="badge badge-sale">عرض</span></div>' : ''}
                
                <div class="product-image" style="background: #f3f4f6;">
                    <button class="product-favorite" onclick="toggleFavorite(${p.id}, event)">
                        <i class="${isFav ? 'fas' : 'far'} fa-heart"></i>
                    </button>
                    <img src="${imageUrl}" alt="${p.name}" onerror="this.src='/images/tulip_store.jpg'">
                    
                    <!-- Floating Cart Control -->
                    <div class="cart-control-wrapper" id="cart-wrapper-${p.id}" onclick="event.stopPropagation()">
                        <button class="${buttonClass}" onclick="${buttonAction}" id="add-btn-${p.id}">
                            <i class="fas ${buttonIcon}"></i>
                        </button>
                        ${!isWeightBased ? `
                        <div class="counter-control" id="counter-${p.id}">
                            <button class="counter-btn" onclick="updateQuantity('${p.id}', -1, event)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="counter-value" id="count-${p.id}">1</span>
                            <button class="counter-btn" onclick="updateQuantity('${p.id}', 1, event)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="product-body">
                    <div class="product-category">${window.categoryName || ''}</div>
                    <h3 class="product-name">${p.name}</h3>
                    <div class="product-origin">
                        <i class="fas fa-map-marker-alt"></i>
                        ${origin}
                    </div>
                    <div class="product-footer">
                        <div class="price-info">
                            <span class="price-current">${window.formatMoney ? window.formatMoney(price) : (price.toFixed(2) + ' د.ع')}</span>
                            ${oldPrice ? `<span class="price-old">${window.formatMoney ? window.formatMoney(oldPrice) : (oldPrice.toFixed(2) + ' د.ع')}</span>` : ''}
                            <span class="price-unit">لكل ${unit}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    };
});
</script>
@endsection
