<nav class="tulip-navbar">
  <div class="navbar-wrapper">
    
    <!-- Icons LEFT -->
    <div class="navbar-icons">
      
      <!-- Favorites Icon -->
      <div class="nav-icon-item" onclick="window.location.href='/favorites'">
        <i class="fas fa-heart icon-favorite"></i>
        <span class="icon-label favorite-label">
          <i class="fas fa-heart"></i>
          <span class="label-badge" id="favoritesCount">0</span>
          <span>المفضلة</span>
        </span>
      </div>

      <!-- User Icon -->
      <?php if(auth()->guard()->check()): ?>
      <div class="nav-icon-item user-logged-in" id="userMenu">
        <span class="user-name-pill" style="position:relative">
          <?php echo e(is_array(data_get(Auth::user(),'name')) ? json_encode(data_get(Auth::user(),'name')) : data_get(Auth::user(),'name')); ?>

          <span id="notificationBadge" style="display:none;position:absolute;top:-5px;right:-5px;width:10px;height:10px;background:#ff6b35;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 8px rgba(255,107,53,0.4)"></span>
        </span>
        
        <!-- User Dropdown -->
        <div class="user-dropdown" id="userDropdown">
          <div class="dropdown-item" onclick="window.location.href='/profile'">
            <i class="fas fa-user-circle"></i>
            <span>الملف الشخصي</span>
          </div>

          <div class="dropdown-item" onclick="window.location.href='/my-orders'">
            <i class="fas fa-shopping-bag"></i>
            <span>طلباتي</span>
          </div>

          <div class="dropdown-item logout-item" onclick="handleLogout()">
            <i class="fas fa-sign-out-alt"></i>
            <span>تسجيل خروج</span>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="nav-icon-item" onclick="window.location.href='/ar-login'">
        <i class="fas fa-user icon-user"></i>
        <span class="icon-label user-label">
          <i class="fas fa-user"></i>
          <span>تسجيل الدخول</span>
        </span>
      </div>
      <?php endif; ?>

      <!-- Cart Icon -->
      <div class="nav-icon-item cart-icon-container" onclick="window.location.href='/cart'">
        <i class="fas fa-shopping-cart icon-cart"></i>
        <span class="cart-badge cart-badge-icon" id="cartBadge" style="display: none;">0</span>
        <span class="icon-label cart-label">
          <i class="fas fa-shopping-cart"></i>
          <span class="cart-badge cart-badge-label" id="cartBadgeLabel" style="display: none;">0</span>
          <span>سلة التسوق</span>
        </span>
      </div>

      <!-- Gift Icon -->
      <div class="nav-icon-item" onclick="window.location.href='/gifts'">
        <i class="fas fa-gift icon-gift"></i>
        <span class="icon-label gift-label">
          <i class="fas fa-gift"></i>
          <span>هدايا توليب</span>
        </span>
      </div>

      <!-- Tulip Mart Icon (Main Store) -->
      <div class="nav-icon-item" onclick="window.location.href='/mart'">
        <i class="fas fa-store icon-store"></i>
        <span class="icon-label store-label">
          <i class="fas fa-store"></i>
          <span>توليب مارت</span>
        </span>
      </div>

    </div>

    <!-- Search CENTER -->
    <div class="navbar-search-wrapper">
      <div class="navbar-search" id="searchBar">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="ابحث عن المنتج الذي تريده">
        <i class="fas fa-bars" id="menuIcon"></i>
      </div>

      <!-- Search Dropdown -->
      <div class="search-dropdown" id="searchDropdown">
        <div class="search-dropdown-header">
          <span id="dropdownTitle">أكثر ما تم البحث عنه مؤخراً</span>
        </div>
        
        <!-- Recent Searches -->
        <div class="search-chips" id="recentChips">
          <button class="search-chip">تنسيق الهدايا والحفلات</button>
          <button class="search-chip">هدايا أطفال</button>
          <button class="search-chip">سلة ورد</button>
          <button class="search-chip">سلة فواكه</button>
          <button class="search-chip">عطور</button>
          <button class="search-chip">شوكولاتة</button>
        </div>

        <!-- Search Results -->
        <div class="search-results" id="searchResults" style="display: none;"></div>
      </div>
    </div>

    <!-- Logo RIGHT -->
    <a href="/" class="navbar-logo">
      <span class="logo-lip">LIP</span>
      <img src="/images/logo-girl.jpg" alt="U" class="logo-girl">
      <span class="logo-t">T</span>
    </a>

  </div>
