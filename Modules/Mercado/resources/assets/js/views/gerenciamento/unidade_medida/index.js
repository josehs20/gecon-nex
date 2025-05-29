import * as gerais from '@/gerais.js';

const columns = [
    ['id', 'ID'],
    ['descricao', 'Nome'],
    ['sigla', 'Sigla'],
    ['pode_ser_float', 'Pode ser fracionado'],
    ['acao', 'Ação', false, false]
];
var getUnidadeMedidas = $('#dataView').data('getUnidadeMedidas');
gerais.montaDatatableYajra('tabela-unidade-media', columns, getUnidadeMedidas);


