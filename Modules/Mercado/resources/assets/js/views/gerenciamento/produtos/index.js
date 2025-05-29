import * as gerais from '@/gerais.js';

const columns = [
    ['id', 'ID'], // Coluna ID
    ['nome', 'Nome'], // Nome do produto
    ['loja_nome', 'Loja'], // Nome da loja
    ['custo', 'Custo'], // Custo
    ['preco', 'Preço'], // Preço
    ['fabricante_nome', 'Fabricante'], // Nome do fabricante
    ['cod_aux', 'Código Auxiliar'], // Código Auxiliar
    ['sigla', 'UN'], // Unidade de medida (Sigla)
    ['classificacao', 'Classificação'], // Classificação do produto
    ['acao', 'Ação', false, false], // Ação, com orderable e searchable como false
];
var getProdutosYajra = $('#dataView').data('getProdutosYajra');
gerais.montaDatatableYajra('tabela-produto', columns, getProdutosYajra);


