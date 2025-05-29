
import * as gerais from '@/gerais.js';

    $(document).ready(function() {
        renderizarTabela();
    });

    function renderizarTabela() {
        gerais.montaDatatable('view-tabela-listagem-cotacoes');
    }
