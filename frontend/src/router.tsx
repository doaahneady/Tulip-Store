import { createBrowserRouter } from 'react-router-dom'
import RootLayout from './layouts/RootLayout'
import Home from './pages/Home'
import Categories from './pages/Categories'
import Category from './pages/Category'
import Product from './pages/Product'
import Search from './pages/Search'
import SignIn from './pages/SignIn'
import Debug from './pages/Debug'

const router = createBrowserRouter([
  {
    path: '/',
    element: <RootLayout />,
    children: [
      { index: true, element: <Home /> },
      { path: 'categories', element: <Categories /> },
      { path: 'category/:slug', element: <Category /> },
      { path: 'product/:idOrSlug', element: <Product /> },
      { path: 'search', element: <Search /> }
    ]
  },
  {
    path: '/signin',
    element: <SignIn />
  },
  {
    path: '/debug',
    element: <Debug />
  }
])

export default router
