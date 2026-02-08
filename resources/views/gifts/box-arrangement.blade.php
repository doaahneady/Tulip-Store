<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تنسيق صندوق هدية - Tulip Gift</title>
    <link rel="stylesheet" href="/css/store.css?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('components.navbar')
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
        body { font-family: 'Tajawal', sans-serif; background: var(--bg-cream); min-height: 100vh; }
        
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

        .filter-tabs { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
        .filter-tab {
            padding: 0.6rem 1.2rem; border: 2px solid #eee; background: #fff; border-radius: 25px;
            cursor: pointer; font-family: inherit; font-size: 0.9rem; transition: all 0.3s;
        }
        .filter-tab:hover, .filter-tab.active { border-color: var(--accent); background: #fdf8e8; color: var(--primary); }

        .options-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
        
        .option-card {
            background: var(--bg-warm); border: 2px solid transparent; border-radius: 16px;
            padding: 1.2rem; cursor: pointer; transition: all 0.3s; text-align: center; position: relative;
        }
        .option-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(139,105,20,0.12); border-color: rgba(212,175,55,0.3); }
        .option-card.selected { border-color: var(--accent); background: linear-gradient(135deg, #fefbf3, #fdf8e8); }
        .option-card.selected::after {
            content: '✓'; position: absolute; top: 8px; left: 8px; width: 24px; height: 24px;
            background: var(--gold-gradient); border-radius: 50%; color: #fff; display: flex;
            align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;
        }
        .option-visual { width: 80px; height: 80px; margin: 0 auto 0.8rem; border-radius: 14px; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; }
        .option-name { font-weight: 600; color: var(--text-dark); font-size: 0.95rem; margin-bottom: 0.3rem; }
        .option-price { color: var(--primary); font-weight: 700; font-size: 0.9rem; }
        .option-meta { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem; }

        .option-card .tooltip {
            position: absolute; bottom: calc(100% + 12px); left: 50%; transform: translateX(-50%) scale(0.9);
            background: #fff; border-radius: 14px; padding: 0.8rem; box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            opacity: 0; visibility: hidden; transition: all 0.3s; z-index: 1000; width: 180px; pointer-events: none;
        }
        .option-card:hover .tooltip { opacity: 1; visibility: visible; transform: translateX(-50%) scale(1); }
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
        .preview-content { min-height: 180px; background: linear-gradient(135deg, #fdf8e8, #fef6dc); border-radius: 14px; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .preview-empty { text-align: center; color: var(--text-muted); }
        .empty-icon { font-size: 3rem; margin-bottom: 0.5rem; }
        .preview-gift { text-align: center; width: 100%; }
        .preview-box-emoji { font-size: 4rem; position: relative; display: inline-block; }
        .preview-ribbon { position: absolute; top: -8px; left: 50%; transform: translateX(-50%); font-size: 1.5rem; }
        .preview-items { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.3rem; margin-top: 0.8rem; }
        .preview-item { font-size: 1.3rem; background: #fff; padding: 0.25rem 0.4rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .preview-label { font-weight: 600; color: var(--text-dark); margin-top: 0.5rem; font-size: 0.9rem; }

        .summary-items { max-height: 180px; overflow-y: auto; margin-bottom: 1rem; }
        .summary-item { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f0ebe0; font-size: 0.9rem; }
        .summary-item:last-child { border: none; }
        .summary-item-name { color: #666; }
        .summary-item-price { font-weight: 600; color: var(--text-dark); }
        .summary-empty { color: var(--text-muted); text-align: center; padding: 1rem; }
        .summary-total { display: flex; justify-content: space-between; padding: 1rem 0; border-top: 2px solid #f0ebe0; font-family: 'El Messiri', sans-serif; font-size: 1.2rem; font-weight: 700; color: var(--text-dark); }
        #totalPrice { color: var(--primary); }

        .cart-btn {
            width: 100%; padding: 1rem; background: var(--gold-gradient); color: #fff; border: none;
            border-radius: 12px; font-family: 'El Messiri', sans-serif; font-size: 1.05rem; font-weight: 700;
            cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .cart-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212,175,55,0.4); }
        .cart-btn:disabled { background: #ddd; cursor: not-allowed; }

        .nav-buttons { display: flex; gap: 1rem; }
        .nav-btn { flex: 1; padding: 0.9rem; border: none; border-radius: 10px; font-family: inherit; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .nav-btn.prev { background: #f5f5f5; color: #666; }
        .nav-btn.prev:hover { background: #eee; }
        .nav-btn.next { background: linear-gradient(135deg, #2c2416, #4a3c28); color: #fff; }
        .nav-btn.next:hover { transform: translateY(-2px); }

        .message-section { margin-top: 1.5rem; }
        .elegant-input, .elegant-textarea { width: 100%; padding: 1rem; border: 2px solid #f0ebe0; border-radius: 12px; font-family: inherit; font-size: 1rem; transition: all 0.3s; margin-bottom: 0.5rem; background: var(--bg-warm); }
        .elegant-input:focus, .elegant-textarea:focus { outline: none; border-color: var(--accent); background: #fff; }
        .elegant-textarea { min-height: 100px; resize: vertical; }
        .char-counter { text-align: left; color: var(--text-muted); font-size: 0.8rem; }

        @media (max-width: 1024px) { .builder-layout { grid-template-columns: 1fr; } .preview-panel { position: static; } .hero-content { flex-direction: column; text-align: center; } }
        @media (max-width: 600px) { .hero-text h1 { font-size: 2rem; } .steps-progress { flex-wrap: wrap; gap: 0.5rem; } .step { padding: 0.5rem 0.8rem; font-size: 0.85rem; } .options-grid { grid-template-columns: repeat(2, 1fr); } }
    </style>

    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-text">
                <h1>✨ صمم هديتك الفاخرة</h1>
                <p>اختر من مجموعتنا الراقية من الصناديق والمحتويات الفاخرة لتصنع هدية مميزة</p>
            </div>
            <div class="hero-icon">🎁</div>
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
                    <div class="options-grid" id="wrappingsGrid"></div>
                    <h3 class="section-label" style="margin-top:1.5rem;"><i class="fas fa-ribbon"></i> الشريط</h3>
                    <div class="options-grid" id="ribbonsGrid"></div>
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
                    <div class="summary-total"><span>الإجمالي</span><span id="totalPrice">0 ر.س</span></div>
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
        let giftState = { box: null, fillers: [], wrapping: null, ribbon: null, card: null, message: '', recipientName: '' };

        const boxes = [
            { id: 1, name: 'صندوق صغير', emoji: '📦', price: 25, maxItems: 3, image: 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=200&h=200&fit=crop' },
            { id: 2, name: 'صندوق متوسط', emoji: '🎁', price: 45, maxItems: 5, image: 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=200&h=200&fit=crop' },
            { id: 3, name: 'صندوق كبير', emoji: '🎀', price: 75, maxItems: 8, image: 'https://images.unsplash.com/photo-1607469256872-48074e807b0b?w=200&h=200&fit=crop' },
            { id: 4, name: 'صندوق فاخر', emoji: '👑', price: 120, maxItems: 12, image: 'https://images.unsplash.com/photo-1512909006721-3d6018887383?w=200&h=200&fit=crop' },
        ];

        const fillers = [
            { id: 1, name: 'شوكولاتة فيريرو', emoji: '🍫', price: 35, cat: 'chocolate', image: 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?w=200&h=200&fit=crop' },
            { id: 2, name: 'شوكولاتة جوديفا', emoji: '🍫', price: 55, cat: 'chocolate', image: 'https://images.unsplash.com/photo-1511381939415-e44015466834?w=200&h=200&fit=crop' },
            { id: 3, name: 'باقة ورد أحمر', emoji: '🌹', price: 45, cat: 'flower', image: 'https://images.unsplash.com/photo-1518882605630-8eb738e98a02?w=200&h=200&fit=crop' },
            { id: 4, name: 'زهور بيضاء', emoji: '🌸', price: 35, cat: 'flower', image: 'https://images.unsplash.com/photo-1487530811176-3780de880c2d?w=200&h=200&fit=crop' },
            { id: 5, name: 'عطر فاخر', emoji: '🌺', price: 150, cat: 'perfume', image: 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=200&h=200&fit=crop' },
            { id: 6, name: 'عطر صغير', emoji: '💐', price: 75, cat: 'perfume', image: 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=200&h=200&fit=crop' },
            { id: 7, name: 'سوار ذهبي', emoji: '💍', price: 85, cat: 'accessory', image: 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=200&h=200&fit=crop' },
            { id: 8, name: 'قلادة فضية', emoji: '📿', price: 65, cat: 'accessory', image: 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=200&h=200&fit=crop' },
            { id: 9, name: 'حلوى ملونة', emoji: '🍬', price: 20, cat: 'candy', image: 'https://images.unsplash.com/photo-1582058091505-f87a2e55a40f?w=200&h=200&fit=crop' },
            { id: 10, name: 'دبدوب صغير', emoji: '🧸', price: 40, cat: 'toy', image: 'https://images.unsplash.com/photo-1558679908-541bcf1249ff?w=200&h=200&fit=crop' },
            { id: 11, name: 'شمعة معطرة', emoji: '🕯️', price: 30, cat: 'other', image: 'https://images.unsplash.com/photo-1602607434774-8f0e0eb0e0b0?w=200&h=200&fit=crop' },
        ];

        const wrappings = [
            { id: 1, name: 'تغليف كلاسيكي', emoji: '🎁', price: 0, image: 'https://images.unsplash.com/photo-1512909006721-3d6018887383?w=200&h=200&fit=crop' },
            { id: 2, name: 'تغليف ذهبي', emoji: '✨', price: 15, image: 'https://images.unsplash.com/photo-1607469256872-48074e807b0b?w=200&h=200&fit=crop' },
            { id: 3, name: 'تغليف وردي', emoji: '💝', price: 10, image: 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=200&h=200&fit=crop' },
            { id: 4, name: 'تغليف أزرق', emoji: '💙', price: 10, image: 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=200&h=200&fit=crop' },
        ];

        const ribbons = [
            { id: 1, name: 'شريط ذهبي', emoji: '🎀', price: 5 },
            { id: 2, name: 'شريط أحمر', emoji: '❤️', price: 5 },
            { id: 3, name: 'شريط وردي', emoji: '💗', price: 5 },
            { id: 4, name: 'شريط أبيض', emoji: '🤍', price: 5 },
            { id: 5, name: 'بدون شريط', emoji: '➖', price: 0 },
        ];

        const cards = [
            { id: 1, name: 'بطاقة عيد ميلاد', emoji: '🎂', price: 5 },
            { id: 2, name: 'بطاقة حب', emoji: '💕', price: 5 },
            { id: 3, name: 'بطاقة تهنئة', emoji: '🎉', price: 5 },
            { id: 4, name: 'بطاقة شكر', emoji: '🙏', price: 5 },
            { id: 5, name: 'بدون بطاقة', emoji: '➖', price: 0 },
        ];

        const categories = [
            { id: 'all', name: 'الكل', emoji: '📦' },
            { id: 'chocolate', name: 'شوكولاتة', emoji: '🍫' },
            { id: 'flower', name: 'زهور', emoji: '🌸' },
            { id: 'perfume', name: 'عطور', emoji: '🌺' },
            { id: 'accessory', name: 'إكسسوارات', emoji: '💍' },
            { id: 'candy', name: 'حلويات', emoji: '🍬' },
        ];

        document.addEventListener('DOMContentLoaded', () => { loadBoxes(); loadCategories(); loadFillers(); loadWrappings(); loadRibbons(); loadCards(); setupMessage(); });

        function loadBoxes() {
            document.getElementById('boxesGrid').innerHTML = boxes.map(b => `
                <div class="option-card ${giftState.box?.id === b.id ? 'selected' : ''}" onclick="selectBox(${b.id})">
                    <div class="tooltip">
                        <div class="tooltip-img"><img src="${b.image}" alt="${b.name}"></div>
                        <div class="tooltip-name">${b.name}</div>
                        <div class="tooltip-price">${b.price} ر.س</div>
                    </div>
                    <div class="option-visual">${b.emoji}</div>
                    <div class="option-name">${b.name}</div>
                    <div class="option-price">${b.price} ر.س</div>
                    <div class="option-meta">حتى ${b.maxItems} عناصر</div>
                </div>
            `).join('');
        }

        function loadCategories() {
            document.getElementById('categoryTabs').innerHTML = categories.map(c => `
                <button class="filter-tab ${c.id === 'all' ? 'active' : ''}" data-cat="${c.id}" onclick="filterCategory('${c.id}')">${c.emoji} ${c.name}</button>
            `).join('');
        }

        function loadFillers(cat = 'all') {
            const filtered = cat === 'all' ? fillers : fillers.filter(f => f.cat === cat);
            document.getElementById('fillersGrid').innerHTML = filtered.map(f => `
                <div class="option-card ${giftState.fillers.includes(f.id) ? 'selected' : ''}" onclick="toggleFiller(${f.id})">
                    <div class="tooltip">
                        <div class="tooltip-img"><img src="${f.image}" alt="${f.name}"></div>
                        <div class="tooltip-name">${f.name}</div>
                        <div class="tooltip-price">${f.price} ر.س</div>
                    </div>
                    <div class="option-visual">${f.emoji}</div>
                    <div class="option-name">${f.name}</div>
                    <div class="option-price">${f.price} ر.س</div>
                </div>
            `).join('');
        }

        function loadWrappings() {
            document.getElementById('wrappingsGrid').innerHTML = wrappings.map(w => `
                <div class="option-card ${giftState.wrapping?.id === w.id ? 'selected' : ''}" onclick="selectWrapping(${w.id})">
                    <div class="tooltip">
                        <div class="tooltip-img"><img src="${w.image}" alt="${w.name}"></div>
                        <div class="tooltip-name">${w.name}</div>
                        <div class="tooltip-price">${w.price > 0 ? w.price + ' ر.س' : 'مجاني'}</div>
                    </div>
                    <div class="option-visual">${w.emoji}</div>
                    <div class="option-name">${w.name}</div>
                    <div class="option-price">${w.price > 0 ? w.price + ' ر.س' : 'مجاني'}</div>
                </div>
            `).join('');
        }

        function loadRibbons() {
            document.getElementById('ribbonsGrid').innerHTML = ribbons.map(r => `
                <div class="option-card ${giftState.ribbon?.id === r.id ? 'selected' : ''}" onclick="selectRibbon(${r.id})">
                    <div class="option-visual">${r.emoji}</div>
                    <div class="option-name">${r.name}</div>
                    <div class="option-price">${r.price > 0 ? r.price + ' ر.س' : 'مجاني'}</div>
                </div>
            `).join('');
        }

        function loadCards() {
            document.getElementById('cardsGrid').innerHTML = cards.map(c => `
                <div class="option-card ${giftState.card?.id === c.id ? 'selected' : ''}" onclick="selectCard(${c.id})">
                    <div class="option-visual">${c.emoji}</div>
                    <div class="option-name">${c.name}</div>
                    <div class="option-price">${c.price > 0 ? c.price + ' ر.س' : 'مجاني'}</div>
                </div>
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
            else if (giftState.fillers.length < giftState.box.maxItems) { giftState.fillers.push(id); }
            else { alert(`الصندوق يتسع لـ ${giftState.box.maxItems} عناصر فقط`); return; }
            loadFillers(document.querySelector('.filter-tab.active')?.dataset.cat || 'all'); updatePreview(); updateSummary();
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
            const ribbonEmoji = giftState.ribbon && giftState.ribbon.id !== 5 ? giftState.ribbon.emoji : '';
            const fillerEmojis = giftState.fillers.map(id => fillers.find(f => f.id === id)?.emoji).filter(Boolean);
            preview.innerHTML = `
                <div class="preview-gift">
                    <div class="preview-box-emoji">${ribbonEmoji ? `<span class="preview-ribbon">${ribbonEmoji}</span>` : ''}${giftState.box.emoji}</div>
                    <div class="preview-label">${giftState.box.name}</div>
                    ${fillerEmojis.length > 0 ? `<div class="preview-items">${fillerEmojis.map(e => `<span class="preview-item">${e}</span>`).join('')}</div>` : '<p style="color:var(--text-muted);font-size:0.85rem;margin-top:0.5rem;">أضف محتويات للهدية</p>'}
                </div>
            `;
        }

        function updateSummary() {
            const summaryEl = document.getElementById('summaryItems');
            const totalEl = document.getElementById('totalPrice');
            const btn = document.getElementById('addToCartBtn');
            let items = [], total = 0;
            if (giftState.box) { items.push({ name: giftState.box.name, price: giftState.box.price }); total += giftState.box.price; }
            giftState.fillers.forEach(id => { const f = fillers.find(x => x.id === id); if (f) { items.push({ name: f.name, price: f.price }); total += f.price; } });
            if (giftState.wrapping?.price > 0) { items.push({ name: giftState.wrapping.name, price: giftState.wrapping.price }); total += giftState.wrapping.price; }
            if (giftState.ribbon?.price > 0) { items.push({ name: giftState.ribbon.name, price: giftState.ribbon.price }); total += giftState.ribbon.price; }
            if (giftState.card?.price > 0) { items.push({ name: giftState.card.name, price: giftState.card.price }); total += giftState.card.price; }
            summaryEl.innerHTML = items.length ? items.map(i => `<div class="summary-item"><span class="summary-item-name">${i.name}</span><span class="summary-item-price">${i.price} ر.س</span></div>`).join('') : '<div class="summary-empty">لم تختر أي عناصر بعد</div>';
            totalEl.textContent = total + ' ر.س';
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
                    body: JSON.stringify({ box_id: giftState.box.id, fillers: giftState.fillers.map(id => ({ id, qty: 1 })), wrapping_id: giftState.wrapping?.id, ribbon_id: giftState.ribbon?.id, card_id: giftState.card?.id, message: giftState.message, recipient_name: giftState.recipientName })
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
            giftState = { box: null, fillers: [], wrapping: null, ribbon: null, card: null, message: '', recipientName: '' };
            currentStep = 1; goToStep(1); loadBoxes(); loadFillers(); loadWrappings(); loadRibbons(); loadCards(); updatePreview(); updateSummary();
            document.getElementById('cardMessage').value = ''; document.getElementById('recipientName').value = ''; document.getElementById('charCount').textContent = '0';
            document.getElementById('addToCartBtn').style.background = ''; document.getElementById('addToCartBtn').innerHTML = '<i class="fas fa-shopping-cart"></i> أضف للسلة';
        }
    </script>
</body>
</html>
