<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>أسعار الفواكه والخضروات - توليب مارت</title>
     <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family:  'El Messiri', sans-serif; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; }

        /* Beautiful Hero Section */
        .prices-hero {
            background: linear-gradient(135deg, #F05928 0%, #0D464C 60%,  #7dd3c0 100%);
            position: relative;
            padding: 3rem 2rem 10rem;
            overflow: hidden;
        }
        .prices-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='120' height='120' viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M9 0h2v20H9V0zm25.134.84l1.732 1-10 17.32-1.732-1 10-17.32zm16.002 4.16l1 1.732L34.816 17.32l-1-1.732 16.32-10.32zm16.15 10.29l.684 1.876-18.5 6.75-.684-1.876 18.5-6.75zM60 30v2H40v-2h20zM49.134 40.84l1.732-1 10 17.32-1.732 1-10-17.32zm16.002-4.16l1-1.732L82.456 45.32l-1 1.732-16.32-10.32zm16.15-10.29l.684-1.876 18.5 6.75-.684 1.876-18.5-6.75zM90 20h2v20h-2V20zm25.134.84l1.732 1-10 17.32-1.732-1 10-17.32zm16.002 4.16l1 1.732-16.32 10.32-1-1.732 16.32-10.32zm16.15 10.29l.684 1.876-18.5 6.75-.684-1.876 18.5-6.75zM30 60v2H10v-2h20zm19.134 20.84l1.732-1 10 17.32-1.732 1-10-17.32zm16.002-4.16l1-1.732L82.456 85.32l-1 1.732-16.32-10.32zm16.15-10.29l.684-1.876 18.5 6.75-.684 1.876-18.5-6.75zM120 80h2v20h-2V80zm25.134.84l1.732 1-10 17.32-1.732-1 10-17.32zm16.002 4.16l1 1.732-16.32 10.32-1-1.732 16.32-10.32zm16.15 10.29l.684 1.876-18.5 6.75-.684-1.876 18.5-6.75z' fill='%23ffffff' fill-opacity='0.04' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.6;
        }
        .prices-hero::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 120px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 120'%3E%3Cpath fill='%23f8f9fa' d='M0,60 C240,120 480,0 720,60 C960,120 1200,0 1440,60 L1440,120 L0,120 Z'/%3E%3C/svg%3E");
            background-size: cover;
        }
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(15px);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            color: #fff;
            font-size: 1rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .hero-icons {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }
        .hero-icon-item {
            font-size: 5rem;
            animation: float 4s ease-in-out infinite;
            filter: drop-shadow(0 15px 35px rgba(0,0,0,0.3));
        }
        .hero-icon-item:nth-child(2) { animation-delay: 0.7s; }
        .hero-icon-item:nth-child(3) { animation-delay: 1.4s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-8deg) scale(1); }
            50% { transform: translateY(-25px) rotate(8deg) scale(1.1); }
        }
        .hero-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 4rem;
            color: #fff;
            margin-bottom: 1.5rem;
            text-shadow: 0 6px 25px rgba(0,0,0,0.4);
            font-weight: 800;
            line-height: 1.2;
        }
        .hero-title span { 
            color: #ffeb3b; 
            text-shadow: 0 0 20px rgba(255,235,59,0.5);
        }
        .hero-subtitle {
            font-size: 1.4rem;
            color: rgba(255,255,255,0.95);
            max-width: 800px;
            margin: 0 auto 2.5rem;
            line-height: 2.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            padding: 1.2rem 2.5rem;
            border-radius: 50px;
            color: #fff;
            font-size: 1.1rem;
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        /* Main Container */
        .prices-container {
            max-width: 1400px;
            margin: -4rem auto 0;
            padding: 0 2rem 5rem;
            position: relative;
            z-index: 2;
        }

       
       
        @keyframes pulse { 
            0%, 100% { opacity: 1; transform: scale(1); } 
            50% { opacity: 0.6; transform: scale(1.2); } 
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 4rem;
        }
        .stat-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        .stat-icon {
            width: 70px; height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        .stat-number { 
            font-family: 'El Messiri', sans-serif;
            font-size: 2.2rem; 
            font-weight: 800; 
            color: #1a5a5a; 
            margin-bottom: 0.5rem; 
        }
        .stat-label { 
            font-size: 0.9rem; 
            color: #7f8c8d; 
            font-weight: 500;
        }
        
        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #2e7d32;
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .section-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.5rem;
            color: #1a3a3a;
            margin-bottom: 0.8rem;
            font-weight: 700;
        }
        .section-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Category Section */
        .category-section {
            background: #fff;
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            border: 1px solid #f0f0f0;
        }
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f8f9fa;
        }
        .category-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a5a5a;
        }
        .category-title .emoji { font-size: 2.5rem; }
        .category-count {
            background: linear-gradient(135deg, #0D464C, #F05928);
            color: #fff;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .price-card {
            display: flex;
            align-items: stretch;
            gap: 0;
            padding: 0;
            background: #fff;
            border-radius: 20px;
            transition: all 0.4s ease;
            cursor: pointer;
            border: 1px solid #eee;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .price-card:hover {
            border-color: #7dd3c0;
            box-shadow: 0 10px 30px rgba(125,211,192,0.15);
            transform: translateY(-5px);
        }
        .price-card .photo-container {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-left: 1px solid #f0f0f0;
        }
        .price-card .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .price-card .photo-container .emoji {
            font-size: 3rem;
        }
        .price-card .info {
            flex: 1;
            padding: 0.8rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .price-card .name { 
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem; 
            font-weight: 700; 
            color: #1a5a5a; 
            margin-bottom: 0.2rem; 
        }
        .price-card .origin { 
            font-size: 0.8rem; 
            color: #7f8c8d; 
            margin-bottom: 0.5rem; 
            display: flex; 
            align-items: center; 
            gap: 0.3rem; 
        }
        .price-card .prices { 
            display: flex; 
            align-items: baseline; 
            gap: 0.5rem; 
            margin-bottom: 0;
        }
        .price-card .current { 
            font-family:'El Messiri',sans-serif;
            font-size: 1.15rem; 
            font-weight: 800; 
            color: #1a5a5a; 
        }
        .price-card .old { 
            font-size: 0.85rem; 
            color: #bdc3c7; 
            text-decoration: line-through; 
        }
        .price-card .unit { 
            font-size: 0.75rem; 
            color: #7f8c8d; 
            margin-inline-start: 0.2rem;
        }
        .add-btn {
            width: 45px;
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: #fff;
            border: none;
            border-radius: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            flex-shrink: 0;
            font-size: 1rem;
        }
        .add-btn:hover { 
            transform: scale(1.1); 
            box-shadow: 0 8px 25px rgba(243,156,18,0.3);
        }
        
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-title { font-size: 2.8rem; }
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-title { font-size: 2.2rem; }
            .hero-subtitle { font-size: 1.1rem; }
            .prices-container { padding: 0 0 5rem; }
            .category-section { 
                border-radius: 0; 
                margin: 0 0 1.5rem; 
                padding: 1.5rem 1rem;
                width: 100%;
                border-left: none;
                border-right: none;
            }
            .products-grid { 
                grid-template-columns: repeat(2, 1fr) !important; 
                gap: 0.5rem !important;
            }
            .category-header { flex-direction: column; gap: 1rem; text-align: center; }
            
            .price-card {
                flex-direction: column !important;
                text-align: center;
            }
            .price-card .photo-container {
                width: 100% !important;
                height: auto !important;
                aspect-ratio: 1 / 1 !important;
                border-left: none !important;
                border-bottom: 1px solid #f0f0f0;
            }
            .price-card .info {
                padding: 0.6rem !important;
            }
            .price-card .prices {
                justify-content: center;
                flex-wrap: wrap;
            }
            .add-btn {
                width: 100% !important;
                height: 40px !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body>
<?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Hero Section -->
<section class="prices-hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-sync-alt"></i>
            تحديث مستمر كل ساعة
        </div>
       
        <h1 class="hero-title">أسعار <span>الفواكه والخضروات</span></h1>
        <p class="hero-subtitle">تابع أحدث أسعار الفواكه والخضروات الطازجة مع تحديث مستمر لضمان أفضل الصفقات</p>
        <div class="date-badge">
            <i class="fas fa-calendar-alt"></i>
            <span id="currentDate"></span>
        </div>
    </div>
</section>

<!-- <div class="live-badge">
    <div class="live-dot"></div>
    تحديث مباشر
</div> -->

<div class="prices-container">
    <!-- Categories -->
    <div id="categoriesContainer"></div>
</div>

<!-- Footer -->
<?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
const API_BASE = window.location.origin + '/api';
let productsData = { categories: { fruits: [], vegetables: [] }, date: null };

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing...');
    loadDate();
    fetchDailyPrices();
});

function loadDate() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dateEl = document.getElementById('currentDate');
    if (dateEl) {
        dateEl.textContent = new Date().toLocaleDateString('ar-SA', options);
    }
}

