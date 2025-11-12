// Legacy Home page script extracted from index.ejs
(function(){
  document.addEventListener("DOMContentLoaded", function () {
    const API = 'http://localhost:8000/api';
    const main_view = document.getElementById('main-view');
    const products_main = document.getElementById('products-cards');
    const productFilterBar = document.getElementById('product-filter-bar');
    const orderSelect = document.getElementById('orderSelect');
    const attBar = document.getElementById('attFilters');
    const activeCatSpan = document.getElementById('activeCategory');
    const searchInputNavbar = document.querySelector('.search-input');
    let currentCategorySlug = '';

    function getParams(){
      const params = {};
      if (searchInputNavbar && searchInputNavbar.value) params.q = searchInputNavbar.value;
      const of = document.querySelector('#orderField');
      const od = document.querySelector('#orderDir');
      if (of && of.value) params.order = of.value;
      if (od && od.value) params.dir = od.value;
      document.querySelectorAll('.filter .list-group select[data-att]').forEach(sel => {
        const name = sel.getAttribute('data-att');
        if (name && sel.value) params[name] = sel.value;
      });
      return params;
    }

    async function fetchAndRender(){
      const params = getParams();
      const query = new URLSearchParams(params).toString();
      try {
        let prods, filterMap = {};
        if (currentCategorySlug) {
          const [filtersRes, prodsRes] = await Promise.all([
            fetch(`${API}/categories/${currentCategorySlug}/filters`),
            fetch(`${API}/categories/${currentCategorySlug}/products${query? ('?'+query):''}`)
          ]);
          filterMap = await filtersRes.json();
          prods = await prodsRes.json();
          if (productFilterBar) productFilterBar.style.display = 'none';
          if (activeCatSpan) activeCatSpan.textContent = 'Category: ' + currentCategorySlug;
        } else {
          const prodsRes = await fetch(`${API}/products/search${query? ('?'+query): ''}`);
          prods = await prodsRes.json();
          if (productFilterBar) productFilterBar.style.display = 'none';
          if (activeCatSpan) activeCatSpan.textContent = params.q ? ('Search: '+params.q) : '';
        }

        if (products_main) {
          products_main.style.display = 'grid';
          products_main.style.gridTemplateColumns = '20% auto';
          products_main.style.gap = '20px';
        }
        if (main_view) main_view.style.display = 'none';

        const filterList = document.querySelector('.filter .list-group');
        if (filterList) {
          const prevOrderField = document.querySelector('#orderField')?.value || 'date';
          const prevOrderDir = document.querySelector('#orderDir')?.value || 'desc';
          const prevAttValues = {};
          document.querySelectorAll('.filter .list-group select[data-att]').forEach(sel => {
            prevAttValues[sel.getAttribute('data-att')] = sel.value;
          });
          filterList.innerHTML = '';
          const orderDiv = document.createElement('div');
          orderDiv.innerHTML = `
            <li><strong>Order</strong></li>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
              <select id="orderField" style="padding:8px 10px; border-radius:8px; border:1px solid #d9dfe4;">
                <option value="date">Date</option>
                <option value="price">Price</option>
                <option value="rate">Rate</option>
                <option value="sales">Popular</option>
              </select>
              <select id="orderDir" style="padding:8px 10px; border-radius:8px; border:1px solid #d9dfe4;">
                <option value="desc">Descending</option>
                <option value="asc">Ascending</option>
              </select>
            </div>
          `;
          filterList.appendChild(orderDiv);
          const orderField = orderDiv.querySelector('#orderField');
          const orderDir = orderDiv.querySelector('#orderDir');
          // @ts-ignore
          orderField.value = prevOrderField;
          // @ts-ignore
          orderDir.value = prevOrderDir;
          if (orderField) orderField.addEventListener('change', fetchAndRender);
          if (orderDir) orderDir.addEventListener('change', fetchAndRender);
          if (currentCategorySlug) {
            Object.keys(filterMap || {}).forEach(name => {
              const div = document.createElement('div');
              div.innerHTML = `<li><strong>${name}</strong></li>`;
              const sel = document.createElement('select');
              sel.setAttribute('data-att', name);
              sel.style.padding = '8px 10px';
              sel.style.border = '1px solid #d9dfe4';
              sel.style.borderRadius = '8px';
              sel.innerHTML = '<option value="">All</option>' + (filterMap[name]||[]).map(v => `<option value="${v}">${v}</option>`).join('');
              if (prevAttValues[name]) sel.value = prevAttValues[name];
              sel.addEventListener('change', fetchAndRender);
              div.appendChild(sel);
              filterList.appendChild(div);
            });
          }
        }

        const productsGrid = document.querySelector('.products');
        if (productsGrid) {
          productsGrid.innerHTML = '';
          (prods.data || []).forEach(p => {
            const card = document.createElement('div');
            card.className = 'product';
            const pslug = p.slug || String(p.id || p.code || p.sku || p.name || '').toLowerCase();
            const pid = p.id != null ? p.id : pslug;
            const pimg = p.image || 'images/category/phone.jpeg';
            const pname = p.name || 'Product';
            const pprice = Number(p.price || 0);
            card.setAttribute('data-product-id', pslug);
            card.innerHTML = `
              <a href="/product/${encodeURIComponent(pslug)}" style="text-decoration:none;color:inherit;">
                <div class="image"><img src="${pimg}" alt="" /></div>
                <div class="name_price"><h3>${pname}</h3><span>$${pprice}</span></div>
                <p>${p.description || ''}</p>
              </a>
              <div class="add-cart">
                <button
                  type="button"
                  class="add-btn"
                  data-id="${pid}"
                  data-name="${pname}"
                  data-price="${pprice}"
                  data-image="${pimg}"
                  aria-pressed="false"
                  title="Add to cart"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart-plus" viewBox="0 0 16 16"><path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/><path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/></svg>
                </button>
              </div>
            `;
            productsGrid.appendChild(card);
          });
        }
      } catch (e) {
        console.error('Failed to load products', e);
      }
    }

    if (searchInputNavbar) {
      searchInputNavbar.addEventListener('keyup', (e)=>{
        if (e.key === 'Enter') {
          currentCategorySlug = '';
          if (products_main){
            products_main.style.display = 'grid';
            products_main.style.gridTemplateColumns = '20% auto';
            products_main.style.gap = '20px';
          }
          if (main_view) main_view.style.display = 'none';
          fetchAndRender();
        }
      });
    }

    let dropdown;
    function ensureDropdown(){
      if (dropdown) return dropdown;
      dropdown = document.createElement('div');
      dropdown.style.position = 'fixed';
      dropdown.style.background = '#fff';
      dropdown.style.border = '1px solid #e5e9ef';
      dropdown.style.borderRadius = '12px';
      dropdown.style.boxShadow = '0 12px 28px rgba(0,0,0,0.12)';
      dropdown.style.zIndex = '10000';
      dropdown.style.maxHeight = '360px';
      dropdown.style.overflowY = 'auto';
      dropdown.style.padding = '6px';
      const rect = searchInputNavbar.getBoundingClientRect();
      dropdown.style.left = rect.left + 'px';
      dropdown.style.top = (rect.bottom + 6) + 'px';
      dropdown.style.width = rect.width + 'px';
      document.body.appendChild(dropdown);
      return dropdown;
    }
    let debounceTimer;
    if (searchInputNavbar) {
      searchInputNavbar.addEventListener('input', ()=>{
        clearTimeout(debounceTimer);
        const q = searchInputNavbar.value.trim();
        if (!q) { if (dropdown) dropdown.remove(); dropdown = null; return; }
        debounceTimer = setTimeout(async ()=>{
          try {
            const res = await fetch(`${API}/products/search?q=${encodeURIComponent(q)}&order=date`);
            const data = await res.json();
            const dd = ensureDropdown();
            dd.innerHTML = '';
            (data.data || []).slice(0,10).forEach(p => {
              const item = document.createElement('div');
              item.style.padding = '10px 12px';
              item.style.cursor = 'pointer';
              item.style.display = 'flex';
              item.style.alignItems = 'center';
              item.style.gap = '10px';
              item.style.borderRadius = '10px';
              item.onmouseenter = ()=> item.style.background = '#f6f8fb';
              item.onmouseleave = ()=> item.style.background = 'transparent';
              const img = document.createElement('img');
              img.src = p.image || 'images/category/phone.jpeg';
              img.style.width = '40px';
              img.style.height = '40px';
              img.style.borderRadius = '8px';
              img.style.objectFit = 'cover';
              const txt = document.createElement('div');
              txt.innerHTML = `<div style=\"font-weight:700;color:#0d464c\">${p.name}</div><div style=\"font-size:12px;color:#6b7785\">$${p.price}</div>`;
              item.appendChild(img);
              item.appendChild(txt);
              item.addEventListener('click', ()=>{
                const pid = p.slug || String(p.id || p.code || p.sku || p.name || '').toLowerCase();
                if (dropdown) { dropdown.remove(); dropdown = null; }
                window.location.href = '/product/' + encodeURIComponent(pid);
              });
              dd.appendChild(item);
            });
          } catch (e) {}
        }, 250);
      });
    }

    document.querySelectorAll('.cards .figure').forEach(fig => {
      fig.addEventListener('click', (e)=>{
        e.preventDefault();
        const img = fig.querySelector('img');
        const src = img ? img.getAttribute('src') : '';
        if (src.includes('1.')) currentCategorySlug = 'fashion';
        else if (src.includes('2.')) currentCategorySlug = 'electronics';
        else if (src.includes('6.')) currentCategorySlug = 'books';
        else currentCategorySlug = 'electronics';
        if (products_main){
          products_main.style.display = 'grid';
          products_main.style.gridTemplateColumns = '20% auto';
          products_main.style.gap = '20px';
        }
        if (main_view) main_view.style.display = 'none';
        fetchAndRender();
      });
    });
  });
})();
