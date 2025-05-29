import * as gerais from '@/gerais.js';

var getPedidos = $('#dataView').data('getPedidos');
const columns = [
    ['id', 'ID'],
    ['usuario_id', 'Usuário'],
    ['status.descricao', 'Status'],
    ['data_limite', 'Data limite'],
    ['qtd_itens', 'Qtd Itens'],
    ['observacao', 'Observação'],
    ['acao', 'Ação', false, false]
];

gerais.montaDatatableYajra('tabela-pedidos', columns, getPedidos);

