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
      @auth
      <div class="nav-icon-item user-logged-in" id="userMenu">
        <span class="user-name-pill" style="position:relative">
          {{ Auth::user()->name }}
          <span id="notificationBadge" style="display:none;position:absolute;top:-5px;right:-5px;width:10px;height:10px;background:#ff6b35;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 8px rgba(255,107,53,0.4)"></span>
        </span>
        
        <!-- User Dropdown -->
        <div class="user-dropdown" id="userDropdown">
          <div class="dropdown-item" onclick="window.location.href='/settings'">
            <i class="fas fa-cog"></i>
            <span>الإعدادات</span>
          </div>
          <div class="dropdown-item has-submenu" id="languageItem">
            <i class="fas fa-language"></i>
            <span>اللغة</span>
            <i class="fas fa-chevron-right submenu-arrow"></i>
            
            <!-- Language Submenu -->
            <div class="dropdown-submenu" id="languageSubmenu">
              <div class="dropdown-item lang-option" data-lang="ar" onclick="changeLanguage('ar')">
                <img src="https://flagcdn.com/w40/sy.png" alt="Syria" class="flag-icon">
                <span>العربية</span>
                <i class="fas fa-check lang-check" style="display: none;"></i>
              </div>
              <div class="dropdown-item lang-option" data-lang="en" onclick="changeLanguage('en')">
                <img src="https://flagcdn.com/w40/gb.png" alt="UK" class="flag-icon">
                <span>English</span>
                <i class="fas fa-check lang-check" style="display: none;"></i>
              </div>
            </div>
          </div>
          <div class="dropdown-item" onclick="window.location.href='/my-orders'">
            <i class="fas fa-shopping-bag"></i>
            <span>طلباتي</span>
          </div>
          <div class="dropdown-item" onclick="window.location.href='/notifications'" style="position:relative">
            <i class="fas fa-bell"></i>
            <span>الإشعارات</span>
            <span id="notificationDropdownBadge" style="display:none;position:absolute;top:50%;left:10px;transform:translateY(-50%);width:8px;height:8px;background:#ff6b35;border-radius:50%;box-shadow:0 2px 6px rgba(255,107,53,0.4)"></span>
          </div>
          @if(Auth::user()->is_admin ?? false)
          <div class="dropdown-item" onclick="window.location.href='/admin/dashboard'">
            <i class="fas fa-chart-line"></i>
            <span>لوحة الإدارة</span>
          </div>
          @endif
          @if(Auth::user()->is_it_super ?? false)
          <div class="dropdown-item" onclick="window.location.href='/it/dashboard'">
            <i class="fas fa-laptop-code"></i>
            <span>لوحة IT Supervisor</span>
          </div>
          @endif
          @if(Auth::user()->is_it ?? false)
          <div class="dropdown-item" onclick="window.location.href='/it/dashboard'">
            <i class="fas fa-laptop"></i>
            <span>لوحة IT Crew</span>
          </div>
          @endif
          @if(Auth::user()->is_cs_agent ?? false)
          <div class="dropdown-item" onclick="window.location.href='/cs/dashboard'">
            <i class="fas fa-headset"></i>
            <span>لوحة خدمة العملاء</span>
          </div>
          @endif
          @if(Auth::user()->is_accountant ?? false)
          <div class="dropdown-item" onclick="window.location.href='/accounting/dashboard'">
            <i class="fas fa-calculator"></i>
            <span>لوحة المحاسبة</span>
          </div>
          @endif
          @if(Auth::user()->is_driver_supervisor ?? false)
          <div class="dropdown-item" onclick="window.location.href='/delivery/supervisor/dashboard'">
            <i class="fas fa-truck"></i>
            <span>لوحة مشرف التوصيل</span>
          </div>
          @endif
          @if(Auth::user()->is_hr ?? false)
          <div class="dropdown-item" onclick="window.location.href='/hr/dashboard'">
            <i class="fas fa-users-cog"></i>
            <span>لوحة الموارد البشرية</span>
          </div>
          @endif
          <div class="dropdown-item {{ Auth::user()->is_trader ?? false ? '' : 'disabled' }}" onclick="{{ Auth::user()->is_trader ?? false ? 'window.location.href=\'/control-panel\'' : 'return false;' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>لوحة التحكم</span>
          </div>
          <div class="dropdown-item {{ Auth::user()->is_trader ?? false ? '' : 'disabled' }}" onclick="{{ Auth::user()->is_trader ?? false ? 'window.location.href=\'/my-store\'' : 'return false;' }}">
            <i class="fas fa-store"></i>
            <span>متجري</span>
          </div>
          <div class="dropdown-item logout-item" onclick="handleLogout()">
            <i class="fas fa-sign-out-alt"></i>
            <span>تسجيل خروج</span>
          </div>
        </div>
      </div>
      @else
      <div class="nav-icon-item" onclick="window.location.href='/ar-login'">
        <i class="fas fa-user icon-user"></i>
        <span class="icon-label user-label">
          <i class="fas fa-user"></i>
          <span>تسجيل الدخول</span>
        </span>
      </div>
      @endauth

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
      <div class="nav-icon-item">
        <i class="fas fa-gift icon-gift"></i>
        <span class="icon-label gift-label">
          <i class="fas fa-gift"></i>
          <span>تنسيق هدايا</span>
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
      <img src="/images/photo_2025-11-17_11-18-40.jpg" alt="U" class="logo-girl">
      <span class="logo-t">T</span>
    </a>

  </div>
