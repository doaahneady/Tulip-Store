<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>تنسيق صندوق هدية - Tulip Gift</title>
    <!-- fav icon -->
        <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <meta name="description" content="اكتشف Tulip Store، منصة تسوق إلكتروني متكاملة تتيح لك الشراء أو إنشاء متجرك الخاص والربح بسهولة، مع توصيل سريع وطرق دفع آمنة وتجربة استخدام مريحة.">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <style>
        :root {
            --primary: #8b6914;
            --accent: #d4af37;
            --bg-cream: #fdfbf7;
            --bg-warm: #f9f5ed;
            --text-dark: #2c2416;
            --text-muted: #8a7d6d;
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #f4d03f 50%, #c9a227 100%);
            --shadow: 0 10px 40px rgba(139, 105, 20, 0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'El Messiri', sans-serif; background: var(--bg-cream); min-height: 100vh; }
        
        .hero-banner {
            background: linear-gradient(135deg, #2c2416 0%, #4a3c28 50%, #6b5a3c 100%);
            padding: 3.5rem 2rem;
            position: relative;
        }
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }
        .hero-text h1 {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.8rem;
            background: var(--gold-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.8rem;
        }
        .hero-text p { font-size: 1.1rem; color: rgba(255,255,255,0.8); max-width: 480px; line-height: 1.8; }
        .hero-icon { font-size: 5rem; animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-15px)} }

        .main-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        
        .steps-progress {
            display: flex; justify-content: center; gap: 0; margin-bottom: 2rem;
            background: #fff; padding: 1.2rem 1.5rem; border-radius: 16px; box-shadow: var(--shadow);
        }
        .step {
            display: flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem;
            color: var(--text-muted); font-weight: 500; cursor: pointer; transition: all 0.3s; border-radius: 10px;
        }
        .step.active { color: var(--primary); background: linear-gradient(135deg, #fdf8e8, #fef6dc); }
        .step.completed { color: #2e7d32; }
        .step-number {
            width: 32px; height: 32px; border-radius: 50%; background: #f0ebe0;
            display: flex; align-items: center; justify-content: center; font-weight: 700;
        }
        .step.active .step-number { background: var(--gold-gradient); color: #fff; }
        .step.completed .step-number { background: #43a047; color: #fff; }

        .builder-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start; }
        
        .options-panel { background: #fff; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow); }
        .panel-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--bg-warm); }
        .panel-icon { width: 45px; height: 45px; background: var(--gold-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; }
        .panel-title { font-family: 'El Messiri', sans-serif; font-size: 1.4rem; color: var(--text-dark); }
        .panel-subtitle { font-size: 0.85rem; color: var(--text-muted); }

        .section-label { font-family: 'El Messiri', sans-serif; font-size: 1.1rem; color: var(--text-dark); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .section-label i { color: var(--accent); }

        .filter-tabs { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 0.5rem; 
            margin-bottom: 1.5rem; 
        }
        .filter-tabs::-webkit-scrollbar { display: none; } /* Chrome, Safari, Opera */

        .filter-tab {
            padding: 0.4rem 1rem; border: 2px solid #eee; background: #fff; border-radius: 25px;
            cursor: pointer; font-family:'El Messiri', sans-serif; font-size: 0.85rem; transition: all 0.3s;
            white-space: nowrap;
        }
        .filter-tab:hover, .filter-tab.active { border-color: var(--accent); background: #fdf8e8; color: var(--primary); }

        .options-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
        
        .option-card {
            background: var(--bg-warm); border: 2px solid transparent; border-radius: 16px;
            padding: 0; cursor: pointer; transition: all 0.3s; text-align: center; position: relative;
            overflow: hidden;
        }
        button.option-card { font-family:'El Messiri', sans-serif; width: 100%; }
        .option-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(139,105,20,0.12); border-color: rgba(212,175,55,0.3); }
        .option-card.selected { border-color: var(--accent); background: linear-gradient(135deg, #fefbf3, #fdf8e8); }
        .option-card.selected::after {
            content: '✓'; position: absolute; top: 8px; left: 8px; width: 24px; height: 24px;
            background: var(--gold-gradient); border-radius: 50%; color: #fff; display: flex;
            align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold; z-index: 10;
        }
        .option-visual { width: 100%; height: 160px; margin-bottom: 0.6rem; background: #fff; overflow: hidden; border-bottom: 1px solid #f0f0f0; }
        .option-visual img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .option-name { font-weight: 600; color: var(--text-dark); font-size: 0.85rem; margin-bottom: 0.2rem; padding: 0 0.5rem; }
        .option-price { color: var(--primary); font-weight: 700; font-size: 0.8rem; padding: 0 0.5rem; }
        .option-meta { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.1rem; padding: 0 0.5rem 0.8rem; }

        .three-cols-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.8rem;
        }

        .option-card .tooltip { display: none; }
    </style>

    <style>
        .preview-panel { position: sticky; top: 2rem; }
        .preview-card, .summary-card { background: #fff; border-radius: 18px; padding: 1.5rem; box-shadow: var(--shadow); margin-bottom: 1rem; }
        .preview-header, .summary-header { font-family: 'El Messiri', sans-serif; font-size: 1.1rem; color: var(--text-dark); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .preview-header i, .summary-header i { color: var(--accent); }
        .preview-content { min-height: 180px; background: linear-gradient(135deg, #fdf8e8, #fef6dc); border-radius: 14px; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .preview-empty { text-align: center; color: var(--text-muted); }
        .empty-icon { font-size: 3rem; margin-bottom: 0.5rem; }
        .preview-gift { text-align: center; width: 100%; display: flex; flex-direction: column; align-items: center; gap: 1rem; }
        .preview-visual { position: relative; width: 120px; height: 120px; }
        .preview-box-img { width: 100%; height: 100%; object-fit: contain; }
        .preview-ribbon-img { position: absolute; top: -10px; left: 50%; transform: translateX(-50%); width: 60px; z-index: 2; }
        .preview-items { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem; margin-top: 0.5rem; }
        .preview-item-img { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; background: #fff; padding: 2px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .preview-label { font-weight: 700; color: var(--text-dark); font-size: 1rem; }

        .summary-items { max-height: 180px; overflow-y: auto; margin-bottom: 1rem; }
        .summary-item { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0ebe0; font-size: 0.9rem; }
        .summary-item:last-child { border: none; }
        .summary-item-name { color: #666; }
        .summary-item-price { font-weight: 600; color: var(--text-dark); }
        .summary-empty { color: var(--text-muted); text-align: center; padding: 1rem; }
        .summary-total { display: flex; justify-content: space-between; padding: 1rem 0; border-top: 2px solid #f0ebe0; font-family: 'El Messiri', sans-serif; font-size: 1.2rem; font-weight: 700; color: var(--text-dark); }
        #totalPrice { color: var(--primary); }

        .cart-btn {
            width: 100%; padding: 0.6rem; background: var(--gold-gradient); color: #fff; border: none;
            border-radius: 12px; font-family: 'El Messiri', sans-serif; font-size: 1.05rem; font-weight: 700;
            cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .cart-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212,175,55,0.4); }
        .cart-btn:disabled { background: #ddd; cursor: not-allowed; }

        .nav-buttons { display: flex; gap: 1rem; }
        .nav-btn { flex: 1; padding: 0.6rem; border: none; border-radius: 10px; font-family: 'El Messiri', sans-serif; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .nav-btn.prev { background: #f5f5f5; color: #666; }
        .nav-btn.prev:hover { background: #eee; }
        .nav-btn.next { background: linear-gradient(135deg, #2c2416, #4a3c28); color: #fff; }
        .nav-btn.next:hover { transform: translateY(-2px); }

        .message-section { margin-top: 1.5rem; }
        .elegant-input, .elegant-textarea { width: 100%; padding: 1rem; border: 2px solid #f0ebe0; border-radius: 12px; font-family: inherit; font-size: 1rem; transition: all 0.3s; margin-bottom: 0.5rem; background: var(--bg-warm); }
        .elegant-input:focus, .elegant-textarea:focus { outline: none; border-color: var(--accent); background: #fff; }
        .elegant-textarea { min-height: 100px; resize: vertical; }
        .char-counter { text-align: left; color: var(--text-muted); font-size: 0.8rem; }

        @media (max-width: 1024px) { 
            .builder-layout { grid-template-columns: 1fr; } 
            .preview-panel { position: static; } 
            .hero-content { flex-direction: column; text-align: center; } 
        }
        @media (max-width: 768px) {
            .preview-card { display: none !important; }
        }
        @media (max-width: 600px) { 
            .hero-text h1 { font-size: 2rem; } 
            .steps-progress { flex-wrap: wrap; gap: 0.5rem; } 
            .step { padding: 0.5rem 0.8rem; font-size: 0.85rem; } 
            .options-grid, .three-cols-grid { grid-template-columns: repeat(4, 1fr); gap: 0.5rem; } 
            .option-card { padding: 0; border-radius: 12px; }
            .option-visual { width: 100%; height: 80px; margin-bottom: 0.4rem; border-radius: 0; }
            .option-name { font-size: 0.6rem; padding: 0 0.2rem; }
            .option-price { font-size: 0.55rem; padding: 0 0.2rem; }
            .option-meta { font-size: 0.5rem; padding: 0 0.2rem 0.4rem; }
            .option-card.selected::after { width: 16px; height: 16px; font-size: 0.6rem; top: 4px; left: 4px; }
            
            .nav-buttons { flex-direction: column; gap: 0.5rem; }
            .nav-btn, .cart-btn { width: 100%; padding: 0.8rem; }
        }
    </style>

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-text">
                <h1> صمم هديتك الفاخرة</h1>
                <p>اختر من مجموعتنا الراقية من الصناديق والمحتويات الفاخرة لتصنع هدية مميزة</p>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="steps-progress">
            <div class="step active" data-step="1" onclick="goToStep(1)"><span class="step-number">1</span><span>الصندوق</span></div>
            <div class="step" data-step="2" onclick="goToStep(2)"><span class="step-number">2</span><span>المحتويات</span></div>
            <div class="step" data-step="3" onclick="goToStep(3)"><span class="step-number">3</span><span>التغليف</span></div>
            <div class="step" data-step="4" onclick="goToStep(4)"><span class="step-number">4</span><span>البطاقة</span></div>
        </div>

        <div class="builder-layout">
            <div class="options-panel">
                <div class="step-content" id="step1">
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-box-open"></i></div>
                        <div><h2 class="panel-title">اختر صندوق الهدية</h2><p class="panel-subtitle">صناديق فاخرة بأحجام متنوعة</p></div>
                    </div>
                    <div class="options-grid" id="boxesGrid"></div>
                </div>
                <div class="step-content" id="step2" style="display:none;">
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-gifts"></i></div>
                        <div><h2 class="panel-title">أضف محتويات الهدية</h2><p class="panel-subtitle">اختر العناصر المفضلة</p></div>
                    </div>
                    <div class="filter-tabs" id="categoryTabs"></div>
                    <div class="options-grid" id="fillersGrid"></div>
                </div>
                <div class="step-content" id="step3" style="display:none;">
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-gift"></i></div>
                        <div><h2 class="panel-title">التغليف والشريط</h2><p class="panel-subtitle">لمسات نهائية أنيقة</p></div>
                    </div>
                    <h3 class="section-label"><i class="fas fa-scroll"></i> نوع التغليف</h3>
                    <div class="three-cols-grid" id="wrappingsGrid"></div>
                    <h3 class="section-label" style="margin-top:1.5rem;"><i class="fas fa-ribbon"></i> الشريط</h3>
                    <div class="three-cols-grid" id="ribbonsGrid"></div>
                </div>
                <div class="step-content" id="step4" style="display:none;">
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-envelope-open-text"></i></div>
                        <div><h2 class="panel-title">البطاقة والرسالة</h2><p class="panel-subtitle">أضف لمستك الشخصية</p></div>
                    </div>
                    <div class="options-grid" id="cardsGrid"></div>
                    <div class="message-section">
                        <h3 class="section-label"><i class="fas fa-pen-fancy"></i> رسالتك</h3>
                        <input type="text" id="recipientName" class="elegant-input" placeholder="اسم المستلم (اختياري)">
                        <textarea id="cardMessage" class="elegant-textarea" placeholder="اكتب رسالتك هنا..." maxlength="200"></textarea>
                        <div class="char-counter"><span id="charCount">0</span>/200</div>
                    </div>
                </div>
            </div>
            <div class="preview-panel">
                <div class="preview-card">
                    <div class="preview-header"><i class="fas fa-eye"></i><span>معاينة الهدية</span></div>
                    <div class="preview-content" id="giftPreview"><div class="preview-empty"><div class="empty-icon">📦</div><p>اختر صندوق للبدء</p></div></div>
                </div>
                <div class="summary-card">
                    <div class="summary-header"><i class="fas fa-receipt"></i><span>ملخص الطلب</span></div>
                    <div class="summary-items" id="summaryItems"><div class="summary-empty">لم تختر أي عناصر بعد</div></div>
                    <div class="summary-total"><span>الإجمالي</span><span id="totalPrice">0 ل.س</span></div>
                    <button class="cart-btn" id="addToCartBtn" disabled onclick="addGiftToCart()"><i class="fas fa-shopping-cart"></i> أضف للسلة</button>
                </div>
                <div class="nav-buttons">
                    <button class="nav-btn prev" id="prevBtn" onclick="prevStep()" style="display:none;"><i class="fas fa-arrow-right"></i> السابق</button>
                    <button class="nav-btn next" id="nextBtn" onclick="nextStep()">التالي <i class="fas fa-arrow-left"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let giftState = { box: null, fillers: [], storeProducts: [], wrapping: null, ribbon: null, card: null, message: '', recipientName: '' };

        let boxes = [];
        let fillers = [];
        let wrappings = [];
        let ribbons = [];
        let cards = [];
        let categories = [];

        let storeProducts = [];

        function resolveMediaUrl(path) {
            const p = String(path || '').trim();
            if (!p) return '/images/tulip_gift.jpg';
            if (p.startsWith('http://') || p.startsWith('https://')) return p;
            if (p.startsWith('/')) return `${window.location.origin}${p}`;
            const cleaned = p.replace(/^storage\//, '');
            return `${window.location.origin}/storage/${cleaned}`;
        }
        document.addEventListener('DOMContentLoaded', async () => {
            await loadGiftOptions();
            loadBoxes();
            loadCategories();
            loadFillers();
            loadWrappings();
            loadRibbons();
            loadCards();
            setupMessage();
            loadStoreProducts();
        });

        async function loadGiftOptions() {
            try {
                const res = await fetch('/api/custom-gift/options');
                const data = await res.json();
                boxes = Array.isArray(data.boxes) ? data.boxes : [];
                fillers = Array.isArray(data.fillers) ? data.fillers : [];
                wrappings = Array.isArray(data.wrappings) ? data.wrappings : [];
                ribbons = Array.isArray(data.ribbons) ? data.ribbons : [];
                cards = [
                    {id: 1001, name: 'بطاقة عيد ميلاد', price: 0, image: '/images/birthday_card.jpeg'},
                    {id: 1002, name: 'بطاقة تهنئة', price: 0, image: '/images/f_card.png'}
                ];

                const emojiByCategory = {
                    chocolate: '🍫',
                    flower: '🌸',
                    perfume: '🌺',
                    accessory: '💍',
                    candy: '🍬',
                    other: '✨',
                };

                const fillerCats = Array.from(new Set(fillers.map(f => f.category).filter(Boolean)));
                categories = [{ id: 'all', name: 'الكل' }]
                    .concat(fillerCats.map(id => ({ id, name: id })))
                    .concat([{ id: 'tulip', name: 'منتجات Tulip' }]);
            } catch (e) {
                boxes = [];
                fillers = [];
                wrappings = [];
                ribbons = [];
                cards = [];
                categories = [{ id: 'all', name: 'الكل' }, { id: 'tulip', name: 'منتجات Tulip' }];
            }
        }

        function loadBoxes() {
            if (!boxes.length) {
                document.getElementById('boxesGrid').innerHTML = '<div class="summary-empty">لا توجد صناديق متاحة</div>';
                return;
            }
            document.getElementById('boxesGrid').innerHTML = boxes.map(b => `
                <button type="button" class="option-card ${giftState.box?.id === b.id ? 'selected' : ''}" onclick="selectBox(${b.id})" aria-label="اختر ${b.name}">
                    <div class="option-visual"><img src="${resolveMediaUrl(b.image)}" alt="${b.name}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${b.name}</div>
                    <div class="option-price">${b.price} ل.س</div>
                    <div class="option-meta">حتى ${b.maxItems} عناصر</div>
                </button>
            `).join('');
        }

        function loadCategories() {
            document.getElementById('categoryTabs').innerHTML = categories.map(c => `
                <button class="filter-tab ${c.id === 'all' ? 'active' : ''}" data-cat="${c.id}" onclick="filterCategory('${c.id}')">${c.name}</button>
            `).join('');
        }

        function loadFillers(cat = 'all') {
            if (cat === 'tulip') {
                const selectedIds = giftState.storeProducts.map(p => p.id);
                document.getElementById('fillersGrid').innerHTML = storeProducts.length ? storeProducts.map(p => `
                    <button type="button" class="option-card ${selectedIds.includes(p.id) ? 'selected' : ''}" onclick="toggleStoreProduct(${p.id})" aria-label="أضف ${p.name}">
                        <div class="option-visual"><img src="${resolveMediaUrl(p.image)}" alt="${p.name}" loading="lazy" onerror="this.src='/images/-tulip_gift.jpg'"></div>
                        <div class="option-name">${p.name}</div>
                        <div class="option-price">${p.price} ل.س</div>
                    </button>
                `).join('') : '<div class="summary-empty">جاري تحميل منتجات Tulip...</div>';
                return;
            }
            const filtered = cat === 'all' ? fillers : fillers.filter(f => f.category === cat);
            if (!filtered.length) {
                document.getElementById('fillersGrid').innerHTML = '<div class="summary-empty">لا توجد عناصر متاحة</div>';
                return;
            }
            document.getElementById('fillersGrid').innerHTML = filtered.map(f => `
                <button type="button" class="option-card ${giftState.fillers.includes(f.id) ? 'selected' : ''}" onclick="toggleFiller(${f.id})" aria-label="أضف ${f.name}">
                    <div class="option-visual"><img src="${resolveMediaUrl(f.image)}" alt="${f.name}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${f.name}</div>
                    <div class="option-price">${f.price} ل.س</div>
                </button>
            `).join('');
        }

        async function loadStoreProducts() {
            try {
                const res = await fetch('/api/products?per_page=12&sort_by=created_at&sort_order=desc');
                const data = await res.json();
                const items = data.data || [];
                storeProducts = items.map(p => ({
                    id: p.id,
                    name: p.name,
                    price: Number(p.discount_price ?? p.price),
                    image: p.primary_image_url ?? p.image ?? (Array.isArray(p.images) ? p.images[0] : null) ?? null
                }));
                if (document.querySelector('.filter-tab.active')?.dataset.cat === 'tulip') loadFillers('tulip');
            } catch (e) {
                storeProducts = [];
            }
        }

        function loadWrappings() {
            if (!wrappings.length) {
                document.getElementById('wrappingsGrid').innerHTML = '<div class="summary-empty">لا توجد تغليفات متاحة</div>';
                return;
            }
            document.getElementById('wrappingsGrid').innerHTML = wrappings.map(w => `
                <button type="button" class="option-card ${giftState.wrapping?.id === w.id ? 'selected' : ''}" onclick="selectWrapping(${w.id})" aria-label="اختر ${w.name}">
                    <div class="option-visual"><img src="${resolveMediaUrl(w.image)}" alt="${w.name}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${w.name}</div>
                    <div class="option-price">${w.price > 0 ? w.price + ' ل.س' : 'مجاني'}</div>
                </button>
            `).join('');
        }

        function loadRibbons() {
            if (!ribbons.length) {
                document.getElementById('ribbonsGrid').innerHTML = '<div class="summary-empty">لا توجد شرائط متاحة</div>';
                return;
            }
            document.getElementById('ribbonsGrid').innerHTML = ribbons.map(r => `
                <button type="button" class="option-card ${giftState.ribbon?.id === r.id ? 'selected' : ''}" onclick="selectRibbon(${r.id})" aria-label="اختر ${r.name}">
                    <div class="option-visual"><img src="${resolveMediaUrl(r.image)}" alt="${r.name}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${r.name}</div>
                    <div class="option-price">${r.price > 0 ? r.price + ' ل.س' : 'مجاني'}</div>
                </button>
            `).join('');
        }

        function loadCards() {
            if (!cards.length) {
                document.getElementById('cardsGrid').innerHTML = '<div class="summary-empty">لا توجد بطاقات متاحة</div>';
                return;
            }
            document.getElementById('cardsGrid').innerHTML = cards.map(c => `
                <button type="button" class="option-card ${giftState.card?.id === c.id ? 'selected' : ''}" onclick="selectCard(${c.id})" aria-label="اختر ${c.name}">
                    <div class="option-visual"><img src="${resolveMediaUrl(c.image)}" alt="${c.name}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${c.name}</div>
                    <div class="option-price">${c.price > 0 ? c.price + ' ل.س' : 'مجاني'}</div>
                </button>
            `).join('');
        }

        function setupMessage() {
            document.getElementById('cardMessage').addEventListener('input', e => { document.getElementById('charCount').textContent = e.target.value.length; giftState.message = e.target.value; });
            document.getElementById('recipientName').addEventListener('input', e => { giftState.recipientName = e.target.value; });
        }

        function selectBox(id) {
            giftState.box = boxes.find(b => b.id === id);
            if (giftState.fillers.length > giftState.box.maxItems) giftState.fillers = giftState.fillers.slice(0, giftState.box.maxItems);
            loadBoxes(); updatePreview(); updateSummary();
        }

        function toggleFiller(id) {
            if (!giftState.box) { alert('اختر صندوق أولاً'); return; }
            const idx = giftState.fillers.indexOf(id);
            if (idx >= 0) { giftState.fillers.splice(idx, 1); }
            else if ((giftState.fillers.length + giftState.storeProducts.length) < giftState.box.maxItems) { giftState.fillers.push(id); }
            else { alert(`الصندوق يتسع لـ ${giftState.box.maxItems} عناصر فقط`); return; }
            loadFillers(document.querySelector('.filter-tab.active')?.dataset.cat || 'all'); updatePreview(); updateSummary();
        }

        function toggleStoreProduct(id) {
            if (!giftState.box) { alert('اختر صندوق أولاً'); return; }
            const idx = giftState.storeProducts.findIndex(p => p.id === id);
            if (idx >= 0) {
                giftState.storeProducts.splice(idx, 1);
            } else {
                if ((giftState.fillers.length + giftState.storeProducts.length) >= giftState.box.maxItems) { alert(`الصندوق يتسع لـ ${giftState.box.maxItems} عناصر فقط`); return; }
                const p = storeProducts.find(x => x.id === id);
                if (p) giftState.storeProducts.push({ id: p.id, name: p.name, price: p.price, qty: 1 });
            }
            loadFillers('tulip'); updateSummary();
        }

        function filterCategory(cat) {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.toggle('active', t.dataset.cat === cat));
            loadFillers(cat);
        }

        function selectWrapping(id) { giftState.wrapping = wrappings.find(w => w.id === id); loadWrappings(); updatePreview(); updateSummary(); }
        function selectRibbon(id) { giftState.ribbon = ribbons.find(r => r.id === id); loadRibbons(); updatePreview(); updateSummary(); }
        function selectCard(id) { giftState.card = cards.find(c => c.id === id); loadCards(); updateSummary(); }

        function updatePreview() {
            const preview = document.getElementById('giftPreview');
            if (!giftState.box) { preview.innerHTML = '<div class="preview-empty"><div class="empty-icon">📦</div><p>اختر صندوق للبدء</p></div>'; return; }
            
            const ribbonImg = giftState.ribbon && giftState.ribbon.id !== 5 ? `<img src="${resolveMediaUrl(giftState.ribbon.image)}" class="preview-ribbon-img" alt="Ribbon">` : '';
            
            const selectedFillers = giftState.fillers.map(id => fillers.find(f => f.id === id)).filter(Boolean);
            const selectedStoreProducts = giftState.storeProducts;
            const allItems = [...selectedFillers, ...selectedStoreProducts];

            preview.innerHTML = `
                <div class="preview-gift">
                    <div class="preview-visual">
                        ${ribbonImg}
                        <img src="${resolveMediaUrl(giftState.box.image)}" class="preview-box-img" alt="${giftState.box.name}">
                    </div>
                    <div class="preview-label">${giftState.box.name}</div>
                    ${allItems.length > 0 ? `
                        <div class="preview-items">
                            ${allItems.map(item => `
                                <img src="${resolveMediaUrl(item.image)}" class="preview-item-img" title="${item.name}" alt="${item.name}" onerror="this.src='/images/tulip_gift.jpg'">
                            `).join('')}
                        </div>
                    ` : '<p style="color:var(--text-muted);font-size:0.85rem;margin-top:0.5rem;">أضف محتويات للهدية</p>'}
                </div>
            `;
        }

        function updateSummary() {
            const summaryEl = document.getElementById('summaryItems');
            const totalEl = document.getElementById('totalPrice');
            const btn = document.getElementById('addToCartBtn');
            let items = [], total = 0;
            if (giftState.box) { items.push({ name: giftState.box.name, price: giftState.box.price, image: giftState.box.image }); total += giftState.box.price; }
            giftState.fillers.forEach(id => { const f = fillers.find(x => x.id === id); if (f) { items.push({ name: f.name, price: f.price, image: f.image }); total += f.price; } });
            giftState.storeProducts.forEach(p => { items.push({ name: p.name, price: p.price, image: p.image }); total += p.price; });
            if (giftState.wrapping?.price > 0) { items.push({ name: giftState.wrapping.name, price: giftState.wrapping.price, image: giftState.wrapping.image }); total += giftState.wrapping.price; }
            if (giftState.ribbon?.price > 0) { items.push({ name: giftState.ribbon.name, price: giftState.ribbon.price, image: giftState.ribbon.image }); total += giftState.ribbon.price; }
            if (giftState.card?.price > 0) { items.push({ name: giftState.card.name, price: giftState.card.price, image: giftState.card.image }); total += giftState.card.price; }
            
            summaryEl.innerHTML = items.length ? items.map(i => `
                <div class="summary-item">
                    <div style="display:flex; align-items:center; gap:0.8rem;">
                        <img src="${resolveMediaUrl(i.image)}" style="width:35px; height:35px; object-fit:cover; border-radius:6px; background:#f8f9fa;" onerror="this.src='/images/'">
                        <span class="summary-item-name">${i.name}</span>
                    </div>
                    <span class="summary-item-price">${i.price} ل.س</span>
                </div>
            `).join('') : '<div class="summary-empty">لم تختر أي عناصر بعد</div>';
            
            totalEl.textContent = total + ' ل.س';
            btn.disabled = !giftState.box;
        }

        function goToStep(step) {
            if (step < 1 || step > 4) return;
            if (step > 1 && !giftState.box) { alert('اختر صندوق أولاً'); return; }
            currentStep = step;
            document.querySelectorAll('.step').forEach((s, i) => { s.classList.remove('active', 'completed'); if (i + 1 < step) s.classList.add('completed'); if (i + 1 === step) s.classList.add('active'); });
            document.querySelectorAll('.step-content').forEach((c, i) => { c.style.display = i + 1 === step ? 'block' : 'none'; });
            document.getElementById('prevBtn').style.display = step > 1 ? 'flex' : 'none';
            document.getElementById('nextBtn').style.display = step < 4 ? 'flex' : 'none';
        }
        function nextStep() { if (currentStep === 1 && !giftState.box) { alert('اختر صندوق أولاً'); return; } goToStep(currentStep + 1); }
        function prevStep() { goToStep(currentStep - 1); }

        async function addGiftToCart() {
            if (!giftState.box) return;
            const btn = document.getElementById('addToCartBtn');
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            try {
                const res = await fetch('/api/custom-gift/add-to-cart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                    body: JSON.stringify({ box_id: giftState.box.id, fillers: giftState.fillers.map(id => ({ id, qty: 1 })), store_products: giftState.storeProducts.map(p => ({ product_id: p.id, qty: p.qty })), wrapping_id: giftState.wrapping?.id, ribbon_id: giftState.ribbon?.id, card_id: giftState.card?.id, message: giftState.message, recipient_name: giftState.recipientName })
                });
                const data = await res.json();
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة!'; btn.style.background = '#43a047';
                    window.dispatchEvent(new Event('cart-updated'));
                    setTimeout(() => { if (confirm('تمت إضافة الهدية! هل تريد الذهاب للسلة؟')) window.location.href = '/cart'; else resetBuilder(); }, 500);
                } else throw new Error();
            } catch (e) { alert('حدث خطأ'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-shopping-cart"></i> أضف للسلة'; }
        }

        function resetBuilder() {
            giftState = { box: null, fillers: [], storeProducts: [], wrapping: null, ribbon: null, card: null, message: '', recipientName: '' };
            currentStep = 1; goToStep(1); loadBoxes(); loadFillers(); loadWrappings(); loadRibbons(); loadCards(); updatePreview(); updateSummary();
            document.getElementById('cardMessage').value = ''; document.getElementById('recipientName').value = ''; document.getElementById('charCount').textContent = '0';
            document.getElementById('addToCartBtn').style.background = ''; document.getElementById('addToCartBtn').innerHTML = '<i class="fas fa-shopping-cart"></i> أضف للسلة';
        }
    </script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/gifts/box-arrangement.blade.php ENDPATH**/ ?>