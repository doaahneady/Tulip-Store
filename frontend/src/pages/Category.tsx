import { useEffect, useMemo, useState } from 'react'
import { useLocation, useParams } from 'react-router-dom'
import { apiGet } from '../lib/api'
import type { Product } from '../lib/types'

function useQuery() {
  const { search } = useLocation()
  return useMemo(() => new URLSearchParams(search), [search])
}

export default function Category() {
  const { slug = '' } = useParams()
  const q = useQuery()
  const location = useLocation()
  const [category, setCategory] = useState<{ name: string; description?: string }>({ name: decodeURIComponent(slug) })
  const [filters, setFilters] = useState<Record<string, string[]>>({})
  const [products, setProducts] = useState<Product[]>([])
  const [loading, setLoading] = useState(true)

  const order = q.get('order') || 'date'
  const searchQuery = q.get('q') || ''

  useEffect(() => {
    setLoading(true)
    const decodedSlug = encodeURIComponent(slug)

    // Collect all active filters from the URL (besides order and q)
    const filterParams: any = { order, q: searchQuery }
    q.forEach((value, key) => {
      if (key !== 'order' && key !== 'q') {
        filterParams[key] = value
      }
    })

    Promise.all([
      apiGet<any>(`/api/categories/${decodedSlug}`),
      apiGet<any>(`/api/categories/${decodedSlug}/filters`),
      apiGet<any>(`/api/categories/${decodedSlug}/products`, filterParams)
    ])
      .then(([catRes, filtersRes, prodsRes]) => {
        if (catRes?.data) {
          setCategory({
            name: catRes.data.name || catRes.data.title || decodeURIComponent(slug),
            description: catRes.data.description
          })
        }
        if (filtersRes?.data) {
          setFilters(filtersRes.data)
        }
        setProducts(prodsRes?.data || prodsRes?.results || [])
      })
      .catch(err => console.error('Failed to load category data', err))
      .finally(() => setLoading(false))
  }, [slug, location.search])

  const addToCart = (product: Product, e?: React.MouseEvent) => {
    if (e) e.stopPropagation()
    try {
      const key = 'cart'
      const items = JSON.parse(localStorage.getItem(key) || '[]')
      const id = product.id
      const idx = items.findIndex((i: any) => String(i.id) === String(id))
      if (idx >= 0) {
        items[idx].qty += 1
      } else {
        items.push({
          id,
          name: product.name,
          price: Number(product.price || 0),
          image: product.image,
          qty: 1
        })
      }
      localStorage.setItem(key, JSON.stringify(items))
      window.dispatchEvent(new Event('cartUpdated'))
      const el = document.getElementById('offcanvasRight')
      // @ts-ignore
      if (el && window.bootstrap) {
        window.bootstrap.Offcanvas.getOrCreateInstance(el).show()
      }
    } catch (e) {
      console.error('Error adding to cart', e)
    }
  }

  return (
    <main className="products-view" id="main-view">
      <div className="container" style={{ display: 'flex', gap: '24px', marginTop: '24px', marginBottom: '40px' }}>
        {/* Sidebar Filters */}
        <aside style={{ width: '280px', flexShrink: 0 }}>
          <div style={{ padding: '24px', background: 'linear-gradient(135deg, #ffffff99 0%, #ffffffcc 100%)', backdropFilter: 'blur(8px)', border: '1px solid rgba(255,255,255,0.5)', borderRadius: '14px', boxShadow: '0 8px 32px rgba(0,0,0,0.08)', position: 'sticky', top: '20px' }}>
            <h3 style={{ marginBottom: '20px', fontSize: '18px', fontWeight: 700, color: '#0d464c', display: 'flex', alignItems: 'center', gap: '8px' }}><i className="fa-solid fa-filter" style={{ color: '#f05928' }}></i>Filters</h3>
            <div style={{ borderBottom: '2px solid #f05928', marginBottom: '20px', opacity: 0.3 }}></div>
            
            {/* Sort */}
            <div style={{ marginBottom: '20px' }}>
              <label style={{ display: 'block', marginBottom: '10px', fontWeight: 700, color: '#0d464c', fontSize: '13px', textTransform: 'uppercase', letterSpacing: '0.5px' }} data-i18n="order">Sort By</label>
              <select value={order} onChange={(e) => {
                const params = new URLSearchParams(q)
                params.set('order', e.target.value)
                window.location.search = params.toString()
              }} style={{ width: '100%', padding: '10px 12px', borderRadius: '8px', border: '1px solid #d9dfe4', fontSize: '14px', background: 'white', cursor: 'pointer', transition: 'all 0.3s ease' }}>
                <option value="date" data-i18n="newest">Newest</option>
                <option value="price" data-i18n="priceLabel">Price: Low to High</option>
                <option value="rate" data-i18n="rate">Highest Rated</option>
                <option value="sales" data-i18n="popular">Most Popular</option>
              </select>
            </div>

            {/* Price Range */}
            <div style={{ marginBottom: '20px', paddingBottom: '20px', borderBottom: '1px solid #e8ecef' }}>
              <label style={{ display: 'block', marginBottom: '10px', fontWeight: 700, color: '#0d464c', fontSize: '13px', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Price Range</label>
              <select name="price" value={q.get('price') || ''} onChange={(e) => {
                const params = new URLSearchParams(q)
                if (e.target.value) params.set('price', e.target.value)
                else params.delete('price')
                window.location.search = params.toString()
              }} style={{ width: '100%', padding: '10px 12px', borderRadius: '8px', border: '1px solid #d9dfe4', fontSize: '14px', background: 'white', cursor: 'pointer', transition: 'all 0.3s ease' }}>
                <option value="">All Prices</option>
                <option value="0-50">$0 - $50</option>
                <option value="50-100">$50 - $100</option>
                <option value="100-500">$100 - $500</option>
                <option value="500+">$500+</option>
              </select>
            </div>

            {/* Rating */}
            <div style={{ marginBottom: '20px', paddingBottom: '20px', borderBottom: '1px solid #e8ecef' }}>
              <label style={{ display: 'block', marginBottom: '10px', fontWeight: 700, color: '#0d464c', fontSize: '13px', textTransform: 'uppercase', letterSpacing: '0.5px' }}>Rating</label>
              <select name="rating" value={q.get('rating') || ''} onChange={(e) => {
                const params = new URLSearchParams(q)
                if (e.target.value) params.set('rating', e.target.value)
                else params.delete('rating')
                window.location.search = params.toString()
              }} style={{ width: '100%', padding: '10px 12px', borderRadius: '8px', border: '1px solid #d9dfe4', fontSize: '14px', background: 'white', cursor: 'pointer', transition: 'all 0.3s ease' }}>
                <option value="">All Ratings</option>
                <option value="5">★★★★★ (5 Stars)</option>
                <option value="4">★★★★ (4+ Stars)</option>
                <option value="3">★★★ (3+ Stars)</option>
              </select>
            </div>

            {/* Dynamic Category Filters */}
            {Object.keys(filters || {}).map((key) => (
              <div key={key} style={{ marginBottom: '20px', paddingBottom: '20px', borderBottom: '1px solid #e8ecef' }}>
                <label style={{ display: 'block', marginBottom: '10px', fontWeight: 700, color: '#0d464c', fontSize: '13px', textTransform: 'uppercase', letterSpacing: '0.5px' }}>{key}</label>
                <select name={key} value={q.get(key) || ''} onChange={(e) => {
                  const params = new URLSearchParams(q)
                  if (e.target.value) params.set(key, e.target.value)
                  else params.delete(key)
                  window.location.search = params.toString()
                }} style={{ width: '100%', padding: '10px 12px', borderRadius: '8px', border: '1px solid #d9dfe4', fontSize: '14px', background: 'white', cursor: 'pointer', transition: 'all 0.3s ease' }}>
                  <option value="">All</option>
                  {(filters[key] || []).map((v) => (
                    <option key={v} value={v}>{v}</option>
                  ))}
                </select>
              </div>
            ))}
          </div>
        </aside>

        {/* Main Content */}
        <div style={{ flex: 1 }}>
          <nav aria-label="breadcrumb" style={{ marginBottom: '16px' }}>
            <div style={{ background: '#ffffffcc', border: '1px solid #e8ecef', borderRadius: '10px', padding: '8px 12px' }}>
              <ol className="breadcrumb" style={{ background: 'transparent', padding: 0, margin: 0 }}>
                <li className="breadcrumb-item"><a href="/" data-i18n="home">Home</a></li>
                <li className="breadcrumb-item active" aria-current="page">{category.name}</li>
              </ol>
            </div>
          </nav>
          <div className="header" style={{ marginBottom: '32px' }}>
            <h1 style={{ fontSize: '36px', fontWeight: 700, color: '#0d464c', marginBottom: '8px', display: 'flex', alignItems: 'center', gap: '12px' }}>
              <span style={{ width: '4px', height: '40px', background: 'linear-gradient(135deg, #f05928, #ff7043)', borderRadius: '2px' }}></span>
              {category.name}
            </h1>
            {category.description && <p style={{ color: '#6b7785', fontSize: '15px', margin: 0 }}>{category.description}</p>}
          </div>
          <div className="products">
          {!loading && products.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '40px', width: '100%' }}>
              <p>No products found</p>
            </div>
          ) : loading ? (
            <div style={{ textAlign: 'center', padding: '40px', width: '100%' }}>
              <div className="spinner-border" role="status">
                <span className="visually-hidden">Loading...</span>
              </div>
            </div>
          ) : (
            products.map((p) => {
              const pslug = p.slug || String(p.id || p.code || p.sku || p.name || '').toLowerCase()
              return (
                <div key={pslug} className="product" data-product-id={pslug} style={{ border: '1px solid #e8ecef', borderRadius: '12px', overflow: 'hidden', background: '#fff', boxShadow: '0 6px 16px rgba(0,0,0,0.06)' }}>
                  <a href={`/product/${encodeURIComponent(String(pslug))}`} style={{ cursor: 'pointer' }}>
                    <div className="image" style={{ height: '200px' }}>
                      <img src={p.image || '/images/category/phone.jpeg'} alt={p.name} />
                    </div>
                  </a>
                <div className="name_price" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                  <h3 style={{ margin: 0 }}>{p.name}</h3>
                  <span style={{ color: '#ff6b35', fontWeight: 700 }}>${Number(p.price || 0)}</span>
                </div>
                  <p>{(p.description || '')}</p>
                  <div className="add-cart">
                    <button type="button" className="add-btn" onClick={(e) => addToCart(p, e)} title="Add to cart">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" className="bi bi-cart-plus" viewBox="0 0 16 16">
                        <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                      </svg>
                    </button>
                  </div>
                </div>
              )
            })
          )}
        </div>
        </div>
      </div>
    </main>
  )
}