</nav>


<script>
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

    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = this.value.trim();
            if (query.length >= 2) {
                // Redirect to store page with search query
                window.location.href = '/store?search=' + encodeURIComponent(query);
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
                // Redirect to store page with search query
                window.location.href = '/store?search=' + encodeURIComponent(query);
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

        fetch('/api/categories')
            .then(res => res.json())
            .then(categories => {
                searchResults.innerHTML = categories.map(cat => {
                    const icon = getCategoryIcon(cat.name, cat.slug || '');
                    return `
                        <div class="search-result-item" onclick="window.location.href='/category/${cat.slug}'">
                            <i class="fas ${icon} search-result-icon"></i>
                            <div class="search-result-info">
                                <div class="search-result-name">${cat.name}</div>
                            </div>
                        </div>
                    `;
                }).join('');
            })
            .catch(err => {
                searchResults.innerHTML = '<div style="text-align:center;color:#e74c3c;">حدث خطأ</div>';
            });
    }

    function performSearch(query) {
        dropdownTitle.textContent = 'نتائج البحث';
        recentChips.style.display = 'none';
        searchResults.style.display = 'flex';
        searchResults.innerHTML = '<div style="text-align:center;color:#999;">جاري البحث...</div>';

        fetch(`/api/products/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                const products = data.data || [];
                
                if (products.length === 0) {
                    searchResults.innerHTML = '<div style="text-align:center;color:#999;">لا توجد نتائج</div>';
                    return;
                }

                searchResults.innerHTML = products.map(product => `
                    <div class="search-result-item" onclick="window.location.href='/product/${product.id}'">
                        <i class="fas fa-search search-result-icon"></i>
                        <div class="search-result-info">
                            <div class="search-result-name">${product.name}</div>
                            <div class="search-result-price">${product.price} ر.س</div>
                        </div>
                        <img src="${product.image || '/images/placeholder.jpg'}" class="search-result-img" alt="${product.name}">
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
@auth
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

// Translate navbar
function translateNavbar(lang) {
    const translations = {
        ar: {
            search: 'ابحث عن المنتج الذي تريده',
            gifts: 'تنسيق هدايا',
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
            gifts: 'Gift Arrangements',
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
}
@endauth

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
        font-family: 'El Messiri', sans-serif;
    `;
    toast.innerHTML = `
        <i class="fas fa-check-circle" style="font-size: 1.2rem; color: #4CAF50;"></i>
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
