import {montaDatatableYajra} from '@/gerais.js';
const columns = [
    ['id', '#'],
    ['nome', 'Nome'],
    ['loja_id', 'Loja'],
    ['ativo', 'Ativo'],
    ['acao', 'Ação', false, false],
];

const routeGetCaixas = $('#objetoView').data('routeYajraCaixaGet');


montaDatatableYajra("tabela-caixas", columns, routeGetCaixas);
