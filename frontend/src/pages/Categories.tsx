import { useEffect, useState } from 'react'
import { apiGet } from '../lib/api'
import type { Category } from '../lib/types'

export default function Categories() {
  const [categories, setCategories] = useState<Category[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const loadCategories = async () => {
      try {
        const res = await apiGet<{ data: Category[] }>('/api/categories')
        setCategories(res.data || [])
      } catch (e) {
        console.error('Failed to load categories', e)
      } finally {
        setLoading(false)
      }
    }
    loadCategories()
  }, [])

  return (
    <main className="products-view" id="main-view">
      <nav aria-label="breadcrumb">
        <ol className="breadcrumb">
          <li className="breadcrumb-item"><a href="/" data-i18n="home">Home</a></li>
          <li className="breadcrumb-item active" aria-current="page">Categories</li>
        </ol>
      </nav>
      <div className="container">
        <div className="products">
          {loading ? (
            <div style={{ textAlign: 'center', padding: '40px', width: '100%' }}>
              <div className="spinner-border" role="status">
                <span className="visually-hidden">Loading...</span>
              </div>
            </div>
          ) : (
            categories.map((cat) => (
              <div key={cat.id} className="product">
                <a href={`/category/${cat.slug}`} style={{ cursor: 'pointer' }}>
                  <div className="image"><img src="/images/category/phone.jpeg" alt={cat.name} /></div>
                </a>
                <div className="name_price">
                  <h3>{cat.name}</h3>
                </div>
              </div>
            ))
          )}
        </div>
      </div>
    </main>
  )
}
