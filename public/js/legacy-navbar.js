// RTL and lang direction from legacy head
(function(){
  try {
    var lang = (localStorage.getItem('lang')||'en').toLowerCase();
    var html = document.documentElement;
    if (lang === 'ar' || lang === 'arabic'){
      html.setAttribute('dir','rtl');
      html.setAttribute('lang','ar');
      document.addEventListener('DOMContentLoaded', function(){
        if (document.body) document.body.classList.add('rtl');
      });
    } else {
      html.setAttribute('dir','ltr');
      html.setAttribute('lang','en');
      document.addEventListener('DOMContentLoaded', function(){
        if (document.body) document.body.classList.remove('rtl');
      });
    }
  } catch(_){}
})();

// Minimal client-side i18n for key UI labels (legacy head)
(function(){
  var dict = {
    en: {
      home: 'Home',
      signIn: 'Sign In',
      cart: 'Cart',
      shareNow: 'Share now',
      addToCart: 'Add To Cart',
      buyNow: 'Buy Now',
      description: 'Description',
      additionalDetails: 'Additional Details',
      category: 'Category',
      code: 'Code',
      products: 'products',
      order: 'Order',
      newest: 'Newest',
      priceLabel: 'Price',
      rate: 'Rate',
      popular: 'Popular',
      descending: 'Descending',
      ascending: 'Ascending',
      searchPlaceholder: 'What are you searching for?',
      apply: 'Apply'
    },
    ar: {
      home: 'الرئيسية',
      signIn: 'تسجيل دخول',
      cart: 'السلة',
      shareNow: 'مشاركة',
      addToCart: 'أضف إلى السلة',
      buyNow: 'اشتري الآن',
      description: 'الوصف',
      additionalDetails: 'تفاصيل إضافية',
      category: 'التصنيف',
      code: 'الرمز',
      products: 'المنتجات',
      order: 'الترتيب',
      newest: 'الأحدث',
      priceLabel: 'السعر',
      rate: 'التقييم',
      popular: 'الأكثر شيوعاً',
      descending: 'تنازلي',
      ascending: 'تصاعدي',
      searchPlaceholder: 'عمّ تبحث؟',
      apply: 'تطبيق'
    }
  };
  var phraseMap = {
    enToAr: {
      'Home':'الرئيسية',
      'Products':'المنتجات',
      'products':'المنتجات',
      'Order':'الترتيب',
      'Newest':'الأحدث',
      'Price':'السعر',
      'Rate':'التقييم',
      'Popular':'الأكثر شيوعاً',
      'Descending':'تنازلي',
      'Ascending':'تصاعدي',
      'All':'الكل',
      "Today's Deal":'عروض اليوم',
      'Gift Cards':'بطاقات الهدايا',
      'Fashions':'أزياء',
      'Shoes & Bags':'أحذية وحقائب',
      'Smart':'أجهزة ذكية',
      'electronics':'إلكترونيات',
      'Toys':'ألعاب',
      'Sports':'رياضة',
      'Jewelry':'مجوهرات',
      'Back To School':'العودة إلى المدرسة',
      'care':'عناية',
      'view products':'عرض المنتجات'
    }
  };
  function applyI18N(){
    try{
      var lang = (localStorage.getItem('lang')||'en').toLowerCase();
      var table = dict[lang.startsWith('ar') ? 'ar' : 'en'] || dict.en;
      document.querySelectorAll('[data-i18n]').forEach(function(el){
        var k = el.getAttribute('data-i18n');
        if (table[k]) el.textContent = table[k];
      });
      document.querySelectorAll('[data-i18n-placeholder]').forEach(function(el){
        var k = el.getAttribute('data-i18n-placeholder');
        if (table[k]) el.setAttribute('placeholder', table[k]);
      });
      if (lang.startsWith('ar')){
        var map = phraseMap.enToAr;
        var candidates = document.querySelectorAll('nav, footer, .breadcrumb, .filter, .section-title, .buttons, .header h1, label, option, .navbar2, .card-title, .figure-caption, #product-filter-bar, .category-select, .category-menu li');
        candidates.forEach(function(el){
          var txt = (el.textContent||'').trim();
          if (map[txt]) el.textContent = map[txt];
          if (el.tagName==='OPTION' && map[txt]) el.textContent = map[txt];
        });
      }
    }catch(_){ }
  }
  document.addEventListener('DOMContentLoaded', applyI18N);
  window.addEventListener('storage', function(e){ if (e.key==='lang') applyI18N(); });
  // @ts-ignore
  window.applyI18N = applyI18N;
})();