function resolvePublicImage(path) {
    if (!path) return null;
    const p = String(path);
    if (p.startsWith('http://') || p.startsWith('https://')) return p;
    if (p.startsWith('/')) return p;
    return `/storage/${p}`;
}

async function fetchDailyPrices() {
    console.log('Starting fetch...');
    try {
        const url = `${API_BASE}/mart/daily-prices`;
        console.log('Fetching from:', url);
        
        const r = await fetch(url);
        console.log('Response status:', r.status);
        console.log('Response ok:', r.ok);
        
        if (!r.ok) {
            throw new Error(`HTTP error! status: ${r.status}`);
        }
        
        const d = await r.json();
        console.log('Response data:', d);
        console.log('Categories:', d.categories);
        console.log('Vegetables count:', d.categories?.vegetables?.length || 0);
        console.log('Fruits count:', d.categories?.fruits?.length || 0);
        
        productsData = d || productsData;
        renderCategories();
    } catch (e) {
        console.error('Error fetching daily prices:', e);
        console.error('Error stack:', e.stack);
        // Still try to render with empty data
        renderCategories();
    }
}

function renderCategories() {
    console.log('=== renderCategories called ===');
    console.log('productsData:', productsData);
    
    const container = document.getElementById('categoriesContainer');
    if (!container) {
        console.error('Container not found!');
        return;
    }
    
    const categoryInfo = {
        fruits: { title: 'الفواكه', emojiFallback: '🍎' },
        vegetables: { title: 'الخضروات', emojiFallback: '🥬' }
    };
    
    const categories = productsData.categories || {};
    console.log('Categories object:', categories);
    
    const entries = Object.entries(categories);
    console.log('Category entries:', entries);
    console.log('Number of categories:', entries.length);
    
    if (entries.length === 0) {
        console.warn('No categories to render!');
        container.innerHTML = '<div style="text-align:center;padding:3rem;color:#999;">لا توجد منتجات متاحة حالياً</div>';
        return;
    }
    
    const html = entries.map(([key, items]) => {
        console.log(`Processing category: ${key}, items:`, items);
        console.log(`Items count: ${items?.length || 0}`);
        
        const info = categoryInfo[key] || { title: key, emojiFallback: '📦' };
        const itemsArray = Array.isArray(items) ? items : [];
        
        return `
            <section class="category-section" id="${key}">
                <div class="category-header">
                    <h2 class="category-title">
                        <span class="emoji">${info.emojiFallback}</span>
                        ${info.title}
                    </h2>
                    <span class="category-count">${itemsArray.length} منتج</span>
                </div>
                <div class="products-grid">
                    ${itemsArray.length > 0 ? itemsArray.map(p => createCard(p)).join('') : '<p style="grid-column:1/-1;text-align:center;color:#999;padding:2rem;">لا توجد منتجات في هذا القسم</p>'}
                </div>
            </section>
        `;
    }).join('');
    
    console.log('Generated HTML length:', html.length);
    container.innerHTML = html;
    console.log('Container updated');
}

