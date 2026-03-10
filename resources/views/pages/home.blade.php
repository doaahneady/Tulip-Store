@extends('app')

@section('title', 'Home - Tulip Store')

@section('content')
<div class="container-custom py-12">
  <!-- Hero Section -->
  <div class="bg-gradient-to-r from-primary to-secondary rounded-lg overflow-hidden mb-12 shadow-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center p-8 md:p-12">
      <div class="text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Tulip Store</h1>
        <p class="text-lg mb-6">Discover our amazing collection of premium products at unbeatable prices.</p>
        <a href="/categories" class="btn-primary inline-block">Shop Now</a>
      </div>
      <div class="hidden md:block">
        <img src="/images/logo.ico" alt="Tulip Store" class="w-64 h-64 mx-auto opacity-90">
      </div>
    </div>
  </div>

  <!-- Featured Categories -->
  <div class="mb-12">
    <h2 class="text-3xl font-bold mb-8 text-gray-900">Featured Categories</h2>
    <div id="categories-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="card text-center p-6 cursor-pointer hover:shadow-lg transition">
        <i class="fas fa-spinner text-primary text-4xl mb-4"></i>
        <p class="text-gray-500">Loading categories...</p>
      </div>
    </div>
  </div>

  <!-- Featured Products -->
  <div class="mb-12">
    <h2 class="text-3xl font-bold mb-8 text-gray-900">Featured Products</h2>
    <div id="products-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="card p-6 text-center">
        <i class="fas fa-spinner text-primary text-4xl mb-4"></i>
        <p class="text-gray-500">Loading products...</p>
      </div>
    </div>
  </div>
</div>

<script>
  // Fetch and display categories
  async function loadCategories() {
    try {
      const response = await fetch('/api/categories');
      if (!response.ok) throw new Error('Failed to load categories');
      
      const categories = await response.json();
      const container = document.getElementById('categories-container');
      
      if (categories.data && categories.data.length > 0) {
        container.innerHTML = categories.data.slice(0, 3).map(cat => `
          <div class="card text-center p-6 cursor-pointer hover:shadow-lg transition">
            <div class="text-primary text-4xl mb-4"><i class="fas fa-box"></i></div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">${cat.name}</h3>
            <p class="text-gray-600 text-sm mb-4">${cat.description || 'Browse our collection'}</p>
            <a href="/category/${encodeURIComponent(cat.slug)}" class="text-primary hover:text-pink-600 transition font-semibold">
              View Products →
            </a>
          </div>
        `).join('');
      }
    } catch (error) {
      console.error('Error loading categories:', error);
      document.getElementById('categories-container').innerHTML = '<p class="text-red-500">Failed to load categories</p>';
    }
  }

  // Fetch and display products
  async function loadProducts() {
    try {
      const response = await fetch('/api/categories/all/products');
      if (!response.ok) throw new Error('Failed to load products');
      
      const data = await response.json();
      const products = data.data || [];
      const container = document.getElementById('products-container');
      
      if (products.length > 0) {
        container.innerHTML = products.slice(0, 4).map(product => `
          <div class="card overflow-hidden hover:shadow-lg transition">
            <div class="bg-gray-200 h-48 flex items-center justify-center">
              ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">` : '<i class="fas fa-image text-gray-400 text-4xl"></i>'}
            </div>
            <div class="p-4">
              <h3 class="font-bold text-gray-900 mb-2 truncate">${product.name}</h3>
              <p class="text-primary font-bold text-lg mb-3">$${product.price}</p>
              <a href="/product/${product.id}" class="block text-center btn-primary text-sm">
                View Details
              </a>
            </div>
          </div>
        `).join('');
      }
    } catch (error) {
      console.error('Error loading products:', error);
      document.getElementById('products-container').innerHTML = '<p class="text-red-500">Failed to load products</p>';
    }
  }

  // Load data on page load
  loadCategories();
  loadProducts();
</script>
@endsection
