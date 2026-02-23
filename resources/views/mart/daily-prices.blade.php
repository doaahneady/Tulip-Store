<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>أسعار الفواكه والخضروات - توليب مارت</title>
    <link rel="stylesheet" href="/css/store.css?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', sans-serif; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; }

        /* Beautiful Hero Section */
        .prices-hero {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c59 30%, #5d8a66 60%, #7dd3c0 100%);
            position: relative;
            padding: 6rem 2rem 10rem;
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

        /* Live Badge */
        .live-badge {
            position: fixed;
            top: 130px;
            left: 30px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: #fff;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 15px 40px rgba(39,174,96,0.4);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.2);
        }
        .live-dot {
            width: 10px; height: 10px;
            background: #ffeb3b;
            border-radius: 50%;
            animation: pulse 1.8s infinite;
            box-shadow: 0 0 10px rgba(255,235,59,0.6);
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
            background: linear-gradient(135deg, #1a5a5a, #2d7a7a);
            color: #fff;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        .price-card {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 20px;
            transition: all 0.4s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .price-card:hover {
            background: #fff;
            border-color: #7dd3c0;
            box-shadow: 0 10px 30px rgba(125,211,192,0.15);
            transform: translateY(-5px);
        }
        .price-card .emoji { 
            font-size: 3.5rem; 
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1));
        }
        .price-card .info { flex: 1; }
        .price-card .name { 
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem; 
            font-weight: 700; 
            color: #1a5a5a; 
            margin-bottom: 0.3rem; 
        }
        .price-card .origin { 
            font-size: 0.85rem; 
            color: #7f8c8d; 
            margin-bottom: 0.8rem; 
            display: flex; 
            align-items: center; 
            gap: 0.4rem; 
        }
        .price-card .prices { 
            display: flex; 
            align-items: center; 
            gap: 0.8rem; 
            flex-wrap: wrap; 
            margin-bottom: 0.8rem;
        }
        .price-card .current { 
            font-family: 'El Messiri', sans-serif;
            font-size: 1.4rem; 
            font-weight: 800; 
            color: #1a5a5a; 
        }
        .price-card .old { 
            font-size: 1rem; 
            color: #bdc3c7; 
            text-decoration: line-through; 
        }
        .price-card .unit { 
            font-size: 0.85rem; 
            color: #7f8c8d; 
        }
        .add-btn {
            width: 50px; height: 50px;
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: #fff;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            flex-shrink: 0;
            font-size: 1.1rem;
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
            .products-grid { grid-template-columns: 1fr; }
            .category-header { flex-direction: column; gap: 1rem; text-align: center; }
        }
    </style>
</head>
<body>
@include('components.navbar')

<!-- Hero Section -->
<section class="prices-hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-sync-alt"></i>
            تحديث مستمر كل ساعة
        </div>
        <div class="hero-icons">
            <span class="hero-icon-item">🍎</span>
            <span class="hero-icon-item">🥬</span>
        </div>
        <h1 class="hero-title">أسعار <span>الفواكه والخضروات</span></h1>
        <p class="hero-subtitle">تابع أحدث أسعار الفواكه والخضروات الطازجة مع تحديث مستمر لضمان أفضل الصفقات</p>
        <div class="date-badge">
            <i class="fas fa-calendar-alt"></i>
            <span id="currentDate"></span>
        </div>
    </div>
</section>

<div class="live-badge">
    <div class="live-dot"></div>
    تحديث مباشر
</div>

<div class="prices-container">
    <!-- Categories -->
    <div id="categoriesContainer"></div>
</div>

<!-- Footer -->
<footer style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); padding: 3rem 2rem 2rem; margin-top: 4rem; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.02\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); opacity: 0.1;"></div>
    
    <div style="max-width: 1400px; margin: 0 auto; position: relative; z-index: 1;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem; margin-bottom: 2.5rem;">
            <!-- Logo & Description -->
            <div style="text-align: center;">
                <img src="/images/white_orange_logo.png" style="height: 120px; margin-bottom: 1.5rem; display: block; margin-left: auto; margin-right: auto;">
                <p style="color: rgba(255,255,255,0.8); line-height: 1.8; font-size: 1rem; margin-bottom: 1.5rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                    متجر فاخر للهدايا والمنتجات المميزة. نساعدك في إرسال ابتسامتك لأحبائك أينما كانوا.
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1rem;">
                    <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s; font-size: 1.2rem;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" style="width: 45px; height: 45px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s; font-size: 1.2rem;">
                        <i class="fab fa-facebook"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div style="text-align: center;">
                <h3 style="color: #27ae60; font-family: 'El Messiri', sans-serif; font-weight: 700; margin-bottom: 1.5rem; font-size: 1.3rem;">روابط سريعة</h3>
                <div style="display: flex; flex-direction: column; gap: 0.8rem; align-items: center;">
                    <a href="/" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">الرئيسية</a>
                    <a href="/store" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">المتجر</a>
                    <a href="/gifts" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">الهدايا</a>
                    <a href="/mart" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">السوبرماركت</a>
                    <a href="/about" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">من نحن</a>
                </div>
            </div>

            <!-- Support -->
            <div style="text-align: center;">
                <h3 style="color: #27ae60; font-family: 'El Messiri', sans-serif; font-weight: 700; margin-bottom: 1.5rem; font-size: 1.3rem;">الدعم</h3>
                <div style="display: flex; flex-direction: column; gap: 0.8rem; align-items: center;">
                    <a href="/contact" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">اتصل بنا</a>
                    <a href="/faq" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">الأسئلة الشائعة</a>
                    <a href="/returns" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">سياسة الإرجاع</a>
                    <a href="/shipping" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">الشحن والتوصيل</a>
                </div>
            </div>

            <!-- Legal -->
            <div style="text-align: center;">
                <h3 style="color: #27ae60; font-family: 'El Messiri', sans-serif; font-weight: 700; margin-bottom: 1.5rem; font-size: 1.3rem;">قانوني</h3>
                <div style="display: flex; flex-direction: column; gap: 0.8rem; align-items: center;">
                    <a href="/privacy" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">سياسة الخصوصية</a>
                    <a href="/terms" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">شروط الخدمة</a>
                    <a href="/cookies" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 1rem;">سياسة ملفات تعريف الارتباط</a>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div style="padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
            <p style="margin: 0; font-size: 0.95rem; color: rgba(255,255,255,0.6);">
                © 2024 متجر توليب. جميع الحقوق محفوظة
            </p>
        </div>
    </div>
