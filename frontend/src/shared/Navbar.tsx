import { useEffect, useState, useRef } from 'react'
import { Link, useNavigate } from 'react-router-dom'

export default function Navbar(){
  const navigate = useNavigate()
  const [isLoggedIn, setIsLoggedIn] = useState(false)
  const [user, setUser] = useState<any>(null)
  const [showGiftTooltip, setShowGiftTooltip] = useState(false)
  const [cartCount, setCartCount] = useState(0)

  useEffect(() => {
    // Check if user is logged in
    const token = localStorage.getItem('token')
    const userData = localStorage.getItem('user')
    
    if (token && userData) {
      try {
        const parsedUser = JSON.parse(userData)
        setUser(parsedUser)
        setIsLoggedIn(true)
      } catch (e) {
        console.error('Error parsing user data:', e)
        // Clear invalid data
        localStorage.removeItem('token')
        localStorage.removeItem('user')
      }
    }

    // Update cart count
    const updateCartCount = () => {
      try {
        const cartData = localStorage.getItem('cart')
        const cartItems = cartData ? JSON.parse(cartData) : []
        const count = cartItems.reduce((sum: number, item: any) => sum + item.qty, 0)
        setCartCount(count)
      } catch (e) {
        setCartCount(0)
      }
    }
    updateCartCount()
    window.addEventListener('storage', updateCartCount)
    window.addEventListener('cartUpdated', updateCartCount)
    return () => {
      window.removeEventListener('storage', updateCartCount)
      window.removeEventListener('cartUpdated', updateCartCount)
    }
  }, [])

  const handleSignOut = () => {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    setIsLoggedIn(false)
    setUser(null)
    navigate('/')
  }

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault()
    const searchInput = document.querySelector('.search-input') as HTMLInputElement
    if (searchInput?.value) {
      navigate(`/search?q=${encodeURIComponent(searchInput.value)}`)
    }
  }
  return (
    <nav>
      <div className="navbar1">
        <a className="logo" href="/" aria-label="Go to home" style={{ display: 'flex', alignItems: 'center' }}>
          <img className="img-fluid" src="/images/logo_wep___.png" alt="Tulip Store" style={{ minWidth: '140px', width: '150px', objectFit: 'contain' }} />
        </a>

        <div className="grid-2">
          <button
            type="button"
            id="gift"
            className="btn button-gift"
            style={{ position: 'relative', padding: '8px 12px', background: 'transparent', border: 'none', cursor: 'pointer', color: '#0d464c' }}
            onMouseEnter={() => setShowGiftTooltip(true)}
            onMouseLeave={() => setShowGiftTooltip(false)}
          >
            <i className="fa-solid fa-gift" style={{ fontSize: '18px' }}></i>
            <div style={{
              position: 'absolute',
              top: '120%',
              left: '50%',
              transform: 'translateX(-50%)',
              background: '#0d464c',
              color: 'white',
              padding: '10px 14px',
              borderRadius: '8px',
              fontSize: '13px',
              fontWeight: 600,
              whiteSpace: 'nowrap',
              zIndex: 1000,
              boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
              pointerEvents: 'none',
              transition: 'opacity 150ms ease, transform 150ms ease',
              opacity: showGiftTooltip ? 1 : 0,
              transformOrigin: 'top center',
              ...(showGiftTooltip ? { transform: 'translateX(-50%) translateY(0px)' } : { transform: 'translateX(-50%) translateY(-6px)' })
            }}>
              🎁 Gift arrangement
            </div>
          </button>

          {/* search */}
          <div className="search-container" style={{ position: 'relative' }}>
            <div className="search-box">
              <div className="search-icon" role="button" tabIndex={0} aria-label="Search">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                </svg>
              </div>
              <SearchInput />
              <div className="category-dropdown">
                <button className="category-select">All</button>
                <ul className="category-menu">
                  <li>All</li>
                  <li>Best Seller</li>
                  <li>Fashion</li>
                  <li>Electronics</li>
                  <li>Sport</li>
                  <li>School</li>
                  <li>Jewelry</li>
                  <li>Kitchen &amp; Home</li>
                  <li>Toys</li>
                  <li>Beauty &amp; Makeup</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <ul className="grid-3" style={{ alignItems: 'center' }}>
          {/* lang */}
          <li className="category-dropdown" style={{ display: 'flex', alignItems: 'center' }}>
            <button className="lang-btn">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                className="bi bi-translate"
                viewBox="0 0 16 16"
              >
                <path d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286zm1.634-.736L5.5 3.956h-.049l-.679 2.022z" />
                <path d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm7.138 9.995q.289.451.63.846c-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6 6 0 0 1-.415-.492 2 2 0 0 1-.94.31" />
              </svg>
            </button>
            <ul className="category-menu">
              <li>English</li>
              <li>العربية</li>
            </ul>
          </li>

          {/* before sign - show when not logged in */}
          {!isLoggedIn ? (
            <li className="sign-in" style={{ display: 'flex', alignItems: 'center' }}>
              <Link to="/signin" className="btn position-relative nav-btn" id="loginBtn" style={{ display: 'inline', background: '#ffffff9f', boxShadow: '2px 1px 8px #000000a2', color: '#0d464c', border: 'none', borderRadius: '20px', height: '35px', width: 'auto', fontSize: '16px', fontWeight: 700, padding: '8px 10px', textDecoration: 'none', transition: 'all 0.2s' }}>
                <i className="fa-solid fa-right-to-bracket"></i>
                <span className="btn-text" data-i18n="signIn" style={{ marginLeft: '6px' }}>Sign In</span>
              </Link>
            </li>
          ) : null}

          {/* after sign - show when logged in */}
          {isLoggedIn ? (
            <li className="profile" style={{ display: 'flex', alignItems: 'center' }}>
              <div className="dropdown after-s" id="after_s">
                <button className="profile-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i className="fa-solid fa-user"></i>
                  <span className="btn-text">{user?.name || 'Account'}</span>
                </button>
                <ul className="profile-menu dropdown-menu dropdown-menu-end">
                  <li><a className="dropdown-item" href="#">Edit profile</a></li>
                  <li><a className="dropdown-item" href="#">Orders</a></li>
                  <li><a className="dropdown-item" href="#">Settings</a></li>
                  <li><hr className="dropdown-divider" /></li>
                  <li>
                    <button
                      className="dropdown-item"
                      onClick={handleSignOut}
                      style={{ background: 'none', border: 'none', cursor: 'pointer', textAlign: 'left', width: '100%' }}
                    >
                      <i className="fa-solid fa-right-from-bracket"></i>
                      <span className="ms-2">Sign out</span>
                    </button>
                  </li>
                </ul>
              </div>
            </li>
          ) : null}

          {/* cart */}
          <li className="cart" style={{ display: 'flex', alignItems: 'center' }}>
            <button
              className="btn position-relative nav-btn"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasRight"
              aria-controls="offcanvasRight"
            >
              <i className="fa-solid fa-cart-shopping"></i>
              <span className="btn-text" data-i18n="cart">Cart</span>
              <span
                className="position-absolute top-0 start-100 translate-middle badge rounded-pill num"
                style={{ background: cartCount > 0 ? '#f05928' : '#ccc' }}
              >
                {cartCount}
              </span>
            </button>
          </li>
        </ul>
      </div>

      <div className="container-fluid navbar2">
        <ul className="nav-link">
          <li><a className="nav-link- mart" href="#">Tulip Mart</a></li>
          <li><a className="nav-link-" href="#">Today's Deal</a></li>
          <li><a className="nav-link-" href="#"> Gift Cards</a></li>
        </ul>
      </div>
    </nav>
  )
}

