import { Outlet } from 'react-router-dom'
import Navbar from '../shared/Navbar'
import CartOffcanvas from '../shared/CartOffcanvas'

export default function RootLayout(){
  return (
    <>
      <Navbar />
      <Outlet />
      <CartOffcanvas />
    </>
  )
}
