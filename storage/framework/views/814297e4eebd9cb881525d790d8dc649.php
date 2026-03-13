<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>الملف الشخصي - Tulip Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Changa:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/store.css?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "El Messiri", sans-serif; background: #f8f9fa; min-height: 100vh; }
        
        .profile-container { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; display: flex; gap: 2rem; position: relative; }
        
        /* Sidebar Toggle Button */
        .sidebar-toggle {
            display: none;
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            z-index: 1001;
            background: #0f4f55;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(15, 79, 85, 0.3);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        .sidebar-toggle:hover { background: #1a6b73; transform: scale(1.05); }

        .profile-sidebar {
            width: 280px; flex-shrink: 0; background: white; border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden; height: fit-content;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .profile-header {
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%);
            padding: 2rem; text-align: center; color: white;
        }
        
        .profile-avatar {
            width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2);
            margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; border: 3px solid rgba(255,255,255,0.3);
        }
        
        .profile-name { font-size: 1.3rem; font-weight: 700; margin-bottom: 0.3rem; }
        .profile-email { font-size: 0.9rem; opacity: 0.8; }
        
        .profile-nav { padding: 1rem 0; }
        
        .profile-nav-item {
            display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem;
            cursor: pointer; transition: all 0.2s; color: #444; font-weight: 500;
        }
        
        .profile-nav-item:hover { background: #f8f9fa; color: #0f4f55; }
        .profile-nav-item.active { background: #e8f4f8; color: #0f4f55; border-right: 4px solid #0f4f55; }
        .profile-nav-item i { width: 20px; text-align: center; font-size: 1.1rem; }
        
        .profile-content { flex: 1; min-width: 0; }
        
        .content-section {
            background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2rem; display: none; animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .content-section.active { display: block; }
        
        .section-title {
            font-size: 1.5rem; font-weight: 700; color: #0f4f55; margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: 0.8rem;
        }
        
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; font-weight: 600; color: #333; margin-bottom: 0.5rem; }
        
        .form-input {
            width: 100%; padding: 0.9rem 1.2rem; border: 2px solid #e8e8e8; border-radius: 10px;
            font-family: 'El Messiri', sans-serif; font-size: 1rem; transition: all 0.2s;
        }
        .form-input:focus { outline: none; border-color: #0f4f55; box-shadow: 0 0 0 3px rgba(15,79,85,0.1); }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        
        .btn-save {
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%); color: white;
            border: none; padding: 1rem 2.5rem; border-radius: 10px; font-family: 'El Messiri', sans-serif;
            font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s;
        }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(15,79,85,0.3); }

        .currency-toggle { display:flex; gap:0.6rem; align-items:center; flex-wrap:wrap; }
        .currency-btn {
            border: 2px solid #0f4f55;
            background: #fff;
            color: #0f4f55;
            padding: 0.7rem 1rem;
            border-radius: 10px;
            font-family: "El Messiri", sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .currency-btn.active { background:#0f4f55; color:#fff; }
        .currency-btn:focus { outline: none; box-shadow: 0 0 0 3px rgba(15,79,85,0.15); }
        
        .order-card {
            background: #f8f9fa; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem;
            border: 1px solid #e8e8e8; transition: all 0.2s; cursor: pointer;
        }
        .order-card:hover { border-color: #0f4f55; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .order-id { font-weight: 700; color: #0f4f55; }
        .order-date { color: #888; font-size: 0.9rem; }
        
        .order-status {
            display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 1rem;
            border-radius: 20px; font-size: 0.85rem; font-weight: 600;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #d1ecf1; color: #0c5460; }
        
        .order-total { font-size: 1.2rem; font-weight: 700; color: #ff6b35; }
        
        /* Order Details Modal */
        .order-modal {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6);
            z-index: 9999; align-items: center; justify-content: center; padding: 1rem;
            backdrop-filter: blur(5px);
        }
        .order-modal.active { display: flex; }
        .order-modal-content {
            background: white; border-radius: 20px; max-width: 600px; width: 100%;
            max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalSlide 0.3s ease;
        }
        @keyframes modalSlide {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-header {
            background: linear-gradient(135deg, #0f4f55 0%, #1a6b73 100%);
            padding: 1.5rem 2rem; color: white; display: flex; justify-content: space-between; align-items: center;
        }
        .modal-header h3 { font-size: 1.3rem; font-weight: 700; }
        .modal-close {
            background: rgba(255,255,255,0.2); border: none; color: white; width: 36px; height: 36px;
            border-radius: 50%; cursor: pointer; font-size: 1.2rem; transition: all 0.2s;
        }
        .modal-close:hover { background: rgba(255,255,255,0.3); }
        .modal-body { padding: 2rem; }
        .order-detail-row {
            display: flex; justify-content: space-between; padding: 0.8rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .order-detail-row:last-child { border-bottom: none; }
        .detail-label { color: #888; }
        .detail-value { font-weight: 600; color: #333; }
        .order-items-title { font-weight: 700; color: #0f4f55; margin: 1.5rem 0 1rem; font-size: 1.1rem; }
        .order-item {
            display: flex; gap: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; margin-bottom: 0.8rem;
        }
        .order-item-img {
            width: 60px; height: 60px; border-radius: 8px; background: #e8e8e8;
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }
        .order-item-info { flex: 1; }
        .order-item-name { font-weight: 600; color: #333; margin-bottom: 0.3rem; }
        .order-item-qty { color: #888; font-size: 0.9rem; }
        .order-item-price { font-weight: 700; color: #ff6b35; }

        
        .notification-item {
            display: flex; gap: 1rem; padding: 1.2rem; border-radius: 10px;
            margin-bottom: 0.8rem; background: #f8f9fa; transition: all 0.2s;
        }
        .notification-item:hover { background: #e8f4f8; }
        .notification-item.unread { background: #e8f4f8; border-right: 3px solid #0f4f55; }
        
        .notification-icon {
            width: 45px; height: 45px; border-radius: 50%; display: flex;
            align-items: center; justify-content: center; flex-shrink: 0;
        }
        .notification-icon.order { background: #d4edda; color: #155724; }
        .notification-icon.promo { background: #fff3cd; color: #856404; }
        .notification-icon.system { background: #cce5ff; color: #004085; }
        
        .notification-content { flex: 1; }
        .notification-title { font-weight: 600; color: #333; margin-bottom: 0.3rem; }
        .notification-text { color: #666; font-size: 0.9rem; line-height: 1.5; }
        .notification-time { color: #999; font-size: 0.8rem; margin-top: 0.5rem; }
        
        .empty-state {
            text-align: center; padding: 3rem; color: #999;
        }
        .empty-state i { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
        .empty-state p { font-size: 1.1rem; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(3px);
            z-index: 999;
            animation: fadeInOverlay 0.3s ease;
        }
        @keyframes fadeInOverlay { from { opacity: 0; } to { opacity: 1; } }
        
        @media (max-width: 992px) {
            .sidebar-toggle { display: flex; align-items: center; justify-content: center; }
            
            .profile-sidebar {
                position: fixed;
                top: 0;
                right: -300px;
                bottom: 0;
                z-index: 1000;
                width: 280px !important;
                border-radius: 0;
                box-shadow: -5px 0 20px rgba(0,0,0,0.1);
            }
            
            .profile-sidebar.open { right: 0; }
            .sidebar-overlay.active { display: block; }
            
            .profile-container { padding-top: 4rem; }
        }
        
        @media (max-width: 768px) {
            .profile-container { flex-direction: column; }
            .form-row { grid-template-columns: 1fr; }
            .content-section { padding: 1.5rem; }
        }
    </style>
  </head>
  <body>
    <?php echo $__env->make('components.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <div class="profile-container">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        
        <div class="profile-sidebar" id="profileSidebar">
            <div class="profile-header">
                <div class="profile-avatar"><i class="fas fa-user"></i></div>
                <div class="profile-name" id="userName"><?php echo e(Auth::user()->name ?? 'المستخدم'); ?></div>
                <div class="profile-email" id="userEmail"><?php echo e(Auth::user()->email ?? ''); ?></div>
            </div>
            <div class="profile-nav">
                <div class="profile-nav-item active" onclick="showSection('info')">
                    <i class="fas fa-user-edit"></i><span>المعلومات الشخصية</span>
                </div>
                <div class="profile-nav-item" onclick="showSection('addresses')">
                    <i class="fas fa-map-marker-alt"></i><span>العناوين</span>
                </div>
               
                <div class="profile-nav-item" onclick="showSection('orders')">
                    <i class="fas fa-box-open"></i><span>طلباتي</span>
                </div>
                 <div class="profile-nav-item " onclick="showSection('cards')">
                    <i class="fas fa-credit-card"></i><span>بطاقاتي</span>
                </div>
                 <div class="profile-nav-item" onclick="showSection('notifications')">
                    <i class="fas fa-bell"></i><span>الإشعارات</span>
                    <span id="notifBadge" style="display:none;background:#ff6b35;color:white;padding:0.2rem 0.6rem;border-radius:10px;font-size:0.75rem;margin-right:auto;"></span>
                </div>
                 <div class="profile-nav-item " onclick="showSection('support')">
                    <i class="fas fa-headset"></i><span>التواصل مع الدعم التقني</span>
                </div>
                   <div class="profile-nav-item " onclick="showSection('signout')">
                    <i class="fas fa-sign-out-alt"></i><span>تسجيل خروج</span>
                </div>
            </div>
        </div>
        
        <div class="profile-content">
            <!-- Personal Info Section -->
            <div class="content-section active" id="section-info">
                <h2 class="section-title"><i class="fas fa-user-edit"></i> المعلومات الشخصية</h2>
                <form id="profileForm" onsubmit="saveProfile(event)">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-input" id="fullName" value="<?php echo e(Auth::user()->name ?? ''); ?>" placeholder="أدخل اسمك الكامل">
                        </div>
                        <div class="form-group">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" disabled class="form-input" id="email" value="<?php echo e(Auth::user()->email ?? ''); ?>" placeholder="example@email.com">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-input" id="phone" value="<?php echo e(Auth::user()->phone ?? ''); ?>" placeholder="09xxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label class="form-label">العنوان</label>
                            <input type="text" class="form-input" id="address" value="<?php echo e(Auth::user()->address ?? ''); ?>" placeholder="المدينة، الحي، الشارع">
                        </div>
                    </div>
                   
                    <div class="form-row">
                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">العملة المفضلة</label>
                            <input type="hidden" id="currencyPref" value="<?php echo e(strtoupper((string) (Auth::user()->currency ?? (session('currency') ?? 'USD')))); ?>">
                            <div class="currency-toggle">
                                <button type="button" class="currency-btn" id="currencyBtnUSD" onclick="setCurrencyPreferenceUI('USD')">
                                    <span>USD</span><span>($)</span>
                                </button>
                                <button type="button" class="currency-btn" id="currencyBtnSYP" onclick="setCurrencyPreferenceUI('SYP')">
                                    <span>SYP</span><span>(Syrian Pound)</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> حفظ التغييرات</button>
                </form>
            </div>

            <div class="content-section" id="section-addresses">
                <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> عناويني</h2>
                <p style="color: #666; font-family: 'El Messiri', sans-serif; margin-bottom: 1.5rem; font-size: 0.95rem;">
                    العناوين التي قمت باستخدامها في طلباتك السابقة:
                </p>
                <div id="addressesList">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>جاري تحميل العناوين...</p>
                    </div>
                </div>
            </div>

            <div class="content-section" id="section-orders">
                <h2 class="section-title"><i class="fas fa-box-open"></i> طلباتي</h2>
                <div id="ordersList">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>جاري تحميل الطلبات...</p>
                    </div>
                </div>
            </div>

            <div class="content-section" id="section-cards">
                <h2 class="section-title"><i class="fas fa-credit-card"></i> بطاقاتي</h2>
                <p style="color: #666; font-family: 'El Messiri', sans-serif; margin-bottom: 1.5rem; font-size: 0.95rem;">
                    البطاقات الائتمانية المحفوظة من عمليات الدفع السابقة:
                </p>
                <div id="cardsList">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>جاري تحميل البطاقات...</p>
                    </div>
                </div>
            </div>

            <div class="content-section" id="section-support">
                <h2 class="section-title"><i class="fas fa-headset"></i> الدعم التقني</h2>
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; border: 1px solid #e0e0e0;">
                    <i class="fas fa-headset" style="font-size: 3rem; color: #2a7080; margin-bottom: 1.5rem;"></i>
                    <h3 style="font-family: 'El Messiri', sans-serif; color: #1a1a1a; margin-bottom: 1rem;">نحن هنا لخدمتكم 24/24</h3>
                    <p style="font-family: 'El Messiri', sans-serif; color: #666; line-height: 1.6; margin-bottom: 2rem;">
                        يسعدنا الرد على استفساراتكم وحل مشكلاتكم في أي وقت. تواصل معنا عبر الواتساب للحصول على رد فوري من فريق الدعم.
                    </p>
                    <a href="https://wa.me/9639xxxxxxxx" target="_blank" style="display: inline-flex; align-items: center; gap: 0.8rem; background: #25D366; color: white; padding: 1rem 2rem; border-radius: 12px; font-family: 'El Messiri', sans-serif; font-weight: 700; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);">
                        <i class="fab fa-whatsapp" style="font-size: 1.5rem;"></i>
                        تواصل معنا عبر واتساب
                    </a>
                </div>
            </div>

            <div class="content-section" id="section-signout">
                <h2 class="section-title"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</h2>
                <div style="background: #fff5f5; padding: 2rem; border-radius: 15px; text-align: center; border: 1px solid #fed7d7;">
                    <p style="font-family: 'El Messiri', sans-serif; color: #c53030; font-weight: 700; font-size: 1.2rem; margin-bottom: 1.5rem;">هل أنت متأكد من رغبتك في تسجيل الخروج؟</p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <button onclick="showSection('info')" style="padding: 0.8rem 2rem; border-radius: 10px; border: 1px solid #e2e8f0; background: white; font-family: 'El Messiri', sans-serif; cursor: pointer;">إلغاء</button>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" style="padding: 0.8rem 2rem; border-radius: 10px; border: none; background: #e53e3e; color: white; font-family: 'El Messiri', sans-serif; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);">تأكيد تسجيل الخروج</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="content-section" id="section-security">
                <h2 class="section-title"><i class="fas fa-lock"></i> الأمان</h2>
                <form id="passwordForm" onsubmit="changePassword(event)">
                    <div class="form-group">
                        <label class="form-label">كلمة المرور الحالية</label>
                        <div style="position: relative;">
                            <input type="password" class="form-input" id="currentPassword" required style="padding-left: 45px;">
                            <i class="fas fa-eye toggle-password" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 1.1rem;"></i>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">كلمة المرور الجديدة</label>
                            <div style="position: relative;">
                                <input type="password" class="form-input" id="newPassword" required style="padding-left: 45px;">
                                <i class="fas fa-eye toggle-password" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 1.1rem;"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <div style="position: relative;">
                                <input type="password" class="form-input" id="newPasswordConfirm" required style="padding-left: 45px;">
                                <i class="fas fa-eye toggle-password" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 1.1rem;"></i>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-save"><i class="fas fa-save"></i> تغيير كلمة المرور</button>
                </form>
            </div>
            
            <!-- Notifications Section -->
            <div class="content-section" id="section-notifications">
                <h2 class="section-title"><i class="fas fa-bell"></i> الإشعارات</h2>
                <div id="notificationsList">
                    <div class="empty-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>جاري تحميل الإشعارات...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Details Modal -->
    <div class="order-modal" id="orderModal" onclick="if(event.target === this) closeOrderModal()">
        <div class="order-modal-content">
            <div class="modal-header">
                <h3 id="modalOrderTitle">تفاصيل الطلب</h3>
                <button class="modal-close" onclick="closeOrderModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="modalOrderBody">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>
    
    <script>
        const API_BASE = window.location.origin;
        const CURRENT_EMAIL = "<?php echo e(Auth::user()->email ?? ''); ?>";
        let ordersData = [];

        function setCurrencyPreferenceUI(currency) {
            const cur = (String(currency || '').toUpperCase() === 'SYP') ? 'SYP' : 'USD';
            const prefInput = document.getElementById('currencyPref');
            if (prefInput) prefInput.value = cur;
            document.getElementById('currencyBtnUSD')?.classList.toggle('active', cur === 'USD');
            document.getElementById('currencyBtnSYP')?.classList.toggle('active', cur === 'SYP');
            if (window.setCurrencyPreference) window.setCurrencyPreference(cur);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const initial = (window.getCurrencyPreference ? window.getCurrencyPreference() : (document.getElementById('currencyPref')?.value || 'USD'));
            setCurrencyPreferenceUI(initial);
        });
        
        function toggleSidebar() {
            const sidebar = document.getElementById('profileSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        function showSection(section) {
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.profile-nav-item').forEach(n => n.classList.remove('active'));
            
            const targetSection = document.getElementById('section-' + section);
            if (targetSection) {
                targetSection.classList.add('active');
            }
            
            // Highlight the clicked nav item
            const navItems = document.querySelectorAll('.profile-nav-item');
            navItems.forEach(item => {
                if (item.getAttribute('onclick')?.includes(`'${section}'`)) {
                    item.classList.add('active');
                }
            });

            // Close sidebar on mobile after selecting a section
            if (window.innerWidth <= 992) {
                const sidebar = document.getElementById('profileSidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
            
            if (section === 'addresses') loadAddresses();
            if (section === 'orders') loadOrders();
            if (section === 'cards') loadCards();
            if (section === 'notifications') loadNotifications();
        }

        async function loadAddresses() {
            const container = document.getElementById('addressesList');
            try {
                const response = await fetch(API_BASE + '/profile/orders', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await response.json();
                const orders = data.orders || [];
                
                // Extract unique addresses from orders
                const addresses = [...new Set(orders.map(o => o.address).filter(a => a))];
                
                if (addresses.length > 0) {
                    container.innerHTML = addresses.map(addr => `
                        <div style="background:white; padding:1.2rem; border-radius:12px; margin-bottom:1rem; border:1px solid #eee; display:flex; align-items:center; gap:1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                            <div style="width:40px; height:40px; background:#e8f4f8; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#2a7080;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div style="flex:1;">
                                <p style="font-family:'El Messiri',sans-serif; font-weight:600; color:#1a1a1a; margin:0;">${addr}</p>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-map-marker-alt"></i><p>لا توجد عناوين مسجلة بعد</p></div>';
                }
            } catch (error) {
                container.innerHTML = '<div class="empty-state"><i class="fas fa-map-marker-alt"></i><p>لا توجد عناوين مسجلة بعد</p></div>';
            }
        }

        async function loadCards() {
            const container = document.getElementById('cardsList');
            try {
                const response = await fetch(API_BASE + '/api/user/saved-cards', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const cards = await response.json();
                
                if (cards && cards.length > 0) {
                    container.innerHTML = cards.map(card => `
                        <div style="background:linear-gradient(135deg, #2a7080 0%, #1a5060 100%); padding:1.5rem; border-radius:15px; margin-bottom:1rem; color:white; display:flex; align-items:center; justify-content:space-between; box-shadow: 0 4px 15px rgba(42,112,128,0.2);">
                            <div style="display:flex; align-items:center; gap:1.2rem;">
                                <i class="fab fa-cc-${card.type || 'visa'}" style="font-size:2.5rem; opacity:0.9;"></i>
                                <div>
                                    <p style="font-family:monospace; font-size:1.1rem; letter-spacing:2px; margin:0;">•••• •••• •••• ${card.last4}</p>
                                    <p style="font-family:'El Messiri',sans-serif; font-size:0.85rem; opacity:0.8; margin-top:0.3rem;">ينتهي في ${card.expiry}</p>
                                </div>
                            </div>
                            <i class="fas fa-shield-alt" style="font-size:1.2rem; opacity:0.5;"></i>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-credit-card"></i><p>لا توجد بطاقات محفوظة بعد</p></div>';
                }
            } catch (error) {
                container.innerHTML = '<div class="empty-state"><i class="fas fa-credit-card"></i><p>لا توجد بطاقات محفوظة بعد</p></div>';
            }
        }
        
        async function saveProfile(e) {
            e.preventDefault();
            const btn = e.target.querySelector('.btn-save');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            btn.disabled = true;
            
            try {
                const payload = {
                    name: document.getElementById('fullName').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    address: document.getElementById('address').value,
                    currency: (document.getElementById('currencyPref')?.value || (window.getCurrencyPreference ? window.getCurrencyPreference() : 'USD'))
                };

                // If email changed, request verification and redirect to code page
                if (payload.email && payload.email !== CURRENT_EMAIL) {
                    const req = await fetch(API_BASE + '/profile/email/verify-request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ new_email: payload.email })
                    });
                    const reqData = await req.json();
                    if (!req.ok || !reqData.success) { throw new Error(reqData.message || 'فشل إرسال رمز التحقق'); }

                    // Persist pending updates (excluding email) and redirect to code page
                    sessionStorage.setItem('email_change:new_email', payload.email);
                    sessionStorage.setItem('profile_pending_update', JSON.stringify({
                        name: payload.name,
                        phone: payload.phone,
                        address: payload.address
                    }));
                    window.location.href = '/ar-verify-code?target=email-change';
                    return;
                }

                // Save other fields
                const response = await fetch(API_BASE + '/profile/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: payload.name,
                        phone: payload.phone,
                        address: payload.address,
                        currency: payload.currency
                    })
                });
                if (!response.ok) {
                    let msg = 'فشل حفظ البيانات';
                    try { const err = await response.json(); msg = err.message || msg; } catch (_) {}
                    throw new Error(msg);
                }

                btn.innerHTML = '<i class="fas fa-check"></i> تم الحفظ';
                btn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                document.getElementById('userName').textContent = payload.name;
                // Auto refresh to reflect latest changes
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            } catch (error) {
                btn.innerHTML = '<i class="fas fa-times"></i> فشل الحفظ';
                btn.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }
        }
        
        async function loadOrders() {
            const container = document.getElementById('ordersList');
            try {
                const response = await fetch(API_BASE + '/profile/orders', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                ordersData = data.orders || [];
                
                if (ordersData.length > 0) {
                    container.innerHTML = ordersData.map((order, index) => `
                        <div class="order-card" onclick="showOrderDetails(${index})">
                            <div class="order-header">
                                <div>
                                    <span class="order-id">طلب #${order.order_number || order.id}</span>
                                    <span class="order-date">${new Date(order.created_at).toLocaleDateString('ar-SY')}</span>
                                </div>
                                <span class="order-status status-${order.status}">${getStatusText(order.status)}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span>${order.items_count || 0} منتجات</span>
                                <span class="order-total">${window.formatMoney ? window.formatMoney(order.total) : (parseFloat(order.total).toFixed(2) + ' $')}</span>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-shopping-bag"></i><p>لا توجد طلبات بعد</p></div>';
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                container.innerHTML = '<div class="empty-state"><i class="fas fa-shopping-bag"></i><p>لا توجد طلبات بعد</p></div>';
            }
        }
        
        function showOrderDetails(index) {
            const order = ordersData[index];
            if (!order) return;
            
            document.getElementById('modalOrderTitle').textContent = `طلب #${order.order_number || order.id}`;
            document.getElementById('modalOrderBody').innerHTML = `
                <div class="order-detail-row">
                    <span class="detail-label">رقم الطلب</span>
                    <span class="detail-value">#${order.order_number || order.id}</span>
                </div>
                <div class="order-detail-row">
                    <span class="detail-label">التاريخ</span>
                    <span class="detail-value">${new Date(order.created_at).toLocaleDateString('ar-SY', {year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</span>
                </div>
                <div class="order-detail-row">
                    <span class="detail-label">الحالة</span>
                    <span class="order-status status-${order.status}">${getStatusText(order.status)}</span>
                </div>
                <div class="order-detail-row">
                    <span class="detail-label">طريقة الدفع</span>
                    <span class="detail-value">${getPaymentMethod(order.payment_method)}</span>
                </div>
                <div class="order-detail-row">
                    <span class="detail-label">العنوان</span>
                    <span class="detail-value">${order.address || 'غير محدد'}</span>
                </div>
                
                <h4 class="order-items-title"><i class="fas fa-box"></i> المنتجات (${order.items_count || 0})</h4>
                ${order.items && order.items.length > 0 ? order.items.map(item => `
                    <div class="order-item">
                        <div class="order-item-img">
                            ${item.product_image ? `<img src="${item.product_image}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;" onerror="this.src='/images/gift-placeholder.svg'">` : '📦'}
                        </div>
                        <div class="order-item-info">
                            <div class="order-item-name">${item.product_name}</div>
                            <div class="order-item-qty">الكمية: ${item.quantity}</div>
                        </div>
                        <div class="order-item-price">${window.formatMoney ? window.formatMoney(item.subtotal) : (parseFloat(item.subtotal).toFixed(2) + ' $')}</div>
                    </div>
                `).join('') : '<p style="color:#888;text-align:center;padding:1rem;">لا توجد تفاصيل للمنتجات</p>'}
                
                <div style="margin-top:1.5rem;padding-top:1rem;border-top:2px solid #f0f0f0;">
                    <div class="order-detail-row">
                        <span class="detail-label">المجموع الفرعي</span>
                        <span class="detail-value">${window.formatMoney ? window.formatMoney(order.subtotal || order.total) : (parseFloat(order.subtotal || order.total).toFixed(2) + ' $')}</span>
                    </div>
                    <div class="order-detail-row">
                        <span class="detail-label">التوصيل</span>
                        <span class="detail-value">${window.formatMoney ? window.formatMoney(order.delivery_cost || 0) : (parseFloat(order.delivery_cost || 0).toFixed(2) + ' $')}</span>
                    </div>
                    <div class="order-detail-row" style="font-size:1.2rem;">
                        <span class="detail-label" style="font-weight:700;">الإجمالي</span>
                        <span class="detail-value" style="color:#ff6b35;font-weight:700;">${window.formatMoney ? window.formatMoney(order.total) : (parseFloat(order.total).toFixed(2) + ' $')}</span>
                    </div>
                </div>
            `;
            document.getElementById('orderModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeOrderModal() {
            document.getElementById('orderModal').classList.remove('active');
            document.body.style.overflow = '';
        }
        
        function getPaymentMethod(method) {
            const methods = {
                'cash': 'الدفع عند الاستلام',
                'card': 'بطاقة ائتمان',
                'credit_card': 'بطاقة ائتمان',
                'payroll': 'Payroll'
            };
            return methods[method] || 'Unknown';
        }
        
        function getStatusText(status) {
            const statuses = {
                'pending': 'قيد الانتظار',
                'confirmed': 'مؤكد',
                'processing': 'قيد المعالجة',
                'ready': 'جاهز',
                'out_for_delivery': 'خرج للتوصيل',
                'delivered': 'تم التسليم',
                'done': 'مكتمل',
                'cancelled': 'ملغي',
                'failed': 'فشل',
                'refunded': 'مسترجع',
                'returned': 'مرتجع'
            };
            return statuses[status] || status;
        }
        
        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeOrderModal();
        });
        
        async function loadNotifications() {
            const container = document.getElementById('notificationsList');
            try {
                const response = await fetch(API_BASE + '/profile/notifications', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                
                if (data.notifications && data.notifications.length > 0) {
                    container.innerHTML = data.notifications.map(notif => `
                        <div class="notification-item ${notif.read ? '' : 'unread'}">
                            <div class="notification-icon ${notif.type || 'system'}">
                                <i class="fas fa-${getNotifIcon(notif.type)}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">${notif.title}</div>
                                <div class="notification-text">${notif.message || ''}</div>
                                <div class="notification-time">${timeAgo(notif.created_at)}</div>
                            </div>
                        </div>
                    `).join('');
                    
                    // Update badge
                    if (data.unread_count > 0) {
                        document.getElementById('notifBadge').style.display = 'inline';
                        document.getElementById('notifBadge').textContent = data.unread_count;
                    }
                } else {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-bell-slash"></i><p>لا توجد إشعارات</p></div>';
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
                container.innerHTML = '<div class="empty-state"><i class="fas fa-bell-slash"></i><p>لا توجد إشعارات</p></div>';
            }
        }
        
        function getNotifIcon(type) {
            const icons = { 'order': 'shopping-bag', 'promo': 'tag', 'system': 'info-circle' };
            return icons[type] || 'bell';
        }
        
        function timeAgo(date) {
            const seconds = Math.floor((new Date() - new Date(date)) / 1000);
            if (seconds < 60) return 'الآن';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' دقيقة';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' ساعة';
            return Math.floor(seconds / 86400) + ' يوم';
        }

        async function loadAddresses() {
            const container = document.getElementById('addressesList');
            try {
                const response = await fetch('/api/addresses', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                const items = data.items || [];
                if (items.length === 0) {
                    container.innerHTML = '<div class="empty-state"><i class="fas fa-map-marker-alt"></i><p>لا توجد عناوين محفوظة</p></div>';
                    return;
                }

                container.innerHTML = items.map(a => `
                    <div style="background:#f8f9fa;border:1px solid #e8e8e8;border-radius:12px;padding:1rem;margin-bottom:0.8rem;display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;">
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                                <div style="font-weight:800;color:#0f4f55;">${a.label || 'عنوان'}</div>
                                ${a.is_default ? '<span style="background:#d4edda;color:#155724;padding:0.15rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">افتراضي</span>' : ''}
                            </div>
                            <div style="color:#333;margin-top:0.35rem;">${a.line1 || ''}</div>
                            ${a.line2 ? `<div style="color:#666;margin-top:0.2rem;font-size:0.9rem;">${a.line2}</div>` : ''}
                            ${a.phone ? `<div style="color:#666;margin-top:0.2rem;font-size:0.9rem;"><i class="fas fa-phone"></i> ${a.phone}</div>` : ''}
                        </div>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            ${a.is_default ? '' : `<button type="button" onclick="setDefaultAddress(${a.id})" style="background:#2a7080;color:#fff;border:none;padding:0.5rem 0.8rem;border-radius:10px;font-family:'El Messiri',sans-serif;font-weight:700;cursor:pointer;">تعيين افتراضي</button>`}
                            <button type="button" onclick="deleteAddress(${a.id})" style="background:#dc3545;color:#fff;border:none;padding:0.5rem 0.8rem;border-radius:10px;font-family:'El Messiri',sans-serif;font-weight:700;cursor:pointer;">حذف</button>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                container.innerHTML = '<div class="empty-state"><i class="fas fa-map-marker-alt"></i><p>تعذر تحميل العناوين</p></div>';
            }
        }

        async function saveAddress(e) {
            e.preventDefault();
            const btn = e.target.querySelector('.btn-save');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/addresses', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        label: document.getElementById('addrLabel').value || null,
                        phone: document.getElementById('addrPhone').value || null,
                        line1: document.getElementById('addrLine1').value,
                        line2: document.getElementById('addrLine2').value || null
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed');
                }

                document.getElementById('addrLabel').value = '';
                document.getElementById('addrPhone').value = '';
                document.getElementById('addrLine1').value = '';
                document.getElementById('addrLine2').value = '';

                await loadAddresses();
                btn.innerHTML = '<i class="fas fa-check"></i> تم الحفظ';
                btn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 1500);
            } catch (error) {
                btn.innerHTML = '<i class="fas fa-times"></i> فشل الحفظ';
                btn.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 1500);
            }
        }

        async function setDefaultAddress(id) {
            await fetch(`/api/addresses/${id}/default`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            loadAddresses();
        }

        async function deleteAddress(id) {
            await fetch(`/api/addresses/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            loadAddresses();
        }

        async function changePassword(e) {
            e.preventDefault();
            const btn = e.target.querySelector('.btn-save');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            btn.disabled = true;

            try {
                const response = await fetch('/profile/password', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: e.target.querySelector('#currentPassword').value,
                        new_password: e.target.querySelector('#newPassword').value,
                        new_password_confirmation: e.target.querySelector('#newPasswordConfirm').value
                    })
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok || data.success === false) {
                    throw new Error(data.message || 'Failed');
                }

                document.getElementById('currentPassword').value = '';
                document.getElementById('newPassword').value = '';
                document.getElementById('newPasswordConfirm').value = '';

                btn.innerHTML = '<i class="fas fa-check"></i> تم التغيير';
                btn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 1500);
            } catch (error) {
                btn.innerHTML = '<i class="fas fa-times"></i> فشل التغيير';
                btn.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 1500);
            }
        }

        // Toggle Password Visibility
         document.addEventListener('DOMContentLoaded', function() {
              // Other password toggles
               document.querySelectorAll('.toggle-password').forEach(btn => {
                  btn.addEventListener('click', function() {
                      const input = this.parentElement.querySelector('input');
                      if (input) {
                          const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                          input.setAttribute('type', type);
                          this.classList.toggle('fa-eye');
                          this.classList.toggle('fa-eye-slash');
                      }
                  });
              });
          });
    </script>
</body>
</html>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/profile.blade.php ENDPATH**/ ?>