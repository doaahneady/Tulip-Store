import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'

interface CartItem {
  id: string | number
  name: string
  price: number
  image: string
  qty: number
}

export default function CartOffcanvas(){
  const [items, setItems] = useState<CartItem[]>([])
  const [total, setTotal] = useState(0)

  useEffect(() => {
    const loadCart = () => {
      try {
        const cartData = localStorage.getItem('cart')
        const cartItems = cartData ? JSON.parse(cartData) : []
        setItems(cartItems)
        const totalAmount = cartItems.reduce((sum: number, item: CartItem) => sum + (item.price * item.qty), 0)
        setTotal(totalAmount)
      } catch (e) {
        console.error('Failed to load cart:', e)
      }
    }
    loadCart()
    window.addEventListener('storage', loadCart)
    window.addEventListener('cartUpdated', loadCart)
    return () => {
      window.removeEventListener('storage', loadCart)
      window.removeEventListener('cartUpdated', loadCart)
    }
  }, [])

  const updateQuantity = (id: string | number, qty: number) => {
    if (qty <= 0) {
      removeItem(id)
      return
    }
    const updated = items.map(item => item.id === id ? { ...item, qty } : item)
    setItems(updated)
    localStorage.setItem('cart', JSON.stringify(updated))
    window.dispatchEvent(new Event('cartUpdated'))
    const totalAmount = updated.reduce((sum: number, item: CartItem) => sum + (item.price * item.qty), 0)
    setTotal(totalAmount)
  }

  const removeItem = (id: string | number) => {
    const updated = items.filter(item => item.id !== id)
    setItems(updated)
    localStorage.setItem('cart', JSON.stringify(updated))
    window.dispatchEvent(new Event('cartUpdated'))
    const totalAmount = updated.reduce((sum: number, item: CartItem) => sum + (item.price * item.qty), 0)
    setTotal(totalAmount)
  }

  const closeCart = () => {
    const offcanvas = document.getElementById('offcanvasRight')
    if (offcanvas && window.bootstrap) {
      window.bootstrap.Offcanvas.getInstance(offcanvas)?.hide()
    }
  }

  return (
    <div
      className="offcanvas offcanvas-end"
      tabIndex={-1}
      id="offcanvasRight"
      aria-labelledby="offcanvasRightLabel"
      style={{ width: '100%', maxWidth: '420px' }}
    >
      {/* Header */}
      <div className="offcanvas-header" style={{ background: 'linear-gradient(135deg, #0d464c 0%, #1a5a63 100%)', color: 'white', padding: '16px', borderBottom: 'none' }}>
        <h5 className="offcanvas-title" id="offcanvasRightLabel" style={{ fontSize: '18px', fontWeight: 700, margin: 0 }}>
          🛍️ Shopping Cart
        </h5>
        <button type="button" className="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close" style={{ filter: 'brightness(1.2)' }}></button>
      </div>

      <div className="offcanvas-body" style={{ display: 'flex', flexDirection: 'column', padding: 0, height: '100%' }}>
        {items.length === 0 ? (
          <div style={{ flex: 1, display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', padding: '30px 20px', textAlign: 'center' }}>
            <div style={{ fontSize: '64px', marginBottom: '16px', opacity: 0.3 }}>🛒</div>
            <h6 style={{ color: '#0d464c', fontWeight: 700, marginBottom: '8px', fontSize: '16px' }}>Cart is Empty</h6>
            <p style={{ color: '#6b7785', fontSize: '13px', marginBottom: '24px' }}>Start adding items to your cart!</p>
            <button
              onClick={() => {
                closeCart()
                window.location.href = '/'
              }}
              style={{ backgroundColor: '#f05928', color: 'white', fontWeight: 600, padding: '10px 20px', fontSize: '13px', borderRadius: '8px', border: 'none', cursor: 'pointer', width: '100%' }}
            >
              Browse Products
            </button>
          </div>
        ) : (
          <>
            {/* Items Section */}
            <div style={{ flex: 1, overflowY: 'auto', borderBottom: '2px solid #f0f0f0' }}>
              {items.map((item, idx) => (
                <div key={item.id} style={{ padding: '12px 16px', borderBottom: idx < items.length - 1 ? '1px solid #f0f0f0' : 'none', display: 'flex', gap: '12px', alignItems: 'flex-start' }}>
                  {/* Product Image */}
                  <div style={{ position: 'relative', flexShrink: 0 }}>
                    <img src={item.image || '/images/category/phone.jpeg'} alt={item.name} style={{ width: '70px', height: '70px', borderRadius: '8px', objectFit: 'cover', border: '1px solid #e8ecef' }} />
                    <div style={{ position: 'absolute', top: '-6px', right: '-6px', background: '#f05928', color: 'white', width: '24px', height: '24px', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '11px', fontWeight: 700 }}>{item.qty}</div>
                  </div>

                  {/* Product Info */}
                  <div style={{ flex: 1, minWidth: 0 }}>
                    <div style={{ fontWeight: 600, color: '#0d464c', fontSize: '13px', marginBottom: '4px', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{item.name}</div>
                    <div style={{ fontSize: '12px', color: '#6b7785', marginBottom: '8px' }}>${Number(item.price).toFixed(2)} each</div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                      <button onClick={() => updateQuantity(item.id, item.qty - 1)} style={{ padding: '2px 6px', border: '1px solid #d9dfe4', borderRadius: '4px', background: 'white', color: '#0d464c', cursor: 'pointer', fontSize: '11px', fontWeight: 700, minWidth: '24px' }}>−</button>
                      <span style={{ fontSize: '12px', fontWeight: 600, minWidth: '20px', textAlign: 'center' }}>{item.qty}</span>
                      <button onClick={() => updateQuantity(item.id, item.qty + 1)} style={{ padding: '2px 6px', border: '1px solid #d9dfe4', borderRadius: '4px', background: 'white', color: '#0d464c', cursor: 'pointer', fontSize: '11px', fontWeight: 700, minWidth: '24px' }}>+</button>
                      <span style={{ marginLeft: 'auto', fontWeight: 700, color: '#f05928', fontSize: '13px' }}>${(item.price * item.qty).toFixed(2)}</span>
                    </div>
                  </div>

                  {/* Remove Button */}
                  <button onClick={() => removeItem(item.id)} style={{ background: 'none', border: 'none', color: '#dc3545', cursor: 'pointer', fontSize: '16px', padding: '4px', flexShrink: 0 }} title="Remove">
                    <i className="fa-solid fa-trash-alt"></i>
                  </button>
                </div>
              ))}
            </div>

            {/* Footer */}
            <div style={{ padding: '14px 16px', background: '#fafbfc' }}>
              <div style={{ marginBottom: '10px', paddingBottom: '10px', borderBottom: '1px solid #e8ecef' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '12px', color: '#6b7785', marginBottom: '4px' }}>
                  <span>Subtotal</span>
                  <span>${Number(total).toFixed(2)}</span>
                </div>
                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '12px', color: '#6b7785' }}>
                  <span>Shipping</span>
                  <span>Free</span>
                </div>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '16px', fontWeight: 700, color: '#0d464c', marginBottom: '12px' }}>
                <span>Total</span>
                <span style={{ color: '#f05928' }}>${Number(total).toFixed(2)}</span>
              </div>
              <Link to="/checkout" onClick={closeCart} style={{ display: 'block', backgroundColor: '#0d464c', color: 'white', fontWeight: 600, padding: '10px', fontSize: '13px', borderRadius: '8px', textDecoration: 'none', textAlign: 'center', marginBottom: '8px', transition: 'all 0.3s' }}>
                <i className="fa-solid fa-lock" style={{ marginRight: '6px' }}></i>Proceed to Checkout
              </Link>
              <button onClick={() => closeCart()} style={{ width: '100%', padding: '10px', fontSize: '13px', borderRadius: '8px', border: '1px solid #d9dfe4', background: 'white', color: '#0d464c', fontWeight: 600, cursor: 'pointer', transition: 'all 0.3s' }}>
                Continue Shopping
              </button>
            </div>
          </>
        )}
      </div>
    </div>
  )
}