</nav>


<script>
(function () {
  const USD_TO_SYP = 117;
  const serverCurrency = <?php echo json_encode(auth()->check() ? (strtoupper((string) (auth()->user()->currency ?: 'USD'))) : (strtoupper((string) (session('currency') ?: 'USD'))), 15, 512) ?>;
  const safeServerCurrency = (serverCurrency === 'SYP' || serverCurrency === 'USD') ? serverCurrency : 'USD';
  let preferred = safeServerCurrency;

  try {
    const stored = (localStorage.getItem('tulip_currency') || '').toUpperCase();
    if (stored === 'USD' || stored === 'SYP') preferred = stored;
  } catch (_) {}

  window.TULIP_USD_TO_SYP = USD_TO_SYP;
  window.getCurrencyPreference = function () {
    return preferred;
  };

  window.setCurrencyPreference = async function (currency) {
    const cur = (String(currency || '').toUpperCase() === 'SYP') ? 'SYP' : 'USD';
    preferred = cur;
    try { localStorage.setItem('tulip_currency', cur); } catch (_) {}

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    if (csrf && <?php echo json_encode(auth()->check(), 15, 512) ?>) {
      try {
        await fetch('/profile/update', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ currency: cur })
        });
      } catch (_) {}
    }
  };

  window.formatMoney = function (amountUsd) {
    const n = Number(amountUsd || 0);
    if (preferred === 'SYP') {
      const syp = Math.round(n * USD_TO_SYP);
      return syp.toLocaleString() + ' SYP';
    }
    return '$' + n.toFixed(2);
  };

  window.formatDualMoney = function (amountUsd) {
    const n = Number(amountUsd || 0);
    const usd = '$' + n.toFixed(2);
    const syp = Math.round(n * USD_TO_SYP).toLocaleString() + ' SYP';
    return `${usd} • ${syp}`;
  };
})();

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');
    const dropdownTitle = document.getElementById('dropdownTitle');
    const recentChips = document.getElementById('recentChips');
    const searchResults = document.getElementById('searchResults');
    const menuIcon = document.getElementById('menuIcon');
    const searchBar = document.getElementById('searchBar');

    let searchTimeout;

    // Show dropdown on focus
    searchInput.addEventListener('focus', function() {
        searchDropdown.classList.add('active');
        showRecent();
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchBar.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.remove('active');
        }
    });

    // Search on input
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length === 0) {
            showRecent();
            return;
        }

        if (query.length < 2) {
            return;
        }

        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    const isMart = window.location.pathname.startsWith('/mart');
    const market = isMart ? 'mart' : 'store';
    const searchRedirectBase = isMart ? '/mart/products' : '/store';

    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = this.value.trim();
            if (query.length >= 2) {
                window.location.href = searchRedirectBase + '?search=' + encodeURIComponent(query);
            }
        }
    });
    
    // Search icon click handler
    const searchIcon = document.querySelector('.navbar-search .fa-search');
    if (searchIcon) {
        searchIcon.style.cursor = 'pointer';
        searchIcon.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                window.location.href = searchRedirectBase + '?search=' + encodeURIComponent(query);
            }
        });
    }

    // Chip click
    document.querySelectorAll('.search-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const text = this.textContent.trim();
            searchInput.value = text;
            performSearch(text);
        });
    });

    // Menu icon click - show categories
    menuIcon.addEventListener('click', function() {
        searchDropdown.classList.add('active');
        showCategories();
    });

    function showRecent() {
        dropdownTitle.textContent = 'أكثر ما تم البحث عنه مؤخراً';
        recentChips.style.display = 'flex';
        searchResults.style.display = 'none';
    }

    function showCategories() {
        dropdownTitle.textContent = 'الأقسام الرئيسية';
        recentChips.style.display = 'none';
        searchResults.style.display = 'flex';
        searchResults.innerHTML = '<div style="text-align:center;color:#999;">جاري التحميل...</div>';

        // Category icons mapping
        const categoryIcons = {
            'gift': 'fa-gift', 'هدايا': 'fa-gift', 'هدية': 'fa-gift',
            'flower': 'fa-rose', 'ورود': 'fa-rose', 'وردة': 'fa-rose', 'ورد': 'fa-rose',
            'chocolate': 'fa-candy-cane', 'شوكولاتة': 'fa-candy-cane', 'شوكولا': 'fa-candy-cane',
            'kid': 'fa-baby', 'أطفال': 'fa-baby', 'طفل': 'fa-baby',
            'bouquet': 'fa-spa', 'باقات': 'fa-spa', 'باقة': 'fa-spa',
            'party': 'fa-birthday-cake', 'حفلات': 'fa-birthday-cake', 'حفلة': 'fa-birthday-cake',
            'decor': 'fa-palette', 'ديكور': 'fa-palette',
            'accessor': 'fa-gem', 'إكسسوارات': 'fa-gem', 'اكسسوار': 'fa-gem',
            'toy': 'fa-gamepad', 'ألعاب': 'fa-gamepad', 'لعبة': 'fa-gamepad',
            'book': 'fa-book', 'كتب': 'fa-book', 'كتاب': 'fa-book',
            'cloth': 'fa-tshirt', 'ملابس': 'fa-tshirt',
            'fruit': 'fa-apple-alt', 'فواكه': 'fa-apple-alt', 'فاكهة': 'fa-apple-alt',
            'perfume': 'fa-spray-can', 'عطور': 'fa-spray-can', 'عطر': 'fa-spray-can',
            'event': 'fa-masks-theater', 'مناسبات': 'fa-masks-theater', 'مناسبة': 'fa-masks-theater'
        };

        function getCategoryIcon(name, slug) {
            const lowerName = (name || '').toLowerCase();
            const lowerSlug = (slug || '').toLowerCase();
            
            // Check name and slug
            for (const [key, icon] of Object.entries(categoryIcons)) {
                if (lowerName.includes(key) || lowerSlug.includes(key)) {
                    return icon;
                }
            }
            
            // Default icon
            return 'fa-folder';
        }

        fetch(`/api/categories?market=${encodeURIComponent(market)}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(payload => {
                const categories = Array.isArray(payload) ? payload : (payload.data || []);
                if (!Array.isArray(categories) || categories.length === 0) {
                    searchResults.innerHTML = '<div style="text-align:center;color:#999;">لا توجد أقسام</div>';
                    return;
                }
                searchResults.innerHTML = categories.map(cat => {
                    const icon = getCategoryIcon(cat.name, cat.slug || '');
                    return `
                        <div class="search-result-item" onclick="window.location.href='/category/' + encodeURIComponent(cat.slug)">
                            <i class="fas ${icon} search-result-icon"></i>
                            <div class="search-result-info">
                                <div class="search-result-name">${cat.name}</div>
                            </div>
                        </div>
                    `;
                }).join('');
            })
            .catch(() => {
                searchResults.innerHTML = '<div style="text-align:center;color:#e74c3c;">حدث خطأ</div>';
            });
    }

    function performSearch(query) {
        dropdownTitle.textContent = 'نتائج البحث';
        recentChips.style.display = 'none';
        searchResults.style.display = 'flex';
        searchResults.innerHTML = '<div style="text-align:center;color:#999;">جاري البحث...</div>';

        fetch(`/api/products/search?market=${encodeURIComponent(market)}&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                const raw = Array.isArray(data.data) ? data.data : [];
                const products = Array.from(new Map(raw.map(p => [String(p?.id ?? ''), p])).values()).filter(p => p && p.id);
                
                if (products.length === 0) {
                    searchResults.innerHTML = '<div style="text-align:center;color:#999;">لا توجد نتائج</div>';
                    return;
                }

                searchResults.innerHTML = products.map(product => `
                    <div class="search-result-item" onclick="window.location.href='/products/${product.id}'">
                        <i class="fas fa-search search-result-icon"></i>
                        <div class="search-result-info">
                            <div class="search-result-name">${product.name}</div>
                            <div class="search-result-price">${product.price} ل.س</div>
                        </div>
                        <img src="${product.primary_image_url || product.image || (Array.isArray(product.images) ? product.images[0] : null) || '/images/gift-placeholder.svg'}" class="search-result-img" alt="${product.name}" loading="lazy" onerror="this.src='/images/gift-placeholder.svg'">
                    </div>
                `).join('');
            })
            .catch(err => {
                console.error(err);
                searchResults.innerHTML = '<div style="text-align:center;color:#e74c3c;">حدث خطأ في البحث</div>';
            });
    }
});

