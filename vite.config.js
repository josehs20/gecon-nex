import fg from 'fast-glob';

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
//busca automaticamente os arquivos js e css nas pastas
const rootResources = fg.sync(['resources/**/*.{js,css}']);
const modulesResources = fg.sync(['Modules/**/resources/**/*.{js,css}']);

const allInputs = [...rootResources, ...modulesResources];
export default defineConfig({
    plugins: [
        laravel({
            input: allInputs,
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
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@css': path.resolve(__dirname, 'resources/css'),
        },
    },
});
