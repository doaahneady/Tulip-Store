import { useEffect, useMemo, useState } from 'react'
import { Link, useLocation } from 'react-router-dom'
import { apiGet } from '../lib/api'
import type { Product } from '../lib/types'

function useQuery() {
  const { search } = useLocation()
  return useMemo(() => new URLSearchParams(search), [search])
}

export default function Search() {
  const q = useQuery()
  const term = q.get('q') || ''
  const order = q.get('order') || 'date'
  const [products, setProducts] = useState<Product[]>([])
  const [loading, setLoading] = useState(true)
  const [searchInput, setSearchInput] = useState(term)

  useEffect(() => {
    setLoading(true)
    if (!term) {
      setProducts([])
      setLoading(false)
      return
    }

    apiGet<{ data: Product[] }>('/api/products/search', { q: term, order })
      .then(res => {
        setProducts(res.data || [])
      })
      .catch(err => {
        console.error('Search failed', err)
        setProducts([])
      })
      .finally(() => setLoading(false))
  }, [term, order])

  const handleOrderChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const params = new URLSearchParams(q)
    params.set('order', e.target.value)
    window.location.search = params.toString()
  }

  const addToCart = (product: Product) => {
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
    <main className="products-view">
      <h1>Search</h1>
      <form method="get">
        <input type="text" name="q" value={searchInput} onChange={(e) => setSearchInput(e.target.value)} placeholder="Search products" />
        <select name="order" value={order} onChange={handleOrderChange}>
          <option value="date">Newest</option>
          <option value="price">Price</option>
          <option value="rate">Rate</option>
          <option value="sales">Popular</option>
        </select>
        <button type="submit">Search</button>
      </form>

      <div>
        {loading ? (
          <div style={{ textAlign: 'center', padding: '40px' }}>
            <div className="spinner-border" role="status">
              <span className="visually-hidden">Loading...</span>
            </div>
          </div>
        ) : (
          products.map((p) => {
            const pslug = p.slug || String(p.id || p.code || p.sku || p.name || '').toLowerCase()
            return (
              <div key={pslug} style={{ border: '1px solid #ddd', margin: '8px', padding: '8px' }}>
                <h3><a href={`/product/${encodeURIComponent(String(pslug))}`}>{p.name}</a></h3>
                <div>Price: ${p.price}</div>
                <div>Rate: {(p as any).rate}</div>
              </div>
            )
          })
        )}
      </div>

      <p><a href="/">Home</a></p>
    </main>
  )
}
