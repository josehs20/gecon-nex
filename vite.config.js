import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                'resources/js/views/admin/empresas/index.js',
                'resources/js/views/admin/usuarios/index.js',
                'resources/js/views/admin/usuarios/show.js',
                'resources/js/views/admin/gtin/index.js',
                'resources/js/views/admin/permissoes/index.js',
                'resources/js/views/admin/empresas/inc/form_empresa.js',
                'resources/js/views/admin/empresas/lojas/inc/form_lojas.js',
                //para o modulo mercado
                'Modules/Mercado/resources/assets/js/views/gerenciamento/caixa/index.js'
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