// User dropdown toggle
<?php if(auth()->guard()->check()): ?>
const userMenu = document.getElementById('userMenu');
const userDropdown = document.getElementById('userDropdown');

if (userMenu && userDropdown) {
    userMenu.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!userMenu.contains(e.target)) {
            userDropdown.classList.remove('show');
        }
    });
}

// Logout function
function handleLogout() {
    if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        }).then(() => {
            window.location.href = '/ar-login';
        }).catch(() => {
            window.location.href = '/ar-login';
        });
    }
}

// Initialize language on page load
const currentLang = document.documentElement.getAttribute('lang') || 'ar';
localStorage.setItem('language', currentLang);

// Update active language indicator
function updateLanguageIndicator() {
    const lang = localStorage.getItem('language') || 'ar';
    document.querySelectorAll('.lang-option').forEach(option => {
        const check = option.querySelector('.lang-check');
        if (option.dataset.lang === lang) {
            option.classList.add('active-lang');
            if (check) check.style.display = 'block';
        } else {
            option.classList.remove('active-lang');
            if (check) check.style.display = 'none';
        }
    });
}

// Call on page load
updateLanguageIndicator();

// Update favorites count on page load
function updateFavoritesCount() {
    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    const countElement = document.getElementById('favoritesCount');
    if (countElement) {
        countElement.textContent = favorites.length > 99 ? '+99' : favorites.length;
    }
}

