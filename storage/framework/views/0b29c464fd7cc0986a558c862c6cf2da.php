<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <title>تنسيق باقة ورد - Tulip Flowers</title>
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <style>
        :root {
            --primary: #c2185b;
            --accent: #e91e63;
            --light: #fce4ec;
            --bg-cream: #fdf8f9;
            --bg-warm: #fef5f7;
            --text-dark: #3d1a24;
            --text-muted: #9a7a82;
            --pink-gradient: linear-gradient(135deg, #e91e63 0%, #f48fb1 50%, #ec407a 100%);
            --shadow: 0 10px 40px rgba(194, 24, 91, 0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'El Messiri', sans-serif; background: var(--bg-cream); min-height: 100vh; }
        
        .hero-banner {
            background: linear-gradient(135deg, #880e4f 0%, #ad1457 50%, #c2185b 100%);
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
            background: linear-gradient(135deg, #fff 0%, #fce4ec 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.8rem;
        }
        .hero-text p { font-size: 1.1rem; color: rgba(255,255,255,0.85); max-width: 480px; line-height: 1.8; }
        .hero-icon { font-size: 5rem; animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%,100%{transform:translateY(0) rotate(-5deg)} 50%{transform:translateY(-15px) rotate(5deg)} }

        .main-container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        
        .steps-progress {
            display: flex; justify-content: center; gap: 0; margin-bottom: 2rem;
            background: #fff; padding: 1.2rem 1.5rem; border-radius: 16px; box-shadow: var(--shadow);
        }
        .step {
            display: flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.5rem;
            color: var(--text-muted); font-weight: 500; cursor: pointer; transition: all 0.3s; border-radius: 10px;
        }
        .step.active { color: var(--primary); background: var(--light); }
        .step.completed { color: #2e7d32; }
        .step-number {
            width: 32px; height: 32px; border-radius: 50%; background: #fce4ec;
            display: flex; align-items: center; justify-content: center; font-weight: 700;
        }
        .step.active .step-number { background: var(--pink-gradient); color: #fff; }
        .step.completed .step-number { background: #43a047; color: #fff; }

        .builder-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start; }
        
        .options-panel { background: #fff; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow); }
        .panel-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid var(--bg-warm); }
        .panel-icon { width: 45px; height: 45px; background: var(--pink-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; }
        .panel-title { font-family: 'El Messiri', sans-serif; font-size: 1.4rem; color: var(--text-dark); }
        .panel-subtitle { font-size: 0.85rem; color: var(--text-muted); }

        .section-label { font-family: 'El Messiri', sans-serif; font-size: 1.1rem; color: var(--text-dark); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .section-label i { color: var(--accent); }

        .options-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
        
        .option-card {
            background: var(--bg-warm); border: 2px solid transparent; border-radius: 16px;
            padding: 0; cursor: pointer; transition: all 0.3s; text-align: center; position: relative;
            overflow: hidden;
        }
        button.option-card { font-family:  'El Messiri', sans-serif; width: 100%; }
        .option-card.selected { border-color: var(--accent); background: linear-gradient(135deg, #fff5f7, #fce4ec); }
        .option-card.selected::after {
            content: '✓'; position: absolute; top: 8px; left: 8px; width: 24px; height: 24px;
            background: var(--pink-gradient); border-radius: 50%; color: #fff; display: flex;
            align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold; z-index: 10;
        }
        .option-visual { width: 100%; height: 160px; margin-bottom: 0.8rem; background: #fff; overflow: hidden; border-bottom: 1px solid #fce4ec; }
        .option-visual img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .option-name { font-weight: 600; color: var(--text-dark); font-size: 0.85rem; margin-bottom: 0.2rem; padding: 0 0.5rem; }
        .option-price { color: var(--primary); font-weight: 700; font-size: 0.8rem; padding: 0 0.5rem; }
        .option-meta { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.1rem; padding: 0 0.5rem 0.8rem; }

        .option-card .tooltip {
            position: absolute; bottom: calc(100% + 12px); left: 50%; transform: translateX(-50%) scale(0.9);
            background: #fff; border-radius: 14px; padding: 0.8rem; box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            opacity: 0; visibility: hidden; transition: all 0.3s; z-index: 1000; width: 180px; pointer-events: none;
        }
        .tooltip-img { width: 100%; height: 110px; border-radius: 10px; overflow: hidden; margin-bottom: 0.5rem; background: #f5f5f5; }
        .tooltip-img img { width: 100%; height: 100%; object-fit: cover; }
        .tooltip-name { font-weight: 600; color: var(--text-dark); font-size: 0.9rem; text-align: center; }
        .tooltip-price { color: var(--primary); font-weight: 700; font-size: 0.85rem; text-align: center; }
        .tooltip::after { content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); border: 10px solid transparent; border-top-color: #fff; }
    </style>

    <style>
        .preview-panel { position: sticky; top: 2rem; }
        .preview-card, .summary-card { background: #fff; border-radius: 18px; padding: 1.5rem; box-shadow: var(--shadow); margin-bottom: 1rem; }
        .preview-header, .summary-header { font-family: 'El Messiri', sans-serif; font-size: 1.1rem; color: var(--text-dark); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .preview-header i, .summary-header i { color: var(--accent); }
        .preview-content { min-height: 200px; background: linear-gradient(135deg, #fce4ec, #f8bbd9); border-radius: 14px; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .preview-empty { text-align: center; color: var(--text-muted); }
        .empty-icon { width: 50px; height: 50px; margin-bottom: 0.5rem; }
        .empty-icon img { width: 100%; height: 100%; object-fit: contain; }
        .preview-bouquet { text-align: center; width: 100%; }
        .bouquet-flowers { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.2rem; max-width: 180px; margin: 0 auto; }
        .bouquet-flower-img { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; }
        .bouquet-wrap-img { width: 80px; height: 80px; object-fit: contain; margin-top: -0.5rem; }
        .preview-label { font-weight: 600; color: var(--text-dark); margin-top: 0.5rem; font-size: 0.9rem; }

        .summary-items { max-height: 180px; overflow-y: auto; margin-bottom: 1rem; }
        .summary-item { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #fce4ec; font-size: 0.9rem; }
        .summary-item:last-child { border: none; }
        .summary-item-name { color: #666; }
        .summary-item-price { font-weight: 600; color: var(--text-dark); }
        .summary-empty { color: var(--text-muted); text-align: center; padding: 1rem; }
        .summary-total { display: flex; justify-content: space-between; padding: 1rem 0; border-top: 2px solid #fce4ec; font-family: 'El Messiri', sans-serif; font-size: 1.2rem; font-weight: 700; color: var(--text-dark); }
        #totalPrice { color: var(--primary); }

        .cart-btn {
            width: 100%; padding: 1rem; background: var(--pink-gradient); color: #fff; border: none;
            border-radius: 12px; font-family: 'El Messiri', sans-serif; font-size: 1.05rem; font-weight: 700;
            cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .cart-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(233,30,99,0.4); }
        .cart-btn:disabled { background: #ddd; cursor: not-allowed; }

        .nav-buttons { display: flex; gap: 1rem; }
        .nav-btn { flex: 1; padding: 0.9rem; border: none; border-radius: 10px; font-family:  'El Messiri', sans-serif; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .nav-btn.prev { background: #f5f5f5; color: #666; }
        .nav-btn.prev:hover { background: #eee; }
        .nav-btn.next { background: linear-gradient(135deg, #880e4f, #ad1457); color: #fff; }
        .nav-btn.next:hover { transform: translateY(-2px); }

        .message-section { margin-top: 1.5rem; }
        .elegant-input, .elegant-textarea { width: 100%; padding: 1rem; border: 2px solid #fce4ec; border-radius: 12px; font-family:  'El Messiri', sans-serif; font-size: 1rem; transition: all 0.3s; margin-bottom: 0.5rem; background: var(--bg-warm); }
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
            .options-grid { grid-template-columns: repeat(4, 1fr); gap: 0.5rem; } 
            .option-card { padding: 0; border-radius: 12px; }
            .option-visual { width: 100%; height: 80px; margin-bottom: 0.4rem; border-radius: 0; }
            .option-name { font-size: 0.6rem; padding: 0 0.2rem; }
            .option-price { font-size: 0.55rem; padding: 0 0.2rem; }
            .option-meta { font-size: 0.5rem; padding: 0 0.2rem 0.4rem; }
            .option-card.selected::after { width: 16px; height: 16px; font-size: 0.6rem; top: 4px; left: 4px; }
        }
    </style>

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-text">
                <h1> صمم باقتك المثالية</h1>
                <p>اختر من أجمل الزهور الطازجة ونسق باقتك بالألوان والتغليف الذي تفضله</p>
            </div>
            
        </div>
    </div>

    <div class="main-container">
        <div class="steps-progress">
            <div class="step active" data-step="1" onclick="goToStep(1)"><span class="step-number">1</span><span>الزهور</span></div>
            <div class="step" data-step="2" onclick="goToStep(2)"><span class="step-number">2</span><span>الحجم</span></div>
            <div class="step" data-step="3" onclick="goToStep(3)"><span class="step-number">3</span><span>التغليف</span></div>
            <div class="step" data-step="4" onclick="goToStep(4)"><span class="step-number">4</span><span>البطاقة</span></div>
        </div>

        <div class="builder-layout">
            <div class="options-panel">
                <div class="step-content" id="step1">
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-seedling"></i></div>
                        <div><h2 class="panel-title">اختر أنواع الزهور</h2><p class="panel-subtitle">زهور طازجة يومياً</p></div>
                    </div>
                    <div class="options-grid" id="flowersGrid"></div>
                </div>
                <div class="step-content" id="step2" style="display:none;">
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-expand-arrows-alt"></i></div>
                        <div><h2 class="panel-title">اختر حجم الباقة</h2><p class="panel-subtitle">أحجام متنوعة لكل مناسبة</p></div>
                    </div>
                    <div class="options-grid" id="sizesGrid"></div>
                </div>
                <div class="step-content" id="step3" style="display:none;">
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-scroll"></i></div>
                        <div><h2 class="panel-title">التغليف والإضافات</h2><p class="panel-subtitle">لمسات نهائية مميزة</p></div>
                    </div>
                    <h3 class="section-label"><i class="fas fa-gift"></i> نوع التغليف</h3>
                    <div class="options-grid" id="wrapsGrid"></div>
                    <h3 class="section-label" style="margin-top:1.5rem;"><i class="fas fa-plus-circle"></i> إضافات اختيارية</h3>
                    <div class="options-grid" id="extrasGrid"></div>
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
                    <div class="preview-header"><i class="fas fa-eye"></i><span>معاينة الباقة</span></div>
                    <div class="preview-content" id="bouquetPreview"><div class="preview-empty"><div class="empty-icon">🌸</div><p>اختر الزهور للبدء</p></div></div>
                </div>
                <div class="summary-card">
                    <div class="summary-header"><i class="fas fa-receipt"></i><span>ملخص الطلب</span></div>
                    <div class="summary-items" id="summaryItems"><div class="summary-empty">لم تختر أي عناصر بعد</div></div>
                    <div class="summary-total"><span>الإجمالي</span><span id="totalPrice">0ل.س</span></div>
                    <button class="cart-btn" id="addToCartBtn" disabled onclick="addBouquetToCart()"><i class="fas fa-shopping-cart"></i> أضف للسلة</button>
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
        let state = { flowers: [], size: null, wrap: null, extras: [], card: null, message: '', recipientName: '' };

        let flowers = [];
        let sizes = [];
        let wraps = [];
        let extras = [];
        let cards = [];

        function resolveMediaUrl(path) {
            const p = String(path || '').trim();
            if (!p) return '/images/tulip_gift.jpg';
            if (p.startsWith('http://') || p.startsWith('https://')) return p;
            if (p.startsWith('/')) return `${window.location.origin}${p}`;
            const cleaned = p.replace(/^storage\//, '');
            return `${window.location.origin}/storage/${cleaned}`;
        }

        document.addEventListener('DOMContentLoaded', async () => {
            await loadOptionsFromApi();
            loadFlowers(); loadSizes(); loadWraps(); loadExtras(); loadCards(); setupMessage();
        });

        async function loadOptionsFromApi() {
            try {
                const res = await fetch('/api/custom-gift/options', { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                const fillers = Array.isArray(data.fillers) ? data.fillers : [];
                const wrps = Array.isArray(data.wrappings) ? data.wrappings : [];
                const crds = Array.isArray(data.cards) ? data.cards : [];
                const boxes = Array.isArray(data.boxes) ? data.boxes : [];

                flowers = fillers
                    .filter(f => (f.category || '').toLowerCase() === 'flower')
                    .map(f => ({
                        id: f.id,
                        name: f.name,
                        price: Number(f.price || 0),
                        image: f.image || '/images/tulip_gift.jpg'
                    }));

                wraps = wrps.map(w => ({
                    id: w.id,
                    name: w.name,
                    price: Number(w.price || 0),
                    image: w.image || '/images/tulip_gift.jpg'
                }));

                extras = fillers
                    .filter(f => ['chocolate','candy','perfume','accessory','other'].includes((f.category || '').toLowerCase()))
                    .map(f => ({
                        id: f.id,
                        name: f.name,
                        price: Number(f.price || 0),
                        image: f.image || '/images/tulip_gift.jpg'
                    }));

                cards = [
                    {id: 1001, name: 'بطاقة عيد ميلاد', price: 0, image: '/images/birthday_card.jpeg'},
                    {id: 1002, name: 'بطاقة تهنئة', price: 0, image: '/images/f_card.png'}
                ];

                const sizeDesc = (size) => {
                    switch ((size || '').toLowerCase()) {
                        case 'small': return '5-10 ورود';
                        case 'medium': return '10-20 وردة';
                        case 'large': return '20-35 وردة';
                        case 'xl': return '35-50 وردة';
                        default: return 'مقاس باقة';
                    }
                };
                const sizeEmoji = (size) => {
                    switch ((size || '').toLowerCase()) {
                        case 'small': return '💐';
                        case 'medium': return '🌺';
                        case 'large': return '🌸';
                        case 'xl': return '👑';
                        default: return '💐';
                    }
                };
                sizes = boxes.map(b => ({
                    id: b.id,
                    name: b.name || (b.size ? ('باقة ' + b.size) : 'حجم باقة'),
                    emoji: sizeEmoji(b.size),
                    price: Number(b.price || 0),
                    image: b.image || '/images/tulip_gift.jpg',
                    desc: sizeDesc(b.size)
                }));
            } catch (e) {
                console.error('Failed to load bouquet options from API', e);
            }
        }

        function loadFlowers() {
            document.getElementById('flowersGrid').innerHTML = flowers.map(f => `
                <button type="button" class="option-card ${state.flowers.includes(f.id) ? 'selected' : ''}" onclick="toggleFlower(${f.id})" aria-label="أضف ${f.name}">
                    <div class="tooltip">
                        <div class="tooltip-img"><img src="${resolveMediaUrl(f.image)}" alt="${f.name}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'"></div>
                        <div class="tooltip-name">${f.name}</div>
                        <div class="tooltip-price">${f.price} ل.س / وردة</div>
                    </div>
                    <div class="option-visual"><img src="${resolveMediaUrl(f.image)}" alt="${f.name}" loading="lazy" width="80" height="80" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${f.name}</div>
                    <div class="option-price">${f.price} ل.س</div>
                </button>
            `).join('');
        }

        function loadSizes() {
            document.getElementById('sizesGrid').innerHTML = sizes.map(s => `
                <button type="button" class="option-card ${state.size?.id === s.id ? 'selected' : ''}" onclick="selectSize(${s.id})" aria-label="اختر ${s.name}">
                    <div class="option-visual"><img src="${resolveMediaUrl(s.image)}" alt="${s.name}" loading="lazy" width="80" height="80" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${s.name}</div>
                    <div class="option-price">${s.price} ل.س</div>
                    <div class="option-meta">${s.desc}</div>
                </button>
            `).join('');
        }

        function loadWraps() {
            document.getElementById('wrapsGrid').innerHTML = wraps.map(w => `
                <button type="button" class="option-card ${state.wrap?.id === w.id ? 'selected' : ''}" onclick="selectWrap(${w.id})" aria-label="اختر ${w.name}">
                    <div class="tooltip">
                        <div class="tooltip-img"><img src="${resolveMediaUrl(w.image)}" alt="${w.name}" loading="lazy" onerror="this.src='/images/tulip_gift.jpg'"></div>
                        <div class="tooltip-name">${w.name}</div>
                        <div class="tooltip-price">${w.price > 0 ? w.price + ' ل.س' : 'مجاني'}</div>
                    </div>
                    <div class="option-visual"><img src="${resolveMediaUrl(w.image)}" alt="${w.name}" loading="lazy" width="80" height="80" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${w.name}</div>
                    <div class="option-price">${w.price > 0 ? w.price + 'ل.س' : 'مجاني'}</div>
                </button>
            `).join('');
        }

        function loadExtras() {
            document.getElementById('extrasGrid').innerHTML = extras.map(e => `
                <button type="button" class="option-card ${state.extras.includes(e.id) ? 'selected' : ''}" onclick="toggleExtra(${e.id})" aria-label="أضف ${e.name}">
                    <div class="option-visual"><img src="${resolveMediaUrl(e.image)}" alt="${e.name}" loading="lazy" width="80" height="80" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${e.name}</div>
                    <div class="option-price">${e.price} ل.س</div>
                </button>
            `).join('');
        }

        function loadCards() {
            document.getElementById('cardsGrid').innerHTML = cards.map(c => `
                <button type="button" class="option-card ${state.card?.id === c.id ? 'selected' : ''}" onclick="selectCard(${c.id})" aria-label="اختر ${c.name}">
                    <div class="option-visual"><img src="${resolveMediaUrl(c.image)}" alt="${c.name}" loading="lazy" width="80" height="80" onerror="this.src='/images/tulip_gift.jpg'"></div>
                    <div class="option-name">${c.name}</div>
                    <div class="option-price">${c.price > 0 ? c.price + ' ل.س' : 'مجاني'}</div>
                </button>
            `).join('');
        }

        function setupMessage() {
            const cardMsg = document.getElementById('cardMessage');
            const recipName = document.getElementById('recipientName');
            const charCount = document.getElementById('charCount');

            cardMsg.addEventListener('input', e => {
                state.message = e.target.value;
                charCount.textContent = e.target.value.length;
            });

            recipName.addEventListener('input', e => {
                state.recipientName = e.target.value;
            });

            // Sync initial state in case of browser autofill
            state.message = cardMsg.value;
            state.recipientName = recipName.value;
            charCount.textContent = cardMsg.value.length;
        }

        function toggleFlower(id) {
            const idx = state.flowers.indexOf(id);
            if (idx >= 0) state.flowers.splice(idx, 1);
            else state.flowers.push(id);
            loadFlowers(); updatePreview(); updateSummary();
        }

        function selectSize(id) { state.size = sizes.find(s => s.id === id); loadSizes(); updatePreview(); updateSummary(); }
        function selectWrap(id) { state.wrap = wraps.find(w => w.id === id); loadWraps(); updatePreview(); updateSummary(); }
        function toggleExtra(id) { const idx = state.extras.indexOf(id); if (idx >= 0) state.extras.splice(idx, 1); else state.extras.push(id); loadExtras(); updateSummary(); }
        function selectCard(id) { state.card = cards.find(c => c.id === id); loadCards(); updateSummary(); }

        function updatePreview() {
            const preview = document.getElementById('bouquetPreview');
            if (state.flowers.length === 0) { 
                preview.innerHTML = `<div class="preview-empty"><div class="empty-icon"><img src="/images/tulip_gift.jpg" alt="flower"></div><p>اختر الزهور للبدء</p></div>`; 
                return; 
            }
            
            const selectedFlowers = state.flowers.map(id => flowers.find(f => f.id === id)).filter(Boolean);
            const wrapImg = state.wrap ? `<img src="${resolveMediaUrl(state.wrap.image)}" class="bouquet-wrap-img" alt="wrap">` : '<div style="height:80px"></div>';
            
            preview.innerHTML = `
                <div class="preview-bouquet">
                    <div class="bouquet-flowers">
                        ${selectedFlowers.slice(0, 12).map(f => `<img src="${resolveMediaUrl(f.image)}" class="bouquet-flower-img" title="${f.name}" alt="${f.name}">`).join('')}
                    </div>
                    <div class="bouquet-wrap">
                        ${wrapImg}
                    </div>
                    <div class="preview-label">${state.flowers.length} نوع زهور${state.size ? ' - ' + state.size.name : ''}</div>
                </div>
            `;
        }

        function updateSummary() {
            const summaryEl = document.getElementById('summaryItems');
            const totalEl = document.getElementById('totalPrice');
            const btn = document.getElementById('addToCartBtn');
            let items = [], total = 0;
            
            state.flowers.forEach(id => { 
                const f = flowers.find(x => x.id === id); 
                if (f) { items.push({ name: f.name, price: f.price, image: f.image }); total += f.price; } 
            });
            
            if (state.size) { items.push({ name: state.size.name, price: state.size.price, image: state.size.image }); total += state.size.price; }
            if (state.wrap?.price > 0) { items.push({ name: state.wrap.name, price: state.wrap.price, image: state.wrap.image }); total += state.wrap.price; }
            
            state.extras.forEach(id => { 
                const e = extras.find(x => x.id === id); 
                if (e) { items.push({ name: e.name, price: e.price, image: e.image }); total += e.price; } 
            });
            
            if (state.card?.price > 0) { items.push({ name: state.card.name, price: state.card.price, image: state.card.image }); total += state.card.price; }
            
            summaryEl.innerHTML = items.length ? items.map(i => `
                <div class="summary-item">
                    <div style="display:flex; align-items:center; gap:0.8rem;">
                        <img src="${resolveMediaUrl(i.image)}" style="width:35px; height:35px; object-fit:cover; border-radius:6px; background:#fce4ec;" onerror="this.src='/images/tulip_gift.jpg'">
                        <span class="summary-item-name">${i.name}</span>
                    </div>
                    <span class="summary-item-price">${i.price} ل.س</span>
                </div>
            `).join('') : '<div class="summary-empty">لم تختر أي عناصر بعد</div>';
            
            totalEl.textContent = total + ' ل.س';
            btn.disabled = state.flowers.length === 0;
        }

        function goToStep(step) {
            if (step < 1 || step > 4) return;
            if (step > 1 && state.flowers.length === 0) { alert('اختر الزهور أولاً'); return; }
            currentStep = step;
            document.querySelectorAll('.step').forEach((s, i) => { s.classList.remove('active', 'completed'); if (i + 1 < step) s.classList.add('completed'); if (i + 1 === step) s.classList.add('active'); });
            document.querySelectorAll('.step-content').forEach((c, i) => { c.style.display = i + 1 === step ? 'block' : 'none'; });
            document.getElementById('prevBtn').style.display = step > 1 ? 'flex' : 'none';
            document.getElementById('nextBtn').style.display = step < 4 ? 'flex' : 'none';
        }
        function nextStep() { if (currentStep === 1 && state.flowers.length === 0) { alert('اختر الزهور أولاً'); return; } goToStep(currentStep + 1); }
        function prevStep() { goToStep(currentStep - 1); }

        async function addBouquetToCart() {
            if (state.flowers.length === 0) return;
            const btn = document.getElementById('addToCartBtn');
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            try {
                const res = await fetch('/api/custom-bouquet/add-to-cart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                    body: JSON.stringify({ flowers: state.flowers.map(id => ({ id, qty: 1 })), size_id: state.size?.id, wrap_id: state.wrap?.id, extras: state.extras, card_id: state.card?.id, message: state.message, recipient_name: state.recipientName })
                });
                const data = await res.json();
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة!'; btn.style.background = '#43a047';
                    window.dispatchEvent(new Event('cart-updated'));
                    setTimeout(() => { if (confirm('تمت إضافة الباقة! هل تريد الذهاب للسلة؟')) window.location.href = '/cart'; else resetBuilder(); }, 500);
                } else throw new Error();
            } catch (e) { alert('حدث خطأ'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-shopping-cart"></i> أضف للسلة'; }
        }

        function resetBuilder() {
            state = { flowers: [], size: null, wrap: null, extras: [], card: null, message: '', recipientName: '' };
            currentStep = 1; goToStep(1); loadFlowers(); loadSizes(); loadWraps(); loadExtras(); loadCards(); updatePreview(); updateSummary();
            document.getElementById('cardMessage').value = ''; document.getElementById('recipientName').value = ''; document.getElementById('charCount').textContent = '0';
            document.getElementById('addToCartBtn').style.background = ''; document.getElementById('addToCartBtn').innerHTML = '<i class="fas fa-shopping-cart"></i> أضف للسلة';
        }
    </script>
</body>
</html>
<?php /**PATH E:\Tulip-Store\resources\views/gifts/flower-bouquet.blade.php ENDPATH**/ ?>