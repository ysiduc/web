import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'

// https://vite.dev/config/
export default defineConfig({
  base: './',
  plugins: [react()],
  server: {
    port: 3000,
    proxy: {
      '/test/web_cty/api': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
