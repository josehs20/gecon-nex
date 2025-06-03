import * as gerais from '@/gerais.js';

const csrfToken = $('meta[name="csrf-token"]').attr('content');

const rotas = {
    finalizarVenda: $('#dataView').data('rotaFinalizarVenda'),
    devolucaoVenda: $('#dataView').data('rotaDevolucaoVenda'),
    orcamentoVenda: $('#dataView').data('rotaOrcamentoVenda'),
};

function ajaxPost(button, url, requestData, texto = null) {
    const $btn = $(button);
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrfToken,
            data: requestData
        },
        beforeSend: function () {
            $btn.prop('disabled', true).text('Aguarde...');
        },
        success: function (response) {
            // Pode redirecionar ou limpar campos aqui
        },
        error: function (xhr) {
            console.error(`Erro ao ${texto.toLowerCase()}:`, xhr);
        },
        complete: function () {
            $btn.prop('disabled', false).text(texto);
        }
    });
}

function montaVendaRequest() {
    return {
        cliente_id: 1,
        desconto_percentual: 10,
        formas_pagamento: [
            { id: 1, valor: 100000 },
            { id: 5, valor: 180082, parcelas: 3 }
        ]
    };
}

function montaRequestDevolucaoVenda() {
    return {
        vendaId: 41,
        forma_pagamento_devolucao: 1,
        itens_quantidades: JSON.stringify([
            { estoqueId: 1, quantidade: '1.551' },
            { estoqueId: 2, quantidade: '40.299' }
        ]),
        motivo: 'motivo teste'
    };
}

function montaRequestOrcamento() {
    return {
        forma_pagamento: null,
        cliente_id: 1 ,
        desconto_porcentagem : 10,
        descricao: 'teste'
    };
}

$('#finalizaVenda').on('click', function () {
    ajaxPost(this, rotas.finalizarVenda, montaVendaRequest(), 'Finalizar Venda');
});

$('#devolucaoVenda').on('click', function () {
    ajaxPost(this, rotas.devolucaoVenda, montaRequestDevolucaoVenda(), 'Devolução Venda');
});

$('#orcamentoVenda').on('click', function () {
    ajaxPost(this, rotas.orcamentoVenda, montaRequestOrcamento(), 'Orçamento Venda');
});