// Update favorites count on page load
window.addEventListener('DOMContentLoaded', updateFavoritesCount);

// Change language function
function changeLanguage(lang) {
    // Store language preference
    localStorage.setItem('language', lang);
    
    // Update page direction
    const html = document.documentElement;
    const body = document.body;
    
    if (lang === 'ar') {
        html.setAttribute('lang', 'ar');
        html.setAttribute('dir', 'rtl');
    } else {
        html.setAttribute('lang', 'en');
        html.setAttribute('dir', 'ltr');
    }
    
    // Keep navbar and dropdown always RTL
    const navbar = document.querySelector('.tulip-navbar');
    const dropdown = document.querySelector('.user-dropdown');
    if (navbar) navbar.style.direction = 'rtl';
    if (dropdown) dropdown.style.direction = 'rtl';
    
    // Translate navbar content
    translateNavbar(lang);
    
    // Update language indicator
    updateLanguageIndicator();
    
    // Send to backend to save preference
    fetch('/api/user/language', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ language: lang })
    }).catch(err => console.log('Language preference saved locally'));
}

// Dashboard submenu functionality
document.addEventListener('DOMContentLoaded', function() {
    const dashboardItem = document.getElementById('dashboardItem');
    const dashboardSubmenu = document.getElementById('dashboardSubmenu');
    
    if (dashboardItem && dashboardSubmenu) {
        dashboardItem.addEventListener('mouseenter', function() {
            dashboardSubmenu.style.display = 'block';
        });
        
        dashboardItem.addEventListener('mouseleave', function() {
            setTimeout(() => {
                if (!dashboardSubmenu.matches(':hover')) {
                    dashboardSubmenu.style.display = 'none';
                }
            }, 100);
        });
        
        dashboardSubmenu.addEventListener('mouseleave', function() {
            dashboardSubmenu.style.display = 'none';
        });
    }
});

