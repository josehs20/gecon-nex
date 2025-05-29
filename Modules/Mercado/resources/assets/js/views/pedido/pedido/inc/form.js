import * as gerais from '@/gerais.js';
var urlGetProdutos = $('#dataView').data('getProdutos'); // Reutilizando a rota de produtos do estoque
var itens_no_pedido = $('#dataView').data('itensNoPedido');
var pedido = $('#dataView').data('pedido');
var itensPedido = [];
if (itens_no_pedido) {
    formataPedidoItens(itens_no_pedido, true);

} else if (pedido && pedido.pedido_itens.length > 0) {
    formataPedidoItens(pedido.pedido_itens);
}

function formataPedidoItens(itens, session = false) {
    let itensFormatados = [];
    if (session == true) {

        itensFormatados = itens;
    } else {
        itens.forEach(item => {
            let nomeProduto = gerais.montaNomeProduto(item.estoque.produto);
            let estoqueId = item.estoque_id;
            let quantidade = item.quantidade_pedida;
            let status = item.status.descricao
            itensFormatados.push({
                estoqueId,
                nomeProduto,
                quantidade,
                status
            });
        });
    }

    itensPedido = itensFormatados;

    atualizarTabelaPedido();
}
$(document).ready(function () {
    gerais.montaDatatable('tabela-item-pedido');
    gerais.constructSelect2('estoque_id', urlGetProdutos);
    gerais.maskQtd('quantidade');


    // Adicionar item ao pedido
    $('#adicionar-item-pedido').submit(function (e) {
        e.preventDefault();

        const estoqueId = $('#estoque_id').val();
        const nomeProduto = $('#estoque_id option:selected').text();
        const quantidade = gerais.converteParaFloat($('#quantidade').val());
        const status = 'ABERTO';
        if (!estoqueId || !quantidade) {
            gerais.msgToastr('Verifique os campos obrigatórios.', 'info');
            return;
        }

        const indexExistente = itensPedido.findIndex(item => item.estoqueId == estoqueId);
        if (indexExistente !== -1) {
            gerais.msgToastr('Item atualizado.', 'success');
            itensPedido[indexExistente] = {
                estoqueId,
                nomeProduto,
                quantidade,
                status
            };
        } else {
            gerais.msgToastr('Item adicionado.', 'success');
            itensPedido.push({
                estoqueId,
                nomeProduto,
                quantidade,
                status
            });
        }

        atualizarTabelaPedido();
        $('#adicionar-item-pedido')[0].reset();
        $('#estoque_id').empty();
        $('#estoque_id').focus();
    });

    // Botões de salvar e finalizar
    $('#btn_salvar_pedido, #btn_finalizar_pedido, #btn_alterar_pedido').on('click', function () {
        var observacao = $('#observacao')[0];
        var confirmacaoPedido = $('#confirmacaoPedido')[0];
        var isFinalizar = $(this).attr('id') === 'btn_finalizar_pedido';
        var isAlterar = $(this).attr('id') === 'btn_alterar_pedido';
        console.log(isAlterar);

        var data = $('#data_limite').val();
        if (data == '') {
            gerais.msgToastr('Selecione a data limite..', 'info');
            $('#data_limite').focus();
            return
        }

        if (!itensPedido.length) {
            gerais.msgToastr('Nenhum item adicionado.', 'info');
            return;
        }
        if (!observacao.checkValidity()) {
            observacao.reportValidity();
            gerais.msgToastr('Campo observação obrigatório', 'info');
        } else if (!isAlterar && !confirmacaoPedido.checked) {
            confirmacaoPedido.reportValidity();
            gerais.msgToastr('Você precisa confirmar o pedido', 'info');
        } else {
            $('#itens').val(JSON.stringify(itensPedido));
            $('#finalizar').val(isFinalizar ? 'true' : 'false');
            $('#finalizar_pedido_post').submit();
        }
    });
});

function atualizarTabelaPedido() {
    const tbody = $('#tabela-item-pedido tbody');
    tbody.empty();

    itensPedido.forEach((item, index) => {

        let botao = $('#dataView').data('botao');

        if (botao) {
            botao =
                `<td><button type="button" class="btn btn-danger btn-sm btn-delete" data-estoque-id="${item.estoqueId}"><i class="bi bi-trash"></i></button></td>`
        }
        const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nomeProduto}</td>
                    <td>${item.quantidade}</td>
                    <td>${item.status}</td>
                ${botao}
                </tr>
            `;
        tbody.append(row);
    });
}
$(document).on('click', '.btn-delete', function () {
    const estoqueId = $(this).data('estoque-id');
    confirmDelete(estoqueId);
    gerais.msgToastr('Item excluído com sucesso', 'success');
});
function confirmDelete(estoqueId) {
    Swal.fire({
        title: 'Você tem certeza?',
        text: "Esta ação não poderá ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            cancelButton: 'btn btn-secondary mx-1',
            confirmButton: 'btn btn-dark',
        },
        buttonsStyling: false,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sim, excluir!',
    }).then((result) => {
        if (result.isConfirmed) {
            itensPedido = itensPedido.filter(item => item.estoqueId != estoqueId);
            atualizarTabelaPedido();
        }
    });
}
$(document).on('click', '[data-cancelar-pedido]', function() {
    const pedidoId = $(this).data('cancelar-pedido');
    cancelarPedido(pedidoId);
});
function cancelarPedido() {

    Swal.fire({
        title: 'Você tem certeza?',
        text: "Esta ação não poderá ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            cancelButton: 'btn btn-secondary mx-1',
            confirmButton: 'btn btn-dark',
        },
        buttonsStyling: false,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sim, cancelar!',
        html: '<input id="motivoCancelamento" class="swal2-input" placeholder="Digite o motivo do cancelamento" required>',
        preConfirm: () => {
            const motivo = document.getElementById('motivoCancelamento').value;
            if (!motivo) {
                Swal.showValidationMessage('O motivo do cancelamento é obrigatório');
                return false;
            }
            return motivo;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Adiciona o motivo ao input do formulário
            $('#formMotivoCancelamento').val(result
                .value); // Certifique-se que o input no formulário tem id="formMotivoCancelamento"
            $('#cancelarPedido').submit();
        }
    });

}
