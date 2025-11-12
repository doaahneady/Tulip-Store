import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { apiGet } from '../lib/api'
import type { Category } from '../lib/types'
import '../styles/home.css'

export default function Home() {
  const [categories, setCategories] = useState<Category[]>([])
  const [categoryProducts, setCategoryProducts] = useState<Record<string, any[]>>({})

  useEffect(() => {
    const loadCategories = async () => {
      try {
        const res = await apiGet<Category[]>('/api/categories')
        const cats = Array.isArray(res) ? res : res?.data || []
        setCategories(cats)

        // Load up to 4 products per category for preview images
        const productsMap: Record<string, any[]> = {}
        await Promise.all(
          cats.map(async (cat: any) => {
            try {
              const pres = await apiGet<any>(`/api/categories/${encodeURIComponent(cat.slug)}/products`, { limit: 4 })
              productsMap[cat.slug] = (pres?.data || pres?.results || []).slice(0, 4)
            } catch {
              productsMap[cat.slug] = []
            }
          })
        )
        setCategoryProducts(productsMap)
      } catch (e) {
        console.error('Failed to load categories', e)
      }
    }
    loadCategories()
  }, [])

  const categoryImages: Record<string, string> = {
    'fashion': '/images/category/1.2women.jpg',
    'electronics': '/images/category/2.1phone.jpeg',
    'books': '/images/category/6.1bags.jpg',
  }

  const getCategoryTitle = (name: string): string => {
    const lower = name.toLowerCase()
    if (lower.includes('fashion')) return 'Fashions'
    if (lower.includes('electron')) return 'Electronics'
    if (lower.includes('toy')) return 'Toys'
    if (lower.includes('sport')) return 'Sports'
    if (lower.includes('jewelry')) return 'Jewelry'
    if (lower.includes('book')) return 'Back To School'
    if (lower.includes('care') || lower.includes('beauty')) return 'Care'
    return name
  }

  const getImageForCategory = (name: string): string => {
    const lower = (name || '').toLowerCase()
    if (lower.includes('fashion')) return categoryImages['fashion']
    if (lower.includes('electron')) return categoryImages['electronics']
    if (lower.includes('book')) return categoryImages['books']
    return '/images/category/1.2women.jpg'
  }

  return (
    <main>
      <div className="products-view" id="main-view">
        {/* Carousel */}
        <div id="carouselExampleAutoplaying" className="carousel slide" data-bs-ride="carousel">
          <div className="carousel-inner">
            <div className="carousel-item active">
              <img src="/images/111.jpg" className="d-block w-100" alt="Banner 1" />
            </div>
            <div className="carousel-item">
              <img src="/images/222.jpg" className="d-block w-100" alt="Banner 2" />
            </div>
            <div className="carousel-item">
              <img src="/images/333.jpg" className="d-block w-100" alt="Banner 3" />
            </div>
          </div>
          <button className="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
            <span className="carousel-control-prev-icon" aria-hidden="true"></span>
            <span className="visually-hidden">Previous</span>
          </button>
          <button className="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
            <span className="carousel-control-next-icon" aria-hidden="true"></span>
            <span className="visually-hidden">Next</span>
          </button>
        </div>

        {/* Category Cards */}
        <div className="cards">
          {categories.length === 0 ? (
            <div style={{ padding: '40px', textAlign: 'center', width: '100%' }}>Loading categories...</div>
          ) : (
            categories.map((cat) => {
              const products = categoryProducts[cat.slug]
              const hasProducts = products && products.length > 0
              const images = hasProducts ? products : Array(4).fill(getImageForCategory(cat.name))
              return (
                <div key={cat.id} className="card-product">
                  <h5 className="card-title mb-3">{getCategoryTitle(cat.name)}</h5>
                  <div className="card-grid">
                    <Link to={`/category/${cat.slug}`} style={{ textDecoration: 'none', color: 'inherit', display: 'contents' }}>
                      {images.slice(0, 4).map((p:any, idx:number) => {
                        const img = (hasProducts && p?.image) ? p.image : getImageForCategory(cat.name)
                        return (
                          <img key={idx} src={img} className="figure-img img-fluid rounded" alt={`${cat.name} ${idx + 1}`} style={{ width: '100%', height: '100%', objectFit: 'cover', borderRadius: '12px', display: 'block' }} loading="lazy" />
                        )
                      })}
                    </Link>
                    <div style={{ gridColumn: '1 / -1', marginTop: '8px' }}>
                      <Link to={`/category/${cat.slug}`} style={{ textDecoration: 'none', color: 'inherit' }}>
                        <div className="figure-caption" style={{ fontWeight: 600 }}>{cat.name}</div>
                      </Link>
                    </div>
                  </div>
                </div>
              )
            })
          )}
        </div>
      </div>
    </main>
  )
}
