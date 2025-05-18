// Importa jQuery
import $ from 'jquery';

// Deixa global para o resto do app poder usar
window.$ = window.jQuery = $;

$(document).ready(function() {
  console.log('jQuery version:', $.fn.jquery);
});

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
