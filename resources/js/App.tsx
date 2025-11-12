import { Suspense } from 'react'
import { RouterProvider } from 'react-router-dom'
import router from './router'

export default function App() {
  return (
    <Suspense fallback={<div className="container py-5">Loading…</div>}>
      <RouterProvider router={router} />
    </Suspense>
  )
}
