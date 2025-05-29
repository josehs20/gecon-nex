import * as gerais from '@/gerais.js';

var routeRealizarCompra = $('#dataView').data('routeRealizarCompra');
var cotacao = $('#dataView').data('cotacao');
var podeAlterar = $('#dataView').data('podeAlterar');
var compra = $('#dataView').data('compra');

gerais.maskDinheiroByClass('preco-unitario');
gerais.maskDinheiroByClass('total-item');
gerais.maskDinheiroByClass('frete-fornecedor');
gerais.maskDinheiroByClass('desconto-fornecedor');
carregaMelhoresCondicoes();

function carregaMelhoresCondicoes() {
    const fornecedores = cotacao.cot_fornecedores;
    let fornecedorClick = null;
    if (!fornecedores || fornecedores.length === 0) return;

    let fornecedorMaisBarato = fornecedores.reduce((menor, atual) => {
        const totalAtual = atual.total;
        const totalMenor = menor.total;
        return totalAtual < totalMenor ? atual : menor;
    });

    console.log(fornecedorMaisBarato);

    let id = fornecedorMaisBarato.fornecedor_id;

    if (compra) {
        fornecedorClick = compra.cot_fornecedor.fornecedor_id;
        $(`#titulo-fornecedor-${fornecedorClick}`).append(
            `<div class="text-success" style="text-decoration: underline;">
        <i class="bi bi-currency-dollar"></i> Comprado
    </div>`
        );

    } else {
        gerais.msgToastr('Melhor compra selecionada.', 'info');

        fornecedorClick = id;
    }

    const btn = $(`[data-fornecedor-id="${id}"] button[data-bs-toggle="tooltip"]`);
    $(`#titulo-fornecedor-${id}`).append(
        '<div class="text-warning" style="text-decoration: underline;"> ⭐ Melhor preço</div>'
    );

    if (btn.length === 0) return;

    const total = gerais.centavosParaReais(fornecedorMaisBarato.total);
    const entrega = fornecedorMaisBarato.previsao_entrega ?
        new Date(fornecedorMaisBarato.previsao_entrega).toLocaleDateString('pt-BR') :
        'Sem data';

    const texto = `⭐ Melhor preço <br> Entrega: ${entrega}<br> Total: ${total}`;

    btn.tooltip('dispose');
    btn.attr('title', texto).tooltip({
        trigger: 'hover',
        placement: 'top',
        html: true,
    }).tooltip('show');

    setTimeout(() => {
        $(`[data-fornecedor-id="${fornecedorClick}"]`).trigger('click')
    }, 500);

    $(`#seleciona-fornecedor-${fornecedorClick}`).prop('checked', true);

}
$(document).on('click', '[data-cancelar-compra]', function() {
    const compraId = $(this).data('cancelar-compra');
    cancelarCompra(compraId);
});

function cancelarCompra() {
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
            $('#formMotivoCancelamento').val(result.value);
            $('#cancelarCompra').submit();
        }
    });
}

// Função para calcular o total de um item
function calcularTotalItem($input) {
    const precoUnitario = parseFloat($input.val().replace(',', '.')) || 0;
    const quantidade = parseFloat($input.data('quantidade')) || 0;
    const total = precoUnitario * quantidade;
    const $totalItem = $input.closest('tr').find('.total-item');
    $totalItem.val(total.toFixed(2).replace('.', ','));

    // Atualiza o total e os pendentes do fornecedor
    const fornecedorId = $input.data('fornecedor-id');
    atualizarTotalFornecedor(fornecedorId);
    atualizarPendentes(fornecedorId);
}

// Função para atualizar o Subtotal e Total do fornecedor
function atualizarTotalFornecedor(fornecedorId) {
    let totalFornecedor = 0;
    $(`#tabela-${fornecedorId} .total-item`).each(function () {
        const valor = parseFloat($(this).val().replace(',', '.')) || 0;
        totalFornecedor += valor;
    });

    let desconto = parseFloat($(`#desconto-${fornecedorId}`).val().replace(',', '.')) || 0;
    let frete = parseFloat($(`#frete-${fornecedorId}`).val().replace(',', '.')) || 0;

    let subTotal = totalFornecedor;
    totalFornecedor = (subTotal - desconto) + frete;

    const $subTotalFornecedor = $(`.sub-total-fornecedor[data-fornecedor-id="${fornecedorId}"]`);
    const $totalFornecedor = $(`.total-fornecedor[data-fornecedor-id="${fornecedorId}"]`);
    $subTotalFornecedor.val(subTotal.toFixed(2).replace('.', ','));
    $totalFornecedor.val(totalFornecedor.toFixed(2).replace('.', ','));
}

