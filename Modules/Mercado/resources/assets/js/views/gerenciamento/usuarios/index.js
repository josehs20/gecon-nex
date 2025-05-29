import * as gerais from '@/gerais.js';

const ROTA = $('#dataView').data('rotaUsuariosObter');
gerais.montaDatatable("tabela-listagem-usuarios-mercado", ROTA);
