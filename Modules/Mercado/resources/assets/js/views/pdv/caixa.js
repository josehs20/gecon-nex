import * as gerais from '@/gerais.js';
var rotaFinalizarVenda = $('#dataView').data('rotaFinalizarVenda');
var rotaDevolucaoVenda = $('#dataView').data('rotaDevolucaoVenda');
//-------------------finalizar venda-----------------//
$('#finalizaVenda').on('click', function () {
    $.ajax({
        url: rotaFinalizarVenda,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            data: montaRequestFinalizarVenda()
        },
        beforeSend: function () {
            $('#finalizaVenda').prop('disabled', true).text('Finalizando...');
        },
        success: function (response) {
            // alert('Venda finalizada com sucesso!');
            // Redireciona ou limpa os campos
        },
        error: function (xhr) {
            // alert('Erro ao finalizar venda!');
        },
        complete: function () {
            $('#finalizaVenda').prop('disabled', false).text('Finalizar Venda');
        }
    });
});

function montaRequestFinalizarVenda() {
    let cliente_id = 1;
    let desconto_percentual = 10;
    let formas_pagamento = [
        {
            id: 1,
            valor: 100000
        },
        {
            id: 5,
            valor: 180082,
            parcelas: 3
        }
    ];

    return {
        cliente_id: cliente_id,
        desconto_percentual: desconto_percentual,
        formas_pagamento: formas_pagamento
    }
}
//-------------------devolução venda-----------------//
$('#devolucaoVenda').on('click', function () {
    $.ajax({
        url: rotaDevolucaoVenda,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            data: montaRequestDevolucaoVenda()
        },
        beforeSend: function () {
            $('#devolucaoVenda').prop('disabled', true).text('Finalizando...');
        },
        success: function (response) {
            // alert('Venda finalizada com sucesso!');
            // Redireciona ou limpa os campos
        },
        error: function (xhr) {
            // alert('Erro ao finalizar venda!');
        },
        complete: function () {
            $('#finalizaVenda').prop('disabled', false).text('Finalizar Venda');
        }
    });
});

function montaRequestDevolucaoVenda() {
    let vendaId = 41;
    let forma_pagamento_devolucao = 1
    let itens_quantidades = JSON.stringify([
        {
            estoqueId: 1,
            quantidade: '1.551',
        },
        {
            estoqueId: 2,
            quantidade: '40.299',
        }
    ]);
    let motivo = 'motivo teste';
    return {
        vendaId: vendaId,
        forma_pagamento_devolucao: forma_pagamento_devolucao,
        itens_quantidades: itens_quantidades,
        motivo: motivo
    }
}