function SearchInput() {
  const containerRef = useRef<HTMLDivElement>(null)
  const debounceRef = useRef<NodeJS.Timeout | null>(null)
  const [products, setProducts] = useState<any[]>([])
  const [isOpen, setIsOpen] = useState(false)

  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
        setIsOpen(false)
      }
    }
    document.addEventListener('click', handleClickOutside)
    return () => document.removeEventListener('click', handleClickOutside)
  }, [])

  const fetchProducts = async (q: string) => {
    if (!q.trim()) {
      setIsOpen(false)
      setProducts([])
      return
    }
    try {
      const res = await fetch(`http://localhost:8000/api/products/search?q=${encodeURIComponent(q)}&order=date`)
      const data = await res.json()
      setProducts((data.data || []).slice(0, 10))
      setIsOpen(true)
    } catch (e) {
      console.error('Search failed:', e)
      setProducts([])
    }
  }

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value
    if (debounceRef.current) clearTimeout(debounceRef.current)
    debounceRef.current = setTimeout(() => fetchProducts(value), 300)
  }

  return (
    <div ref={containerRef} style={{ position: 'relative', width: '100%' }}>
      <input
        type="text"
        className="search-input"
        placeholder="Search products..."
        data-i18n-placeholder="searchPlaceholder"
        onChange={handleInput}
        onKeyDown={(e) => {
          if (e.key === 'Enter') {
            const value = (e.target as HTMLInputElement).value.trim()
            if (value) {
              window.location.href = `/search?q=${encodeURIComponent(value)}`
            }
          }
        }}
        style={{ width: '100%', border: 'none', outline: 'none', padding: '12px 0', fontSize: '15px', background: 'transparent', color: '#0d464c' }}
      />
      {isOpen && products.length > 0 && (
        <div style={{ position: 'absolute', top: '100%', left: '-12px', right: '-12px', background: '#fff', border: '1px solid #e5e9ef', borderTop: 'none', borderRadius: '0 0 12px 12px', maxHeight: '400px', overflowY: 'auto', marginTop: 0, zIndex: 1000, boxShadow: '0 8px 24px rgba(0,0,0,0.12)' }}>
          {products.map((p, idx) => (
            <div
              key={idx}
              onClick={() => {
                const pid = p.slug || String(p.id)
                window.location.href = `/product/${encodeURIComponent(pid)}`
              }}
              style={{
                padding: '14px 16px',
                cursor: 'pointer',
                display: 'flex',
                alignItems: 'center',
                gap: '14px',
                borderBottom: idx < products.length - 1 ? '1px solid #f0f0f0' : 'none',
                transition: 'all 0.2s ease'
              }}
              onMouseEnter={(e) => {
                (e.currentTarget.style.background = '#f8f9fa')
              }}
              onMouseLeave={(e) => (e.currentTarget.style.background = 'transparent')}
            >
              <img
                src={p.image || '/images/category/phone.jpeg'}
                alt={p.name}
                style={{ width: '56px', height: '56px', borderRadius: '8px', objectFit: 'cover', flexShrink: 0 }}
              />
              <div style={{ flex: 1, minWidth: 0 }}>
                <div style={{ fontWeight: 700, color: '#0d464c', fontSize: '14px', marginBottom: '4px', textOverflow: 'ellipsis', overflow: 'hidden', whiteSpace: 'nowrap' }}>{p.name}</div>
                <div style={{ fontSize: '13px', color: '#f05928', fontWeight: 600 }}>${Number(p.price).toFixed(2)}</div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
