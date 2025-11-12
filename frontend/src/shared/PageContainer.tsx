import { PropsWithChildren } from 'react'

export default function PageContainer({ children }: PropsWithChildren){
  return <div className="container py-3">{children}</div>
}
