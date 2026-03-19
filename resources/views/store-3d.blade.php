<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>صمم هديتك - Tulip Gift</title>
    <!-- fav icon -->
     <link rel="icon" type="image/png" href="/images/fav_icon.png">
    <link rel="stylesheet" href="/css/store.css?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "El Messiri", sans-serif; background: #faf8f5; min-height: 100vh; }
        
        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #8b5a3c 0%, #c9956c 50%, #daa87e 100%);
            position: relative;
            overflow: hidden;
            padding: 3rem 2rem;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }
        .hero-text h1 {
            font-family: 'El Messiri', sans-serif;
            font-size: 2.8rem;
            color: #fff;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .hero-text p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            max-width: 500px;
            line-height: 1.8;
        }
        .hero-gift {
            font-size: 8rem;
            filter: drop-shadow(4px 4px 8px rgba(0,0,0,0.3));
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Steps Progress */
        .steps-progress {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-bottom: 2rem;
            background: #fff;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            color: #999;
            font-weight: 500;
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
        }
        .step::after {
            content: '';
            position: absolute;
            left: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 2px;
            background: #ddd;
        }
        .step:first-child::after { display: none; }
        .step.active {
            color: #c9956c;
            background: linear-gradient(135deg, #fef6f0 0%, #fff5eb 100%);
            border-radius: 12px;
        }
        .step.completed {
            color: #27ae60;
        }
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .step.active .step-number {
            background: linear-gradient(135deg, #c9956c 0%, #a67c52 100%);
            color: #fff;
        }
        .step.completed .step-number {
            background: #27ae60;
            color: #fff;
        }

        /* Gift Builder Layout */
        .builder-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
            align-items: start;
        }

        /* Options Panel */
        .options-panel {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        }
        .panel-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.5rem;
            color: #4a3f35;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .panel-title i { color: #c9956c; }

        /* Option Cards Grid */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 1rem;
        }
        .option-card {
            background: #faf8f5;
            border: 3px solid transparent;
            border-radius: 16px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .option-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(201,149,108,0.2);
        }
        .option-card.selected {
            border-color: #c9956c;
            background: linear-gradient(135deg, #fef6f0 0%, #fff5eb 100%);
        }
        .option-image {
            width: 80px;
            height: 80px;
            margin: 0 auto 0.8rem;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }
        .option-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .option-name {
            font-weight: 600;
            color: #4a3f35;
            font-size: 0.95rem;
            margin-bottom: 0.3rem;
        }
        .option-price {
            color: #c9956c;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .option-check {
            position: absolute;
            top: 8px;
            left: 8px;
            width: 24px;
            height: 24px;
            background: #c9956c;
            border-radius: 50%;
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }
        .option-card.selected .option-check { display: flex; }
        .option-card { position: relative; }
</style>
</head>
<body>
    @include('components.navbar')

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-text">
                <h1>🎁 صمم هديتك المثالية</h1>
                <p>اختر الصندوق، أضف الحشوات، اختر التغليف والشريط، واكتب رسالتك الخاصة. نحن نجمعها لك بكل حب!</p>
            </div>
            <div class="hero-gift">🎀</div>
        </div>
    </div>

    <div class="main-container">
        <!-- Steps Progress -->
        <div class="steps-progress">
            <div class="step active" data-step="1" onclick="goToStep(1)">
                <span class="step-number">1</span>
                <span>اختر الصندوق</span>
            </div>
            <div class="step" data-step="2" onclick="goToStep(2)">
                <span class="step-number">2</span>
                <span>أضف الحشوات</span>
            </div>
            <div class="step" data-step="3" onclick="goToStep(3)">
                <span class="step-number">3</span>
                <span>التغليف والشريط</span>
            </div>
            <div class="step" data-step="4" onclick="goToStep(4)">
                <span class="step-number">4</span>
                <span>البطاقة والرسالة</span>
            </div>
        </div>

        <div class="builder-layout">
            <!-- Options Panel -->
            <div class="options-panel">
                <!-- Step 1: Box Selection -->
                <div class="step-content" id="step1">
                    <h2 class="panel-title"><i class="fas fa-box-open"></i> اختر صندوق الهدية</h2>
                    <div class="options-grid" id="boxesGrid">
                        <!-- Boxes loaded dynamically -->
                    </div>
                </div>

                <!-- Step 2: Fillers -->
                <div class="step-content" id="step2" style="display:none;">
                    <h2 class="panel-title"><i class="fas fa-gifts"></i> أضف محتويات الهدية</h2>
                    <div class="filler-categories">
                        <button class="cat-btn active" data-cat="all">الكل</button>
                        <button class="cat-btn" data-cat="chocolate">🍫 شوكولاتة</button>
                        <button class="cat-btn" data-cat="flower">🌸 زهور</button>
                        <button class="cat-btn" data-cat="perfume">🌺 عطور</button>
                        <button class="cat-btn" data-cat="accessory">💍 إكسسوارات</button>
                        <button class="cat-btn" data-cat="candy">🍬 حلويات</button>
                    </div>
                    <div class="options-grid fillers-grid" id="fillersGrid">
                        <!-- Fillers loaded dynamically -->
                    </div>
                </div>

                <!-- Step 3: Wrapping & Ribbon -->
                <div class="step-content" id="step3" style="display:none;">
                    <h2 class="panel-title"><i class="fas fa-gift"></i> اختر التغليف</h2>
                    <div class="options-grid" id="wrappingsGrid"></div>
                    
                    <h2 class="panel-title" style="margin-top:2rem;"><i class="fas fa-ribbon"></i> اختر الشريط</h2>
                    <div class="options-grid" id="ribbonsGrid"></div>
                </div>

                <!-- Step 4: Card & Message -->
                <div class="step-content" id="step4" style="display:none;">
                    <h2 class="panel-title"><i class="fas fa-envelope-open-text"></i> اختر البطاقة</h2>
                    <div class="options-grid" id="cardsGrid"></div>
                    
                    <div class="message-section">
                        <h2 class="panel-title" style="margin-top:2rem;"><i class="fas fa-pen-fancy"></i> اكتب رسالتك</h2>
                        <input type="text" id="recipientName" class="message-input" placeholder="اسم المستلم (اختياري)">
                        <textarea id="cardMessage" class="message-textarea" placeholder="اكتب رسالتك هنا... (اختياري)" maxlength="200"></textarea>
                        <div class="char-count"><span id="charCount">0</span>/200</div>
                    </div>
                </div>
            </div>

            <!-- Preview Panel -->
            <div class="preview-panel">
                <div class="preview-box">
                    <h3 class="preview-title"><i class="fas fa-eye"></i> معاينة هديتك</h3>
                    <div class="gift-preview" id="giftPreview">
                        <div class="preview-empty">
                            <i class="fas fa-box-open"></i>
                            <p>اختر صندوق للبدء</p>
                        </div>
                    </div>
                </div>

                <div class="summary-box">
                    <h3 class="summary-title"><i class="fas fa-receipt"></i> ملخص الطلب</h3>
                    <div class="summary-items" id="summaryItems">
                        <div class="summary-empty">لم تختر أي عناصر بعد</div>
                    </div>
                    <div class="summary-total">
                        <span>الإجمالي</span>
                        <span id="totalPrice">ل.س</span>
                    </div>
                    <button class="add-cart-btn" id="addToCartBtn" disabled onclick="addGiftToCart()">
                        <i class="fas fa-shopping-cart"></i>
                        أضف للسلة
                    </button>
                </div>

                <div class="nav-buttons">
                    <button class="nav-btn prev" id="prevBtn" onclick="prevStep()" style="display:none;">
                        <i class="fas fa-arrow-right"></i> السابق
                    </button>
                    <button class="nav-btn next" id="nextBtn" onclick="nextStep()">
                        التالي <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Filler Categories */
        .filler-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .cat-btn {
            padding: 0.6rem 1.2rem;
            border: 2px solid #eee;
            background: #fff;
            border-radius: 25px;
            cursor: pointer;
            font-family: "El Messiri", sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .cat-btn:hover, .cat-btn.active {
            border-color: #c9956c;
            background: linear-gradient(135deg, #fef6f0 0%, #fff5eb 100%);
            color: #8b5a3c;
        }

        /* Filler Card with Quantity */
        .filler-card {
            position: relative;
        }
        .filler-qty {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            background: #c9956c;
            color: #fff;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .qty-btn:hover { background: #a67c52; transform: scale(1.1); }
        .qty-btn:disabled { background: #ddd; cursor: not-allowed; transform: none; }
        .qty-value {
            font-weight: 700;
            min-width: 24px;
            text-align: center;
        }

        /* Preview Panel */
        .preview-panel {
            position: sticky;
            top: 2rem;
        }
        .preview-box, .summary-box {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
        }
        .preview-title, .summary-title {
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem;
            color: #4a3f35;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .preview-title i, .summary-title i { color: #c9956c; }
        
        .gift-preview {
            min-height: 200px;
            background: linear-gradient(135deg, #fef6f0 0%, #fff5eb 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .preview-empty {
            text-align: center;
            color: #999;
        }
        .preview-empty i { font-size: 3rem; margin-bottom: 0.5rem; display: block; }

        .preview-gift {
            text-align: center;
            width: 100%;
        }
        .preview-gift-box {
            font-size: 5rem;
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }
        .preview-ribbon {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 2rem;
        }
        .preview-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.3rem;
            margin-top: 0.5rem;
        }
        .preview-item {
            font-size: 1.5rem;
            background: #fff;
            padding: 0.3rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .preview-box-name {
            font-weight: 600;
            color: #4a3f35;
            margin-top: 0.5rem;
        }

        /* Summary */
        .summary-items {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 1rem;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
        }
        .summary-item:last-child { border: none; }
        .summary-item-name { color: #666; }
        .summary-item-price { font-weight: 600; color: #4a3f35; }
        .summary-empty { color: #999; text-align: center; padding: 1rem; }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-top: 2px solid #f0f0f0;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: #4a3f35;
        }
        #totalPrice { color: #c9956c; }

        .add-cart-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #c9956c 0%, #a67c52 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .add-cart-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(201,149,108,0.4);
        }
        .add-cart-btn:disabled {
            background: #ddd;
            cursor: not-allowed;
        }

        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            gap: 1rem;
        }
        .nav-btn {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-family:"El Messiri", sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .nav-btn.prev {
            background: #f5f5f5;
            color: #666;
        }
        .nav-btn.prev:hover { background: #eee; }
        .nav-btn.next {
            background: linear-gradient(135deg, #4a3f35 0%, #6b5a4a 100%);
            color: #fff;
        }
        .nav-btn.next:hover { transform: translateY(-2px); }

        /* Message Section */
        .message-section { margin-top: 1.5rem; }
        .message-input, .message-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #eee;
            border-radius: 12px;
            font-family: "El Messiri", sans-serif;
            font-size: 1rem;
            transition: all 0.3s;
            margin-bottom: 0.5rem;
        }
        .message-input:focus, .message-textarea:focus {
            outline: none;
            border-color: #c9956c;
        }
        .message-textarea {
            min-height: 120px;
            resize: vertical;
        }
        .char-count {
            text-align: left;
            color: #999;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .builder-layout { grid-template-columns: 1fr; }
            .preview-panel { position: static; }
            .hero-content { flex-direction: column; text-align: center; }
            .hero-text p { margin: 0 auto; }
        }
        @media (max-width: 600px) {
            .hero-text h1 { font-size: 1.8rem; }
            .hero-gift { font-size: 5rem; }
            .steps-progress { flex-wrap: wrap; gap: 0.5rem; }
            .step { padding: 0.5rem 0.8rem; font-size: 0.85rem; }
            .step::after { display: none; }
            .options-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>

    <script>
        // Gift Builder State
        let currentStep = 1;
        let giftState = {
            box: null,
            fillers: [],
            wrapping: null,
            ribbon: null,
            card: null,
            message: '',
            recipientName: ''
        };

        // Sample Data (will be replaced with API data)
        const sampleBoxes = [
            { id: 1, name: 'صندوق صغير', emoji: '📦', price: 25, size: 'small', maxItems: 3, color: '#f5e6d3' },
            { id: 2, name: 'صندوق متوسط', emoji: '🎁', price: 45, size: 'medium', maxItems: 5, color: '#e8d4c4' },
            { id: 3, name: 'صندوق كبير', emoji: '🎀', price: 75, size: 'large', maxItems: 8, color: '#d4c4b4' },
            { id: 4, name: 'صندوق فاخر', emoji: '👑', price: 120, size: 'xl', maxItems: 12, color: '#c9956c' },
        ];

        const sampleFillers = [
            { id: 1, name: 'شوكولاتة فيريرو', emoji: '🍫', price: 35, category: 'chocolate' },
            { id: 2, name: 'شوكولاتة جوديفا', emoji: '🍫', price: 55, category: 'chocolate' },
            { id: 3, name: 'باقة ورد أحمر', emoji: '🌹', price: 45, category: 'flower' },
            { id: 4, name: 'زهور بيضاء', emoji: '🌸', price: 35, category: 'flower' },
            { id: 5, name: 'عطر فاخر', emoji: '🌺', price: 150, category: 'perfume' },
            { id: 6, name: 'عطر صغير', emoji: '💐', price: 75, category: 'perfume' },
            { id: 7, name: 'سوار ذهبي', emoji: '💍', price: 85, category: 'accessory' },
            { id: 8, name: 'قلادة فضية', emoji: '📿', price: 65, category: 'accessory' },
            { id: 9, name: 'حلوى ملونة', emoji: '🍬', price: 20, category: 'candy' },
            { id: 10, name: 'مارشميلو', emoji: '🍡', price: 15, category: 'candy' },
            { id: 11, name: 'دبدوب صغير', emoji: '🧸', price: 40, category: 'toy' },
            { id: 12, name: 'شمعة معطرة', emoji: '🕯️', price: 30, category: 'other' },
        ];

        const sampleWrappings = [
            { id: 1, name: 'تغليف كلاسيكي', emoji: '🎁', price: 0, color: '#c9956c' },
            { id: 2, name: 'تغليف ذهبي', emoji: '✨', price: 15, color: '#ffd700' },
            { id: 3, name: 'تغليف وردي', emoji: '💝', price: 10, color: '#ff69b4' },
            { id: 4, name: 'تغليف أزرق', emoji: '💙', price: 10, color: '#4169e1' },
        ];

        const sampleRibbons = [
            { id: 1, name: 'شريط ذهبي', emoji: '🎀', price: 5, color: '#ffd700' },
            { id: 2, name: 'شريط أحمر', emoji: '❤️', price: 5, color: '#dc143c' },
            { id: 3, name: 'شريط وردي', emoji: '💗', price: 5, color: '#ff69b4' },
            { id: 4, name: 'شريط أبيض', emoji: '🤍', price: 5, color: '#ffffff' },
            { id: 5, name: 'بدون شريط', emoji: '➖', price: 0, color: 'transparent' },
        ];

        const sampleCards = [
            { id: 1, name: 'بطاقة عيد ميلاد', emoji: '🎂', price: 5, occasion: 'birthday' },
            { id: 2, name: 'بطاقة حب', emoji: '💕', price: 5, occasion: 'love' },
            { id: 3, name: 'بطاقة تهنئة', emoji: '🎉', price: 5, occasion: 'congrats' },
            { id: 4, name: 'بطاقة شكر', emoji: '🙏', price: 5, occasion: 'thanks' },
            { id: 5, name: 'بطاقة عيد', emoji: '🌙', price: 5, occasion: 'eid' },
            { id: 6, name: 'بدون بطاقة', emoji: '➖', price: 0, occasion: 'none' },
        ];

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadBoxes();
            loadFillers();
            loadWrappings();
            loadRibbons();
            loadCards();
            setupMessageInput();
        });

        function loadBoxes() {
            const grid = document.getElementById('boxesGrid');
            grid.innerHTML = sampleBoxes.map(box => `
                <div class="option-card" data-id="${box.id}" onclick="selectBox(${box.id})">
                    <div class="option-check"><i class="fas fa-check"></i></div>
                    <div class="option-image">${box.emoji}</div>
                    <div class="option-name">${box.name}</div>
                    <div class="option-price">${box.price} ل.س</div>
                    <div style="font-size:0.75rem;color:#999;margin-top:0.3rem;">حتى ${box.maxItems} عناصر</div>
                </div>
            `).join('');
        }

        function loadFillers(category = 'all') {
            const grid = document.getElementById('fillersGrid');
            const filtered = category === 'all' ? sampleFillers : sampleFillers.filter(f => f.category === category);
            
            grid.innerHTML = filtered.map(filler => {
                const inGift = giftState.fillers.find(f => f.id === filler.id);
                const qty = inGift ? inGift.qty : 0;
                return `
                    <div class="option-card filler-card ${qty > 0 ? 'selected' : ''}" data-id="${filler.id}">
                        <div class="option-check"><i class="fas fa-check"></i></div>
                        <div class="option-image">${filler.emoji}</div>
                        <div class="option-name">${filler.name}</div>
                        <div class="option-price">${filler.price} ل.س</div>
                        <div class="filler-qty">
                            <button class="qty-btn" onclick="event.stopPropagation(); changeFiller(${filler.id}, -1)">-</button>
                            <span class="qty-value">${qty}</span>
                            <button class="qty-btn" onclick="event.stopPropagation(); changeFiller(${filler.id}, 1)">+</button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function loadWrappings() {
            const grid = document.getElementById('wrappingsGrid');
            grid.innerHTML = sampleWrappings.map(w => `
                <div class="option-card ${giftState.wrapping?.id === w.id ? 'selected' : ''}" data-id="${w.id}" onclick="selectWrapping(${w.id})">
                    <div class="option-check"><i class="fas fa-check"></i></div>
                    <div class="option-image" style="background:${w.color}20;">${w.emoji}</div>
                    <div class="option-name">${w.name}</div>
                    <div class="option-price">${w.price > 0 ? w.price + ' ل.س' : 'مجاني'}</div>
                </div>
            `).join('');
        }

        function loadRibbons() {
            const grid = document.getElementById('ribbonsGrid');
            grid.innerHTML = sampleRibbons.map(r => `
                <div class="option-card ${giftState.ribbon?.id === r.id ? 'selected' : ''}" data-id="${r.id}" onclick="selectRibbon(${r.id})">
                    <div class="option-check"><i class="fas fa-check"></i></div>
                    <div class="option-image">${r.emoji}</div>
                    <div class="option-name">${r.name}</div>
                    <div class="option-price">${r.price > 0 ? r.price + ' ل.س' : 'مجاني'}</div>
                </div>
            `).join('');
        }

        function loadCards() {
            const grid = document.getElementById('cardsGrid');
            grid.innerHTML = sampleCards.map(c => `
                <div class="option-card ${giftState.card?.id === c.id ? 'selected' : ''}" data-id="${c.id}" onclick="selectCard(${c.id})">
                    <div class="option-check"><i class="fas fa-check"></i></div>
                    <div class="option-image">${c.emoji}</div>
                    <div class="option-name">${c.name}</div>
                    <div class="option-price">${c.price > 0 ? c.price + 'ل.س' : 'مجاني'}</div>
                </div>
            `).join('');
        }

        function setupMessageInput() {
            const textarea = document.getElementById('cardMessage');
            const charCount = document.getElementById('charCount');
            const recipientInput = document.getElementById('recipientName');

            textarea.addEventListener('input', () => {
                charCount.textContent = textarea.value.length;
                giftState.message = textarea.value;
            });

            recipientInput.addEventListener('input', () => {
                giftState.recipientName = recipientInput.value;
            });
        }

        // Selection Functions
        function selectBox(id) {
            const box = sampleBoxes.find(b => b.id === id);
            giftState.box = box;
            
            // Clear fillers if box changed to smaller one
            const totalFillers = giftState.fillers.reduce((sum, f) => sum + f.qty, 0);
            if (totalFillers > box.maxItems) {
                giftState.fillers = [];
            }
            
            document.querySelectorAll('#boxesGrid .option-card').forEach(card => {
                card.classList.toggle('selected', card.dataset.id == id);
            });
            
            updatePreview();
            updateSummary();
        }

        function changeFiller(id, delta) {
            if (!giftState.box) {
                alert('الرجاء اختيار صندوق أولاً');
                return;
            }

            const filler = sampleFillers.find(f => f.id === id);
            let existing = giftState.fillers.find(f => f.id === id);
            
            const totalFillers = giftState.fillers.reduce((sum, f) => sum + f.qty, 0);
            
            if (delta > 0 && totalFillers >= giftState.box.maxItems) {
                alert(`الصندوق يتسع لـ ${giftState.box.maxItems} عناصر فقط`);
                return;
            }

            if (existing) {
                existing.qty += delta;
                if (existing.qty <= 0) {
                    giftState.fillers = giftState.fillers.filter(f => f.id !== id);
                }
            } else if (delta > 0) {
                giftState.fillers.push({ ...filler, qty: 1 });
            }

            loadFillers(document.querySelector('.cat-btn.active')?.dataset.cat || 'all');
            updatePreview();
            updateSummary();
        }

        function selectWrapping(id) {
            giftState.wrapping = sampleWrappings.find(w => w.id === id);
            loadWrappings();
            updatePreview();
            updateSummary();
        }

        function selectRibbon(id) {
            giftState.ribbon = sampleRibbons.find(r => r.id === id);
            loadRibbons();
            updatePreview();
            updateSummary();
        }

        function selectCard(id) {
            giftState.card = sampleCards.find(c => c.id === id);
            loadCards();
            updateSummary();
        }

        // Category Filter
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('cat-btn')) {
                document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
                e.target.classList.add('active');
                loadFillers(e.target.dataset.cat);
            }
        });

        // Update Preview
        function updatePreview() {
            const preview = document.getElementById('giftPreview');
            
            if (!giftState.box) {
                preview.innerHTML = `
                    <div class="preview-empty">
                        <i class="fas fa-box-open"></i>
                        <p>اختر صندوق للبدء</p>
                    </div>
                `;
                return;
            }

            const ribbonEmoji = giftState.ribbon && giftState.ribbon.id !== 5 ? giftState.ribbon.emoji : '';
            const fillerEmojis = giftState.fillers.flatMap(f => Array(f.qty).fill(f.emoji));

            preview.innerHTML = `
                <div class="preview-gift">
                    <div class="preview-gift-box">
                        ${ribbonEmoji ? `<span class="preview-ribbon">${ribbonEmoji}</span>` : ''}
                        ${giftState.box.emoji}
                    </div>
                    <div class="preview-box-name">${giftState.box.name}</div>
                    ${fillerEmojis.length > 0 ? `
                        <div class="preview-items">
                            ${fillerEmojis.map(e => `<span class="preview-item">${e}</span>`).join('')}
                        </div>
                    ` : '<div style="color:#999;font-size:0.9rem;margin-top:0.5rem;">أضف محتويات للهدية</div>'}
                </div>
            `;
        }

        // Update Summary
        function updateSummary() {
            const summaryItems = document.getElementById('summaryItems');
            const totalEl = document.getElementById('totalPrice');
            const addBtn = document.getElementById('addToCartBtn');
            
            let items = [];
            let total = 0;

            if (giftState.box) {
                items.push({ name: giftState.box.name, price: giftState.box.price });
                total += giftState.box.price;
            }

            giftState.fillers.forEach(f => {
                const itemTotal = f.price * f.qty;
                items.push({ name: `${f.name} × ${f.qty}`, price: itemTotal });
                total += itemTotal;
            });

            if (giftState.wrapping && giftState.wrapping.price > 0) {
                items.push({ name: giftState.wrapping.name, price: giftState.wrapping.price });
                total += giftState.wrapping.price;
            }

            if (giftState.ribbon && giftState.ribbon.price > 0) {
                items.push({ name: giftState.ribbon.name, price: giftState.ribbon.price });
                total += giftState.ribbon.price;
            }

            if (giftState.card && giftState.card.price > 0) {
                items.push({ name: giftState.card.name, price: giftState.card.price });
                total += giftState.card.price;
            }

            if (items.length === 0) {
                summaryItems.innerHTML = '<div class="summary-empty">لم تختر أي عناصر بعد</div>';
            } else {
                summaryItems.innerHTML = items.map(item => `
                    <div class="summary-item">
                        <span class="summary-item-name">${item.name}</span>
                        <span class="summary-item-price">${item.price}ل.س</span>
                    </div>
                `).join('');
            }

            totalEl.textContent = total + ' ل.س';
            addBtn.disabled = !giftState.box;
        }

        // Navigation
        function goToStep(step) {
            if (step < 1 || step > 4) return;
            if (step > 1 && !giftState.box) {
                alert('الرجاء اختيار صندوق أولاً');
                return;
            }

            currentStep = step;
            
            // Update steps UI
            document.querySelectorAll('.step').forEach((s, i) => {
                s.classList.remove('active', 'completed');
                if (i + 1 < step) s.classList.add('completed');
                if (i + 1 === step) s.classList.add('active');
            });

            // Show/hide content
            document.querySelectorAll('.step-content').forEach((content, i) => {
                content.style.display = i + 1 === step ? 'block' : 'none';
            });

            // Update nav buttons
            document.getElementById('prevBtn').style.display = step > 1 ? 'flex' : 'none';
            document.getElementById('nextBtn').style.display = step < 4 ? 'flex' : 'none';
        }

        function nextStep() {
            if (currentStep === 1 && !giftState.box) {
                alert('الرجاء اختيار صندوق أولاً');
                return;
            }
            goToStep(currentStep + 1);
        }

        function prevStep() {
            goToStep(currentStep - 1);
        }

        // Add to Cart
        async function addGiftToCart() {
            if (!giftState.box) {
                alert('الرجاء اختيار صندوق أولاً');
                return;
            }

            const btn = document.getElementById('addToCartBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';

            try {
                const response = await fetch('/api/custom-gift/add-to-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        box_id: giftState.box.id,
                        fillers: giftState.fillers.map(f => ({ id: f.id, qty: f.qty })),
                        wrapping_id: giftState.wrapping?.id,
                        ribbon_id: giftState.ribbon?.id,
                        card_id: giftState.card?.id,
                        message: giftState.message,
                        recipient_name: giftState.recipientName
                    })
                });

                const data = await response.json();

                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> تمت الإضافة!';
                    btn.style.background = '#27ae60';
                    
                    // Update cart count in navbar
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(data.cart_count);
                    }
                    
                    // Dispatch event for navbar
                    window.dispatchEvent(new Event('cart-updated'));

                    setTimeout(() => {
                        if (confirm('تمت إضافة الهدية للسلة! هل تريد الذهاب للسلة؟')) {
                            window.location.href = '/cart';
                        } else {
                            // Reset for new gift
                            resetGiftBuilder();
                        }
                    }, 500);
                } else {
                    throw new Error(data.message || 'حدث خطأ');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('حدث خطأ أثناء إضافة الهدية للسلة');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-shopping-cart"></i> أضف للسلة';
            }
        }

        function resetGiftBuilder() {
            giftState = {
                box: null,
                fillers: [],
                wrapping: null,
                ribbon: null,
                card: null,
                message: '',
                recipientName: ''
            };
            
            currentStep = 1;
            goToStep(1);
            loadBoxes();
            loadFillers();
            loadWrappings();
            loadRibbons();
            loadCards();
            updatePreview();
            updateSummary();
            
            document.getElementById('cardMessage').value = '';
            document.getElementById('recipientName').value = '';
            document.getElementById('charCount').textContent = '0';
            
            const btn = document.getElementById('addToCartBtn');
            btn.style.background = '';
            btn.innerHTML = '<i class="fas fa-shopping-cart"></i> أضف للسلة';
        }
    </script>
</body>
</html>
