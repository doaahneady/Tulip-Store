<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الدفع الآمن - Tulip Store</title>
    <link rel="icon" type="image/png" sizes="48x48" href="/images/fav_icon-v1.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://js.stripe.com/v2/"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'El Messiri', sans-serif;
            background: linear-gradient(135deg, #e8f4f8 0%, #d4edda 50%, #f0f8ff 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background circles */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }
        
        body::before {
            width: 500px;
            height: 500px;
            background: #2a7080;
            top: -250px;
            right: -250px;
            animation: float 20s ease-in-out infinite;
        }
        
        body::after {
            width: 400px;
            height: 400px;
            background: #ff6b35;
            bottom: -200px;
            left: -200px;
            animation: float 15s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #2a7080;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 30px;
            padding: 12px 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(42, 112, 128, 0.1);
            transition: all 0.3s;
        }
        
        .back-button:hover {
            background: #fff;
            box-shadow: 0 6px 20px rgba(42, 112, 128, 0.2);
            transform: translateX(5px);
        }
        
        .payment-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
            animation: slideUp 0.6s ease;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Order Summary Card */
        .order-summary {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .summary-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e8f4f8;
        }
        
        .summary-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 30px rgba(42, 112, 128, 0.3);
        }
        
        .summary-icon i {
            font-size: 2.5rem;
            color: #fff;
        }
        
        .summary-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        
        .summary-subtitle {
            font-size: 0.9rem;
            color: #666;
        }
        
        .amount-box {
            background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%);
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(42, 112, 128, 0.2);
        }
        
        .amount-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
        }
        
        .amount-value {
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 2px;
        }
        
        .security-features {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .security-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .security-item > div {
            flex-shrink: 0;
        }
        
        .security-item span {
            font-size: 0.9rem;
            color: #555;
            font-weight: 600;
        }
        
        /* Payment Card */
        .payment-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            margin-bottom: 30px;
        }
        
        .card-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 8px;
        }
        
        .card-subtitle {
            font-size: 1rem;
            color: #666;
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: none;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert.show { display: flex; }
        .alert-success { background: #d1fae5; color: #065f46; border: 2px solid #10b981; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 2px solid #ef4444; }
        .alert-info { background: #e8f4f8; color: #1a5060; border: 2px solid #2a7080; }
        .alert i { font-size: 1.3rem; }
        
        /* Saved Cards */
        .saved-cards-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: #2a7080;
        }
        
        .saved-card {
            background: #fff;
            border: 3px solid #e8f4f8;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .saved-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2a7080 0%, #ff6b35 100%);
            transform: scaleX(0);
            transition: transform 0.3s;
        }
        
        .saved-card:hover {
            border-color: #2a7080;
            box-shadow: 0 8px 25px rgba(42, 112, 128, 0.15);
            transform: translateY(-2px);
        }
        
        .saved-card:hover::before {
            transform: scaleX(1);
        }
        
        .saved-card.selected {
            border-color: #2a7080;
            background: linear-gradient(135deg, #e8f4f8 0%, #f0f8ff 100%);
            box-shadow: 0 8px 25px rgba(42, 112, 128, 0.2);
        }
        
        .saved-card.selected::before {
            transform: scaleX(1);
        }
        
        .card-brand-icon {
            width: 60px;
            height: 45px;
            background: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card-info {
            flex: 1;
        }
        
        .card-number {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        
        .card-expiry {
            font-size: 0.85rem;
            color: #666;
        }
        
        .card-check {
            width: 28px;
            height: 28px;
            border: 3px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .saved-card.selected .card-check {
            background: #2a7080;
            border-color: #2a7080;
            color: #fff;
        }
        
        .add-new-card-btn {
            width: 100%;
            padding: 18px;
            background: #fff;
            border: 3px dashed #2a7080;
            border-radius: 16px;
            color: #2a7080;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .add-new-card-btn:hover {
            background: #e8f4f8;
            border-style: solid;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42, 112, 128, 0.15);
        }
        
        /* New Card Form */
        .new-card-form {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        
        .new-card-form.show {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }
        
        .form-label i {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2a7080 0%, #1a5060 100%);
            color: #fff;
            border-radius: 8px;
            font-size: 0.85rem;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 18px;
            padding-left: 55px;
            border: 3px solid #e8f4f8;
            border-radius: 14px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            transition: all 0.3s;
            background: #fff;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #2a7080;
            box-shadow: 0 0 0 5px rgba(42, 112, 128, 0.1);
            transform: translateY(-2px);
        }
        
        .form-input.card-number {
            font-family: 'Courier New', monospace;
            font-size: 1.15rem;
            letter-spacing: 2px;
            direction: ltr;
            text-align: left;
            padding-left: 70px;
            padding-right: 18px;
        }
        
        .form-input.cvv, .form-input.expiry {
            font-family: 'Courier New', monospace;
            font-size: 1.15rem;
            direction: ltr;
            text-align: center;
            padding-left: 18px;
        }
        
        .card-type-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            transition: all 0.3s;
            opacity: 0.3;
            filter: grayscale(1);
            pointer-events: none;
        }
        
        .card-type-icon.active {
            opacity: 1;
            filter: grayscale(0);
            animation: bounce 0.5s ease;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.1); }
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.9rem;
            margin-top: 10px;
            display: none;
            font-weight: 600;
            animation: shake 0.3s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .error-message.show {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .save-card-checkbox {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #e8f4f8 0%, #f0f8ff 100%);
            border-radius: 14px;
            border: 3px solid #2a7080;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .save-card-checkbox:hover {
            background: linear-gradient(135deg, #d4edda 0%, #e8f4f8 100%);
            box-shadow: 0 6px 20px rgba(42, 112, 128, 0.15);
        }
        
        .save-card-checkbox input {
            width: 22px;
            height: 22px;
            cursor: pointer;
            accent-color: #2a7080;
        }
        
        .save-card-checkbox label {
            flex: 1;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            color: #1a5060;
        }
        
        .submit-button {
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-family: 'El Messiri', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }
        
        .submit-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .submit-button:hover:not(:disabled)::before {
            width: 300px;
            height: 300px;
        }
        
        .submit-button:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 107, 53, 0.4);
        }
        
        .submit-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .submit-button span, .submit-button i {
            position: relative;
            z-index: 1;
        }
        
        .loading-spinner {
            width: 22px;
            height: 22px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 968px) {
            .payment-wrapper {
                grid-template-columns: 1fr;
            }
            
            .order-summary {
                position: static;
            }
        }
        
        @media (max-width: 640px) {
            body {
                padding: 10px;
            }
            
            .payment-card, .order-summary {
                padding: 25px 20px;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .amount-value {
                font-size: 2.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
            <a href="/checkout" class="back-button" style="margin-bottom: 0;">
                <i class="fas fa-arrow-right"></i>
                العودة إلى الدفع
            </a>
            
            <img src="/images/logo.png" alt="Tulip Store" style="height: 60px; width: auto; filter: drop-shadow(0 4px 15px rgba(42, 112, 128, 0.2));">
        </div>
        
        <div class="payment-wrapper">
            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-header">
                    <div class="summary-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h2 class="summary-title">دفع آمن</h2>
                    <p class="summary-subtitle">معاملتك محمية بالكامل</p>
                </div>
                
                <div class="amount-box">
                    <div class="amount-label">المبلغ الإجمالي</div>
                    <div class="amount-value">$<span id="totalAmount">{{ number_format($order->total, 2) }}</span></div>
                </div>
                
                <div class="security-features">
                    <div class="security-item">
                        <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-lock" style="color: #fff; font-size: 0.9rem;"></i>
                        </div>
                        <span>تشفير SSL 256-bit</span>
                    </div>
                    <div class="security-item">
                        <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-shield-alt" style="color: #fff; font-size: 0.9rem;"></i>
                        </div>
                        <span>حماية بيانات البطاقة</span>
                    </div>
                    <div class="security-item">
                        <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-circle" style="color: #fff; font-size: 0.9rem;"></i>
                        </div>
                        <span>معالجة فورية</span>
                    </div>
                    <div class="security-item">
                        <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-shield" style="color: #fff; font-size: 0.9rem;"></i>
                        </div>
                        <span>خصوصية مضمونة</span>
                    </div>
                </div>
            </div>
            
            <!-- Payment Form -->
            <div class="payment-card">
                <div class="card-header">
                    <h1 class="card-title">
                        <i class="fas fa-credit-card"></i>
                        معلومات الدفع
                    </h1>
                    <p class="card-subtitle">أدخل بيانات بطاقتك لإتمام عملية الشراء</p>
                </div>
                
                <div id="alertContainer"></div>
                
                <!-- Saved Cards Section -->
                <div class="saved-cards-section" id="savedCardsSection" style="display: none;">
                    <h3 class="section-title">
                        <i class="fas fa-wallet"></i>
                        البطاقات المحفوظة
                    </h3>
                    <div id="savedCardsList"></div>
                    <button type="button" class="add-new-card-btn" onclick="showNewCardForm()">
                        <i class="fas fa-plus-circle"></i>
                        إضافة بطاقة جديدة
                    </button>
                </div>
                
                <!-- New Card Form -->
                <form id="paymentForm" class="new-card-form show">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-credit-card"></i>
                            رقم البطاقة
                        </label>
                        <div class="input-wrapper">
                            <input type="text" id="cardNumber" class="form-input card-number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                            <i id="cardTypeIcon" class="card-type-icon fas fa-credit-card"></i>
                        </div>
                        <div class="error-message" id="cardNumberError">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>الرجاء إدخال رقم بطاقة صحيح</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i>
                            اسم حامل البطاقة
                        </label>
                        <input type="text" id="cardName" class="form-input" placeholder="الاسم كما هو مكتوب على البطاقة" required>
                        <div class="error-message" id="cardNameError">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>الرجاء إدخال اسم حامل البطاقة</span>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar"></i>
                                تاريخ الانتهاء
                            </label>
                            <input type="text" id="cardExpiry" class="form-input expiry" placeholder="MM/YY" maxlength="5" required>
                            <div class="error-message" id="cardExpiryError">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>تاريخ غير صحيح</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                CVV
                            </label>
                            <input type="password" id="cardCVV" class="form-input cvv" placeholder="•••" maxlength="4" required>
                            <div class="error-message" id="cardCVVError">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>رمز CVV غير صحيح</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="save-card-checkbox">
                            <input type="checkbox" id="saveCard">
                            <label for="saveCard">
                                <i class="fas fa-bookmark"></i>
                                حفظ البطاقة للمشتريات المستقبلية
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-button" id="submitButton">
                        <span id="buttonText">إتمام الدفع الآن</span>
                        <div class="loading-spinner" id="loadingSpinner"></div>
                        <i class="fas fa-arrow-left" id="buttonIcon"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize Stripe
        Stripe.setPublishableKey('{{ config("services.stripe.public") }}');
        
        const orderId = {{ $order->id }};
        const orderTotal = {{ $order->total }};
        let selectedSavedCard = null;
        
        // Load saved cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSavedCards();
        });
        
        // Load saved cards from backend
        async function loadSavedCards() {
            try {
                const response = await fetch('/api/user/saved-cards', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const cards = await response.json();
                    if (cards && cards.length > 0) {
                        displaySavedCards(cards);
                    }
                }
            } catch (error) {
                console.log('No saved cards or error loading:', error);
            }
        }
        
        // Display saved cards
        function displaySavedCards(cards) {
            const savedCardsSection = document.getElementById('savedCardsSection');
            const savedCardsList = document.getElementById('savedCardsList');
            const newCardForm = document.getElementById('paymentForm');
            
            savedCardsList.innerHTML = '';
            
            cards.forEach(card => {
                const cardElement = document.createElement('div');
                cardElement.className = 'saved-card';
                cardElement.onclick = () => selectSavedCard(card, cardElement);
                
                const brandIcon = getCardBrandIcon(card.brand);
                
                cardElement.innerHTML = `
                    <div class="card-brand-icon">${brandIcon}</div>
                    <div class="card-info">
                        <div class="card-number">•••• •••• •••• ${card.last4}</div>
                        <div class="card-expiry">ينتهي في ${card.exp_month}/${card.exp_year}</div>
                    </div>
                    <div class="card-check">
                        <i class="fas fa-check" style="display: none;"></i>
                    </div>
                `;
                
                savedCardsList.appendChild(cardElement);
            });
            
            savedCardsSection.style.display = 'block';
            newCardForm.classList.remove('show');
        }
        
        // Get card brand icon
        function getCardBrandIcon(brand) {
            const icons = {
                'visa': '<i class="fab fa-cc-visa" style="color: #1434CB;"></i>',
                'mastercard': '<i class="fab fa-cc-mastercard" style="color: #EB001B;"></i>',
                'amex': '<i class="fab fa-cc-amex" style="color: #006FCF;"></i>',
                'discover': '<i class="fab fa-cc-discover" style="color: #FF6000;"></i>',
                'diners': '<i class="fab fa-cc-diners-club" style="color: #0079BE;"></i>',
                'jcb': '<i class="fab fa-cc-jcb" style="color: #0E4C96;"></i>'
            };
            return icons[brand.toLowerCase()] || '<i class="fas fa-credit-card" style="color: #666;"></i>';
        }
        
        // Select saved card
        function selectSavedCard(card, element) {
            document.querySelectorAll('.saved-card').forEach(el => {
                el.classList.remove('selected');
                el.querySelector('.card-check i').style.display = 'none';
            });
            
            element.classList.add('selected');
            element.querySelector('.card-check i').style.display = 'block';
            selectedSavedCard = card;
        }
        
        // Show new card form
        function showNewCardForm() {
            const newCardForm = document.getElementById('paymentForm');
            newCardForm.classList.add('show');
            selectedSavedCard = null;
            
            document.querySelectorAll('.saved-card').forEach(el => {
                el.classList.remove('selected');
                el.querySelector('.card-check i').style.display = 'none';
            });
            
            newCardForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        // Detect card type and show icon
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
            
            const cardTypeIcon = document.getElementById('cardTypeIcon');
            const firstDigit = value.charAt(0);
            const firstTwoDigits = value.substring(0, 2);
            const firstFourDigits = value.substring(0, 4);
            
            cardTypeIcon.classList.add('active');
            
            if (firstDigit === '4') {
                cardTypeIcon.className = 'card-type-icon fab fa-cc-visa active';
                cardTypeIcon.style.color = '#1434CB';
            } else if ((firstTwoDigits >= '51' && firstTwoDigits <= '55') || (firstTwoDigits >= '22' && firstTwoDigits <= '27')) {
                cardTypeIcon.className = 'card-type-icon fab fa-cc-mastercard active';
                cardTypeIcon.style.color = '#EB001B';
            } else if (firstTwoDigits === '34' || firstTwoDigits === '37') {
                cardTypeIcon.className = 'card-type-icon fab fa-cc-amex active';
                cardTypeIcon.style.color = '#006FCF';
            } else if (firstFourDigits === '6011' || firstTwoDigits === '65' || (firstTwoDigits >= '64' && firstTwoDigits <= '65')) {
                cardTypeIcon.className = 'card-type-icon fab fa-cc-discover active';
                cardTypeIcon.style.color = '#FF6000';
            } else if (firstTwoDigits === '36' || firstTwoDigits === '38' || firstTwoDigits === '30') {
                cardTypeIcon.className = 'card-type-icon fab fa-cc-diners-club active';
                cardTypeIcon.style.color = '#0079BE';
            } else if (firstFourDigits >= '3528' && firstFourDigits <= '3589') {
                cardTypeIcon.className = 'card-type-icon fab fa-cc-jcb active';
                cardTypeIcon.style.color = '#0E4C96';
            } else {
                cardTypeIcon.className = 'card-type-icon fas fa-credit-card';
                cardTypeIcon.style.color = '#666';
                cardTypeIcon.classList.remove('active');
            }
        });
        
        // Format expiry
        document.getElementById('cardExpiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
        
        // Show alert
        function showAlert(message, type) {
            const container = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} show`;
            
            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle';
            alert.innerHTML = `
                <i class="fas fa-${icon}"></i>
                <span>${message}</span>
            `;
            
            container.innerHTML = '';
            container.appendChild(alert);
            
            if (type === 'success') {
                setTimeout(() => {
                    window.location.href = '/my-orders';
                }, 2000);
            }
        }
        
        // Handle form submission
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Check if using saved card
            if (selectedSavedCard) {
                await processWithSavedCard();
                return;
            }
            
            // Reset errors
            document.querySelectorAll('.error-message').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.form-input').forEach(el => el.style.borderColor = '#e8f4f8');
            
            // Get values
            const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const cardName = document.getElementById('cardName').value.trim();
            const cardExpiry = document.getElementById('cardExpiry').value;
            const cardCVV = document.getElementById('cardCVV').value;
            const saveCard = document.getElementById('saveCard').checked;
            
            // Validate
            let isValid = true;
            
            if (cardNumber.length < 16 || !/^\d+$/.test(cardNumber)) {
                document.getElementById('cardNumberError').classList.add('show');
                document.getElementById('cardNumber').style.borderColor = '#ef4444';
                isValid = false;
            }
            
            if (cardName.length < 3) {
                document.getElementById('cardNameError').classList.add('show');
                document.getElementById('cardName').style.borderColor = '#ef4444';
                isValid = false;
            }
            
            const expiryRegex = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
            if (!expiryRegex.test(cardExpiry)) {
                document.getElementById('cardExpiryError').classList.add('show');
                document.getElementById('cardExpiry').style.borderColor = '#ef4444';
                isValid = false;
            }
            
            if (cardCVV.length < 3 || !/^\d+$/.test(cardCVV)) {
                document.getElementById('cardCVVError').classList.add('show');
                document.getElementById('cardCVV').style.borderColor = '#ef4444';
                isValid = false;
            }
            
            if (!isValid) {
                showAlert('الرجاء تصحيح الأخطاء في بيانات البطاقة', 'error');
                return;
            }
            
            // Show loading
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const buttonIcon = document.getElementById('buttonIcon');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            submitButton.disabled = true;
            buttonText.textContent = 'جاري المعالجة...';
            buttonIcon.style.display = 'none';
            loadingSpinner.style.display = 'block';
            
            showAlert('جاري معالجة الدفع بشكل آمن...', 'info');
            
            // Parse expiry
            const expiryParts = cardExpiry.split('/');
            const expMonth = parseInt(expiryParts[0], 10);
            const expYear = parseInt('20' + expiryParts[1], 10);
            
            // Create Stripe token
            Stripe.card.createToken({
                number: cardNumber,
                cvc: cardCVV,
                exp_month: expMonth,
                exp_year: expYear,
                name: cardName
            }, async function(status, response) {
                if (response.error) {
                    showAlert('خطأ في معالجة البطاقة: ' + response.error.message, 'error');
                    submitButton.disabled = false;
                    buttonText.textContent = 'إتمام الدفع الآن';
                    buttonIcon.style.display = 'block';
                    loadingSpinner.style.display = 'none';
                    return;
                }
                
                try {
                    const apiResponse = await fetch('/api/orders/stripe-payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            stripe_token: response.id,
                            save_card: saveCard
                        })
                    });
                    
                    const result = await apiResponse.json();
                    
                    if (result.success) {
                        showAlert('✅ تم الدفع بنجاح! جاري تحويلك...', 'success');
                    } else {
                        showAlert(result.message || 'فشل معالجة الدفع', 'error');
                        submitButton.disabled = false;
                        buttonText.textContent = 'إتمام الدفع الآن';
                        buttonIcon.style.display = 'block';
                        loadingSpinner.style.display = 'none';
                    }
                } catch (error) {
                    showAlert('حدث خطأ أثناء معالجة الدفع', 'error');
                    submitButton.disabled = false;
                    buttonText.textContent = 'إتمام الدفع الآن';
                    buttonIcon.style.display = 'block';
                    loadingSpinner.style.display = 'none';
                }
            });
        });
        
        // Process payment with saved card
        async function processWithSavedCard() {
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const buttonIcon = document.getElementById('buttonIcon');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            submitButton.disabled = true;
            buttonText.textContent = 'جاري المعالجة...';
            buttonIcon.style.display = 'none';
            loadingSpinner.style.display = 'block';
            
            showAlert('جاري معالجة الدفع بشكل آمن...', 'info');
            
            try {
                const apiResponse = await fetch('/api/orders/stripe-payment-saved-card', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        card_id: selectedSavedCard.id
                    })
                });
                
                const result = await apiResponse.json();
                
                if (result.success) {
                    showAlert('✅ تم الدفع بنجاح! جاري تحويلك...', 'success');
                } else {
                    showAlert(result.message || 'فشل معالجة الدفع', 'error');
                    submitButton.disabled = false;
                    buttonText.textContent = 'إتمام الدفع الآن';
                    buttonIcon.style.display = 'block';
                    loadingSpinner.style.display = 'none';
                }
            } catch (error) {
                showAlert('حدث خطأ أثناء معالجة الدفع', 'error');
                submitButton.disabled = false;
                buttonText.textContent = 'إتمام الدفع الآن';
                buttonIcon.style.display = 'block';
                loadingSpinner.style.display = 'none';
            }
        }
    </script>
</body>
</html>