// Navbar auth UI + signout (legacy)
(function(){
  function displayNameFrom(user){
    return (
      (user && (user.user_full_name || user.userFullName || user.name || user.username)) || 'Account'
    );
  }
  function updateAuthUI(){
    try {
      var userRaw = localStorage.getItem('user');
      var token = localStorage.getItem('token');
      var signInLi = document.querySelector('li.sign-in');
      var afterDiv = document.getElementById('after_s');
      var nameSpan = afterDiv ? afterDiv.querySelector('.btn-text') : null;
      if (userRaw && token) {
        var user = null;
        try { user = JSON.parse(userRaw); } catch(_){ }
        var nameText = displayNameFrom(user);
        if (nameSpan) nameSpan.textContent = nameText;
        if (signInLi) signInLi.style.display = 'none';
        if (afterDiv) afterDiv.style.display = 'block';
      } else {
        if (nameSpan) nameSpan.textContent = 'Account';
        if (afterDiv) afterDiv.style.display = 'none';
        if (signInLi) signInLi.style.display = 'list-item';
      }
    } catch(_){ }
  }
  document.addEventListener('DOMContentLoaded', updateAuthUI);
  window.addEventListener('pageshow', updateAuthUI);
  window.addEventListener('storage', updateAuthUI);
  setTimeout(updateAuthUI, 0);

  // Search icon acts like submit
  (function(){
    var box = document.querySelector('.search-box');
    if (!box) return;
    var input = box.querySelector('.search-input');
    var icon = box.querySelector('.search-icon');
    function submitLikeEnter(){
      if (!input) return;
      var form = (input.closest && input.closest('form')) || input.form || null;
      if (form) {
        if (typeof form.requestSubmit === 'function') form.requestSubmit(); else form.submit();
        return;
      }
      input.focus();
      var evDown = new KeyboardEvent('keydown', { key: 'Enter', code: 'Enter', keyCode: 13, which: 13, bubbles: true });
      var evPress = new KeyboardEvent('keypress', { key: 'Enter', code: 'Enter', keyCode: 13, which: 13, bubbles: true });
      var evUp = new KeyboardEvent('keyup', { key: 'Enter', code: 'Enter', keyCode: 13, which: 13, bubbles: true });
      input.dispatchEvent(evDown);
      input.dispatchEvent(evPress);
      input.dispatchEvent(evUp);
    }
    if (box) box.addEventListener('click', function(e){
      var el = e.target;
      if (icon && (el === icon || icon.contains(el))) { e.preventDefault(); submitLikeEnter(); }
    });
    if (icon) icon.addEventListener('keydown', function(e){ if (e.key === 'Enter' || e.keyCode === 13) { e.preventDefault(); submitLikeEnter(); } });
  })();

  // Search suggestions dropdown
  (function(){
    if (window.__SEARCH_DD_BOUND) return; window.__SEARCH_DD_BOUND = true;
    var API = '/api';
    var box = document.querySelector('.search-box');
    var input = document.querySelector('.search-input');
    if (!box || !input) return;
    var dd = null, timer = null;
    function ensureDD(){
      if (dd) return dd;
      dd = document.createElement('div');
      dd.id = 'navSearchDD';
      dd.style.position = 'fixed';
      dd.style.background = '#fff';
      dd.style.border = '1px solid #e5e9ef';
      dd.style.borderRadius = '12px';
      dd.style.boxShadow = '0 12px 28px rgba(0,0,0,0.12)';
      dd.style.zIndex = '10000';
      dd.style.maxHeight = '360px';
      dd.style.overflowY = 'auto';
      dd.style.padding = '6px';
      positionDD();
      document.body.appendChild(dd);
      window.addEventListener('resize', positionDD);
      window.addEventListener('scroll', positionDD, true);
      return dd;
    }
    function positionDD(){
      var rect = input.getBoundingClientRect();
      if (!dd) return;
      dd.style.left = rect.left + 'px';
      dd.style.top = (rect.bottom + 6) + 'px';
      dd.style.width = rect.width + 'px';
    }
    function clearDD(){ if (dd){ dd.remove(); dd = null; } }
    document.addEventListener('click', function(e){ if (dd && !dd.contains(e.target) && !box.contains(e.target)) clearDD(); });
    input.addEventListener('input', function(){
      clearTimeout(timer);
      var q = input.value.trim();
      if (!q) { clearDD(); return; }
      timer = setTimeout(async function(){
        try{
          var res = await fetch(API + '/products/search?q=' + encodeURIComponent(q) + '&order=date');
          var data = await res.json();
          var list = Array.isArray(data && data.data) ? data.data.slice(0,10) : [];
          if (!list.length) { clearDD(); return; }
          var el = ensureDD();
          el.innerHTML = '';
          list.forEach(function(p){
            var pid = p.slug || String(p.id || p.code || p.sku || p.name || '').toLowerCase();
            var item = document.createElement('div');
            item.style.padding = '10px 12px';
            item.style.cursor = 'pointer';
            item.style.display = 'flex';
            item.style.alignItems = 'center';
            item.style.gap = '10px';
            item.style.borderRadius = '10px';
            item.onmouseenter = function(){ item.style.background = '#f6f8fb'; };
            item.onmouseleave = function(){ item.style.background = 'transparent'; };
            var img = document.createElement('img');
            img.src = p.image || 'images/category/phone.jpeg';
            img.style.width = '40px'; img.style.height = '40px'; img.style.borderRadius = '8px'; img.style.objectFit = 'cover';
            var txt = document.createElement('div');
            txt.innerHTML = '<div style="font-weight:700;color:#0d464c">' + p.name + '</div><div style="font-size:12px;color:#6b7785">$' + (p.price ?? '') + '</div>';
            item.appendChild(img); item.appendChild(txt);
            item.addEventListener('click', function(){ window.location.href = '/product/' + encodeURIComponent(pid); });
            el.appendChild(item);
          });
          positionDD();
        } catch(_){ }
      }, 250);
    });
    input.addEventListener('keydown', function(e){
      if (e.key === 'Enter') {
        var q = input.value.trim();
        if (q) window.location.href = '/search?q=' + encodeURIComponent(q);
      }
    });
  })();

  // Language switcher in navbar
  (function(){
    try{
      var langLis = document.querySelectorAll('.category-dropdown .category-menu li');
      var btn = document.querySelector('.lang-btn');
      function label(){
        var lang = (localStorage.getItem('lang')||'en').toLowerCase();
        if (btn) btn.setAttribute('title', 'Language: ' + (lang.startsWith('ar') ? 'العربية' : 'English'));
      }
      langLis.forEach(function(li){
        li.addEventListener('click', function(){
          var t = (li.textContent||'').trim();
          var isAr = /العربية|arabic/i.test(t);
          localStorage.setItem('lang', isAr ? 'ar' : 'en');
          window.location.reload();
        });
      });
      label();
    }catch(_){ }
  })();

  // Gift button + tooltip + auth signout behavior
  (function(){
    var gift = document.getElementById('gift');
    if (gift) gift.addEventListener('click', function () { window.location.href = '/box'; });
    try {
      var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      // @ts-ignore
      var tooltipList = [].slice.call(tooltipTriggerList).map(function (tooltipTriggerEl){ return new bootstrap.Tooltip(tooltipTriggerEl); });
    } catch(_){ }
    var loginBtn = document.getElementById('loginBtn');
    var after_s = document.getElementById('after_s');
    var isloggedIn = localStorage.getItem('isloggedIn');
    if (isloggedIn && loginBtn && after_s) {
      loginBtn.style.display = 'none';
      after_s.style.display = 'inline';
      try {
        var userRaw = localStorage.getItem('user');
        if (userRaw) {
          var user = JSON.parse(userRaw);
          var nameEl = document.querySelector('.profile-btn .btn-text');
          if (nameEl) {
            nameEl.textContent = (user.user_full_name && user.user_full_name.trim()) ? user.user_full_name : (user.username || 'Account');
          }
        }
      } catch (_) {}
    }
    var signOutLink = document.getElementById('signOutLink');
    if (signOutLink) {
      signOutLink.addEventListener('click', async function (e) {
        e.preventDefault();
        try {
          var token = localStorage.getItem('token');
          if (token) {
            await fetch('http://localhost:8000/api/auth/logout', {
              method: 'POST', headers: { Authorization: 'Bearer ' + token }
            });
          }
        } catch (_) {}
        localStorage.removeItem('token'); localStorage.removeItem('user'); localStorage.removeItem('isloggedIn');
        try { if (loginBtn) loginBtn.style.display = 'inline'; if (after_s) after_s.style.display = 'none'; } catch(_){}
        window.location.href = '/';
      });
    }
  })();
})();