// Função para atualizar a quantidade de itens pendentes
function atualizarPendentes(fornecedorId) {
    let pendentes = 0;
    $(`#tabela-${fornecedorId} .preco-unitario`).each(function () {
        const valor = $(this).val().trim();
        if (!valor || parseFloat(valor.replace(',', '.')) === 0) {
            pendentes++;
        }
    });
    $(`#pendentes-${fornecedorId}`).text(pendentes);
}

// Função para validar a cotação (usada apenas ao finalizar)
function validarCotacao() {
    for (const fornecedor of cotacao.cot_fornecedores) {
        const fornecedorId = fornecedor.fornecedor_id;
        const nomeFornecedor = fornecedor.fornecedor.nome || 'Fornecedor';

        // Verifica itens pendentes (preço unitário)
        let pendentes = 0;
        $(`#tabela-${fornecedorId} .preco-unitario`).each(function () {
            const valor = $(this).val().trim();
            if (!valor || parseFloat(valor.replace(',', '.')) === 0) {
                pendentes++;
            }
        });
        if (pendentes > 0) {
            return {
                erro: `Fornecedor ${nomeFornecedor}: ${pendentes} item(s) com preço unitário não preenchido(s).`,
                fornecedorId
            };
        }

        // Verifica previsão de entrega
        const $previsaoEntrega = $(`#previsao-entrega-${fornecedorId}`);
        if (!$previsaoEntrega.val()) {
            return {
                erro: `Fornecedor ${nomeFornecedor}: Previsão de entrega não preenchida.`,
                fornecedorId
            };
        }

        // Verifica se o total é negativo
        let subTotal = 0;
        $(`#tabela-${fornecedorId} .total-item`).each(function () {
            const valor = parseFloat($(this).val().replace(',', '.')) || 0;
            subTotal += valor;
        });
        const desconto = parseFloat($(`#desconto-${fornecedorId}`).val().replace(',', '.')) || 0;
        const frete = parseFloat($(`#frete-${fornecedorId}`).val().replace(',', '.')) || 0;
        const totalFornecedor = subTotal - desconto + frete;

        if (totalFornecedor < 0) {
            return {
                erro: `Fornecedor ${nomeFornecedor}: O total não pode ser negativo.`,
                fornecedorId
            };
        }
    }
    return {
        erro: null,
        fornecedorId: null
    };
}

// Função para montar o objeto da cotação
function montarObjetoCotacao() {
    const cotacaoAtualizada = {
        ...cotacao
    };

    cotacaoAtualizada.cot_fornecedores.forEach(fornecedor => {
        const fornecedorId = fornecedor.fornecedor_id;

        const freteVal = $(`#frete-${fornecedorId}`).val();
        const frete = freteVal ? parseFloat(freteVal.replace(',', '.')) || null : null;

        const descontoVal = $(`#desconto-${fornecedorId}`).val();
        const desconto = descontoVal ? parseFloat(descontoVal.replace(',', '.')) || null : null;

        const previsaoEntrega = $(`#previsao-entrega-${fornecedorId}`).val() || null;
        const observacao = $(`#observacao-${fornecedorId}`).val()?.trim() || null;

        let totalItens = 0;
        $(`#tabela-${fornecedorId} .total-item`).each(function () {
            const valorVal = $(this).val();
            const valor = valorVal ? parseFloat(valorVal.replace(',', '.')) || 0 : 0;
            totalItens += valor;
        });

        fornecedor.frete = frete;
        fornecedor.desconto = desconto;
        fornecedor.previsao_entrega = previsaoEntrega;
        fornecedor.observacao = observacao;
        fornecedor.subTotal = totalItens > 0 ? totalItens : null;
        fornecedor.total = totalItens > 0 ? (totalItens + frete) - desconto : null;

        fornecedor.cot_for_itens.forEach(item => {
            const $precoUnitario = $(
                `#tabela-${fornecedorId} .preco-unitario[data-item-id="${item.pedido_item_id}"]`
            );
            const precoUnitarioVal = $precoUnitario.val();
            const precoUnitario = precoUnitarioVal ? parseFloat(precoUnitarioVal.replace(',',
                '.')) || null : null;
            item.preco_unitario = precoUnitario;
        });
    });

    return cotacaoAtualizada;
}

// Monitora mudanças no preço unitário
$(document).on('input', '.preco-unitario', function () {
    calcularTotalItem($(this));
});

// Monitora mudanças no frete e desconto
$(document).on('input', '.frete-fornecedor', function () {
    const fornecedorId = $(this).data('fornecedor-id');
    atualizarTotalFornecedor(fornecedorId);
});

