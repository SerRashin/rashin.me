import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react'

export default defineConfig({
    plugins: [react()],
    define: {
        'process.env': process.env,
        global: {},
    },
    server: {
        host: true,
        port: parseInt(process.env.FRONTEND_PUBLIC_PORT),
        strictPort: true
    },
    esbuild: {
        keepNames: true,
    },
    build: {
        sourcemap: true,
    },
});
