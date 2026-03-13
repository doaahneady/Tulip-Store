@extends('app')

@section('title', 'Product - Tulip Store')

@section('content')
<div class="container-custom py-12">
  <!-- Breadcrumb -->
  <div class="mb-8 text-sm">
    <a href="/" class="text-primary hover:text-pink-600">Home</a>
    <span class="text-gray-500 mx-2">/</span>
    <a href="/categories" class="text-primary hover:text-pink-600">Categories</a>
    <span class="text-gray-500 mx-2">/</span>
    <span id="product-breadcrumb" class="text-gray-700">Product</span>
  </div>

  <!-- Product Details -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="product-container">
    <div class="card p-6 text-center">
      <i class="fas fa-spinner text-primary text-4xl mb-4 animate-spin"></i>
      <p class="text-gray-500">Loading product...</p>
    </div>
  </div>

  <!-- Related Products -->
  <div class="mt-16">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8">Related Products</h2>
    <div id="related-products" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
      <div class="col-span-full py-12 text-center">
        <i class="fas fa-spinner text-primary text-4xl mb-4 animate-spin"></i>
        <p class="text-gray-500">Loading related products...</p>
      </div>
    </div>
  </div>
</div>

<style>
  @media (max-width: 768px) {
    #related-products .card {
      background: transparent !important;
      box-shadow: none !important;
      padding: 0 !important;
      border: none !important;
    }
    #related-products .product-image-container {
      border-radius: 12px !important;
      overflow: hidden !important;
    }
  }
</style>

<script>
  const productId = window.location.pathname.split('/').pop();

  async function loadRelatedProducts() {
    try {
      const response = await fetch(`/api/products?limit=4`); // Simple mock for related products
      if (!response.ok) throw new Error('Failed to load related products');
      
      const data = await response.json();
      const products = data.data.filter(p => p.id != productId).slice(0, 4);
      const container = document.getElementById('related-products');
      
      if (products.length === 0) {
        container.innerHTML = '<p class="text-gray-500 col-span-full text-center">No related products found.</p>';
        return;
      }

      container.innerHTML = products.map(product => `
        <a href="/products/${product.id}" class="card group overflow-hidden hover:shadow-lg transition flex flex-col h-full">
          <div class="product-image-container aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
            ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">` : '<i class="fas fa-image text-gray-400 text-4xl"></i>'}
          </div>
          <div class="p-3 md:p-4 flex flex-col flex-1">
            <h3 class="font-bold text-gray-900 text-sm md:text-base mb-1 line-clamp-1">${product.name}</h3>
            <p class="text-primary font-bold mt-auto">$${product.price}</p>
          </div>
        </a>
      `).join('');

    } catch (error) {
      console.error('Error loading related products:', error);
      document.getElementById('related-products').innerHTML = '<p class="text-red-500 col-span-full text-center">Failed to load related products</p>';
    }
  }

  async function loadProduct() {
    try {
      const response = await fetch(`/api/products/${productId}`);
      if (!response.ok) throw new Error('Failed to load product');
      
      const data = await response.json();
      const product = data.data;
      const container = document.getElementById('product-container');
      
      container.innerHTML = `
        <!-- Product Image -->
        <div class="card overflow-hidden">
          <div class="bg-gray-200 h-96 flex items-center justify-center">
            ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">` : '<i class="fas fa-image text-gray-400 text-6xl"></i>'}
          </div>
        </div>

        <!-- Product Info -->
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">${product.name}</h1>
          <div class="flex items-center gap-4 mb-4">
            <div class="flex text-yellow-400">
              ${Array(5).fill().map((_, i) => `<i class="fas fa-star"></i>`).join('')}
            </div>
            <span class="text-gray-600">(${product.reviews || 0} reviews)</span>
          </div>

          <div class="card p-6 mb-6">
            <p class="text-4xl font-bold text-primary mb-2">$${product.price}</p>
            ${product.original_price ? `<p class="text-lg text-gray-500 line-through">$${product.original_price}</p>` : ''}
          </div>

          <div class="mb-6">
            <h3 class="font-bold text-gray-900 mb-3">Description</h3>
            <p class="text-gray-700 leading-relaxed">${product.description || 'No description available'}</p>
          </div>

          ${product.features ? `
            <div class="mb-6">
              <h3 class="font-bold text-gray-900 mb-3">Features</h3>
              <ul class="list-disc list-inside text-gray-700 space-y-1">
                ${product.features.split(',').map(f => `<li>${f.trim()}</li>`).join('')}
              </ul>
            </div>
          ` : ''}

          <!-- Add to Cart -->
          <div class="space-y-4">
            <div class="flex items-center gap-4">
              <label class="font-semibold text-gray-900">Quantity:</label>
              <div class="flex items-center border border-gray-300 rounded-lg">
                <button class="px-4 py-2 text-primary hover:bg-gray-100" id="qty-dec">−</button>
                <input type="number" id="quantity" value="1" min="1" max="${product.stock || 999}" class="w-12 text-center border-l border-r border-gray-300" readonly>
                <button class="px-4 py-2 text-primary hover:bg-gray-100" id="qty-inc">+</button>
              </div>
              ${product.stock ? `<span class="text-sm text-gray-600">${product.stock} in stock</span>` : ''}
            </div>

            <button id="add-to-cart" class="w-full btn-primary text-lg py-3">
              <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
            </button>

            <button class="w-full px-6 py-3 border-2 border-primary text-primary rounded-lg hover:bg-pink-50 transition font-semibold">
              <i class="fas fa-heart mr-2"></i> Add to Wishlist
            </button>
          </div>
        </div>
      `;

      // Quantity controls
      document.getElementById('qty-inc').addEventListener('click', () => {
        const qty = document.getElementById('quantity');
        const max = parseInt(qty.max) || 999;
        if (parseInt(qty.value) < max) qty.value = parseInt(qty.value) + 1;
      });

      document.getElementById('qty-dec').addEventListener('click', () => {
        const qty = document.getElementById('quantity');
        if (parseInt(qty.value) > 1) qty.value = parseInt(qty.value) - 1;
      });

      // Add to cart
      document.getElementById('add-to-cart').addEventListener('click', () => {
        const qty = document.getElementById('quantity').value;
        // Store in localStorage for now
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const item = cart.find(i => i.id === product.id);
        if (item) {
          item.quantity += parseInt(qty);
        } else {
          cart.push({ ...product, quantity: parseInt(qty) });
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        alert('Added to cart!');
        document.querySelector('.cart-count').textContent = cart.length;
      });

    } catch (error) {
      console.error('Error loading product:', error);
      document.getElementById('product-container').innerHTML = '<p class="text-red-500 col-span-full text-center">Failed to load product</p>';
    }
  }

  loadProduct();
  loadRelatedProducts();
</script>
@endsection
