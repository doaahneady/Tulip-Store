import { useState } from 'react'
import { apiGet, apiPost } from '../lib/api'

export default function Debug() {
  const [results, setResults] = useState<any[]>([])
  const [loading, setLoading] = useState(false)

  const testEndpoint = async (name: string, method: 'GET' | 'POST', path: string, body?: any) => {
    setLoading(true)
    const timestamp = new Date().toLocaleTimeString()

    try {
      let data
      if (method === 'GET') {
        data = await apiGet<any>(path)
      } else {
        data = await apiPost<any>(path, body)
      }

      setResults(prev => [{
        timestamp,
        name,
        method,
        path,
        status: 'SUCCESS',
        response: JSON.stringify(data, null, 2)
      }, ...prev])
    } catch (error: any) {
      setResults(prev => [{
        timestamp,
        name,
        method,
        path,
        status: 'ERROR',
        response: error.message || String(error)
      }, ...prev])
    } finally {
      setLoading(false)
    }
  }

  return (
    <main className="products-view">
      <div className="container-fluid py-4">
        <h1 className="mb-4">API Debug Console</h1>

        {/* Test Buttons */}
        <div className="card mb-4">
          <div className="card-header bg-dark text-white">
            <h5 className="mb-0">API Tests</h5>
          </div>
          <div className="card-body">
            <div className="row g-2 mb-3">
              <div className="col-auto">
                <button
                  className="btn btn-sm btn-primary"
                  onClick={() => testEndpoint('Get Categories', 'GET', '/api/categories')}
                  disabled={loading}
                >
                  <i className="fa-solid fa-list me-1" /> Get Categories
                </button>
              </div>
              <div className="col-auto">
                <button
                  className="btn btn-sm btn-info"
                  onClick={() => testEndpoint('Get Products', 'GET', '/api/products')}
                  disabled={loading}
                >
                  <i className="fa-solid fa-box me-1" /> Get Products
                </button>
              </div>
              <div className="col-auto">
                <button
                  className="btn btn-sm btn-warning"
                  onClick={() => testEndpoint('Search Products', 'GET', '/api/products/search', { q: 'phone' })}
                  disabled={loading}
                >
                  <i className="fa-solid fa-search me-1" /> Search
                </button>
              </div>
              <div className="col-auto">
                <button
                  className="btn btn-sm btn-danger"
                  onClick={() => testEndpoint('Test Register', 'POST', '/auth/register', {
                    name: 'Test User',
                    email: 'test@example.com',
                    password: 'password123',
                    password_confirmation: 'password123'
                  })}
                  disabled={loading}
                >
                  <i className="fa-solid fa-user-plus me-1" /> Test Register
                </button>
              </div>
              <div className="col-auto">
                <button
                  className="btn btn-sm btn-secondary"
                  onClick={() => setResults([])}
                  disabled={loading}
                >
                  <i className="fa-solid fa-trash me-1" /> Clear
                </button>
              </div>
            </div>
          </div>
        </div>

        {/* Results */}
        <div className="card">
          <div className="card-header bg-dark text-white">
            <h5 className="mb-0">Results ({results.length})</h5>
          </div>
          <div className="card-body" style={{ maxHeight: '600px', overflowY: 'auto' }}>
            {results.length === 0 ? (
              <p className="text-muted">No results yet. Click a test button above.</p>
            ) : (
              results.map((result, idx) => (
                <div key={idx} className="mb-3 p-2 border rounded" style={{ backgroundColor: result.status === 'ERROR' ? '#ffe0e0' : '#e0f0e0' }}>
                  <div className="d-flex justify-content-between align-items-center mb-2">
                    <div>
                      <strong>{result.name}</strong>
                      <small className="text-muted ms-2">{result.timestamp}</small>
                    </div>
                    <span className={`badge ${result.status === 'ERROR' ? 'bg-danger' : 'bg-success'}`}>
                      {result.status}
                    </span>
                  </div>
                  <div className="small text-muted mb-2">
                    {result.method} {result.path}
                  </div>
                  <pre style={{ fontSize: '0.85rem', maxHeight: '200px', overflowY: 'auto', backgroundColor: '#f8f9fa', padding: '8px', borderRadius: '4px' }}>
                    {result.response}
                  </pre>
                </div>
              ))
            )}
          </div>
        </div>

        {/* Info Box */}
        <div className="alert alert-info mt-4" role="alert">
          <h5 className="alert-heading">Debugging Information</h5>
          <ul className="mb-0 small">
            <li>Backend URL: <code>http://localhost:8000</code></li>
            <li>API Base: <code>http://localhost:8000/api</code></li>
            <li>Check browser console for detailed error messages</li>
            <li>Ensure Laravel backend is running on port 8000</li>
            <li>Verify database connection in Laravel</li>
          </ul>
        </div>
      </div>
    </main>
  )
}
