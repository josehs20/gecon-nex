import { montaDatatableYajra } from '../../gerais.js';

const routeGetEmpresas = '/yajra/empresas/get';
const columns = [
    ['id', '#'],
    ['nome_fantasia', 'Nome'],
    ['cnpj', 'CNPJ'],
    ['ativo', 'Ativo'],
    ['acao', 'Ação'],
];

montaDatatableYajra("tabela-home-admin", columns, routeGetEmpresas);
