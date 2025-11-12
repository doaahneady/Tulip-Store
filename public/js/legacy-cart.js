// Legacy cart behavior extracted from EJS
(function(){
  const CART_KEY = 'cart';
  function readCart(){
    try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch(e){ return []; }
  }
  function writeCart(list){
    localStorage.setItem(CART_KEY, JSON.stringify(list));
    updateBadge();
    updateEmptyState();
  }
  function updateBadge(){
    try {
      const numEl = document.querySelector('.num');
      const items = readCart();
      const total = items.reduce((s,i)=> s + (Number(i.qty)||0), 0);
      if (numEl) numEl.textContent = String(total);
    } catch(_){ }
  }
  function updateEmptyState(){
    const items = readCart();
    const has = items.length > 0;
    const content = document.getElementById('cartContent');
    const empty = document.getElementById('emptyCart');
    if (content) content.style.display = has ? 'block' : 'none';
    if (empty) empty.style.display = has ? 'none' : 'flex';
  }
  function renderCart(){
    const tbody = document.getElementById('cartTable');
    if (!tbody) return;
    tbody.innerHTML = '';
    const items = readCart();
    items.forEach(item => {
      const tr = document.createElement('tr');
      tr.setAttribute('data-id', item.id);
      const price = Number(item.price)||0;
      const qty = Number(item.qty)||1;
      const name = (item.name||'Product').replace(/\"/g,'&quot;');
      tr.innerHTML = `
        <td>
          <div class="ratio ratio-1x1" style="width: 60px">
            <img src="${item.image||''}" class="img-fluid rounded" alt="${name}" />
          </div>
        </td>
        <td class="price" data-price="${price}">$${price.toFixed(2)}</td>
        <td>
          <div style="display:inline-flex;align-items:center;background:#e0e5ec9f;box-shadow:inset 2px 2px 4px rgba(163,177,198,0.6), inset -2px -2px 4px rgba(255,255,255,0.9);color:#0d464c;box-shadow:2px 1px 8px #000000a2;border-radius:6px;overflow:hidden;box-sizing:border-box;height:32px;">
            <button type="button" class="qty-dec" aria-label="decrease" style="width:32px;height:100%;padding:0;margin:0;border:none;background:transparent;display:inline-flex;align-items:center;justify-content:center;font-size:16px;line-height:1;color:#0d464c;cursor:pointer;">−</button>
            <span class="count" style="display:inline-flex;align-items:center;justify-content:center;padding:0 10px;min-width:26px;font-weight:700;font-size:18px;">${qty}</span>
            <button type="button" class="qty-inc" aria-label="increase" style="width:32px;height:100%;padding:0;margin:0;border:none;background:transparent;display:inline-flex;align-items:center;justify-content:center;font-size:16px;line-height:1;color:#0d464c;cursor:pointer;">+</button>
          </div>
        </td>
        <td><button type="button" class="btn-close deleteRow" aria-label="Close"></button></td>
      `;
      tbody.appendChild(tr);
    });
    updateBadge();
    updateEmptyState();
  }
  function addItem({id,name,price,image,qty}){
    if (!id) return;
    const cart = readCart();
    const idx = cart.findIndex(i=> i.id === id);
    const inc = Number(qty)||1;
    if (idx >= 0) {
      cart[idx].qty = Number(cart[idx].qty||0) + inc;
    } else {
      cart.push({ id, name: name||'Product', price: Number(price)||0, image: image||'', qty: inc });
    }
    writeCart(cart);
    renderCart();
  }
  function updateQty(id, newQty){
    const cart = readCart();
    const i = cart.findIndex(x=> x.id === id);
    if (i < 0) return;
    const q = Math.max(1, Number(newQty)||1);
    cart[i].qty = q;
    writeCart(cart);
    renderCart();
  }
  function removeItem(id){
    let cart = readCart();
    cart = cart.filter(x=> x.id !== id);
    writeCart(cart);
    renderCart();
  }
  function parsePriceFromText(txt){
    if (!txt) return 0;
    const m = String(txt).replace(/,/g,'').match(/([0-9]+(?:\.[0-9]+)?)/);
    return m ? Number(m[1]) : 0;
  }
  function getProductInfoFromElement(btn){
    const d = btn.dataset || {};
    let id = d.id || btn.getAttribute('data-id');
    let name = d.name || '';
    let price = d.price;
    let image = d.image || '';
    let qty = Number(d.qty) || 1;
    if (!id) {
      const card = btn.closest('.product');
      id = card?.getAttribute('data-product-id') || (card?.querySelector('h3')?.textContent||'').trim();
    }
    if (!name) {
      name = (btn.closest('.product')?.querySelector('h3')?.textContent || '').trim();
    }
    if (price == null) {
      const card = btn.closest('.product');
      const moneyTxt = card?.querySelector('.name_price span')?.textContent || '';
      price = parsePriceFromText(moneyTxt);
    } else {
      price = Number(price);
    }
    if (!image) {
      const card = btn.closest('.product');
      image = card?.querySelector('.image img')?.getAttribute('src') || '';
    }
    return { id, name, price, image, qty };
  }

  // Add to cart (delegated for static/dynamic buttons)
  document.addEventListener('click', function(e){
    const btn = e.target.closest && e.target.closest('.add-btn');
    if (!btn) return;
    e.preventDefault();
    const info = getProductInfoFromElement(btn);
    addItem({ ...info, qty: info.qty || 1 });
    try {
      const offcanvasEl = document.getElementById('offcanvasRight');
      if (offcanvasEl) bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl).show();
    } catch(_){ }
  });

  // Cart table controls (delegated)
  document.addEventListener('click', function(e){
    const row = e.target.closest && e.target.closest('tr');
    if (!row) return;
    const id = row.getAttribute('data-id');
    if (!id) return;
    const dec = e.target.closest && e.target.closest('.qty-dec');
    const inc = e.target.closest && e.target.closest('.qty-inc');
    const del = e.target.closest && e.target.closest('.deleteRow');
    if (dec) {
      const countEl = row.querySelector('.count');
      const cur = parseInt(countEl?.textContent||'1', 10);
      updateQty(id, Math.max(1, cur - 1));
    } else if (inc) {
      const countEl = row.querySelector('.count');
      const cur = parseInt(countEl?.textContent||'1', 10);
      updateQty(id, cur + 1);
    } else if (del) {
      removeItem(id);
    }
  });

  // Checkout button action
  document.addEventListener('click', function(e){
    const btn = e.target.closest && e.target.closest('#checkoutBtn');
    if (!btn) return;
    const items = readCart();
    if (!items.length) { alert('Your cart is empty.'); return; }
    window.location.href = '/checkout';
  });

  document.addEventListener('DOMContentLoaded', function(){ renderCart(); });
  window.addEventListener('storage', function(e){ if (e.key === CART_KEY) renderCart(); });
  try { document.getElementById('offcanvasRight')?.addEventListener('shown.bs.offcanvas', renderCart); } catch(_){ }
  updateBadge();
  updateEmptyState();
})();
