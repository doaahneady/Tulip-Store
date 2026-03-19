<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $package['name'] }} - Tulip Store</title>
<!-- fav icon -->
<link rel="icon" type="image/png" href="/images/fav_icon.png">

<link rel="stylesheet" href="/css/store.css?v={{ time() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin:0;font-family:'El Messiri',sans-serif;background:#f5f5f5;">

@if(View::exists('components.navbar'))
    @include('components.navbar')
@endif

<section style="background:linear-gradient(135deg,#2a7080 0%,#1a5060 100%);padding:2.5rem 1.5rem;text-align:center;margin-top:80px;">
<h1 style="font-size:2.2rem;font-weight:800;color:#fff;margin:0;">{{ $package['name'] }}</h1>
<p style="color:#e8f4f8;margin:0.5rem 0 0 0;">تصفح منتجات هذه الباقة</p>
<a href="/" style="display:inline-block;margin-top:1rem;color:#fff;text-decoration:none;background:rgba(255,255,255,0.2);padding:0.5rem 1.5rem;border-radius:25px;font-size:0.9rem;">
<i class="fas fa-arrow-right"></i> العودة للرئيسية
</a>
</section>

<div style="max-width:1400px;margin:0 auto;padding:2rem;">
<div id="productsGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1.5rem;">
<div style="grid-column:1/-1;text-align:center;padding:3rem;color:#999;">
<i class="fas fa-spinner fa-spin" style="font-size:2rem;"></i>
<p>جاري تحميل المنتجات...</p>
</div>
</div>
</div>

<script>
const packageProductIds = @json($package['product_ids'] ?? []);

async function loadPackageProducts() {
    try {
        const response = await fetch('/api/products');
        const data = await response.json();
        const allProducts = data.data || [];
        
        // Filter only package products
        const packageProducts = allProducts.filter(p => packageProductIds.includes(p.id));
        
        const grid = document.getElementById('productsGrid');
        
        if (packageProducts.length === 0) {
            grid.innerHTML = `
                <div style="grid-column:1/-1;text-align:center;padding:3rem;">
                    <i class="fas fa-box-open" style="font-size:4rem;color:#ddd;margin-bottom:1rem;display:block;"></i>
                    <p style="color:#999;font-size:1.2rem;">لا توجد منتجات في هذه الباقة حالياً</p>
                    <a href="/store" style="display:inline-block;margin-top:1rem;background:#2a7080;color:#fff;padding:0.8rem 2rem;border-radius:10px;text-decoration:none;font-weight:600;">
                        تصفح جميع المنتجات
                    </a>
                </div>
            `;
            return;
        }

        grid.innerHTML = packageProducts.map(p => `
            <div onclick="window.location.href='/products/${p.id}'" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.08);cursor:pointer;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'">
                <div style="aspect-ratio:1;overflow:hidden;background:#f8f8f8;">
                    <img src="${p.image || 'https://via.placeholder.com/300'}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="padding:1.2rem;">
                    <h3 style="font-size:1rem;font-weight:700;color:#1a1a1a;margin:0 0 0.5rem 0;line-height:1.4;height:2.8em;overflow:hidden;">${p.name}</h3>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="font-size:1.3rem;font-weight:800;color:#2a7080;">${p.price} USD</div>
                        <button onclick="event.stopPropagation();addToCart(${p.id},this)" style="background:#ff6b35;color:#fff;border:none;width:45px;height:45px;border-radius:10px;cursor:pointer;font-size:1.2rem;transition:all 0.3s;" onmouseover="this.style.background='#e55a2b';this.style.transform='scale(1.1)'" onmouseout="this.style.background='#ff6b35';this.style.transform='scale(1)'">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error loading products:', error);
        document.getElementById('productsGrid').innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:3rem;color:#e94560;">حدث خطأ في تحميل المنتجات</div>';
    }
}

async function addToCart(productId, button) {
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    try {
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        
        const data = await response.json();
        
        if (data.success) {
            button.style.background = '#10b981';
            button.innerHTML = '<i class="fas fa-check"></i>';
            
            if (window.updateCartCount) {
                window.updateCartCount(data.cart_count || data.count || 0);
            }
        } else {
            throw new Error('Failed');
        }
    } catch (error) {
        button.style.background = '#e94560';
        button.innerHTML = '<i class="fas fa-times"></i>';
        
        setTimeout(() => {
            button.style.background = '#ff6b35';
            button.innerHTML = '<i class="fas fa-shopping-cart"></i>';
        }, 2000);
    }
}

document.addEventListener('DOMContentLoaded', loadPackageProducts);
</script>

</body>
</html>
