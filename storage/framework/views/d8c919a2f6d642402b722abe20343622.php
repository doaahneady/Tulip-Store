<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>هدايا توليب - Tulip Gifts</title>
    <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon.png">
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>&fix=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family:  'El Messiri', sans-serif; background: #f8f9fa; min-height: 100vh; }

        .hero { padding: 1.5rem 0; background: transparent; }
        .hero-card { max-width: 1400px; margin: 0 auto; }
        .hero-card-img { width: 100%; height: auto; display: block; }

        /* Main Container */
        .gifts-container { max-width: 1300px; margin: 2rem auto 0; padding: 0 2rem 4rem; position: relative; z-index: 2; }

        /* Premium Cards Section */
        .premium-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 5rem;
        }

        /* Premium Card */
        .premium-card {
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(0,0,0,0.08);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 440px;
            border: 1px solid rgba(0,0,0,0.06);
        }
        .premium-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            border-color: var(--card-color);
        }
        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--card-gradient);
            z-index: 2;
        }
        .premium-card.box-card { --card-gradient: linear-gradient(90deg, #c9956c, #daa87e, #e8c4a8); --card-color: #c9956c; }
        .premium-card.flower-card { --card-gradient: linear-gradient(90deg, #e91e63, #f48fb1, #fce4ec); --card-color: #e91e63; }
        .premium-card.ready-card { --card-gradient: linear-gradient(90deg, #1a5a5a, #3d9a8a, #7dd3c0); --card-color: #1a5a5a; }

        .card-visual {
            height: 180px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .card-visual::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8) 0%, transparent 60%);
        }
        .card-image {
            height: 140px;
            width: auto;
            max-height: 100%;
            position: relative;
            z-index: 1;
            transition: all 0.5s ease;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.15));
        }
        .premium-card:hover .card-image {
            transform: scale(1.05);
        }
        .card-emoji {
            font-size: 5.5rem;
            position: relative;
            z-index: 1;
            transition: all 0.5s ease;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.15));
        }
        .premium-card:hover .card-emoji {
            transform: scale(1.1) rotate(5deg);
        }
        .card-floating {
            position: absolute;
            font-size: 1.5rem;
            opacity: 0.3;
            animation: floatAround 6s ease-in-out infinite;
        }
        .card-floating:nth-child(2) { top: 20%; left: 15%; animation-delay: 0s; }
        .card-floating:nth-child(3) { top: 60%; right: 15%; animation-delay: 2s; }
        .card-floating:nth-child(4) { bottom: 20%; left: 25%; animation-delay: 4s; }
        @keyframes floatAround {
            0%, 100% { transform: translate(0, 0) rotate(0deg); opacity: 0.3; }
            50% { transform: translate(8px, -12px) rotate(15deg); opacity: 0.5; }
        }

        .card-content {
            padding: 1.2rem 1.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .card-tag {
            display: inline-block;
            background: var(--card-color);
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.6rem;
        }
        .card-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 0.4rem;
            font-weight: 700;
        }
        .card-desc {
            color: #7f8c8d;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .card-features {
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
        }
        .feature {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.8rem;
            color: #95a5a6;
        }
        .feature i { color: var(--card-color); font-size: 0.85rem; }
        .card-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 2rem;
            background: var(--card-gradient);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            margin-top: auto;
            align-self: center;
        }
        .card-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
        }
        .card-btn i { transition: transform 0.3s; }
        .card-btn:hover i { transform: translateX(-5px); }

        /* Section Title */
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

        /* Ready Gifts Grid */
        .gifts-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        /* Gift Card */
        .gift-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
        }
        .gift-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        .gift-image {
            aspect-ratio: 1 / 1;
            width: 100%;
            height: auto;
            background: linear-gradient(135deg, #f5f7fa, #e4e8eb);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .gift-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: relative;
            z-index: 1;
        }
        .gift-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.6) 0%, transparent 50%);
        }
        .gift-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(231,76,60,0.4);
        }
        .gift-badge.new { background: linear-gradient(135deg, #9b59b6, #8e44ad); box-shadow: 0 4px 15px rgba(155,89,182,0.4); }
        .gift-badge.sale { background: linear-gradient(135deg, #27ae60, #2ecc71); box-shadow: 0 4px 15px rgba(39,174,96,0.4); }
        .gift-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        .gift-add-btn { margin-top: auto; }
        .gift-name {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.15rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .gift-rating {
            display: none;
            align-items: center;
            gap: 0.3rem;
            margin-bottom: 0.8rem;
        }
        /* .gift-rating i { color: #f1c40f; font-size: 0.85rem; }
        .gift-rating span { color: #95a5a6; font-size: 0.85rem; margin-right: 0.3rem; } */
        .gift-price {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1rem;
        }
        .price-current {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a5a5a;
        }
        .price-old {
            font-size: 1rem;
            color: #bdc3c7;
            text-decoration: line-through;
        }
        .gift-add-btn {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #1a5a5a, #2d7a7a);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .gift-add-btn:hover {
            background: linear-gradient(135deg, #2d7a7a, #3d9a8a);
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(26,90,90,0.3);
        }

        /* View All */
        .view-all-container {
            text-align: center;
            margin-top: 3rem;
        }
        .view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1rem 3rem;
            background: #fff;
            color: #1a5a5a;
            border: 2px solid #1a5a5a;
            border-radius: 50px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .view-all-btn:hover {
            background: #1a5a5a;
            color: #fff;
            box-shadow: 0 10px 30px rgba(26,90,90,0.3);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .premium-cards { grid-template-columns: repeat(2, 1fr); }
            .gifts-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 900px) {
            .premium-cards { grid-template-columns: 1fr; max-width: 500px; margin: 0 auto 4rem; }
            .gifts-grid { grid-template-columns: repeat(2, 1fr); }
            
            /* Disable hover scale on mobile */
            .premium-card:hover, .gift-card:hover {
                transform: none !important;
                box-shadow: 0 8px 30px rgba(0,0,0,0.08) !important;
            }
        }
        @media (max-width: 600px) {
            .hero-title { font-size: 2.2rem; }
            .hero-subtitle { font-size: 1rem; }
            .hero-icon-item { font-size: 2.5rem; }
            .gifts-grid { 
                grid-template-columns: repeat(2, 1fr); 
                gap: 0.5rem;
            }
            .section-title { font-size: 1.8rem; }
            .gift-info { padding: 0.8rem; }
            .gift-name { font-size: 0.9rem; }
            .price-current { font-size: 1.1rem; }
            .gift-add-btn { padding: 0.6rem; font-size: 0.85rem; }
        }
    </style>
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="gifts-container">
        <!-- Gifts Creation Section (dynamic images from DB) -->
        <div class="premium-cards">
            <!-- Custom Box Card -->
            <div class="premium-card box-card" onclick="window.location.href='/gifts/box-arrangement'">
                <div class="card-visual">
                   
                    <img id="boxCardImage" src="/images/mistery_box.jpg" alt="Gift Box" class="card-image" loading="lazy" >
                </div>
                <div class="card-content">
                    <span class="card-tag">الأكثر طلباً</span>
                    <h3 class="card-title">تنسيق صندوق هدية</h3>
                    <p class="card-desc">صمم صندوق هدية فريد بإضافة الشوكولاتة والعطور والإكسسوارات وكل ما يحبه قلبك</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-box"></i> 4 أحجام</span>
                        <span class="feature"><i class="fas fa-gift"></i> +50 عنصر</span>
                        <span class="feature"><i class="fas fa-envelope"></i> بطاقة مجانية</span>
                    </div>
                    <button class="card-btn">
                        ابدأ التصميم
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- Custom Bouquet Card -->
            <div class="premium-card flower-card" onclick="window.location.href='/gifts/flower-bouquet'">
                <div class="card-visual">
                   
                    <img id="bouquetCardImage" src="/images/banner.jpg" alt="Rose Bouquet" class="card-image" loading="lazy">
                </div>
                <div class="card-content">
                    <span class="card-tag">  دفعة ورورد جديدة يومياً</span>
                    <h3 class="card-title">تنسيق باقة ورد</h3>
                    <p class="card-desc">اختر من أجمل الزهور ونسق باقتك المثالية بالألوان والتغليف الذي تفضله</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-seedling"></i> +20 نوع زهور</span>
                        <span class="feature"><i class="fas fa-palette"></i> ألوان متعددة</span>
                        <span class="feature"><i class="fas fa-truck"></i> توصيل سريع</span>
                    </div>
                    <button class="card-btn">
                        ابدأ التصميم
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- Ready Made Card -->
            <div class="premium-card ready-card" onclick="document.getElementById('readyGifts').scrollIntoView({behavior: 'smooth'})">
                <div class="card-visual">
                  
                    <img id="readyCardImage" src="/images/" alt="Ready Gifts" class="card-image" loading="lazy" onerror="this.src=''">
                </div>
                <div class="card-content">
                    <span class="card-tag">جاهزة للتوصيل</span>
                    <h3 class="card-title">هدايا جاهزة</h3>
                    <p class="card-desc">مجموعة منتقاة بعناية من أفخم الهدايا المنسقة والجاهزة للتوصيل في نفس اليوم</p>
                    <div class="card-features">
                        <span class="feature"><i class="fas fa-check-circle"></i> منسقة باحتراف</span>
                        <span class="feature"><i class="fas fa-tag"></i> أسعار مميزة</span>
                        <span class="feature"><i class="fas fa-shield-alt"></i> ضمان الجودة</span>
                    </div>
                    <button class="card-btn">
                        تصفح الهدايا
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>
        </div>
        

        <!-- Ready Made Gifts Section -->
        <section id="readyGifts">
            <div class="section-header">
                <span class="section-label"><i class="fas fa-star"></i> من صنعنا </span>
                <p class="section-subtitle">اختر من مجموعتنا المنسقة بعناية واستمتع بتوصيل سريع </p>
            </div>
            
            <div class="gifts-grid" id="giftsGrid"></div>

            <!-- <div class="view-all-container">
                <button class="view-all-btn" onclick="window.location.href='/store?category=gifts'">
                    عرض جميع الهدايا
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div> -->
        </section>
    </div>

    <!-- Footer -->
 <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <script>
        function resolveMediaUrl(path) {
            const p = String(path || '').trim();
            if (!p) return '/images/tulip_gift.jpg';
            if (p.startsWith('http://') || p.startsWith('https://')) return p;
            if (p.startsWith('/')) return p;
            const cleaned = p.replace(/^storage\//, '');
            return `/storage/${cleaned}`;
        }

        async function hydrateGiftCreationSection() {
            try {
                const res = await fetch('/api/gifts/featured');
                const payload = await res.json();
                const gifts = Array.isArray(payload.data) ? payload.data : [];
                const fallback = '/images/tulip_gift.jpg';
                const img1 = document.getElementById('boxCardImage');
                const img2 = document.getElementById('bouquetCardImage');
                const img3 = document.getElementById('readyCardImage');
                const getImg = (g) => resolveMediaUrl((g?.main_image || (g?.images && g.images[0]) || g?.image || '') || '') || fallback;
                if (img1) img1.src = getImg(gifts[0]) || img1.src || fallback;
                if (img2) img2.src = getImg(gifts[1] || gifts[0]) || img2.src || fallback;
                if (img3) img3.src = getImg(gifts[2] || gifts[1] || gifts[0]) || img3.src || fallback;
            } catch (e) {
                // Leave placeholders if API not available
            }
        }

        async function loadGifts() {
            const grid = document.getElementById('giftsGrid');
            if (!grid) return;

            grid.innerHTML = `
                <div style="text-align:center;color:#999;padding:2rem;grid-column:1/-1;">
                    <i class="fas fa-spinner fa-spin" style="font-size:2rem;margin-bottom:1rem;opacity:0.6;"></i>
                    <p>جاري تحميل الهدايا...</p>
                </div>
            `;

            try {
                const response = await fetch('/api/gifts?sort=featured&per_page=24');
                const data = await response.json();
                const gifts = Array.isArray(data.data) ? data.data : [];

                if (!gifts.length) {
                    grid.innerHTML = `
                        <div style="text-align:center;color:#999;padding:2rem;grid-column:1/-1;">
                            <i class="fas fa-gift" style="font-size:2rem;margin-bottom:1rem;opacity:0.4;"></i>
                            <p>لا توجد هدايا حالياً</p>
                        </div>
                    `;
                    return;
                }

                grid.innerHTML = gifts.map(gift => {
                    const image = resolveMediaUrl(gift.main_image || (gift.images && gift.images[0]) || gift.image || '') || '/images/tulip_gift.jpg';
                    const rating = Number(gift.rating ?? 0);
                    const reviews = Number(gift.reviews_count ?? 0);
                    const badgeText = gift.is_featured ? 'مميز' : '';
                    const badgeClass = gift.is_featured ? 'new' : '';
                    return `
                        <div class="gift-card" onclick="window.location.href='/gifts/${gift.id}'">
                            <div class="gift-image">
                                ${badgeText ? `<span class="gift-badge ${badgeClass}">${badgeText}</span>` : ''}
                                <img src="${image}" alt="${gift.name || ''}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'">
                            </div>
                            <div class="gift-info">
                                <h3 class="gift-name">${gift.name || ''}</h3>
                                <div class="gift-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star${rating < 5 ? '-half-alt' : ''}"></i>
                                    <span>${rating.toFixed(1)} (${reviews})</span>
                                </div>
                                <div class="gift-price">
                                    <span class="price-current">${Number(gift.price || 0)} ل.س</span>
                                </div>
                                <button class="gift-add-btn" onclick="event.stopPropagation(); window.location.href='/gifts/${gift.id}'">
                                    <i class="fas fa-eye"></i>
                                    عرض
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            } catch (e) {
                grid.innerHTML = `
                    <div style="text-align:center;color:#999;padding:2rem;grid-column:1/-1;">
                        <i class="fas fa-exclamation-triangle" style="font-size:2rem;margin-bottom:1rem;opacity:0.4;"></i>
                        <p>تعذر تحميل الهدايا</p>
                    </div>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            hydrateGiftCreationSection();
            loadGifts();
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/gifts/index.blade.php ENDPATH**/ ?>