import { useEffect, useState } from 'react'
import { useParams, Link } from 'react-router-dom'
import { apiGet } from '../lib/api'
import type { Product } from '../lib/types'
import '../styles/product.css'

function resolveImg(u?: string){
  if (!u) return '/images/cover_phone.jpg'
  if (/^https?:\/\//i.test(u)) return u
  if (u.startsWith('/images') || u.startsWith('images/')) return u.startsWith('/') ? u : '/'+u
  if (u.startsWith('/')) return 'http://localhost:8000'+u
  return 'http://localhost:8000/'+u
}

export default function ProductPage(){
  const { idOrSlug = '' } = useParams()
  const [p, setP] = useState<Product | null>(null)
  const [error, setError] = useState<string | null>(null)
  const [qty, setQty] = useState(1)

  useEffect(() => {
    async function load(){
      setError(null)
      try {
        let payload: any = null
        try { payload = await apiGet<any>(`/api/products/${encodeURIComponent(idOrSlug)}`) } catch {}
        if (!payload) {
          const q = await apiGet<any>(`/api/products/search`, { q: idOrSlug })
          const list = Array.isArray(q?.data) ? q.data : []
          const lower = String(idOrSlug).toLowerCase()
          payload = list.find((x:any)=> String(x.slug||'').toLowerCase()===lower) ||
                    list.find((x:any)=> String(x.id)===String(idOrSlug)) ||
                    list[0]
        } else if (payload?.data) {
          payload = Array.isArray(payload.data) ? payload.data[0] : payload.data
        }
        if (!payload) { setError('Product not found'); return }
        const images = Array.isArray(payload.images) ? payload.images : (payload.gallery || payload.photos || [])
        const mainImage = payload.image || images?.[0] || '/images/cover_phone.jpg'
        setP({
          id: payload.id || payload.slug || idOrSlug,
          slug: payload.slug,
          name: payload.name || payload.title || 'Product',
          price: Number(payload.price || payload.sale_price || payload.amount || 0),
          description: payload.description || payload.details || '',
          image: mainImage,
          images: images && images.length ? images : [mainImage],
          attributes: payload.attributes || payload.attrs || {},
          category: payload.category || null,
        })
      } catch {
        setError('Failed to load product')
      }
    }
    load()
  }, [idOrSlug])

  if (error) return <div className="container py-4">{error}</div>
  if (!p) return <div className="container py-4">Loading…</div>

  const catSlug = p.category && (typeof p.category === 'string' ? p.category : (p.category.slug || ''))
  const catName = p.category && (typeof p.category === 'string' ? p.category : (p.category.name || ''))

  const handleQtyChange = (val: number) => {
    setQty(Math.max(1, val))
  }

  const handleAddToCart = () => {
    try {
      const key = 'cart'
      const items = JSON.parse(localStorage.getItem(key) || '[]')
      const id = p.id
      const idx = items.findIndex((i: any) => String(i.id) === String(id))
      if (idx >= 0) items[idx].qty += qty
      else items.push({ id, name: p.name, price: Number(p.price || 0), image: p.image, qty })
      localStorage.setItem(key, JSON.stringify(items))
      window.dispatchEvent(new Event('cartUpdated'))
      const el = document.getElementById('offcanvasRight')
      // @ts-ignore
      if (el && window.bootstrap) window.bootstrap.Offcanvas.getOrCreateInstance(el).show()
    } catch {}
  }

  const handleBuyNow = () => {
    try {
      const key = 'cart'
      const items = JSON.parse(localStorage.getItem(key) || '[]')
      const id = p.id
      const idx = items.findIndex((i: any) => String(i.id) === String(id))
      if (idx >= 0) items[idx].qty = qty
      else items.push({ id, name: p.name, price: Number(p.price || 0), image: p.image, qty })
      localStorage.setItem(key, JSON.stringify(items))
      window.dispatchEvent(new Event('cartUpdated'))
    } catch {}
    window.location.href = '/checkout'
  }

  return (
    <>
      <div className="container mt-3">
        <nav aria-label="breadcrumb">
          <ol className="breadcrumb">
            <li className="breadcrumb-item"><Link to="/">Home</Link></li>
            {catName && <li className="breadcrumb-item"><Link to={`/category/${encodeURIComponent(catSlug)}`}>{catName}</Link></li>}
            <li className="breadcrumb-item active" aria-current="page">{p.name}</li>
          </ol>
        </nav>
      </div>

      <div className="container my-4 product-detail">
        <div className="row g-5 align-items-start pd-wrapper">
          {/* Product Image */}
          <div className="col-lg-6 product-gallery">
            <div className="mb-3 position-relative gallery-card glass">
              <img
                id="mainProductImage"
                src={resolveImg(p.image)}
                className="img-fluid rounded w-100 main-photo"
                alt={p.name}
              />
              {p.images && p.images.length > 1 && (
                <div className="d-flex align-items-center mt-3 thumbs-bar">
                  <div id="thumbScroll" className="d-flex overflow-auto" style={{ gap: '16px', width: '100%', scrollBehavior: 'smooth', direction: 'rtl', paddingBottom: '8px' }}>
                    {p.images.map((img, idx) => (
                      <img
                        key={idx}
                        src={resolveImg(img)}
                        className="img-thumbnail thumb-img"
                        alt={`Thumb ${idx + 1}`}
                        onClick={() => setP({ ...p, image: img })}
                      />
                    ))}
                  </div>
                </div>
              )}
            </div>
          </div>

          {/* Product Details */}
          <div className="col-lg-6 product-info-card glass">
            <h1 className="product-title">{p.name}</h1>
            {catName && <div className="category-name">{catName}</div>}
            <div className="price">
              <span className="sale-price">${Number(p.price || 0).toFixed(2)}</span>
            </div>

            <p className="mb-4">{p.description || ''}</p>

            {p.attributes && Object.keys(p.attributes).length > 0 && (
              <div className="mb-3 attribute">
                <div style={{ marginBottom: '8px' }}>Attributes:</div>
                {Object.entries(p.attributes).map(([key, val]) => (
                  <div key={key} className="mt-2">
                    <strong>{key}:</strong> <span className="att-values">
                      {Array.isArray(val) ? (
                        val.map((v, i) => <span key={i} className="chip">{v}</span>)
                      ) : (
                        <span className="chip">{String(val)}</span>
                      )}
                    </span>
                  </div>
                ))}
              </div>
            )}

            {/* Quantity Selector */}
            <div className="d-flex align-items-center mb-4">
              <div className="quantity-selector">
                <button className="quantity-btn" id="qtyDec" onClick={() => handleQtyChange(qty - 1)}>-</button>
                <input type="text" className="quantity-input" id="qtyInput" value={qty} onChange={(e) => handleQtyChange(parseInt(e.target.value) || 1)} />
                <button className="quantity-btn" id="qtyInc" onClick={() => handleQtyChange(qty + 1)}>+</button>
              </div>
            </div>

            {/* Buttons */}
            <div className="d-flex mb-4 buttons">
              <button className="btn btn-share-now" id="shareBtn" onClick={() => {
                if (navigator.share) {
                  navigator.share({ title: p.name, text: p.description || '', url: window.location.href }).catch(() => {})
                }
              }}>Share now</button>
              <button className="btn btn-add-to-cart add-btn" onClick={handleAddToCart}>Add To Cart</button>
              <button className="btn btn-buy-now" id="buyNowBtn" onClick={handleBuyNow}>Buy Now</button>
            </div>
          </div>
        </div>
      </div>

      {/* Product Description Tabs */}
      <div className="container my-5">
        <ul className="nav nav-tabs" id="productTabs" role="tablist">
          <li className="nav-item" role="presentation">
            <button className="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
          </li>
          <li className="nav-item" role="presentation">
            <button className="nav-link" id="information-tab" data-bs-toggle="tab" data-bs-target="#information" type="button" role="tab">Additional Information</button>
          </li>
        </ul>

        <div className="tab-content" id="productTabsContent">
          <div className="tab-pane fade show active" id="description" role="tabpanel">
            <div className="section-card">
              <h5 className="section-title"><i className="fa-solid fa-align-left"></i> <span>Description</span></h5>
              <div className="section-body prose">{p.description || 'No description available.'}</div>
            </div>
          </div>

          <div className="tab-pane fade" id="information" role="tabpanel">
            <div className="section-card">
              <h5 className="section-title"><i className="fa-solid fa-circle-info"></i> <span>Additional Details</span></h5>
              <div className="details-grid">
                <div className="detail-item">
                  <span className="detail-label">Code</span>
                  <span className="detail-value">{p.id}</span>
                </div>
                <div className="detail-item">
                  <span className="detail-label">Category</span>
                  <span className="detail-value">{catName || '-'}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
