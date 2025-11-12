export type Category = {
  id?: number | string
  slug: string
  name: string
  description?: string
  image?: string
}

export type Product = {
  id: number | string
  slug?: string
  name: string
  price?: number
  description?: string
  image?: string
  images?: string[]
  attributes?: Record<string, any>
  category?: string | { name?: string; slug?: string }
}