function createCard(p) {
    console.log('Creating card for product:', p);
    const photoUrl = resolvePublicImage(p.photo || p.image || p.imageUrl) || '/images/tulip_mart.jpg';
    return `
        <div class="price-card">
            <div class="photo-container">
                <img src="${photoUrl}" alt="${p.name}" onerror="this.src='/images/panner_mart.png'">
            </div>
            <div class="info">
                <div class="name">${p.name}</div>
                <div class="origin"><i class="fas fa-map-marker-alt"></i> ${p.origin || 'محلي'}</div>
                <div class="prices">
                    <span class="current">${p.price} ل.س</span>
                    ${p.oldPrice ? `<span class="old">${p.oldPrice} ل.س</span>` : ''}
                    <span class="unit">/ ${p.unit || 'كغ'}</span>
                </div>
            </div>
            <button class="add-btn" onclick="addToCart('${p.id || p.name}', this)">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    `;
}

async function addToCart(id, btn) {
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    const all = [...(productsData.categories?.fruits || []), ...(productsData.categories?.vegetables || [])];
    const p = all.find(x => (x.id || x.name) === id);
    
    try {
        const photoUrl = resolvePublicImage(p?.photo || p?.image || p?.imageUrl) || '/images/panner_mart.png';
        const r = await fetch(`${API_BASE}/cart/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ 
                product_id: id, 
                product_type: 'mart',
                name: p?.name, 
                price: p?.price, 
                quantity: 1,
                image: photoUrl,
                unit: p?.unit || 'كغ',
                emoji: p?.emoji || ''
            })
        });
        const d = await r.json();
        
        btn.style.background = 'linear-gradient(135deg, #00b894, #00a085)';
        btn.innerHTML = '<i class="fas fa-check"></i>';
        
        if (window.updateCartCount && d.count !== undefined) window.updateCartCount(d.count);
        if (window.animateCartIcon) window.animateCartIcon();
        if (window.showToast) window.showToast('تمت إضافة ' + (p?.name || 'المنتج') + ' إلى السلة');
        
        setTimeout(() => {
            btn.style.background = 'linear-gradient(135deg, #e17055, #d63031)';
            btn.innerHTML = orig;
            btn.disabled = false;
        }, 2000);
    } catch (e) {
        console.error('Error adding to cart:', e);
        btn.innerHTML = orig;
        btn.disabled = false;
    }
}
</script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/mart/daily-prices.blade.php ENDPATH**/ ?>