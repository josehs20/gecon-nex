import * as gerais from '@/gerais.js';

var getCotacoes = $('#dataView').data('getCotacoes');
const columns = [
    ['id', 'ID'],
    ['usuario_id', 'Usuário'],
    ['status.descricao', 'Status'],
    ['data_abertura', 'Data criação'],
    ['data_encerramento', 'Data limite'],
    ['descricao', 'Descrição'],
    ['acao', 'Ação', false, false]
];

gerais.montaDatatableYajra('tabela-cotacoes', columns, getCotacoes);