// Translate navbar
function translateNavbar(lang) {
    const translations = {
        ar: {
            search: 'ابحث عن المنتج الذي تريده',
            gifts: 'هدايا توليب',
            store: 'توليب مارت',
            cart: 'سلة التسوق',
            login: 'تسجيل الدخول',
            settings: 'الإعدادات',
            nightMode: 'الوضع الليلي',
            language: 'اللغة',
            orders: 'طلباتي',
            notifications: 'الإشعارات',
            controlPanel: 'لوحة التحكم',
            myStore: 'متجري',
            logout: 'تسجيل خروج'
        },
        en: {
            search: 'Search for the product you want',
            gifts: 'Tulip Gifts',
            store: 'Tulip Mart',
            cart: 'Shopping Cart',
            login: 'Login',
            settings: 'Settings',
            nightMode: 'Night Mode',
            language: 'Language',
            orders: 'My Orders',
            notifications: 'Notifications',
            controlPanel: 'Control Panel',
            myStore: 'My Store',
            logout: 'Logout'
        }
    };
    
    const t = translations[lang];
    
    // Update search placeholder
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.placeholder = t.search;
    
    // Update dropdown items - skip submenu items
    const dropdownItems = document.querySelectorAll('.user-dropdown > .dropdown-item:not(.has-submenu) > span');
    const mainItems = [t.settings, t.nightMode, null, t.orders, t.notifications, t.controlPanel, t.myStore, t.logout];
    
    dropdownItems.forEach((span, index) => {
        if (mainItems[index]) {
            span.textContent = mainItems[index];
        }
    });
    
    // Update language item text specifically
    const langItem = document.querySelector('.dropdown-item.has-submenu > span');
    if (langItem) langItem.textContent = t.language;
    
    // Update icon labels
    const giftLabel = document.querySelector('.gift-label > span:last-child');
    if (giftLabel) giftLabel.textContent = t.gifts;
    
    const storeLabel = document.querySelector('.store-label > span:last-child');
    if (storeLabel) storeLabel.textContent = t.store;
    
    const cartLabel = document.querySelector('.cart-label > span:last-child');
    if (cartLabel) cartLabel.textContent = t.cart;
    
    const loginLabel = document.querySelector('.user-label > span:last-child');
    if (loginLabel) loginLabel.textContent = t.login;
}
<?php endif; ?>

// Global function to update cart count (can be called from any page)
window.updateCartCount = function(count) {
    console.log('Updating cart count to:', count);
    const cartBadge = document.getElementById('cartBadge');
    const cartBadgeLabel = document.getElementById('cartBadgeLabel');
    
    // Ensure count is a number
    const numCount = parseInt(count) || 0;
    const displayText = numCount > 99 ? '99+' : numCount;
    
    // Update icon badge (visible before hover)
    if (cartBadge) {
        if (numCount > 0) {
            cartBadge.textContent = displayText;
            cartBadge.style.display = 'flex';
        } else {
            cartBadge.style.display = 'none';
        }
    }
    
    // Update label badge (visible during hover)
    if (cartBadgeLabel) {
        if (numCount > 0) {
            cartBadgeLabel.textContent = displayText;
            cartBadgeLabel.style.display = 'inline-flex';
        } else {
            cartBadgeLabel.style.display = 'none';
        }
    }
};

