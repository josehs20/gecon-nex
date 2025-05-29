import * as gerais from '@/gerais.js';

var getEstoques = $('#dataView').data('getEstoques');
const columns = [
    ['id', 'ID'], // Coluna ID
    ['produto@nome', 'Produto'],
    ['produto@fabricante@nome', 'Fabricante'],
    ['quantidade_total', 'Qtd Total'],
    ['quantidade_disponivel', 'Qtd Disponível'],
    ['quantidade_minima', 'Qtd Mínima'],
    ['quantidade_maxima', 'Qtd Máxima'],
    ['localizacao', 'Localização'],
    ['acao', 'Ação'] // Ação
];

gerais.montaDatatableYajra('tabela-estoque', columns, getEstoques);

