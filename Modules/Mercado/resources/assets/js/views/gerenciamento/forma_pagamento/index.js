import * as gerais from '@/gerais.js';

const columns = [
    ['id', '#'],
    ['descricao', 'Nome'],
    ['loja.nome', 'Loja'],
    ['ativo', 'Ativo'],

];

var routeGetFormasPagamento = $('#dataView').data('routeGetFormasPagamento');

gerais.montaDatatableYajra('tabela-forma-pagamento', columns, routeGetFormasPagamento);