</footer>

<script>
const API_BASE = window.location.origin + '/api';
let productsData = { categories: { fruits: [], vegetables: [] }, date: null };

document.addEventListener('DOMContentLoaded', () => {
    loadDate();
    fetchDailyPrices();
});

function loadDate() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').textContent = new Date().toLocaleDateString('ar-SA', options);
}

async function fetchDailyPrices() {
    try {
        const r = await fetch(`${API_BASE}/mart/daily-prices`);
        const d = await r.json();
        productsData = d || productsData;
        renderCategories();
    } catch (e) {
        renderCategories();
    }
}

function renderCategories() {
    const container = document.getElementById('categoriesContainer');
    const categoryInfo = {
        fruits: { title: 'الفواكه', emojiFallback: '🍎' },
        vegetables: { title: 'الخضروات', emojiFallback: '🥬' }
    };
    const entries = Object.entries(productsData.categories || {});
    container.innerHTML = entries.map(([key, items]) => {
        const info = categoryInfo[key] || { title: key, emojiFallback: '🥗' };
        return `
            <section class="category-section" id="${key}">
                <div class="category-header">
                    <h2 class="category-title">
                        <span class="emoji">${info.emojiFallback}</span>
                        ${info.title}
                    </h2>
                </div>
                <div class="products-grid">
                    ${items.map(p => createCard(p)).join('')}
                </div>
            </section>
        `;
    }).join('');
}

function createCard(p) {
    const icon = p.photo
        ? `<img src="${p.photo}" alt="${p.name}" style="width:52px;height:52px;border-radius:14px;object-fit:cover;border:1px solid #eee;">`
        : `<span class="emoji">${p.emoji || '🛍️'}</span>`;
    return `
        <div class="price-card">
            ${icon}
            <div class="info">
                <div class="name">${p.name}</div>
                <div class="origin"><i class="fas fa-map-marker-alt"></i> ${p.origin || ''}</div>
                <div class="prices">
                    <span class="current">${p.price} ر.س</span>
                    ${p.oldPrice ? `<span class="old">${p.oldPrice} ر.س</span>` : ''}
                    <span class="unit">/ ${p.unit || ''}</span>
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
        const r = await fetch(`${API_BASE}/cart/add`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ product_id: id, name: p?.name, price: p?.price, quantity: 1 })
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
        btn.innerHTML = orig;
        btn.disabled = false;
    }
}
</script>
</body>
</html>