// Global function to animate cart icon when item is added
window.animateCartIcon = function() {
    const cartContainer = document.querySelector('.cart-icon-container');
    const cartIcon = document.querySelector('.icon-cart');
    const cartBadge = document.getElementById('cartBadge');
    
    if (cartContainer) {
        // Add bounce animation class
        cartContainer.style.animation = 'cartBounce 0.6s ease';
        
        // Remove animation after it completes
        setTimeout(() => {
            cartContainer.style.animation = '';
        }, 600);
    }
    
    if (cartIcon) {
        // Scale and rotate animation
        cartIcon.style.transition = 'transform 0.1s ease';
        cartIcon.style.transform = 'scale(1.4) rotate(-15deg)';
        
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1.3) rotate(15deg)';
        }, 100);
        
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1.2) rotate(-8deg)';
        }, 200);
        
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1.1) rotate(8deg)';
        }, 300);
        
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1) rotate(0deg)';
        }, 400);
    }
    
    // Animate badge
    if (cartBadge) {
        cartBadge.style.transition = 'transform 0.3s ease';
        cartBadge.style.transform = 'scale(1.6)';
        setTimeout(() => {
            cartBadge.style.transform = 'scale(1)';
        }, 300);
    }
};

// Global toast notification function
window.showToast = function(message, duration = 2500) {
    // Remove existing toast
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: linear-gradient(135deg, #2a7080, #1a5060);
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 9999;
        opacity: 0;
        transition: all 0.3s ease;
        font-weight: 500;
        font-family: "Montserrat-Alt", sans-serif;
    `;
    toast.innerHTML = `
        <span>${message}</span>
    `;
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.style.transform = 'translateX(-50%) translateY(0)';
        toast.style.opacity = '1';
    }, 10);
    
    // Remove after duration
    setTimeout(() => {
        toast.style.transform = 'translateX(-50%) translateY(100px)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

// Load cart count on page load
async function loadNavbarCartCount() {
    try {
        const response = await fetch('/api/cart');
        const data = await response.json();
        console.log('Cart data from API:', data);
        window.updateCartCount(data.count || 0);
    } catch (error) {
        console.error('Error loading cart count:', error);
    }
}

// Load cart count when page loads
window.addEventListener('DOMContentLoaded', loadNavbarCartCount);
</script>

<script>
<?php if(auth()->guard()->check()): ?>
window.addEventListener('DOMContentLoaded', function() {
    fetch('/api/wishlist')
        .then(r => r.json())
        .then(d => {
            const countElement = document.getElementById('favoritesCount');
            if (countElement) {
                const c = d.count || (Array.isArray(d.items) ? d.items.length : 0) || 0;
                countElement.textContent = c > 99 ? '+99' : c;
            }
        })
        .catch(() => {});
});
<?php endif; ?>
</script>


<script>
// Check for unread notifications on page load
function checkUnreadNotifications() {
    fetch('/api/notifications/unread-count')
        .then(r => r.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            const dropdownBadge = document.getElementById('notificationDropdownBadge');
            
            if (data.count > 0) {
                if (badge) badge.style.display = 'block';
                if (dropdownBadge) dropdownBadge.style.display = 'block';
            } else {
                if (badge) badge.style.display = 'none';
                if (dropdownBadge) dropdownBadge.style.display = 'none';
            }
        })
        .catch(err => console.log('Could not fetch notifications'));
}

// Check on page load
if (document.getElementById('notificationBadge')) {
    checkUnreadNotifications();
    // Check every 30 seconds
    setInterval(checkUnreadNotifications, 30000);
}
</script>
<?php /**PATH C:\Users\Doaa\StudioProjects\Tulip-Store\resources\views/components/navbar.blade.php ENDPATH**/ ?>