import * as gerais from '@/gerais.js';

var getCompras = $('#dataView').data('getCompras');
const columns = [
    ['id', 'ID'],
    ['usuario_id', 'Usuário'],
    ['status.descricao', 'Status'],
    ['cotacao.data_abertura', 'Data criação'],
    ['cot_fornecedor.previsao_entrega', 'Previsão entrega'],
    ['cot_fornecedor.observacao', 'Descrição'],
    ['acao', 'Ação', false, false]
];

gerais.montaDatatableYajra('tabela-compras', columns, getCompras);

