import {montaDatatableYajra} from '@/gerais.js';
const columns = [
    ['id', '#'],
    ['descricao', 'Nome'],
    ['acao', 'Ação', false, false]
];
var routeGetClassificacaoProduto = $('#dataView').data('routeGetclassificacaoProduto');

montaDatatableYajra('tabela-classificacoes', columns, routeGetClassificacaoProduto);
