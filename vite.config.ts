import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/main.tsx'],
      refresh: true,
    }),
    react(),
  ],
  server: {
    port: 5173,
    host: 'localhost',
    hmr: {
      host: 'localhost',
      port: 5173
    },
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true
      }
    }
  },
})
