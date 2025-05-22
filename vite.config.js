import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                'resources/js/views/admin/admin.js',
                'resources/js/views/admin/gtins.js',
                'resources/js/views/admin/permissoes.js',
                'resources/js/views/admin/form_empresa.js',
                'resources/js/views/lojas/form_lojas.js',
            ],
            refresh: true,
            // o segredo está aqui:
            buildDirectory: 'build', // força o manifest.json a ir direto pra public/build
        }),
        tailwindcss(),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',          // Gera arquivos em public/build
        assetsDir: 'assets',             // Subpasta para arquivos estáticos
        emptyOutDir: true,               // Limpa antes de cada build
    },
});
