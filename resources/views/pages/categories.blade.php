@extends('app')

@section('title', 'Categories - Tulip Store')

@section('content')
<div class="container-custom py-12">
  <h1 class="text-4xl font-bold text-gray-900 mb-8">All Categories</h1>

  <div id="categories-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="card text-center p-6">
      <i class="fas fa-spinner text-primary text-4xl mb-4 animate-spin"></i>
      <p class="text-gray-500">Loading categories...</p>
    </div>
  </div>
</div>

<script>
  async function loadCategories() {
    try {
      const response = await fetch('/api/categories');
      if (!response.ok) throw new Error('Failed to load categories');
      
      const categories = await response.json();
      const container = document.getElementById('categories-container');
      
      if (categories.data && categories.data.length > 0) {
        container.innerHTML = categories.data.map(cat => `
          <a href="/category/${cat.slug}" class="card overflow-hidden hover:shadow-lg transition cursor-pointer">
            <div class="bg-gradient-to-r from-primary to-secondary h-40 flex items-center justify-center">
              <div class="text-white text-5xl"><i class="fas fa-box"></i></div>
            </div>
            <div class="p-6">
              <h2 class="text-xl font-bold text-gray-900 mb-2">${cat.name}</h2>
              <p class="text-gray-600 text-sm mb-4">${cat.description || 'Browse our collection'}</p>
              <span class="text-primary font-semibold hover:text-pink-600 transition">
                View Products →
              </span>
            </div>
          </a>
        `).join('');
      } else {
        container.innerHTML = '<p class="text-gray-500 col-span-full text-center">No categories available</p>';
      }
    } catch (error) {
      console.error('Error loading categories:', error);
      document.getElementById('categories-container').innerHTML = '<p class="text-red-500 col-span-full text-center">Failed to load categories. Please try again.</p>';
    }
  }

  loadCategories();
</script>
@endsection
