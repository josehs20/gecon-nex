import * as gerais from '@/gerais.js';
var urlGetProdutos = $('#dataView').data('urlGetProdutos');
var urlGetEstoque = $('#dataView').data('urlGetEstoque');
var podeAlterarAlgo = $('#dataView').data('podeAlterarAlgo');
var movimentacao = $('#dataView').data('movimentacao');
var itens_na_movimentacao = $('#dataView').data('itensNaMovimentacao');
// Inicializa o array global sempre

var itensMovimentacao = [];
if (itens_na_movimentacao) {
    formataMovimentacaoItens(itens_na_movimentacao, true);

} else if (movimentacao && movimentacao.movimentacao_estoque_itens.length > 0) {
    formataMovimentacaoItens(movimentacao.movimentacao_estoque_itens);
}

gerais.montaDatatable('tabela-movimentacao-item');
gerais.constructSelect2('estoque_id', urlGetProdutos);
gerais.maskQtd('quantidade_disponivel');
gerais.maskQtd('quantidade');

$('#btn_finalizar_movimentacao, #btn_salvar_movimentacao').on('click', function () {
    var observacao = $('#observacao')[0];
    var confirmacaoMovimentacao = $('#confirmacaoMovimentacao')[0];
    var isFinalizar = $(this).attr('id') === 'btn_finalizar_movimentacao';

    if (!itensMovimentacao.length) {
        gerais.msgToastr('Nenhum item adicionado.', 'info');
        return;
    }
    if (!observacao.checkValidity()) {
        observacao.reportValidity();
        gerais.msgToastr('Campo observação obrigatório', 'info');
    } else if (!confirmacaoMovimentacao.checked) {
        confirmacaoMovimentacao.reportValidity();
        gerais.msgToastr('Você precisa confirmar a movimentação', 'info');
    } else {
        $('#observacaoFinalizar').val(observacao.value);
        $('#itens').val(JSON.stringify(itensMovimentacao));
        $('input[name="finalizar"]').val(isFinalizar ? 'true' : 'false');
        $('#finalizar_movimentacao_post').submit();
    }
});

$('#estoque_id').change(function () {
    var selectedValue = $(this).val();

    $.ajax({
        url: urlGetEstoque,
        type: 'GET',
        data: {
            id: selectedValue
        },
        success: function (response) {
            var valor = response.estoque.quantidade_disponivel;
            valor = valor.replace(/[^0-9,\.]/g, '');
            $('#quantidade_disponivel').val(valor).trigger('input');
            $('#quantidade').val('').trigger('input');
            $('#quantidade').focus();

        },
        error: function (error) {
            gerais.msgToastr(error, 'error');
        }
    });
});

$('#form-movimentar-movimentacao').submit(function (e) {
    e.preventDefault();

    const estoqueId = $('#estoque_id').val();
    const nomeProduto = $('#estoque_id option:selected').text();
    const quantidadeDisponivel = gerais.converteParaFloat($('#quantidade_disponivel').val());
    const quantidadeMovimentar = gerais.converteParaFloat($('#quantidade').val());
    const tipoMovimentacao = $('#tipo_movimentacao').val();
    const tipoDescricao = $('#tipo_movimentacao option:selected').text();

    if (!estoqueId || !quantidadeMovimentar || !tipoMovimentacao) {
        gerais.msgToastr('Verifique os campos obrigatórios.', 'info');
        return;
    }

    if (quantidadeMovimentar > quantidadeDisponivel) {
        gerais.msgToastr('Quantidade saída maior que disponível.', 'info');
        return;
    }
    const indexExistente = itensMovimentacao.findIndex(item => item.estoqueId == estoqueId);
    if (indexExistente !== -1) {
        gerais.msgToastr('Item atualizado.', 'success');
        itensMovimentacao[indexExistente] = {
            estoqueId,
            nomeProduto,
            quantidadeDisponivel,
            quantidadeMovimentar,
            tipoMovimentacao,
            tipoDescricao
        };
    } else {
        gerais.msgToastr('Item adicionado.', 'success');
        itensMovimentacao.push({
            estoqueId,
            nomeProduto,
            quantidadeDisponivel,
            quantidadeMovimentar,
            tipoMovimentacao,
            tipoDescricao
        });
    }

    atualizarTabelaMovimentacao();
    $('#estoque_id').empty();
    $('#form-movimentar-movimentacao')[0].reset();
    $('#estoque_id').focus();

});

function formataMovimentacaoItens(itens, session = false) {
    let itensFormatados = [];
    if (session == true) {

        itensFormatados = itens;
    } else {

        itens.forEach(item => {
            let tipoMovimentacaoDescricao = $('#dataView').data('tipoMovimentacaoId');
            let nome = montaNomeProduto(item.estoque.produto);
            itensFormatados.push({
                estoqueId: item.estoque_id,
                nomeProduto: nome,
                quantidadeDisponivel: item.estoque.quantidade_disponivel,
                quantidadeMovimentar: item.quantidade_movimentada,
                tipoMovimentacao: item.tipo_movimentacao_estoque_id,
                tipoDescricao: item.tipo_movimentacao_id == tipoMovimentacaoDescricao ? 'ENTRADA' :
                    'SAÍDA'
            });
        });
    }

    itensMovimentacao = itensFormatados;

    atualizarTabelaMovimentacao();
}
$(document).on('click', '.btn-delete', function () {
    const estoqueId = $(this).data('estoque-id');
    confirmDelete(estoqueId);
});
function atualizarTabelaMovimentacao() {
    const tbody = $('#tabela-movimentacao-item tbody');
    tbody.empty();

    itensMovimentacao.forEach((item, index) => {
        let colunaAcao = podeAlterarAlgo ?
            `<td><button type="button" class="btn btn-danger btn-sm btn-delete" data-estoque-id="${item.estoqueId}"><i class="bi bi-trash"></i></button></td>` :
            '';
        const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${item.nomeProduto}</td>
                <td>${item.quantidadeDisponivel}</td>
                <td>${item.quantidadeMovimentar}</td>
                <td>${item.tipoDescricao}</td>
                ${colunaAcao}
            </tr>
        `;
        tbody.append(row);
    });
}

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
            removerItemMovimentacao(estoqueId);
        }
    });
}

function cancelarMovimentacao() {
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
    }).then((result) => {
        if (result.isConfirmed) {
            $('#cancelarMovimentacao').submit();
        }
    });
}

function removerItemMovimentacao(estoqueId) {
    itensMovimentacao = itensMovimentacao.filter(item => item.estoqueId != estoqueId);
    atualizarTabelaMovimentacao();
    gerais.msgToastr('Item removido com sucesso.', 'success');
}
