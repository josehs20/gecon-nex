import * as gerais from '@/gerais.js';

var getBalancos = $('#dataView').data('getBalanco');
const columns = [
    ['id', 'ID'],
    ['usuario_id', 'Usuário'],
    ['status.descricao', 'Status'],
    ['qtd_itens', 'Qtd itens'],
    ['created_at', 'Data de criação'],
    ['observacao', 'Observação'],
    ['acao', 'Ação', false, false],
];

gerais.montaDatatableYajra('tabela-balanco', columns, getBalancos);
