@extends('app')

@section('title', 'Category - Tulip Store')

@section('content')
<div class="container-custom py-12">
  <!-- Breadcrumb -->
  <div class="mb-8 text-sm">
    <a href="/" class="text-primary hover:text-pink-600">Home</a>
    <span class="text-gray-500 mx-2">/</span>
    <a href="/categories" class="text-primary hover:text-pink-600">Categories</a>
    <span class="text-gray-500 mx-2">/</span>
    <span id="category-name" class="text-gray-700">Loading...</span>
  </div>

  <!-- Category Header -->
  <div class="mb-8">
    <h1 id="category-title" class="text-4xl font-bold text-gray-900 mb-2">Loading...</h1>
    <p id="category-desc" class="text-gray-600 text-lg">Loading...</p>
  </div>

  <!-- Filters Section (if needed) -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
    <!-- Filters Sidebar (optional) -->
    <div class="hidden md:block">
      <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4">Filters</h3>
        <div id="filters-container">
          <p class="text-gray-500 text-sm">Loading filters...</p>
        </div>
      </div>
    </div>

    <!-- Products -->
    <div class="md:col-span-3">
      <div id="products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="card p-6 text-center col-span-full">
          <i class="fas fa-spinner text-primary text-4xl mb-4 animate-spin"></i>
          <p class="text-gray-500">Loading products...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const slug = window.location.pathname.split('/').pop();

  async function loadCategory() {
    try {
      const response = await fetch(`/api/categories/${slug}`);
      if (!response.ok) throw new Error('Failed to load category');
      
      const category = await response.json();
      document.getElementById('category-name').textContent = category.data.name;
      document.getElementById('category-title').textContent = category.data.name;
      document.getElementById('category-desc').textContent = category.data.description || 'Browse our collection';
    } catch (error) {
      console.error('Error loading category:', error);
      document.getElementById('category-title').textContent = 'Category Not Found';
    }
  }

  async function loadProducts() {
    try {
      const response = await fetch(`/api/categories/${slug}/products`);
      if (!response.ok) throw new Error('Failed to load products');
      
      const data = await response.json();
      const products = data.data || [];
      const container = document.getElementById('products-container');
      
      if (products.length > 0) {
        container.innerHTML = products.map(product => `
          <div class="card overflow-hidden hover:shadow-lg transition">
            <div class="bg-gray-200 h-48 flex items-center justify-center">
              ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">` : '<i class="fas fa-image text-gray-400 text-4xl"></i>'}
            </div>
            <div class="p-4">
              <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">${product.name}</h3>
              <p class="text-primary font-bold text-lg mb-3">$${product.price}</p>
              <a href="/product/${product.id}" class="block text-center btn-primary text-sm">
                View Details
              </a>
            </div>
          </div>
        `).join('');
      } else {
        container.innerHTML = '<p class="text-gray-500 col-span-full text-center">No products in this category</p>';
      }
    } catch (error) {
      console.error('Error loading products:', error);
      document.getElementById('products-container').innerHTML = '<p class="text-red-500 col-span-full text-center">Failed to load products</p>';
    }
  }

  loadCategory();
  loadProducts();
</script>
@endsection
