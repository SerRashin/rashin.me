import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { resolve } from 'path'


// https://vitejs.dev/config/
export default defineConfig({
    plugins: [react()],
    define: {
        'process.env': process.env,
    },
    server: {
        host: true,
        port: parseInt(process.env.FRONTEND_ADMIN_PORT),
        strictPort: true
    },
    base: '/admin',
    esbuild: {
        keepNames: true,
    },
    build: {
        sourcemap: true,
    },
});
