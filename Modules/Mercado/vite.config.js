import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.js', // seu arquivo js de entrada
      refresh: true, // habilita hot reload
    }),
  ],
  build: {
    rollupOptions: {
      // Pode configurar para externalizar dependências se quiser,
      // mas para jQuery queremos que entre no bundle, então nada especial aqui
    },
  },
  optimizeDeps: {
    include: ['jquery'], // força pré-bundle do jquery
  }
});
