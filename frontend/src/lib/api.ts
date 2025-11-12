export const API_BASE = 'http://localhost:8000/api'

export async function apiGet<T>(path: string, params?: Record<string, any>): Promise<T> {
  // Ensure path starts with /api or construct full URL
  let url: string
  if (path.startsWith('http')) {
    url = path
  } else if (path.startsWith('/api')) {
    url = `http://localhost:8000${path}`
  } else if (path.startsWith('/')) {
    url = `http://localhost:8000/api${path}`
  } else {
    url = `http://localhost:8000/api/${path}`
  }

  const urlObj = new URL(url)
  if (params) {
    Object.entries(params).forEach(([k, v]) => {
      if (v != null) {
        urlObj.searchParams.append(k, String(v))
      }
    })
  }

  try {
    console.log(`🔵 API GET: ${urlObj.toString()}`)
    const res = await fetch(urlObj.toString(), {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })

    if (!res.ok) {
      const errorText = await res.text()
      console.error(`❌ API Error: GET ${urlObj.toString()} ${res.status}`, errorText)
      throw new Error(`API Error: ${res.status} ${res.statusText}`)
    }

    const data = await res.json()
    console.log(`✅ API Response:`, data)
    return data as T
  } catch (error) {
    console.error(`❌ Failed to fetch ${url}:`, error)
    throw error
  }
}

export async function apiPost<T>(path: string, body?: Record<string, any>): Promise<T> {
  let url: string
  if (path.startsWith('http')) {
    url = path
  } else if (path.startsWith('/api')) {
    url = `http://localhost:8000${path}`
  } else if (path.startsWith('/')) {
    url = `http://localhost:8000/api${path}`
  } else {
    url = `http://localhost:8000/api/${path}`
  }

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: body ? JSON.stringify(body) : undefined
    })

    if (!res.ok) {
      const errorText = await res.text()
      console.error(`API Error: POST ${url} ${res.status}`, errorText)
      throw new Error(`API Error: ${res.status} ${res.statusText}`)
    }

    const data = await res.json()
    return data as T
  } catch (error) {
    console.error(`Failed to post ${url}:`, error)
    throw error
  }
}
