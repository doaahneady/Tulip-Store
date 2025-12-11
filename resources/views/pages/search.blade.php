@extends('app')

@section('title', 'Search - Tulip Store')

@section('content')
<div class="container-custom py-12">
  <!-- Search Form -->
  <div class="mb-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Search Products</h1>
    <form id="search-form" class="flex gap-4">
      <input 
        type="text" 
        id="search-input"
        placeholder="Search for products..." 
        class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
      >
      <button 
        type="submit" 
        class="btn-primary text-lg"
      >
        <i class="fas fa-search mr-2"></i> Search
      </button>
    </form>
  </div>

  <!-- Results -->
  <div>
    <h2 id="results-title" class="text-2xl font-bold text-gray-900 mb-8">
      Search Results
    </h2>
    
    <div id="results-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="card p-6 text-center col-span-full">
        <p class="text-gray-500">Enter a search term to find products</p>
      </div>
    </div>
  </div>
</div>

<script>
  const searchInput = document.getElementById('search-input');
  const searchForm = document.getElementById('search-form');
  const resultsContainer = document.getElementById('results-container');
  const resultsTitle = document.getElementById('results-title');

  // Load search query from URL
  const params = new URLSearchParams(window.location.search);
  const query = params.get('q');
  
  if (query) {
    searchInput.value = query;
    performSearch(query);
  }

  searchForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const query = searchInput.value.trim();
    if (query) {
      window.location.href = `/search?q=${encodeURIComponent(query)}`;
    }
  });

  async function performSearch(query) {
    try {
      resultsContainer.innerHTML = `
        <div class="col-span-full text-center py-8">
          <i class="fas fa-spinner text-primary text-4xl mb-4 animate-spin"></i>
          <p class="text-gray-500">Searching for "${query}"...</p>
        </div>
      `;

      const response = await fetch(`/api/products/search?q=${encodeURIComponent(query)}`);
      if (!response.ok) throw new Error('Search failed');
      
      const data = await response.json();
      const products = data.data || [];

      resultsTitle.textContent = `Search Results for "${query}" (${products.length} found)`;

      if (products.length > 0) {
        resultsContainer.innerHTML = products.map(product => `
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
        resultsContainer.innerHTML = `
          <div class="col-span-full text-center py-8">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
            <p class="text-gray-500">Try searching for different keywords</p>
          </div>
        `;
      }
    } catch (error) {
      console.error('Search error:', error);
      resultsContainer.innerHTML = '<p class="text-red-500 col-span-full text-center">Failed to perform search. Please try again.</p>';
    }
  }
</script>
@endsection