$(document).on('input', '.desconto-fornecedor', function () {
    const $input = $(this);
    const fornecedorId = $input.data('fornecedor-id');
    const desconto = parseFloat($input.val().replace(',', '.')) || 0;
    let subTotal = 0;
    $(`#tabela-${fornecedorId} .total-item`).each(function () {
        const valor = parseFloat($(this).val().replace(',', '.')) || 0;
        subTotal += valor;
    });
    const frete = parseFloat($(`#frete-${fornecedorId}`).val().replace(',', '.')) || 0;
    const totalFornecedor = subTotal - desconto + frete;

    if (totalFornecedor < 0) {
        gerais.msgToastr('O desconto não pode tornar o total negativo.', 'warning');
        $input.val('0,00'); // Redefine o desconto para 0
        atualizarTotalFornecedor(fornecedorId); // Atualiza com o desconto redefinido
    } else {
        atualizarTotalFornecedor(fornecedorId);
    }
});

$(document).on('change', '.fornecedor-selecionado-checkbox', function () {
    $('.fornecedor-selecionado-checkbox').not(this).prop('checked', false);
});

// Evento de clique no botão Finalizar Cotação
$('#btn_finalizar_cotacao').on('click', function () {
    const checkboxSelecionado = $('.fornecedor-selecionado-checkbox:checked');

    if (checkboxSelecionado.length === 0) {
        gerais.msgToastr('Selecione um fornecedor primeiro', 'warning');
        return;
    }

    const fornecedorId = checkboxSelecionado.val();
    const nome = $(`#titulo-fornecedor-${fornecedorId}`).html();
    const subtotal = $(`#subtotal-${fornecedorId}`).val();
    const frete = $(`#frete-${fornecedorId}`).val();
    const desconto = $(`#desconto-${fornecedorId}`).val();
    const total = $(`#total-${fornecedorId}`).val();

    $('#modalFornecedorNome').html(nome);
    $('#modalSubtotal').text('R$ ' + subtotal);
    $('#modalFrete').text('R$ ' + frete);
    $('#modalDesconto').text('R$ ' + desconto);
    $('#modalTotal').text('R$ ' + total);
    $('#modalFinalizarCotacao').modal('show');
});

$('#btn_finalizar_compra').on('click', function () {
    const checkboxSelecionado = $('.fornecedor-selecionado-checkbox:checked');

    if (checkboxSelecionado.length === 0) {
        gerais.msgToastr('Selecione um fornecedor primeiro', 'warning');
        return;
    }

    const fornecedorId = checkboxSelecionado.val();
    const especie_pagamento_id = $('#forma_pagamento').val();
    const cot_fornecedor = cotacao.cot_fornecedores.find(cf => cf.fornecedor_id == fornecedorId);
    $('#cot_fornecedor_id').val(cot_fornecedor.id);
    $('#especie_pagamento_id').val(especie_pagamento_id);
    $('#formComprar').submit();
});
$('.mostrar-cotacao').on('click', function () {
    const fornecedorId = $(this).data('fornecedor-id');
    mostrarCotacaoFornecedor(fornecedorId);
});
// Função para exibir o card do fornecedor selecionado
function mostrarCotacaoFornecedor(fornecedorId) {
gerais.bloquear();
    $('.cotacao-fornecedor').addClass('d-none');
    $('.fornecedor-card').removeClass('fornecedor-selecionado');
    $(`#cotacao-fornecedor-${fornecedorId}`).removeClass('d-none');
    $(`.fornecedor-card[data-fornecedor-id="${fornecedorId}"]`).addClass('fornecedor-selecionado');

    const $cardCotacao = $(`#cotacao-fornecedor-${fornecedorId}`);
    if ($cardCotacao.length) {
        const posicao = $cardCotacao.offset().top - 20;
        $('html, body').animate({
            scrollTop: posicao
        }, 500);
    }

    atualizarTotalFornecedor(fornecedorId);
    atualizarPendentes(fornecedorId);

    setTimeout(() => {
        gerais.desbloquear();
    }, 500);
}

// Inicializa as DataTables e pendentes
cotacao.cot_fornecedores.forEach(function (element) {
    gerais.montaDatatable('tabela-' + element.fornecedor_id);
    atualizarPendentes(element.fornecedor_id);
    // Inicializa os totais dos itens
    $(`#tabela-${element.fornecedor_id} .preco-unitario`).each(function () {
        calcularTotalItem($(this));
    });
});

// Seleciona o primeiro fornecedor ao carregar a página
if (cotacao.cot_fornecedores.length > 0) {
    var primeiroFornecedorId = cotacao.cot_fornecedores[0].fornecedor_id;
    mostrarCotacaoFornecedor(primeiroFornecedorId);
}
