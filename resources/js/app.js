import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'popper.js';
import 'select2';  // IMPORTANTE: isso adiciona o método select2 ao jQuery
import 'select2/dist/css/select2.min.css';
import dt from 'datatables.net-bs5';  // ou datatables.net
import { initSidebar } from './vendor/siedBar/js/main.js';
// Importa o script geral
// import './gerais.js';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';

// Importa o script específico da view admin/admin.js

console.log('jquery funcionando');

$(document).ready(function() {
    $('.collapse').on('show.bs.collapse', function () {
        // Remove visibility: collapse quando abrir
        $(this).css('visibility', 'visible');

        $(this).prev('a').find('.toggle-icon')
          .removeClass('bi-caret-up')
          .addClass('bi-caret-down');
      });

      $('.collapse').on('hide.bs.collapse', function () {
        // Aplica visibility: collapse quando fechar
        $(this).css('visibility', 'collapse');

        $(this).prev('a').find('.toggle-icon')
          .removeClass('bi-caret-down')
          .addClass('bi-caret-up');
      });
    // Seu logout, sidebar etc
    $('#logout-button').on('click', function() {
      if (confirmLogountComCaixa) {
        $('#logoutModal').modal('show');
      } else {
        logout();
      }
    });

    initSidebar();
  });
